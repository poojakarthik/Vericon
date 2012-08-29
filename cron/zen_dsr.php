<?php
mysql_connect('localhost','vericon','18450be');

$date = date("Y-m-d", strtotime("-1 day"));
$type = $argv[1];

$header = "DSR#,Account ID,Account Number,VeriCon ID,Recording,Sale ID,Account Status,ADSL Status,Wireless Status,Agent,Centre,Date of Sale,Group,Whoisit,Telco Name,Rating,Industry,Title,First Name,Middle Name,Last Name,Position,DOB,Account Name,ABN,CLI 1,Plan 1,CLI 2,Plan 2,CLI 3,Plan 3,CLI 4,Plan 4,CLI 5,Plan 5,CLI 6,Plan 6,CLI 7,Plan 7,CLI 8,Plan 8,CLI 9,Plan 9,CLI 10,Plan 10,MSN 1,Mplan 1,MSN 2,Mplan 2,MSN 3,Mplan 3,WMSN 1,Wplan 1,WMSN 2,Wplan 2,ACLI,APLAN,Bundle,Building Type,Building Number,Building Number Suffix,Building Name,Street Number Start,Street Number End,Street Name,Street Type,Suburb,State,Post Code,PO Box Number Only,Mail Street Number,Mail Street,Mail Suburb,Mail State,Mail Post Code,Contract Months,Credit Offered,Ongoing Credit,Once Off Credit,Promotions,Welcome Email,PayWay,Direct Debit,E-Bill,Sale Type,Mobile Contact,Home Number,Current Provider,Email Address ,Additional Information,Billing Comment,Provisioning Comment,Mobile Comment,Other Comment";

$body = "";

$campaign_query = "campaign = 'Action Telecom' OR campaign = 'Alpha Talk' OR campaign = 'Telkokey' OR campaign = 'Venus Telecom' OR campaign = 'XLN Telecom'";

