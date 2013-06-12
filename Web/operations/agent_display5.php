<?php
mysql_connect('localhost','vericon','18450be');

$user = $_GET["user"];
$we = $_GET["we"];
?>
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;" width="100%">
<thead>
<tr class="ui-widget-header ">
<th>Day</th>
<th>Date</th>
<th style="text-align:center;">Start Time</th>
<th style="text-align:center;">End Time</th>
<th style="text-align:center;">Hours</th>
<th style="text-align:center;">Bonus</th>
<th style="text-align:center;">Sales</th>
<th style="text-align:center;">SPH</th>
<th style="text-align:center;">Estimated CPS</th>
</tr>
</thead>
<tbody>
<?php
for ($day=1; $day <= 7; $day++)
{
	$date = date('Y-m-d', strtotime(date("Y", strtotime($we)) . "W" . date("W", strtotime($we)) . $day));
	$week = date("W", strtotime($date));	
	
	$q = mysql_query("SELECT start,end,hours,bonus FROM vericon.timesheet WHERE user = '$user' AND DATE(date) = '$date'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	$start = $data[0];
	$end = $data[1];
	$hours = $data[2];
	$bonus = $data[3];
	
	$q1 = mysql_query("SELECT * FROM vericon.sales_customers WHERE status = 'Approved' AND agent = '$user' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
	$sales = mysql_num_rows($q1);
	
	$q2 = mysql_query("SELECT rate FROM vericon.timesheet_other WHERE user = '$user' AND week = '$week'") or die(mysql_error());
	if (mysql_num_rows($q2) == 0)
	{
		$q3 = mysql_query("SELECT rate FROM vericon.timesheet_rate WHERE user = '$user'") or die(mysql_error());
		if (mysql_num_rows($q3) == 0)
		{
			$rate = 16.57;
		}
		else
		{
			$da = mysql_fetch_row($q3);
			$rate = $da[0];
		}
	}
	else
	{
		$da = mysql_fetch_row($q2);
		$rate = $da[0];
	}
	
	$sph = $sales / $hours;
	$gross = (($rate * $hours) + $bonus) * 1.09;
	if ($sales > 0) { $cps = $gross / $sales; } else { $cps = $gross; }
	
	$start_d = date("h:i A", strtotime($start));
	$end_d = date("h:i A", strtotime($end));
	$hours_d = number_format($hours,2);
	$bonus_d = "\$" . number_format($bonus,2);
	$sales_d = $sales;
	$sph_d = number_format($sph,2);
	$cps_d = "\$" . number_format($cps,2);
	
	if ($hours == 0)
	{
		$start_d = "-";
		$end_d = "-";
		$hours_d = "-";
		$bonus_d = "-";
		$sales_d = "-";
		$sph_d = "-";
		$cps_d = "-";
	}

	echo "<tr>";
	echo "<td style='padding:.3em 10px;'>" . date("l", strtotime($date)) . "</a></td>";
	echo "<td style='padding:.3em 10px;'>" . date("d/m/Y", strtotime($date)) . "</td>";
	echo "<td style='text-align:center; padding:.3em 10px;'>" . $start_d . "</td>";
	echo "<td style='text-align:center; padding:.3em 10px;'>" . $end_d . "</td>";
	echo "<td style='text-align:center; padding:.3em 10px;'>" . $hours_d . "</td>";
	echo "<td style='text-align:center; padding:.3em 10px;'>" . $bonus_d . "</td>";
	echo "<td style='text-align:center; padding:.3em 10px;'>" . $sales_d . "</td>";
	echo "<td style='text-align:center; padding:.3em 10px;'>" . $sph_d . "</td>";
	echo "<td style='text-align:center; padding:.3em 10px;'>" . $cps_d . "</td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div>