<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];

if ($method == "get") //get sale
{
	$id = $_GET["id"];
	$user = $_GET["user"];
	$centre = $_GET["centre"];
	$date1 = date("Y-m-d");
	$date2 = date("Y-m-d", strtotime("-1 week"));
	$lead_id = substr($id,1,9);
	
	$lq = mysql_query("SELECT leads FROM vericon.centres WHERE centre = '$centre'") or die(mysql_error());
	$lead_val = mysql_fetch_row($lq);
	
	if ($lead_val[0] == 1)
	{
		$q = mysql_query("SELECT * FROM leads.leads WHERE cli = '$lead_id'") or die(mysql_error());
		$check = mysql_fetch_assoc($q);
	
		$q1 = mysql_query("SELECT COUNT(lead_id),status FROM vericon.sales_customers WHERE lead_id = '$id' AND DATE(timestamp) BETWEEN '$check[issue_date]' AND '$check[expiry_date]'") or die(mysql_error());
		$check1 = mysql_fetch_row($q1);
		
		$q2 = mysql_query("SELECT COUNT(cli) FROM vericon.sct_dnc WHERE cli = '$id'") or die(mysql_error());
		$check2 = mysql_fetch_row($q2);
		
		$qct = mysql_query("SELECT * FROM vericon.centres WHERE centre = '$centre'") or die(mysql_error());
		$check3 = mysql_fetch_assoc($qct);
		
		if ($check3["type"] == "Captive")
		{
			$qcg = mysql_query("SELECT * FROM leads.groups WHERE centres LIKE '%$centre%'") or die(mysql_error());
			$check4 = mysql_fetch_assoc($qcg);
			
			$qlg = mysql_query("SELECT * FROM leads.groups WHERE centres LIKE '%$check[centre]%'") or die(mysql_error());
			$check5 = mysql_fetch_assoc($qlg);
			
			if ($check4["group"] != $check5["group"] && strtoupper($check["centre"]) != "ROHAN")
			{
				$valid_lead = "false";
			}
			else
			{
				$valid_lead = "true";
			}
		}
		else
		{
			if ($check["centre"] != $centre)
			{
				$valid_lead = "false";
			}
			else
			{
				$valid_lead = "true";
			}
		}
		
		if (!preg_match("/^0[2378][0-9]{8}$/",$id))
		{
			echo "Invalid Lead ID!";
		}
		elseif (mysql_num_rows($q) == 0)
		{
			echo "Lead is not in the data packet!";
		}
		elseif ($valid_lead == "false")
		{
			echo "Lead is not in the data packet!";
		}
		elseif ($check2[0] != 0)
		{
			echo "Customer is on the SCT DNC!";
		}
		elseif (strtotime(date("Y-m-d")) < strtotime($check["issue_date"]) || strtotime(date("Y-m-d")) > strtotime($check["expiry_date"]))
		{
			echo "Lead has expired!";
		}
		elseif ($check1[0] != 0 && $check1[1] == "Approved")
		{
			echo "Customer already submitted within this data period!";
		}
		else
		{
			echo "valid";
		}
	}
	else
	{
		$q1 = mysql_query("SELECT COUNT(lead_id),status FROM vericon.sales_customers WHERE lead_id = '$id' AND DATE(timestamp) BETWEEN '$date2' AND '$date1'") or die(mysql_error());
		$check2 = mysql_fetch_row($q1);
		
		if (!preg_match("/^0[2378][0-9]{8}$/",$id))
		{
			echo "Invalid ID!";
		}
		elseif ($check2[0] != 0 && $check2[1] == "Approved")
		{
			echo "Customer already submitted within this data period!";
		}
		else
		{
			echo "valid";
		}
	}
}
elseif ($method == "load")
{
	$lead_id = $_GET["id"];
	$agent = $_GET["agent"];
	$centre = $_GET["centre"];
	$campaign = $_GET["campaign"];
	$type = $_GET["type"];
	$timestamp = date("Y-m-d H:i:s");
	
	mysql_query("DELETE FROM vericon.verification_lock WHERE user = '$agent'") or die(mysql_error());
	
	$lq = mysql_query("SELECT * FROM leads.leads WHERE cli = '$lead_id'") or die(mysql_error());
	$lead = mysql_fetch_assoc($lq);
	
	$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE lead_id = '" . mysql_real_escape_string($lead_id) . "' AND centre = '$centre' AND DATE(timestamp) >= '$lead[issue_date]'") or die(mysql_error());
	$check = mysql_fetch_assoc($q);
	
	$q1 = mysql_query("SELECT user FROM vericon.verification_lock WHERE id = '" . mysql_real_escape_string($check["id"]) . "'") or die(mysql_error());
	$lock = mysql_fetch_assoc($q1);
	
	if ($check["status"] == "Approved")
	{
		echo "Sale is already approved";
	}
	elseif (mysql_num_rows($q1) != 0)
	{
		$q2 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$lock[user]'") or die(mysql_error());
		$lv = mysql_fetch_row($q2);
		
		echo "Sale is locked by " . $lv[0] . " " . $lv[1];
	}
	elseif (mysql_num_rows($q) == 0)
	{
		$pre_id = date("y") . str_pad(date("z"),3,"0",STR_PAD_LEFT);
		$q2 = mysql_query("SELECT COUNT(id) FROM vericon.sales_customers WHERE id LIKE '$pre_id%'");
		$num = mysql_fetch_row($q2);
		$random = (rand(0,9));
		
		$id = $pre_id . str_pad(($num[0]+1),3,"0",STR_PAD_LEFT) . $random;
			
		mysql_query("INSERT INTO vericon.sales_customers (id, status, lead_id, timestamp, agent, centre, campaign, type) VALUES ('$id', 'Queue', '$lead_id', '$timestamp', '$agent', '$centre', '$campaign', '$type')") or die(mysql_error());
		
		mysql_query("INSERT INTO vericon.verification_lock (id, user) VALUES ('$id', '$agent') ON DUPLICATE KEY UPDATE id = '$id'") or die(mysql_error());
		
		echo "valid" . $id;
	}
	else
	{
		$id = $check["id"];
		mysql_query("UPDATE vericon.sales_customers SET timestamp = '$timestamp', agent = '$agent', type = '$type', centre = '$centre', campaign = '$campaign' WHERE id = '$id' LIMIT 1") or die(mysql_error());
		
		mysql_query("INSERT INTO vericon.verification_lock (id, user) VALUES ('$id', '$agent') ON DUPLICATE KEY UPDATE id = '$id'") or die(mysql_error());
		
		if ($check["type"] != $type)
		{
			mysql_query("DELETE FROM vericon.sales_packages WHERE sid = '$id'") or die(mysql_error());
		}
		
		echo "valid" . $id;
	}
}
elseif ($method == "add") //add package
{
	$sid = $_GET["id"];
	$cli = $_GET["cli"];
	$plan = $_GET["plan"];
	$week = date("W");
	
	$ch2 = mysql_query("SELECT COUNT(cli) FROM vericon.sales_packages WHERE cli = '$cli' AND WEEK(timestamp) = '$week'");
	$check2 = mysql_fetch_row($ch2);
	
	$ch3 = mysql_query("SELECT COUNT(cli) FROM vericon.sct_dnc WHERE cli = '" . mysql_real_escape_string($cli) . "'");
	$check3 = mysql_fetch_row($ch3);
	
	if (!preg_match("/^0[2378][0-9]{8}$/",$cli))
	{
		echo "Invalid CLI";
	}
	elseif ($check3[0] != 0)
	{
		echo "CLI is on the SCT DNC list";
	}
	elseif ($check2[0] != 0)
	{
		echo "CLI already added";
	}
	else
	{
		mysql_query("INSERT INTO vericon.sales_packages (sid, cli, plan) VALUES ('$sid', '" . mysql_real_escape_string($cli) . "', '$plan')") or die(mysql_error());
		echo "added";
	}
}
elseif ($method == "edit") //edit package
{
	$sid = $_GET["id"];
	$cli = $_GET["cli"];
	$plan = $_GET["plan"];
	$cli2 = $_GET["cli2"];
	
	$ch2 = mysql_query("SELECT COUNT(cli) FROM vericon.sales_packages WHERE cli = '$cli'");
	$check2 = mysql_fetch_row($ch2);
	
	$ch3 = mysql_query("SELECT COUNT(cli) FROM vericon.sct_dnc WHERE cli = '" . mysql_real_escape_string($cli) . "'");
	$check3 = mysql_fetch_row($ch3);
	
	if (!preg_match("/^0[2378][0-9]{8}$/",$cli))
	{
		echo "Invalid CLI";
	}
	elseif ($check3[0] != 0)
	{
		echo "CLI is on the SCT DNC list";
	}
	elseif ($check2[0] != 0 && $cli != $cli2)
	{
		echo "CLI already added";
	}
	else
	{
		mysql_query("DELETE FROM vericon.sales_packages WHERE sid = '$sid' AND cli = '$cli2' LIMIT 1") or die(mysql_error());
		mysql_query("INSERT INTO vericon.sales_packages (sid, cli, plan) VALUES ('$sid', '" . mysql_real_escape_string($cli) . "', '$plan')") or die(mysql_error());
		echo "editted";
	}
}
elseif ($method == "delete") //delete package
{
	$sid = $_GET["id"];
	$cli = $_GET["cli"];
	
	mysql_query("DELETE FROM vericon.sales_packages WHERE sid = '$sid' AND cli = '$cli' LIMIT 1") or die(mysql_error());
	
	echo "deleted";
}
elseif ($method == "script_check") //check packages
{
	$sid = $_GET["id"];
	
	$q = mysql_query("SELECT * FROM vericon.sales_packages WHERE sid = '$sid'");
	
	if (mysql_num_rows($q) == 0)
	{
		echo "Please enter a package for the customer";
	}
	else
	{
		echo "valid";
	}
}
elseif ($method == "verifier") //list verifiers
{
	$term = explode(" ",$_GET["term"]);
	$centre = $_GET["centre"];
	
	if ($centre == "CC51" || $centre == "CC52" || $centre == "CC53" || $centre == "CC54")
	{
		$inc = " (centre = 'CC51' OR centre = 'CC52' OR centre = 'CC53' OR centre = 'CC54') ";
	}
	elseif ($centre == "CC61" || $centre == "CC63")
	{
		$inc = " (centre = 'CC61' OR centre = 'CC63') ";
	}
	elseif ($centre == "CC71" || $centre == "CC72" || $centre == "CC73" || $centre == "CC74")
	{
		$inc = " (centre = 'CC71' OR centre = 'CC72' OR centre = 'CC73' OR centre = 'CC74') ";
	}
	
	$q = mysql_query("SELECT * FROM vericon.auth WHERE" . $inc . "AND (first LIKE '" . mysql_real_escape_string($term[0]) . "%' AND last LIKE '" . mysql_real_escape_string($term[1]) . "%') AND status = 'Enabled'") or die(mysql_error());
	echo '[';
	while ($data = mysql_fetch_assoc($q))
	{
		$d[] = "{ \"id\": \"" . $data["user"] . "\", \"value\": \"" . $data["first"] . " " . $data["last"] . " (" . $data["user"] . ")\" }";
	}
	echo implode(", ",$d);
	echo ']';
}
elseif ($method == "cancel") //cancel sale
{
	$id = $_GET["id"];
	$status = $_GET["status"];
	$verifier = $_GET["verifier"];
	$dialled = trim($_GET["dialled"]);
	$note = $_GET["note"];
	$now = date("Y-m-d H:i:s");
	
	$q = mysql_query("SELECT centre,lead_id FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	if ($id == "")
	{
		echo "Error! Please contact your administrator";
	}
	elseif ($verifier == "")
	{
		echo "Please enter the verifier's name";
	}
	elseif (!preg_match("/^0[23478][0-9]{8}$/",$dialled))
	{
		echo "Please enter a valid dialled number";
	}
	elseif ($status == "")
	{
		echo "Please select a status";
	}
	elseif ($note == "")
	{
		echo "Please enter a note";
	}
	else
	{
		$note = "Dialled Number: " . $dialled . " -- " . $note;
		
		mysql_query("INSERT INTO vericon.tpv_notes (id, status, lead_id, centre, verifier, note) VALUES ('$id', '$status', '$data[1]', '$data[0]', '$verifier', '". mysql_real_escape_string($note) . "')") or die(mysql_error());
		
		mysql_query("UPDATE vericon.sales_customers SET status = '$status', approved_timestamp = '$now' WHERE id = '$id'") or die(mysql_error());
		
		mysql_query("DELETE FROM vericon.verification_lock WHERE id = '$id'") or die(mysql_error());
		
		echo "done";
	}
}
elseif ($method == "submit") //submit sale
{
	$id = $_GET["id"];
	$status = "Approved";
	$industry = "SELF";
	$verifier = $_GET["verifier"];
	$dialled = trim($_GET["dialled"]);
	$note = $_GET["note"];
	$now = date("Y-m-d H:i:s");
	
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
	
	$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
	$data = mysql_fetch_assoc($q);
	
	if ($id == "")
	{
		echo "Error! Please contact your administrator";
	}
	elseif ($verifier == "")
	{
		echo "Please enter the verifier's name";
	}
	elseif (!preg_match("/^0[23478][0-9]{8}$/",$dialled))
	{
		echo "Please enter a valid dialled number";
	}
	elseif ($data["title"] == "")
	{
		echo "Please select a title";
	}
	elseif ($data["firstname"] == "")
	{
		echo "Please enter the customer's first name";
	}
	elseif ($data["lastname"] == "")
	{
		echo "Please enter the customer's last name";
	}
	elseif ($data["dob"] == "0000-00-00" || $data["dob"] == "")
	{
		echo "Please enter the customer's date of birth";
	}
	elseif ($data["email"] == "")
	{
		echo "Please enter the customer's email address";
	}
	elseif ($data["email"] != "N/A" && !check_email_address($data["email"]))
	{
		echo 'Please enter a valid email address';
	}
	elseif ($data["mobile"] == "")
	{
		echo "Please enter the customer's mobile number";
	}
	elseif ($data["mobile"] != "N/A" && !preg_match("/^04[0-9]{8}$/",$data["mobile"]))
	{
		echo "Please enter a valid mobile number";
	}
	elseif ($data["mobile"] != "N/A" && $data["mobile"] == "0400000000")
	{
		echo "Please enter a valid mobile number";
	}
	elseif ($data["physical"] == "")
	{
		echo "Please enter the customer's physical address";
	}
	elseif ($data["postal"] == "")
	{
		echo "Please enter the customer's postal address";
	}
	elseif ($data["type"] == "Residential" && $data["id_type"] == "")
	{
		echo "Please select an ID type";
	}
	elseif ($data["type"] == "Residential" && $data["id_num"] == "")
	{
		echo "Please enter the customer's ID number";
	}
	elseif ($data["id_type"] == "Medicare Card" && !preg_match("/^[0-9]{10}$/",$data["id_num"]))
	{
		echo "Please enter a valid Medicare Card number";
	}
	elseif ($data["id_type"] == "Healthcare Card" && !preg_match("/^[0-9]{9}[a-zA-Z]$/",$data["id_num"]))
	{
		echo "Please enter a valid Healthcare Card number";
	}
	elseif ($data["id_type"] == "Pension Card" && !preg_match("/^[0-9]{9}[a-zA-Z]$/",$data["id_num"]))
	{
		echo "Please enter a valid Pension Card number";
	}
	elseif ($data["type"] == "Business" && $data["abn"] == "")
	{
		echo "Please enter the customer's ABN";
	}
	elseif ($data["type"] == "Business" && $data["position"] == "")
	{
		echo "Please enter the customer's position in the business";
	}
	else
	{
		$note = "Dialled Number: " . $dialled . " -- " . $note;
		
		mysql_query("INSERT INTO vericon.tpv_notes (id, status, lead_id, centre, verifier, note) VALUES ('$id', '$status', '$data[lead_id]', '$data[centre]', '$verifier', '". mysql_real_escape_string($note) . "')") or die(mysql_error());
		
		mysql_query("UPDATE vericon.sales_customers SET status = '$status', industry = '$industry', approved_timestamp = '$now' WHERE id = '$id'") or die(mysql_error());
		
		mysql_query("DELETE FROM vericon.verification_lock WHERE id = '$id'") or die(mysql_error());
		
		echo "done";
	}
}
?>