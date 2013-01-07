<?php
$mysqli = new mysqli('localhost','vericon','18450be');

$referer = $_SERVER['SERVER_NAME'] . "/main/";
$referer_check = split("//", $_SERVER['HTTP_REFERER']);
if ($_SERVER["REQUEST_METHOD"] != "POST" || $referer_check[1] != $referer)
{
	header('HTTP/1.1 403 Forbidden');
	include("../error/forbidden.php");
	exit;
}

$token = $_COOKIE["vc_token"];;

if($token == ""){ exit; }

$mysqli->query("DELETE FROM `vericon`.`current_users` WHERE `token` = '" . $mysqli->real_escape_string($token) . "' LIMIT 1") or die($mysqli->error);

setcookie('vc_token', null, time()-3600, '/');
setcookie('vc_mail_token', null, time()-3600, '/');

$mysqli->close();
?>