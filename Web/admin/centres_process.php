<?php
include("../auth/restrict_inner.php");

$method = $_POST["m"];

if ($method == "disable")
{
	$centre = $_POST["centre"];
	
	$mysqli->query("UPDATE `vericon`.`centres` SET `status` = 'Disabled' WHERE `id` = '" . $mysqli->real_escape_string($centre) . "' LIMIT 1") or die($mysqli->error);
}
elseif ($method == "enable")
{
	$centre = $_POST["centre"];
	
	$mysqli->query("UPDATE `vericon`.`centres` SET `status` = 'Enabled' WHERE `id` = '" . $mysqli->real_escape_string($centre) . "' LIMIT 1") or die($mysqli->error);
}
elseif ($method == "add")
{
	$centre = strtoupper(trim($_POST["centre"]));
	$campaign = implode(",", $_POST["campaign"]);
	$type = $_POST["type"];
	$leads = $_POST["leads"];
	
	$q = $mysqli->query("SELECT `id` FROM `vericon`.`centres` WHERE `id` = '" . $mysqli->real_escape_string($centre) . "'") or die($mysqli->error);
	
	if (!preg_match("/^CC[0-9]{2}$/", $centre))
	{
		echo "<b>Error: </b>Please enter a valid centre code.";
	}
	elseif ($q->num_rows != 0)
	{
		echo "<b>Error: </b>Centre code is taken.";
	}
	elseif ($campaign == "")
	{
		echo "<b>Error: </b>Please select a campaign.";
	}
	elseif ($type == "")
	{
		echo "<b>Error: </b>Please select a centre type.";
	}
	elseif ($leads == "")
	{
		echo "<b>Error: </b>Please select a lead validation status.";
	}
	else
	{
		$mysqli->query("INSERT INTO `vericon`.`centres` (`id`, `campaign`, `type`, `status`, `leads`) VALUES ('" . $mysqli->real_escape_string($centre) . "', '" . $mysqli->real_escape_string($campaign) . "', '" . $mysqli->real_escape_string($type) . "', 'Enabled', '" . $mysqli->real_escape_string($leads) . "')") or die($mysqli->error);
		
		echo "valid";
	}
	$q->free();
}
elseif ($method == "edit")
{
	$centre = strtoupper(trim($_POST["centre"]));
	$campaign = implode(",", $_POST["campaign"]);
	$type = $_POST["type"];
	$leads = $_POST["leads"];
	
	if ($campaign == "")
	{
		echo "<b>Error: </b>Please select a campaign.";
	}
	elseif ($type == "")
	{
		echo "<b>Error: </b>Please select a centre type.";
	}
	elseif ($leads == "")
	{
		echo "<b>Error: </b>Please select a lead validation status.";
	}
	else
	{
		$mysqli->query("UPDATE `vericon`.`centres` SET `campaign` = '" . $mysqli->real_escape_string($campaign) . "', `type` = '" . $mysqli->real_escape_string($type) . "', `leads` = '" . $mysqli->real_escape_string($leads) . "' WHERE `id` = '" . $mysqli->real_escape_string($centre) . "' LIMIT 1") or die($mysqli->error);
		
		echo "valid";
	}
}
$mysqli->close();
?>