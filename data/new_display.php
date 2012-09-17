<?php
mysql_connect('localhost','vericon','18450be');

$date = $_GET["date"];
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
		maxDate: "-1d",
		minDate: "<?php echo "2012-03-01"; ?>",
		onSelect: function(dateText, inst) {
			$( "#display" ).hide('blind', '', 'slow', function() {
				$( "#display" ).load('new_display.php?date=' + dateText, function() {
					$( "#display2" ).load('new_display2.php?date=' + dateText, function() {
						$( "#display" ).show('blind', '', 'slow');
					});
				});
			});
		}});
});
</script>
<script>
function Browse(location)
{
	var date = $( "#datepicker" );
	
	$( "#display2" ).hide('blind', '', 'slow', function() {
		$( "#display2" ).load('new_display2.php?date=' + date.val() + '&location=' + location, function() {
			$( "#display2" ).show('blind', '', 'slow');
		});
	});
}
</script>

<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/new_customers_header.png" width="160" height="25" /></td>
<td align="right" style="padding-right:10px;"><input type='text' size='9' id='datepicker2' readonly='readonly' value='<?php echo date("d/m/Y", strtotime($date)); ?>' /><input type='hidden' id='datepicker' value='<?php echo $date; ?>' />
</td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<center><div id="users-contain" class="ui-widget" style="width:98%;">
<table id="users" class="ui-widget ui-widget-content sortable" style="margin-top:0px;">
<?php
$q0 = mysql_query("SELECT id,name FROM vericon.groups") or die(mysql_error());
while ($group = mysql_fetch_row($q0))
{
	$total_b_approved = 0;
	$total_b_rejected = 0;
	$total_r_approved = 0;
	$total_r_rejected = 0;
	$total_business = 0;
	$total_residential = 0;
	
	echo '<thead>';
	echo '<tr class="ui-widget-header ">';
	echo '<th style="text-align:center;" colspan="7">' . $group[1] . '</th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th rowspan="2">Campaign</th>';
	echo '<th style="text-align:center;" colspan="3">Business</th>';
	echo '<th style="text-align:center;" colspan="3">Residential</th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th style="text-align:center;">Approved</th>';
	echo '<th style="text-align:center;">Rejected</th>';
	echo '<th style="text-align:center;">Total</th>';
	echo '<th style="text-align:center;">Approved</th>';
	echo '<th style="text-align:center;">Rejected</th>';
	echo '<th style="text-align:center;">Total</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	
	$q = mysql_query("SELECT campaign FROM vericon.campaigns WHERE `group` = '$group[0]' ORDER BY id ASC") or die(mysql_error());
	while ($campaigns = mysql_fetch_row($q))
	{
		$b_approved = 0;
		$r_approved = 0;
		$b_rejected = 0;
		$r_rejected = 0;
		
		$q1 = mysql_query("SELECT status,type,COUNT(id) FROM vericon.qa_customers WHERE campaign = '$campaigns[0]' AND DATE(timestamp) = '$date' GROUP BY status,type");
		while ($data = mysql_fetch_row($q1))
		{
			if ($data[0] == "Approved" && $data[1] == "Business") { $b_approved = $data[2];	}
			elseif ($data[0] == "Approved" && $data[1] == "Residential") { $r_approved = $data[2];	}
			elseif ($data[0] == "Rejected" && $data[1] == "Business") { $b_rejected = $data[2];	}
			elseif ($data[0] == "Rejected" && $data[1] == "Residential") { $r_rejected = $data[2];	}
		}
		
		$total_b_approved += $b_approved;
		$total_b_rejected += $b_rejected;
		$business = $b_approved + $b_rejected;
		$total_business += $business;
	
		$total_r_approved += $r_approved;	
		$total_r_rejected += $r_rejected;
		$residential = $r_approved + $r_rejected;
		$total_residential += $residential;
		
		echo "<tr>";
		echo "<td>" . $campaigns[0] . "</td>";
		echo "<td style='text-align:center;'>" . $b_approved . "</td>";
		echo "<td style='text-align:center;'>" . $b_rejected . "</td>";
		echo "<td style='text-align:center;'><b>" . $business . "</b></td>";
		echo "<td style='text-align:center;'>" . $r_approved . "</td>";
		echo "<td style='text-align:center;'>" . $r_rejected . "</td>";
		echo "<td style='text-align:center;'><b>" . $residential . "</b></td>";
		echo "</tr>";
	}
	
	echo "<tr>";
	echo "<td><b>Total</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_b_approved . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_b_rejected . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_business . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_r_approved . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_r_rejected . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_residential . "</b></td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div></center>
<br>
<table width="100%">
<tr>
<td style="padding-left:5px;"><img src="../images/dsr_download_header.png" width="155" height="25" /></td>
</tr>
<tr>
<td><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<div id="display2" style="padding-left:10px;">
</div>