<?php
$mysqli = new mysqli('localhost','vericon','18450be');

$two = date("Y-m-d H:i:s", strtotime("-2 minutes"));

$mysqli->query("DELETE FROM `vericon`.`invalid_login` WHERE `attempts` >= '3' AND `timestamp` < '" . $mysqli->real_escape_string($two) . "'") or die($mysqli->error);

$mysqli->close();
?>