<?php
include("../auth/restrict_inner.php");

$method = $_POST["m"];

if ($method == "disable")
{
	$user = $_POST["user"];
	
	mysql_query("UPDATE `vericon`.`auth` SET `status` = 'Disabled' WHERE `user` = '" . mysql_real_escape_string($user) . "' LIMIT 1") or die(mysql_error());
}
elseif ($method == "enable")
{
	$user = $_POST["user"];
	
	mysql_query("UPDATE `vericon`.`auth` SET `status` = 'Enabled' WHERE `user` = '" . mysql_real_escape_string($user) . "' LIMIT 1") or die(mysql_error());
}
?>