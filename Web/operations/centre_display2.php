<?php
mysql_connect('localhost','vericon','18450be');

$centre = $_GET["centre"];
$date1 = $_GET["date1"];
$week1 = date("W", strtotime($date1));
$date2 = $_GET["date2"];
$week2 = date("W", strtotime($date2));
?>

<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/weekly_breakdown_header.png" width="200" height="25" /></td>
<td align="right" style="padding-right:10px;"><b><?php echo $centre; ?></b></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>
<input type="hidden" id="centre" value="<?php echo $centre; ?>" />

<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;" width="98%">
<thead>
<tr class="ui-widget-header ">
<th>Week Ending</th>
<th style="text-align:center;">Hours</th>
<th style="text-align:center;">Sales</th>
<th style="text-align:center;">SPH</th>
<th style="text-align:center;">Estimated CPS</th>
<th style="text-align:center;">Actual CPS</th>
<th style="text-align:center;"></th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT SUM(hours),date FROM vericon.timesheet WHERE centre = '$centre' AND WEEK(date) BETWEEN '$week1' AND '$week2' GROUP BY WEEK(date) ORDER BY date ASC") or die(mysql_error());

if (mysql_num_rows($q) == 0)
{
    echo "<tr>";
    echo "<td colspan='7' style='text-align:center;'>No Records Found</td>";
    echo "</tr>";
}
else
{
    while ($data = mysql_fetch_assoc($q))
    {
        $hours = $data["SUM(hours)"];
        $total_hours += $hours;
		
		$gross = 0;
		$cancellations = 0;
		
		$q1 = mysql_query("SELECT * FROM vericon.timesheet WHERE centre = '$centre' AND WEEK(date) = '" . date("W",strtotime($data["date"])) . "' GROUP BY user ORDER BY user ASC") or die(mysql_error());
		while ($data2 = mysql_fetch_assoc($q1))
		{
			$q2 = mysql_query("SELECT SUM(op_hours),SUM(op_bonus),AVG(rate),SUM(payg),SUM(annual),SUM(sick),SUM(cancellations) FROM vericon.timesheet_other WHERE user = '$data2[user]' AND week = '" . date("W",strtotime($data["date"])) . "'") or die(mysql_error());
			$da = mysql_fetch_row($q2);
			
			$q3 = mysql_query("SELECT rate FROM vericon.timesheet_rate WHERE user = '$data2[user]'") or die(mysql_error());
			$r = mysql_fetch_row($q3);
			
			if ($da[2] <= 0) { $rate = $r[0]; } else { $rate = $da[2]; }
			$gross += (($rate * ($da[0] + $da[4] + $da[5])) + $da[1]) * 1.09;
			$cancellations += $da[6];
		}
        
        $q2 = mysql_query("SELECT * FROM vericon.sales_customers WHERE status = 'Approved' AND centre = '$centre' AND WEEK(approved_timestamp) = '" . date("W",strtotime($data["date"])) . "'") or die(mysql_error());
        $sales = mysql_num_rows($q2);
        $total_sales += $sales;
        
		$q3 = mysql_query("SELECT m_cost FROM vericon.timesheet_mcost WHERE centre = '$centre' AND week = '" . date("W",strtotime($data["date"])) . "'") or die(mysql_error());
		$da2 = mysql_fetch_row($q3);
		
		if ($da2[0] > 0)
		{
			$gross += $da2[0];
			$total_gross += $gross;
			$net_sales = $sales - $cancellations;
			$total_net_sales += $net_sales;
			if ($net_sales > 0) { $a_cps = $gross/$net_sales; } else { $a_cps = $gross; }
			$a_cps_d = "\$" . number_format($a_cps,2);
		}
		else
		{
			$a_cps_d = "-";
		}
		
        $sph = $sales / $hours;
        $cps = ($hours*27) / ($sales*0.62);
        $we = date("Y-m-d", strtotime(date("Y",strtotime($data["date"])) . "W" . date("W",strtotime($data["date"])) . "7"));

        echo "<tr>";
        echo "<td>W.E. " . date("d/m/Y", strtotime($we)) . "</td>";
        echo "<td style='text-align:center;'>" . number_format($hours,2) . "</td>";
        echo "<td style='text-align:center;'>" . number_format($sales) . "</td>";
        echo "<td style='text-align:center;'>" . number_format($sph,2) . "</td>";
        echo "<td style='text-align:center;'>\$" . number_format($cps,2) . "</td>";
		echo "<td style='text-align:center;'>" . $a_cps_d . "</td>";
		echo "<td style='text-align:center;'><button onclick='View(\"$centre\",\"$we\")' class='icon_view' alt='View'></button></td>";
        echo "</tr>";
    }
    
    $total_sph = $total_sales / $total_hours;
    $total_cps = ($total_hours*27) / ($total_sales*0.62);
	
	if ($total_net_sales > 0) { $total_a_cps = $total_gross/$total_net_sales; } else { $total_a_cps = $total_gross; }
	$total_a_cps_d = "\$" . number_format($total_a_cps,2);
    
    echo "<tr>";
    echo "<td><b>Total</b></td>";
    echo "<td style='text-align:center;'><b>" . number_format($total_hours,2) . "</b></td>";
    echo "<td style='text-align:center;'><b>" . number_format($total_sales) . "</b></td>";
    echo "<td style='text-align:center;'><b>" . number_format($total_sph,2) . "</b></td>";
    echo "<td style='text-align:center;'><b>\$" . number_format($total_cps,2) . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_a_cps_d . "</b></td>";
	echo "<td></td>";
    echo "</tr>";
}
?>
</tbody>
</table>
</div></center><br>