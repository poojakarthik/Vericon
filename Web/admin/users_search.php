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
//User
$q = $mysqli->query("SELECT `user`, CONCAT(`first`, ' ', `last`) AS name FROM `vericon`.`auth` WHERE `user` LIKE '%" . $mysqli->real_escape_string($term) . "%' OR CONCAT(`first`, ' ', `last`) LIKE '%" . $mysqli->real_escape_string($term) . "%' ORDER BY `user` ASC") or die($mysqli->error);
while ($data = $q->fetch_assoc())
{
	$d[] = "{ \"id\": \"" . $data["user"] . "\", \"category\": \"Users\", \"label\": \"" . $data["name"] . " (" . $data["user"] . ")\" }";
}
$q->free();
//Type
$q = $mysqli->query("SELECT `id`, `name` FROM `vericon`.`portals` WHERE `id` LIKE '%" . $mysqli->real_escape_string($term) . "%' OR `name` LIKE '%" . $mysqli->real_escape_string($term) . "%' ORDER BY `id` ASC") or die($mysqli->error);
while ($data = $q->fetch_assoc())
{
	$d[] = "{ \"id\": \"" . $data["id"] . "\", \"category\": \"Departments\", \"label\": \"" . $data["name"] . "\" }";
}
$q->free();
//Centre
$q = $mysqli->query("SELECT `id` FROM `vericon`.`centres` WHERE `id` LIKE '%" . $mysqli->real_escape_string($term) . "%' ORDER BY `id` ASC") or die($mysqli->error);
while ($data = $q->fetch_assoc())
{
	$d[] = "{ \"id\": \"" . $data["id"] . "\", \"category\": \"Centres\", \"label\": \"" . $data["id"] . "\" }";
}
$q->free();
echo implode(", ",$d);
echo ']';
$mysqli->close();
?>