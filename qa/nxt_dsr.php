<?php
mysql_connect('localhost','vericon','18450be');

$date = date("Y-m-d");
$type = $argv[1];

if (file_exists("/var/dsr/" . date("Y/F/d.m.Y", strtotime($date)) . "/NXT/DSR_Accounts_" . date("d.m.Y", strtotime($date)) . "_" . $type . ".csv"))
{
	exit;
}

if (file_exists("/var/dsr/" . date("Y/F/d.m.Y", strtotime($date)) . "/NXT/DSR_Services_" . date("d.m.Y", strtotime($date)) . "_" . $type . ".csv"))
{
	exit;
}

$header = "DSR#,VeriCon ID,Recording,Sale ID,Account Status,Agent,Centre,Date of Sale,Telco Name,Rating,Title,First Name,Middle Name,Last Name,Position,DOB,Account Name,Company Number,Contact Name,Contact Phone Number,Service Number 1,Service Number 2,Service Number 3,Service Number 4,Bundle,Building Address,Street Number,Street Name,Street Type,Suburb,City/Town,Post Code,PO Box Number Only,Mail Street Number,Mail Street,Mail Suburb,Mail City/Town,Mail Post Code,Contract Months,Ongoing Credit,Once Off Credit,Promotions,Welcome Email,PayWay,Direct Debit,E-Bill,Mobile Contact,Home Number,Email Address,Additional Information,Billing Comment,Provisioning Comment,Mobile Comment,Other Comment,Address Verified";

$header1 = "Sale ID,Service Name,Service Number,Service Plan,Current Provider,Current SP Acc Number,Intact ADSL Connection,Pending Orders,Pending Order Closing,Service Status,Zone,Density,Urbanisation,Account Name";

$body = "";
$body1 = "";

$campaign_query = "campaign = 'Spirit Telco' OR campaign = 'Origin Communications'";

$dsr_num = "3" . date("y", strtotime($date)) . str_pad(date("z", strtotime($date)),3,"0",STR_PAD_LEFT);
if ($type == "Business")
{
	$sale_id = $dsr_num . "001";
}
else
{
	$q = mysql_query("SELECT COUNT(id) FROM vericon.qa_customers WHERE status = 'Approved' AND type = 'Business' AND DATE(timestamp) = '$date' AND (" . $campaign_query . ")") or die(mysql_error());
	$b_num = mysql_fetch_row($q);
	$sale_id = $dsr_num . str_pad(($b_num[0]+1),3,"0",STR_PAD_LEFT);
}

