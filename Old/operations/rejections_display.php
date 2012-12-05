<?php
mysql_connect('localhost','vericon','18450be');

$centres = explode(",", $_GET["centres"]);
$date = $_GET["date"];
$week = date("W", strtotime($date));
$year = date("Y", strtotime($date));
$date1 = date("Y-m-d", strtotime($year . "W" . $week . "1"));
$date2 = date("Y-m-d", strtotime($year . "W" . $week . "7"));
?>
<script>
$(function() {
	$( "#datepicker" ).datepicker( {
		showOn: "button",
		buttonImage: "../images/calendar.png",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		firstDay: 1,
		showOtherMonths: true,
		selectOtherMonths: true,
		changeMonth: true,
		changeYear: true,
		maxDate: "0d",
		minDate: "<?php echo "2012-03-01"; ?>",
		onSelect: function(dateText, inst) {
			var centres = "<?php echo $_GET["centres"]; ?>";
			
			$( "#display" ).hide('blind', '' , 'slow', function() {
				$( "#display_loading" ).show();
				$( "#display" ).load('rejections_display.php?centres=' + centres + '&date=' + dateText,
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
<td align="right" style="padding-right:10px;"><input type='text' size='9' readonly='readonly' style="height:20px;" value="<?php echo date("d/m/Y", strtotime($date1)); ?>" /> to <input type='text' size='9' readonly='readonly' style="height:20px;" value="<?php echo date("d/m/Y", strtotime($date2)); ?>" /><input type='hidden' id='datepicker' value='<?php echo $date; ?>' /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<center><table width="99%">
<tr>
<td valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; margin-top:0px;">
<?php //captive
for ($i = 0; $i < count($centres); $i++)
{
	$q0 = mysql_query("SELECT centre FROM vericon.centres WHERE centre = '$centres[$i]' AND type = 'Captive'") or die(mysql_error());
	if (mysql_num_rows($q0) != 0)
	{
		$captive[$centres[$i]] = 1;
	}
}

if (array_sum($captive) > 0)
{
	echo '<thead>';
	echo '<tr class="ui-widget-header ">';
	echo '<th colspan="8" style="text-align:center;">Captive</th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th rowspan="2" style="text-align:center;">Centre</th>';
	echo '<th colspan="3" style="text-align:center;">In-House</th>';
	echo '<th colspan="3" style="text-align:center;">Rework</th>';
	echo '<th rowspan="2"></th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th style="text-align:center;">B</th>';
	echo '<th style="text-align:center;">R</th>';
	echo '<th style="text-align:center;">T</th>';
	echo '<th style="text-align:center;">B</th>';
	echo '<th style="text-align:center;">R</th>';
	echo '<th style="text-align:center;">T</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
}

for ($i = 0; $i < count($centres); $i++)
{
	if ($captive[$centres[$i]] == 1)
	{
		$i_business = 0;
		$i_residential = 0;
		$r_business = 0;
		$r_residential = 0;
		
		$q1 = mysql_query("SELECT status,type,COUNT(id) FROM vericon.qa_customers WHERE centre = '$centres[$i]' AND DATE(sale_timestamp) BETWEEN '$date1' AND '$date2' GROUP BY status,type") or die(mysql_error());
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
		echo "<td style='text-align:center'>" . $centres[$i] . "</td>";
		echo "<td style='text-align:center'>" . $i_business . "</td>";
		echo "<td style='text-align:center'>" . $i_residential . "</td>";
		echo "<td style='text-align:center'><b>" . $i_total . "</b></td>";
		echo "<td style='text-align:center'>" . $r_business . "</td>";
		echo "<td style='text-align:center'>" . $r_residential . "</td>";
		echo "<td style='text-align:center'><b>" . $r_total . "</b></td>";
		echo "<td style='text-align:center'><button onclick='View(\"$centres[$i]\")' class='icon_view' title='View'></button></td>";
		echo "</tr>";
	}
}
?>
</table>
</div></center>
</td>
<td valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; margin-top:0px;">
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
	echo '<th colspan="8" style="text-align:center;">Melbourne</th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th rowspan="2" style="text-align:center;">Centre</th>';
	echo '<th colspan="3" style="text-align:center;">In-House</th>';
	echo '<th colspan="3" style="text-align:center;">Rework</th>';
	echo '<th rowspan="2"></th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th style="text-align:center;">B</th>';
	echo '<th style="text-align:center;">R</th>';
	echo '<th style="text-align:center;">T</th>';
	echo '<th style="text-align:center;">B</th>';
	echo '<th style="text-align:center;">R</th>';
	echo '<th style="text-align:center;">T</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
}

for ($i = 0; $i < count($centres); $i++)
{
	if ($self[$centres[$i]] == 1)
	{
		$i_business = 0;
		$i_residential = 0;
		$r_business = 0;
		$r_residential = 0;
		
		$q1 = mysql_query("SELECT status,type,COUNT(id) FROM vericon.qa_customers WHERE centre = '$centres[$i]' AND DATE(sale_timestamp) BETWEEN '$date1' AND '$date2' GROUP BY status,type") or die(mysql_error());
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
		echo "<td style='text-align:center'>" . $centres[$i] . "</td>";
		echo "<td style='text-align:center'>" . $i_business . "</td>";
		echo "<td style='text-align:center'>" . $i_residential . "</td>";
		echo "<td style='text-align:center'><b>" . $i_total . "</b></td>";
		echo "<td style='text-align:center'>" . $r_business . "</td>";
		echo "<td style='text-align:center'>" . $r_residential . "</td>";
		echo "<td style='text-align:center'><b>" . $r_total . "</b></td>";
		echo "<td style='text-align:center'><button onclick='View(\"$centres[$i]\")' class='icon_view' title='View'></button></td>";
		echo "</tr>";
	}
}
?>
</table>
</div></center>
</td>
</tr>
<tr>
<td colspan="2">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; margin-top:0px;">
<?php //outsourced
for ($i = 0; $i < count($centres); $i++)
{
	$q0 = mysql_query("SELECT centre FROM vericon.centres WHERE centre = '$centres[$i]' AND type = 'Outsourced'") or die(mysql_error());
	if (mysql_num_rows($q0) != 0)
	{
		$outsourced[$centres[$i]] = 1;
	}
}

if (array_sum($outsourced) > 0)
{
	echo '<thead>';
	echo '<tr class="ui-widget-header ">';
	echo '<th colspan="8" style="text-align:center;">Outsourced</th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th rowspan="2" style="text-align:center;">Centre</th>';
	echo '<th colspan="3" style="text-align:center;">In-House</th>';
	echo '<th colspan="3" style="text-align:center;">Rework</th>';
	echo '<th rowspan="2"></th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th style="text-align:center;">B</th>';
	echo '<th style="text-align:center;">R</th>';
	echo '<th style="text-align:center;">T</th>';
	echo '<th style="text-align:center;">B</th>';
	echo '<th style="text-align:center;">R</th>';
	echo '<th style="text-align:center;">T</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
}

for ($i = 0; $i < count($centres); $i++)
{
	if ($outsourced[$centres[$i]] == 1)
	{
		$i_business = 0;
		$i_residential = 0;
		$r_business = 0;
		$r_residential = 0;
		
		$q1 = mysql_query("SELECT status,type,COUNT(id) FROM vericon.qa_customers WHERE centre = '$centres[$i]' AND DATE(sale_timestamp) BETWEEN '$date1' AND '$date2' GROUP BY status,type") or die(mysql_error());
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
		echo "<td style='text-align:center'>" . $centres[$i] . "</td>";
		echo "<td style='text-align:center'>" . $i_business . "</td>";
		echo "<td style='text-align:center'>" . $i_residential . "</td>";
		echo "<td style='text-align:center'><b>" . $i_total . "</b></td>";
		echo "<td style='text-align:center'>" . $r_business . "</td>";
		echo "<td style='text-align:center'>" . $r_residential . "</td>";
		echo "<td style='text-align:center'><b>" . $r_total . "</b></td>";
		echo "<td style='text-align:center'><button onclick='View(\"$centres[$i]\")' class='icon_view' title='View'></button></td>";
		echo "</tr>";
	}
}
?>
</table>
</div></center>
</td>
</tr>
</table>
</center>
<br />
<div id="display2">
</div>