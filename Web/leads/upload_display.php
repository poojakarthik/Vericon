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
			$( "#display2" ).hide('blind', '' , 'slow', function() {
				$( "#display2" ).load('upload_display2.php?date=' + dateText, function() {
					$( "#display2" ).show('blind', '' , 'slow');
				});
			});
		}
	});
});
</script>

<div id="upload" style="min-height:225px;">
<?php
if (file_exists("/var/vtmp/leads_report.txt"))
{
?>
<script>
Upload_View();
</script>
<?php	
}
?>
</div>

<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/upload_history_header.png" width="160" height="25"></td>
<td align="right" style="padding-right:10px;"><input type='text' size='11' id='datepicker2' readonly='readonly' value='<?php echo date("d/m/Y"); ?>' /><input type='hidden' id='datepicker' value='<?php echo date("Y-m-d"); ?>' /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<div id="display2">
</div>