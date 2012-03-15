<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$id = $_GET["id"];

if (substr($id,0,2) == "GA")
{
	$q = mysql_query("SELECT * FROM gnaf WHERE address_detail_pid = '$id'") or die(mysql_error());
	$data = mysql_fetch_assoc($q);
	
	if ($data["FLAT_NUMBER"] != 0) { $d_number = $data["FLAT_TYPE_CODE"] . " " . $data["FLAT_NUMBER"] . "/"; } elseif ($data["LEVEL_NUMBER"] != 0) { $d_number = "LVL " . $data["LEVEL_NUMBER"] . "/"; }
	if ($data["NUMBER_LAST"] != 0) { $d_number = $d_number . $data["NUMBER_FIRST"] . "-" . $data["NUMBER_LAST"]; } else { $d_number = $d_number . $data["NUMBER_FIRST"] . $data["NUMBER_FIRST_SUFFIX"]; }
	if ($data["STREET_SUFFIX_CODE"] != "") { $d_street = $data["STREET_NAME"] . " " . $data["STREET_TYPE_CODE"] . " " . $data["STREET_SUFFIX_CODE"]; } else{ $d_street = $data["STREET_NAME"] . " " . $data["STREET_TYPE_CODE"]; }
	
	$method = "GNAF";
	$street = $d_number . " " . $d_street;
	$suburb = $data["LOCALITY_NAME"];
	$state = $data["STATE"];
	$postcode = $data["POSTCODE"];
}
elseif (substr($id,0,2) == "MA")
{
	$q = mysql_query("SELECT * FROM address WHERE id = '$id'") or die(mysql_error());
	$data = mysql_fetch_assoc($q);
	
	$method = "Manual";
	$street = $data["street"];
	$suburb = $data["suburb"];
	$state = $data["state"];
	$postcode = $data["postcode"];
}
?>

<table border='0' width='100%'>
<tr>
<td>Address Method</td>
<td><b><?php echo $method; ?></b></td>
</tr>
<tr>
<td width='95px'>Street Address</td>
<td><input type='text' size='25' readonly='readonly' value='<?php echo $street; ?>'></td>
</tr>
<tr>
<td>Suburb</td>
<td><input type='text' size='25' readonly='readonly' value='<?php echo $suburb; ?>'></td>
</tr>
<tr>
<td>State</td>
<td><input type='text' size='5' readonly='readonly' value='<?php echo $state; ?>'></td>
</tr>
<tr>
<td>Postcode</td>
<td><input type='text' size='5' readonly='readonly' value='<?php echo $postcode; ?>'></td>
</tr>