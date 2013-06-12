<?php
mysql_connect('localhost','vericon','18450be');

$centres = explode(",",$_GET["centres"]);
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
			var centres = "<?php echo $_GET["centres"]; ?>",
				date2 = $( "#datepicker3" );
			
			$( "#display" ).hide('blind', '', 'slow',
			function() {
				$( "#display" ).load('index_display.php?centres=' + centres + '&date1=' + dateText + '&date2=' + date2.val(),
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
			var centres = "<?php echo $_GET["centres"]; ?>",
				date1 = $( "#datepicker" );
			
			$( "#display" ).hide('blind', '', 'slow',
			function() {
				$( "#display" ).load('index_display.php?centres=' + centres + '&date1=' + date1.val() + '&date2=' + dateText,
				function() {
					$( "#display" ).show('blind', '' , 'slow');
				});
			});
		}});
});
</script>

<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/sale_stats_header.png" width="110" height="25" /></td>
<td align="right" style="padding-right:10px;"><input type='text' size='9' id='datepicker2' readonly='readonly' value='<?php echo date("d/m/Y", strtotime($date1)); ?>' /><input type='hidden' id='datepicker' value='<?php echo $date1; ?>' /> to <input type='text' size='9' id='datepicker4' readonly='readonly' value='<?php echo date("d/m/Y", strtotime($date2)); ?>' /><input type='hidden' id='datepicker3' value='<?php echo $date2; ?>' /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<center><div id="users-contain" class="ui-widget" style="width:98%">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; margin-top:0px;">
<?php //captive
$total_b_approved = 0;
$total_r_approved = 0;
$total_approved = 0;
$total_b_declined = 0;
$total_r_declined = 0;
$total_declined = 0;
$total_b_line_issue = 0;
$total_r_line_issue = 0;
$total_line_issue = 0;
$tt = 0;
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
	echo '<th rowspan="3">Centre</th>';
	echo '<th colspan="9" style="text-align:center;">Captive</th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th colspan="3" style="text-align:center;">Approved</th>';
	echo '<th colspan="3" style="text-align:center;">Declined</th>';
	echo '<th colspan="3" style="text-align:center;">Line Issue</th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th style="text-align:center;">Business</th>';
	echo '<th style="text-align:center;">Residential</th>';
	echo '<th style="text-align:center;">Total</th>';
	echo '<th style="text-align:center;">Business</th>';
	echo '<th style="text-align:center;">Residential</th>';
	echo '<th style="text-align:center;">Total</th>';
	echo '<th style="text-align:center;">Business</th>';
	echo '<th style="text-align:center;">Residential</th>';
	echo '<th style="text-align:center;">Total</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
}

for ($i = 0; $i < count($centres); $i++)
{
	if ($captive[$centres[$i]] == 1)
	{
		$b_approved = 0;
		$r_approved = 0;
		$b_declined = 0;
		$r_declined = 0;
		$b_line_issue = 0;
		$r_line_issue = 0;
		
		$q = mysql_query("SELECT status,type,COUNT(id) FROM vericon.sales_customers WHERE centre = '$centres[$i]' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2' GROUP BY status,type");
		while ($data = mysql_fetch_row($q))
		{
			if ($data[0] == "Approved" && $data[1] == "Business") { $b_approved = $data[2];	}
			elseif ($data[0] == "Approved" && $data[1] == "Residential") { $r_approved = $data[2];	}
			elseif ($data[0] == "Declined" && $data[1] == "Business") { $b_declined = $data[2];	}
			elseif ($data[0] == "Declined" && $data[1] == "Residential") { $r_declined = $data[2];	}
			elseif ($data[0] == "Line Issue" && $data[1] == "Business") { $b_line_issue = $data[2];	}
			elseif ($data[0] == "Line Issue" && $data[1] == "Residential") { $r_line_issue = $data[2];	}
		}
		
		$total_b_approved += $b_approved;
		$total_r_approved += $r_approved;
		$approved = $b_approved + $r_approved;
		$total_approved += $approved;
		
		$total_b_declined += $b_declined;
		$total_r_declined += $r_declined;
		$declined = $b_declined + $r_declined;
		$total_declined += $declined;
		
		$total_b_line_issue += $b_line_issue;
		$total_r_line_issue += $r_line_issue;
		$line_issue = $b_line_issue + $r_line_issue;
		$total_line_issue += $line_issue;
		
		$t = $approved + $declined + $line_issue;
		$tt += $t;
		
		if ($t > 0)
		{
			echo "<tr>";
			echo "<td>" . $centres[$i] . "</td>";
			echo "<td style='text-align:center;'>" . $b_approved . "</td>";
			echo "<td style='text-align:center;'>" . $r_approved . "</td>";
			echo "<td style='text-align:center;'><b>" . $approved . "</b></td>";
			echo "<td style='text-align:center;'>" . $b_declined . "</td>";
			echo "<td style='text-align:center;'>" . $r_declined . "</td>";
			echo "<td style='text-align:center;'><b>" . $declined . "</b></td>";
			echo "<td style='text-align:center;'>" . $b_line_issue . "</td>";
			echo "<td style='text-align:center;'>" . $r_line_issue . "</td>";
			echo "<td style='text-align:center;'><b>" . $line_issue . "</b></td>";
			echo "</tr>";
		}
	}
}

