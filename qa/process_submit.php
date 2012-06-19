<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];

if ($method == "approve")
{
	$id = $_GET["id"];
	$verifier = $_GET["verifier"];
	$lead_id = $_GET["lead_id"];
	$lead = $_GET["lead"];
	$recording = $_GET["recording"];
	$details = $_GET["details"];
	$filename = "/var/vericon/upload/tmp/" . $lead_id . ".gsm";
	$timestamp = date("Y-m-d H:i:s");
	
	$qc = mysql_query("SELECT * FROM vericon.qa_customers WHERE id = '$id'") or die(mysql_error());
	
	if ($id == "" || $verifier == "" || $lead_id == "")
	{
		echo "Error! Please contact your administrator!";
	}
	elseif ($lead == 0)
	{
		echo "Please check if the lead is valid";
	}
	elseif ($recording == 0)
	{
		echo "Please check if the recording is complete and fine for processing";
	}
	elseif ($details == 0)
	{
		echo "Please check if the customer's details are correct";
	}
	elseif (!file_exists($filename) && mysql_num_rows($qc) == 0)
	{
		echo "Please upload the voice file";
	}
	else
	{
		$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
		$data = mysql_fetch_assoc($q);
		
		if (mysql_fetch_row($qc) == 0)
		{
			mysql_query("INSERT INTO vericon.qa_customers (id, status, lead_id, timestamp, verifier, sale_timestamp, agent, centre, campaign, type, lead_check, recording_check, details_check) VALUES ('$id', 'Approved', '$lead_id', '$timestamp', '$verifier', '$data[approved_timestamp]', '$data[agent]', '$data[centre]', '$data[campaign]', '$data[type]', '$lead', '$recording', '$details')") or die(mysql_error());
		}
		else
		{
			mysql_query("UPDATE vericon.qa_customers SET status = 'Approved', timestamp = '$timestamp', verifier = '$verifier', lead_check = '$lead', recording_check = '$recording', details_check = '$details' WHERE id = '$id' LIMIT 1") or die(mysql_error());
		}
		
		$q1 = mysql_query("SELECT * FROM vericon.customers WHERE sale_id = '$id'") or die(mysql_error());
		if (mysql_num_rows($q1) == 0)
		{
			$pre_id = date("y", strtotime($data["approved_timestamp"])) . str_pad(date("z", strtotime($data["approved_timestamp"])),3,"0",STR_PAD_LEFT);
			$q2 = mysql_query("SELECT COUNT(id) FROM vericon.customers WHERE id LIKE '$pre_id%'");
			$num = mysql_fetch_row($q2);
			
			$account_number = $pre_id . str_pad(($num[0]+1),3,"0",STR_PAD_LEFT);
			
			mysql_query("INSERT INTO vericon.customers (id, industry, lead_id, sale_id, timestamp, agent, centre, campaign, type, title, firstname, middlename, lastname, dob, email, mobile, billing, welcome, promotions, physical, postal, id_type, id_num, abn, position) VALUES ('$account_number', '$data[industry]', '$data[lead_id]', '$data[id]', '$timestamp', '$data[agent]', '$data[centre]', '$data[campaign]', '$data[type]', '" . mysql_escape_string($data["title"]) . "', '" . mysql_escape_string($data["firstname"]) . "', '" . mysql_escape_string($data["middlename"]) . "', '" . mysql_escape_string($data["lastname"]) . "', '" . mysql_escape_string($data["dob"]) . "', '" . mysql_escape_string($data["email"]) . "', '" . mysql_escape_string($data["mobile"]) . "', '$data[billing]', '$data[welcome]', '$data[promotions]', '$data[physical]', '$data[postal]', '" . mysql_escape_string($data["id_type"]) . "', '" . mysql_escape_string($data["id_num"]) . "', '" . mysql_escape_string($data["abn"]) . "', '" . mysql_escape_string($data["position"]) . "')") or die(mysql_error());
			
			$q3 = mysql_query("SELECT * FROM vericon.sales_packages WHERE sid = '$id'") or die(mysql_error());
			while ($packages = mysql_fetch_row($q3))
			{
				mysql_query("INSERT INTO vericon.packages (id, cli, plan) VALUES ('$account_number', '$packages[1]', '$packages[2]')") or die(mysql_error());
			}
			
			$command = "mv /var/vericon/upload/tmp/" . $data["lead_id"] . ".gsm /var/rec/" . md5($account_number) . sha1($account_number) . ".gsm";
			exec($command);
			
			mysql_query("INSERT INTO vericon.recordings (id, name) VALUES ('$account_number', '" . mysql_escape_string(md5($account_number) . sha1($account_number) . ".gsm") . "')") or die(mysql_error());
			
			echo 1;
		}
		else
		{
			echo 1;
		}
	}
}
elseif ($method == "reject")
{
	$id = $_GET["id"];
	$verifier = $_GET["verifier"];
	$status = $_GET["status"];
	$reason = $_GET["reason"];
	$timestamp = date("Y-m-d H:i:s");
	
	if ($id == "" || $verifier == "")
	{
		echo "Error! Please contact your administrator!";
	}
	elseif ($status == "In-House Rejection")
	{
		$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
		$data = mysql_fetch_assoc($q);
		
		$qc = mysql_query("SELECT * FROM vericon.qa_customers WHERE id = '$id'") or die(mysql_error());
		$check = mysql_fetch_assoc($qc);
		
		if (mysql_num_rows($qc) == 0)
		{
			mysql_query("INSERT INTO vericon.qa_customers (id, status, lead_id, timestamp, verifier, sale_timestamp, agent, centre, campaign, type, rejection_reason) VALUES ('$id', 'Rejected', '$data[lead_id]', '$timestamp', '$verifier', '$data[approved_timestamp]', '$data[agent]', '$data[centre]', '$data[campaign]', '$data[type]', '" . mysql_escape_string($reason) . "')") or die(mysql_error());
			echo "submitted";
		}
		else
		{
			if ($check["status"] == "Approved")
			{
				$q4 = mysql_query("SELECT id FROM vericon.customers WHERE sale_id = '$id'") or die(mysql_error());
				$aid = mysql_fetch_row($q4);
				
				mysql_query("UPDATE vericon.tmp_dsr SET Account_Status = 'Cancelled' WHERE Sale_ID = '$aid[0]'") or die(mysql_error());
				
				mysql_query("UPDATE vericon.qa_customers SET status = 'Rejected', timestamp = '$timestamp', verifier = '$verifier', rejection_reason = '" . mysql_escape_string($reason) . "' WHERE id = '$id' LIMIT 1") or die(mysql_error());
				echo "submitted";
			}
			else
			{
				mysql_query("UPDATE vericon.qa_customers SET status = 'Rejected', timestamp = '$timestamp', verifier = '$verifier', rejection_reason = '" . mysql_escape_string($reason) . "' WHERE id = '$id' LIMIT 1") or die(mysql_error());
				
				echo "submitted";
			}
		}
		
		$command = "rm /var/vericon/upload/tmp/" . $data["lead_id"] . ".gsm";
		exec($command);
	}
	elseif ($status == "Rework")
	{
		$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
		$data = mysql_fetch_assoc($q);
		
		mysql_query("INSERT INTO vericon.reworks (id,timestamp,centre,agent,reason) VALUES ('$id', '$timestamp', '$data[centre]',  '$data[agent]', '" . mysql_escape_string($reason) . "')") or die(mysql_error());
		mysql_query("UPDATE vericon.sales_customers SET status = 'Rework' WHERE id = '$id' LIMIT 1") or die(mysql_error());
		
		$path = "/var/vericon/upload/tmp/" . $data["lead_id"] . ".gsm";
		$command = "rm /var/vericon/upload/tmp/" . $data["lead_id"] . ".gsm";
		
		if (file_exists($path))
		{
			exec($command);
		}
		
		echo "submitted";
	}
}
elseif ($method == "plans")
{
	$id = $_GET['id'];
	$type = $_GET["type"];
?>
<table width="100%">
<?php
$p_i = 0;
$a_i = 0;
$b_i = 0;
$p = 1;
$a = 1;
$q = mysql_query("SELECT * FROM vericon.sales_packages WHERE sid = '$id' ORDER BY plan DESC") or die(mysql_error());
while ($pack = mysql_fetch_assoc($q))
{
	$q1 = mysql_query("SELECT * FROM vericon.plan_matrix WHERE id = '$pack[plan]'") or die(mysql_error());
	$da = mysql_fetch_assoc($q1);
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

$q2 = mysql_query("SELECT plan FROM vericon.sales_packages WHERE sid = '$id'") or die(mysql_error());
$contract_months = 0;
while ($planc = mysql_fetch_row($q2))
{
	$q1 = mysql_query("SELECT name FROM vericon.plan_matrix WHERE id = '$planc[0]'") or die(mysql_error());
	$plan_name = mysql_fetch_row($q1);
	$planc[0] = $plan_name[0];
	
	if (preg_match("/24 Month Contract/", $planc[0]))
	{
		$contract = 24;
	}
	elseif (preg_match("/12 Month Contract/", $planc[0]))
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

for ($i = 1; $i <= 10; $i++)
{
	echo "<tr>";
	echo "<td>CLI " . $i . "</td>";
	echo "<td><input type='text' id='cli_" . $i . "' value='$p_cli[$i]' /></td>";
	echo "<td>Plan " . $i . "</td>";
	echo "<td><input type='text' id='plan_" . $i ."' value='$p_plan[$i]'></td>";
	$i++;
	echo "<td>CLI " . $i . "</td>";
	echo "<td><input type='text' id='cli_" . $i . "' value='$p_cli[$i]' /></td>";
	echo "<td>Plan " . $i . "</td>";
	echo "<td><input type='text' id='plan_" . $i ."' value='$p_plan[$i]'></td>";
	echo "</tr>";
}

for ($i = 1; $i <= 3; $i++)
{
	echo "<tr>";
	echo "<td>MSN " . $i . "</td>";
	echo "<td><input type='text' id='msn_" . $i . "' /></td>";
	echo "<td>Mplan " . $i . "</td>";
	echo "<td><input type='text' id='mplan_" . $i ."' value=''></td>";
	$i++;
	if ($i <= 3)
	{
		echo "<td>MSN " . $i . "</td>";
		echo "<td><input type='text' id='msn_" . $i . "' /></td>";
		echo "<td>Mplan " . $i . "</td>";
		echo "<td><input type='text' id='mplan_" . $i ."' value=''></td>";
		echo "</tr>";
	}
}

for ($i = 1; $i <= 2; $i++)
{
	echo "<tr>";
	echo "<td>WMSN " . $i . "</td>";
	echo "<td><input type='text' id='wmsn_" . $i . "' /></td>";
	echo "<td>Wplan " . $i . "</td>";
	echo "<td><input type='text' id='wplan_" . $i ."' value=''></td>";
	$i++;
	echo "<td>WMSN " . $i . "</td>";
	echo "<td><input type='text' id='wmsn_" . $i . "' /></td>";
	echo "<td>Wplan " . $i . "</td>";
	echo "<td><input type='text' id='wplan_" . $i ."' value=''></td>";
	echo "</tr>";
}
?>
<tr>
<td>ACLI</td>
<td><input type='text' id='acli' value="<?php echo $a_cli[1]; ?>" /></td>
<td>Aplan</td>
<td><input type="text" id='aplan' value="<?php echo $a_plan[1]; ?>" /></td>
<td></td>
<td></td>
<td>Bundle</td>
<td><input type="text" id='bundle' value="<?php echo $b_type; ?>" /></td>
</tr>
</table>
<?php
$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
$data = mysql_fetch_assoc($q);

$physical = $data["physical"];
$postal = $data["postal"];
$resi_id_info = "";
if ($data["type"] == "Residential")
{
	$resi_id_info = $data["id_type"] . " - " . $data["id_num"];
}

if (substr($physical,0,2) == "GA")
{
	$q1 = mysql_query("SELECT * FROM gnaf.ADDRESS_DETAIL WHERE address_detail_pid = '$physical'") or die(mysql_error());
	$data2 = mysql_fetch_assoc($q1);
	
	if ($data2["number_first"] == 0 && $data2["number_last"] == 0)
	{
		$building_type = "LOT";
		$building_number = $data2["lot_number"];
		$building_number_suffix = $data2["lot_number_suffix"];
	}
	
	if ($data2["flat_number"] != 0)
	{
		$building_type = $data2["flat_type_code"];
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
	$q1 = mysql_query("SELECT * FROM vericon.address WHERE id = '$physical'") or die(mysql_error());
	$data2 = mysql_fetch_assoc($q1);
	
	$building_type = $data2["building_type"];
	$building_number = $data2["building_number"];
	$building_number_suffix = $data2["building_number_suffix"];
	$building_name = $data2["building_name"];
	$number_first = $data2["number_first"];
	$number_last = $data2["number_last"];
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
		$q1 = mysql_query("SELECT * FROM gnaf.ADDRESS_DETAIL WHERE address_detail_pid = '$physical'") or die(mysql_error());
		$data2 = mysql_fetch_assoc($q1);
		
		if ($data2["number_first"] == 0 && $data2["number_last"] == 0)
		{
			$mail_street_number = "LOT " . $data2["lot_number"] . $data2["lot_number_suffix"];
		}		
		elseif ($data2["flat_number"] != 0)
		{
			$mail_street_number = $data2["flat_type_code"] . " " . $data2["flat_number"] . $data2["flat_number_suffix"] . "/";
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
		$q1 = mysql_query("SELECT * FROM vericon.address WHERE id = '$postal'") or die(mysql_error());
		$data2 = mysql_fetch_assoc($q1);
		
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
?>
<table width="100%">
<tr>
<td colspan="2">Additional Information</td>
<td colspan="2"><textarea id="additional_information" style="resize:none;">
<?php echo $resi_id_info; ?>
</textarea></td>
<td colspan="2">Billing Comment</td>
<td colspan="2"><textarea id="billing_comment" style="resize:none;"></textarea></td>
</tr>
<tr>
<td colspan="2">Provisioning Comment</td>
<td colspan="2"><textarea id="provisioning_comment" style="resize:none;">
<?php
if ($a > 1)
{
	for ($i = 2; $i < $a; $i++)
	{
		echo "ACLI $i. $a_cli[$i]  --  $a_plan[$i]\n";
	}
}

if ($p > 10)
{
	for ($i = 11; $i < $p; $i++)
	{
		echo "CLI $i. $p_cli[$i]  --  $p_plan[$i]\n";
	}
}
?>
</textarea></td>
<td colspan="2">Mobile Comment</td>
<td colspan="2"><textarea id="mobile_comment" style="resize:none;"></textarea></td>
</tr>
</table>
<table width="100%">
<tr>
<td>Account Status</td>
<td><input type="text" id="account_status" value="Waiting Welcome Call" /></td>
<td>ADSL Status</td>
<td><input type="text" id="adsl_status" value="<?php echo $a_status; ?>" /></td>
<td>Wireless Status</td>
<td><input type="text" id="wireless_status" value="" /></td>
</tr>
<tr>
<td>Contract Months</td>
<td><input type="text" id="contract_months" value="<?php echo $contract_months; ?>" /></td>
</tr>
</table>
<table width="100%">
<tr>
<td colspan="6"><img src="../images/physical_address_header.png" width="125" height="15" style="margin-left:3px;" /></td>
</tr>
<tr>
<td colspan="6"><img src="../images/line.png" width="100%" height="9" alt="line" /></td>
</tr>
<tr>
<td>Building Type</td>
<td><input type="text" id="building_type" value="<?php echo $building_type; ?>" /></td>
<td>Building #</td>
<td><input type="text" id="building_number" value="<?php echo $building_number; ?>" /></td>
<td>Building # Suffix</td>
<td><input type="text" id="building_number_suffix" value="<?php echo $building_number_suffix; ?>" /></td>
</tr>
<tr>
<td>Building Name</td>
<td><input type="text" id="building_name" value="<?php echo $building_name; ?>" /></td>
<td>Street # Start</td>
<td><input type="text" id="street_number_start" value="<?php echo $street_number_start; ?>" /></td>
<td>Street # End</td>
<td><input type="text" id="street_number_end" value="<?php echo $street_number_end; ?>" /></td>
</tr>
<tr>
<td>Street Name</td>
<td><input type="text" id="street_name" value="<?php echo $street_name; ?>" /></td>
<td>Street Type</td>
<td><input type="text" id="street_type" value="<?php echo $street_type; ?>" /></td>
</tr>
<tr>
<td>Suburb</td>
<td><input type="text" id="suburb" value="<?php echo $suburb; ?>" /></td>
<td>State</td>
<td><input type="text" id="state" value="<?php echo $state; ?>" /></td>
<td>Postcode</td>
<td><input type="text" id="postcode" value="<?php echo $postcode; ?>" /></td>
</tr>
<tr>
<td colspan="6"><img src="../images/postal_address_header.png" width="115" height="15" style="margin-left:3px;" /></td>
</tr>
<tr>
<td colspan="6"><img src="../images/line.png" width="100%" height="9" alt="line" /></td>
</tr>
<tr>
<td>PO Box Number</td>
<td><input type="text" id="po_box_number" value="<?php echo $po_box_number; ?>" /></td>
<td>Mail Street Number</td>
<td><input type="text" id="mail_street_number" value="<?php echo $mail_street_number; ?>" /></td>
<td>Mail Street</td>
<td><input type="text" id="mail_street" value="<?php echo $mail_street; ?>" /></td>
</tr>
<tr>
<td>Mail Suburb</td>
<td><input type="text" id="mail_suburb" value="<?php echo $mail_suburb; ?>" /></td>
<td>Mail State</td>
<td><input type="text" id="mail_state" value="<?php echo $mail_state; ?>" /></td>
<td>Mail Postcode</td>
<td><input type="text" id="mail_postcode" value="<?php echo $mail_postcode; ?>" /></td>
</tr>
</table>
<?php
}
?>

