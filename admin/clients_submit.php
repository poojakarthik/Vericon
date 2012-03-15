<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$type = $_GET["type"];
$method = $_GET["method"];

if ($type == "campaign")
{
	$id = $_GET["id"];
	$campaign = $_GET["campaign"];
	
	if ($method == "add")
	{
		mysql_query("INSERT INTO campaigns (id,campaign) VALUES ('" . mysql_escape_string($id) . "', '" . mysql_escape_string($campaign) . "')") or die(mysql_error());
	}
	elseif ($method == "delete")
	{
		mysql_query("DELETE FROM campaigns WHERE id = '" . mysql_escape_string($id) . "' AND campaign = '" . mysql_escape_string($campaign) . "' LIMIT 1") or die(mysql_error());
	}
}
elseif ($type == "centre")
{
	$centre = $_GET["centre"];
	$camps = $_GET["campaign"];
	$campaign = implode(",",$camps);
	
	if ($method == "add")
	{
		mysql_query("INSERT INTO centres (centre,campaign) VALUES ('" . mysql_escape_string($centre) . "', '" . mysql_escape_string($campaign) . "')") or die(mysql_error());
	}
	elseif ($method == "delete")
	{
		mysql_query("DELETE FROM centres WHERE centre = '" . mysql_escape_string($centre) . "' LIMIT 1") or die(mysql_error());
	}
	elseif ($method == "edit")
	{
		mysql_query("UPDATE centres SET campaign = '" . mysql_escape_string($campaign) . "' WHERE centre = '" . mysql_escape_string($centre) . "'") or die(mysql_error());
	}
}
?>