<?php
mysql_connect('localhost','vericon','18450be');

$query = trim($_GET["term"]);
$type = $_GET["type"];

if ($type == "input")
{	
	$q = mysql_query("SELECT suburb, city_town, postcode FROM terralinks.po WHERE postcode LIKE '" . mysql_real_escape_string($query) . "%'") or die(mysql_error());
	echo '[';
	while ($data = mysql_fetch_row($q))
	{
		$id = strtoupper($data[0]) . "," . strtoupper($data[1]) . "," . strtoupper($data[2]);
		if ($data[0] == "")
		{
			$display = $data[1] . ", " . $data[2];
		}
		else
		{
			$display = strtoupper($data[0]) . ", " . strtoupper($data[1]) . ", " . $data[2];
		}
		$d[] = " { \"id\": \"$id\", \"label\": \"$display\" }";
	}
	echo implode(",",$d);
	echo ' ]';
}
elseif ($type == "store")
{
	$building_type = $_GET["building_type"];
	$building_number = preg_replace("/[^0-9]/", "", trim($_GET["building_number"]));
	$building_number_suffix = strtoupper(preg_replace("/[^a-zA-Z]/", "", trim($_GET["building_number"])));
	$building_name = strtoupper(trim($_GET["building_name"]));
	$street_number = explode("-",trim($_GET["street_number"]));
	$number_first = $street_number[0];
	$number_last = $street_number[1];
	$street_name = strtoupper(trim($_GET["street_name"]));
	$street_type = strtoupper(trim($_GET["street_type"]));
	$suburb = strtoupper($_GET["suburb"]);
	$city_town = strtoupper($_GET["city_town"]);
	$postcode = strtoupper($_GET["postcode"]);
	
	if ($building_type == "null") { $building_type = ""; }
	
	$q1 = mysql_query("SELECT id FROM vericon.address WHERE building_type = '" . mysql_real_escape_string($building_type) . "' AND building_number = '" . mysql_real_escape_string($building_number) . "' AND building_number_suffix = '" . mysql_real_escape_string($building_number_suffix) . "' AND building_name = '" . mysql_real_escape_string($building_name) . "' AND number_first = '" . mysql_real_escape_string($number_first) . "' AND number_last = '" . mysql_real_escape_string($number_last) . "' AND street_name = '" . mysql_real_escape_string($street_name) . "' AND street_type = '" . mysql_real_escape_string($street_type) . "' AND suburb = '" . mysql_real_escape_string($suburb) . "' AND city_town = '" . mysql_real_escape_string($city_town) . "' AND postcode = '" . mysql_real_escape_string($postcode) . "'") or die(mysql_error());
	if (mysql_num_rows($q1) != 0)
	{
		$id = mysql_fetch_row($q1);
		echo $id[0];
	}
	else
	{
		$pre_id = "MANZ_";
		$q2 = mysql_query("SELECT * FROM vericon.address WHERE id LIKE '" . mysql_real_escape_string($pre_id) . "%'") or die(mysql_error());
		$num = mysql_num_rows($q2);
		$id = $pre_id . str_pad(($num+1),9,"0",STR_PAD_LEFT);
		
		mysql_query("INSERT INTO vericon.address (id, building_type, building_number, building_number_suffix, building_name, number_first, number_last, street_name, street_type, suburb, city_town, postcode) VALUES ('$id', '" . mysql_real_escape_string($building_type) . "', '" . mysql_real_escape_string($building_number) . "', '" . mysql_real_escape_string($building_number_suffix) . "', '" . mysql_real_escape_string($building_name) . "', '" . mysql_real_escape_string($number_first) . "', '" . mysql_real_escape_string($number_last) . "', '" . mysql_real_escape_string($street_name) . "', '" . mysql_real_escape_string($street_type) . "', '" . mysql_real_escape_string($suburb) . "', '" . mysql_real_escape_string($city_town) . "', '" . mysql_real_escape_string($postcode) . "')") or die(mysql_error());
		
		echo $id;
	}
}
elseif ($type == "display" )
{
	$id = $_GET["id"];
	
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
	$city_town = $data["city_town"];
	$postcode = $data["postcode"];
	
	if ($number_last != "")
	{
		$street_number = $number_first . "-" . $number_last;
	}
	else
	{
		$street_number = $number_first;
	}
	
	echo preg_replace('!\s+!', ' ', trim($building_type . " " . $building_number . " " . $building_name . " " . $street_number . " " . $street_name . " " . $street_type)) . "}" . $suburb . "}" . $city_town . "}" . $postcode;
}
?>