<?php
$method = $_GET["method"];
$ip = $_GET["ip"];
$description = $_GET["description"];
$user = $_GET["user"];
$timestamp = date("Y-m-d H:i:s");

mysql_connect('localhost','vericon','18450be');

if ($method == "add")
{	
	$q = mysql_query("SELECT * FROM vericon.allowedip WHERE IP = '" . mysql_real_escape_string($ip) . "'");
	
	if ($user == "")
	{
		echo "Error! Please contact your administrator";
	}
	elseif (!preg_match('/^(?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)(?:[.](?:25[0-5]|2[0-4]\d|1\d\d|[1-9]\d|\d)){3}$/',$ip))
	{
		echo "Please enter a valid IP";
	}
	elseif ($description == "")
	{
		echo "Please enter a description";
	}
	elseif (mysql_num_rows($q) != 0)
	{
		echo "IP already allowed";
	}
	else
	{		
		mysql_query("INSERT INTO vericon.allowedip (IP, status, Description, timestamp, added_by) VALUES ('" . mysql_real_escape_string($ip) . "', '1', '" . mysql_real_escape_string($description) . "', '$timestamp', '$user')");
		echo "added";
	}
}
elseif ($method == "disable")
{
	mysql_query("UPDATE vericon.allowedip SET status = '0' WHERE IP = '" . mysql_real_escape_string($ip) . "' LIMIT 1");
}
elseif ($method == "enable")
{
	mysql_query("UPDATE vericon.allowedip SET status = '1' WHERE IP = '" . mysql_real_escape_string($ip) . "' LIMIT 1");
}
?>