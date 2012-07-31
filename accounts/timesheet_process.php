<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$method = $_GET["method"];
$centre = $_GET["centre"];
$date = $_GET["date"];
$week = date("W", strtotime($date));

if ($method == "name")
{
	$user = $_GET["user"];
	$q = mysql_query("SELECT first,last FROM auth WHERE user = '$user'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	echo $data[0] . " " . $data[1];
}
elseif ($method == "annual")
{
	$user = $_GET["user"];
	$q = mysql_query("SELECT annual FROM timesheet_other WHERE user = '$user' AND week = '$week'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	echo $data[0];
}
elseif ($method == "sick")
{
	$user = $_GET["user"];
	$q = mysql_query("SELECT sick FROM timesheet_other WHERE user = '$user' AND week = '$week'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	echo $data[0];
}
elseif ($method == "comments")
{
	$user = $_GET["user"];
	$q = mysql_query("SELECT comment FROM timesheet_other WHERE user = '$user' AND week = '$week'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	echo $data[0];
}
elseif ($method == "hours")
{
	$user = $_GET["user"];
	$hours = $_GET["hours"];
	
	mysql_query("UPDATE timesheet_other SET op_hours = '$hours' WHERE user = '$user' AND week = '$week'") or die(mysql_error());
}
elseif ($method == "bonus")
{
	$user = $_GET["user"];
	$bonus = $_GET["bonus"];
	
	mysql_query("UPDATE timesheet_other SET op_bonus = '$bonus' WHERE user = '$user' AND week = '$week'") or die(mysql_error());
}
elseif ($method == "payg")
{
	$user = $_GET["user"];
	$payg = $_GET["payg"];
	$rate = $_GET["rate"];
	
	mysql_query("UPDATE timesheet_other SET payg = '$payg', rate = '$rate' WHERE user = '$user' AND week = '$week'") or die(mysql_error());
	
	$q = mysql_query("SELECT SUM(op_hours),SUM(op_bonus),SUM(annual),SUM(sick) FROM timesheet_other WHERE user = '$user' AND week = '$week'") or die(mysql_error());
	$da = mysql_fetch_row($q);
	
	$gross = ($rate * ($da[0] + $da[2] + $da[3])) + $da[1];
	$net =  $gross - $payg;
	echo "\$" . number_format($net,2);
}
elseif ($method == "m_cost")
{
	$m_cost = $_GET["m_cost"];
	$q = mysql_query("SELECT * FROM timesheet_mcost WHERE centre = '$centre' AND week = '$week'") or die(mysql_error());
	
	if(mysql_num_rows($q) == 0)
	{
		mysql_query("INSERT INTO timesheet_mcost (centre, week, m_cost) VALUES ('$centre', '$week', '$m_cost')") or die(mysql_error());
	}
	else
	{
		mysql_query("UPDATE timesheet_mcost SET m_cost = '$m_cost' WHERE centre = '$centre' AND week = '$week'") or die(mysql_error());
	}
}
elseif ($method == "other")
{
	$user = $_GET["user"];
	$annual = $_GET["annual"];
	$sick = $_GET["sick"];
	$comments = $_GET["comments"];
	
	if (!preg_match('/[0-9.]/', $annual) && $annual != "")
	{
		echo "Not a valid entry for Annual Leave Hours";
	}
	elseif (!preg_match('/[0-9.]/', $sick) && $sick != "")
	{
		echo "Not a valid entry for Sick Leave Hours";
	}
	else
	{
		mysql_query("UPDATE timesheet_other SET annual = '" . mysql_escape_string($annual) . "', sick = '" . mysql_escape_string($sick) . "', comment = '" . mysql_escape_string($comments) . "' WHERE user = '$user' AND week = '$week'") or die(mysql_error());
		echo "submitted";
	}
}
?>