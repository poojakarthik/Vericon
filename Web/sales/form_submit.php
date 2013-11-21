<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];

if ($method == "get")
{
	$id = $_GET["id"];
	$centre = $_GET["centre"];
	$date1 = date("Y-m-d");
	$date2 = date("Y-m-d", strtotime("-1 week"));
	
	$lq = mysql_query("SELECT leads FROM vericon.centres WHERE centre = '$centre'") or die(mysql_error());
	$lead_val = mysql_fetch_row($lq);
	
	if ($lead_val[0] == 1)
	{
		$q = mysql_query("SELECT * FROM leads.leads WHERE cli = '$id'") or die(mysql_error());
		$check = mysql_fetch_assoc($q);
	
		$q1 = mysql_query("SELECT COUNT(lead_id) FROM vericon.sales_customers WHERE lead_id = '$id' AND DATE(timestamp) BETWEEN '$check[issue_date]' AND '$check[expiry_date]'") or die(mysql_error());
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
		
		if (!preg_match("/^0[2378][0-9]{8}$/",$id) && !preg_match("/^0[34679][0-9]{7}$/",$id))
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
		elseif ($check1[0] != 0)
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
		$q1 = mysql_query("SELECT COUNT(lead_id) FROM vericon.sales_customers WHERE lead_id = '$id' AND DATE(timestamp) BETWEEN '$date2' AND '$date1'") or die(mysql_error());
		$check2 = mysql_fetch_row($q1);
		
		if (!preg_match("/^0[2378][0-9]{8}$/",$id) && !preg_match("/^0[34679][0-9]{7}$/",$id))
		{
			echo "Invalid Lead ID!";
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
}
elseif ($method == "load")
{
	$lead_id = $_GET["id"];
	$agent = $_GET["agent"];
	$centre = $_GET["centre"];
	$campaign = $_GET["campaign"];
	$type = $_GET["type"];
	
	$q = mysql_query("SELECT country FROM vericon.campaigns WHERE campaign = '" . mysql_real_escape_string($campaign) . "'") or die(mysql_error());
	$country = mysql_fetch_row($q);
	
	if ($lead_id == "" || $agent == "" || $centre == "")
	{
		echo "Error! Please contact your administrator";
	}
	elseif ($campaign == "")
	{
		echo "Please select a campaign";
	}
	elseif ($type == "")
	{
		echo "Please select a sale type";
	}
	elseif ($country[0] == "AU" && !preg_match("/^0[2378][0-9]{8}$/",$lead_id))
	{
		echo "Not an Australian Lead!";
	}
	elseif ($country[0] == "NZ" && !preg_match("/^0[34679][0-9]{7}$/",$lead_id))
	{
		echo "Not a New Zealand Lead!";
	}
	else
	{
		$q3 = mysql_query("SELECT lead_id FROM vericon.sales_customers_temp WHERE lead_id = '$lead_id'");
		
		if (mysql_num_rows($q3) == 0)
		{
			mysql_query("INSERT INTO vericon.sales_customers_temp (lead_id, agent, centre, campaign, type) VALUES ('$lead_id', '$agent', '$centre', '$campaign', '$type')") or die(mysql_error());
		}
		else
		{
			mysql_query("UPDATE vericon.sales_customers_temp SET agent = '$agent', type = '$type', centre = '$centre', campaign = '$campaign' WHERE lead_id = '$lead_id' LIMIT 1") or die(mysql_error());
			
			mysql_query("DELETE FROM vericon.sales_packages_temp WHERE lead_id = '$lead_id'") or die(mysql_error());
		}
		
		echo "valid_" . strtolower($country[0]);
	}
}
elseif ($method == "add_au")
{
	$lead_id = $_GET["id"];
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
		mysql_query("INSERT INTO vericon.sales_packages_temp (lead_id, cli, plan) VALUES ('$lead_id', '$cli', '$plan')") or die(mysql_error());
		echo "added";
	}
}
elseif ($method == "add_nz")
{
	$lead_id = $_GET["id"];
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
		mysql_query("INSERT INTO vericon.sales_packages_temp (lead_id, cli, plan, provider, ac_number, adsl_provider, adsl_ac_number) VALUES ('$lead_id', '$cli', '$plan', '" . mysql_real_escape_string($provider) . "', '" . mysql_real_escape_string($ac_number) . "', '" . mysql_real_escape_string($adsl_provider) . "', '" . mysql_real_escape_string($adsl_ac_number) . "')") or die(mysql_error());
		echo "added";
	}
}
elseif ($method == "edit_au") //edit package
{
	$lead_id = $_GET["id"];
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
		mysql_query("DELETE FROM vericon.sales_packages_temp WHERE lead_id = '$lead_id' AND cli = '$cli2' LIMIT 1") or die(mysql_error());
		mysql_query("INSERT INTO vericon.sales_packages_temp (lead_id, cli, plan) VALUES ('$lead_id', '$cli', '$plan')") or die(mysql_error());
		echo "editted";
	}
}
elseif ($method == "nz_provider") //get provider
{
	$lead_id = $_GET["id"];
	$cli = $_GET["cli"];
	
	$q = mysql_query("SELECT provider FROM vericon.sales_packages_temp WHERE lead_id = '$lead_id' AND cli = '$cli'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo $data[0];
}
elseif ($method == "nz_ac_number") //get account number
{
	$lead_id = $_GET["id"];
	$cli = $_GET["cli"];
	
	$q = mysql_query("SELECT ac_number FROM vericon.sales_packages_temp WHERE lead_id = '$lead_id' AND cli = '$cli'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo $data[0];
}
elseif ($method == "nz_adsl_provider") //get adsl provider
{
	$lead_id = $_GET["id"];
	$cli = $_GET["cli"];
	
	$q = mysql_query("SELECT adsl_provider FROM vericon.sales_packages_temp WHERE lead_id = '$lead_id' AND cli = '$cli'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo $data[0];
}
elseif ($method == "nz_adsl_ac_number") //get adsl account number
{
	$lead_id = $_GET["id"];
	$cli = $_GET["cli"];
	
	$q = mysql_query("SELECT adsl_ac_number FROM vericon.sales_packages_temp WHERE lead_id = '$lead_id' AND cli = '$cli'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo $data[0];
}
elseif ($method == "edit_nz") //edit package
{
	$lead_id = $_GET["id"];
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
		mysql_query("DELETE FROM vericon.sales_packages_temp WHERE lead_id = '$lead_id' AND cli = '$cli2' LIMIT 1") or die(mysql_error());
		mysql_query("INSERT INTO vericon.sales_packages_temp (lead_id, cli, plan, provider, ac_number, adsl_provider, adsl_ac_number) VALUES ('$lead_id', '$cli', '$plan', '" . mysql_real_escape_string($provider) . "', '" . mysql_real_escape_string($ac_number) . "', '" . mysql_real_escape_string($adsl_provider) . "', '" . mysql_real_escape_string($adsl_ac_number) . "')") or die(mysql_error());
		echo "editted";
	}
}
elseif ($method == "delete")
{
	$lead_id = $_GET["id"];
	$cli = $_GET["cli"];
	
	mysql_query("DELETE FROM vericon.sales_packages_temp WHERE lead_id = '$lead_id' AND cli = '$cli' LIMIT 1") or die(mysql_error());
	
	echo "deleted";
}
elseif ($method == "cancel")
{
	$lead_id = $_GET["id"];
	
	mysql_query("DELETE FROM vericon.sales_customers_temp WHERE lead_id = '$lead_id' LIMIT 1") or die(mysql_error());
	
	mysql_query("DELETE FROM vericon.sales_packages_temp WHERE lead_id = '$lead_id'") or die(mysql_error());
}
elseif ($method == "submit_au")
{
	$lead_id = $_GET["id"];
	$agent = $_GET["agent"];
	$centre = $_GET["centre"];
	$campaign = $_GET["campaign"];
	$type = $_GET["type"];
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
	$position = strtoupper(trim($_GET["position"]));
	
	$q4 = mysql_query("SELECT * FROM vericon.sales_packages_temp WHERE lead_id = '$lead_id'");
	
	$today = date("Y-m-d");
	$last_week = date("Y-m-d", strtotime("-1 week"));
	$q5 = mysql_query("SELECT * FROM vericon.sales_customers WHERE abn = '$abn' AND DATE(approved_timestamp) BETWEEN '$last_week' AND '$today'") or die(mysql_error());
	
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
	elseif ($type == "Business" && mysql_num_rows($q5) != 0)
	{
		echo "Customer with same ABN already submitted";
	}
	elseif ($type == "Business" && $position == "")
	{
		echo "Please enter the customer's position in the business";
	}
	elseif (mysql_num_rows($q4) == 0)
	{
		echo "Please enter a package for the customer";
	}
	else
	{
		$pre_id = date("y") . str_pad(date("z"),3,"0",STR_PAD_LEFT);
		$q2 = mysql_query("SELECT COUNT(id) FROM vericon.sales_customers WHERE id LIKE '$pre_id%'");
		$num = mysql_fetch_row($q2);
		$random = (rand(0,9));
	
		$id = $pre_id . str_pad(($num[0]+1),3,"0",STR_PAD_LEFT) . $random;
		$timestamp = date("Y-m-d H:i:s");
		
		if ($email == "N/A") { $billing = "post"; } else { $billing = "email"; }
		
		mysql_query("INSERT INTO vericon.sales_customers (id, status, lead_id, timestamp, agent, centre, campaign, type, title, firstname, middlename, lastname, dob, email, mobile, billing, physical, postal, id_type, id_num, abn, position) VALUES ('$id', 'Queue', '$lead_id', '$timestamp', '$agent', '$centre', '$campaign', '$type', '$title', '" . mysql_real_escape_string($first) . "', '" . mysql_real_escape_string($middle) . "', '" . mysql_real_escape_string($last) . "', '" . mysql_real_escape_string($dob) . "', '" . mysql_real_escape_string($email) . "', '" . mysql_real_escape_string($mobile) . "', '$billing', '" . mysql_real_escape_string($physical) . "', '" . mysql_real_escape_string($postal) . "', '" . mysql_real_escape_string($id_type) . "', '" . mysql_real_escape_string($id_num) . "', '" . mysql_real_escape_string($abn) . "', '" . mysql_real_escape_string($position) . "')") or die(mysql_error());
		
		while ($p = mysql_fetch_assoc($q4))
		{
			mysql_query("INSERT INTO vericon.sales_packages (sid,cli,plan) VALUES ('$id','$p[cli]','$p[plan]')") or die(mysql_error());
		}
		
		mysql_query("DELETE FROM vericon.sales_packages_temp WHERE lead_id = '$lead_id'") or die(mysql_error());
		
		mysql_query("DELETE FROM vericon.sales_customers_temp WHERE lead_id = '$lead_id'") or die(mysql_error());
		
		echo "submitted<br>Your Sale ID is <b>" . $id . "</b>";
	}
}
elseif ($method == "submit_nz")
{
	$lead_id = $_GET["id"];
	$agent = $_GET["agent"];
	$centre = $_GET["centre"];
	$campaign = $_GET["campaign"];
	$type = $_GET["type"];
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
	$bus_name = strtoupper(trim($_GET["bus_name"]));
	$position = strtoupper(trim($_GET["position"]));
	
	$q4 = mysql_query("SELECT * FROM vericon.sales_packages_temp WHERE lead_id = '$lead_id'");
	
	$today = date("Y-m-d");
	$last_week = date("Y-m-d", strtotime("-1 week"));
	$q5 = mysql_query("SELECT * FROM vericon.sales_customers WHERE abn = '$abn' AND DATE(approved_timestamp) BETWEEN '$last_week' AND '$today'") or die(mysql_error());
	
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
	elseif (mysql_num_rows($q4) == 0)
	{
		echo "Please enter a package for the customer";
	}
	else
	{
		$pre_id = date("y") . str_pad(date("z"),3,"0",STR_PAD_LEFT);
		$q2 = mysql_query("SELECT COUNT(id) FROM vericon.sales_customers WHERE id LIKE '$pre_id%'");
		$num = mysql_fetch_row($q2);
		$random = (rand(0,9));
	
		$id = $pre_id . str_pad(($num[0]+1),3,"0",STR_PAD_LEFT) . $random;
		$timestamp = date("Y-m-d H:i:s");
		
		if ($email == "N/A") { $billing = "post"; } else { $billing = "email"; }
		
		mysql_query("INSERT INTO vericon.sales_customers (id, status, lead_id, timestamp, agent, centre, campaign, type, title, firstname, middlename, lastname, dob, email, mobile, billing, physical, postal, id_type, id_num, bus_name, position) VALUES ('$id', 'Queue', '$lead_id', '$timestamp', '$agent', '$centre', '$campaign', '$type', '$title', '" . mysql_real_escape_string($first) . "', '" . mysql_real_escape_string($middle) . "', '" . mysql_real_escape_string($last) . "', '" . mysql_real_escape_string($dob) . "', '" . mysql_real_escape_string($email) . "', '" . mysql_real_escape_string($mobile) . "', '$billing', '" . mysql_real_escape_string($physical) . "', '" . mysql_real_escape_string($postal) . "', '" . mysql_real_escape_string($id_type) . "', '" . mysql_real_escape_string($id_num) . "', '" . mysql_real_escape_string($bus_name) . "', '" . mysql_real_escape_string($position) . "')") or die(mysql_error());
		
		while ($p = mysql_fetch_assoc($q4))
		{
			mysql_query("INSERT INTO vericon.sales_packages (sid,cli,plan,provider,ac_number) VALUES ('$id','$p[cli]','$p[plan]','" . mysql_real_escape_string($p["provider"]) . "','" . mysql_real_escape_string($p["ac_number"]) . "')") or die(mysql_error());
		}
		
		mysql_query("DELETE FROM vericon.sales_packages_temp WHERE lead_id = '$lead_id'") or die(mysql_error());
		
		mysql_query("DELETE FROM vericon.sales_customers_temp WHERE lead_id = '$lead_id'") or die(mysql_error());
		
		echo "submitted<br>Your Sale ID is <b>" . $id . "</b>";
	}
}
?>