$q = mysql_query("SELECT * FROM vericon.qa_customers WHERE status = 'Approved' AND type = '$type' AND DATE(timestamp) = '$date' AND (" . $campaign_query . ") ORDER BY sale_timestamp ASC") or die(mysql_error());
while ($qa = mysql_fetch_assoc($q))
{
	$q1 = mysql_query("SELECT * FROM vericon.customers WHERE sale_id = '$qa[id]'") or die(mysql_error());
	$data = mysql_fetch_assoc($q1);
	
	$q2 = mysql_query("SELECT id,`group` FROM vericon.campaigns WHERE campaign = '" . mysql_real_escape_string($data["campaign"]) . "'") or die(mysql_error());
	$c = mysql_fetch_row($q2);
	$campaign_id = $c[0];
	
	$q7 = mysql_query("SELECT name FROM vericon.groups WHERE id = '$c[1]'") or die(mysql_error());
	$g = mysql_fetch_row($q7);
	$group = $g[0];
	
	$contract_months = 0;
	$p_i = 0;
	$a_i = 0;
	$b_i = 0;
	$p = 1;
	$a = 1;
	$p_packages = array();
	$a_packages = array();
	$b_packages = array();
	$p_cli = array();
	$p_plan = array();
	$p_provider = array();
	$p_ac_number = array();
	$a_cli = array();
	$a_plan = array();
	$a_provider = array();
	$a_ac_number = array();
	$q2 = mysql_query("SELECT * FROM vericon.packages WHERE id = '$data[id]' ORDER BY plan DESC") or die(mysql_error());
	while ($pack = mysql_fetch_assoc($q2))
	{
		$q3 = mysql_query("SELECT * FROM vericon.plan_matrix WHERE id = '$pack[plan]' AND campaign = '" . mysql_real_escape_string($campaign_id) . "'") or die(mysql_error());
		$da = mysql_fetch_assoc($q3);
		if ($da["type"] == "PSTN")
		{
			$p_packages[$p_i] = $pack["cli"] . "," . $da["s_id"] . "," . $pack["provider"] . "," . $pack["ac_number"];
			$p_i++;
		}
		elseif ($da["type"] == "ADSL Metro" || $da["type"] == "ADSL Regional")
		{
			$a_packages[$a_i] = $pack["cli"] . "," . $da["s_id"] . "," . $pack["adsl_provider"] . "," . $pack["adsl_ac_number"];
			$a_i++;
		}
		elseif ($da["type"] == "Bundle")
		{
			$b_packages[$b_i] = $pack["cli"] . "," . $da["s_id"] . "," . $pack["provider"] . "," . $pack["adsl_provider"] . "," . $pack["ac_number"] . "," . $pack["adsl_ac_number"];
			$b_i++;
		}
		
		if (preg_match("/24 Month Contract/", $da["name"]))
		{
			$contract = 24;
		}
		elseif (preg_match("/12 Month Contract/", $da["name"]))
		{
			$contract = 12;
		}
		else
		{
			$contract = 0;
		}
		
		if ($contract >= $contract_months)
		{
			$contract_months = $contract;
		}
	}
	
	if ($b_i >= 1)
	{
		foreach ($b_packages as $row)
		{
			$package = explode(",", $row);
			$p_cli[$p] = $package[0];
			$a_cli[$a] = "A" . substr($package[0],1);
			$p_plan[$p] = $package[1];
			$a_plan[$a] = $package[2];
			$p_provider[$p] = $package[3];
			$a_provider[$a] = $package[4];
			$p_ac_number[$p] = $package[5];
			$a_ac_number[$a] = $package[6];
			$p++;
			$a++;
		}
	}
	
	if ($p_i >= 1)
	{
		foreach ($p_packages as $row)
		{
			$package = explode(",", $row);
			$p_cli[$p] = $package[0];
			$p_plan[$p] = $package[1];
			$p_provider[$p] = $package[2];
			$p_ac_number[$p] = $package[3];
			$p++;
		}
	}
	
	if ($a_i >= 1)
	{
		foreach ($a_packages as $row)
		{
			$package = explode(",", $row);
			$a_cli[$a] = "A" . substr($package[0],1);
			$a_plan[$a] = $package[1];
			$a_provider[$a] = $package[2];
			$a_ac_number[$a] = $package[3];
			$a++;
		}
	}
	
	$b_type = "PSTN";
	
	if ($a_i >= 1)
	{
		$b_type = "ADSL";
	}
	
	if ($b_i >= 1)
	{
		$b_type = "ABUNDLE";
	}
	
	$building_type = "";
	$building_number = "";
	$building_number_suffix = "";
	$building_name = "";
	$street_number_start = "";
	$street_number_end = "";
	$street_name = "";
	$street_type = "";
	$suburb = "";
	$state = "";
	$postcode = "";
	$po_box_number = "";
	$mail_street_number = "";
	$mail_street = "";
	$mail_suburb = "";
	$mail_state = "";
	$mail_postcode = "";
	
	$physical = $data["physical"];
	$postal = $data["postal"];
	$resi_id_info = "";
	if ($data["type"] == "Residential")
	{
		$resi_id_info = $data["id_type"] . " - " . $data["id_num"];
	}
	
	if (substr($physical,0,2) == "MA")
	{
		$q5 = mysql_query("SELECT * FROM vericon.address WHERE id = '$physical'") or die(mysql_error());
		$data2 = mysql_fetch_assoc($q5);
		
		$building_type = $data2["building_type"];
		$building_number = $data2["building_number"];
		$building_number_suffix = $data2["building_number_suffix"];
		$building_name = $data2["building_name"];
		$street_number_start = $data2["number_first"];
		$street_number_end = $data2["number_last"];
		$street_name = $data2["street_name"];
		$street_type = $data2["street_type"];
		$suburb = $data2["suburb"];
		$city_town = $data2["city_town"];
		$postcode = $data2["postcode"];
		
		$building_address = preg_replace('!\s+!', ' ', $building_type . " " . $building_number . $building_number_suffix . " " . $building_name);
		if ($street_number_end != "")
		{
			$street_number = $street_number_start . "-" . $street_number_end;
		}
		else
		{
			$street_number = $street_number_start;
		}
	}
	
	if ($postal != $physical)
	{
		if (substr($postal,0,2) == "MA")
		{
			$q5 = mysql_query("SELECT * FROM vericon.address WHERE id = '$postal'") or die(mysql_error());
			$data2 = mysql_fetch_assoc($q5);
			
			if ($data2["building_type"] == "PO BOX")
			{
				$po_box_number = $data2["building_number"];
			}
			else
			{
				if ($data2["building_type"] == "LOT")
				{
					$mail_street_number = "LOT " . $data2["building_number"] . $data2["building_number_suffix"];
				}		
				elseif ($data2["building_type"] == "LEVEL")
				{
					$mail_street_number = "LEVEL " . $data2["building_number"] . $data2["building_number_suffix"] . "/";
				}
				elseif ($data2["building_type"] != "" && $data2["number_first"] != "")
				{
					$mail_street_number = $data2["building_type"] . " " . $data2["building_number"] . $data2["building_number_suffix"] . "/" . $data2["number_first"] . $data2["number_first_suffix"];
				}
				elseif ($data2["building_type"] != "" && $data2["number_first"] == "")
				{
					$mail_street_number = $data2["building_type"] . " " . $data2["building_number"] . $data2["building_number_suffix"];
				}
				
				if ($data2["number_last"] != "")
				{
					$mail_street_number .= "-" . $data2["number_last"];
				}
				
				if ($data2["building_name"] != "" && $data2["street_name"] == "")
				{
					$mail_street = $data2["building_name"];
				}
				elseif ($data2["building_name"] != "" && $data2["street_name"] != "")
				{
					$mail_street = $data2["building_name"] . " " . $data2["street_name"] . " " . $data2["street_type"];
				}
				else
				{
					$mail_street = $data2["street_name"] . " " . $data2["street_type"];
				}
			}
			
			$mail_suburb = $data2["suburb"];
			$mail_city_town = $data2["city_town"];
			$mail_postcode = $data2["postcode"];
		}
	}
	
	$q4 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$data[agent]'") or die(mysql_error());
	$user = mysql_fetch_row($q4);
	$agent = $user[0] . " " . $user[1];
	
	if ($data["type"] == "Business")
	{
		$account_name = $data["bus_name"];
	}
	else
	{
		$account_name = strtoupper($data["lastname"]) . ", " . $data["firstname"];
	}
	
	if ($data["email"] == "N/A")
	{
		$welcome = "N";
		$ebill = "N";
		$email = "";
	}
	else
	{
		$welcome = "Y";
		$ebill = "Y";
		$email = $data["email"];
	}
	
	if ($data["mobile"] == "N/A") { $mobile = ""; } else { $mobile = $data["mobile"]; }
	
	$body .= '"' . $dsr_num . '",';
	$body .= '"' . $data["id"] . '",';
	$body .= '="' . $p_cli[1] . '.gsm",';
	$body .= '"' . $sale_id . '",';
	$body .= '"' . $data["status"] . '",';
	$body .= '"' . $agent . '",';
	$body .= '"' . $data["centre"] . '",';
	$body .= '"' . date("d/m/Y", strtotime($qa["sale_timestamp"])) . '",';
	$body .= '"' . $data["campaign"] . '",';
	$body .= '"' . $data["campaign"] . " " . $data["type"] . '",';
	$body .= '"' . $data["title"] . '",';
	$body .= '"' . $data["firstname"] . '",';
	$body .= '"' . $data["middlename"] . '",';
	$body .= '"' . $data["lastname"] . '",';
	$body .= '"' . $data["position"] . '",';
	$body .= '"' . date("d/m/Y", strtotime($data["dob"])) . '",';
	$body .= '"' . $account_name . '",';
	$body .= '"' . '",';
	$body .= '"' . $data["firstname"] . " " . $data["lastname"] . '",';
	$body .= '="' . $p_cli[1] . '",';
	for ($i = 1; $i <= 4; $i++)
	{
		$body .= '="' . $p_cli[$i] . '",';
	}
	$body .= '"' . $b_type . '",';
	$body .= '"' . $building_address . '",';
	$body .= '"' . $street_number . '",';
	$body .= '"' . $street_name . '",';
	$body .= '"' . $street_type . '",';
	$body .= '"' . $suburb . '",';
	$body .= '"' . $city_town . '",';
	$body .= '"' . $postcode . '",';
	$body .= '"' . $po_box_number . '",';
	$body .= '"' . $mail_street_number . '",';
	$body .= '"' . $mail_street . '",';
	$body .= '"' . $mail_suburb . '",';
	$body .= '"' . $mail_city_town . '",';
	$body .= '"' . $mail_postcode . '",';
	$body .= '"' . $contract_months . '",';
	$body .= '"' . $data["ongoing_credit"] . '",';
	$body .= '"' . $data["onceoff_credit"] . '",';
	$body .= '"' . $data["promotions"] . '",';
	$body .= '"' . $welcome . '",';
	$body .= '"' . $data["payway"] . '",';
	$body .= '"' . $data["dd_type"] . '",';
	$body .= '"' . $ebill . '",';
	$body .= '="' . $mobile . '",';
	$body .= '"' . '",';
	$body .= '"' . $email . '",';
	$body .= '"' . $resi_id_info . '",';
	$body .= '"' . $data["billing_comments"] . '",';
	$body .= '"' . '",';
	$body .= '"' . '",';
	$body .= '"' . $data["other_comments"] . '",';
	$body .= '"' . "0" . '",';
	$body .= "\n";
	
	for ($i = 1; $i < $p; $i++)
	{
		$body1 .= '"' . $sale_id . '",';
		$body1 .= '="' . $p_cli[$i] . '",';
		$body1 .= '="' . $p_cli[$i] . '",';
		$body1 .= '="' . $p_plan[$i] . '",';
		$body1 .= '="' . $p_provider[$i] . '",';
		$body1 .= '="' . $p_ac_number[$i] . '",';
		$body1 .= '"' . '",';
		$body1 .= '"' . '",';
		$body1 .= '"' . '",';
		$body1 .= '"' . "Pending" . '",';
		$body1 .= '"' . '",';
		$body1 .= '"' . '",';
		$body1 .= '"' . '",';
		$body1 .= '"' . $account_name . '",';
		$body1 .= "\n";
	}
	
	for ($i = 1; $i < $a; $i++)
	{
		$body1 .= '"' . $sale_id . '",';
		$body1 .= '="' . $a_cli[$i] . '",';
		$body1 .= '="' . $a_cli[$i] . '",';
		$body1 .= '="' . $a_plan[$i] . '",';
		$body1 .= '="' . $a_provider[$i] . '",';
		$body1 .= '="' . $a_ac_number[$i] . '",';
		$body1 .= '"' . '",';
		$body1 .= '"' . '",';
		$body1 .= '"' . '",';
		$body1 .= '"' . "Pending" . '",';
		$body1 .= '"' . '",';
		$body1 .= '"' . '",';
		$body1 .= '"' . '",';
		$body1 .= '"' . $account_name . '",';
		$body1 .= "\n";
	}
	
	$sale_id++;
}

