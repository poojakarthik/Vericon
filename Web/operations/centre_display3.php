<?php
mysql_connect('localhost','vericon','18450be');

$centre = $_GET["centre"];
$we = $_GET["we"];
?>

<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;" width="100%">
<thead>
<tr class="ui-widget-header ">
<th>Day</th>
<th>Date</th>
<th style="text-align:center;">Hours</th>
<th style="text-align:center;">Bonus</th>
<th style="text-align:center;">Sales</th>
<th style="text-align:center;">SPH</th>
<th style="text-align:center;">SPA</th>
<th style="text-align:center;">Estimated CPS</th>
<th style="text-align:center;">Timesheet</th>
</tr>
</thead>
<tbody>
<?php
for ($day=1; $day <= 7; $day++)
{
	$date = date('Y-m-d', strtotime(date("Y", strtotime($we)) . "W" . date("W", strtotime($we)) . $day));	
	
	$q = mysql_query("SELECT SUM(hours),SUM(bonus) FROM vericon.timesheet WHERE centre = '$centre' AND DATE(date) = '$date'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	$hours = $data[0];
	$bonus = $data[1];
	
	$q1 = mysql_query("SELECT * FROM vericon.timesheet WHERE centre = '$centre' AND DATE(date) = '$date' GROUP BY user") or die(mysql_error());
	$agents = mysql_num_rows($q1);
	
	$q2 = mysql_query("SELECT * FROM vericon.sales_customers WHERE status = 'Approved' AND centre = '$centre' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
	$sales = mysql_num_rows($q2);
	
	$sph = $sales / $hours;
	$spa = $sales / $agents;
	$cps = ($hours*27) / ($sales*0.62);
	
	$hours_d = number_format($hours,2);
	$bonus_d = "\$" . number_format($bonus,2);
	$sales_d = $sales;
	$sph_d = number_format($sph,2);
	$spa_d = number_format($spa,2);
	$cps_d = "\$" . number_format($cps,2);
	$export_d = "<button onclick='Daily_Export(\"$date\")' class='icon_excel' title='Export'></button>";
	
	if ($hours == 0)
	{
		$hours_d = "-";
		$bonus_d = "-";
		$sales_d = "-";
		$sph_d = "-";
		$spa_d = "-";
		$cps_d = "-";
		$export_d = "-";
	}

	echo "<tr>";
	echo "<td style='padding:.3em 10px;'>" . date("l", strtotime($date)) . "</a></td>";
	echo "<td style='padding:.3em 10px;'>" . date("d/m/Y", strtotime($date)) . "</td>";
	echo "<td style='text-align:center; padding:.3em 10px;'>" . $hours_d . "</td>";
	echo "<td style='text-align:center; padding:.3em 10px;'>" . $bonus_d . "</td>";
	echo "<td style='text-align:center; padding:.3em 10px;'>" . $sales_d . "</td>";
	echo "<td style='text-align:center; padding:.3em 10px;'>" .$sph_d . "</td>";
	echo "<td style='text-align:center; padding:.3em 10px;'>" . $spa_d . "</td>";
	echo "<td style='text-align:center; padding:.3em 10px;'>" . $cps_d . "</td>";
	echo "<td style='text-align:center; padding:.3em 10px;'>" . $export_d . "</td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div>