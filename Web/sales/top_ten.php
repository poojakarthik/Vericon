<?php
$method = $_GET["method"];
$centre = $_GET["centre"];

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

if ($method == "Today")
{
	$today = date("Y-m-d");
	$q2 = mysql_query("SELECT agent, COUNT(*) FROM sales_customers WHERE status = 'Approved' AND centre = '$centre' AND DATE(approved_timestamp) = '$today' GROUP BY agent ORDER BY COUNT(*) DESC LIMIT 10") or die(mysql_error());
	while ($stats2 = mysql_fetch_row($q2))
	{
		$q3 = mysql_query("SELECT * FROM auth WHERE user = '$stats2[0]'") or die(mysql_error());
		$agent2 = mysql_fetch_assoc($q3);
		echo "<tr>";
		echo "<td align='left'>" . $agent2["first"] . " " . $agent2["last"] . "</td>";
		echo "<td>" . $stats2[1] . "</td>";
		echo "</tr>";
	}
}
elseif ($method == "Week")
{
	$week = date("W");
	$q2 = mysql_query("SELECT agent, COUNT(*) FROM sales_customers WHERE status = 'Approved' AND centre = '$centre' AND WEEK(timestamp) = '$week' GROUP BY agent ORDER BY COUNT(*) DESC LIMIT 10") or die(mysql_error());
	while ($stats2 = mysql_fetch_row($q2))
	{
		$q3 = mysql_query("SELECT * FROM auth WHERE user = '$stats2[0]'") or die(mysql_error());
		$agent2 = mysql_fetch_assoc($q3);
		echo "<tr>";
		echo "<td align='left'>" . $agent2["first"] . " " . $agent2["last"] . "</td>";
		echo "<td>" . $stats2[1] . "</td>";
		echo "</tr>";
	}
}
elseif ($method == "Overall")
{
	$q2 = mysql_query("SELECT agent, COUNT(*) FROM sales_customers WHERE status = 'Approved' AND centre = '$centre' GROUP BY agent ORDER BY COUNT(*) DESC LIMIT 10") or die(mysql_error());
	while ($stats2 = mysql_fetch_row($q2))
	{
		$q3 = mysql_query("SELECT * FROM auth WHERE user = '$stats2[0]'") or die(mysql_error());
		$agent2 = mysql_fetch_assoc($q3);
		echo "<tr>";
		echo "<td align='left'>" . $agent2["first"] . " " . $agent2["last"] . "</td>";
		echo "<td>" . $stats2[1] . "</td>";
		echo "</tr>";
	}
}
?>