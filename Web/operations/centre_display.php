<?php
mysql_connect('localhost','vericon','18450be');

$centres = explode(",", $_GET["centres"]);
$date1 = $_GET["date1"];
$date2 = $_GET["date2"];
?>
<script>
$(function() {
	$( "#datepicker" ).datepicker( {
		showOn: "button",
		buttonImage: "../images/calendar.png",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		firstDay: 1,
		altField: "#datepicker2",
		altFormat: "dd/mm/yy",
		showOtherMonths: true,
		selectOtherMonths: true,
		changeMonth: true,
		changeYear: true,
		minDate: "<?php echo "2012-03-01"; ?>",
		maxDate: "0d",
		onSelect: function(dateText, inst) {
			var date2 = $( "#datepicker3" ),
				centres = "<?php echo $_GET["centres"]; ?>";
			
			$( "#display" ).hide('blind','','slow', function() {
				$( "#display_loading" ).show();
				$( "#display" ).load('centre_display.php?centres=' + centres + '&date1=' + dateText + '&date2=' + date2.val(), function() {
					$( "#display_loading" ).hide();
					$( "#display" ).show('blind', '', 'slow');
				});
			});
		}
	});
});
</script>
<script>
$(function() {
	$( "#datepicker3" ).datepicker( {
		showOn: "button",
		buttonImage: "../images/calendar.png",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		firstDay: 1,
		altField: "#datepicker4",
		altFormat: "dd/mm/yy",
		showOtherMonths: true,
		selectOtherMonths: true,
		changeMonth: true,
		changeYear: true,
		minDate: "<?php echo "2012-03-01"; ?>",
		maxDate: "0d",
		onSelect: function(dateText, inst) {
			var date1 = $( "#datepicker" ),
				centres = "<?php echo $_GET["centres"]; ?>";
			
			$( "#display" ).hide('blind','','slow', function() {
				$( "#display_loading" ).show();
				$( "#display" ).load('centre_display.php?centres=' + centres + '&date1=' + date1.val() + '&date2=' + dateText, function() {
					$( "#display_loading" ).hide();
					$( "#display" ).show('blind', '', 'slow');
				});
			});
		}
	});
});
</script>

<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/centre_report_header.png" width="145" height="25" /></td>
<td align="right" style="padding-right:10px;"><input type='text' size='9' id='datepicker2' readonly='readonly' value='<?php echo date("d/m/Y", strtotime($date1)); ?>' /><input type='hidden' id='datepicker' value='<?php echo $date1; ?>' /> to <input type='text' size='9' id='datepicker4' readonly='readonly' style="height:20px;" value='<?php echo date("d/m/Y", strtotime($date2)); ?>' /><input type='hidden' id='datepicker3' value='<?php echo $date2; ?>' /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;">
<?php //self
for ($i = 0; $i < count($centres); $i++)
{
    $q0 = mysql_query("SELECT centre FROM vericon.centres WHERE centre = '$centres[$i]' AND type = 'Self'") or die(mysql_error());
    if (mysql_num_rows($q0) != 0)
    {
        $self[$centres[$i]] = 1;
    }
}

if (array_sum($self) > 0)
{
    echo '<thead>';
    echo '<tr class="ui-widget-header ">';
    echo '<th colspan="7" style="text-align:center;">Melbourne</th>';
    echo '</tr>';
    echo '<tr class="ui-widget-header ">';
    echo '<th>Centre</th>';
	echo '<th>Team Leader</th>';
    echo '<th style="text-align:center;">Hours</th>';
    echo '<th style="text-align:center;">Sales</th>';
    echo '<th style="text-align:center;">SPH</th>';
    echo '<th style="text-align:center;">Estimated CPS</th>';
	echo '<th></th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
}

for ($i = 0; $i < count($centres); $i++)
{
    if ($self[$centres[$i]] == 1)
    {
		$q0 = mysql_query("SELECT auth.first, auth.last FROM vericon.timesheet_designation,vericon.auth WHERE timesheet_designation.designation = 'Team Leader' AND auth.centre = '$centres[$i]' AND auth.status = 'Enabled' AND auth.user = timesheet_designation.user") or die(mysql_error());
		$t = mysql_fetch_row($q0);
		$tl = $t[0] . " " . $t[1];
		
        $q = mysql_query("SELECT SUM(hours) FROM vericon.timesheet WHERE centre = '$centres[$i]' AND DATE(date) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
        $hours = mysql_fetch_row($q);
        $total_hours += $hours[0];
        
		$q2 = mysql_query("SELECT * FROM vericon.sales_customers WHERE status = 'Approved' AND centre = '$centres[$i]' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
        $sales = mysql_num_rows($q2);
        $total_sales += $sales;
        
        $sph = $sales / $hours[0];
        $cps = ($hours[0]*27) / ($sales*0.62);

        echo "<tr>";
        echo "<td>" . $centres[$i] . "</td>";
		echo "<td>" . $tl . "</td>";
        echo "<td style='text-align:center;'>" . number_format($hours[0],2) . "</td>";
        echo "<td style='text-align:center;'>" . number_format($sales) . "</td>";
        echo "<td style='text-align:center;'>" . number_format($sph,2) . "</td>";
        echo "<td style='text-align:center;'>\$" . number_format($cps,2) . "</td>";
		echo "<td style='text-align:center;'><button onclick='Display(\"$centres[$i]\")' class='icon_view' title='View'></button>";
        echo "</tr>";
    }
}

if (array_sum($self) > 0)
{
    $total_sph = $total_sales / $total_hours;
    $total_spa = $total_sales / $total_agents;
    $total_cps = ($total_hours*27) / ($total_sales*0.62);
    
    echo "<tr>";
    echo "<td colspan='2' style='text-align:right;'><b>Total</b></td>";
    echo "<td style='text-align:center;'><b>" . number_format($total_hours,2) . "</b></td>";
    echo "<td style='text-align:center;'><b>" . number_format($total_sales) . "</b></td>";
    echo "<td style='text-align:center;'><b>" . number_format($total_sph,2) . "</b></td>";
    echo "<td style='text-align:center;'><b>\$" . number_format($total_cps,2) . "</b></td>";
	echo "<td></td>";
    echo "</tr>";
    echo "</tbody>";
}
?>
</table>
</div></center>
<br>
<div id="display2">
</div>

<table width="100%">
<tr>
<td align="right" style="padding-right:10px;"><button onclick="Export()" class="btn">Export</button></td>
</tr>
</table>