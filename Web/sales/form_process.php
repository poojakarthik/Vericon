<?php
include("../auth/restrict_inner.php");

$method = $_POST["m"];

if ($method == "load_form")
{
	$lead = $_POST["lead"];
	$campaign = $_POST["campaign"];
	$type = $_POST["type"];
	
	$q = $mysqli->query("SELECT * FROM `vericon`.`centres` WHERE `id` = '" . $mysqli->real_escape_string($ac["centre"]) . "'") or die($mysqli->error);
	$centre = $q->fetch_assoc();
	$q->free();
	
	if ($centre["leads"] == "Active")
	{
		$q = $mysqli->query("SELECT `country` FROM `vericon`.`campaigns` WHERE `id` = '" . $mysqli->real_escape_string($campaign) . "'") or die($mysqli->error);
		$country = $q->fetch_row();
		$q->free();
		
		$q = $mysqli->query("SELECT * FROM `leads`.`leads` WHERE `cli` = '" . $mysqli->real_escape_string($lead) . "'") or die($mysqli->error);
		$check = $q->fetch_assoc();
		
		$submitted = $mysqli->query("SELECT `lead_id` FROM `vericon`.`sales_customers` WHERE `lead_id` = '" . $mysqli->real_escape_string($lead) . "' AND DATE(`timestamp`) BETWEEN '" . $mysqli->real_escape_string($check["issue_date"]) . "' AND '" . $mysqli->real_escape_string($check["expiry_date"]) . "'") or die($mysqli->error);
		
		$sct = $mysqli->query("SELECT `cli` FROM `vericon`.`sct_dnc` WHERE `cli` = '" . $mysqli->real_escape_string($lead) . "'") or die($mysqli->error);
		
		if ($centre["type"] == "Captive") 
		{
			$group_check = $mysqli->query("SELECT `group` FROM `leads`.`groups` WHERE `centres` LIKE '%" . $mysqli->real_escape_string($ac["centre"]) . "%' AND `centres` LIKE '%" . $mysqli->real_escape_string($check["centre"]) . "%'") or die($mysqli->error);
			
			if ($group_check->num_rows == 0 && strtoupper($check["centre"]) != "ROHAN") {
				$valid_lead = false;
			} else {
				$valid_lead = true;
			}
			
			$group_check->free();
		}
		elseif ($centre["type"] == "Melbourne")
		{
			$group_check = $mysqli->query("SELECT `group` FROM `leads`.`groups` WHERE `centres` LIKE '%" . $mysqli->real_escape_string($ac["centre"]) . "%' AND `centres` LIKE '%" . $mysqli->real_escape_string($check["centre"]) . "%'") or die($mysqli->error);
			
			if ($group_check->num_rows == 0 && strtoupper($check["centre"]) != "KAMAL") {
				$valid_lead = false;
			} else {
				$valid_lead = true;
			}
			
			$group_check->free();
		}
		else
		{
			if ($check["centre"] != $ac["centre"]) {
				$valid_lead = false;
			} else {
				$valid_lead = true;
			}
		}
		
		if (!preg_match("/^0[2378][0-9]{8}$/",$lead) && !preg_match("/^0[34679][0-9]{7}$/",$lead))
		{
			echo "<b>Error: </b>Invalid lead ID.";
		}
		elseif ($country[0] == "AU" && !preg_match("/^0[2378][0-9]{8}$/",$lead))
		{
			echo "<b>Error: </b>Not an Australian lead.";
		}
		elseif ($country[0] == "NZ" && !preg_match("/^0[34679][0-9]{7}$/",$lead))
		{
			echo "<b>Error: </b>Not a New Zealand lead.";
		}
		elseif ($q->num_rows == 0)
		{
			echo "<b>Error: </b>Lead is not in the data packet.";
		}
		elseif (!$valid_lead)
		{
			echo "<b>Error: </b>Lead is not in the data packet.";
		}
		elseif ($sct->num_rows != 0)
		{
			echo "<b>Error: </b>Customer is on the SCT DNC list.";
		}
		elseif (strtotime(date("Y-m-d")) < strtotime($check["issue_date"]) || strtotime(date("Y-m-d")) > strtotime($check["expiry_date"]))
		{
			echo "<b>Error: </b>Lead has expired.";
		}
		elseif ($submitted->num_rows != 0)
		{
			echo "<b>Error: </b>Customer already submitted within this data period.";
		}
		elseif ($campaign == "")
		{
			echo "<b>Error: </b>Please select a campaign.";
		}
		elseif ($type == "")
		{
			echo "<b>Error: </b>Please select a sale type.";
		}
		else
		{
			$mysqli->query("INSERT INTO `vericon`.`sales_customers_temp` (`id`, `timestamp`, `user`, `centre`, `campaign`, `type`) VALUES ('" . $mysqli->real_escape_string($lead) . "', NOW(), '" . $mysqli->real_escape_string($ac["user"]) . "', '" . $mysqli->real_escape_string($ac["centre"]) . "', '" . $mysqli->real_escape_string($campaign) . "', '" . $mysqli->real_escape_string($type) . "') ON DUPLICATE KEY UPDATE `timestamp` = NOW(), `user` = '" . $mysqli->real_escape_string($ac["user"]) . "', `type` = '" . $mysqli->real_escape_string($type) . "', `centre` = '" . $mysqli->real_escape_string($ac["centre"]) . "', `campaign` = '" . $mysqli->real_escape_string($campaign) . "'") or die($mysqli->error);
			
			$mysqli->query("DELETE FROM `vericon`.`sales_packages_temp` WHERE `id` = '" . $mysqli->real_escape_string($lead) . "'") or die($mysqli->error);
			
			echo "valid_" . strtolower($country[0]);
		}
		
		$q->free();
		$submitted->free();
		$sct->free();
	}
	else
	{
		$q = $mysqli->query("SELECT `country` FROM `vericon`.`campaigns` WHERE `id` = '" . $mysqli->real_escape_string($campaign) . "'") or die($mysqli->error);
		$country = $q->fetch_row();
		$q->free();
		
		$submitted = $mysqli->query("SELECT `lead_id` FROM `vericon`.`sales_customers` WHERE `lead_id` = '" . $mysqli->real_escape_string($lead) . "' AND DATE(`timestamp`) BETWEEN DATE_SUB(CURDATE(),INTERVAL 1 WEEK) AND CURDATE()") or die($mysqli->error);
		
		$sct = $mysqli->query("SELECT `cli` FROM `vericon`.`sct_dnc` WHERE `cli` = '" . $mysqli->real_escape_string($lead) . "'") or die($mysqli->error);
		
		if (!preg_match("/^0[2378][0-9]{8}$/",$lead) && !preg_match("/^0[34679][0-9]{7}$/",$lead))
		{
			echo "<b>Error: </b>Invalid lead ID.";
		}
		elseif ($country[0] == "AU" && !preg_match("/^0[2378][0-9]{8}$/",$lead))
		{
			echo "<b>Error: </b>Not an Australian lead.";
		}
		elseif ($country[0] == "NZ" && !preg_match("/^0[34679][0-9]{7}$/",$lead))
		{
			echo "<b>Error: </b>Not a New Zealand lead.";
		}
		elseif ($sct->num_rows != 0)
		{
			echo "<b>Error: </b>Customer is on the SCT DNC list.";
		}
		elseif ($submitted->num_rows != 0)
		{
			echo "<b>Error: </b>Customer already submitted within this data period.";
		}
		elseif ($campaign == "")
		{
			echo "<b>Error: </b>Please select a campaign.";
		}
		elseif ($type == "")
		{
			echo "<b>Error: </b>Please select a sale type.";
		}
		else
		{
			$mysqli->query("INSERT INTO `vericon`.`sales_customers_temp` (`id`, `timestamp`, `user`, `centre`, `campaign`, `type`) VALUES ('" . $mysqli->real_escape_string($lead) . "', NOW(), '" . $mysqli->real_escape_string($ac["user"]) . "', '" . $mysqli->real_escape_string($ac["centre"]) . "', '" . $mysqli->real_escape_string($campaign) . "', '" . $mysqli->real_escape_string($type) . "') ON DUPLICATE KEY UPDATE `timestamp` = NOW(), `user` = '" . $mysqli->real_escape_string($ac["user"]) . "', `type` = '" . $mysqli->real_escape_string($type) . "', `centre` = '" . $mysqli->real_escape_string($ac["centre"]) . "', `campaign` = '" . $mysqli->real_escape_string($campaign) . "'") or die($mysqli->error);
			
			$mysqli->query("DELETE FROM `vericon`.`sales_packages_temp` WHERE `id` = '" . $mysqli->real_escape_string($lead) . "'") or die($mysqli->error);
			
			echo "valid_" . strtolower($country[0]);
		}
		
		$submitted->free();
		$sct->free();
	}
}
elseif ($method == "cancel")
{
	$lead = $_POST["lead"];
	
	$mysqli->query("DELETE FROM `vericon`.`sales_customers_temp` WHERE `id` = '" . $mysqli->real_escape_string($lead) . "'") or die($mysqli->error);
	
	$mysqli->query("DELETE FROM `vericon`.`sales_packages_temp` WHERE `id` = '" . $mysqli->real_escape_string($lead) . "'") or die($mysqli->error);
	
	echo "valid";
}

$mysqli->close();
?>