//Save DSR
$year_path = "/var/dsr/" . date("Y", strtotime($date));
$month_path = $year_path . "/" . date("F", strtotime($date));
$day_path = $month_path . "/" . date("d.m.Y", strtotime($date));
$new_path = $day_path . "/NXT";
$filename = $new_path . "/DSR_Accounts_" . date("d.m.Y", strtotime($date)) . "_" . $type . ".csv";
$filename1 = $new_path . "/DSR_Services_" . date("d.m.Y", strtotime($date)) . "_" . $type . ".csv";

if (!file_exists($year_path))
{
	mkdir($year_path,0777);
}

if (!file_exists($month_path))
{
	mkdir($month_path,0777);
}

if (!file_exists($day_path))
{
	mkdir($day_path,0777);
}

if (!file_exists($new_path))
{
	mkdir($new_path,0777);
}

if (!file_exists($filename))
{
	$fh = fopen($filename, 'w+') or die("can't open file");
	fwrite($fh, $header);
	fwrite($fh, "\n");
	fwrite($fh, $body);
	fclose($fh);
}

if (!file_exists($filename1))
{
	$fh1 = fopen($filename1, 'w+') or die("can't open file");
	fwrite($fh1, $header1);
	fwrite($fh1, "\n");
	fwrite($fh1, $body1);
	fclose($fh1);
}

exec("rm /var/vtmp/dsr_loading.txt");
?>