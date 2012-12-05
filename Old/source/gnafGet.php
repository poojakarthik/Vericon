<?php
mysql_connect('localhost','vericon','18450be');

$query = trim($_GET["term"]);
$type = $_GET["type"];

if ($type == "input")
{	
	$q = mysql_query("SELECT locality_pid, locality_name, state_pid, primary_postcode FROM gnaf.LOCALITY WHERE locality_name LIKE '%" . mysql_real_escape_string($query) . "%' AND primary_postcode != '' GROUP BY LOCALITY.locality_name, LOCALITY.primary_postcode") or die(mysql_error());
	echo '[';
	while ($data = mysql_fetch_row($q))
	{
		switch ($data[2])
		{
			case "1": $state = "NSW"; break;
			case "2": $state = "VIC"; break;
			case "3": $state = "QLD"; break;
			case "4": $state = "SA"; break;
			case "5": $state = "WA"; break;
			case "6": $state = "TAS"; break;
			case "7": $state = "NT"; break;
			case "8": $state = "ACT"; break;
		}
		$d[] = " { \"id\": \"$data[0]\", \"label\": \"$data[1] $state $data[3]\" }";
	}
	echo implode(",",$d);
	echo ' ]';
}
elseif ($type == "input2")
{	
	$q = mysql_query("SELECT locality_pid, locality_name, state_pid, primary_postcode FROM gnaf.LOCALITY WHERE primary_postcode LIKE '" . mysql_real_escape_string($query) . "%' GROUP BY LOCALITY.locality_name, LOCALITY.primary_postcode") or die(mysql_error());
	echo '[';
	while ($data = mysql_fetch_row($q))
	{
		switch ($data[2])
		{
			case "1": $state = "NSW"; break;
			case "2": $state = "VIC"; break;
			case "3": $state = "QLD"; break;
			case "4": $state = "SA"; break;
			case "5": $state = "WA"; break;
			case "6": $state = "TAS"; break;
			case "7": $state = "NT"; break;
			case "8": $state = "ACT"; break;
		}
		$d[] = " { \"id\": \"$data[0]\", \"label\": \"$data[1] $state $data[3]\" }";
	}
	echo implode(",",$d);
	echo ' ]';
}
elseif ($type == "input3")
{	
	$q = mysql_query("SELECT locality, state, postcode FROM paf.pcode WHERE locality LIKE '%" . mysql_real_escape_string($query) . "%' GROUP BY locality, postcode") or die(mysql_error());
	echo '[';
	while ($data = mysql_fetch_row($q))
	{
		$d[] = " { \"id\": \"$data[0],$data[1],$data[2]\", \"label\": \"$data[0] $data[1] $data[2]\" }";
	}
	echo implode(",",$d);
	echo ' ]';
}
elseif ($type == "input4")
{	
	$q = mysql_query("SELECT locality, state, postcode FROM paf.pcode WHERE postcode LIKE '%" . mysql_real_escape_string($query) . "%' GROUP BY locality, postcode") or die(mysql_error());
	echo '[';
	while ($data = mysql_fetch_row($q))
	{
		$d[] = " { \"id\": \"$data[0],$data[1],$data[2]\", \"label\": \"$data[0] $data[1] $data[2]\" }";
	}
	echo implode(",",$d);
	echo ' ]';
}
elseif ($type == "input_check")
{
	$l_pid = $_GET["l_pid"];
	
	$q = mysql_query("SELECT LOCALITY.locality_name, STATE.state_abbreviation, LOCALITY.primary_postcode FROM gnaf.LOCALITY, gnaf.STATE WHERE locality_pid LIKE '$l_pid' AND STATE.state_pid = LOCALITY.state_pid") or die(mysql_error());
	$data = mysql_fetch_row($q);
	echo $data[0] . "," . $data[1] . "," . $data[2];
}
elseif ($type == "street_name")
{
	$l_pid = $_GET["l_pid"];
	
	$q = mysql_query("SELECT `street_name` FROM gnaf.STREET_LOCALITY WHERE `locality_pid` = '$l_pid' AND street_name LIKE '%" . mysql_real_escape_string($query) . "%' GROUP BY street_name") or die(mysql_error());
	echo '[';
	while ($data = mysql_fetch_row($q))
	{
		$d[] = "\"" . $data[0] . "\"";
	}
	echo implode(",",$d);
	echo ']';
}
elseif ($type == "street_type")
{
	$q = mysql_query("SELECT `code` FROM gnaf.STREET_TYPE_AUT WHERE code LIKE '" . mysql_real_escape_string($query) . "%'") or die(mysql_error());
	echo '[';
	while ($data = mysql_fetch_row($q))
	{
		$d[] = "\"" . $data[0] . "\"";
	}
	echo implode(",",$d);
	echo ']';
}
elseif ($type == "check")
{
	$address_type = $_GET["address_type"];
	$l_pid = $_GET["l_pid"];
	$building_type = $_GET["building_type"];
	$building_number = trim($_GET["building_number"]);
	$building_name = trim($_GET["building_name"]);
	$street_number = explode("-",trim($_GET["street_number"]));
	$street_name = trim($_GET["street_name"]);
	$st = explode(" ",trim($_GET["street_type"]));
	$street_type = $st[0];
	
	$q = mysql_query("SELECT `code` FROM gnaf.STREET_TYPE_AUT WHERE code = '" . mysql_real_escape_string($street_type) . "'") or die(mysql_error());
	
	if ($l_pid == "")
	{
		echo "Enter the customer's suburb or postcode below to begin searching the GNAF dataset";
	}
	elseif ($address_type == "")
	{
		echo "Select the address type";
	}
	elseif ($address_type == "FS" && ($street_number == "" || $street_name == "" || $street_type == ""))
	{
		echo "Enter a valid address";
	}
	elseif ($address_type == "OB" && ($building_number == "" || $street_number == "" || $street_name == "" || $street_type == ""))
	{
		echo "Enter a valid address";
	}
	elseif ($address_type == "BU" && ($building_type == "" || $building_number == "" || $street_number == "" || $street_name == "" || $street_type == ""))
	{
		echo "Enter a valid address";
	}
	elseif ($address_type == "LOT" && ($building_number == "" || $street_name == "" || $street_type == ""))
	{
		echo "Enter a valid address";
	}
	elseif ($street_type != "" && mysql_num_rows($q) == 0)
	{
		echo "Enter a valid street type";
	}
	else
	{
		echo "valid";
	}
}
elseif ($type == "check2")
{
	$address_type = $_GET["address_type"];
	$building_type = $_GET["building_type"];
	$building_number = trim($_GET["building_number"]);
	$suburb = $_GET["suburb"];
	$state = $_GET["state"];
	$postcode = $_GET["postcode"];
		
	if ($suburb == "" || $state == "" || $postcode == "")
	{
		echo "Enter the customer's suburb or postcode below to begin searching the PAF dataset";
	}
	elseif ($building_type == "")
	{
		echo "Select a Mail Box Type";
	}
	elseif ($building_number == "" && $building_type != "Care of Post Office")
	{
		echo "Enter a valid Mail Box Number";
	}
	else
	{
		echo "valid";
	}
}
elseif ($type == "search")
{
	$address_type = $_GET["address_type"];
	$l_pid = $_GET["l_pid"];
	$building_type = $_GET["building_type"];
	$building_number = preg_replace("/[^0-9]/", "", trim($_GET["building_number"]));
	$building_name = trim($_GET["building_name"]);
	$street_number = explode("-",preg_replace("/[^0-9-]/", "", trim($_GET["street_number"])));
	$street_name = trim($_GET["street_name"]);
	$st = explode(" ",trim($_GET["street_type"]));
	$street_type = $st[0];
	$results_count = 0;
	
	if ($building_type == "null") { $building_type = ""; }
	
	$q = mysql_query("SELECT street_locality_pid FROM gnaf.STREET_LOCALITY WHERE `locality_pid` = '$l_pid' AND street_name = '" . mysql_real_escape_string($street_name) . "'") or die(mysql_error());
	while ($s_pid = mysql_fetch_row($q))
	{
		if ($address_type == "FS")
		{
			$q1 = mysql_query("SELECT * FROM gnaf.ADDRESS_DETAIL WHERE `locality_pid` = '$l_pid' AND street_locality_pid = '$s_pid[0]' AND flat_number = '0' AND level_number = '0'") or die(mysql_error());
		}
		elseif ($address_type == "OB")
		{
			$q1 = mysql_query("SELECT * FROM gnaf.ADDRESS_DETAIL WHERE `locality_pid` = '$l_pid' AND street_locality_pid = '$s_pid[0]' AND flat_number = '0' AND level_number = '" . mysql_real_escape_string($building_number) . "'") or die(mysql_error());
		}
		elseif ($address_type == "BU")
		{
			$q1 = mysql_query("SELECT * FROM gnaf.ADDRESS_DETAIL WHERE `locality_pid` = '$l_pid' AND street_locality_pid = '$s_pid[0]' AND flat_number = '" . mysql_real_escape_string($building_number) . "'") or die(mysql_error());
		}
		elseif ($address_type == "LOT")
		{
			$q1 = mysql_query("SELECT * FROM gnaf.ADDRESS_DETAIL WHERE `locality_pid` = '$l_pid' AND street_locality_pid = '$s_pid[0]' AND lot_number = '" . mysql_real_escape_string($building_number) . "'") or die(mysql_error());
		}
		
		if (mysql_num_rows($q1) != 0)
		{
			while ($data = mysql_fetch_assoc($q1))
			{
				if ($address_type == "LOT" || $building_type == "LOT")
				{
					$results_count = 1;
					$d_number = "LOT " . $data["lot_number"] . $data["lot_number_suffix"];
					
					$d_street = $data["street_name"] . " ";
					
					if ($data["street_suffix_code"] != "")
					{
						$d_street .= $data["street_type_code"] . " " . $data["street_suffix_code"];
					}
					else
					{
						$d_street .= $data["street_type_code"];
					}
					
					echo '<input type="radio" name="address_code" value="' . $data["address_detail_pid"] . '" style="height:auto; margin:0 3px;">' . $d_number . " " . $d_street . ", " . $data["locality_name"] . ", " . $data["state"] . " " . $data["postcode"] . '<br>';
				}
				elseif (($street_number[0] >= $data["number_first"] && $street_number[0] <= $data["number_last"]) || $street_number[0] == $data["number_first"])
				{
					$results_count = 1;
					$d_street_number = "";
					if ($data["number_first"] == 0 && $data["number_last"] == 0)
					{
						$d_street_number = "LOT " . $data["lot_number"] . $data["lot_number_suffix"];
					}		
					elseif ($data["flat_number"] != 0)
					{
						$d_street_number = $data["flat_type_code"] . " " . $data["flat_number"] . $data["flat_number_suffix"] . "/";
					}
					elseif ($data["level_number"] != 0)
					{
						$d_street_number = "LEVEL " . $data["level_number"] . $data["level_number_suffix"] . "/";
					}
					
					if ($data["number_first"] != 0)
					{
						$d_street_number .= $data["number_first"] . $data["number_first_suffix"];
					}
					
					if ($data["number_last"] != 0)
					{
						$d_street_number .= "-" . $data["number_last"];
					}
					
					$d_street_name = $data["street_name"];
				
					if ($data["street_suffix_code"] != "")
					{
						$d_street_type = $data["street_type_code"] . " " . $data["street_suffix_code"];
					}
					else
					{
						$d_street_type = $data["street_type_code"];
					}
					
					$d_suburb = $data["locality_name"];
					$d_state = $data["state"];
					$d_postcode = $data["postcode"];
					
					echo '<input type="radio" name="address_code" value="' . $data["address_detail_pid"] . '" style="height:auto; margin:0 3px;">' . $d_street_number . " " . $d_street_name . " " . $d_street_type . ", " . $d_suburb . ", " . $d_state . " " . $d_postcode . '<br>';
				}
			}
		}
	}
	
	$q3 = mysql_query("SELECT LOCALITY.locality_name, STATE.state_abbreviation, LOCALITY.primary_postcode FROM gnaf.LOCALITY, gnaf.STATE WHERE locality_pid = '$l_pid' AND STATE.state_pid = LOCALITY.state_pid") or die(mysql_error());
	$data2 = mysql_fetch_row($q3);
	$suburb = $data2[0];
	$state = $data2[1];
	$postcode = $data2[2];
	
	if ($building_type == "null") { $building_type = ""; }
	
	$street_number = trim($_GET["street_number"]);
	
	$free_format =  preg_replace('!\s+!', ' ', trim($building_type . " " . $building_number . " " . $street_number . " " . $street_name . " " . $street_type . ", " . $suburb . " " . $state . " " . $postcode));
	
	$q4 = mysql_query("SELECT auth.user FROM vericon.auth, vericon.currentuser WHERE currentuser.hash = '" . mysql_real_escape_string($_COOKIE["hash"]) . "' AND auth.user = currentuser.user");
	$user = mysql_fetch_row($q4);

	if ($results_count == 0)
	{
		mysql_query("INSERT INTO vericon.log_gnaf (timestamp, user, input, result) VALUES (NOW(), '$user[0]', '" . mysql_real_escape_string($free_format) . "', '0')") or die(mysql_error());
		echo 'No Results Found';
	}
	else
	{
		mysql_query("INSERT INTO vericon.log_gnaf (timestamp, user, input, result) VALUES (NOW(), '$user[0]', '" . mysql_real_escape_string($free_format) . "', '1')") or die(mysql_error());
	}
}
elseif ($type == "format")
{
	$l_pid = $_GET["l_pid"];
	$building_type = $_GET["building_type"];
	$building_number = preg_replace("/[^0-9]/", "", trim($_GET["building_number"]));
	$street_number = trim($_GET["street_number"]);
	$street_name = trim($_GET["street_name"]);
	$st = explode(" ",trim($_GET["street_type"]));
	$street_type = $st[0];
	
	$q = mysql_query("SELECT LOCALITY.locality_name, STATE.state_abbreviation, LOCALITY.primary_postcode FROM gnaf.LOCALITY, gnaf.STATE WHERE locality_pid = '$l_pid' AND STATE.state_pid = LOCALITY.state_pid") or die(mysql_error());
	$data = mysql_fetch_row($q);
	$suburb = $data[0];
	$state = $data[1];
	$postcode = $data[2];
	
	if ($building_type == "null") { $building_type = ""; }
	
	echo trim($building_type . " " . $building_number . " " . $street_number . " " . $street_name . " " . $street_type . ", " . $suburb . " " . $state . " " . $postcode);
}
elseif ($type == "search2")
{
	$address_type = $_GET["address_type"];
	$a_pid = $_GET["a_pid"];
	$building_type = $_GET["building_type"];
	$building_number = preg_replace("/[^0-9]/", "", trim($_GET["building_number"]));
	$building_name = trim($_GET["building_name"]);
	$street_number = explode("-",preg_replace("/[^0-9-]/", "", trim($_GET["street_number"])));
	$street_name = trim($_GET["street_name"]);
	$st = explode(" ",trim($_GET["street_type"]));
	$street_type = $st[0];
	$results_count = 0;
	
	if ($a_pid == "null") { echo 'No Results Found'; exit; }
	
	if ($building_type == "null") { $building_type = ""; }
	
	$q = mysql_query("SELECT locality_pid, street_locality_pid FROM gnaf.ADDRESS_DETAIL WHERE address_detail_pid = '$a_pid'") or die(mysql_error());
	$da = mysql_fetch_row($q);
	$l_pid = $da[0];
	$s_pid = $da[1];
	
	if ($address_type == "FS")
	{
		$q1 = mysql_query("SELECT * FROM gnaf.ADDRESS_DETAIL WHERE `locality_pid` = '$l_pid' AND street_locality_pid = '$s_pid' AND flat_number = '0' AND level_number = '0'") or die(mysql_error());
	}
	elseif ($address_type == "OB")
	{
		$q1 = mysql_query("SELECT * FROM gnaf.ADDRESS_DETAIL WHERE `locality_pid` = '$l_pid' AND street_locality_pid = '$s_pid' AND flat_number = '0' AND level_number = '" . mysql_real_escape_string($building_number) . "'") or die(mysql_error());
	}
	elseif ($address_type == "BU")
	{
		$q1 = mysql_query("SELECT * FROM gnaf.ADDRESS_DETAIL WHERE `locality_pid` = '$l_pid' AND street_locality_pid = '$s_pid' AND flat_number = '" . mysql_real_escape_string($building_number) . "'") or die(mysql_error());
	}
	elseif ($address_type == "LOT")
	{
		$q1 = mysql_query("SELECT * FROM gnaf.ADDRESS_DETAIL WHERE `locality_pid` = '$l_pid' AND street_locality_pid = '$s_pid' AND lot_number = '" . mysql_real_escape_string($building_number) . "'") or die(mysql_error());
	}
	
	if (mysql_num_rows($q1) != 0)
	{
		while ($data = mysql_fetch_assoc($q1))
		{
			if ($address_type == "LOT" || $building_type == "LOT")
			{
				$results_count = 1;
				$d_number = "LOT " . $data["lot_number"] . $data["lot_number_suffix"];
				
				$d_street = $data["street_name"] . " ";
				
				if ($data["street_suffix_code"] != "")
				{
					$d_street .= $data["street_type_code"] . " " . $data["street_suffix_code"];
				}
				else
				{
					$d_street .= $data["street_type_code"];
				}
				
				echo '<input type="radio" name="address_code" value="' . $data["address_detail_pid"] . '" style="height:auto; margin:0 3px;">' . $d_number . " " . $d_street . ", " . $data["locality_name"] . ", " . $data["state"] . " " . $data["postcode"] . '<br>';
			}
			elseif (($street_number[0] >= $data["number_first"] && $street_number[0] <= $data["number_last"]) || $street_number[0] == $data["number_first"])
			{
				$results_count = 1;
				$d_street_number = "";
				if ($data["number_first"] == 0 && $data["number_last"] == 0)
				{
					$d_street_number = "LOT " . $data["lot_number"] . $data["lot_number_suffix"];
				}		
				elseif ($data["flat_number"] != 0)
				{
					$d_street_number = $data["flat_type_code"] . " " . $data["flat_number"] . $data["flat_number_suffix"] . "/";
				}
				elseif ($data["level_number"] != 0)
				{
					$d_street_number = "LEVEL " . $data["level_number"] . $data["level_number_suffix"] . "/";
				}
				
				if ($data["number_first"] != 0)
				{
					$d_street_number .= $data["number_first"] . $data["number_first_suffix"];
				}
				
				if ($data["number_last"] != 0)
				{
					$d_street_number .= "-" . $data["number_last"];
				}
				
				$d_street_name = $data["street_name"];
			
				if ($data["street_suffix_code"] != "")
				{
					$d_street_type = $data["street_type_code"] . " " . $data["street_suffix_code"];
				}
				else
				{
					$d_street_type = $data["street_type_code"];
				}
				
				$d_suburb = $data["locality_name"];
				$d_state = $data["state"];
				$d_postcode = $data["postcode"];
				
				echo '<input type="radio" name="address_code" value="' . $data["address_detail_pid"] . '" style="height:auto; margin:0 3px;">' . $d_street_number . " " . $d_street_name . " " . $d_street_type . ", " . $d_suburb . ", " . $d_state . " " . $d_postcode . '<br>';
			}
		}
	}
	if ($results_count == 0) { echo 'No Results Found'; }
}
elseif ($type == "manual")
{
	$l_pid = $_GET["l_pid"];
	$building_type = $_GET["building_type"];
	$building_number = preg_replace("/[^0-9]/", "", trim($_GET["building_number"]));
	$building_number_suffix = strtoupper(preg_replace("/[^a-zA-Z]/", "", trim($_GET["building_number"])));
	$building_name = strtoupper(trim($_GET["building_name"]));
	$street_number = explode("-",trim($_GET["street_number"]));
	$number_first = $street_number[0];
	$number_last = $street_number[1];
	$street_name = strtoupper(trim($_GET["street_name"]));
	$street_type = strtoupper(trim($_GET["street_type"]));
	
	if ($building_type == "null") { $building_type = ""; }
	
	$q = mysql_query("SELECT locality_name, state_pid, primary_postcode FROM gnaf.LOCALITY WHERE locality_pid = '$l_pid'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	switch ($data[1])
	{
		case "1": $state = "NSW"; break;
		case "2": $state = "VIC"; break;
		case "3": $state = "QLD"; break;
		case "4": $state = "SA"; break;
		case "5": $state = "WA"; break;
		case "6": $state = "TAS"; break;
		case "7": $state = "NT"; break;
		case "8": $state = "ACT"; break;
	}
	$suburb = $data[0];
	$postcode = $data[2];
	
	$q1 = mysql_query("SELECT id FROM vericon.address WHERE building_type = '" . mysql_real_escape_string($building_type) . "' AND building_number = '" . mysql_real_escape_string($building_number) . "' AND building_number_suffix = '" . mysql_real_escape_string($building_number_suffix) . "' AND building_name = '" . mysql_real_escape_string($building_name) . "' AND number_first = '" . mysql_real_escape_string($number_first) . "' AND number_last = '" . mysql_real_escape_string($number_last) . "' AND street_name = '" . mysql_real_escape_string($street_name) . "' AND street_type = '" . mysql_real_escape_string($street_type) . "' AND suburb = '" . mysql_real_escape_string($suburb) . "' AND state = '" . mysql_real_escape_string($state) . "' AND postcode = '" . mysql_real_escape_string($postcode) . "'") or die(mysql_error());
	if (mysql_num_rows($q1) != 0)
	{
		$id = mysql_fetch_row($q1);
		echo $id[0];
	}
	else
	{
		$pre_id = "MA" . str_pad($state,3,"_",STR_PAD_RIGHT);
		$q2 = mysql_query("SELECT * FROM vericon.address WHERE id LIKE '" . mysql_real_escape_string($pre_id) . "%'") or die(mysql_error());
		$num = mysql_num_rows($q2);
		$id = $pre_id . str_pad(($num+1),9,"0",STR_PAD_LEFT);
		
		mysql_query("INSERT INTO vericon.address (id, building_type, building_number, building_number_suffix, building_name, number_first, number_last, street_name, street_type, suburb, state, postcode) VALUES ('$id', '" . mysql_real_escape_string($building_type) . "', '" . mysql_real_escape_string($building_number) . "', '" . mysql_real_escape_string($building_number_suffix) . "', '" . mysql_real_escape_string($building_name) . "', '" . mysql_real_escape_string($number_first) . "', '" . mysql_real_escape_string($number_last) . "', '" . mysql_real_escape_string($street_name) . "', '" . mysql_real_escape_string($street_type) . "', '" . mysql_real_escape_string($suburb) . "', '" . mysql_real_escape_string($state) . "', '" . mysql_real_escape_string($postcode) . "')") or die(mysql_error());
		
		echo $id;
	}
}
elseif ($type == "mailbox")
{
	$building_type = $_GET["building_type"];
	$building_number = trim($_GET["building_number"]);
	$suburb = $_GET["suburb"];
	$state = $_GET["state"];
	$postcode = $_GET["postcode"];
	
	$q1 = mysql_query("SELECT id FROM vericon.address WHERE building_type = '" . mysql_real_escape_string($building_type) . "' AND building_number = '" . mysql_real_escape_string($building_number) . "' AND suburb = '" . mysql_real_escape_string($suburb) . "' AND state = '" . mysql_real_escape_string($state) . "' AND postcode = '" . mysql_real_escape_string($postcode) . "'") or die(mysql_error());
	if (mysql_num_rows($q1) != 0)
	{
		$id = mysql_fetch_row($q1);
		echo $id[0];
	}
	else
	{
		$pre_id = "MA" . str_pad($state,3,"_",STR_PAD_RIGHT);
		$q2 = mysql_query("SELECT * FROM vericon.address WHERE id LIKE '" . mysql_real_escape_string($pre_id) . "%'") or die(mysql_error());
		$num = mysql_num_rows($q2);
		$id = $pre_id . str_pad(($num+1),9,"0",STR_PAD_LEFT);
		
		mysql_query("INSERT INTO vericon.address (id, building_type, building_number, suburb, state, postcode) VALUES ('$id', '" . mysql_real_escape_string($building_type) . "', '" . mysql_real_escape_string($building_number) . "', '" . mysql_real_escape_string($suburb) . "', '" . mysql_real_escape_string($state) . "', '" . mysql_real_escape_string($postcode) . "')") or die(mysql_error());
		
		echo $id;
	}
}
elseif ($type == "display")
{
	$id = $_GET["id"];
	if (substr($id,0,2) == "GA")
	{
		$q = mysql_query("SELECT * FROM gnaf.ADDRESS_DETAIL WHERE address_detail_pid = '$id'") or die(mysql_error());
		$data = mysql_fetch_assoc($q);
		
		if ($data["number_first"] == 0 && $data["number_last"] == 0)
		{
			$street_number = "LOT " . $data["lot_number"] . $data["lot_number_suffix"];
		}		
		elseif ($data["flat_number"] != 0)
		{
			$q1 = mysql_query("SELECT `name` FROM gnaf.FLAT_TYPE_AUT WHERE code = '" . mysql_real_escape_string($data["flat_type_code"]) . "'") or die(mysql_error());
			$ft = mysql_fetch_row($q1);
			
			$street_number = $ft[0] . " " . $data["flat_number"] . $data["flat_number_suffix"] . "/";
		}
		elseif ($data["level_number"] != 0)
		{
			$street_number = "LEVEL " . $data["level_number"] . $data["level_number_suffix"] . "/";
		}
		
		if ($data["number_first"] != 0)
		{
			$street_number .= $data["number_first"] . $data["number_first_suffix"];
		}
		
		if ($data["number_last"] != 0)
		{
			$street_number .= "-" . $data["number_last"];
		}
		
		$street_name = $data["street_name"];
	
		if ($data["street_suffix_code"] != "")
		{
			$street_type = $data["street_type_code"] . " " . $data["street_suffix_code"];
		}
		else
		{
			$street_type = $data["street_type_code"];
		}
		
		$suburb = $data["locality_name"];
		$state = $data["state"];
		$postcode = $data["postcode"];
		
		echo preg_replace('!\s+!', ' ', trim($street_number . " " . $street_name . " " . $street_type)) . "}" . $suburb . "}" . $state . "}" . $postcode;
		
	}
	elseif (substr($id,0,2) == "MA")
	{
		$q = mysql_query("SELECT * FROM vericon.address WHERE id = '$id'") or die(mysql_error());
		$data = mysql_fetch_assoc($q);
		
		$building_type = $data["building_type"];
		$building_number = $data["building_number"];
		$building_number_suffix = $data["building_number_suffix"];
		$building_name = $data["building_name"];
		$number_first = $data["number_first"];
		$number_last = $data["number_last"];
		$street_name = $data["street_name"];
		$street_type = $data["street_type"];
		$suburb = $data["suburb"];
		$state = $data["state"];
		$postcode = $data["postcode"];
		
		if ($number_last != "")
		{
			$street_number = $number_first . "-" . $number_last;
		}
		else
		{
			$street_number = $number_first;
		}
		
		echo preg_replace('!\s+!', ' ', trim($building_type . " " . $building_number . " " . $building_name . " " . $street_number . " " . $street_name . " " . $street_type)) . "}" . $suburb . "}" . $state . "}" . $postcode;
	}
}
?>