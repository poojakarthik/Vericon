<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$method = $_GET["method"];
$centre = trim($_GET["centre"]);
$campaign = $_GET["campaign"];
$type = $_GET["type"];
$leads = $_GET["leads"];

if ($method == "add")
{
	$q = mysql_query("SELECT * FROM vericon.centres WHERE centre = '" . mysql_real_escape_string($centre) . "'") or die(mysql_error());
	
	if (!preg_match("/CC([0-9]{2})/", $centre))
	{
		echo "Please enter a valid centre code";
	}
	elseif (mysql_num_rows($q) != 0)
	{
		echo "Centre already exists";
	}
	elseif ($campaign == "")
	{
		echo "Please select a campaign";
	}
	elseif ($type == "")
	{
		echo "Please select a centre type";
	}
	elseif ($leads == "")
	{
		echo "Please select a lead validation status";
	}
	else
	{
		mysql_query("INSERT INTO vericon.centres (centre, campaign, type, status, leads) VALUES ('" . mysql_real_escape_string($centre) . "', '" . mysql_real_escape_string($campaign) . "', '$type', 'Enabled', '$leads')") or die(mysql_error());
		
		echo "added";
	}
}
elseif ($method == "edit")
{
	mysql_query("UPDATE vericon.centres SET campaign = '" . mysql_real_escape_string($campaign) . "', type = '$type', leads = '$leads' WHERE centre = '" . mysql_real_escape_string($centre) . "'") or die(mysql_error());
	
	echo "editted";
}
elseif ($method == "campaign")
{
	$q = mysql_query("SELECT campaign FROM vericon.centres WHERE centre = '$centre'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo $data[0];
}
elseif ($method == "type")
{
	$q = mysql_query("SELECT type FROM vericon.centres WHERE centre = '$centre'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo $data[0];
}
elseif ($method == "leads")
{
	$q = mysql_query("SELECT leads FROM vericon.centres WHERE centre = '$centre'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo $data[0];
}
elseif ($method == "disable")
{
	mysql_query("UPDATE vericon.centres SET status = 'Disabled' WHERE centre = '" . mysql_real_escape_string($centre) . "'") or die(mysql_error());
	
	mysql_query("UPDATE vericon.auth SET status = 'Disabled' WHERE centre = '" . mysql_real_escape_string($centre) . "'") or die(mysql_error());
}
elseif ($method == "enable")
{
	mysql_query("UPDATE vericon.centres SET status = 'Enabled' WHERE centre = '" . mysql_real_escape_string($centre) . "'") or die(mysql_error());
}
?>