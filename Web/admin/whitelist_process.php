<?php
include("../auth/restrict_inner.php");

$method = $_POST["m"];

if ($method == "disable")
{
	$ip_start = $_POST["ip_start"];
	$ip_end = $_POST["ip_end"];
	
	mysql_query("UPDATE `vericon`.`allowedip` SET `status` = 'Disabled' WHERE `ip_start` = '" . mysql_real_escape_string(ip2long($ip_start)) . "' AND `ip_end` = '" . mysql_real_escape_string(ip2long($ip_end)) . "' LIMIT 1") or die(mysql_error());
}
elseif ($method == "enable")
{
	$ip_start = $_POST["ip_start"];
	$ip_end = $_POST["ip_end"];
	
	mysql_query("UPDATE `vericon`.`allowedip` SET `status` = 'Enabled' WHERE `ip_start` = '" . mysql_real_escape_string(ip2long($ip_start)) . "' AND `ip_end` = '" . mysql_real_escape_string(ip2long($ip_end)) . "' LIMIT 1") or die(mysql_error());
}
?>