<?php
$mysqli = new mysqli('localhost','vericon','18450be');
$date = date("Y-m-d");

$q = $mysqli->query("SELECT * FROM `vericon`.`qa_customers` WHERE `status` = 'Approved' AND DATE(`timestamp`) = '$date' ORDER BY `sale_timestamp` ASC") or die($mysqli->error);
while ($qa = $q->fetch_assoc())
{
	$q1 = $mysqli->query("SELECT * FROM `vericon`.`sales_customers` WHERE `id` = '" . $qa["id"] . "'") or die($mysqli->error);
	$data = $q1->fetch_assoc();
	$q1->free();
	
	if ($data["industry"] == "SELF")
	{
		$q2 = $mysqli->query("SELECT `id`,`group` FROM `vericon`.`campaigns` WHERE `campaign` = '" . $mysqli->real_escape_string($data["campaign"]) . "'") or die($mysqli->error);
		$c = $q2->fetch_row();
		$campaign_id = $c[0];
		$group = $c[1];
		$q2->free();
		
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
				$bus_name = $xml['organisationName'];
			}
			elseif ($xml['tradingName'] != null)
			{
				$bus_name = $xml['tradingName'];
			}
			elseif ($xml['entityName'] != ", ")
			{
				$bus_name = $xml['entityName'];
			}
			else
			{
				$bus_name = strtoupper($data["lastname"] . ", " . $data["firstname"]);
			}
		}
		else
		{
			$bus_name = "";
		}
		
		if ($data["email"] == "N/A") {
			$email = "";
			$delivery = "P";
		} else {
			$email = $data["email"];
			$delivery = "E";
		}
		
		$p_i = 0;
		$a_i = 0;
		$b_i = 0;
		$p = 1;
		$p_packages = array();
		$a_packages = array();
		$b_packages = array();
		$p_cli = array();
		$p_plan = array();
		$a_cli = array();
		$a_plan = array();
		
		$q2 = $mysqli->query("SELECT * FROM `vericon`.`sales_packages` WHERE `sid` = '" . $data["id"] . "' ORDER BY `plan` DESC") or die($mysqli->error);
		while ($pack = $q2->fetch_assoc())
		{
			$q3 = $mysqli->query("SELECT * FROM `vericon`.`plan_matrix` WHERE `id` = '" . $pack["plan"] . "' AND `campaign` = '" . $mysqli->real_escape_string($campaign_id) . "'") or die($mysql->error);
			$da = $q3->fetch_assoc();
			$q3->free();
			if ($da["type"] == "PSTN")
			{
				$p_packages[$p_i] = $pack["cli"] . "," . $da["id"];
				$p_i++;
			}
			elseif ($da["type"] == "ADSL Metro" || $da["type"] == "ADSL Regional")
			{
				$a_packages[$a_i] = $pack["cli"] . "," . $da["id"];
				$a_i++;
			}
			elseif ($da["type"] == "Bundle")
			{
				$b_packages[$b_i] = $pack["cli"] . "," . $da["id"];
				$b_i++;
			}
		}
		$q2->free();
		
		if ($b_i >= 1)
		{
			foreach ($b_packages as $row)
			{
				$package = explode(",", $row);
				$p_cli[$p] = $package[0];
				$p_plan[$p] = $package[1];
				$p++;
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
				$p_cli[$p] = $package[0];
				$p_plan[$p] = $package[1];
				$p++;
			}
		}
		
		$package_type = "Landline";
		
		if ($a_i >= 1)
		{
			$package_type = "Broadband";
		}
		if ($b_i >= 1)
		{
			$package_type = "Bundle";
		}
		
		$mail_street_number = "";
		$mail_street = "";
		$mail_suburb = "";
		$mail_state = "";
		$mail_postcode = "";
		
		if (substr($data["postal"],0,2) == "GA")
		{
			$q5 = $mysqli->query("SELECT * FROM `gnaf`.`ADDRESS_DETAIL` WHERE `address_detail_pid` = '" . $data["postal"] . "'") or die($mysqli->error);
			$data2 = $q5->fetch_assoc();
			$q5->free();
			
			if ($data2["number_first"] == 0 && $data2["number_last"] == 0)
			{
				$mail_street_number = "Lot " . $data2["lot_number"] . $data2["lot_number_suffix"];
			}		
			elseif ($data2["flat_number"] != 0)
			{
				$q6 = $mysqli->query("SELECT `name` FROM `gnaf`.`FLAT_TYPE_AUT` WHERE `code` = '" . $mysqli->real_escape_string($data2["flat_type_code"]) . "'") or die($mysqli->error);
				$ft = $q6->fetch_row();
				$q6->free();
				
				$mail_street_number = $ft[0] . " " . $data2["flat_number"] . $data2["flat_number_suffix"];
			}
			elseif ($data2["level_number"] != 0)
			{
				$mail_street_number = "Level " . $data2["level_number"] . $data2["level_number_suffix"];
			}
			if ($data2["number_first"] != 0)
			{
				$mail_street_number .= " " . $data2["number_first"] . $data2["number_first_suffix"];
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
			
			$address_line1 = trim(preg_replace('!\s+!', ' ', ucwords(strtolower($mail_street_number . " " . $mail_street))));
			$address_line2 = trim(preg_replace('!\s+!', ' ', strtoupper($mail_suburb . " " . $mail_state . " " . $mail_postcode)));
		}
		elseif (substr($data["postal"],0,2) == "MA")
		{
			$q5 = $mysqli->query("SELECT * FROM vericon.address WHERE id = '" . $data["postal"] . "'") or die($mysqli->error);
			$data2 = $q5->fetch_assoc();
			$q5->free();
			
			if ($data2["building_type"] == "PO BOX")
			{
				$address_line1 = trim("PO Box " . $data2["building_number"]);
			}
			else
			{
				if ($data2["building_type"] == "LOT")
				{
					$mail_street_number = "Lot " . $data2["building_number"] . $data2["building_number_suffix"];
				}		
				elseif ($data2["building_type"] == "LEVEL")
				{
					$mail_street_number = "Level " . $data2["building_number"] . $data2["building_number_suffix"];
				}
				elseif ($data2["building_type"] != "" && $data2["number_first"] != "")
				{
					$mail_street_number = $data2["building_type"] . " " . $data2["building_number"] . $data2["building_number_suffix"] . " " . $data2["number_first"] . $data2["number_first_suffix"];
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
				
				$address_line1 = trim(preg_replace('!\s+!', ' ', ucwords(strtolower($mail_street_number . " " . $mail_street))));
			}
			
			$mail_suburb = $data2["suburb"];
			$mail_state = $data2["state"];
			$mail_postcode = $data2["postcode"];
			
			$address_line2 = trim(preg_replace('!\s+!', ' ', strtoupper($mail_suburb . " " . $mail_state . " " . $mail_postcode)));
		}
		
		$mysqli->query("INSERT IGNORE INTO `letters`.`customers` (`id`, `letter_type`, `sale_date`, `group`, `campaign`, `type`, `title`, `firstname`, `lastname`, `bus_name`, `email`, `address_line1`, `address_line2`, `delivery`, `package_type`) VALUES ('" . $mysqli->real_escape_string($data["id"]) . "', 'N', '" . $mysqli->real_escape_string(date("Y-m-d", strtotime($data["approved_timestamp"]))) . "', '" . $mysqli->real_escape_string($group) . "', '" . $mysqli->real_escape_string($campaign_id) . "', '" . $mysqli->real_escape_string($data["type"]) . "', '" . $mysqli->real_escape_string($data["title"]) . "', '" . $mysqli->real_escape_string($data["firstname"]) . "', '" . $mysqli->real_escape_string($data["lastname"]) . "', '" . $mysqli->real_escape_string($bus_name) . "', '" . $mysqli->real_escape_string($email) . "', '" . $mysqli->real_escape_string($address_line1) . "', '" . $mysqli->real_escape_string($address_line2) . "', '" . $mysqli->real_escape_string($delivery) . "', '" . $mysqli->real_escape_string($package_type) . "')") or die($mysqli->error);
		
		for ($i = 1; $i < $p; $i++)
		{
			$mysqli->query("INSERT IGNORE INTO `letters`.`packages` (`id`, `cli`, `plan`) VALUES ('" . $mysqli->real_escape_string($data["id"]) . "', '" . $mysqli->real_escape_string($p_cli[$i]) . "', '" . $mysqli->real_escape_string($p_plan[$i]) . "')") or die($mysqli->error);
		}
	}
}
$q->free();

