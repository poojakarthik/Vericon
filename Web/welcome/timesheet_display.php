<?php
$date = $_GET["date"];
?>
<script> //datepicker
$(function() {
	$( "#datepicker" ).datepicker( {
		showOn: "button",
		buttonImage: "../images/calendar.png",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		altField: "#datepicker2",
		altFormat: "dd/mm/yy",
		changeMonth: true,
		changeYear: true,
		firstDay: 1,
		minDate: new Date(2012,2,1),
		maxDate: "W",
		onSelect: function(dateText, inst){
			$( "#display2" ).hide('blind', '' , 'slow', function() {
				$( "#display2" ).load('timesheet_view.php?date=' + dateText,
				function() {
					$( "#display2" ).show('blind', '' , 'slow');
				});
			});
		}});
});
</script>
<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/welcome_timesheet_header.png" width="200" height="25"></td>
<td align="right" style="padding-right:10px;"><input type="text" size="10" id="datepicker2" readonly value="<?php echo date("d/m/Y", strtotime($date)); ?>" style="height:20px;" /><input type="hidden" id="datepicker" value="<?php echo $date; ?>" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" alt="line" /></td>
</tr>
</table>

<div id="display2">
</div>