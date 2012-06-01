<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$method = $_GET["method"];
$user = $_GET["user"];
$date1 = $_GET["date1"];
if ($date1 == "undefined") { $date1 = "2012-03-01"; }
$week1 = date("W", strtotime($date1));
$year1 = date("Y", strtotime($date1));
$date2 = $_GET["date2"];
if ($date2 == "undefined") { $date2 = date("Y-m-d"); }
$week2 = date("W", strtotime($date2));
$year2 = date("Y", strtotime($date2));

if ($method == "details")
{
	$q = mysql_query("SELECT * FROM auth WHERE user = '$user'") or die(mysql_error());
	$agent = mysql_fetch_assoc($q);
	
	$q1 = mysql_query("SELECT date FROM timesheet WHERE user = '$user' ORDER BY date ASC") or die(mysql_error());
	$start = mysql_fetch_row($q1);
	
	$q2 = mysql_query("SELECT date FROM timesheet WHERE user = '$user' ORDER BY date DESC") or die(mysql_error());
	$en = mysql_fetch_row($q2);
	
	$q3 = mysql_query("SELECT designation FROM timesheet_designation WHERE user = '$user'") or die(mysql_error());
	$desi = mysql_fetch_row($q3);
	
	if ($agent["status"] == "Enabled") { $end = "Current"; } else { $end = date("d/m/Y", strtotime($en[0])); }
?>
	<table width="100%">
    <tr>
    <td align="left"><img src="../images/agent_details_header.png" width="145" height="25" style="margin-left:3px;" /></td>
    <td align="right"><input type="button" onclick="Search()" class="search" />
    </td>
    </tr>
    <tr>
    <td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
    </tr>
    </table>
    <input type="hidden" id="user" value="<?php echo $user; ?>" />
    <center><table width="98%" style="margin-bottom:7px;">
    <tr>
    <td width="85px">Employee ID </td>
    <td><b><?php echo $agent["user"]; ?></b></td>
    </tr>
    <tr>
    <td>Agent Name </td>
    <td><b><?php echo $agent["first"] . " " . $agent["last"] . " (" . $agent["alias"] . ")"; ?></b></td>
    </tr>
    <tr>
    <td>Designation </td>
    <td><b><?php echo $desi[0]; ?></b></td>
    </tr>
    <tr>
    <td>Centre </td>
    <td><b><?php echo $agent["centre"]; ?></b></td>
    </tr>
    <tr>
    <td>Employement </td>
    <td><b><?php echo date("d/m/Y", strtotime($start[0])) . " - " . $end; ?></b></td>
    </tr>
    </table></center>
<?php
}
elseif ($method == "week")
{
?>
    <center><div id="users-contain" class="ui-widget">
    <table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;" width="98%">
    <thead>
    <tr class="ui-widget-header ">
    <th>Week Ending</th>
    <th style="text-align:center;">Hours</th>
    <th style="text-align:center;">Bonus</th>
    <th style="text-align:center;">Sales</th>
    <th style="text-align:center;">SPH</th>
    <th style="text-align:center;">Estimated CPS</th>
    </tr>
    </thead>
    <tbody>
<?php
$q = mysql_query("SELECT SUM(hours),SUM(bonus),date FROM timesheet WHERE user = '$user' AND WEEK(date) BETWEEN '$week1' AND '$week2' GROUP BY WEEK(date) ORDER BY date ASC") or die(mysql_error());

while ($data = mysql_fetch_assoc($q))
{
	$week = date("W", strtotime($data["date"]));
	$year = date("Y", strtotime($data["date"]));
	$we = date("Y-m-d", strtotime($year . "W" . $week . "7"));
	
	$hours = $data["SUM(hours)"];
	$total_hours += $hours;
	
	$bonus = $data["SUM(bonus)"];
	$total_bonus += $bonus;
	
	$q1 = mysql_query("SELECT * FROM sales_customers WHERE agent = '$user' AND WEEK(approved_timestamp) = '$week' AND status = 'Approved'") or die(mysql_error());
	$sales = mysql_num_rows($q1);
	$total_sales += $sales;
	
	$q2 = mysql_query("SELECT rate FROM timesheet_other WHERE user = '$user' AND week = '$week'") or die(mysql_error());
	if (mysql_num_rows($q2) == 0)
	{
		$q3 = mysql_query("SELECT rate FROM timesheet_rate WHERE user = '$user'") or die(mysql_error());
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
	
	echo "<tr>";
	echo "<td><a onclick='View(\"$we\")' style='text-decoration:underline; cursor:pointer;'>W.E. " . date("d/m/Y", strtotime($we)) . "</a></td>";
	echo "<td style='text-align:center;'>" . number_format($hours,2)  . "</td>";
	echo "<td style='text-align:center;'>\$" . number_format($bonus,2)  . "</td>";
	echo "<td style='text-align:center;'>" . $sales . "</td>";
	echo "<td style='text-align:center;'>" . number_format($sph,2) . "</td>";
	echo "<td style='text-align:center;'>\$" . number_format($cps,2) . "</td>";
	echo "</tr>";
}

$q1 = mysql_query("SELECT rate FROM timesheet_rate WHERE user = '$user'") or die(mysql_error());
if (mysql_num_rows($q1) == 0)
{
	$rate = 16.57;
}
else
{
	$da = mysql_fetch_row($q1);
	$rate = $da[0];
}

$total_sph = $total_sales / $total_hours;
$total_gross = (($rate * $total_hours) + $total_bonus) * 1.09;
if ($total_sales > 0) { $total_cps = $total_gross / $total_sales; } else { $cps = $total_gross; }

echo "<tr>";
echo "<td><b>Total</b></td>";
echo "<td style='text-align:center;'><b>" . number_format($total_hours,2)  . "</b></td>";
echo "<td style='text-align:center;'><b>\$" . number_format($total_bonus,2)  . "</b></td>";
echo "<td style='text-align:center;'><b>" . $total_sales . "</b></td>";
echo "<td style='text-align:center;'><b>" . number_format($total_sph,2) . "</b></td>";
echo "<td style='text-align:center;'><b>\$" . number_format($total_cps,2) . "</b></td>";
echo "</tr>";
?>
</tbody>
</table>
</div></center>
<input type="button" onclick="Export()" class="export" />
<?php
}
elseif ($method == "daily")
{
	$we = $_GET["we"];
	
	for ($day=1; $day <= 7; $day++)
	{
		$date = date('Y-m-d', strtotime(date("Y", strtotime($we)) . "W" . date("W", strtotime($we)) . $day));
		$week = date("W", strtotime($date));	
		
		$q = mysql_query("SELECT start,end,hours,bonus FROM timesheet WHERE user = '$user' AND DATE(date) = '$date'") or die(mysql_error());
		$data = mysql_fetch_row($q);
		$start = $data[0];
		$end = $data[1];
		$hours = $data[2];
		$bonus = $data[3];
		
		$q1 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND agent = '$user' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
		$sales = mysql_num_rows($q1);
		
		$q2 = mysql_query("SELECT rate FROM timesheet_other WHERE user = '$user' AND week = '$week'") or die(mysql_error());
		if (mysql_num_rows($q2) == 0)
		{
			$q3 = mysql_query("SELECT rate FROM timesheet_rate WHERE user = '$user'") or die(mysql_error());
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
}
?>