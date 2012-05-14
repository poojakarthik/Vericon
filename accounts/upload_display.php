<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$centre = $_GET["centre"];
$date = $_GET["date"];
$week = date("W", strtotime($date));
?>

<center><table width="100%">
<tr>
<td width="50%" valign="top">
<table width="100%">
<tr>
<td><img src="../images/dialler_hours_header.png" width="105" height="15" style="margin-left:3px;" /></td>
</tr>
<tr>
<td><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
<tr>
<td>
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th>Username</th>
<th>Full Name</th>
<th style="text-align:center;">Dialler Hours</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT timesheet.user,timesheet.dialler_hours,auth.first,auth.last FROM timesheet,auth WHERE timesheet.date = '$date' AND auth.centre = '$centre' AND timesheet.user = auth.user ORDER BY timesheet.user ASC") or die(mysql_error());

if ($centre == "Centre")
{
	echo "<tr><td colspan='3' style='text-align:center;'>Please Select a Centre From Above</td></tr>";
}
elseif (mysql_num_rows($q) == 0)
{
	echo "<tr><td colspan='3' style='text-align:center;'>No Records Found!</td></tr>";
}
else
{
	while ($data = mysql_fetch_row($q))
	{
		if ($data[1] == "0.00") { $hours = "-"; } else { $hours = $data[1]; }
		
		echo "<tr>";
		echo "<td>" . $data[0] . "</td>";
		echo "<td>" . $data[2] . " " . $data[3] . "</td>";
		echo "<td style='text-align:center;'>" . $hours . "</td>";
		echo "</tr>";
	}
}
?>
</tbody>
</table>
</div></center><br>
<?php
if (mysql_num_rows($q) != 0)
{
?>
<input type="button" onClick="Export_Hours()" class="export">
<input type="button" onClick="Import_Hours()" class="import">
<?php
}
?>
</td>
</tr>
</table>
</td>
<td width="50%" valign="top">
<table width="100%">
<tr>
<td><img src="../images/sale_cancellations_header.png" width="140" height="15" style="margin-left:3px;" /></td>
</tr>
<tr>
<td><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
<tr>
<td>
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th>Username</th>
<th>Full Name</th>
<th style="text-align:center;">Cancellations</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT timesheet.user,auth.first,auth.last FROM timesheet,auth WHERE WEEK(timesheet.date) = '$week' AND auth.centre = '$centre' AND timesheet.user = auth.user GROUP BY timesheet.user ORDER BY timesheet.user ASC") or die(mysql_error());

if ($centre == "Centre")
{
	echo "<tr><td colspan='3' style='text-align:center;'>Please Select a Centre From Above</td></tr>";
}
elseif (mysql_num_rows($q) == 0)
{
	echo "<tr><td colspan='3' style='text-align:center;'>No Records Found!</td></tr>";
}
else
{
	while ($data = mysql_fetch_row($q))
	{
		$q1 = mysql_query("SELECT cancellations FROM timesheet_canc WHERE user = '$data[0]' AND week = '$week'") or die(mysql_error());
		$c = mysql_fetch_row($q1);
		
		if ($c[0] == "") { $cancellations = "-"; } else { $cancellations = $c[0]; }
		
		echo "<tr>";
		echo "<td>" . $data[0] . "</td>";
		echo "<td>" . $data[1] . " " . $data[2] . "</td>";
		echo "<td style='text-align:center;'>" . $cancellations . "</td>";
		echo "</tr>";
	}
}
?>
</tbody>
</table>
</div></center><br>
<?php
if (mysql_num_rows($q) != 0)
{
?>
<input type="button" onClick="Export_Cancellations()" class="export">
<input type="button" onClick="Import_Cancellations()" class="import">
<?php
}
?>
</td>
</tr>
</table>
</td>
</tr>
</table></center>