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
elseif ($method == "payg")
{
	$user = $_GET["user"];
	$payg = $_GET["payg"];
	$rate = $_GET["rate"];
	
	mysql_query("UPDATE timesheet_other SET payg = '$payg', rate = '$rate' WHERE user = '$user' AND week = '$week'") or die(mysql_error());
	
	$q = mysql_query("SELECT SUM(op_hours),SUM(op_bonus) FROM timesheet_other WHERE user = '$user' AND week = '$week'") or die(mysql_error());
	$da = mysql_fetch_row($q);
	
	$gross = ($rate * $da[0]) + $da[1];
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
?>