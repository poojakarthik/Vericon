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
		onSelect: function(dateText, inst) {
			var date2 = $( "#datepicker3" );
			$( "#display2" ).hide('blind', '' , 'slow', function() {
				$( "#display2" ).load('index_display2.php?date1=' + dateText + '&date2=' + date2.val(),
				function() {
					$( "#display2" ).show('blind', '' , 'slow');
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
		onSelect: function(dateText, inst) {
			var date1 = $( "#datepicker" );
			$( "#display2" ).hide('blind', '' , 'slow', function() {
				$( "#display2" ).load('index_display2.php?date1=' + date1.val() + '&date2=' + dateText,
				function() {
					$( "#display2" ).show('blind', '' , 'slow');
				});
			});
		}});
});
</script>

<table width="99%">
<tr>
<td align="left">
<img src="../images/customer_conversion_header.png" width="160" height="15" />
</td>
<td align="right" style="padding-right:10px;">
<input type='text' size='9' id='datepicker2' readonly='readonly' value='<?php echo date("d/m/Y"); ?>' /><input type='hidden' id='datepicker' value='<?php echo date("Y-m-d"); ?>' /> to <input type='text' size='9' id='datepicker4' readonly='readonly' value='<?php echo date("d/m/Y"); ?>' /><input type='hidden' id='datepicker3' value='<?php echo date("Y-m-d"); ?>' />
</td>
</tr>
<tr>
<td colspan="2">
<img src="../images/line.png" width="740" height="9" />
</td>
</tr>
</table>

<div id="display2">
<script>
var date1 = $( "#datepicker" ),
	date2 = $( "#datepicker3" );

$( "#display2" ).load('index_display2.php?date1=' + date1.val() + '&date2=' + date2.val(),
function() {
	$( "#display2" ).show('blind', '' , 'slow');
});
</script>
</div>