if (array_sum($captive) > 0)
{
	if ($tt > 0)
	{
		echo "<tr>";
		echo "<td><b>Total</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_b_approved . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_r_approved . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_approved . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_b_declined . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_r_declined . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_declined . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_b_line_issue . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_r_line_issue . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_line_issue . "</b></td>";
		echo "</tr>";
		echo "</tbody>";
	}
	else
	{
		echo "<tr>";
		echo "<td style='text-align:center;' colspan='10'>No Sales For This Date Range</td>";
		echo "</tr>";
		echo "</tbody>";
	}
}
?>
<?php //outsourced
$total_b_approved = 0;
$total_r_approved = 0;
$total_approved = 0;
$total_b_declined = 0;
$total_r_declined = 0;
$total_declined = 0;
$total_b_line_issue = 0;
$total_r_line_issue = 0;
$total_line_issue = 0;
$tt = 0;
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
	echo '<th rowspan="3">Centre</th>';
	echo '<th colspan="9" style="text-align:center;">Outsourced</th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th colspan="3" style="text-align:center;">Approved</th>';
	echo '<th colspan="3" style="text-align:center;">Declined</th>';
	echo '<th colspan="3" style="text-align:center;">Line Issue</th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th style="text-align:center;">Business</th>';
	echo '<th style="text-align:center;">Residential</th>';
	echo '<th style="text-align:center;">Total</th>';
	echo '<th style="text-align:center;">Business</th>';
	echo '<th style="text-align:center;">Residential</th>';
	echo '<th style="text-align:center;">Total</th>';
	echo '<th style="text-align:center;">Business</th>';
	echo '<th style="text-align:center;">Residential</th>';
	echo '<th style="text-align:center;">Total</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
}

for ($i = 0; $i < count($centres); $i++)
{
	if ($outsourced[$centres[$i]] == 1)
	{
		$b_approved = 0;
		$r_approved = 0;
		$b_declined = 0;
		$r_declined = 0;
		$b_line_issue = 0;
		$r_line_issue = 0;
		
		$q = mysql_query("SELECT status,type,COUNT(id) FROM vericon.sales_customers WHERE centre = '$centres[$i]' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2' GROUP BY status,type");
		while ($data = mysql_fetch_row($q))
		{
			if ($data[0] == "Approved" && $data[1] == "Business") { $b_approved = $data[2];	}
			elseif ($data[0] == "Approved" && $data[1] == "Residential") { $r_approved = $data[2];	}
			elseif ($data[0] == "Declined" && $data[1] == "Business") { $b_declined = $data[2];	}
			elseif ($data[0] == "Declined" && $data[1] == "Residential") { $r_declined = $data[2];	}
			elseif ($data[0] == "Line Issue" && $data[1] == "Business") { $b_line_issue = $data[2];	}
			elseif ($data[0] == "Line Issue" && $data[1] == "Residential") { $r_line_issue = $data[2];	}
		}
		
		$total_b_approved += $b_approved;
		$total_r_approved += $r_approved;
		$approved = $b_approved + $r_approved;
		$total_approved += $approved;
		
		$total_b_declined += $b_declined;
		$total_r_declined += $r_declined;
		$declined = $b_declined + $r_declined;
		$total_declined += $declined;
		
		$total_b_line_issue += $b_line_issue;
		$total_r_line_issue += $r_line_issue;
		$line_issue = $b_line_issue + $r_line_issue;
		$total_line_issue += $line_issue;
		
		$t = $approved + $declined + $line_issue;
		$tt += $t;
		
		if ($t > 0)
		{
			echo "<tr>";
			echo "<td>" . $centres[$i] . "</td>";
			echo "<td style='text-align:center;'>" . $b_approved . "</td>";
			echo "<td style='text-align:center;'>" . $r_approved . "</td>";
			echo "<td style='text-align:center;'><b>" . $approved . "</b></td>";
			echo "<td style='text-align:center;'>" . $b_declined . "</td>";
			echo "<td style='text-align:center;'>" . $r_declined . "</td>";
			echo "<td style='text-align:center;'><b>" . $declined . "</b></td>";
			echo "<td style='text-align:center;'>" . $b_line_issue . "</td>";
			echo "<td style='text-align:center;'>" . $r_line_issue . "</td>";
			echo "<td style='text-align:center;'><b>" . $line_issue . "</b></td>";
			echo "</tr>";
		}
	}
}

