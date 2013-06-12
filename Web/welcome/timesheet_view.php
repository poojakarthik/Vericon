<?php
mysql_connect('localhost','vericon','18450be');

$date = $_GET["date"];
$nr = 0;
?>

<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th style="text-align:left;">Agent Name</th>
<th>Start Time</th>
<th>End Time</th>
<th>Hours</th>
<th>Approved</th>
<th>Cancelled</th>
<th>Upgrades</th>
<th>DD</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT * FROM vericon.timesheet WHERE centre = 'Welcome' AND date = '$date' ORDER BY user ASC") or die(mysql_error());

if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td colspan='8'>Timesheet Not Entered!</td>";
	echo "</tr>";
	$nr = 1;
}
else
{
	while ($data = mysql_fetch_assoc($q))
	{
		$approved = 0;
		$cancelled = 0;
		$upgrade = 0;
		
		$q0 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$data[user]'") or die(mysql_error());
		$user = mysql_fetch_row($q0);
		
		$q1 = mysql_query("SELECT status, COUNT(id) FROM vericon.welcome WHERE user = '$data[user]' AND DATE(timestamp) = '$date' GROUP BY status") or die(mysql_error());
		while($data2 = mysql_fetch_row($q1))
		{		
			if ($data2[0] == "Approve") { $approved = $data2[1]; }
			elseif ($data2[0] == "Cancel") { $cancelled = $data2[1]; }
			elseif ($data2[0] == "Upgrade") { $upgrade = $data2[1]; }
		}
		
		$q2 = mysql_query("SELECT COUNT(id) FROM vericon.welcome WHERE user = '$data[user]' AND DATE(timestamp) = '$date' AND dd = '1'") or die(mysql_error());
		$dd = mysql_fetch_row($q2);
		
		echo "<tr>";
		echo "<td style='text-align:left;'>" . $user[0] . " " . $user[1] ."</td>";
		echo "<td>" . date("H:i", strtotime($data["start"])) . "</td>";
		echo "<td>" . date("H:i", strtotime($data["end"])) . "</td>";
		echo "<td>" . $data["hours"] . "</td>";
		echo "<td>" . $approved . "</td>";
		echo "<td>" . $cancelled . "</td>";
		echo "<td>" . $upgrade . "</td>";
		echo "<td>" . $dd[0] . "</td>";
		echo "</tr>";
		
		$total_hours += $data["hours"];
		$total_approved += $approved;
		$total_cancelled += $cancelled;
		$total_upgrades += $upgrade;
		$total_dd += $dd[0];
	}
	echo "<tr>";
	echo "<td style='text-align:left;'><b>Total</b></td>";
	echo "<td></td>";
	echo "<td></td>";
	echo "<td><b>" . $total_hours . "</b></td>";
	echo "<td><b>" . $total_approved . "</b></td>";
	echo "<td><b>" . $total_cancelled . "</b></td>";
	echo "<td><b>" . $total_upgrades . "</b></td>";
	echo "<td><b>" . $total_dd . "</b></td>";
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