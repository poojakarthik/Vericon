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
<th colspan="2">Timesheet</th>
<th>Dialler</th>
<th colspan="2">Operations</th>
</tr>
<tr class="ui-widget-header">
<th style="text-align:left">Agent ID</th>
<th style="text-align:left">Full Name</th>
<th>Hours</th>
<th>Bonus</th>
<th>Hours</th>
<th>Hours</th>
<th>Bonus</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT * FROM timesheet WHERE centre = '$centre' AND date BETWEEN '$date1' AND '$date2' GROUP BY user ORDER BY user ASC") or die(mysql_error());

if ($centre == "Centre")
{
	echo "<tr><td colspan='7'>Please Select a Centre From Above</td></tr>";
}
elseif (mysql_num_rows($q) == 0)
{
	echo "<tr><td colspan='7'>No Records Found!</td></tr>";
}
else
{
	while ($data = mysql_fetch_assoc($q))
	{
		$q0 = mysql_query("SELECT first,last FROM auth WHERE user = '$data[user]'") or die(mysql_error());
		$user = mysql_fetch_row($q0);
		
		$q1 = mysql_query("SELECT SUM(hours),SUM(bonus),SUM(dialler_hours) FROM timesheet WHERE user = '$data[user]' AND date BETWEEN '$date1' AND '$date2'") or die(mysql_error());
		$da = mysql_fetch_row($q1);
		
		$q2 = mysql_query("SELECT SUM(op_hours),SUM(op_bonus) FROM timesheet_other WHERE user = '$data[user]' AND week BETWEEN '$week1' AND '$week2'") or die(mysql_error());
		$da2 = mysql_fetch_row($q2);
		
		if ($da2[0] != "" || $da2[0] > 0)
		{
			$o_hours = number_format($da2[0],2);
			$o_bonus = number_format($da2[1],2);
		}
		else
		{
			$o_hours = "";
			$o_bonus = "";
		}
		
		if ($da[0] == "")
		{
			$t_hours_d = "-";
			$t_bonus_d = "-";
			$o_hours_d = "-";
			$o_bonus_d = "-";
		}
		else
		{
			$t_hours_d = number_format($da[0],2);
			$t_bonus_d = "\$" . number_format($da[1],2);
			$o_hours_d = "<input type='text' id='$data[user]_hours' value='$o_hours' onChange='Hours(\"$data[user]\")' style='height:15px; width:30px;'>";
			$o_bonus_d = "$<input type='text' id='$data[user]_bonus' value='$o_bonus' onChange='Bonus(\"$data[user]\")' style='height:15px; width:35px;'>";
		}
		
		if ($da[2] <= 0)
		{
			$d_hours_d = "-";
		}
		else
		{
			$d_hours_d = number_format($da[0],2);
		}
		
		echo "<tr>";
		echo "<td style='text-align:left;'>" . $data["user"] . "</td>";
		echo "<td style='text-align:left;'>" . $user[0] . " " . $user[1] . "</td>";
		echo "<td>" . $t_hours_d . "</td>";
		echo "<td>" . $t_bonus_d . "</td>";
		echo "<td>" . $d_hours_d . "</td>";
		echo "<td>" . $o_hours_d . "</td>";
		echo "<td>" . $o_bonus_d . "</td>";
		echo "</tr>";
	}
}
?>
</tbody>
</table>
</div></center><br>

<center><table width="98%">
<tr>
<td align="right"><input type="button" onclick="Done()" class="done" /></td>
</tr>
</table></center>