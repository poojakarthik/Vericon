<?php
if(eregi("MSIE",getenv("HTTP_USER_AGENT")) || eregi("Internet Explorer",getenv("HTTP_USER_AGENT"))) {
	echo "Sorry VeriCon is not supported by your web browser<br>Please use <b>Google Chrome</b> or <b>Firefox</b> to access VeriCon";
	exit;
}

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

function CheckAccess()
{
	$q = mysql_query("SELECT * FROM allowedip") or die(mysql_error());
	
	while ($iplist = mysql_fetch_assoc($q))
	{
		$allowedip[$iplist['IP']] = $iplist['status'];
	}
  	$ip = $_SERVER['REMOTE_ADDR'];
	return ($allowedip[$ip]);
}

if (!CheckAccess())
{
	header("Location: ../index.php");
	exit;
}

$q1 = mysql_query("SELECT user FROM currentuser WHERE hash = '" . $_COOKIE["hash"] . "'") 
  or die(mysql_error());

$user = mysql_fetch_row($q1);

$q2 = mysql_query("SELECT * FROM auth WHERE user = '$user[0]'") or die(mysql_error());

$ac = mysql_fetch_assoc($q2);

$p = strtolower($ac["type"]);

$p1 = explode(",",$p);

for ($i = 0;$i < count($p1);$i++)
{
	foreach ($p1 as &$value)
	{
    	$acc[$p1[$i]] = true;
	}
}

$d = explode("/",$_SERVER['PHP_SELF']);

if ($_SERVER[PHP_SELF] == "/index.php")
{
	if ($p != "")
	{
		header("Location: ../main.php");
	}
}
elseif (mysql_num_rows($q1) != 1)
{
	header("Location: ../index.php");
	exit;
}
elseif (preg_match("/admin/",$p) || $_SERVER[PHP_SELF] == "/main.php")
{
	
}
elseif ($acc[$d[1]] != true)
{
	header("Location: ../index.php");
	exit;
}

$access_level = $ac["access"];

if ($ac["status"] == "Disabled")
{
	setcookie("hash", "", time()-86400);
	header("Location: ../index.php?attempt=banned");
}
?>