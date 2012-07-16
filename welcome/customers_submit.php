<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];

if ($method == "call_back")
{
	$id = $_GET["id"];
	$time = date("Y-m-d") . " " . date("H:i:s", strtotime($_GET["time"]));
	
	mysql_query("INSERT INTO vericon.welcome_cb (id, time) VALUES ('$id', '$time') ON DUPLICATE KEY UPDATE time = '$time'") or die(mysql_error());
	
	echo "done";
}
elseif ($method == "reject")
{
	$id = $_GET["id"];
	$user = $_GET["user"];
	$reason = $_GET["reason"];
	$notes = trim($_GET["notes"]);
	$timestamp = date("Y-m-d H:i:s");
	
	$q = mysql_query("SELECT * FROM vericon.customers WHERE id = '$id'");
	$data = mysql_fetch_assoc($q);
	
	if ($id == "" || $user == "")
	{
		echo "Error! Contact your administrator!";
	}
	elseif ($reason == "")
	{
		echo "Please select a cancellation reason";
	}
	elseif ($notes == "")
	{
		echo "Please enter a detailed explaination for cancelling";
	}
	else
	{
		$note = $reason . " - " . $notes;
		
		mysql_query("INSERT INTO vericon.welcome (id, status, timestamp, user) VALUES ('$id', 'Cancel', '$timestamp', '$user')") or die(mysql_error());
		
		mysql_query("INSERT INTO vericon.customers_notes (id, user, timestamp, type, note) VALUES ('$id', '$user', '$timestamp', 'Cancelled', '" . mysql_real_escape_string($note) . "')") or die(mysql_error());
		
		mysql_query("UPDATE vericon.customers SET status = 'Cancelled', last_edit_by = '$user' WHERE id = '$id' LIMIT 1") or die(mysql_error());
		
		mysql_query("INSERT INTO vericon.customers_log (id, status, last_edit_by, industry, lead_id, sale_id, timestamp, agent, centre, campaign, type, title, firstname, middlename, lastname, dob, email, mobile, billing, welcome, promotions, physical, postal, id_type, id_num, abn, position, credit, payway, dd_type, billing_comments, other_comments) VALUES ('$data[id]', 'Cancelled', '$user', '$data[industry]', '$data[lead_id]', '$data[sale_id]', '$data[timestamp]', '$data[agent]', '$data[centre]', '$data[campaign]', '$data[type]', '" . mysql_real_escape_string($data["title"]) . "', '" . mysql_real_escape_string($data["firstname"]) . "', '" . mysql_real_escape_string($data["middlename"]) . "', '" . mysql_real_escape_string($data["lastname"]) . "', '" . mysql_real_escape_string($data["dob"]) . "', '" . mysql_real_escape_string($data["email"]) . "', '" . mysql_real_escape_string($data["mobile"]) . "', '$data[billing]', '$data[welcome]', '$data[promotions]', '$data[physical]', '$data[postal]', '" . mysql_real_escape_string($data["id_type"]) . "', '" . mysql_real_escape_string($data["id_num"]) . "', '" . mysql_real_escape_string($data["abn"]) . "', '" . mysql_real_escape_string($data["position"]) . "', '" . mysql_real_escape_string($data["credit"]) . "', '" . mysql_real_escape_string($data["payway"]) . "', '" . mysql_real_escape_string($data["dd_type"]) . "', '" . mysql_real_escape_string($data["billing_comments"]) . "', '" . mysql_real_escape_string($data["other_comments"]) . "')") or die(mysql_error());
		
		mysql_query("DELETE FROM vericon.welcome_lock WHERE id = '$id' LIMIT 1") or die(mysql_error());
		
		echo "rejected";
	}
}
elseif ($method == "approve")
{
	$id = $_GET["id"];
	$title = $_GET["title"];
	$first = trim(strtoupper(substr($_GET["first"],0,1)) . strtolower(substr($_GET["first"],1)));
	$middle = trim(strtoupper(substr($_GET["middle"],0,1)) . strtolower(substr($_GET["middle"],1)));
	$last = trim(strtoupper(substr($_GET["last"],0,1)) . strtolower(substr($_GET["last"],1)));
	$dob = $_GET["dob"];
	$email = $_GET["email"];
	$mobile = $_GET["mobile"];
	$physical = $_GET["physical"];
	$postal = $_GET["postal"];
	$id_type = $_GET["id_type"];
	$id_num = trim($_GET["id_num"]);
	$abn = preg_replace("/\s/","",$_GET["abn"]);
	$abn_status = $_GET["abn_status"];
	$position = trim(strtoupper($_GET["position"]));
	$credit = trim($_GET["credit"]);
	$payway = trim($_GET["payway"]);
	$dd_type = $_GET["dd_type"];
	$user = $_GET["user"];
	
	$q = mysql_query("SELECT * FROM vericon.customers WHERE id = '$id'");
	$data = mysql_fetch_assoc($q);
	
	$type = $data["type"];
	
	$q1 = mysql_query("SELECT * FROM vericon.packages WHERE id = '$id'");
	
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
	
	if ($id == "" || $user == "")
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
	elseif ($dob == "0000-00-00" || $dob == "")
	{
		echo "Please enter the customer's date of birth";
	}
	elseif ($email == "")
	{
		echo "Please enter the customer's email address";
	}
	elseif ($email != "N/A" && !check_email_address($email))
	{
		echo 'Please enter a valid email address';
	}
	elseif ($mobile == "")
	{
		echo "Please enter the customer's mobile number";
	}
	elseif ($mobile != "N/A" && !preg_match("/^04[0-9]{8}$/",$mobile))
	{
		echo "Please enter a valid mobile number";
	}
	elseif ($mobile != "N/A" && $mobile == "0400000000")
	{
		echo "Please enter a valid mobile number";
	}
	elseif ($physical == "")
	{
		echo "Please enter the customer's physical address";
	}
	elseif ($postal == "")
	{
		echo "Please enter the customer's postal address";
	}
	elseif ($type == "Residential" && $id_type == "")
	{
		echo "Please select an ID type";
	}
	elseif ($type == "Residential" && $id_num == "")
	{
		echo "Please enter the customer's ID number";
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
	elseif ($type == "Business" && $position == "")
	{
		echo "Please enter the customer's position in the business";
	}
	elseif ($credit != "" && !preg_match("/^[0-9]$/",$credit))
	{
		echo "Please enter a valid credit amount";
	}
	elseif ($payway != "" && !preg_match("/^SP0[2378][0-9]{8}$/",$payway))
	{
		echo "Please enter a valid payway ID (e.g. SP0312345678)";
	}
	elseif (mysql_num_rows($q1) == 0)
	{
		echo "Please enter a package for the customer";
	}
	else
	{
		if ($email == "N/A") { $billing = "post"; } else { $email = strtolower($email); $billing = "email"; }
		$timestamp = date("Y-m-d H:i:s");
		
		mysql_query("INSERT INTO vericon.welcome (id, status, timestamp, user) VALUES ('$id', 'Approve', '$timestamp', '$user')") or die(mysql_error());
		
		mysql_query("UPDATE vericon.customers SET status = 'Waiting Provisioning', last_edit_by = '$user', title = '$title', firstname = '" . mysql_real_escape_string($first) . "', middlename = '" . mysql_real_escape_string($middle) . "', lastname = '" . mysql_real_escape_string($last) . "', dob = '" . mysql_real_escape_string($dob) . "', email = '" . mysql_real_escape_string($email) . "', mobile = '" . mysql_real_escape_string($mobile) . "', billing = '$billing', welcome = '$billing', physical = '$physical', postal = '$postal', id_type = '" . mysql_real_escape_string($id_type) . "', id_num = '" . mysql_real_escape_string($id_num) . "', abn = '" . mysql_real_escape_string($abn) . "', position = '" . mysql_real_escape_string($position) . "', credit = '" . mysql_real_escape_string($credit) . "', payway = '" . mysql_real_escape_string($payway) . "', dd_type = '" . mysql_real_escape_string($dd_type) . "' WHERE id = '$id' LIMIT 1") or die(mysql_error());
		
		$q = mysql_query("SELECT * FROM vericon.customers WHERE id = '$id'");
		$data = mysql_fetch_assoc($q);
		
		mysql_query("INSERT INTO vericon.customers_log (id, status, last_edit_by, industry, lead_id, sale_id, timestamp, agent, centre, campaign, type, title, firstname, middlename, lastname, dob, email, mobile, billing, welcome, promotions, physical, postal, id_type, id_num, abn, position, credit, payway, dd_type, billing_comments, other_comments) VALUES ('$data[id]', 'Waiting Provisioning', '$user', '$data[industry]', '$data[lead_id]', '$data[sale_id]', '$data[timestamp]', '$data[agent]', '$data[centre]', '$data[campaign]', '$data[type]', '" . mysql_real_escape_string($data["title"]) . "', '" . mysql_real_escape_string($data["firstname"]) . "', '" . mysql_real_escape_string($data["middlename"]) . "', '" . mysql_real_escape_string($data["lastname"]) . "', '" . mysql_real_escape_string($data["dob"]) . "', '" . mysql_real_escape_string($data["email"]) . "', '" . mysql_real_escape_string($data["mobile"]) . "', '$data[billing]', '$data[welcome]', '$data[promotions]', '$data[physical]', '$data[postal]', '" . mysql_real_escape_string($data["id_type"]) . "', '" . mysql_real_escape_string($data["id_num"]) . "', '" . mysql_real_escape_string($data["abn"]) . "', '" . mysql_real_escape_string($data["position"]) . "', '" . mysql_real_escape_string($data["credit"]) . "', '" . mysql_real_escape_string($data["payway"]) . "', '" . mysql_real_escape_string($data["dd_type"]) . "', '" . mysql_real_escape_string($data["billing_comments"]) . "', '" . mysql_real_escape_string($data["other_comments"]) . "')") or die(mysql_error());
		
		mysql_query("DELETE FROM vericon.welcome_lock WHERE id = '$id' LIMIT 1") or die(mysql_error());
		
		echo "submitted";
	}
}
?>