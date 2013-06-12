<?php
mysql_connect('localhost','vericon','18450be');

$user = $_GET["user"];
if ($_GET["date1"] == "")
{
	$q = mysql_query("SELECT MIN(date),MAX(date) FROM vericon.timesheet WHERE user = '$user'") or die(mysql_error());
	$d = mysql_fetch_row($q);
	
	$date1 = $d[0];
	$date2 = $d[1];
}
else
{
	$date1 = $_GET["date1"];
	$date2 = $_GET["date2"];
}
$week1 = date("W", strtotime($date1));
$week2 = date("W", strtotime($date2));
?>

<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;" width="98%">
<thead>
<tr class="ui-widget-header ">
<th>Week Ending</th>
<th style="text-align:center;">Hours</th>
<th style="text-align:center;">Bonus</th>
<th style="text-align:center;">Net Sales</th>
<th style="text-align:center;">SPH</th>
<th style="text-align:center;">CPS</th>
<th style="text-align:center;"></th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT SUM(hours),SUM(bonus),date FROM vericon.timesheet WHERE user = '$user' AND WEEK(date) BETWEEN '$week1' AND '$week2' GROUP BY WEEK(date) ORDER BY date ASC") or die(mysql_error());

while ($data = mysql_fetch_assoc($q))
{
	$week = date("W", strtotime($data["date"]));
	$year = date("Y", strtotime($data["date"]));
	$we = date("Y-m-d", strtotime($year . "W" . $week . "7"));
	
	$q1 = mysql_query("SELECT * FROM vericon.sales_customers WHERE agent = '$user' AND WEEK(approved_timestamp) = '$week' AND status = 'Approved'") or die(mysql_error());
	$o_sales = mysql_num_rows($q1);
	$o_total_sales += $o_sales;
	
	$q2 = mysql_query("SELECT op_hours,op_bonus,cancellations,rate FROM vericon.timesheet_other WHERE user = '$user' AND week = '$week'") or die(mysql_error());
	$da = mysql_fetch_row($q2);
	
	if (mysql_num_rows($q2) != 0 && $da[3] != "0.00")
	{
		$a_hours = $da[0];
		$a_total_hours += $a_hours;
		$a_bonus = $da[1];
		$a_total_bonus += $a_bonus;
		$cancellations = $da[2];
		$rate = $da[3];
		$a_sales = $o_sales - $cancellations;
		$a_total_sales += $a_sales;
		$a_sph = $a_sales / $a_hours;
		$a_gross = (($rate * $a_hours) + $a_bonus) * 1.09;
		if ($a_sales > 0) { $a_cps = $a_gross / $a_sales; } else { $a_cps = $a_gross; }
		
		$a_hours_d = number_format($a_hours,2);
		$a_bonus_d = "\$" . number_format($a_bonus,2);
		$a_sales_d = number_format($a_sales);
		$a_sph_d = number_format($a_sph,2);
		$a_cps_d = "\$" . number_format($a_cps,2);
	}
	else
	{
		$a_hours_d = "-";
		$a_bonus_d = "-";
		$a_sales_d = "-";
		$a_sph_d = "-";
		$a_cps_d = "-";
		
		$q3 = mysql_query("SELECT rate FROM vericon.timesheet_rate WHERE user = '$user'") or die(mysql_error());
		if (mysql_num_rows($q3) == 0)
		{
			$rate = 16.57;
		}
		else
		{
			$da2 = mysql_fetch_row($q3);
			$rate = $da2[0];
		}
	}
	
	echo "<tr>";
	echo "<td>W.E. " . date("d/m/Y", strtotime($we)) . "</td>";
	echo "<td style='text-align:center;'>" . $a_hours_d  . "</td>";
	echo "<td style='text-align:center;'>" . $a_bonus_d  . "</td>";
	echo "<td style='text-align:center;'>" . $a_sales_d . "</td>";
	echo "<td style='text-align:center;'>" . $a_sph_d . "</td>";
	echo "<td style='text-align:center;'>" . $a_cps_d . "</td>";
	echo "<td style='text-align:center;'><button onclick='View(\"$we\")' class='icon_view' title='View'></button></td>";
	echo "</tr>";
}

$q1 = mysql_query("SELECT rate FROM vericon.timesheet_rate WHERE user = '$user'") or die(mysql_error());
if (mysql_num_rows($q1) == 0)
{
	$rate = 16.57;
}
else
{
	$da = mysql_fetch_row($q1);
	$rate = $da[0];
}

if ($a_total_hours > 0)
{
	$a_total_sph = $a_total_sales / $a_total_hours;
	$a_total_gross = (($rate * $a_total_hours) + $a_total_bonus) * 1.09;
	if ($a_total_sales > 0) { $a_total_cps = $a_total_gross / $a_total_sales; } else { $a_total_cps = $a_total_gross; }
	
	$a_total_hours_d = number_format($a_total_hours,2);
	$a_total_bonus_d = "\$" . number_format($a_total_bonus,2);
	$a_total_sales_d = number_format($a_total_sales);
	$a_total_sph_d = number_format($a_total_sph,2);
	$a_total_cps_d = "\$" . number_format($a_total_cps,2);
}
else
{
	$a_total_hours_d = "-";
	$a_total_bonus_d = "-";
	$a_total_sales_d = "-";
	$a_total_sph_d = "-";
	$a_total_cps_d = "-";
}

echo "<tr>";
echo "<td><b>Total</b></td>";
echo "<td style='text-align:center;'><b>" . $a_total_hours_d  . "</b></td>";
echo "<td style='text-align:center;'><b>" . $a_total_bonus_d  . "</b></td>";
echo "<td style='text-align:center;'><b>" . $a_total_sales_d . "</b></td>";
echo "<td style='text-align:center;'><b>" . $a_total_sph_d . "</b></td>";
echo "<td style='text-align:center;'><b>" . $a_total_cps_d . "</b></td>";
echo "<td></td>";
echo "</tr>";
?>
</tbody>
</table>
</div></center>
<br>