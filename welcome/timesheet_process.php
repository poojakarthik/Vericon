<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];

if ($method == "save");
{
	$date = $_GET["date"];
	$centre = "Welcome";
	$user = explode(",", $_GET["users"]);
	$start = explode(",", $_GET["start"]);
	$end = explode(",", $_GET["end"]);
	$hours = explode(",", $_GET["hours"]);
	
	for ($i = 0; $i <= count($user); $i++)
	{
		if ($start[$i] != "" && $end[$i] != "" && $hours[$i] != "")
		{
			mysql_query("INSERT INTO vericon.timesheet (user, centre, date, start, end, hours) VALUES ('$user[$i]', '$centre', '$date', '" . mysql_real_escape_string($start[$i]) . "', '" . mysql_real_escape_string($end[$i]) . "', '" . mysql_real_escape_string($hours[$i]) . "') ON DUPLICATE KEY UPDATE centre = '$centre', start = '" . mysql_real_escape_string($start[$i]) . "', end = '" . mysql_real_escape_string($end[$i]) . "', hours = '" . mysql_real_escape_string($hours[$i]) . "'") or die(mysql_error());
		}
		else
		{
			mysql_query("DELETE FROM vericon.timesheet WHERE user = '$user[$i]' AND date = '$date' LIMIT 1") or die(mysql_error());
		}
	}
}
?>