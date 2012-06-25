<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
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
			$( "#display" ).load('stats_display.php?date=' + dateText);
		}});
});
</script>

<table width="99%">
<tr>
<td align="left">
<img src="../images/customer_conversion_header.png" width="160" height="15" />
</td>
<td align="right">
<input type='text' size='9' id='datepicker2' readonly='readonly' style="height:20px;" value='<?php echo date("d/m/Y"); ?>' /><input type='hidden' id='datepicker' value='<?php echo date("Y-m-d"); ?>' />
</td>
</tr>
<tr>
<td colspan="2">
<img src="../images/line.png" width="740" height="9" />
</td>
</tr>
</table>
<div id="display">
<script>
var date = $( "#datepicker" );

$( "#display" ).load('stats_display.php?date=' + date.val());
</script>
</div>

<?php
include "../source/footer.php";
?>