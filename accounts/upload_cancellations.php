<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$centre = $_GET["centre"];
$date = $_GET["date"];
$week = date("W", strtotime($date));
?>

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
$q = mysql_query("SELECT user FROM timesheet WHERE WEEK(date) = '$week' AND centre = '$centre' GROUP BY user ORDER BY user ASC") or die(mysql_error());

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
		$q0 = mysql_query("SELECT first,last FROM auth WHERE user = '$data[0]'") or die(mysql_error());
		$user = mysql_fetch_row($q0);
		
		$q1 = mysql_query("SELECT cancellations FROM timesheet_other WHERE user = '$data[0]' AND week = '$week'") or die(mysql_error());
		$c = mysql_fetch_row($q1);
		
		if ($c[0] == "") { $cancellations = "-"; } else { $cancellations = $c[0]; }
		
		echo "<tr>";
		echo "<td>" . $data[0] . "</td>";
		echo "<td>" . $user[0] . " " . $user[1] . "</td>";
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