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
<th style="text-align:left">Agent ID</th>
<th style="text-align:left">Full Name</th>
<th>Hours</th>
<th>Bonus</th>
<th>Rate</th>
<th>Gross Pay</th>
<th>PAYG</th>
<th>Net Pay</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT * FROM timesheet WHERE centre = '$centre' AND date BETWEEN '$date1' AND '$date2' GROUP BY user ORDER BY user ASC") or die(mysql_error());

if ($centre == "Centre")
{
	echo "<tr><td colspan='8'>Please Select a Centre From Above</td></tr>";
}
elseif (mysql_num_rows($q) == 0)
{
	echo "<tr><td colspan='8'>No Records Found!</td></tr>";
}
else
{
	while ($data = mysql_fetch_assoc($q))
	{
		$q0 = mysql_query("SELECT first,last FROM auth WHERE user = '$data[user]'") or die(mysql_error());
		$user = mysql_fetch_row($q0);
		
		$q1 = mysql_query("SELECT SUM(op_hours),SUM(op_bonus),AVG(rate),SUM(payg) FROM timesheet_other WHERE user = '$data[user]' AND week BETWEEN '$week1' AND '$week2'") or die(mysql_error());
		$da = mysql_fetch_row($q1);
		
		$q2 = mysql_query("SELECT rate FROM timesheet_rate WHERE user = '$data[user]'") or die(mysql_error());
		$r = mysql_fetch_row($q2);
		
		$hours_d = number_format($da[0],2);
		$bonus_d = "\$" . number_format($da[1],2);
		if ($da[2] <= 0) { $rate = $r[0]; } else { $rate = $da[2]; }
		$rate_d = "\$" . number_format($rate,2);
		$gross = ($rate * $da[0]) + $da[1];
		$gross_d = "\$" . number_format($gross,2);
		$payg = $da[3];
		$payg_d = "\$" . number_format($payg,2);
		$net = $gross - $payg;
		$net_d = "\$" . number_format($net,2);

		if ($da[0] == "" || $da[0] == 0)
		{
			$hours_d = "-";
			$bonus_d = "-";
			$rate_d = "-";
			$gross_d = "-";	
			$payg_d = "-";
			$net_d = "-";
		}
		elseif ($rate_d == "$0.00")
		{
			$rate_d = "-";
			$gross_d = "-";	
			$payg_d = "-";
			$net_d = "-";
		}
		elseif ($da[2] <= 0)
		{
			$payg_d = "-";
			$net_d = "-";
		}
		
		echo "<tr>";
		echo "<td style='text-align:left;'>" . $data["user"] . "</td>";
		echo "<td style='text-align:left;'>" . $user[0] . " " . $user[1] . "</td>";
		echo "<td>" . $hours_d . "</td>";
		echo "<td>" . $bonus_d . "</td>";
		echo "<td>" . $rate_d . "</td>";
		echo "<td>" . $gross_d . "</td>";
		echo "<td>" . $payg_d . "</td>";
		echo "<td>" . $net_d . "</td>";
		echo "</tr>";
	}
	
	$q1 = mysql_query("SELECT m_cost FROM timesheet_mcost WHERE centre = '$centre' AND week = '$week'") or die(mysql_error());
	$da = mysql_fetch_row($q1);
	
	if ($da[0] == "")
	{
		$m_cost = "-";
	}
	else
	{
		$m_cost = "\$" . number_format($da[0],2);
	}
	
	echo "<tr>";
	echo "<td colspan='7' style='text-align:right;'><b>Management Cost</b></td>";
	echo "<td><b>" . $m_cost . "</b></td>";
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
<td align="left"><input type="button" onclick="Export()" class="export" /></td>
<td align="right"><input type="button" onclick="Edit_View()" class="edit" /></td>
</tr>
</table></center>
<?php
}
?>