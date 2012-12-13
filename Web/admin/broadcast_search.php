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
$q = mysql_query("SELECT `user`, CONCAT(`first`, ' ', `last`) AS name FROM `vericon`.`auth` WHERE `user` LIKE '%" . mysql_real_escape_string($term) . "%' OR CONCAT(`first`, ' ', `last`) LIKE '%" . mysql_real_escape_string($term) . "%' ORDER BY `user` ASC") or die(mysql_error());
while ($data = mysql_fetch_assoc($q))
{
	$d[] = "{ \"id\": \"" . $data["user"] . "\", \"label\": \"" . $data["name"] . " (" . $data["user"] . ")\" }";
}
echo implode(", ",$d);
echo ']';
?>