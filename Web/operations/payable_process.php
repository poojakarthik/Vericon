<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];
$centre = $_GET["centre"];
$date = $_GET["date"];
$week = date("W", strtotime($date));

if ($method == "hours")
{
	$user = $_GET["user"];
	$hours = $_GET["hours"];
	
	$q = mysql_query("SELECT * FROM vericon.timesheet_other WHERE user = '$user' AND week = '$week'") or die(mysql_error());
	if (mysql_num_rows($q) == 0)
	{
		mysql_query("INSERT INTO vericon.timesheet_other (user, week, op_hours) VALUES ('$user', '$week', '$hours')") or die(mysql_error());
	}
	else
	{
		mysql_query("UPDATE vericon.timesheet_other SET op_hours = '$hours' WHERE user = '$user' AND week = '$week'") or die(mysql_error());
	}
	
	$da = mysql_fetch_assoc($q);
	$q1 = mysql_query("SELECT * FROM vericon.sales_customers WHERE agent = '$user' AND WEEK(approved_timestamp) = '$week' AND status = 'Approved'") or die(mysql_error());
	$sale = mysql_num_rows($q1);
	
	$q2 = mysql_query("SELECT rate,type FROM vericon.timesheet_rate WHERE user = '$user'") or die(mysql_error());
	$da2 = mysql_fetch_row($q2);
	
	if ($da2[0] == "")
	{
		$rate = 17.0458;
	}
	else
	{
		if ($da2[1] == "F")
		{
			$rate = $da2[0];
		}
		elseif ($da2[1] == "T")
		{
			$q3 = mysql_query("SELECT designation FROM vericon.timesheet_designation WHERE user = '$user'") or die(mysql_error());
			$desig = mysql_fetch_row($q3);
			
			$q3 = mysql_query("SELECT rate FROM vericon.timesheet_tiered WHERE designation = '$desig[0]' AND '$sale' BETWEEN `from` AND `to`") or die(mysql_error());
			$t_rate = mysql_fetch_row($q3);
			
			$rate = $t_rate[0];
		}
	}
	
	$gross = (($rate * $hours) + $da["op_bonus"]) * 1.09;
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
	
	$q = mysql_query("SELECT * FROM vericon.timesheet_other WHERE user = '$user' AND week = '$week'") or die(mysql_error());
	if (mysql_num_rows($q) == 0)
	{
		mysql_query("INSERT INTO vericon.timesheet_other (user, week, op_bonus) VALUES ('$user', '$week', '$bonus')") or die(mysql_error());
	}
	else
	{
		mysql_query("UPDATE vericon.timesheet_other SET op_bonus = '$bonus' WHERE user = '$user' AND week = '$week'") or die(mysql_error());
	}
	
	$da = mysql_fetch_assoc($q);
	$q1 = mysql_query("SELECT * FROM vericon.sales_customers WHERE agent = '$user' AND WEEK(approved_timestamp) = '$week' AND status = 'Approved'") or die(mysql_error());
	$sale = mysql_num_rows($q1);
	
	$q2 = mysql_query("SELECT rate,type FROM vericon.timesheet_rate WHERE user = '$user'") or die(mysql_error());
	$da2 = mysql_fetch_row($q2);
	
	if ($da2[0] == "")
	{
		$rate = 17.0458;
	}
	else
	{
		if ($da2[1] == "F")
		{
			$rate = $da2[0];
		}
		elseif ($da2[1] == "T")
		{
			$q3 = mysql_query("SELECT designation FROM vericon.timesheet_designation WHERE user = '$user'") or die(mysql_error());
			$desig = mysql_fetch_row($q3);
			
			$q3 = mysql_query("SELECT rate FROM vericon.timesheet_tiered WHERE designation = '$desig[0]' AND '$sale' BETWEEN `from` AND `to`") or die(mysql_error());
			$t_rate = mysql_fetch_row($q3);
			
			$rate = $t_rate[0];
		}
	}
	
	$gross = (($rate * $da["op_hours"]) + $bonus) * 1.09;
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