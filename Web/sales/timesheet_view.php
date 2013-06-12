<?php
mysql_connect('localhost','vericon','18450be');

$date = $_GET["date"];
$centre = $_GET["centre"];
$nr = 0;
?>

<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th style="text-align:left;">Agent Name</th>
<th style="text-align:left;">Designation</th>
<th>Start Time</th>
<th>End Time</th>
<th>Hours</th>
<th>Sales</th>
<th>Bonus</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT * FROM vericon.timesheet WHERE centre = '$centre' AND date = '$date' ORDER BY user ASC") or die(mysql_error());

if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td colspan='7'>Timesheet Not Entered!</td>";
	echo "</tr>";
	$nr = 1;
}
else
{
	while ($data = mysql_fetch_assoc($q))
	{
		$q0 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$data[user]'") or die(mysql_error());
		$user = mysql_fetch_row($q0);
		
		$q1 = mysql_query("SELECT * FROM vericon.sales_customers WHERE agent = '$data[user]' AND status = 'Approved' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
		$sales = mysql_num_rows($q1);
		
		echo "<tr>";
		echo "<td style='text-align:left;'>" . $user[0] . " " . $user[1] ."</td>";
		echo "<td style='text-align:left;'>" . $data["designation"] ."</td>";
		echo "<td>" . date("H:i", strtotime($data["start"])) . "</td>";
		echo "<td>" . date("H:i", strtotime($data["end"])) . "</td>";
		echo "<td>" . $data["hours"] . "</td>";
		echo "<td>" . $sales . "</td>";
		echo "<td>\$" . $data["bonus"] . "</td>";
		echo "</tr>";
		
		$total_hours += $data["hours"];
		$total_sales += $sales;
		$total_bonus += $data["bonus"];
	}
	echo "<tr>";
	echo "<td style='text-align:left;'><b>Total</b></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td><b>" . $total_hours . "</b></td>";
	echo "<td><b>" . $total_sales . "</b></td>";
	echo "<td><b>\$" . $total_bonus . "</b></td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div></center>

<table width="100%">
<tr>
<?php
if ($nr == 0)
{
?>
<td align="left" style="padding-left:5px;"><button onClick="Export()" class="btn">Export</button></td>
<?php
}
if (date("W") == date("W", strtotime($date)))
{
?>
<td align="right" style="padding-right:5px;"><button onClick="View_Edit()" class="btn">Edit</button></td>
<?php
}
?>
</tr>
</table>