<?php
mysql_connect('localhost','vericon','18450be');

function CheckAccess()
{
	$q = mysql_query("SELECT * FROM `vericon`.`allowedip` WHERE '" . mysql_real_escape_string(ip2long($_SERVER['REMOTE_ADDR'])) . "' BETWEEN `ip_start` AND `ip_end` AND `status` = 'Enabled'") or die(mysql_error());
	
	if (mysql_num_rows($q) == 0) {
		return false;
	} else {
		return true;
	}
}

$referer = $_SERVER['SERVER_NAME'] . "/main/";
$referer_check = split("//", $_SERVER['HTTP_REFERER']);

if ($referer_check[1] != $referer || !CheckAccess())
{
	header('HTTP/1.1 403 Forbidden');
	include("../error/forbidden.php");
	exit;
}

$token = $_COOKIE['vc_token'];

$q = mysql_query("SELECT `auth`.`user`, `auth`.`type`, `auth`.`centre`, `auth`.`status`, `auth`.`first`, `auth`.`last`, `auth`.`alias` FROM `vericon`.`auth`, `vericon`.`current_users` WHERE `current_users`.`token` = '" . mysql_real_escape_string($token) . "' AND `current_users`.`user` = `auth`.`user`") or die(mysql_error());
$ac = mysql_fetch_assoc($q);

if (mysql_num_rows($q) == 0)
{
	header('HTTP/1.1 420 Not Logged In');
	exit;
}

if ($ac["status"] != "Enabled")
{
	header('HTTP/1.1 421 Account Disabled');
	exit;
}

$term = trim($_POST["term"]);

echo '[';
//IP
$q = mysql_query("SELECT INET_NTOA(`ip_start`) AS ip_start, INET_NTOA(`ip_end`) AS ip_end, `description` FROM `vericon`.`allowedip` WHERE '" . mysql_real_escape_string(ip2long($term)) . "' BETWEEN `ip_start` AND `ip_end` ORDER BY `ip_start` ASC") or die(mysql_error());
while ($data = mysql_fetch_assoc($q))
{
	if ($data["ip_start"] != $data["ip_end"])
	{
		$d[] = "{ \"id\": \"" . $data["ip_start"] . "," . $data["ip_end"] . "\", \"category\": \"IPs\", \"label\": \"" . $data["description"] . " (" . $data["ip_start"] . " - " . $data["ip_end"] . ")\" }";
	}
	else
	{
		$d[] = "{ \"id\": \"" . $data["ip_start"] . "," . $data["ip_end"] . "\", \"category\": \"IPs\", \"label\": \"" . $data["description"] . " (" . $data["ip_start"] . ")\" }";
	}
}
//Description
$q = mysql_query("SELECT `description` FROM `vericon`.`allowedip` WHERE `description` LIKE '%" . mysql_real_escape_string($term) . "%' GROUP BY `description` ORDER BY `description` ASC") or die(mysql_error());
while ($data = mysql_fetch_assoc($q))
{
	$d[] = "{ \"id\": \"" . $data["description"] . "\", \"category\": \"Description\", \"label\": \"" . $data["description"] . "\" }";
}
echo implode(", ",$d);
echo ']';
?>