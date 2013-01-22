<?php
$mysqli = new mysqli('localhost','vericon','18450be');

function CheckAccess()
{
	if (ip2long($_SERVER['REMOTE_ADDR']) != ip2long("122.129.217.194")) {
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

$q = $mysqli->query("SELECT `auth`.`user`, `auth`.`type`, `auth`.`centre`, `auth`.`status`, `auth`.`first`, `auth`.`last`, `auth`.`alias` FROM `vericon`.`auth`, `vericon`.`current_users` WHERE `current_users`.`token` = '" . $mysqli->real_escape_string($token) . "' AND `current_users`.`user` = `auth`.`user`") or die($mysqli->error);
$ac = $q->fetch_assoc();

if ($q->num_rows == 0)
{
	header('HTTP/1.1 420 Not Logged In');
	exit;
}
if ($ac["status"] != "Enabled")
{
	header('HTTP/1.1 421 Account Disabled');
	exit;
}
$q->free();

$term = trim($_POST["term"]);

echo '[';
//IP
$q = $mysqli->query("SELECT INET_NTOA(`ip_start`) AS ip_start, INET_NTOA(`ip_end`) AS ip_end, `description` FROM `vericon`.`allowedip` WHERE '" . $mysqli->real_escape_string(ip2long($term)) . "' BETWEEN `ip_start` AND `ip_end` ORDER BY `ip_start` ASC") or die($mysqli->error);
while ($data = $q->fetch_assoc())
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
$q->free();
//Description
$q = $mysqli->query("SELECT `description` FROM `vericon`.`allowedip` WHERE `description` LIKE '%" . $mysqli->real_escape_string($term) . "%' GROUP BY `description` ORDER BY `description` ASC") or die($mysqli->error);
while ($data = $q->fetch_assoc())
{
	$d[] = "{ \"id\": \"" . $data["description"] . "\", \"category\": \"Description\", \"label\": \"" . $data["description"] . "\" }";
}
$q->free();
echo implode(", ",$d);
echo ']';
$mysqli->close();
?>