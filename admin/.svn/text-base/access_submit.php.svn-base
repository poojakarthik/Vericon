<?php
$method = $_GET["method"];
$ip = $_GET["ip"];
$description = $_GET["description"];

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

if ($method == "create")
{	
	$q = mysql_query("SELECT * FROM `allowedip` WHERE `IP` = '$ip'");
	$r = mysql_fetch_row($q);
	
	if ($r == true)
	{
		echo "IP already allowed!";
		exit;
	}
	else
	{		
		mysql_query("INSERT INTO `allowedip` (`IP`,`status`,`Description`) VALUES ('$ip','1','$description')");
		echo "created";
		exit;
	}
}
elseif ($method == "disable")
{
	mysql_query("UPDATE allowedip SET status = '0' WHERE IP = '$ip' LIMIT 1");
	exit;
}
elseif ($method == "enable")
{
	mysql_query("UPDATE allowedip SET status = '1' WHERE IP = '$ip' LIMIT 1");
	exit;
}
?>