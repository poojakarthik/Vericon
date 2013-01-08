<?php
include("../auth/restrict_inner.php");

$method = $_POST["m"];

if ($method == "disable")
{
	$ip_start = $_POST["ip_start"];
	$ip_end = $_POST["ip_end"];
	
	$mysqli->query("UPDATE `vericon`.`allowedip` SET `status` = 'Disabled' WHERE `ip_start` = '" . $mysqli->real_escape_string(ip2long($ip_start)) . "' AND `ip_end` = '" . $mysqli->real_escape_string(ip2long($ip_end)) . "' LIMIT 1") or die($mysqli->error);
}
elseif ($method == "enable")
{
	$ip_start = $_POST["ip_start"];
	$ip_end = $_POST["ip_end"];
	
	$mysqli->query("UPDATE `vericon`.`allowedip` SET `status` = 'Enabled' WHERE `ip_start` = '" . $mysqli->real_escape_string(ip2long($ip_start)) . "' AND `ip_end` = '" . $mysqli->real_escape_string(ip2long($ip_end)) . "' LIMIT 1") or die($mysqli->error);
}
elseif ($method == "add")
{
	$ip_start = trim($_POST["ip_start"]);
	$ip_end = trim($_POST["ip_end"]);
	$description = trim ($_POST["description"]);
	
	$q_start = $mysqli->query("SELECT * FROM `vericon`.`allowedip` WHERE '" . $mysqli->real_escape_string(ip2long($ip_start)) . "' BETWEEN `ip_start` AND `ip_end`") or die($mysqli->error);
	$q_end = $mysqli->query("SELECT * FROM `vericon`.`allowedip` WHERE '" . $mysqli->real_escape_string(ip2long($ip_end)) . "' BETWEEN `ip_start` AND `ip_end`") or die($mysqli->error);
	
	if ($ip_start == "")
	{
		echo "<b>Error: </b>Please enter a start IP address.";
	}
	elseif (!filter_var($ip_start, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4))
	{
		echo "<b>Error: </b>The entered start IP address is invalid.";
	}
	elseif ($q_start->num_rows != 0)
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
	elseif ($q_end->num_rows != 0)
	{
		echo "<b>Error: </b>The entered end IP address is already within a range.";
	}
	elseif ($description == "")
	{
		echo "<b>Error: </b>Please enter a description.";
	}
	else
	{
		$q_start->free();
		$q_end->free();
		
		$mysqli->query("INSERT INTO `vericon`.`allowedip` (`ip_start`, `ip_end`, `description`, `status`, `added_by`, `timestamp`) VALUES ('" . $mysqli->real_escape_string(ip2long($ip_start)) . "', '" . $mysqli->real_escape_string(ip2long($ip_end)) . "', '" . $mysqli->real_escape_string($description) . "', 'Enabled', '" . $mysqli->real_escape_string($ac["user"]) . "', NOW())") or die($mysqli->error);
		
		echo "valid" . $ip_start . "," . $ip_end;
	}
}
?>