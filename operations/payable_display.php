<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$centre = $_GET["centre"];
$date = $_GET["date"];
$week = date("W", strtotime($date));
$year = date("Y", strtotime($date));
$date1 = date("Y-m-d", strtotime($year . "W" . $week . "1"));
$week1 = date("W", strtotime($date1));
$date2 = date("Y-m-d", strtotime($year . "W" . $week . "7"));
$week2 = date("W", strtotime($date2));
?>

<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header">
<th colspan="2">Details</th>
<th colspan="3">Timesheet</th>
<th colspan="4">Operations</th>
</tr>
<tr class="ui-widget-header">
<th style="text-align:left">Agent ID</th>
<th style="text-align:left">Full Name</th>
<th>Hours</th>
<th>Bonus</th>
<th>Sales</th>
<th>Hours</th>
<th>Bonus</th>
<th>Net Sales</th>
<th>CPS</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT * FROM timesheet WHERE centre = '$centre' AND date BETWEEN '$date1' AND '$date2' GROUP BY user ORDER BY user ASC") or die(mysql_error());

if ($centre == "Centre")
{
	echo "<tr><td colspan='9'>Please Select a Centre From Above</td></tr>";
}
elseif (mysql_num_rows($q) == 0)
{
	echo "<tr><td colspan='9'>No Records Found!</td></tr>";
}
else
{
	while ($data = mysql_fetch_assoc($q))
	{
		$q0 = mysql_query("SELECT first,last FROM auth WHERE user = '$data[user]'") or die(mysql_error());
		$user = mysql_fetch_row($q0);
		
		$q1 = mysql_query("SELECT SUM(hours),SUM(bonus) FROM timesheet WHERE user = '$data[user]' AND date BETWEEN '$date1' AND '$date2'") or die(mysql_error());
		$da = mysql_fetch_row($q1);
		
		$q2 = mysql_query("SELECT SUM(op_hours),SUM(op_bonus),SUM(cancellations) FROM timesheet_other WHERE user = '$data[user]' AND week BETWEEN '$week1' AND '$week2'") or die(mysql_error());
		$da2 = mysql_fetch_row($q2);
		
		$q3 = mysql_query("SELECT * FROM sales_customers WHERE agent = '$data[user]' AND status = 'Approved' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
		$da3 = mysql_num_rows($q3);
		
		$q4 = mysql_query("SELECT rate FROM timesheet_rate WHERE user = '$data[user]'") or die(mysql_error());
		$da4 = mysql_fetch_row($q4);
		
		if ($da4[0] == "") { $rate = 16.57; } else { $rate = $da4[0]; }
		
		if ($da[0] == "")
		{
			$t_hours_d = "-";
			$t_bonus_d = "-";
			$t_sales_d = "-";
		}
		else
		{
			$t_hours_d = number_format($da[0],2);
			$t_bonus_d = "\$" . number_format($da[1],2);
			$t_sales_d = $da3;
		}
		
		if ($da2[0] != "")
		{
			$n_sales_d = $da3 - $da2[2];
		}
		else
		{
			$n_sales_d = "-";
		}
		
		if (($da3 - $da2[2]) > 0)
		{
			$gross = (($rate * $da2[0]) + $da2[1]) * 1.09;
			$net = $da3 - $da2[2];
			$cps = $gross / $net;
		}
		else
		{
			$cps = (($rate * $da2[0]) + $da2[1]) * 1.09;
		}
		
		if ($da2[0] != 0)
		{
			$o_hours_d = number_format($da2[0],2);
			$o_bonus_d = "\$" . number_format($da2[1],2);
			$cps_d = "\$" . number_format($cps,2);
		}
		else
		{
			$o_hours_d = "-";
			$o_bonus_d = "-";
			$cps_d = "-";
		}
		
		$total_hours += $da2[0];
		$total_bonus += $da2[1];
		$total_net += ($da3 - $da2[2]);
		
		echo "<tr>";
		echo "<td style='text-align:left;'>" . $data["user"] . "</td>";
		echo "<td style='text-align:left;'>" . $user[0] . " " . $user[1] . "</td>";
		echo "<td>" . $t_hours_d . "</td>";
		echo "<td>" . $t_bonus_d . "</td>";
		echo "<td>" . $t_sales_d . "</td>";
		echo "<td>" . $o_hours_d . "</td>";
		echo "<td>" . $o_bonus_d . "</td>";
		echo "<td>" . $n_sales_d . "</td>";
		echo "<td>" . $cps_d . "</td>";
		echo "</tr>";
	}
	
	$q1 = mysql_query("SELECT SUM(hours),SUM(bonus) FROM timesheet WHERE centre = '$centre' AND date BETWEEN '$date1' AND '$date2'") or die(mysql_error());
	$da = mysql_fetch_row($q1);
	
	$q2 = mysql_query("SELECT COUNT(id) FROM sales_customers WHERE status = 'Approved' AND centre = '$centre' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
	$da2 = mysql_fetch_row($q2);
	
	$total_gross = ((16.57 * $total_hours) + $total_bonus) * 1.09;
	$total_cps = $total_gross / $total_net;
	
	echo "<tr>";
	echo "<td colspan='2' style='text-align:right;'><b>Total</b></td>";
	echo "<td><b>" . number_format($da[0],2) . "</b></td>";
	echo "<td><b>\$" . number_format($da[1],2) . "</b></td>";
	echo "<td><b>" . $da2[0] . "</b></td>";
	echo "<td><b>" . number_format($total_hours,2) . "</b></td>";
	echo "<td><b>\$" . number_format($total_bonus,2) . "</b></td>";
	echo "<td><b>" . $total_net . "</b></td>";
	echo "<td><b>\$" . number_format($total_cps,2) . "</b></td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div></center><br>

<?php
if ($centre != "Centre" && mysql_num_rows($q) != 0)
{
?>
<center><table width="98%">
<tr>
<td align="right"><input type="button" onclick="Edit_View()" class="edit" /></td>
</tr>
</table></center>
<?php
}
?>