if (array_sum($outsourced) > 0)
{
	if ($tt > 0)
	{
		echo "<tr>";
		echo "<td><b>Total</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_b_approved . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_r_approved . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_approved . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_b_declined . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_r_declined . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_declined . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_b_line_issue . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_r_line_issue . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_line_issue . "</b></td>";
		echo "</tr>";
		echo "</tbody>";
	}
	else
	{
		echo "<tr>";
		echo "<td style='text-align:center;' colspan='10'>No Sales For This Date Range</td>";
		echo "</tr>";
		echo "</tbody>";
	}
}
?>
<?php //self
$total_b_approved = 0;
$total_r_approved = 0;
$total_approved = 0;
$total_b_declined = 0;
$total_r_declined = 0;
$total_declined = 0;
$total_b_line_issue = 0;
$total_r_line_issue = 0;
$total_line_issue = 0;
$tt = 0;
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
	echo '<th rowspan="3">Centre</th>';
	echo '<th colspan="9" style="text-align:center;">Melbourne</th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th colspan="3" style="text-align:center;">Approved</th>';
	echo '<th colspan="3" style="text-align:center;">Declined</th>';
	echo '<th colspan="3" style="text-align:center;">Line Issue</th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th style="text-align:center;">Business</th>';
	echo '<th style="text-align:center;">Residential</th>';
	echo '<th style="text-align:center;">Total</th>';
	echo '<th style="text-align:center;">Business</th>';
	echo '<th style="text-align:center;">Residential</th>';
	echo '<th style="text-align:center;">Total</th>';
	echo '<th style="text-align:center;">Business</th>';
	echo '<th style="text-align:center;">Residential</th>';
	echo '<th style="text-align:center;">Total</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
}

for ($i = 0; $i < count($centres); $i++)
{
	if ($self[$centres[$i]] == 1)
	{
		$b_approved = 0;
		$r_approved = 0;
		$b_declined = 0;
		$r_declined = 0;
		$b_line_issue = 0;
		$r_line_issue = 0;
		
		$q = mysql_query("SELECT status,type,COUNT(id) FROM vericon.sales_customers WHERE centre = '$centres[$i]' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2' GROUP BY status,type");
		while ($data = mysql_fetch_row($q))
		{
			if ($data[0] == "Approved" && $data[1] == "Business") { $b_approved = $data[2];	}
			elseif ($data[0] == "Approved" && $data[1] == "Residential") { $r_approved = $data[2];	}
			elseif ($data[0] == "Declined" && $data[1] == "Business") { $b_declined = $data[2];	}
			elseif ($data[0] == "Declined" && $data[1] == "Residential") { $r_declined = $data[2];	}
			elseif ($data[0] == "Line Issue" && $data[1] == "Business") { $b_line_issue = $data[2];	}
			elseif ($data[0] == "Line Issue" && $data[1] == "Residential") { $r_line_issue = $data[2];	}
		}
		
		$total_b_approved += $b_approved;
		$total_r_approved += $r_approved;
		$approved = $b_approved + $r_approved;
		$total_approved += $approved;
		
		$total_b_declined += $b_declined;
		$total_r_declined += $r_declined;
		$declined = $b_declined + $r_declined;
		$total_declined += $declined;
		
		$total_b_line_issue += $b_line_issue;
		$total_r_line_issue += $r_line_issue;
		$line_issue = $b_line_issue + $r_line_issue;
		$total_line_issue += $line_issue;
		
		$t = $approved + $declined + $line_issue;
		$tt += $t;
		
		if ($t > 0)
		{
			echo "<tr>";
			echo "<td>" . $centres[$i] . "</td>";
			echo "<td style='text-align:center;'>" . $b_approved . "</td>";
			echo "<td style='text-align:center;'>" . $r_approved . "</td>";
			echo "<td style='text-align:center;'><b>" . $approved . "</b></td>";
			echo "<td style='text-align:center;'>" . $b_declined . "</td>";
			echo "<td style='text-align:center;'>" . $r_declined . "</td>";
			echo "<td style='text-align:center;'><b>" . $declined . "</b></td>";
			echo "<td style='text-align:center;'>" . $b_line_issue . "</td>";
			echo "<td style='text-align:center;'>" . $r_line_issue . "</td>";
			echo "<td style='text-align:center;'><b>" . $line_issue . "</b></td>";
			echo "</tr>";
		}
	}
}

if (array_sum($self) > 0)
{
	if ($tt > 0)
	{
		echo "<tr>";
		echo "<td><b>Total</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_b_approved . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_r_approved . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_approved . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_b_declined . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_r_declined . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_declined . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_b_line_issue . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_r_line_issue . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_line_issue . "</b></td>";
		echo "</tr>";
		echo "</tbody>";
	}
	else
	{
		echo "<tr>";
		echo "<td style='text-align:center;' colspan='10'>No Sales For This Date Range</td>";
		echo "</tr>";
		echo "</tbody>";
	}
}
?>
</table>
</div></center>