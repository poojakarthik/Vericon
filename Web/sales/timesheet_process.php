<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];

if ($method == "save");
{
	$date = $_GET["date"];
	$centre = $_GET["centre"];
	$user = explode(",", $_GET["users"]);
	$start = explode(",", $_GET["start"]);
	$end = explode(",", $_GET["end"]);
	$hours = explode(",", $_GET["hours"]);
	$bonus = explode(",", $_GET["bonus"]);
	
	for ($i = 0; $i <= count($user); $i++)
	{
		if ($start[$i] != "" && $end[$i] != "" && $hours[$i] != "")
		{
			$q = mysql_query("SELECT designation FROM vericon.timesheet_designation WHERE user = '$user[$i]'") or die(mysql_error());
			$designation = mysql_fetch_row($q);
			
			mysql_query("INSERT INTO vericon.timesheet (user, centre, date, designation, start, end, hours, bonus) VALUES ('$user[$i]', '$centre', '$date', '$designation[0]', '" . mysql_real_escape_string($start[$i]) . "', '" . mysql_real_escape_string($end[$i]) . "', '" . mysql_real_escape_string($hours[$i]) . "', '" . mysql_real_escape_string($bonus[$i]) . "') ON DUPLICATE KEY UPDATE centre = '$centre', designation = '$designation[0]', start = '" . mysql_real_escape_string($start[$i]) . "', end = '" . mysql_real_escape_string($end[$i]) . "', hours = '" . mysql_real_escape_string($hours[$i]) . "', bonus = '" . mysql_real_escape_string($bonus[$i]) . "'") or die(mysql_error());
		}
		else
		{
			mysql_query("DELETE FROM vericon.timesheet WHERE user = '$user[$i]' AND date = '$date' LIMIT 1") or die(mysql_error());
		}
	}
}
?>