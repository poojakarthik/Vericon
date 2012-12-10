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
elseif ($method == "add")
{
	$ip_start = trim($_POST["ip_start"]);
	$ip_end = trim($_POST["ip_end"]);
	$description = trim ($_POST["description"]);
	
	$q_start = mysql_query("SELECT * FROM `vericon`.`allowedip` WHERE '" . mysql_real_escape_string(ip2long($ip_start)) . "' BETWEEN `ip_start` AND `ip_end`") or die(mysql_error());
	$q_end = mysql_query("SELECT * FROM `vericon`.`allowedip` WHERE '" . mysql_real_escape_string(ip2long($ip_end)) . "' BETWEEN `ip_start` AND `ip_end`") or die(mysql_error());
	
	if ($ip_start == "")
	{
		echo "<b>Error: </b>Please enter a start IP address.";
	}
	elseif (!filter_var($ip_start, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
	{
		echo "<b>Error: </b>The entered start IP address is invalid.";
	}
	elseif (mysql_num_rows($q_start) != 0)
	{
		echo "<b>Error: </b>The entered start IP address is already within a range.";
	}
	elseif ($ip_end == "")
	{
		echo "<b>Error: </b>Please enter an end IP address.";
	}
	elseif (!filter_var($ip_end, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
	{
		echo "<b>Error: </b>The entered end IP address is invalid.";
	}
	elseif (mysql_num_rows($q_end) != 0)
	{
		echo "<b>Error: </b>The entered end IP address is already within a range.";
	}
	elseif ($description == "")
	{
		echo "<b>Error: </b>Please enter a description.";
	}
	else
	{
		mysql_query("INSERT INTO `vericon`.`allowedip` (`ip_start`, `ip_end`, `description`, `status`, `added_by`, `timestamp`) VALUES ('" . mysql_real_escape_string(ip2long($ip_start)) . "', '" . mysql_real_escape_string(ip2long($ip_end)) . "', '" . mysql_real_escape_string($description) . "', 'Enabled', '" . mysql_real_escape_string($ac["user"]) . "', NOW())");
		
		echo "valid" . $ip_start . "," . $ip_end;
	}
}
?>