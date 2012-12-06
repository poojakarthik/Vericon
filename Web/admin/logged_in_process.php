<?php
include("../auth/restrict_inner.php");

$user = $_POST["user"];
mysql_query("DELETE FROM `vericon`.`current_users` WHERE `user` = '" . mysql_real_escape_string($user) . "' LIMIT 1");
?>