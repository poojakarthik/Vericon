<?php
include "../auth/iprestrict.php";
$q = mysql_query("SELECT centres FROM operations WHERE user = '$ac[user]'") or die(mysql_error());
$cen = mysql_fetch_row($q);
$centres = str_replace(",","_",$cen[0]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Operations :: Centre Timesheet Report</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<?php
include "../source/jquery.php";
?>
<script src="../js/date.js" type="text/javascript"></script>
<style>
div#users-contain table { margin: 1em 0; margin-bottom:0; border-collapse: collapse; }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid #eee; padding: .1em 10px; text-align: left; }
.ui-dialog { padding: .3em; }

.export
{
	background-image:url('../images/export2_btn.png');
	background-repeat:no-repeat;
	height:25px;
	width:85px;
	border:none;
	background-color:transparent;
	margin-left:5px;
}

.export:hover
{
	background-image:url('../images/export2_btn_hover.png');
	cursor:pointer;
}

.export2 {
	background-image:url('../images/export_excel_icon.png');
	background-repeat:no-repeat;
	height:16px;
	width:16px;
	border:none;
	background-color:transparent;
	cursor:pointer;
}
</style>
<script>
$(function() {
	$( "#datepicker" ).datepicker( {
		showOn: "button",
		buttonImage: "../images/calendar.gif",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		firstDay: 1,
		altField: "#datepicker2",
		altFormat: "dd/mm/yy",
		showOtherMonths: true,
		selectOtherMonths: true,
		changeMonth: true,
		changeYear: true,
		maxDate: "0d",
		onSelect: function(dateText, inst) {
			var date2 = $( "#datepicker3" ),
				centre = $( "#centre" );
			
			$("#overall").load('centre_display.php?method=overall&centre=<?php echo $centres; ?>&date1=' + dateText + '&date2=' + date2.val());
			$("#week").load('centre_display.php?method=week&centre=' + centre.val() + '&date1=' + dateText + '&date2=' + date2.val());
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
		firstDay: 1,
		altField: "#datepicker4",
		altFormat: "dd/mm/yy",
		showOtherMonths: true,
		selectOtherMonths: true,
		changeMonth: true,
		changeYear: true,
		maxDate: "0d",
		onSelect: function(dateText, inst) {
			var date1 = $( "#datepicker" ),
				centre = $( "#centre" );
			
			$("#overall").load('centre_display.php?method=overall&centre=<?php echo $centres; ?>&date1=' + date1.val() + '&date2=' + dateText);
			$("#week").load('centre_display.php?method=week&centre=' + centre.val() + '&date1=' + date1.val() + '&date2=' + dateText);
		}});
});
</script>
<script>
function Export()
{
	var date1 = $( "#datepicker" ),
		date2 = $( "#datepicker3" );
	
	window.location = 'centre_export.php?centre=<?php echo $centres; ?>&date1=' + date1.val() + '&date2=' + date2.val();
}
</script>
<script>
function Display(centre)
{
	var date1 = $( "#datepicker" ),
		date2 = $( "#datepicker3" );
	
	$( "#week" ).load('centre_display.php?method=week&centre=' + centre + '&date1=' + date1.val() + '&date2=' + date2.val());
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:650,
		height:285,
		modal: true,
		buttons: {
			"Close": function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

function View(we)
{
	var centre = $( "#centre" ),
		date = formatDate(new Date(getDateFromFormat(we,"y-MM-dd")),"dd/MM/y");
	
	$( ".we" ).html(date);
	$( "#daily" ).load('centre_display.php?method=daily&centre=' + centre.val() + '&we=' + we);
	$( "#dialog-confirm" ).dialog( "open" );
}
</script>
<script>
function Daily_Export(date)
{
	var centre = $( "#centre" );
	
	window.location = "../self/admin_timesheet_export.php?centre=" + centre.val() + "&date=" + date;
}
</script>
</head>

<body>
<div style="display:none;">
<img src="../images/export2_btn_hover.png" />
</div>
<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/operations_menu.php";
?>

<div id="text">

<div id="dialog-confirm" title="Daily Breakdown for W.E. <span class='we'></span>">
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;" width="100%">
<thead>
<tr class="ui-widget-header ">
<th>Day</th>
<th>Date</th>
<th style="text-align:center;">Hours</th>
<th style="text-align:center;">Bonus</th>
<th style="text-align:center;">Sales</th>
<th style="text-align:center;">SPH</th>
<th style="text-align:center;">SPA</th>
<th style="text-align:center;">Estimated CPS</th>
<th style="text-align:center;">Timesheet</th>
</tr>
</thead>
<tbody id="daily">
</tbody>
</table>
</div>
</div>

<table width="100%">
<tr>
<td align="left"><img src="../images/centre_breakdown_header.png" width="190" height="25" style="margin-left:3px;" /></td>
<td align="right" style="padding-right:10px;">
<input type='text' size='9' id='datepicker2' readonly='readonly' style="height:20px;" value='<?php echo "01/" . date("m") ."/" . date("Y"); ?>' /><input type='hidden' id='datepicker' value='<?php echo date("Y") . "-" . date("m") . "-01" ?>' /> to <input type='text' size='9' id='datepicker4' readonly='readonly' style="height:20px;" value='<?php echo date("d/m/Y"); ?>' /><input type='hidden' id='datepicker3' value='<?php echo date("Y-m-d"); ?>' /><input type="button" onclick="Export()" class="export" />
</td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>
<div id="overall">
<script>
var date1 = $( "#datepicker" ),
	date2 = $( "#datepicker3" );
$( "#overall" ).load('centre_display.php?method=overall&centre=<?php echo $centres; ?>&date1=' + date1.val() + '&date2=' + date2.val());
</script>
</div>
<br />
<div id="week">
<script>
$( "#week" ).load('centre_display.php?method=week');
</script>
</div>

</div>

</div> 
<?php
include "../source/footer.php";
?>
</body>
</html>