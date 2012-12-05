<?php
mysql_connect('localhost','vericon','18450be');

$user = $_GET["user"];
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
				user = $( "#user" );
			
			$( "#display2" ).hide('blind','','slow', function() {
				$( "#display2" ).load("agent_display4.php?user=" + user.val() + '&date1=' + dateText + '&date2=' + date2.val(),
				function() {
					$( "#display2" ).show('blind','','slow');
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
				user = $( "#user" );
			
			$( "#display2" ).hide('blind','','slow', function() {
				$( "#display2" ).load("agent_display4.php?user=" + user.val() + '&date1=' + date1.val() + '&date2=' + dateText,
				function() {
					$( "#display2" ).show('blind','','slow');
				});
			});
		}
	});
});
</script>

<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/agent_details_header.png" width="145" height="25" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<input type="hidden" id="user" value="<?php echo $user; ?>" />

<?php
$q = mysql_query("SELECT * FROM vericon.auth WHERE user = '$user'") or die(mysql_error());
$agent = mysql_fetch_assoc($q);

$q1 = mysql_query("SELECT MIN(date), MAX(date) FROM vericon.timesheet WHERE user = '$user'") or die(mysql_error());
$em_date = mysql_fetch_row($q1);

$q2 = mysql_query("SELECT designation FROM vericon.timesheet_designation WHERE user = '$user'") or die(mysql_error());
$desi = mysql_fetch_row($q2);

if ($agent["status"] == "Enabled") { $end = "Current"; } else { $end = date("d/m/Y", strtotime($em_date[1])); }
?>

<center><table width="98%">
<tr>
<td width="85px">Employee ID </td>
<td><b><?php echo $agent["user"]; ?></b></td>
</tr>
<tr>
<td>Agent Name </td>
<td><b><?php echo $agent["first"] . " " . $agent["last"] . " (" . $agent["alias"] . ")"; ?></b></td>
</tr>
<tr>
<td>Designation </td>
<td><b><?php echo $desi[0]; ?></b></td>
</tr>
<tr>
<td>Centre </td>
<td><b><?php echo $agent["centre"]; ?></b></td>
</tr>
<tr>
<td>Employement </td>
<td><b><?php echo date("d/m/Y", strtotime($em_date[0])) . " - " . $end; ?></b></td>
</tr>
</table></center>
<br />
<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/weekly_breakdown_header.png" width="200" height="25" /></td>
<td align="right" style="padding-right:10px;"><input type='text' size='9' id='datepicker2' readonly='readonly' style="height:20px;" value='<?php echo date("d/m/Y", strtotime($em_date[0])); ?>' /><input type='hidden' id='datepicker' value='<?php echo $em_date[0]; ?>' /> to <input type='text' size='9' id='datepicker4' readonly='readonly' style="height:20px;" value='<?php echo date("d/m/Y", strtotime($em_date[1])); ?>' /><input type='hidden' id='datepicker3' value='<?php echo $em_date[1]; ?>' /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<div id="display2">
</div>

<table width="100%">
<tr>
<td align="right" style="padding-right:10px;"><button onclick="Back()" class="btn">Back</button></td>
</tr>
</table>