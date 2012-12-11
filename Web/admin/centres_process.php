<?php
include("../auth/restrict_inner.php");

$method = $_POST["m"];

if ($method == "disable")
{
	$centre = $_POST["centre"];
	
	mysql_query("UPDATE `vericon`.`centres` SET `status` = 'Disabled' WHERE `id` = '" . mysql_real_escape_string($centre) . "' LIMIT 1") or die(mysql_error());
}
elseif ($method == "enable")
{
	$centre = $_POST["centre"];
	
	mysql_query("UPDATE `vericon`.`centres` SET `status` = 'Enabled' WHERE `id` = '" . mysql_real_escape_string($centre) . "' LIMIT 1") or die(mysql_error());
}
elseif ($method == "add")
{
	$centre = strtoupper(trim($_POST["centre"]));
	$campaign = implode(",", $_POST["campaign"]);
	$type = $_POST["type"];
	$leads = $_POST["leads"];
	
	$q = mysql_query("SELECT `id` FROM `vericon`.`centres` WHERE `id` = '" . mysql_real_escape_string($centre) . "'") or die(mysql_error());
	
	if (!preg_match("/^CC[0-9]{2}$/", $centre))
	{
		echo "<b>Error: </b>Please enter a valid centre code.";
	}
	elseif (mysql_num_rows($q) != 0)
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
		mysql_query("INSERT INTO `vericon`.`centres` (`id`, `campaign`, `type`, `status`, `leads`) VALUES ('" . mysql_real_escape_string($centre) . "', '" . mysql_real_escape_string($campaign) . "', '" . mysql_real_escape_string($type) . "', 'Enabled', '" . mysql_real_escape_string($leads) . "')") or die(mysql_error());
		
		echo "valid";
	}
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
		mysql_query("UPDATE `vericon`.`centres` SET `campaign` = '" . mysql_real_escape_string($campaign) . "', `type` = '" . mysql_real_escape_string($type) . "', `leads` = '" . mysql_real_escape_string($leads) . "' WHERE `id` = '" . mysql_real_escape_string($centre) . "' LIMIT 1") or die(mysql_error());
		
		echo "valid";
	}
}
?>