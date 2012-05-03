<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th style="text-align:left;">Agent Name</th>
<th>Start Time</th>
<th>End Time</th>
<th>Hours</th>
<th>Sales</th>
<th>Bonus</th>
</tr>
</thead>
<tbody>
<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$date = $_GET["date"];
$centre = $_GET["centre"];

$q = mysql_query("SELECT * FROM auth,timesheet WHERE auth.centre = '$centre' AND timesheet.date = '$date' AND auth.user = timesheet.user ORDER BY timesheet.user ASC") or die(mysql_error());

if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td colspan='6'>Timesheet Not Entered!</td>";
	echo "</tr>";
	exit;
}

while ($data = mysql_fetch_assoc($q))
{
	$q1 = mysql_query("SELECT * FROM sales_customers WHERE agent = '$data[user]' AND status = 'Approved' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
	$sales = mysql_num_rows($q1);
	
	echo "<tr>";
	echo "<td style='text-align:left;'>" . $data["first"] . " " . $data["last"] ."</td>";
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
echo "<td><b>" . $total_hours . "</b></td>";
echo "<td><b>" . $total_sales . "</b></td>";
echo "<td><b>\$" . $total_bonus . "</b></td>";
echo "</tr>";
?>
</tbody>
</table>
</div></center>