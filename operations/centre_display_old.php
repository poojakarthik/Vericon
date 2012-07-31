<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$method = $_GET["method"];
$centre = $_GET["centre"];
$date1 = $_GET["date1"];
$week1 = date("W", strtotime($date1));
$year1 = date("Y", strtotime($date1));
$date2 = $_GET["date2"];
$week2 = date("W", strtotime($date2));
$year2 = date("Y", strtotime($date2));

if ($method == "overall")
{
?>
    <center><div id="users-contain" class="ui-widget">
    <table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;">
    <?php //self
	$centres = explode("_",$centre);
    for ($i = 0; $i < count($centres); $i++)
    {
        $q0 = mysql_query("SELECT centre FROM centres WHERE centre = '$centres[$i]' AND type = 'Self'") or die(mysql_error());
        if (mysql_num_rows($q0) != 0)
        {
            $self[$centres[$i]] = 1;
        }
    }
    
    if (array_sum($self) > 0)
    {
        echo '<thead>';
        echo '<tr class="ui-widget-header ">';
        echo '<th colspan="6" style="text-align:center;">Melbourne</th>';
        echo '</tr>';
        echo '<tr class="ui-widget-header ">';
        echo '<th>Centre</th>';
        echo '<th style="text-align:center;">Total Hours</th>';
        echo '<th style="text-align:center;">Total Sales</th>';
        echo '<th style="text-align:center;">Average SPH</th>';
		echo '<th style="text-align:center;">Average SPA</th>';
        echo '<th style="text-align:center;">Estimated CPS</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
    }
    
    for ($i = 0; $i < count($centres); $i++)
    {
        if ($self[$centres[$i]] == 1)
        {
            $q = mysql_query("SELECT SUM(hours) FROM timesheet WHERE centre = '$centres[$i]' AND DATE(date) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
			$hours = mysql_fetch_row($q);
            $total_hours += $hours[0];
			
			$q1 = mysql_query("SELECT * FROM timesheet WHERE centre = '$centres[$i]' AND DATE(date) BETWEEN '$date1' AND '$date2' GROUP BY user") or die(mysql_error());
			$agents = mysql_num_rows($q1);
			$total_agents += $agents;
            
            $q2 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND centre = '$centres[$i]' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
            $sales = mysql_num_rows($q2);
            $total_sales += $sales;
            
            $sph = $sales / $hours[0];
			$spa = $sales / $agents;
			$cps = ($hours[0]*27) / ($sales*0.62);
    
            echo "<tr>";
            echo "<td><a onclick='Display(\"$centres[$i]\")' style='text-decoration:underline; cursor:pointer;'>" . $centres[$i] . "</a></td>";
            echo "<td style='text-align:center;'>" . number_format($hours[0],2) . "</td>";
            echo "<td style='text-align:center;'>" . $sales . "</td>";
            echo "<td style='text-align:center;'>" . number_format($sph,2) . "</td>";
            echo "<td style='text-align:center;'>" . number_format($spa,2) . "</td>";
            echo "<td style='text-align:center;'>\$" . number_format($cps,2) . "</td>";
            echo "</tr>";
        }
    }
    
    if (array_sum($self) > 0)
    {
        $total_sph = $total_sales / $total_hours;
		$total_spa = $total_sales / $total_agents;
		$total_cps = ($total_hours*27) / ($total_sales*0.62);
        
        echo "<tr>";
        echo "<td><b>Total</b></td>";
        echo "<td style='text-align:center;'><b>" . number_format($total_hours,2) . "</b></td>";
        echo "<td style='text-align:center;'><b>" . $total_sales . "</b></td>";
        echo "<td style='text-align:center;'><b>" . number_format($total_sph,2) . "</b></td>";
        echo "<td style='text-align:center;'><b>" . number_format($total_spa,2) . "</b></td>";
        echo "<td style='text-align:center;'><b>\$" . number_format($total_cps,2) . "</b></td>";
        echo "</tr>";
        echo "</tbody>";
    }
    ?>
    </table>
    </div></center>
<?php
}
elseif ($method == "week")
{
?>
	<table width="100%">
	<tr>
	<td align="left"><img src="../images/weekly_breakdown_header.png" width="200" height="25" style="margin-left:3px;" /></td>
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
    <th width="20%">Week Ending</th>
    <th width="16%" style="text-align:center;">Hours</th>
    <th width="16%" style="text-align:center;">Sales</th>
    <th width="16%" style="text-align:center;">SPH</th>
    <th width="16%" style="text-align:center;">SPA</th>
    <th width="16%" style="text-align:center;">Estimated CPS</th>
    </tr>
    </thead>
    <tbody>
<?php
	$q = mysql_query("SELECT SUM(hours),date FROM timesheet WHERE centre = '$centre' AND WEEK(date) BETWEEN '$week1' AND '$week2' GROUP BY WEEK(date) ORDER BY date ASC") or die(mysql_error());
	
	if ($centre == "")
	{
		echo "<tr>";
		echo "<td colspan='6' style='text-align:center;'>Please Select a Centre From Above</td>";
		echo "</tr>";
	}
	elseif (mysql_num_rows($q) == 0)
	{
		echo "<tr>";
		echo "<td colspan='6' style='text-align:center;'>No Records Found</td>";
		echo "</tr>";
	}
	else
	{
		while ($data = mysql_fetch_assoc($q))
		{
			$hours = $data["SUM(hours)"];
			$total_hours += $hours;
			
			$q1 = mysql_query("SELECT * FROM timesheet WHERE centre = '$centre' AND WEEK(date) = '" . date("W",strtotime($data["date"])) . "' GROUP BY user") or die(mysql_error());
			$agents = mysql_num_rows($q1);
			
			$q2 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND centre = '$centre' AND WEEK(approved_timestamp) = '" . date("W",strtotime($data["date"])) . "'") or die(mysql_error());
			$sales = mysql_num_rows($q2);
			$total_sales += $sales;
			
			$sph = $sales / $hours;
			$spa = $sales / $agents;
			$cps = ($hours*27) / ($sales*0.62);
			$we = date("Y-m-d", strtotime(date("Y",strtotime($data["date"])) . "W" . date("W",strtotime($data["date"])) . "7"));
	
			echo "<tr>";
			echo "<td><a onclick='View(\"$we\")' style='text-decoration:underline; cursor:pointer;'>W.E. " . date("d/m/Y", strtotime($we)) . "</a></td>";
			echo "<td style='text-align:center;'>" . number_format($hours,2) . "</td>";
			echo "<td style='text-align:center;'>" . $sales . "</td>";
			echo "<td style='text-align:center;'>" . number_format($sph,2) . "</td>";
			echo "<td style='text-align:center;'>" . number_format($spa,2) . "</td>";
			echo "<td style='text-align:center;'>\$" . number_format($cps,2) . "</td>";
			echo "</tr>";
		}
		
		$q1 = mysql_query("SELECT * FROM timesheet WHERE centre = '$centre' AND WEEK(date) BETWEEN '$week1' AND '$week2' GROUP BY user") or die(mysql_error());
		$total_agents = mysql_num_rows($q1);
		
		$total_sph = $total_sales / $total_hours;
		$total_spa = $total_sales / $total_agents;
		$total_cps = ($total_hours*27) / ($total_sales*0.62);
		
		echo "<tr>";
        echo "<td><b>Total</b></td>";
        echo "<td style='text-align:center;'><b>" . number_format($total_hours,2) . "</b></td>";
        echo "<td style='text-align:center;'><b>" . $total_sales . "</b></td>";
        echo "<td style='text-align:center;'><b>" . number_format($total_sph,2) . "</b></td>";
        echo "<td style='text-align:center;'><b>" . number_format($total_spa,2) . "</b></td>";
        echo "<td style='text-align:center;'><b>\$" . number_format($total_cps,2) . "</b></td>";
        echo "</tr>";
	}
?>
	</tbody>
    </table>
    </div></center><br>
<?php
}
elseif ($method == "daily")
{
	$we = $_GET["we"];
	
	for ($day=1; $day <= 7; $day++)
	{
		$date = date('Y-m-d', strtotime(date("Y", strtotime($we)) . "W" . date("W", strtotime($we)) . $day));	
		
		$q = mysql_query("SELECT SUM(hours),SUM(bonus) FROM timesheet WHERE centre = '$centre' AND DATE(date) = '$date'") or die(mysql_error());
		$data = mysql_fetch_row($q);
		$hours = $data[0];
		$bonus = $data[1];
		
		$q1 = mysql_query("SELECT * FROM timesheet WHERE centre = '$centre' AND DATE(date) = '$date' GROUP BY user") or die(mysql_error());
		$agents = mysql_num_rows($q1);
		
		$q2 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND centre = '$centre' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
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
		$export_d = "<input type='button' onclick='Daily_Export(\"$date\")' class='export2' title='Export'>";
		
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
}
?>