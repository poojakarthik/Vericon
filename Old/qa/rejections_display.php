<?php
mysql_connect('localhost','vericon','18450be');

$date1 = $_GET["date1"];
$date2 = $_GET["date2"];

$q0 = mysql_query("SELECT * FROM vericon.centres WHERE status = 'Enabled'") or die(mysql_error());
$num = mysql_num_rows($q0);

$t1 = ceil($num / 2);
$t2 = $num - $t1;
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
			var date2 = $( "#datepicker3" );
			
			$( "#display" ).hide('blind', '' , 'slow', function() {
				$( "#display_loading" ).show();
				$( "#display" ).load('rejections_display.php?date1=' + dateText + '&date2=' + date2.val(),
				function() {
					$( "#display_loading" ).hide();
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
			var date1 = $( "#datepicker" );
			
			$( "#display" ).hide('blind', '' , 'slow', function() {
				$( "#display_loading" ).show();
				$( "#display" ).load('rejections_display.php?date1=' + date1.val() + '&date2=' + dateText,
				function() {
					$( "#display_loading" ).hide();
					$( "#display" ).show('blind', '' , 'slow');
				});
			});
		}});
});
</script>

<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/rejection_report_header.png" width="170" height="25"></td>
<td align="right" style="padding-right:10px;"><input type='text' size='9' id='datepicker2' readonly='readonly' value='<?php echo date("d/m/Y", strtotime($date1)); ?>' /><input type='hidden' id='datepicker' value='<?php echo $date1; ?>' /> to <input type='text' size='9' id='datepicker4' readonly='readonly' value='<?php echo date("d/m/Y", strtotime($date2)); ?>' /><input type='hidden' id='datepicker3' value='<?php echo $date2; ?>' /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<center><table width="98%">
<tr>
<td width="50%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="98%" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th style="text-align:center;" rowspan="2">Centre</th>
<th style="text-align:center;" colspan="3">In-House</th>
<th style="text-align:center;" colspan="3">Rework</th>
<th style="text-align:center;" rowspan="2"></th>
</tr>
<tr class="ui-widget-header ">
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
$q = mysql_query("SELECT centre FROM vericon.centres WHERE status = 'Enabled' ORDER BY centre ASC LIMIT 0,$t1") or die(mysql_error());
while ($centre = mysql_fetch_row($q))
{
	$i_business = 0;
	$i_residential = 0;
	$r_business = 0;
	$r_residential = 0;
	
	$q1 = mysql_query("SELECT status,type,COUNT(id) FROM vericon.qa_customers WHERE centre = '$centre[0]' AND DATE(sale_timestamp) BETWEEN '$date1' AND '$date2' GROUP BY status,type") or die(mysql_error());
	while ($data = mysql_fetch_row($q1))
	{
		if ($data[0] == "Rejected") {
			if($data[1] == "Business") { $i_business = $data[2]; } elseif($data[1] == "Residential") { $i_residential = $data[2]; }
		} elseif ($data[0] == "Rework") {
			if($data[1] == "Business") { $r_business = $data[2]; } elseif($data[1] == "Residential") { $r_residential = $data[2]; }
		}
	}
	
	$i_total = $i_business + $i_residential;
	$r_total = $r_business + $r_residential;
	
	echo "<tr>";
	echo "<td style='text-align:center'>" . $centre[0] . "</td>";
	echo "<td style='text-align:center'>" . $i_business . "</td>";
	echo "<td style='text-align:center'>" . $i_residential . "</td>";
	echo "<td style='text-align:center'><b>" . $i_total . "</b></td>";
	echo "<td style='text-align:center'>" . $r_business . "</td>";
	echo "<td style='text-align:center'>" . $r_residential . "</td>";
	echo "<td style='text-align:center'><b>" . $r_total . "</b></td>";
	echo "<td style='text-align:center'><button onclick='View(\"$centre[0]\")' class='icon_view' title='View'></button></td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div></center>
</td>
<td width="50%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users2" class="ui-widget ui-widget-content" width="98%" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th style="text-align:center;" rowspan="2">Centre</th>
<th style="text-align:center;" colspan="3">In-House</th>
<th style="text-align:center;" colspan="3">Rework</th>
<th style="text-align:center;" rowspan="2"></th>
</tr>
<tr class="ui-widget-header ">
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
$q = mysql_query("SELECT centre FROM vericon.centres WHERE status = 'Enabled' ORDER BY centre ASC LIMIT $t1,$t2") or die(mysql_error());
while ($centre = mysql_fetch_row($q))
{
	$i_business = 0;
	$i_residential = 0;
	$r_business = 0;
	$r_residential = 0;
	
	$q1 = mysql_query("SELECT status,type,COUNT(id) FROM vericon.qa_customers WHERE centre = '$centre[0]' AND DATE(sale_timestamp) BETWEEN '$date1' AND '$date2' GROUP BY status,type") or die(mysql_error());
	while ($data = mysql_fetch_row($q1))
	{
		if ($data[0] == "Rejected") {
			if($data[1] == "Business") { $i_business = $data[2]; } elseif($data[1] == "Residential") { $i_residential = $data[2]; }
		} elseif ($data[0] == "Rework") {
			if($data[1] == "Business") { $r_business = $data[2]; } elseif($data[1] == "Residential") { $r_residential = $data[2]; }
		}
	}
	
	$i_total = $i_business + $i_residential;
	$r_total = $r_business + $r_residential;
	
	echo "<tr>";
	echo "<td style='text-align:center'>" . $centre[0] . "</td>";
	echo "<td style='text-align:center'>" . $i_business . "</td>";
	echo "<td style='text-align:center'>" . $i_residential . "</td>";
	echo "<td style='text-align:center'><b>" . $i_total . "</b></td>";
	echo "<td style='text-align:center'>" . $r_business . "</td>";
	echo "<td style='text-align:center'>" . $r_residential . "</td>";
	echo "<td style='text-align:center'><b>" . $r_total . "</b></td>";
	echo "<td style='text-align:center'><button onclick='View(\"$centre[0]\")' class='icon_view' title='View'></button></td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div></center>
</td>
</tr>
</table></center>

<div id="display2">
</div>

<table width="100%">
<tr>
<td align="right" style="padding-right:10px;"><button onclick="Export()" class="btn">Export</button></td>
</tr>
</table>