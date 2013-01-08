<?php
include("../auth/restrict_inner.php");

$user = $_POST["user"];
$mysqli->query("DELETE FROM `vericon`.`current_users` WHERE `user` = '" . $mysqli->real_escape_string($user) . "' LIMIT 1") or die($mysqli->error);
$mysqli->close();
?>