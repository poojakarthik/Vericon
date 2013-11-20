<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];

if ($method == "add_group")
{
	$id = $_GET["id"];
	$name = $_GET["name"];
	
	$q = mysql_query("SELECT * FROM vericon.groups WHERE id = '" . mysql_real_escape_string($id) . "'") or die(mysql_error());
	$q1 = mysql_query("SELECT * FROM vericon.groups WHERE name = '" . mysql_real_escape_string($name) . "'") or die(mysql_error());
	
	if ($id == "")
	{
		echo "Please enter the group ID";
	}
	elseif (mysql_num_rows($q) != 0)
	{
		echo "This ID already exists";
	}
	elseif ($name == "")
	{
		echo "Please enter the group name";
	}
	elseif (mysql_num_rows($q1) != 0)
	{
		echo "This name already exists";
	}
	else
	{
		mysql_query("INSERT INTO vericon.groups (id, name) VALUES ('" . mysql_real_escape_string($id) . "', '" . mysql_real_escape_string($name) . "')") or die(mysql_error());
		
		echo "added";
	}
}
elseif ($method == "add_campaign")
{
	$id = $_GET["id"];
	$group = $_GET["group"];
	$name = $_GET["name"];
	
	$q = mysql_query("SELECT * FROM vericon.campaigns WHERE id = '" . mysql_real_escape_string($id) . "'") or die(mysql_error());
	$q1 = mysql_query("SELECT * FROM vericon.campaigns WHERE campaign = '" . mysql_real_escape_string($name) . "'") or die(mysql_error());
	
	if ($group == "")
	{
		echo "Error! Please contact your administrator";
	}
	elseif ($id == "")
	{
		echo "Please enter the campaign ID";
	}
	elseif (mysql_num_rows($q) != 0)
	{
		echo "This ID already exists";
	}
	elseif ($name == "")
	{
		echo "Please enter the campaign name";
	}
	elseif (mysql_num_rows($q1) != 0)
	{
		echo "This name already exists";
	}
	else
	{
		mysql_query("INSERT INTO vericon.campaigns (id, `group`, campaign) VALUES ('" . mysql_real_escape_string($id) . "', '" . mysql_real_escape_string($group) . "', '" . mysql_real_escape_string($name) . "')") or die(mysql_error());
		
		echo "added";
	}
}
?>