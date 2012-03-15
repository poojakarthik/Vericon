<style>
div#users-contain table { margin: 1em 0; margin-bottom:0; border-collapse: collapse; }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid #eee; padding: .1em 10px; text-align: left; }
</style>
<script>
$(function() {
	$( "#datepicker" ).datepicker( {
		showOn: "button",
		buttonImage: "../images/calendar.gif",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		altField: "#datepicker2",
		altFormat: "dd/mm/yy",
		changeMonth: true,
		changeYear: true,
		maxDate: "0d",
		onSelect: function(dateText, inst) {
			$( "#display" ).load('admin_stats_display.php?date=' + dateText);
		}});
});
</script>

<table width="99%">
<tr>
<td align="right">
<input type='text' size='9' id='datepicker2' readonly='readonly' value='<?php echo date("d/m/Y"); ?>' /><input type='hidden' id='datepicker' value='<?php echo date("Y-m-d"); ?>' />
</td>
</tr>
</table>
<div id="display">
<script>
var user = "<?php echo $ac["user"] ?>",
	date = $( "#datepicker" );

$( "#display" ).load('admin_stats_display.php?date=' + date.val());
</script>
</div>