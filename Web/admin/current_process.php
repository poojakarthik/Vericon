<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];

if ($method == "logout")
{
	$username = $_GET["username"];
	$hash = $_GET["hash"];
	mysql_query("DELETE FROM vericon.currentuser WHERE hash = '$hash' AND user = '$username' LIMIT 1");
}
?>