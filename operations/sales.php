<?php
include "../auth/iprestrict.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Operations :: Centre Sales</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<?php
include "../source/jquery.php";
?>
<style>
.search
{
	background-image:url('../images/search_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
}

.search:hover
{
	background-image:url('../images/search_btn_hover.png');
	cursor:pointer;
}

div#users-contain table { margin: 1em 0; border-collapse: collapse; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
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
			var centre = $( "#centre" );
		
			window.location = 'sales.php?centre=' + centre.val() + '&date=' + dateText;
		}
	});
});
</script>
<script>
function Display()
{
	var centre = $( "#centre" ),
		date = $( "#datepicker" );
		
	window.location = 'sales.php?centre=' + centre.val() + '&date=' + date.val();
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:450,
		height:260,
		modal: true,
		buttons: {
			"Close": function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

function Details(id)
{
	$( "#sale_details" ).load('sales_display.php?method=details&id=' + id);
	$( "#dialog-confirm" ).dialog( "open" );
}
</script>
</head>

<body>
<div style="display:none;">

</div>
<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/operations_menu.php";
?>

<div id="text">

<div id="dialog-confirm" title="Sale Details">
<div id="sale_details"></div>
</div>

<input type="hidden" id="centre_link" value="<?php echo $_GET["centre"]; ?>" />
<table width="100%">
<tr>
<td align="left"><img src="../images/sale_details_header2.png" width="125" height="25" style="margin-left:3px;" /></td>
<td align="right" style="padding-right:10px;"><select id="centre" onchange="Display()" style="height:auto; margin:0; padding:0; width:70px;">
<option></option>
<?php

if ($_GET["date"] == "")
{
	$date = date("Y-m-d");
}
else
{
	$date = $_GET["date"];
}

$q = mysql_query("SELECT centres FROM operations WHERE user = '$ac[user]'") or die(mysql_error());
$cen = mysql_fetch_row($q);
$centres = explode(",",$cen[0]);
for ($i = 0; $i < count($centres); $i++)
{
	echo "<option>" . $centres[$i] . "</option>";
}
?>
</select>
<input type='text' size='11' id='datepicker2' style="height:20px;" readonly='readonly' value='<?php echo date("d/m/Y", strtotime($date)); ?>' /><input type='hidden' id='datepicker' value='<?php echo $date; ?>' /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<script>
if ( $( "#centre_link" ).val() != "" )
{
	$( "#centre" ).val($( "#centre_link" ).val());
}
</script>

<?php
if ($_GET["centre"] != "")
{
	if (!in_array($_GET["centre"], $centres))
	{
		echo "<script>";
		echo "window.location = 'sales.php';";
		echo "</script>";
	}
}
?>

<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="98%" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th>Sale ID</th>
<th>Final Status</th>
<th>Agent</th>
<th>Sale Type</th>
<th>Last Updated</th>
</tr>
</thead>
<tbody id="details">
<script>
var centre = $( "#centre_link" ),
	date = $( "#datepicker" );
		
$( "#details" ).load('sales_display.php?method=sales&centre=' + centre.val() + '&date=' + date.val());
</script>
</tbody>
</table>
</div></center>

</div>

</div> 
<?php
include "../source/footer.php";
?>
</body>
</html>