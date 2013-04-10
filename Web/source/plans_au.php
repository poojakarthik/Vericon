<?php
include("../auth/restrict_inner.php");

$campaign = $_POST["campaign"];
$type = $_POST["type"];
$cli = $_POST["cli"];
$zone = "ADSL Regional";

if (!preg_match('/^0[2378][0-9]{8}$/',$cli))
{
	echo "error:=<option value=''>Invalid CLI</option>";
	$mysqli->close();
	exit;
}
// validation checks

if (preg_match('/0([2378])([0-9]{8})/',$cli,$d))
{
	$q = $mysqli->query("SELECT * FROM `adsl`.`Enabled_Exchanges` WHERE `Range_From` <= " . $mysqli->real_escape_string($d[2]) . " AND `Range_To` >= " . $mysqli->real_escape_string($d[2]) . " AND `AC` = '" . $mysqli->real_escape_string($d[1]) . "'") or die($mysqli->error);
	
	if($q->num_rows != 0)
	{
		$r = $q->fetch_row();
		
		if ($r[8] == "Zone 1")
		{
			$zone = "ADSL Metro";
		}
		else
		{
			$zone = "ADSL Regional";
		}
	}
	
	$q->free();
}

$sct = $mysqli->query("SELECT `cli` FROM `vericon`.`sct_dnc` WHERE `cli` = '" . $mysqli->real_escape_string($cli) . "'") or die($mysqli->error);

if ($sct->num_rows != 0)
{
	echo "error:=<option value=''>SCT DNC</option>";
	$sct->free();
	$mysqli->close();
	exit;
}
?>
<option></option>
<option disabled="disabled">--- Landline ---</option>
<?php
$q = $mysqli->query("SELECT * FROM `vericon`.`plan_matrix` WHERE `status` = 'Active' AND `rating` = '" . $mysqli->real_escape_string($type) . "' AND `type` = 'PSTN' AND `campaign` = '" . $mysqli->real_escape_string($campaign) . "' AND `name` != 'Addon' ORDER BY `id` ASC") or die($mysqli->error);

while ($l_plan = $q->fetch_assoc())
{
	echo "<option value='" . $l_plan["id"] . "'>" . $l_plan["name"] . "</option>";
}

$q->free();

$q = $mysqli->query("SELECT * FROM `vericon`.`plan_matrix` WHERE `status` = 'Active' AND `rating` = '" . $mysqli->real_escape_string($type) . "' AND `type` = 'PSTN' AND `campaign` = '" . $mysqli->real_escape_string($campaign) . "' AND `name` = 'Addon'") or die($mysqli->error);

while ($l_plan = $q->fetch_assoc())
{
	echo "<option value='" . $l_plan["id"] . "'>" . $l_plan["name"] . "</option>";
}

$q->free();
?>
<option disabled="disabled">--- Internet ---</option>
<?php
$q = $mysqli->query("SELECT * FROM `vericon`.`plan_matrix` WHERE `status` = 'Active' AND `rating` = '" . $mysqli->real_escape_string($type) . "' AND `type` = '" . $mysqli->real_escape_string($zone) . "' AND `campaign` = '" . $mysqli->real_escape_string($campaign) . "' ORDER BY `id` ASC") or die($mysqli->error);

while ($a_plan = $q->fetch_assoc())
{
	echo "<option value='" . $a_plan["id"] . "'>" . $a_plan["name"] . "</option>";
}

$q->free();
?>
<option disabled="disabled">--- Bundle ---</option>
<?php
$q = $mysqli->query("SELECT * FROM `vericon`.`plan_matrix` WHERE `status` = 'Active' AND `rating` = '" . $mysqli->real_escape_string($type) . "' AND `type` = 'Bundle' AND `campaign` = '" . $mysqli->real_escape_string($campaign) . "' ORDER BY `id` ASC") or die($mysqli->error);

while ($b_plan = $q->fetch_assoc())
{
	echo "<option value='" . $b_plan["id"] . "'>" . $b_plan["name"] . "</option>";
}

$q->free();
$mysqli->close();
?>