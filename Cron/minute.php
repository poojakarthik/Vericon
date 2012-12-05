<?php
mysql_connect('localhost','vericon','18450be');

$fifteen = date("Y-m-d H:i:s", strtotime("-15 minutes"));
$thirty = date("Y-m-d H:i:s", strtotime("-30 minutes"));

// Logout inactive users
mysql_query("DELETE FROM `vericon`.`current_users` WHERE `last_action` < '" . mysql_real_escape_string($fifteen) . "'") or die(mysql_error());
?>