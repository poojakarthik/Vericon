<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];

if ($method == "get") //get sale
{
	$id = $_GET["id"];
	$user = $_GET["user"];
	
	mysql_query("DELETE FROM vericon.verification_lock WHERE user = '$user'") or die(mysql_error());
	
	$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE id = '" . mysql_real_escape_string($id) . "'") or die(mysql_error());
	$check = mysql_fetch_assoc($q);
	
	$q1 = mysql_query("SELECT user FROM vericon.verification_lock WHERE id = '" . mysql_real_escape_string($id) . "'") or die(mysql_error());
	$lock = mysql_fetch_assoc($q1);
	
	$q2 = mysql_query("SELECT country FROM vericon.campaigns WHERE campaign = '" . mysql_real_escape_string($check["campaign"]) . "'") or die(mysql_error());
	$country = mysql_fetch_row($q2);
	
	if ($id == "" || mysql_num_rows($q) == 0)
	{
		echo "Invalid ID";
	}
	elseif ($check["status"] == "Approved")
	{
		echo "Sale is already approved";
	}
	elseif (mysql_num_rows($q1) != 0)
	{
		$q2 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$lock[user]'") or die(mysql_error());
		$lv = mysql_fetch_row($q2);
		
		echo "Sale is locked by " . $lv[0] . " " . $lv[1];
	}
	else
	{
		mysql_query("INSERT INTO vericon.verification_lock (id, user) VALUES ('$id', '$user') ON DUPLICATE KEY UPDATE id = '$id'") or die(mysql_error());
		
		echo "valid_" . strtolower($country[0]);
	}
}
elseif ($method == "change_type") //change type
{
	$id = $_GET["id"];
	$verifier = $_GET["verifier"];
	$type = $_GET["type"];
	$now = date("Y-m-d H:i:s");
	
	$q = mysql_query("SELECT centre,lead_id FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	if ($id == "" || $verifier == "" || $type == "")
	{
		echo "Error! Please contact your administrator";
	}
	else
	{
		mysql_query("INSERT INTO vericon.tpv_notes (id, status, lead_id, centre, verifier, note) VALUES ('$id', 'Declined', '$data[1]', '$data[0]', '$verifier', 'Changed sale to " . $type . "')") or die(mysql_error());
		
		mysql_query("UPDATE vericon.sales_customers SET status = 'Declined', approved_timestamp = '$now', type = '$type' WHERE id = '$id'") or die(mysql_error());
		
		$q1 = mysql_query("SELECT * FROM vericon.sales_packages WHERE sid = '$id'") or die(mysql_error());
		while ($pack = mysql_fetch_assoc($q1))
		{
			if ($type == "Business")
			{
				$new_plan = substr($pack["plan"],0,3) . "B" . substr($pack["plan"],4);
			}
			elseif ($type == "Residential")
			{
				$new_plan = substr($pack["plan"],0,3) . "R" . substr($pack["plan"],4);
			}
			
			mysql_query("UPDATE vericon.sales_packages SET plan = '$new_plan' WHERE sid = '$id' AND cli = '$pack[cli]'") or die(mysql_error());
		}
		
		echo "done";
	}
}
elseif ($method == "add_au") //add package
{
	$sid = $_GET["id"];
	$cli = $_GET["cli"];
	$plan = $_GET["plan"];
	$week = date("W");
	$year = date("Y");
	
	$ch2 = mysql_query("SELECT COUNT(cli) FROM vericon.sales_packages WHERE cli = '$cli' AND WEEK(timestamp,3) = '$week' AND YEAR(timestamp) = '$year'");
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
elseif ($method == "add_nz") //add package
{
	$sid = $_GET["id"];
	$cli = $_GET["cli"];
	$plan = $_GET["plan"];
	$plan_type = $_GET["plan_type"];
	$provider = $_GET["provider"];
	$ac_number = trim($_GET["ac_number"]);
	$adsl_provider = $_GET["adsl_provider"];
	$adsl_ac_number = trim($_GET["adsl_ac_number"]);
	$week = date("W");
	$year = date("Y");
	
	$ch2 = mysql_query("SELECT COUNT(cli) FROM vericon.sales_packages WHERE cli = '$cli' AND WEEK(timestamp,3) = '$week' AND YEAR(timestamp) = '$year'");
	$check2 = mysql_fetch_row($ch2);
	
	$ch3 = mysql_query("SELECT COUNT(cli) FROM vericon.sct_dnc WHERE cli = '" . mysql_real_escape_string($cli) . "'");
	$check3 = mysql_fetch_row($ch3);
	
	if (!preg_match("/^0[34679][0-9]{7}$/",$cli))
	{
		echo "Invalid CLI";
	}
	elseif ($plan == "")
	{
		echo "Please select a plan";
	}
	elseif ($provider == "")
	{
		echo "Please select the CLI's provider";
	}
	elseif ($ac_number == "")
	{
		echo "Please enter the CLI's account number";
	}
	elseif ($plan_type == "Bundle" && $adsl_provider == "")
	{
		echo "Please select the CLI's ADSL provider";
	}
	elseif ($plan_type == "Bundle" && $adsl_ac_number == "")
	{
		echo "Please enter the CLI's ADSL account number";
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
		mysql_query("INSERT INTO vericon.sales_packages (sid, cli, plan, provider, ac_number, adsl_provider, adsl_ac_number) VALUES ('$sid', '" . mysql_real_escape_string($cli) . "', '$plan', '" . mysql_real_escape_string($provider) . "', '" . mysql_real_escape_string($ac_number) . "', '" . mysql_real_escape_string($adsl_provider) . "', '" . mysql_real_escape_string($adsl_ac_number) . "')") or die(mysql_error());
		echo "added";
	}
}
elseif ($method == "edit_au") //edit package
{
	$sid = $_GET["id"];
	$cli = $_GET["cli"];
	$plan = $_GET["plan"];
	$cli2 = $_GET["cli2"];
	$week = date("W");
	$year = date("Y");
	
	$ch2 = mysql_query("SELECT COUNT(cli) FROM vericon.sales_packages WHERE cli = '$cli' AND WEEK(timestamp,3) = '$week' AND YEAR(timestamp) = '$year'");
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
elseif ($method == "nz_provider") //get provider
{
	$id = $_GET["id"];
	$cli = $_GET["cli"];
	
	$q = mysql_query("SELECT provider FROM vericon.sales_packages WHERE sid = '$id' AND cli = '$cli'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo $data[0];
}
elseif ($method == "nz_ac_number") //get account number
{
	$id = $_GET["id"];
	$cli = $_GET["cli"];
	
	$q = mysql_query("SELECT ac_number FROM vericon.sales_packages WHERE sid = '$id' AND cli = '$cli'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo $data[0];
}
elseif ($method == "nz_adsl_provider") //get adsl provider
{
	$id = $_GET["id"];
	$cli = $_GET["cli"];
	
	$q = mysql_query("SELECT adsl_provider FROM vericon.sales_packages WHERE sid = '$id' AND cli = '$cli'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo $data[0];
}
elseif ($method == "nz_adsl_ac_number") //get adsl account number
{
	$id = $_GET["id"];
	$cli = $_GET["cli"];
	
	$q = mysql_query("SELECT adsl_ac_number FROM vericon.sales_packages WHERE sid = '$id' AND cli = '$cli'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo $data[0];
}
elseif ($method == "edit_nz") //edit package
{
	$sid = $_GET["id"];
	$cli = $_GET["cli"];
	$plan = $_GET["plan"];
	$plan_type = $_GET["plan_type"];
	$provider = $_GET["provider"];
	$ac_number = trim($_GET["ac_number"]);
	$adsl_provider = $_GET["adsl_provider"];
	$adsl_ac_number = trim($_GET["adsl_ac_number"]);
	$cli2 = $_GET["cli2"];
	$week = date("W");
	$year = date("Y");
	
	$ch2 = mysql_query("SELECT COUNT(cli) FROM vericon.sales_packages WHERE cli = '$cli' AND WEEK(timestamp,3) = '$week' AND YEAR(timestamp) = '$year'");
	$check2 = mysql_fetch_row($ch2);
	
	$ch3 = mysql_query("SELECT COUNT(cli) FROM vericon.sct_dnc WHERE cli = '" . mysql_real_escape_string($cli) . "'");
	$check3 = mysql_fetch_row($ch3);
	
	if (!preg_match("/^0[34679][0-9]{7}$/",$cli))
	{
		echo "Invalid CLI";
	}
	elseif ($plan == "")
	{
		echo "Please select a plan";
	}
	elseif ($provider == "")
	{
		echo "Please select the CLI's provider";
	}
	elseif ($ac_number == "")
	{
		echo "Please enter the CLI's account number";
	}
	elseif ($plan_type == "Bundle" && $adsl_provider == "")
	{
		echo "Please select the CLI's ADSL provider";
	}
	elseif ($plan_type == "Bundle" && $adsl_ac_number == "")
	{
		echo "Please enter the CLI's ADSL account number";
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
		mysql_query("INSERT INTO vericon.sales_packages (sid, cli, plan, provider, ac_number, adsl_provider, adsl_ac_number) VALUES ('$sid', '" . mysql_real_escape_string($cli) . "', '$plan', '" . mysql_real_escape_string($provider) . "', '" . mysql_real_escape_string($ac_number) . "', '" . mysql_real_escape_string($adsl_provider) . "', '" . mysql_real_escape_string($adsl_ac_number) . "')") or die(mysql_error());
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
elseif ($method == "cancel") //cancel sale
{
	$id = $_GET["id"];
	$status = $_GET["status"];
	$lead_id = $_GET["lead_id"];
	$verifier = $_GET["verifier"];
	$note = $_GET["note"];
	$now = date("Y-m-d H:i:s");
	
	$q = mysql_query("SELECT centre,lead_id FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	if ($id == "" || $verifier == "")
	{
		echo "Error! Please contact your administrator";
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
		mysql_query("INSERT INTO vericon.tpv_notes (id, status, lead_id, centre, verifier, note) VALUES ('$id', '$status', '$data[1]', '$data[0]', '$verifier', '". mysql_real_escape_string($note) . "')") or die(mysql_error());
		
		mysql_query("UPDATE vericon.sales_customers SET status = '$status', approved_timestamp = '$now' WHERE id = '$id'") or die(mysql_error());
		
		mysql_query("DELETE FROM vericon.verification_lock WHERE id = '$id'") or die(mysql_error());
		
		echo "done";
	}
}
elseif ($method == "submit_au") //submit sale
{
	$id = $_GET["id"];
	$status = "Approved";
	$industry = "TPV";
	$verifier = $_GET["verifier"];
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
	
	if ($id == "" || $verifier == "")
	{
		echo "Error! Please contact your administrator";
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
		mysql_query("INSERT INTO vericon.tpv_notes (id, status, lead_id, centre, verifier, note) VALUES ('$id', '$status', '$data[lead_id]', '$data[centre]', '$verifier', '". mysql_real_escape_string($note) . "')") or die(mysql_error());
		
		mysql_query("UPDATE vericon.sales_customers SET status = '$status', industry = '$industry', approved_timestamp = '$now' WHERE id = '$id'") or die(mysql_error());
		
		mysql_query("DELETE FROM vericon.verification_lock WHERE id = '$id'") or die(mysql_error());
		
		echo "done";
	}
}
elseif ($method == "submit_nz") //submit sale
{
	$id = $_GET["id"];
	$status = "Approved";
	$industry = "TPV";
	$verifier = $_GET["verifier"];
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
	
	if ($id == "" || $verifier == "")
	{
		echo "Error! Please contact your administrator";
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
	elseif ($data["mobile"] != "N/A" && (!preg_match("/^02[0-9]{7}$/",$data["mobile"]) && !preg_match("/^02[0-9]{8}$/",$data["mobile"]) && !preg_match("/^02[0-9]{9}$/",$data["mobile"])))
	{
		echo "Please enter a valid mobile number";
	}
	elseif ($data["mobile"] != "N/A" && $data["mobile"] == "0200000000")
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
	elseif ($data["type"] == "Business" && $data["bus_name"] == "")
	{
		echo "Please enter the customer's business name";
	}
	elseif ($data["type"] == "Business" && $data["position"] == "")
	{
		echo "Please enter the customer's position in the business";
	}
	else
	{
		mysql_query("INSERT INTO vericon.tpv_notes (id, status, lead_id, centre, verifier, note) VALUES ('$id', '$status', '$data[lead_id]', '$data[centre]', '$verifier', '". mysql_real_escape_string($note) . "')") or die(mysql_error());
		
		mysql_query("UPDATE vericon.sales_customers SET status = '$status', industry = '$industry', approved_timestamp = '$now' WHERE id = '$id'") or die(mysql_error());
		
		mysql_query("DELETE FROM vericon.verification_lock WHERE id = '$id'") or die(mysql_error());
		
		echo "done";
	}
}
?>