<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];

if ($method == "call_back")
{
	$id = $_GET["id"];
	$time = date("Y-m-d") . " " . date("H:i:s", strtotime($_GET["time"]));
	
	mysql_query("INSERT INTO vericon.welcome_cb (id, time) VALUES ('$id', '$time') ON DUPLICATE KEY UPDATE time = '$time'") or die(mysql_error());
	
	echo "done";
}
?>