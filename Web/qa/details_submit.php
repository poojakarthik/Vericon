<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];

if ($method == "country")
{
	$id = $_GET["id"];
	
	$q = mysql_query("SELECT campaigns.country FROM vericon.sales_customers,vericon.campaigns WHERE sales_customers.id = '$id' AND sales_customers.campaign = campaigns.campaign") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo strtolower($data[0]);
}
elseif ($method == "notes")
{
	$id = $_GET["id"];
	
	$q = mysql_query("SELECT * FROM vericon.tpv_notes WHERE id = '$id' ORDER BY timestamp DESC") or die (mysql_error());
	while ($tpv_notes = mysql_fetch_assoc($q))
	{
		$q1 = mysql_query("SELECT * FROM vericon.auth WHERE user = '$tpv_notes[verifier]'") or die(mysql_error());
		$vname = mysql_fetch_assoc($q1);
		
		echo "----- " . date("d/m/Y H:i:s", strtotime($tpv_notes["timestamp"])) . " - " . $vname["first"] . " " . $vname["last"] . " -----" . " (" . $tpv_notes["status"] . ")\n";
		echo $tpv_notes["note"] . "\n";
	}
}
elseif ($method == "add_au")
{
	$id = $_GET["id"];
	$cli = $_GET["cli"];
	$plan = $_GET["plan"];
	$week = date("W");
	$year = date("Y");
	
	$ch2 = mysql_query("SELECT COUNT(cli) FROM vericon.sales_packages_temp WHERE cli = '" . mysql_real_escape_string($cli) . "'");
	$check2 = mysql_fetch_row($ch2);
	
	$ch3 = mysql_query("SELECT COUNT(cli) FROM vericon.sct_dnc WHERE cli = '" . mysql_real_escape_string($cli) . "'");
	$check3 = mysql_fetch_row($ch3);
	
	$ch4 = mysql_query("SELECT COUNT(cli) FROM vericon.sales_packages WHERE cli = '" . mysql_real_escape_string($cli) . "' AND WEEK(timestamp,3) = '$week' AND YEAR(timestamp) = '$year'");
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
		mysql_query("INSERT INTO vericon.sales_packages (sid, cli, plan) VALUES ('$id', '$cli', '$plan')") or die(mysql_error());
		echo "added";
	}
}
elseif ($method == "add_nz")
{
	$id = $_GET["id"];
	$cli = $_GET["cli"];
	$plan = $_GET["plan"];
	$plan_type = $_GET["plan_type"];
	$provider = $_GET["provider"];
	$ac_number = trim($_GET["ac_number"]);
	$adsl_provider = $_GET["adsl_provider"];
	$adsl_ac_number = trim($_GET["adsl_ac_number"]);
	$week = date("W");
	$year = date("Y");
	
	$ch2 = mysql_query("SELECT COUNT(cli) FROM vericon.sales_packages_temp WHERE cli = '" . mysql_real_escape_string($cli) . "'");
	$check2 = mysql_fetch_row($ch2);
	
	$ch3 = mysql_query("SELECT COUNT(cli) FROM vericon.sct_dnc WHERE cli = '" . mysql_real_escape_string($cli) . "'");
	$check3 = mysql_fetch_row($ch3);
	
	$ch4 = mysql_query("SELECT COUNT(cli) FROM vericon.sales_packages WHERE cli = '" . mysql_real_escape_string($cli) . "' AND WEEK(timestamp,3) = '$week' AND YEAR(timestamp) = '$year'");
	$check4 = mysql_fetch_row($ch4);
	
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
	elseif ($check2[0] != 0 || $check4[0] != 0)
	{
		echo "CLI already added";
	}
	else
	{
		mysql_query("INSERT INTO vericon.sales_packages (sid, cli, plan, provider, ac_number, adsl_provider, adsl_ac_number) VALUES ('$id', '$cli', '$plan', '" . mysql_real_escape_string($provider) . "', '" . mysql_real_escape_string($ac_number) . "', '" . mysql_real_escape_string($adsl_provider) . "', '" . mysql_real_escape_string($adsl_ac_number) . "')") or die(mysql_error());
		echo "added";
	}
}
elseif ($method == "edit_au") //edit package
{
	$id = $_GET["id"];
	$cli = $_GET["cli"];
	$plan = $_GET["plan"];
	$cli2 = $_GET["cli2"];
	$week = date("W");
	$year = date("Y");
	
	$ch2 = mysql_query("SELECT COUNT(cli) FROM vericon.sales_packages_temp WHERE cli = '" . mysql_real_escape_string($cli) . "'");
	$check2 = mysql_fetch_row($ch2);
	
	$ch3 = mysql_query("SELECT COUNT(cli) FROM vericon.sct_dnc WHERE cli = '" . mysql_real_escape_string($cli) . "'");
	$check3 = mysql_fetch_row($ch3);
	
	$ch4 = mysql_query("SELECT COUNT(cli) FROM vericon.sales_packages WHERE cli = '" . mysql_real_escape_string($cli) . "' AND WEEK(timestamp,3) = '$week' AND YEAR(timestamp) = '$year'");
	$check4 = mysql_fetch_row($ch4);
	
	if (!preg_match("/^0[2378][0-9]{8}$/",$cli))
	{
		echo "Invalid CLI";
	}
	elseif ($check3[0] != 0)
	{
		echo "CLI is on the SCT DNC list";
	}
	elseif (($check2[0] != 0 || $check4[0] != 0) && $cli != $cli2)
	{
		echo "CLI already added";
	}
	else
	{
		mysql_query("DELETE FROM vericon.sales_packages WHERE sid = '$id' AND cli = '$cli2' LIMIT 1") or die(mysql_error());
		mysql_query("INSERT INTO vericon.sales_packages (sid, cli, plan) VALUES ('$id', '$cli', '$plan')") or die(mysql_error());
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
	$id = $_GET["id"];
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
	
	$ch2 = mysql_query("SELECT COUNT(cli) FROM vericon.sales_packages_temp WHERE cli = '" . mysql_real_escape_string($cli) . "'");
	$check2 = mysql_fetch_row($ch2);
	
	$ch3 = mysql_query("SELECT COUNT(cli) FROM vericon.sct_dnc WHERE cli = '" . mysql_real_escape_string($cli) . "'");
	$check3 = mysql_fetch_row($ch3);
	
	$ch4 = mysql_query("SELECT COUNT(cli) FROM vericon.sales_packages WHERE cli = '" . mysql_real_escape_string($cli) . "' AND WEEK(timestamp,3) = '$week' AND YEAR(timestamp) = '$year'");
	$check4 = mysql_fetch_row($ch4);
	
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
	elseif (($check2[0] != 0 || $check4[0] != 0) && $cli != $cli2)
	{
		echo "CLI already added";
	}
	else
	{
		mysql_query("DELETE FROM vericon.sales_packages WHERE sid = '$id' AND cli = '$cli2' LIMIT 1") or die(mysql_error());
		mysql_query("INSERT INTO vericon.sales_packages (sid, cli, plan, provider, ac_number, adsl_provider, adsl_ac_number) VALUES ('$id', '$cli', '$plan', '" . mysql_real_escape_string($provider) . "', '" . mysql_real_escape_string($ac_number) . "', '" . mysql_real_escape_string($adsl_provider) . "', '" . mysql_real_escape_string($adsl_ac_number) . "')") or die(mysql_error());
		echo "editted";
	}
}
elseif ($method == "delete")
{
	$id = $_GET["id"];
	$cli = $_GET["cli"];
	
	mysql_query("DELETE FROM vericon.sales_packages WHERE sid = '$id' AND cli = '$cli' LIMIT 1") or die(mysql_error());
	
	echo "deleted";
}
elseif ($method == "submit_au")
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
	$ongoing_credit = trim($_GET["ongoing_credit"]);
	$onceoff_credit = trim($_GET["onceoff_credit"]);
	$promo_code = $_GET["promo_code"];
	$promo_cli = $_GET["promo_cli"];
	$payway = trim($_GET["payway"]);
	$dd_type = $_GET["dd_type"];
	
	$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE id = '$id'");
	$data = mysql_fetch_assoc($q);
	
	$type = $data["type"];
	$campaign = $data["campaign"];
	
	$q1 = mysql_query("SELECT * FROM vericon.sales_packages WHERE sid = '$id'");
	
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
	
	if ($id == "")
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
	elseif ($ongoing_credit != "" && !preg_match('/^\d+$/',$ongoing_credit))
	{
		echo "Please enter a valid ongoing credit amount";
	}
	elseif ($onceoff_credit != "" && !preg_match('/^\d+$/',$onceoff_credit))
	{
		echo "Please enter a valid once off credit amount";
	}
	elseif ($promo_code != "" && !preg_match("/^0[2378][0-9]{8}$/",$promo_cli))
	{
		echo "Please enter a valid promo cli";
	}
	elseif ($promo_code == "" && $promo_cli != "")
	{
		echo "Please select a promo code";
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
		
		if ($promo_code != "") {
			$q2 = mysql_query("SELECT `id` FROM `vericon`.`campaigns` WHERE `campaign` = '" . $campaign . "'") or die(mysql_error());
			$campaign_id = mysql_fetch_row($q2);
			
			$promo_plan = "001Z" . substr($campaign_id[0], 0, 2) . substr($type, 0, 1) . "AX039";
		} else {
			$promo_plan = "";
		}
		
		mysql_query("UPDATE vericon.sales_customers SET title = '$title', firstname = '" . mysql_real_escape_string($first) . "', middlename = '" . mysql_real_escape_string($middle) . "', lastname = '" . mysql_real_escape_string($last) . "', dob = '" . mysql_real_escape_string($dob) . "', email = '" . mysql_real_escape_string($email) . "', mobile = '" . mysql_real_escape_string($mobile) . "', billing = '$billing', welcome = '$billing', physical = '$physical', postal = '$postal', id_type = '" . mysql_real_escape_string($id_type) . "', id_num = '" . mysql_real_escape_string($id_num) . "', abn = '" . mysql_real_escape_string($abn) . "', position = '" . mysql_real_escape_string($position) . "', ongoing_credit = '" . mysql_real_escape_string($ongoing_credit) . "', onceoff_credit = '" . mysql_real_escape_string($onceoff_credit) . "', promo_code = '" . mysql_real_escape_string($promo_code) . "', promo_cli = '" . mysql_real_escape_string($promo_cli) . "', promo_plan = '" . mysql_real_escape_string($promo_plan) . "', payway = '" . mysql_real_escape_string($payway) . "', dd_type = '" . mysql_real_escape_string($dd_type) . "' WHERE id = '$id' LIMIT 1") or die(mysql_error());
		
		echo "submitted";
	}
}
elseif ($method == "submit_nz")
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
	$bus_name = $_GET["bus_name"];
	$position = trim(strtoupper($_GET["position"]));
	$ongoing_credit = trim($_GET["ongoing_credit"]);
	$onceoff_credit = trim($_GET["onceoff_credit"]);
	
	$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE id = '$id'");
	$data = mysql_fetch_assoc($q);
	
	$type = $data["type"];
	
	$q1 = mysql_query("SELECT * FROM vericon.sales_packages WHERE sid = '$id'");
	
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
	
	if ($id == "")
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
	elseif ($mobile != "N/A" && (!preg_match("/^02[0-9]{7}$/",$mobile) && !preg_match("/^02[0-9]{8}$/",$mobile) && !preg_match("/^02[0-9]{9}$/",$mobile)))
	{
		echo "Please enter a valid mobile number";
	}
	elseif ($mobile != "N/A" && $mobile == "0200000000")
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
	elseif ($type == "Business" && $bus_name == "")
	{
		echo "Please enter the customer's business name";
	}
	elseif ($type == "Business" && $position == "")
	{
		echo "Please enter the customer's position in the business";
	}
	elseif ($ongoing_credit != "" && !preg_match('/^\d+$/',$ongoing_credit))
	{
		echo "Please enter a valid ongoing credit amount";
	}
	elseif ($onceoff_credit != "" && !preg_match('/^\d+$/',$onceoff_credit))
	{
		echo "Please enter a valid once off credit amount";
	}
	elseif (mysql_num_rows($q1) == 0)
	{
		echo "Please enter a package for the customer";
	}
	else
	{
		if ($email == "N/A") { $billing = "post"; } else { $email = strtolower($email); $billing = "email"; }
		
		mysql_query("UPDATE vericon.sales_customers SET title = '$title', firstname = '" . mysql_real_escape_string($first) . "', middlename = '" . mysql_real_escape_string($middle) . "', lastname = '" . mysql_real_escape_string($last) . "', dob = '" . mysql_real_escape_string($dob) . "', email = '" . mysql_real_escape_string($email) . "', mobile = '" . mysql_real_escape_string($mobile) . "', billing = '$billing', welcome = '$billing', physical = '$physical', postal = '$postal', id_type = '" . mysql_real_escape_string($id_type) . "', id_num = '" . mysql_real_escape_string($id_num) . "', bus_name = '" . mysql_real_escape_string($bus_name) . "', position = '" . mysql_real_escape_string($position) . "', ongoing_credit = '" . mysql_real_escape_string($ongoing_credit) . "', onceoff_credit = '" . mysql_real_escape_string($onceoff_credit) . "' WHERE id = '$id' LIMIT 1") or die(mysql_error());
		
		echo "submitted";
	}
}
?>