$q = $mysqli->query("SELECT * FROM `vericon`.`sales_customers` WHERE `status` = 'Approved' AND `industry` = 'TPV' AND DATE(`approved_timestamp`) = '$date' ORDER BY `approved_timestamp` ASC") or die($mysqli->error);
while ($data = $q->fetch_assoc())
{
	$q2 = $mysqli->query("SELECT `id`,`group` FROM `vericon`.`campaigns` WHERE `campaign` = '" . $mysqli->real_escape_string($data["campaign"]) . "'") or die($mysqli->error);
	$c = $q2->fetch_row();
	$campaign_id = $c[0];
	$group = $c[1];
	$q2->free();
	
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
			$bus_name = $xml['organisationName'];
		}
		elseif ($xml['tradingName'] != null)
		{
			$bus_name = $xml['tradingName'];
		}
		elseif ($xml['entityName'] != ", ")
		{
			$bus_name = $xml['entityName'];
		}
		else
		{
			$bus_name = strtoupper($data["lastname"] . ", " . $data["firstname"]);
		}
	}
	else
	{
		$bus_name = "";
	}
	
	if ($data["email"] == "N/A") {
		$email = "";
		$delivery = "P";
	} else {
		$email = $data["email"];
		$delivery = "E";
	}
	
	$p_i = 0;
	$a_i = 0;
	$b_i = 0;
	$p = 1;
	$p_packages = array();
	$a_packages = array();
	$b_packages = array();
	$p_cli = array();
	$p_plan = array();
	$a_cli = array();
	$a_plan = array();
	
	$q2 = $mysqli->query("SELECT * FROM `vericon`.`sales_packages` WHERE `sid` = '" . $data["id"] . "' ORDER BY `plan` DESC") or die($mysqli->error);
	while ($pack = $q2->fetch_assoc())
	{
		$q3 = $mysqli->query("SELECT * FROM `vericon`.`plan_matrix` WHERE `id` = '" . $pack["plan"] . "' AND `campaign` = '" . $mysqli->real_escape_string($campaign_id) . "'") or die($mysql->error);
		$da = $q3->fetch_assoc();
		$q3->free();
		if ($da["type"] == "PSTN")
		{
			$p_packages[$p_i] = $pack["cli"] . "," . $da["id"];
			$p_i++;
		}
		elseif ($da["type"] == "ADSL Metro" || $da["type"] == "ADSL Regional")
		{
			$a_packages[$a_i] = $pack["cli"] . "," . $da["id"];
			$a_i++;
		}
		elseif ($da["type"] == "Bundle")
		{
			$b_packages[$b_i] = $pack["cli"] . "," . $da["id"];
			$b_i++;
		}
	}
	$q2->free();
	
	if ($b_i >= 1)
	{
		foreach ($b_packages as $row)
		{
			$package = explode(",", $row);
			$p_cli[$p] = $package[0];
			$p_plan[$p] = $package[1];
			$p++;
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
			$p_cli[$p] = $package[0];
			$p_plan[$p] = $package[1];
			$p++;
		}
	}
	
	$package_type = "Landline";
	
	if ($a_i >= 1)
	{
		$package_type = "Broadband";
	}
	if ($b_i >= 1)
	{
		$package_type = "Bundle";
	}
	
	$mail_street_number = "";
	$mail_street = "";
	$mail_suburb = "";
	$mail_state = "";
	$mail_postcode = "";
	
	if (substr($data["postal"],0,2) == "GA")
	{
		$q5 = $mysqli->query("SELECT * FROM `gnaf`.`ADDRESS_DETAIL` WHERE `address_detail_pid` = '" . $data["postal"] . "'") or die($mysqli->error);
		$data2 = $q5->fetch_assoc();
		$q5->free();
		
		if ($data2["number_first"] == 0 && $data2["number_last"] == 0)
		{
			$mail_street_number = "Lot " . $data2["lot_number"] . $data2["lot_number_suffix"];
		}		
		elseif ($data2["flat_number"] != 0)
		{
			$q6 = $mysqli->query("SELECT `name` FROM `gnaf`.`FLAT_TYPE_AUT` WHERE `code` = '" . $mysqli->real_escape_string($data2["flat_type_code"]) . "'") or die($mysqli->error);
			$ft = $q6->fetch_row();
			$q6->free();
			
			$mail_street_number = $ft[0] . " " . $data2["flat_number"] . $data2["flat_number_suffix"];
		}
		elseif ($data2["level_number"] != 0)
		{
			$mail_street_number = "Level " . $data2["level_number"] . $data2["level_number_suffix"];
		}
		if ($data2["number_first"] != 0)
		{
			$mail_street_number .= " " . $data2["number_first"] . $data2["number_first_suffix"];
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
		
		$address_line1 = trim(preg_replace('!\s+!', ' ', ucwords(strtolower($mail_street_number . " " . $mail_street))));
		$address_line2 = trim(preg_replace('!\s+!', ' ', strtoupper($mail_suburb . " " . $mail_state . " " . $mail_postcode)));
	}
	elseif (substr($data["postal"],0,2) == "MA")
	{
		$q5 = $mysqli->query("SELECT * FROM vericon.address WHERE id = '" . $data["postal"] . "'") or die($mysqli->error);
		$data2 = $q5->fetch_assoc();
		$q5->free();
		
		if ($data2["building_type"] == "PO BOX")
		{
			$address_line1 = trim("PO Box " . $data2["building_number"]);
		}
		else
		{
			if ($data2["building_type"] == "LOT")
			{
				$mail_street_number = "Lot " . $data2["building_number"] . $data2["building_number_suffix"];
			}		
			elseif ($data2["building_type"] == "LEVEL")
			{
				$mail_street_number = "Level " . $data2["building_number"] . $data2["building_number_suffix"];
			}
			elseif ($data2["building_type"] != "" && $data2["number_first"] != "")
			{
				$mail_street_number = $data2["building_type"] . " " . $data2["building_number"] . $data2["building_number_suffix"] . " " . $data2["number_first"] . $data2["number_first_suffix"];
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
			
			$address_line1 = trim(preg_replace('!\s+!', ' ', ucwords(strtolower($mail_street_number . " " . $mail_street))));
		}
		
		$mail_suburb = $data2["suburb"];
		$mail_state = $data2["state"];
		$mail_postcode = $data2["postcode"];
		
		$address_line2 = trim(preg_replace('!\s+!', ' ', strtoupper($mail_suburb . " " . $mail_state . " " . $mail_postcode)));
	}
	
	$mysqli->query("INSERT IGNORE INTO `letters`.`customers` (`id`, `letter_type`, `sale_date`, `group`, `campaign`, `type`, `title`, `firstname`, `lastname`, `bus_name`, `email`, `address_line1`, `address_line2`, `delivery`, `package_type`) VALUES ('" . $mysqli->real_escape_string($data["id"]) . "', 'N', '" . $mysqli->real_escape_string(date("Y-m-d", strtotime($data["approved_timestamp"]))) . "', '" . $mysqli->real_escape_string($group) . "', '" . $mysqli->real_escape_string($campaign_id) . "', '" . $mysqli->real_escape_string($data["type"]) . "', '" . $mysqli->real_escape_string($data["title"]) . "', '" . $mysqli->real_escape_string($data["firstname"]) . "', '" . $mysqli->real_escape_string($data["lastname"]) . "', '" . $mysqli->real_escape_string($bus_name) . "', '" . $mysqli->real_escape_string($email) . "', '" . $mysqli->real_escape_string($address_line1) . "', '" . $mysqli->real_escape_string($address_line2) . "', '" . $mysqli->real_escape_string($delivery) . "', '" . $mysqli->real_escape_string($package_type) . "')") or die($mysqli->error);
	
	for ($i = 1; $i < $p; $i++)
	{
		$mysqli->query("INSERT IGNORE INTO `letters`.`packages` (`id`, `cli`, `plan`) VALUES ('" . $mysqli->real_escape_string($data["id"]) . "', '" . $mysqli->real_escape_string($p_cli[$i]) . "', '" . $mysqli->real_escape_string($p_plan[$i]) . "')") or die($mysqli->error);
	}
}
$q->free();
$mysqli->close();
?>