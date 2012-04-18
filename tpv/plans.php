<?php
mysql_connect('localhost','adsl','18450be');
mysql_select_db('adsl');

$type = $_GET["type"];
$cli = $_GET["cli"];

if (preg_match('/0([2378])([0-9]{8})/',$cli,$d))
{
	$q = mysql_query("SELECT * FROM Enabled_Exchanges WHERE Range_From <= $d[2] AND Range_To >= $d[2] AND AC = '$d[1]'") or die(mysql_error());
	
	if(mysql_num_rows($q) == 0)
	{
		$av = "NA";
	}
	else
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

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');
?>
<option></option>
<option disabled="disabled">--- Landline ---</option>
<?php
$qp = mysql_query("SELECT * FROM plan_matrix WHERE status = 'Active' AND rating = '$type' AND type = 'PSTN' AND name != 'Addon' ORDER BY id ASC");

while ($l_plan = mysql_fetch_assoc($qp))
{
	echo "<option value='" . $l_plan["id"] . "'>" . $l_plan["name"] . "</option>";
}

$qp = mysql_query("SELECT * FROM plan_matrix WHERE status = 'Active' AND rating = '$type' AND type = 'PSTN' AND name = 'Addon'");

while ($l_plan = mysql_fetch_assoc($qp))
{
	echo "<option value='" . $l_plan["id"] . "'>" . $l_plan["name"] . "</option>";
}
?>
<option disabled="disabled">--- Internet ---</option>
<?php
if ($av == "NA")
{
	echo "<option disabled='disabled'>ADSL Not Available</option>";
}
else
{
	$qp = mysql_query("SELECT * FROM plan_matrix WHERE status = 'Active' AND rating = '$type' AND type = '$zone' ORDER BY id ASC");

	while ($a_plan = mysql_fetch_assoc($qp))
	{
		echo "<option value='" . $a_plan["id"] . "'>" . $a_plan["name"] . "</option>";
	}
}
?>
<option disabled="disabled">--- Bundle ---</option>
<?php
if ($av == "NA")
{
	echo "<option disabled='disabled'>ADSL Not Available</option>";
}
else
{
	$qp = mysql_query("SELECT * FROM plan_matrix WHERE status = 'Active' AND rating = '$type' AND type = 'Bundle' ORDER BY id ASC");
	
	while ($b_plan = mysql_fetch_assoc($qp))
	{
		echo "<option value='" . $b_plan["id"] . "'>" . $b_plan["name"] . "</option>";
	}
}
?>