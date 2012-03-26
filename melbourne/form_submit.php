<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$method = $_GET["method"];

if ($method == "get")
{
	$id = $_GET["id"];
	$centre = $_GET["centre"];
	$date1 = date("Y-m-d");
	$date2 = date("Y-m-d", strtotime("+1 week"));
	
	mysql_connect('localhost','vericon','18450be');
	mysql_select_db('vericon');

	$q1 = mysql_query("SELECT COUNT(lead_id) FROM sales_customers WHERE lead_id = '$id' AND DATE(timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
	$check2 = mysql_fetch_row($q1);
	
	if (!preg_match("/^0[2378][0-9]{8}$/",$id))
	{
		echo "Invalid ID!";
	}
	elseif ($check2[0] != 0)
	{
		echo "Customer already submitted within this data period!";
	}
	else
	{
		echo "valid";
	}
}
elseif ($method == "load")
{
	$lead_id = $_GET["id"];
	$agent = $_GET["agent"];
	$centre = $_GET["centre"];
	$campaign = $_GET["campaign"];
	$type = $_GET["type"];
	
	$q3 = mysql_query("SELECT COUNT(lead_id) FROM sales_customers_temp WHERE lead_id = '$lead_id'");
	$num = mysql_fetch_row($q3);
	
	if ($num[0] == 0)
	{
		mysql_query("INSERT INTO sales_customers_temp (lead_id, agent, centre, campaign, type) VALUES ('$lead_id', '$agent', '$centre', '$campaign', '$type')") or die(mysql_error());
	}
	else
	{
		mysql_query("UPDATE sales_customers_temp SET agent = '$agent', type = '$type' WHERE lead_id = '$lead_id' AND centre = '$centre' LIMIT 1") or die(mysql_error());
	}
	
	echo "valid";
}
elseif ($method == "add")
{
	$lead_id = $_GET["id"];
	$cli = $_GET["cli"];
	$plan = $_GET["plan"];
	$week = date("W");
	
	$ch2 = mysql_query("SELECT COUNT(cli) FROM sales_packages_temp WHERE cli = '" . mysql_escape_string($cli) . "'");
	$check2 = mysql_fetch_row($ch2);
	
	$ch3 = mysql_query("SELECT COUNT(cli) FROM sct_dnc WHERE cli = '" . mysql_escape_string($cli) . "'");
	$check3 = mysql_fetch_row($ch3);
	
	$ch4 = mysql_query("SELECT COUNT(cli) FROM sales_packages WHERE cli = '" . mysql_escape_string($cli) . "' AND WEEK(timestamp) = '$week'");
	$check4 = mysql_fetch_row($ch4);
	
	if (!preg_match("/^0[2378][0-9]{8}$/",$cli))
	{
		echo "Invalid CLI";
	}
	elseif ($check3[0] != 0)
	{
		echo "CLI is on the SCT DNC list";
	}
	elseif ($check2[0] != 0 || $check4[0] != 0)
	{
		echo "CLI already added";
	}
	else
	{
		mysql_query("INSERT INTO sales_packages_temp (lead_id, cli, plan) VALUES ('$lead_id', '$cli', '$plan')") or die(mysql_error());
		echo "added";
	}
}
elseif ($method == "delete")
{
	$lead_id = $_GET["id"];
	$cli = $_GET["cli"];
	
	mysql_query("DELETE FROM sales_packages_temp WHERE lead_id = '$lead_id' AND cli = '$cli' LIMIT 1") or die(mysql_error());
	
	echo "deleted";
}
elseif ($method == "submit")
{
	$lead_id = $_GET["id"];
	$agent = $_GET["agent"];
	$centre = $_GET["centre"];
	$campaign = $_GET["campaign"];
	$type = $_GET["type"];
	$title = $_GET["title"];
	$first = strtoupper(substr($_GET["first"],0,1)) . strtolower(substr($_GET["first"],1));
	$middle = strtoupper(substr($_GET["middle"],0,1)) . strtolower(substr($_GET["middle"],1));
	$last = strtoupper(substr($_GET["last"],0,1)) . strtolower(substr($_GET["last"],1));
	$dob = $_GET["dob"];
	$email = $_GET["email"];
	$mobile = $_GET["mobile"];
	$billing = $_GET["billing"];
	$physical = $_GET["physical"];
	$postal = $_GET["postal"];
	$id_type = $_GET["id_type"];
	$id_num = $_GET["id_num"];
	$abn = preg_replace("/\s/","",$_GET["abn"]);
	$abn_status = $_GET["abn_status"];
	$position = $_GET["position"];
	
	$q4 = mysql_query("SELECT * FROM sales_packages_temp WHERE lead_id = '$lead_id'");
	
	function check_email_address($email) //email validation function
	{
		if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) 
		{
			return false;
		}
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++) 
		{
			if(!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",$local_array[$i]))
			{
				return false;
			}
		}
		if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1]))
		{
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2)
			{
				return false;
			}
			for ($i = 0; $i < sizeof($domain_array); $i++)
			{
				if(!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$",$domain_array[$i]))
				{
					return false;
				}
			}
		}
		if(!checkdnsrr($email_array[1],'MX'))
		{
			return false;
		}
		return true;
	} //end email check function 
	
	if ($lead_id == "" || $agent == "" || $centre == "" || $campaign == "" || $type == "")
	{
		echo "Error! Contact your administrator!";
	}
	elseif ($title == "")
	{
		echo "Please select a title";
	}
	elseif ($first == "")
	{
		echo "Please enter the customer's first name";
	}
	elseif ($last == "")
	{
		echo "Please enter the customer's last name";
	}
	elseif ($id_type == "Medicare Card" && !preg_match("/^[0-9]{10}$/",$id_num))
	{
		echo "Please enter a valid Medicare Card number";
	}
	elseif ($id_type == "Healthcare Card" && !preg_match("/^[0-9]{9}[a-zA-Z]$/",$id_num))
	{
		echo "Please enter a valid Healthcare Card number";
	}
	elseif ($id_type == "Pension Card" && !preg_match("/^[0-9]{9}[a-zA-Z]$/",$id_num))
	{
		echo "Please enter a valid Pension Card number";
	}
	elseif ($type == "Business" && $abn == "")
	{
		echo "Please enter the customer's ABN";
	}
	elseif ($type == "Business" && $abn_status != "Active")
	{
		echo "Please enter a valid ABN";
	}
	elseif (mysql_num_rows($q4) == 0)
	{
		echo "Please enter a package for the customer";
	}
	else
	{
		$pre_id = date("y") . str_pad(date("z"),3,"0",STR_PAD_LEFT);
		$q2 = mysql_query("SELECT COUNT(id) FROM sales_customers WHERE id LIKE '$pre_id%'");
		$num = mysql_fetch_row($q2);
		$random = (rand(0,9));
	
		$id = $pre_id . str_pad(($num[0]+1),3,"0",STR_PAD_LEFT) . $random;
		$timestamp = date("Y-m-d H:i:s");		
		
		mysql_query("INSERT INTO sales_customers (id,status,lead_id,timestamp,agent,centre,campaign,type,title,firstname,middlename,lastname,dob,email,mobile,billing,welcome,physical,postal,id_type,id_num,abn,position) VALUES ('$id','Queue','$lead_id','$timestamp','$agent','$centre','$campaign','$type','$title','" . mysql_escape_string($first) . "','" . mysql_escape_string($middle) . "','" . mysql_escape_string($last) . "','" . mysql_escape_string($dob) . "','" . mysql_escape_string($email) . "','" . mysql_escape_string($mobile) . "','$billing','$billing','$physical','$postal','" . mysql_escape_string($id_type) . "','" . mysql_escape_string($id_num) . "','" . mysql_escape_string($abn) . "','" . mysql_escape_string($position) . "')") or die(mysql_error());
		
		while ($p = mysql_fetch_assoc($q4))
		{
			mysql_query("INSERT INTO sales_packages (sid,cli,plan) VALUES ('$id','$p[cli]','$p[plan]')") or die(mysql_error());
		}
		
		mysql_query("DELETE FROM sales_packages_temp WHERE lead_id = '$lead_id'") or die(mysql_error());
		
		mysql_query("DELETE FROM sales_customers_temp WHERE lead_id = '$lead_id' AND centre = '$centre' LIMIT 1") or die(mysql_error());
		
		echo "submitted<br>Your Sale ID is <b>" . $id . "</b>";
	}
}
elseif ($method == "cancel")
{
	$lead_id = $_GET["id"];
	$centre = $_GET["centre"];
	
	mysql_query("DELETE FROM sales_customers_temp WHERE lead_id = '$lead_id' AND centre = '$centre' LIMIT 1") or die(mysql_error());
}

?>