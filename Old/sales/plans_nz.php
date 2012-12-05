<?php
mysql_connect('localhost','vericon','18450be');

$id = $_GET["id"];
$type = $_GET["type"];
$cli = $_GET["cli"];
$option = $_GET["option"];

$q0 = mysql_query("SELECT campaign FROM vericon.sales_customers_temp WHERE lead_id = '" . mysql_real_escape_string($id) . "'") or die(mysql_error());
$c = mysql_fetch_row($q0);
$q = mysql_query("SELECT id FROM vericon.campaigns WHERE campaign = '" . mysql_real_escape_string($c[0]) . "'") or die(mysql_error());
$c2 = mysql_fetch_row($q);
$c_id = $c2[0];

if (!preg_match("/^0[34679][0-9]{7}$/",$cli))
{
	echo "<option value=''>Invalid CLI</option>";
	exit;
}

$check = exec("perl /var/vericon/source/nz_legacy.pl " . $cli);

if ($check == "invalid")
{
	echo "<option value=''>Invalid Network</option>";
	exit;
}
elseif ($check == "error")
{
	echo "<option value=''>Error! Contact your administrator</option>";
	exit;
}
?>
<option></option>
<option disabled="disabled">--- Landline ---</option>
<?php
if ($check == "other")
{
	$qp = mysql_query("SELECT * FROM vericon.plan_matrix WHERE status = 'Active' AND rating = '$type' AND type = 'PSTN' AND campaign = '" . mysql_real_escape_string($c_id) . "' AND name != 'Addon' ORDER BY id ASC");
	
	while ($l_plan = mysql_fetch_assoc($qp))
	{
		echo "<option onclick='Plan_Option(\"$option\",\"PSTN\")' value='" . $l_plan["id"] . "'>" . $l_plan["name"] . "</option>";
	}
	
	$qp = mysql_query("SELECT * FROM vericon.plan_matrix WHERE status = 'Active' AND rating = '$type' AND type = 'PSTN' AND campaign = '" . mysql_real_escape_string($c_id) . "' AND name = 'Addon'");
	
	while ($l_plan = mysql_fetch_assoc($qp))
	{
		echo "<option onclick='Plan_Option(\"$option\",\"PSTN\")' value='" . $l_plan["id"] . "'>" . $l_plan["name"] . "</option>";
	}
}
else
{
	echo "<option disabled='disabled'>Landline Plans Not Available</option>";
}
?>
<option disabled="disabled">--- Bundle ---</option>
<?php
$qp = mysql_query("SELECT * FROM vericon.plan_matrix WHERE status = 'Active' AND rating = '$type' AND type = 'Bundle' AND campaign = '" . mysql_real_escape_string($c_id) . "' ORDER BY id ASC");

while ($b_plan = mysql_fetch_assoc($qp))
{
	echo "<option onclick='Plan_Option(\"$option\",\"Bundle\")' value='" . $b_plan["id"] . "'>" . $b_plan["name"] . "</option>";
}
?>