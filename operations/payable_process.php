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
elseif ($method == "check")
{
	if (date("W", strtotime(date("Y")."W".(date("W") - 2)."7")) <= date("W", strtotime($date)))
	{
		echo "valid";
	}
	else
	{
		echo "You can only edit this pay week's timesheets";
	}
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
	
	$da = mysql_fetch_assoc($q);
	$q1 = mysql_query("SELECT * FROM sales_customers WHERE agent = '$user' AND WEEK(approved_timestamp) = '$week' AND status = 'Approved'") or die(mysql_error());
	$sale = mysql_num_rows($q1);
	
	$gross = ((16.57 * $hours) + $da["op_bonus"]) * 1.09;
	$net = $sale - $da["cancellations"];
	if ($net > 0)
	{
		echo "\$" . number_format($gross / $net,2);
	}
	else
	{
		echo "\$" . number_format($gross,2);
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
	
	$da = mysql_fetch_assoc($q);
	$q1 = mysql_query("SELECT * FROM sales_customers WHERE agent = '$user' AND WEEK(approved_timestamp) = '$week' AND status = 'Approved'") or die(mysql_error());
	$sale = mysql_num_rows($q1);
	
	$gross = ((16.57 * $da["op_hours"]) + $bonus) * 1.09;
	$net = $sale - $da["cancellations"];
	if ($net > 0)
	{
		echo "\$" . number_format($gross / $net,2);
	}
	else
	{
		echo "\$" . number_format($gross,2);
	}
}
?>