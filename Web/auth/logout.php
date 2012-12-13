<?php
mysql_connect('localhost','vericon','18450be');

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

mysql_query("DELETE FROM `vericon`.`current_users` WHERE `token` = '" . mysql_real_escape_string($token) . "' LIMIT 1") or die(mysql_error());

setcookie('vc_token', null, time()-3600, '/');
?>