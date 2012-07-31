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
elseif ($method == "rename_rec")
{
	$id = $_GET["id"];
	$file = $_GET["file"];
	
	if (file_exists("/var/vtmp/" . $file["name"]))
	{
		if (substr($file["name"],-3) == "gsm")
		{
			exec("mv /var/vtmp/" . $file["name"] . " /var/vtmp/wc_" . $id . ".gsm");
			echo 1;
		}
		elseif (substr($file["name"],-3) == "mp3")
		{
			exec("mv /var/vtmp/" . $file["name"] . " /var/vtmp/wc_" . $id . ".mp3");
			echo 1;
		}
		else
		{
			echo 0;
		}
	}
	else
	{
		echo 0;
	}
}
elseif ($method == "dd")
{
	$id = $_GET["id"];
	$type = $_GET["type"];
	$user = $_GET["user"];
	
	if ($type == "CC")
	{
		$cardholder = $_GET["cardholder"];
		$cardtype = $_GET["cardtype"];
		$cardnumber = $_GET["cardnumber"];
		$cardexpiry_m = $_GET["cardexpiry_m"];
		$cardexpiry_y = $_GET["cardexpiry_y"];
		
		if ($cardholder == "")
		{
			echo "Please enter the cardholder's name";
		}
		elseif ($cardtype == "")
		{
			echo "Please select the card type";
		}
		elseif ($cardnumber == "")
		{
			echo "Please enter the card number";
		}
		elseif ($cardexpiry_m == "" || ($cardexpiry_m < 1 || $cardexpiry_m > 12))
		{
			echo "Please enter a valid card expiry month";
		}
		elseif ($cardexpiry_y == "" || !preg_match("/^[0-9]{2}$/",$cardexpiry_y))
		{
			echo "Please enter a valid card expiry year";
		}
		else
		{
			$q = mysql_query("SELECT * FROM vericon.customers WHERE id = '$id'") or die(mysql_error());
			$data = mysql_fetch_assoc($q);
			
			$q2 = mysql_query("SELECT id FROM vericon.campaigns WHERE campaign = '" . mysql_real_escape_string($data["campaign"]) . "'") or die(mysql_error());
			$c = mysql_fetch_row($q2);
			$campaign_id = $c[0];
			
			$p_i = 0;
			$b_i = 0;
			$p = 1;
			$p_packages = array();
			$b_packages = array();
			$p_cli = array();
			$q2 = mysql_query("SELECT * FROM vericon.packages WHERE id = '$data[id]' ORDER BY plan DESC") or die(mysql_error());
			while ($pack = mysql_fetch_assoc($q2))
			{
				$q3 = mysql_query("SELECT * FROM vericon.plan_matrix WHERE id = '$pack[plan]' AND campaign = '" . mysql_real_escape_string($campaign_id) . "'") or die(mysql_error());
				$da = mysql_fetch_assoc($q3);
				if ($da["type"] == "PSTN")
				{
					$p_packages[$p_i] = $pack["cli"] . "," . $da["s_id"];
					$p_i++;
				}
				elseif ($da["type"] == "Bundle")
				{
					$b_packages[$b_i] = $pack["cli"] . "," . $da["s_id"];
					$b_i++;
				}
			}
			
			if ($b_i >= 1)
			{
				foreach ($b_packages as $row)
				{
					$package = explode(",", $row);
					$p_cli[$p] = $package[0];
					$p++;
				}
			}
			
			if ($p_i >= 1)
			{
				foreach ($p_packages as $row)
				{
					$package = explode(",", $row);
					$p_cli[$p] = $package[0];
					$p++;
				}
			}
			
			if (substr($data["postal"],0,2) == "GA")
			{
				$q5 = mysql_query("SELECT * FROM gnaf.ADDRESS_DETAIL WHERE address_detail_pid = '$data[postal]'") or die(mysql_error());
				$data2 = mysql_fetch_assoc($q5);
				
				if ($data2["number_first"] == 0 && $data2["number_last"] == 0)
				{
					$street = "LOT " . $data2["lot_number"] . $data2["lot_number_suffix"];
				}		
				elseif ($data2["flat_number"] != 0)
				{
					$street = $data2["flat_type_code"] . " " . $data2["flat_number"] . $data2["flat_number_suffix"] . "/";
				}
				elseif ($data2["level_number"] != 0)
				{
					$street = "LEVEL " . $data2["level_number"] . $data2["level_number_suffix"] . "/";
				}
				
				if ($data2["number_first"] != 0)
				{
					$street .= $data2["number_first"] . $data2["number_first_suffix"];
				}
				
				if ($data2["number_last"] != 0)
				{
					$street .= "-" . $data2["number_last"];
				}
				
				$street .= " " . $data2["street_name"] . " ";
			
				if ($data2["street_suffix_code"] != "")
				{
					$street .= $data2["street_type_code"] . " " . $data2["street_suffix_code"];
				}
				else
				{
					$street .= $data2["street_type_code"];
				}
				
				$suburb = $data2["locality_name"];
				$state = $data2["state"];
				$postcode = $data2["postcode"];
			}
			elseif (substr($data["postal"],0,2) == "MA")
			{
				$q5 = mysql_query("SELECT * FROM vericon.address WHERE id = '$data[postal]'") or die(mysql_error());
				$data2 = mysql_fetch_assoc($q5);
				
				if ($data2["building_type"] == "PO BOX")
				{
					$street = $data2["building_number"];
				}
				else
				{
					if ($data2["building_type"] == "LOT")
					{
						$street = "LOT " . $data2["building_number"] . $data2["building_number_suffix"];
					}		
					elseif ($data2["building_type"] == "LEVEL")
					{
						$street = "LEVEL " . $data2["building_number"] . $data2["building_number_suffix"] . "/";
					}
					elseif ($data2["building_type"] != "" && $data2["number_first"] != "")
					{
						$street = $data2["building_type"] . " " . $data2["building_number"] . $data2["building_number_suffix"] . "/" . $data2["number_first"] . $data2["number_first_suffix"];
					}
					elseif ($data2["building_type"] != "" && $data2["number_first"] == "")
					{
						$street = $data2["building_type"] . " " . $data2["building_number"] . $data2["building_number_suffix"];
					}
					
					if ($data2["number_last"] != "")
					{
						$street .= "-" . $data2["number_last"];
					}
					
					if ($data2["building_name"] != "" && $data2["street_name"] == "")
					{
						$street .= " " . $data2["building_name"];
					}
					elseif ($data2["building_name"] != "" && $data2["street_name"] != "")
					{
						$street .= " " . $data2["building_name"] . " " . $data2["street_name"] . " " . $data2["street_type"];
					}
					else
					{
						$street .= " " . $data2["street_name"] . " " . $data2["street_type"];
					}
				}
				
				$suburb = $data2["suburb"];
				$state = $data2["state"];
				$postcode = $data2["postcode"];
			}
			
			if ($data["middlename"] != "")
			{
				$name = $data["title"] . " " . $data["firstname"] . " " . $data["middlename"] . " " . $data["lastname"];
			}
			else
			{
				$name = $data["title"] . " " . $data["firstname"] . " " . $data["lastname"];
			}
			
			if ($data["email"] == "N/A") { $email = ""; } else { $email = $data["email"]; }
			
			$cardexpiry_m = str_pad($cardexpiry_m, 2, 0, STR_PAD_LEFT);
			$cardexpiry_y = str_pad($cardexpiry_y, 2, 0, STR_PAD_LEFT);
			
			$header = "Customer Number,Customer Name,Email Address,Phone Number,Street,Suburb,State,Post Code,Cardholder Name,Card Number,Card Expiry Month,Card Expiry Year";
			
			$body = "";
			
			$body .= '"SP' . $p_cli[1] . '",';
			$body .= '"' . $name . '",';
			$body .= '"' . $email . '",';
			$body .= '"' . $p_cli[1] . '",';
			$body .= '"' . $street . '",';
			$body .= '"' . $suburb . '",';
			$body .= '"' . $state . '",';
			$body .= '"' . $postcode . '",';
			$body .= '"' . $cardholder . '",';
			$body .= '"' . $cardnumber . '",';
			$body .= '"' . $cardexpiry_m . '",';
			$body .= '"' . $cardexpiry_y . '"';
			
			$filename = "/var/vtmp/DD_" . $id . "_" . date("Y-m-d_H-i-s") . ".csv";
			$fh = fopen($filename, 'w+') or die("can't open file");
			fwrite($fh, $header);
			fwrite($fh, "\n");
			fwrite($fh, $body);
			fclose($fh);
			
			$command = "zip -P " . md5($id) . md5(date("Y-m-d_H-i-s")) . " /var/vtmp/DD_" . $id . "_" . date("Y-m-d_H-i-s") . ".zip /var/vtmp/DD_" . $id . "_" . date("Y-m-d_H-i-s") . ".csv";
			exec($command);
			exec("rm /var/vtmp/DD_" . $id . "_" . date("Y-m-d_H-i-s") . ".csv");
			
			echo "doneSP" . $p_cli[1];
		}
	}
	elseif ($type == "Bank")
	{
		$accountname = $_GET["accountname"];
		$bsb = $_GET["bsb"];
		$accountnumber = $_GET["accountnumber"];
		
		if ($accountname == "")
		{
			echo "Please enter the customer's account name";
		}
		elseif ($bsb == "")
		{
			echo "Please select the customer's BSB";
		}
		elseif ($accountnumber == "")
		{
			echo "Please enter the customer's account number";
		}
		else
		{
			$q = mysql_query("SELECT * FROM vericon.customers WHERE id = '$id'") or die(mysql_error());
			$data = mysql_fetch_assoc($q);
			
			$q2 = mysql_query("SELECT id FROM vericon.campaigns WHERE campaign = '" . mysql_real_escape_string($data["campaign"]) . "'") or die(mysql_error());
			$c = mysql_fetch_row($q2);
			$campaign_id = $c[0];
			
			$p_i = 0;
			$b_i = 0;
			$p = 1;
			$p_packages = array();
			$b_packages = array();
			$p_cli = array();
			$q2 = mysql_query("SELECT * FROM vericon.packages WHERE id = '$data[id]' ORDER BY plan DESC") or die(mysql_error());
			while ($pack = mysql_fetch_assoc($q2))
			{
				$q3 = mysql_query("SELECT * FROM vericon.plan_matrix WHERE id = '$pack[plan]' AND campaign = '" . mysql_real_escape_string($campaign_id) . "'") or die(mysql_error());
				$da = mysql_fetch_assoc($q3);
				if ($da["type"] == "PSTN")
				{
					$p_packages[$p_i] = $pack["cli"] . "," . $da["s_id"];
					$p_i++;
				}
				elseif ($da["type"] == "Bundle")
				{
					$b_packages[$b_i] = $pack["cli"] . "," . $da["s_id"];
					$b_i++;
				}
			}
			
			if ($b_i >= 1)
			{
				foreach ($b_packages as $row)
				{
					$package = explode(",", $row);
					$p_cli[$p] = $package[0];
					$p++;
				}
			}
			
			if ($p_i >= 1)
			{
				foreach ($p_packages as $row)
				{
					$package = explode(",", $row);
					$p_cli[$p] = $package[0];
					$p++;
				}
			}
			
			if (substr($data["postal"],0,2) == "GA")
			{
				$q5 = mysql_query("SELECT * FROM gnaf.ADDRESS_DETAIL WHERE address_detail_pid = '$data[postal]'") or die(mysql_error());
				$data2 = mysql_fetch_assoc($q5);
				
				if ($data2["number_first"] == 0 && $data2["number_last"] == 0)
				{
					$street = "LOT " . $data2["lot_number"] . $data2["lot_number_suffix"];
				}		
				elseif ($data2["flat_number"] != 0)
				{
					$street = $data2["flat_type_code"] . " " . $data2["flat_number"] . $data2["flat_number_suffix"] . "/";
				}
				elseif ($data2["level_number"] != 0)
				{
					$street = "LEVEL " . $data2["level_number"] . $data2["level_number_suffix"] . "/";
				}
				
				if ($data2["number_first"] != 0)
				{
					$street .= $data2["number_first"] . $data2["number_first_suffix"];
				}
				
				if ($data2["number_last"] != 0)
				{
					$street .= "-" . $data2["number_last"];
				}
				
				$street .= " " . $data2["street_name"] . " ";
			
				if ($data2["street_suffix_code"] != "")
				{
					$street .= $data2["street_type_code"] . " " . $data2["street_suffix_code"];
				}
				else
				{
					$street .= $data2["street_type_code"];
				}
				
				$suburb = $data2["locality_name"];
				$state = $data2["state"];
				$postcode = $data2["postcode"];
			}
			elseif (substr($data["postal"],0,2) == "MA")
			{
				$q5 = mysql_query("SELECT * FROM vericon.address WHERE id = '$data[postal]'") or die(mysql_error());
				$data2 = mysql_fetch_assoc($q5);
				
				if ($data2["building_type"] == "PO BOX")
				{
					$street = $data2["building_number"];
				}
				else
				{
					if ($data2["building_type"] == "LOT")
					{
						$street = "LOT " . $data2["building_number"] . $data2["building_number_suffix"];
					}		
					elseif ($data2["building_type"] == "LEVEL")
					{
						$street = "LEVEL " . $data2["building_number"] . $data2["building_number_suffix"] . "/";
					}
					elseif ($data2["building_type"] != "" && $data2["number_first"] != "")
					{
						$street = $data2["building_type"] . " " . $data2["building_number"] . $data2["building_number_suffix"] . "/" . $data2["number_first"] . $data2["number_first_suffix"];
					}
					elseif ($data2["building_type"] != "" && $data2["number_first"] == "")
					{
						$street = $data2["building_type"] . " " . $data2["building_number"] . $data2["building_number_suffix"];
					}
					
					if ($data2["number_last"] != "")
					{
						$street .= "-" . $data2["number_last"];
					}
					
					if ($data2["building_name"] != "" && $data2["street_name"] == "")
					{
						$street .= " " . $data2["building_name"];
					}
					elseif ($data2["building_name"] != "" && $data2["street_name"] != "")
					{
						$street .= " " . $data2["building_name"] . " " . $data2["street_name"] . " " . $data2["street_type"];
					}
					else
					{
						$street .= " " . $data2["street_name"] . " " . $data2["street_type"];
					}
				}
				
				$suburb = $data2["suburb"];
				$state = $data2["state"];
				$postcode = $data2["postcode"];
			}
			
			if ($data["middlename"] != "")
			{
				$name = $data["title"] . " " . $data["firstname"] . " " . $data["middlename"] . " " . $data["lastname"];
			}
			else
			{
				$name = $data["title"] . " " . $data["firstname"] . " " . $data["lastname"];
			}
			
			if ($data["email"] == "N/A") { $email = ""; } else { $email = $data["email"]; }
			
			$cardexpiry_m = str_pad($cardexpiry_m, 2, 0, STR_PAD_LEFT);
			$cardexpiry_y = str_pad($cardexpiry_y, 2, 0, STR_PAD_LEFT);
			
			$header = "Customer Number,Customer Name,Email Address,Phone Number,Street,Suburb,State,Post Code,Account Name,BSB,Account Number";
			
			$body = "";
			
			$body .= '"SP' . $p_cli[1] . '",';
			$body .= '"' . $name . '",';
			$body .= '"' . $email . '",';
			$body .= '"' . $p_cli[1] . '",';
			$body .= '"' . $street . '",';
			$body .= '"' . $suburb . '",';
			$body .= '"' . $state . '",';
			$body .= '"' . $postcode . '",';
			$body .= '"' . $accountname . '",';
			$body .= '"' . $bsb . '",';
			$body .= '"' . $accountnumber . '"';
			
			$filename = "/var/vtmp/DD_" . $id . "_" . date("Y-m-d_H-i-s") . ".csv";
			$fh = fopen($filename, 'w+') or die("can't open file");
			fwrite($fh, $header);
			fwrite($fh, "\n");
			fwrite($fh, $body);
			fclose($fh);
			
			$command = "zip -P " . md5($id) . md5(date("Y-m-d_H-i-s")) . " /var/vtmp/DD_" . $id . ".zip /var/vtmp/DD_" . $id . "_" . date("Y-m-d_H-i-s") . ".csv";
			exec($command);
			exec("rm /var/vtmp/DD_" . $id . "_" . date("Y-m-d_H-i-s") . ".csv");
			
			echo "doneSP" . $p_cli[1];
		}
	}
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
		
		mysql_query("INSERT INTO vericon.welcome (id, status, centre, timestamp, user, cancellation_reason) VALUES ('$id', 'Cancel', '$data[centre]', '$timestamp', '$user', '" . mysql_real_escape_string($reason) . "')") or die(mysql_error());
		
		mysql_query("INSERT INTO vericon.customers_notes (id, user, timestamp, type, note) VALUES ('$id', '$user', '$timestamp', 'Cancelled', '" . mysql_real_escape_string($note) . "')") or die(mysql_error());
		
		mysql_query("UPDATE vericon.customers SET status = 'Cancelled', last_edit_by = '$user' WHERE id = '$id' LIMIT 1") or die(mysql_error());
		
		mysql_query("INSERT INTO vericon.customers_log (id, status, last_edit_by, industry, lead_id, sale_id, timestamp, agent, centre, campaign, type, title, firstname, middlename, lastname, dob, email, mobile, billing, welcome, promotions, physical, postal, id_type, id_num, abn, position, credit, payway, dd_type, billing_comments, other_comments) VALUES ('$data[id]', 'Cancelled', '$user', '$data[industry]', '$data[lead_id]', '$data[sale_id]', '$data[timestamp]', '$data[agent]', '$data[centre]', '$data[campaign]', '$data[type]', '" . mysql_real_escape_string($data["title"]) . "', '" . mysql_real_escape_string($data["firstname"]) . "', '" . mysql_real_escape_string($data["middlename"]) . "', '" . mysql_real_escape_string($data["lastname"]) . "', '" . mysql_real_escape_string($data["dob"]) . "', '" . mysql_real_escape_string($data["email"]) . "', '" . mysql_real_escape_string($data["mobile"]) . "', '$data[billing]', '$data[welcome]', '$data[promotions]', '$data[physical]', '$data[postal]', '" . mysql_real_escape_string($data["id_type"]) . "', '" . mysql_real_escape_string($data["id_num"]) . "', '" . mysql_real_escape_string($data["abn"]) . "', '" . mysql_real_escape_string($data["position"]) . "', '" . mysql_real_escape_string($data["credit"]) . "', '" . mysql_real_escape_string($data["payway"]) . "', '" . mysql_real_escape_string($data["dd_type"]) . "', '" . mysql_real_escape_string($data["billing_comments"]) . "', '" . mysql_real_escape_string($data["other_comments"]) . "')") or die(mysql_error());
		
		if (file_exists("/var/vtmp/wc_" . $data["id"] . ".gsm"))
		{
			$command = "mv /var/vtmp/wc_" . $data["id"] . ".gsm /var/rec/" . md5($data["id"] . date("Y-m-d H:i:s")) . ".gsm";
			exec($command);
			
			mysql_query("INSERT INTO vericon.recordings (id, sale_id, type, name) VALUES ('$data[id]', '$data[sale_id]', 'Welcome Call',  '" . mysql_real_escape_string(md5($data["id"] . date("Y-m-d H:i:s")) . ".gsm") . "')") or die(mysql_error());
		}
		elseif (file_exists("/var/vtmp/wc_" . $data["id"] . ".mp3"))
		{
			$command = "mv /var/vtmp/wc_" . $data["id"] . ".mp3 /var/rec/" . md5($data["id"] . date("Y-m-d H:i:s")) . ".mp3";
			exec($command);
			
			mysql_query("INSERT INTO vericon.recordings (id, sale_id, type, name) VALUES ('$data[id]', '$data[sale_id]', 'Welcome Call',  '" . mysql_real_escape_string(md5($data["id"] . date("Y-m-d H:i:s")) . ".mp3") . "')") or die(mysql_error());
		}
		
		exec("rm /var/vtmp/DD_" . $id . ".zip");
		
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
	$dd = $_GET["dd"];
	
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
		
		mysql_query("INSERT INTO vericon.welcome (id, status, centre, timestamp, user, dd) VALUES ('$id', 'Cancel', '$data[centre]', '$timestamp', '$user', '$dd')") or die(mysql_error());
		
		mysql_query("UPDATE vericon.customers SET status = 'Waiting Provisioning', last_edit_by = '$user', title = '$title', firstname = '" . mysql_real_escape_string($first) . "', middlename = '" . mysql_real_escape_string($middle) . "', lastname = '" . mysql_real_escape_string($last) . "', dob = '" . mysql_real_escape_string($dob) . "', email = '" . mysql_real_escape_string($email) . "', mobile = '" . mysql_real_escape_string($mobile) . "', billing = '$billing', welcome = '$billing', physical = '$physical', postal = '$postal', id_type = '" . mysql_real_escape_string($id_type) . "', id_num = '" . mysql_real_escape_string($id_num) . "', abn = '" . mysql_real_escape_string($abn) . "', position = '" . mysql_real_escape_string($position) . "', credit = '" . mysql_real_escape_string($credit) . "', payway = '" . mysql_real_escape_string($payway) . "', dd_type = '" . mysql_real_escape_string($dd_type) . "' WHERE id = '$id' LIMIT 1") or die(mysql_error());
		
		mysql_query("INSERT INTO vericon.customers_log (id, status, last_edit_by, industry, lead_id, sale_id, timestamp, agent, centre, campaign, type, title, firstname, middlename, lastname, dob, email, mobile, billing, welcome, promotions, physical, postal, id_type, id_num, abn, position, credit, payway, dd_type, billing_comments, other_comments) VALUES ('$data[id]', 'Waiting Provisioning', '$user', '$data[industry]', '$data[lead_id]', '$data[sale_id]', '$data[timestamp]', '$data[agent]', '$data[centre]', '$data[campaign]', '$data[type]', '" . mysql_real_escape_string($title) . "', '" . mysql_real_escape_string($first) . "', '" . mysql_real_escape_string($middle) . "', '" . mysql_real_escape_string($last) . "', '" . mysql_real_escape_string($dob) . "', '" . mysql_real_escape_string($email) . "', '" . mysql_real_escape_string($mobile) . "', '$billing', '$billing', '$data[promotions]', '$physical', '$postal', '" . mysql_real_escape_string($id_type) . "', '" . mysql_real_escape_string($id_num) . "', '" . mysql_real_escape_string($abn) . "', '" . mysql_real_escape_string($position) . "', '" . mysql_real_escape_string($credit) . "', '" . mysql_real_escape_string($payway) . "', '" . mysql_real_escape_string($dd_type) . "', '" . mysql_real_escape_string($data["billing_comments"]) . "', '" . mysql_real_escape_string($data["other_comments"]) . "')") or die(mysql_error());
		
		if (file_exists("/var/vtmp/wc_" . $data["id"] . ".gsm"))
		{
			$command = "mv /var/vtmp/wc_" . $data["id"] . ".gsm /var/rec/" . md5($data["id"] . date("Y-m-d H:i:s")) . ".gsm";
			exec($command);
			
			mysql_query("INSERT INTO vericon.recordings (id, sale_id, type, name) VALUES ('$data[id]', '$data[sale_id]', 'Welcome Call',  '" . mysql_real_escape_string(md5($data["id"] . date("Y-m-d H:i:s")) . ".gsm") . "')") or die(mysql_error());
		}
		elseif (file_exists("/var/vtmp/wc_" . $data["id"] . ".mp3"))
		{
			$command = "mv /var/vtmp/wc_" . $data["id"] . ".mp3 /var/rec/" . md5($data["id"] . date("Y-m-d H:i:s")) . ".mp3";
			exec($command);
			
			mysql_query("INSERT INTO vericon.recordings (id, sale_id, type, name) VALUES ('$data[id]', '$data[sale_id]', 'Welcome Call',  '" . mysql_real_escape_string(md5($data["id"] . date("Y-m-d H:i:s")) . ".mp3") . "')") or die(mysql_error());
		}
		
		if (file_exists("/var/vtmp/DD_" . $id . ".zip"))
		{
			$srcFile = '/var/vtmp/DD_' . $id . '.zip';
			$dstFile = '/payway_files/DD_' . $id . '.zip';
			
			// Create connection the the remote host
			$conn = ssh2_connect('ftp.telecaregroup.com', 2422);
			ssh2_auth_password($conn, 'vericonsys', 'v3r1n0(159');
			
			// Create SFTP session
			$sftp = ssh2_sftp($conn);
			
			$sftpStream = @fopen('ssh2.sftp://'.$sftp.$dstFile, 'w');
			
			$data_to_send = @file_get_contents($srcFile);
			
			if (!$sftpStream) {
				echo "Could not open remote file: $dstFile";
			}
			elseif ($data_to_send === false) {
				echo "Could not open local file: $srcFile.";
			}
			elseif (@fwrite($sftpStream, $data_to_send) === false) {
				echo "Could not send data from file: $srcFile.";
			}
			
			fclose($sftpStream);
			
			exec("rm /var/vtmp/DD_" . $id . ".zip");
		}
		
		mysql_query("DELETE FROM vericon.welcome_lock WHERE id = '$id' LIMIT 1") or die(mysql_error());
		
		echo "submitted";
	}
}
elseif ($method == "add")
{
	$id = $_GET["id"];
	$cli = $_GET["cli"];
	$plan = $_GET["plan"];
	$week = date("W");
	$user = $_GET["user"];
	$timestamp = date("Y-m-d H:i:s");
	
	$ch2 = mysql_query("SELECT COUNT(cli) FROM vericon.sales_packages WHERE cli = '" . mysql_real_escape_string($cli) . "' AND WEEK(timestamp) = '$week'");
	$check2 = mysql_fetch_row($ch2);
	
	$ch3 = mysql_query("SELECT COUNT(cli) FROM vericon.sct_dnc WHERE cli = '" . mysql_real_escape_string($cli) . "'");
	$check3 = mysql_fetch_row($ch3);
	
	$ch4 = mysql_query("SELECT COUNT(cli) FROM vericon.packages WHERE cli = '" . mysql_real_escape_string($cli) . "' AND WEEK(timestamp) = '$week'");
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
		mysql_query("INSERT INTO vericon.packages_log (id, cli, plan, status, edit_by, timestamp) VALUES ('$id', '$cli', '$plan', 'P', '$user', '$timestamp')") or die(mysql_error());
		
		mysql_query("INSERT INTO vericon.packages (id, cli, plan, status, edit_by) VALUES ('$id', '$cli', '$plan', 'P', '$user')") or die(mysql_error());
		
		echo "added";
	}
}
elseif ($method == "edit") //edit package
{
	$id = $_GET["id"];
	$cli = $_GET["cli"];
	$plan = $_GET["plan"];
	$week = date("W");
	$user = $_GET["user"];
	$timestamp = date("Y-m-d H:i:s");
	
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
		mysql_query("INSERT INTO vericon.packages_log (id, cli, plan, status, edit_by, timestamp) VALUES ('$id', '$cli', '$plan', 'P', '$user', '$timestamp')") or die(mysql_error());
		
		mysql_query("UPDATE vericon.packages SET plan = '$plan', edit_by = '$user' WHERE id = '$id' AND cli = '$cli'") or die(mysql_error());
		
		echo "editted";
	}
}
elseif ($method == "upgrade")
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
	$dd = $_GET["dd"];
	
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
		
		mysql_query("INSERT INTO vericon.welcome (id, status, centre, timestamp, user, dd) VALUES ('$id', 'Cancel', '$data[centre]', '$timestamp', '$user', '$dd')") or die(mysql_error());
		
		mysql_query("UPDATE vericon.customers SET status = 'Waiting Provisioning', last_edit_by = '$user', title = '$title', firstname = '" . mysql_real_escape_string($first) . "', middlename = '" . mysql_real_escape_string($middle) . "', lastname = '" . mysql_real_escape_string($last) . "', dob = '" . mysql_real_escape_string($dob) . "', email = '" . mysql_real_escape_string($email) . "', mobile = '" . mysql_real_escape_string($mobile) . "', billing = '$billing', welcome = '$billing', physical = '$physical', postal = '$postal', id_type = '" . mysql_real_escape_string($id_type) . "', id_num = '" . mysql_real_escape_string($id_num) . "', abn = '" . mysql_real_escape_string($abn) . "', position = '" . mysql_real_escape_string($position) . "', credit = '" . mysql_real_escape_string($credit) . "', payway = '" . mysql_real_escape_string($payway) . "', dd_type = '" . mysql_real_escape_string($dd_type) . "' WHERE id = '$id' LIMIT 1") or die(mysql_error());
		
		mysql_query("INSERT INTO vericon.customers_log (id, status, last_edit_by, industry, lead_id, sale_id, timestamp, agent, centre, campaign, type, title, firstname, middlename, lastname, dob, email, mobile, billing, welcome, promotions, physical, postal, id_type, id_num, abn, position, credit, payway, dd_type, billing_comments, other_comments) VALUES ('$data[id]', 'Waiting Provisioning', '$user', '$data[industry]', '$data[lead_id]', '$data[sale_id]', '$data[timestamp]', '$data[agent]', '$data[centre]', '$data[campaign]', '$data[type]', '" . mysql_real_escape_string($title) . "', '" . mysql_real_escape_string($first) . "', '" . mysql_real_escape_string($middle) . "', '" . mysql_real_escape_string($last) . "', '" . mysql_real_escape_string($dob) . "', '" . mysql_real_escape_string($email) . "', '" . mysql_real_escape_string($mobile) . "', '$billing', '$billing', '$data[promotions]', '$physical', '$postal', '" . mysql_real_escape_string($id_type) . "', '" . mysql_real_escape_string($id_num) . "', '" . mysql_real_escape_string($abn) . "', '" . mysql_real_escape_string($position) . "', '" . mysql_real_escape_string($credit) . "', '" . mysql_real_escape_string($payway) . "', '" . mysql_real_escape_string($dd_type) . "', '" . mysql_real_escape_string($data["billing_comments"]) . "', '" . mysql_real_escape_string($data["other_comments"]) . "')") or die(mysql_error());
		
		if (file_exists("/var/vtmp/wc_" . $data["id"] . ".gsm"))
		{
			$command = "mv /var/vtmp/wc_" . $data["id"] . ".gsm /var/rec/" . md5($data["id"] . date("Y-m-d H:i:s")) . ".gsm";
			exec($command);
			
			mysql_query("INSERT INTO vericon.recordings (id, sale_id, type, name) VALUES ('$data[id]', '$data[sale_id]', 'Welcome Call',  '" . mysql_real_escape_string(md5($data["id"] . date("Y-m-d H:i:s")) . ".gsm") . "')") or die(mysql_error());
		}
		elseif (file_exists("/var/vtmp/wc_" . $data["id"] . ".mp3"))
		{
			$command = "mv /var/vtmp/wc_" . $data["id"] . ".mp3 /var/rec/" . md5($data["id"] . date("Y-m-d H:i:s")) . ".mp3";
			exec($command);
			
			mysql_query("INSERT INTO vericon.recordings (id, sale_id, type, name) VALUES ('$data[id]', '$data[sale_id]', 'Welcome Call',  '" . mysql_real_escape_string(md5($data["id"] . date("Y-m-d H:i:s")) . ".mp3") . "')") or die(mysql_error());
		}
		
		if (file_exists("/var/vtmp/DD_" . $id . ".zip"))
		{
			$srcFile = '/var/vtmp/DD_' . $id . '.zip';
			$dstFile = '/payway_files/DD_' . $id . '.zip';
			
			// Create connection the the remote host
			$conn = ssh2_connect('ftp.telecaregroup.com', 2422);
			ssh2_auth_password($conn, 'vericonsys', 'v3r1n0(159');
			
			// Create SFTP session
			$sftp = ssh2_sftp($conn);
			
			$sftpStream = @fopen('ssh2.sftp://'.$sftp.$dstFile, 'w');
			
			$data_to_send = @file_get_contents($srcFile);
			
			if (!$sftpStream) {
				echo "Could not open remote file: $dstFile";
			}
			elseif ($data_to_send === false) {
				echo "Could not open local file: $srcFile.";
			}
			elseif (@fwrite($sftpStream, $data_to_send) === false) {
				echo "Could not send data from file: $srcFile.";
			}
			
			fclose($sftpStream);
			
			exec("rm /var/vtmp/DD_" . $id . ".zip");
		}
		
		mysql_query("DELETE FROM vericon.welcome_lock WHERE id = '$id' LIMIT 1") or die(mysql_error());
		
		echo "submitted";
	}
}
?>