<?php
include "../auth/iprestrict.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Accounts :: Rejections</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<?php
include "../source/jquery.php";
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
			
			$( "#display" ).load('rejections_display.php?method=display&date1=' + dateText + '&date2=' + date2.val());
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
			
			$( "#display" ).load('rejections_display.php?method=display&date1=' + date1.val() + '&date2=' + dateText);
		}});
});
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:750,
		height:400,
		modal: true,
		buttons: {
			"Close": function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
</script>
</head>

<body>
<div style="display:none;">

</div>
<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/accounts_menu.php";
?>

<div id="text">

<div id="dialog-confirm" title="Rejections for <span class='cent'></span>">
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;" width="100%">
<thead>
<tr class="ui-widget-header ">
<th>Sale ID</th>
<th>Date</th>
<th>Agent</th>
<th>Reason</th>
</tr>
</thead>
<tbody id="rejection_view">
</tbody>
</table>
</div>
</div>

<table width="100%" style="margin-top:-10px;">
<tr>
<td align="left"><img src="../images/rejection_report_header.png" width="170" height="25" style="margin-left:4px;" /></td>
<td align="right" style="padding-right:10px;"><input type='text' size='9' id='datepicker2' readonly='readonly' style="height:20px;" value='<?php echo date("d/m/Y"); ?>' /><input type='hidden' id='datepicker' value='<?php echo date("Y-m-d"); ?>' /> to <input type='text' size='9' id='datepicker4' readonly='readonly' style="height:20px;" value='<?php echo date("d/m/Y"); ?>' /><input type='hidden' id='datepicker3' value='<?php echo date("Y-m-d"); ?>' /></td>
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

$( "#display" ).load('rejections_display.php?method=display&date1=' + date1.val() + '&date2=' + date2.val());
</script>
</div>

</div>

</div> 
<?php
include "../source/footer.php";
?>
</body>
</html>