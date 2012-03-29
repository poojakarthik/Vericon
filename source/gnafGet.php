<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$query = $_GET["term"];
$type = $_GET["type"];

if ($type == "suburb")
{
	$postcode = $_GET["postcode"];
	
	$q = mysql_query("SELECT locality_name FROM gnaf WHERE postcode = '$postcode' GROUP BY locality_name") or die(mysql_error());
	if(mysql_num_rows($q) == 0)
	{
		echo "<option>--- No Results Found ---</option>";
		exit;
	}
	echo "<option>--- Select a Suburb ---</option>";
	while ($data = mysql_fetch_row($q))
	{
		echo "<option>" .$data[0] . "</option>";
	}
}
elseif ($type == "street")
{
	$postcode = $_GET["postcode"];
	$suburb = $_GET["suburb"];
	
	$q = mysql_query("SELECT street_name FROM gnaf WHERE postcode = '$postcode' AND locality_name = '" . mysql_escape_string($suburb) . "' AND street_name LIKE '$query%' GROUP BY street_name") or die(mysql_error());
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
	$postcode = $_GET["postcode"];
	$suburb = str_replace("_", " ", $_GET["suburb"]);
	$street = str_replace("_", " ", $_GET["street"]);
	
	$q = mysql_query("SELECT street_type_code FROM gnaf WHERE postcode = '$postcode' AND locality_name = '" . mysql_escape_string($suburb) . "' AND street_name = '" . mysql_escape_string($street) . "' GROUP BY street_type_code") or die(mysql_error());
	echo "<option>--- Select a Street Type ---</option>";
	while ($data = mysql_fetch_row($q))
	{
		echo '<option>' . $data[0] . "</option>";
	}
}
elseif ($type == "check")
{
	$postcode = $_GET["postcode"];
	$suburb = str_replace("_", " ", $_GET["suburb"]);
	$street = str_replace("_", " ", $_GET["street"]);
	$street_type = str_replace("_", " ", $_GET["street_type"]);
	$number = $_GET["number"];
	$unit = $_GET["unit"];
	
	$q = mysql_query("SELECT * FROM gnaf WHERE postcode = '$postcode' AND locality_name = '" . mysql_escape_string($suburb) . "' AND street_name = '" . mysql_escape_string($street) . "' AND street_type_code = '$street_type' AND (number_first = '$number' OR number_last = '$number') AND flat_number LIKE '%$unit%'") or die(mysql_error());
	
	echo '<select multiple="multiple" id="address_code" style="width:100%; height:120px; margin:0px;">';
	if (mysql_num_rows($q) == 0)
	{
		echo '<option value="">No Results Found</option>';
		echo '</select>';
		exit;
	}
	while ($data = mysql_fetch_assoc($q))
	{
		$d_number = "";
		if ($data["FLAT_NUMBER"] != 0) { $d_number = $data["FLAT_TYPE_CODE"] . " " . $data["FLAT_NUMBER"] . "/"; } elseif ($data["LEVEL_NUMBER"] != 0) { $d_number = "LVL " . $data["LEVEL_NUMBER"] . "/"; }
		
		if ($data["NUMBER_LAST"] != 0) { $d_number = $d_number . $data["NUMBER_FIRST"] . "-" . $data["NUMBER_LAST"]; } else { $d_number = $d_number . $data["NUMBER_FIRST"] . $data["NUMBER_FIRST_SUFFIX"]; }
		
		if ($data["STREET_SUFFIX_CODE"] != "") { $d_street = $data["STREET_NAME"] . " " . $data["STREET_TYPE_CODE"] . " " . $data["STREET_SUFFIX_CODE"]; } else { $d_street = $data["STREET_NAME"] . " " . $data["STREET_TYPE_CODE"]; }
		
		echo '<option value="' . $data["ADDRESS_DETAIL_PID"] . '">' . $d_number . " " . $d_street . ", " . $data["LOCALITY_NAME"] . ", " . $data["STATE"] . " " . $data["POSTCODE"] . '</option>';
	}
	echo '</select>';	
}
elseif ($type == "check2")
{
	$postcode = $_GET["postcode"];
	$suburb = str_replace("_", " ", $_GET["suburb"]);
	$street = str_replace("_", " ", $_GET["street"]);
	$street_type = str_replace("_", " ", $_GET["street_type"]);
	$number = $_GET["number"];
	$unit = $_GET["unit"];
	
	$q = mysql_query("SELECT * FROM gnaf WHERE postcode = '$postcode' AND locality_name = '" . mysql_escape_string($suburb) . "' AND street_name = '" . mysql_escape_string($street) . "' AND street_type_code = '$street_type' AND (number_first = '$number' OR number_last = '$number') AND flat_number LIKE '%$unit%'") or die(mysql_error());
	
	echo '<select multiple="multiple" id="address_code_p" style="width:100%; height:120px; margin:0px;">';
	if (mysql_num_rows($q) == 0)
	{
		echo '<option value="">No Results Found</option>';
		echo '</select>';
		exit;
	}
	while ($data = mysql_fetch_assoc($q))
	{
		$d_number = "";
		if ($data["FLAT_NUMBER"] != 0) { $d_number = $data["FLAT_TYPE_CODE"] . " " . $data["FLAT_NUMBER"] . "/"; } elseif ($data["LEVEL_NUMBER"] != 0) { $d_number = "LVL " . $data["LEVEL_NUMBER"] . "/"; }
		
		if ($data["NUMBER_LAST"] != 0) { $d_number = $d_number . $data["NUMBER_FIRST"] . "-" . $data["NUMBER_LAST"]; } else { $d_number = $d_number . $data["NUMBER_FIRST"] . $data["NUMBER_FIRST_SUFFIX"]; }
		
		if ($data["STREET_SUFFIX_CODE"] != "") { $d_street = $data["STREET_NAME"] . " " . $data["STREET_TYPE_CODE"] . " " . $data["STREET_SUFFIX_CODE"]; } else { $d_street = $data["STREET_NAME"] . " " . $data["STREET_TYPE_CODE"]; }
		
		echo '<option value="' . $data["ADDRESS_DETAIL_PID"] . '">' . $d_number . " " . $d_street . ", " . $data["LOCALITY_NAME"] . ", " . $data["STATE"] . " " . $data["POSTCODE"] . '</option>';
	}
	echo '</select>';	
}
elseif ($type == "display")
{
	$id = $_GET["gnaf_id"];
	
	$q = mysql_query("SELECT * FROM gnaf WHERE address_detail_pid = '$id'") or die(mysql_error());
	$data = mysql_fetch_assoc($q);
	
	if ($data["FLAT_NUMBER"] != 0) { $d_number = $data["FLAT_TYPE_CODE"] . " " . $data["FLAT_NUMBER"] . "/"; } elseif ($data["LEVEL_NUMBER"] != 0) { $d_number = "LVL " . $data["LEVEL_NUMBER"] . "/"; }
	if ($data["NUMBER_LAST"] != 0) { $d_number = $d_number . $data["NUMBER_FIRST"] . "-" . $data["NUMBER_LAST"]; } else { $d_number = $d_number . $data["NUMBER_FIRST"] . $data["NUMBER_FIRST_SUFFIX"]; }
	if ($data["STREET_SUFFIX_CODE"] != "") { $d_street = $data["STREET_NAME"] . " " . $data["STREET_TYPE_CODE"] . " " . $data["STREET_SUFFIX_CODE"]; } else{ $d_street = $data["STREET_NAME"] . " " . $data["STREET_TYPE_CODE"]; }
	
	echo $d_number . " " . $d_street . ", " . $data["LOCALITY_NAME"] . ", " . $data["STATE"] . " " . $data["POSTCODE"];
}
elseif ($type == "manualdisplay")
{
	$id = $_GET["id"];
	
	$q = mysql_query("SELECT * FROM address WHERE id = '$id'") or die(mysql_error());
	$data = mysql_fetch_assoc($q);
	
	echo $data["street"] . ", " . $data["suburb"] . ", " . $data["state"] . " " . $data["postcode"];
}
elseif ($type == "manual")
{
	$postcode = $_GET["postcode"];
	$suburb = $_GET["suburb"];
	$street = $_GET["street"];
	
	$q0 = mysql_query("SELECT state FROM gnaf WHERE postcode = '$postcode' AND LOCALITY_NAME = '" . mysql_escape_string($suburb) . "' GROUP BY state") or die(mysql_error());
	$state = mysql_fetch_row($q0);
	
	$q1 = mysql_query("SELECT COUNT(id) FROM address WHERE id LIKE 'MA%'") or die(mysql_error());
	$num = mysql_fetch_row($q1);
	
	$id = "MA" . $state[0] . str_pad(($num[0]+1),9,"0",STR_PAD_LEFT);
	
	mysql_query("INSERT INTO address (id,street,suburb,state,postcode) VALUES ('$id', '" . mysql_escape_string($street) . "', '" . mysql_escape_string($suburb) . "', '$state[0]', '$postcode')") or die(mysql_error());
	
	echo $id;
}
?>