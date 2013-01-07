<?php
$mysqli = new mysqli('localhost','vericon','18450be');

$fifteen = date("Y-m-d H:i:s", strtotime("-15 minutes"));

$mysqli->query("DELETE FROM `vericon`.`current_users` WHERE `last_action` < '" . $mysqli->real_escape_string($fifteen) . "'") or die($mysqli->error);
?>