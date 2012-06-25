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
			var user = "<?php echo $ac["user"] ?>",
				date2 = $( "#datepicker3" );
			
			$( "#display" ).load('display.php?user=' + user + '&date1=' + dateText + '&date2=' + date2.val());
		}});
});
</script>
<script>
$(function() {
	$( "#datepicker3" ).datepicker( {
		showOn: "button",
		buttonImage: "../images/calendar.gif",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		altField: "#datepicker4",
		altFormat: "dd/mm/yy",
		changeMonth: true,
		changeYear: true,
		maxDate: "0d",
		onSelect: function(dateText, inst) {
			var user = "<?php echo $ac["user"] ?>",
				date1 = $( "#datepicker" );
			
			$( "#display" ).load('display.php?user=' + user + '&date1=' + date1.val() + '&date2=' + dateText);
		}});
});
</script>

<table width="100%">
<tr>
<td align="left"><img src="../images/sale_stats_header.png" width="110" height="25" /></td>
<td align="right" style="padding-right:10px;">
<input type='text' size='9' id='datepicker2' readonly='readonly' style="height:20px;" value='<?php echo date("d/m/Y"); ?>' /><input type='hidden' id='datepicker' value='<?php echo date("Y-m-d"); ?>' /> to <input type='text' size='9' id='datepicker4' readonly='readonly' style="height:20px;" value='<?php echo date("d/m/Y"); ?>' /><input type='hidden' id='datepicker3' value='<?php echo date("Y-m-d"); ?>' />
</td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>
<div id="display">
<script>
var user = "<?php echo $ac["user"] ?>",
	date1 = $( "#datepicker" );
	date2 = $( "#datepicker3" );

$( "#display" ).load('display.php?user=' + user + '&date1=' + date1.val() + '&date2=' + date2.val());
</script>
</div>

<?php
include "../source/footer.php";
?>