$dsr_num = "2" . date("y", strtotime($date)) . str_pad(date("z", strtotime($date)),3,"0",STR_PAD_LEFT);
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
	$a_cli = array();
	$a_plan = array();
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
		elseif ($da["type"] == "ADSL Metro" || $da["type"] == "ADSL Regional")
		{
			$a_packages[$a_i] = $pack["cli"] . "," . $da["s_id"];
			$a_i++;
		}
		elseif ($da["type"] == "Bundle")
		{
			$b_packages[$b_i] = $pack["cli"] . "," . $da["s_id"];
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
			$a++;
		}
	}
	
	$b_type = "PSTN";
	$a_status = "";
	
	if ($a_i >= 1)
	{
		$b_type = "ADSL";
		$a_status = "Pending";
	}
	
	if ($b_i >= 1)
	{
		$b_type = "ABUNDLE";
		$a_status = "Pending";
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
	
	if (substr($physical,0,2) == "GA")
	{
		$q5 = mysql_query("SELECT * FROM gnaf.ADDRESS_DETAIL WHERE address_detail_pid = '$physical'") or die(mysql_error());
		$data2 = mysql_fetch_assoc($q5);
		
		if ($data2["number_first"] == 0 && $data2["number_last"] == 0)
		{
			$building_type = "LOT";
			$building_number = $data2["lot_number"];
			$building_number_suffix = $data2["lot_number_suffix"];
		}
		
		if ($data2["flat_number"] != 0)
		{
			$q6 = mysql_query("SELECT `name` FROM gnaf.FLAT_TYPE_AUT WHERE code = '" . mysql_real_escape_string($data2["flat_type_code"]) . "'") or die(mysql_error());
			$ft = mysql_fetch_row($q6);
			
			$building_type = $ft[0];
			$building_number = $data2["flat_number"];
			$building_number_suffix = $data2["flat_number_suffix"];
		}
		elseif ($data2["level_number"] != 0)
		{
			$building_type = "LVL";
			$building_number = $data2["level_number"];
			$building_number_suffix = $data2["level_number_suffix"];
		}
		
		if ($data2["number_first"] != 0)
		{
			$street_number_start = $data2["number_first"] . $data2["number_first_suffix"];
		}
		
		
		if ($data2["number_last"] != 0)
		{
			$street_number_end = $data2["number_last"];
		}
		
		$street_name = $data2["street_name"];
	
		if ($data2["street_suffix_code"] != "")
		{
			$street_type = $data2["street_type_code"] . " " . $data2["street_suffix_code"];
		}
		else
		{
			$street_type = $data2["street_type_code"];
		}
		
		$suburb = $data2["locality_name"];
		$state = $data2["state"];
		$postcode = $data2["postcode"];
	}
	elseif (substr($physical,0,2) == "MA")
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
		$state = $data2["state"];
		$postcode = $data2["postcode"];
	}
	
	if ($postal != $physical)
	{
		if (substr($postal,0,2) == "GA")
		{
			$q5 = mysql_query("SELECT * FROM gnaf.ADDRESS_DETAIL WHERE address_detail_pid = '$postal'") or die(mysql_error());
			$data2 = mysql_fetch_assoc($q5);
			
			if ($data2["number_first"] == 0 && $data2["number_last"] == 0)
			{
				$mail_street_number = "LOT " . $data2["lot_number"] . $data2["lot_number_suffix"];
			}		
			elseif ($data2["flat_number"] != 0)
			{
				$q6 = mysql_query("SELECT `name` FROM gnaf.FLAT_TYPE_AUT WHERE code = '" . mysql_real_escape_string($data2["flat_type_code"]) . "'") or die(mysql_error());
				$ft = mysql_fetch_row($q6);
			
				$mail_street_number = $ft[0] . " " . $data2["flat_number"] . $data2["flat_number_suffix"] . "/";
			}
			elseif ($data2["level_number"] != 0)
			{
				$mail_street_number = "LEVEL " . $data2["level_number"] . $data2["level_number_suffix"] . "/";
			}
			
			if ($data2["number_first"] != 0)
			{
				$mail_street_number .= $data2["number_first"] . $data2["number_first_suffix"];
			}
			
			if ($data2["number_last"] != 0)
			{
				$mail_street_number .= "-" . $data2["number_last"];
			}
			
			$mail_street = $data2["street_name"] . " ";
		
			if ($data2["street_suffix_code"] != "")
			{
				$mail_street .= $data2["street_type_code"] . " " . $data2["street_suffix_code"];
			}
			else
			{
				$mail_street .= $data2["street_type_code"];
			}
			
			$mail_suburb = $data2["locality_name"];
			$mail_state = $data2["state"];
			$mail_postcode = $data2["postcode"];
		}
		elseif (substr($postal,0,2) == "MA")
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
			$mail_state = $data2["state"];
			$mail_postcode = $data2["postcode"];
		}
	}
	
	$q4 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$data[agent]'") or die(mysql_error());
	$user = mysql_fetch_row($q4);
	$agent = $user[0] . " " . $user[1];
	
	if ($data["type"] == "Business")
	{
		$abr = new SoapClient('http://abr.business.gov.au/abrxmlsearch/AbrXmlSearch.asmx?WSDL');

		$data3 = $abr->ABRSearchByABN(array('searchString'=>$data["abn"],'includeHistoricalDetails'=>'N','authenticationGuid'=>'24183d05-6498-4d78-89d3-2bd791c6bc17'));
		
		$xml = array(
			'organisationName'=>$data3->ABRPayloadSearchResults->response->businessEntity->mainName->organisationName,
			'tradingName'=>$data3->ABRPayloadSearchResults->response->businessEntity->mainTradingName->organisationName,
			'entityName'=>$data3->ABRPayloadSearchResults->response->businessEntity->legalName->familyName . ", " . $data3->ABRPayloadSearchResults->response->businessEntity->legalName->givenName
		);
		
		if ($xml['organisationName'] != null)
		{
			$account_name = $xml['organisationName'];
		}
		elseif ($xml['tradingName'] != null)
		{
			$account_name = $xml['tradingName'];
		}
		else
		{
			$account_name = $xml['entityName'];
		}
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
	$body .= '"' . '",';
	$body .= '"' . '",';
	$body .= '"' . $data["id"] . '",';
	$body .= '="' . $p_cli[1] . '.gsm",';
	$body .= '"' . $sale_id . '",';
	$body .= '"' . $data["status"] . '",';
	$body .= '"' . $a_status . '",';
	$body .= '"' . '",';
	$body .= '"' . $agent . '",';
	$body .= '"' . $data["centre"] . '",';
	$body .= '"' . date("d/m/Y", strtotime($qa["sale_timestamp"])) . '",';
	$body .= '"' . $group . '",';
	$body .= '"' . $campaign_id . '",';
	$body .= '"' . $data["campaign"] . '",';
	$body .= '"' . $data["campaign"] . " " . $data["type"] . '",';
	$body .= '"' . $data["industry"] . '",';
	$body .= '"' . $data["title"] . '",';
	$body .= '"' . $data["firstname"] . '",';
	$body .= '"' . $data["middlename"] . '",';
	$body .= '"' . $data["lastname"] . '",';
	$body .= '"' . $data["position"] . '",';
	$body .= '"' . date("d/m/Y", strtotime($data["dob"])) . '",';
	$body .= '"' . $account_name . '",';
	$body .= '"' . $data["abn"] . '",';
	for ($i = 1; $i <= 10; $i++)
	{
		$body .= '="' . $p_cli[$i] . '",';
		$body .= '"' . $p_plan[$i] . '",';
	}
	for ($i = 1; $i <= 3; $i++)
	{
		$body .= '"' . '",';
		$body .= '"' . '",';
	}
	for ($i = 1; $i <= 2; $i++)
	{
		$body .= '"' . '",';
		$body .= '"' . '",';
	}
	$body .= '"' . $a_cli[1] . '",';
	$body .= '"' . $a_plan[1] . '",';
	$body .= '"' . $b_type . '",';
	$body .= '"' . $building_type . '",';
	$body .= '"' . $building_number . '",';
	$body .= '"' . $building_number_suffix . '",';
	$body .= '"' . $building_name . '",';
	$body .= '"' . $street_number_start . '",';
	$body .= '"' . $street_number_end . '",';
	$body .= '"' . $street_name . '",';
	$body .= '"' . $street_type . '",';
	$body .= '"' . $suburb . '",';
	$body .= '"' . $state . '",';
	$body .= '"' . $postcode . '",';
	$body .= '"' . $po_box_number . '",';
	$body .= '"' . $mail_street_number . '",';
	$body .= '"' . $mail_street . '",';
	$body .= '"' . $mail_suburb . '",';
	$body .= '"' . $mail_state . '",';
	$body .= '"' . $mail_postcode . '",';
	$body .= '"' . $contract_months . '",';
	$body .= '"' . $data["credit"] . '",';
	$body .= '"' . $data["ongoing_credit"] . '",';
	$body .= '"' . $data["onceoff_credit"] . '",';
	$body .= '"' . $data["promotions"] . '",';
	$body .= '"' . $welcome . '",';
	$body .= '"' . $data["payway"] . '",';
	$body .= '"' . $data["dd_type"] . '",';
	$body .= '"' . $ebill . '",';
	$body .= '"' . "N" . '",';
	$body .= '="' . $mobile . '",';
	$body .= '"' . '",';
	$body .= '"' . "Telstra" . '",';
	$body .= '"' . $email . '",';
	$body .= '"' . $resi_id_info . '",';
	$body .= '"' . $data["billing_comments"] . '",';
	$body .= '"';
	if ($a > 1) { for ($i = 2; $i < $a; $i++) { $body .= "ACLI $i. $a_cli[$i]  --  $a_plan[$i]\n"; } }
	if ($p > 10) { for ($i = 11; $i < $p; $i++) { $body .= "CLI $i. $p_cli[$i]  --  $p_plan[$i]\n"; } }
	$body .= '",';
	$body .= '"' . '",';
	$body .= '"' . $data["other_comments"] . '",';
	$body .= "\n";
	
	$sale_id++;
}

//Save DSR
$year_path = "/var/dsr/" . date("Y", strtotime($date));
$month_path = $year_path . "/" . date("F", strtotime($date));
$day_path = $month_path . "/" . date("d.m.Y", strtotime($date));
$new_path = $day_path . "/ZEN";
$filename = $new_path . "/DSR_" . date("d.m.Y", strtotime($date)) . "_" . $type . ".csv";

if (!file_exists($year_path))
{
	mkdir($year_path,0755);
}

if (!file_exists($month_path))
{
	mkdir($month_path,0755);
}

if (!file_exists($day_path))
{
	mkdir($day_path,0755);
}

if (!file_exists($new_path))
{
	mkdir($new_path,0755);
}

if (!file_exists($filename))
{
	$fh = fopen($filename, 'w+') or die("can't open file");
	fwrite($fh, $header);
	fwrite($fh, "\n");
	fwrite($fh, $body);
	fclose($fh);
}
?>