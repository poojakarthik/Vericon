<?php
mysql_connect('localhost','vericon','18450be');

$id = $_GET["id"];
$type = $_GET["type"];
$cli = $_GET["cli"];
$zone = "ADSL Regional";

$q0 = mysql_query("SELECT campaign FROM vericon.sales_customers WHERE id = '" . mysql_real_escape_string($id) . "'") or die(mysql_error());
$c = mysql_fetch_row($q0);
$q = mysql_query("SELECT id FROM vericon.campaigns WHERE campaign = '" . mysql_real_escape_string($c[0]) . "'") or die(mysql_error());
$c2 = mysql_fetch_row($q);
$c_id = $c2[0];

if (preg_match('/0([2378])([0-9]{8})/',$cli,$d))
{
	$q = mysql_query("SELECT * FROM adsl.Enabled_Exchanges WHERE Range_From <= $d[2] AND Range_To >= $d[2] AND AC = '$d[1]'") or die(mysql_error());
	
	if(mysql_num_rows($q) != 0)
	{
		$r = mysql_fetch_row($q);
		
		if ($r[8] == "Zone 1")
		{
			$zone = "ADSL Metro";
		}
		else
		{
			$zone = "ADSL Regional";
		}
	}
}
?>
<option></option>
<option disabled="disabled">--- Landline ---</option>
<?php
$qp = mysql_query("SELECT * FROM vericon.plan_matrix WHERE status = 'Active' AND rating = '$type' AND type = 'PSTN' AND campaign = '" . mysql_real_escape_string($c_id) . "' AND name != 'Addon' ORDER BY id ASC");

while ($l_plan = mysql_fetch_assoc($qp))
{
	echo "<option value='" . $l_plan["id"] . "'>" . $l_plan["name"] . "</option>";
}

$qp = mysql_query("SELECT * FROM vericon.plan_matrix WHERE status = 'Active' AND rating = '$type' AND type = 'PSTN' AND campaign = '" . mysql_real_escape_string($c_id) . "' AND name = 'Addon'");

while ($l_plan = mysql_fetch_assoc($qp))
{
	echo "<option value='" . $l_plan["id"] . "'>" . $l_plan["name"] . "</option>";
}
?>
<option disabled="disabled">--- Internet ---</option>
<?php
$qp = mysql_query("SELECT * FROM vericon.plan_matrix WHERE status = 'Active' AND rating = '$type' AND type = '$zone' AND campaign = '" . mysql_real_escape_string($c_id) . "' ORDER BY id ASC");

while ($a_plan = mysql_fetch_assoc($qp))
{
	echo "<option value='" . $a_plan["id"] . "'>" . $a_plan["name"] . "</option>";
}
?>
<option disabled="disabled">--- Bundle ---</option>
<?php
$qp = mysql_query("SELECT * FROM vericon.plan_matrix WHERE status = 'Active' AND rating = '$type' AND type = 'Bundle' AND campaign = '" . mysql_real_escape_string($c_id) . "' ORDER BY id ASC");

while ($b_plan = mysql_fetch_assoc($qp))
{
	echo "<option value='" . $b_plan["id"] . "'>" . $b_plan["name"] . "</option>";
}
?>