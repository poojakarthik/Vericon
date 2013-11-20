<?php
mysql_connect('localhost','vericon','18450be');

$user = $_GET["user"];
$date1 = $_GET["date1"];
$date2 = $_GET["date2"];

$q0 = mysql_query("SELECT centre FROM vericon.auth WHERE user = '$user'") or die(mysql_error());
$centre = mysql_fetch_row($q0);
?>

<script>
$(function() {
	$( "#datepicker" ).datepicker( {
		showOn: "button",
		buttonImage: "../images/calendar.png",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		altField: "#datepicker2",
		altFormat: "dd/mm/yy",
		firstDay: 1,
		showOtherMonths: true,
		selectOtherMonths: true,
		changeMonth: true,
		changeYear: true,
		maxDate: "0d",
		minDate: "<?php echo "2012-03-01"; ?>",
		onSelect: function(dateText, inst) {
			var user = "<?php echo $user; ?>",
				date2 = $( "#datepicker3" );
			
			$( "#display" ).hide('blind', '', 'slow',
			function() {
				$( "#display" ).load('sales_report_display.php?user=' + user + '&date1=' + dateText + '&date2=' + date2.val(),
				function() {
					$( "#display" ).show('blind', '' , 'slow');
				});
			});
		}});
});
</script>
<script>
$(function() {
	$( "#datepicker3" ).datepicker( {
		showOn: "button",
		buttonImage: "../images/calendar.png",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		altField: "#datepicker4",
		altFormat: "dd/mm/yy",
		firstDay: 1,
		showOtherMonths: true,
		selectOtherMonths: true,
		changeMonth: true,
		changeYear: true,
		maxDate: "0d",
		minDate: "<?php echo "2012-03-01"; ?>",
		onSelect: function(dateText, inst) {
			var user = "<?php echo $user; ?>",
				date1 = $( "#datepicker" );
			
			$( "#display" ).hide('blind', '', 'slow',
			function() {
				$( "#display" ).load('sales_report_display.php?user=' + user + '&date1=' + date1.val() + '&date2=' + dateText,
				function() {
					$( "#display" ).show('blind', '' , 'slow');
				});
			});
		}});
});
</script>

<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/sales_report_header.png" width="135" height="25" /></td>
<td align="right" style="padding-right:10px;"><input type='text' size='9' id='datepicker2' readonly='readonly' style="height:20px;" value='<?php echo date("d/m/Y", strtotime($date1)); ?>' /><input type='hidden' id='datepicker' value='<?php echo $date1; ?>' /> to <input type='text' size='9' id='datepicker4' readonly='readonly' style="height:20px;" value='<?php echo date("d/m/Y", strtotime($date2)); ?>' /><input type='hidden' id='datepicker3' value='<?php echo $date2; ?>' /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:98%; margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th rowspan="2">Agent</th>
<th colspan="3" style="text-align:center;">Approved</th>
<th colspan="3" style="text-align:center;">Declined</th>
<th colspan="3" style="text-align:center;">Line Issue</th>
</tr>
<tr class="ui-widget-header ">
<th style="text-align:center;">B</th>
<th style="text-align:center;">R</th>
<th style="text-align:center;">T</th>
<th style="text-align:center;">B</th>
<th style="text-align:center;">R</th>
<th style="text-align:center;">T</th>
<th style="text-align:center;">B</th>
<th style="text-align:center;">R</th>
<th style="text-align:center;">T</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT user FROM vericon.auth WHERE centre = '$centre[0]' AND status = 'Enabled' ORDER BY user ASC") or die(mysql_error());
if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td colspan='10'><center>No agents</center></td>";
	echo "</tr>";
}
else
{
	$b_a = array();
	$r_a = array();
	$b_d = array();
	$r_d = array();
	$b_l = array();
	$r_l = array();
	
	$q1 = mysql_query("SELECT agent, status, type, COUNT(id) FROM vericon.sales_customers WHERE centre = '$centre[0]' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2' GROUP BY agent, status, type") or die(mysql_error());
	while ($data = mysql_fetch_row($q1))
	{
		if ($data[2] == "Business" && $data[1] == "Approved")
		{
			$b_a[$data[0]] = $data[3];
		}
		elseif ($data[2] == "Business" && $data[1] == "Declined")
		{
			$b_d[$data[0]] = $data[3];
		}
		elseif ($data[2] == "Business" && $data[1] == "Line Issue")
		{
			$b_l[$data[0]] = $data[3];
		}
		elseif ($data[2] == "Residential" && $data[1] == "Approved")
		{
			$r_a[$data[0]] = $data[3];
		}
		elseif ($data[2] == "Residential" && $data[1] == "Declined")
		{
			$r_d[$data[0]] = $data[3];
		}
		elseif ($data[2] == "Residential" && $data[1] == "Line Issue")
		{
			$r_l[$data[0]] = $data[3];
		}
	}
	
	while ($users = mysql_fetch_row($q))
	{
		$q2 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$users[0]'") or die(mysql_error());
		$ag = mysql_fetch_row($q2);
		$agent = $ag[0] . " " . $ag[1];
		
		echo "<tr>";
		echo "<td>" . $agent . "</td>";
		echo "<td style='text-align:center;'>" . number_format($b_a[$users[0]],0) . "</td>";
		echo "<td style='text-align:center;'>" . number_format($r_a[$users[0]],0) . "</td>";
		echo "<td style='text-align:center;'><b>" . number_format($b_a[$users[0]] + $r_a[$users[0]],0) . "</b></td>";
		echo "<td style='text-align:center;'>" . number_format($b_d[$users[0]],0) . "</td>";
		echo "<td style='text-align:center;'>" . number_format($r_d[$users[0]],0) . "</td>";
		echo "<td style='text-align:center;'><b>" . number_format($b_d[$users[0]] + $r_d[$users[0]],0) . "</b></td>";
		echo "<td style='text-align:center;'>" . number_format($b_l[$users[0]],0) . "</td>";
		echo "<td style='text-align:center;'>" . number_format($r_l[$users[0]],0) . "</td>";
		echo "<td style='text-align:center;'><b>" . number_format($b_l[$users[0]] + $r_l[$users[0]],0) . "</b></td>";
		echo "</tr>";
	}
}
?>
</tbody>
</table>
</div></center>

<table width="100%">
<tr>
<td align="right" style="padding-right:10px;"><button onClick="Export()" class="btn">Export</button></td>
</tr>
</table>