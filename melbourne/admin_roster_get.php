<?php
$centre = $_GET["centre"];
if ($_GET["date"] == "") { $date = date("Y-m-d"); } else { $date = $_GET["date"]; }

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');
?>
<div id="users-contain" class="ui-widget" style="margin-left:3px;">
<table id="users" class="ui-widget ui-widget-content" width="100%" style="margin-top:5px; font-size:10px;">
<thead>
<tr class="ui-widget-header ">
<th><p>Name</p></th>
<?php
$week_number = date("W",strtotime($date));
for ($day=1; $day <= 5; $day++)
{
	echo "<th style='text-align:center;'><p>" . date('l', strtotime(date("Y") . "W" . $week_number . $day)) . "</p>";
	echo "<p>" . date('d/m/Y', strtotime(date("Y") . "W" . $week_number . $day)) . "</p></th>";
}
?>
</tr>
</thead>
<tbody align="center">
<?php
$q = mysql_query("SELECT agent FROM roster WHERE centre = '$centre' AND WEEK(date) = '$week_number' GROUP BY agent ORDER BY agent ASC") or die(mysql_error());
while ($agent = mysql_fetch_row($q))
{
	$q1 = mysql_query("SELECT first,last FROM auth WHERE user = '$agent[0]'") or die(mysql_error());
	$name = mysql_fetch_row($q1);
	$q2 = mysql_query("SELECT * FROM roster WHERE agent = '$agent[0]' AND WEEK(date) = '$week_number' ORDER BY date ASC") or die(mysql_error());
	echo "<tr>";
	echo "<td>" . $name[0] . " " . $name[1] ."</td>";
	while ($roster = mysql_fetch_assoc($q2))
	{
		if ($roster["na"] == 1) {$start = "N/A"; $end = "N/A";} else {$start = date("h:i A", strtotime($roster["start"])); $end = date("h:i A", strtotime($roster["end"]));}
		
		echo "<td style='text-align:center;'>" . $start . " - " . $end . "</td>";
	}
	echo "</tr>";
}
?>
</tbody>
</table>
</div>