<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$method = $_GET["method"];
$centre = $_GET["centre"];
$date = $_GET["date"];
$week = date("W", strtotime($date));

if ($method == "from")
{
	$year = date("Y", strtotime($date));
	echo date("d/m/Y", strtotime($year . "W" . $week . "1"));
}
elseif ($method == "to")
{
	$year = date("Y", strtotime($date));
	echo date("d/m/Y", strtotime($year . "W" . $week . "7"));
}
elseif ($method == "hours")
{
	$user = $_GET["user"];
	$hours = $_GET["hours"];
	
	$q = mysql_query("SELECT * FROM timesheet_other WHERE user = '$user' AND week = '$week'") or die(mysql_error());
	if (mysql_num_rows($q) == 0)
	{
		mysql_query("INSERT INTO timesheet_other (user, week, op_hours) VALUES ('$user', '$week', '$hours')") or die(mysql_error());
	}
	else
	{
		mysql_query("UPDATE timesheet_other SET op_hours = '$hours' WHERE user = '$user' AND week = '$week'") or die(mysql_error());
	}
}
elseif ($method == "bonus")
{
	$user = $_GET["user"];
	$bonus = $_GET["bonus"];
	
	$q = mysql_query("SELECT * FROM timesheet_other WHERE user = '$user' AND week = '$week'") or die(mysql_error());
	if (mysql_num_rows($q) == 0)
	{
		mysql_query("INSERT INTO timesheet_other (user, week, op_bonus) VALUES ('$user', '$week', '$bonus')") or die(mysql_error());
	}
	else
	{
		mysql_query("UPDATE timesheet_other SET op_bonus = '$bonus' WHERE user = '$user' AND week = '$week'") or die(mysql_error());
	}
}
?>