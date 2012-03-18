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
		maxDate: "0d" });
});
</script>
<script>
function Teams()
{
	if ($( "#centre" ).val() == "CC12")
	{
		$( "#teams" ).removeAttr('style');
	}
	else
	{
		$( "#teams" ).attr('style','display:none;');
	}
}
</script>
<script>
function Display()
{
	var centre = $( "#centre" ),
		team = $( "#team" ),
		date = $( "#datepicker" );
		
	$( "#details" ).load('sales_display.php?method=sales&centre=' + centre.val() + '&team=' + team.val() + '&date=' + date.val());
	
	$( "#results" ).removeAttr('style');
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

<table style="margin-top:5px;">
<tr>
<td>Centre </td>
<td width="80px"><select id="centre" onchange="Teams()" style="height:auto; margin:0; padding:0; width:70px;">
<option></option>
<?php
$q = mysql_query("SELECT centres FROM operations WHERE user = '$ac[user]'") or die(mysql_error());
$cen = mysql_fetch_row($q);
$centres = explode(",",$cen[0]);
for ($i = 0; $i < count($centres); $i++)
{
	echo "<option>" . $centres[$i] . "</option>";
}
?>
</select>
</td>
<td width="80px" id="teams" style="display:none;"><select id="team" style="height:auto; margin:0; padding:0; width:70px;">
<option></option>
<option>Damith</option>
<option>Daniel</option>
<option>Liam</option>
<option>Sanu</option>
</select></td>
<td>Date</td>
<td width="120px"><input type='text' size='11' id='datepicker2' readonly='readonly' value='<?php echo date("d/m/Y"); ?>' /><input type='hidden' id='datepicker' value='<?php echo date("Y-m-d"); ?>' /></td>
<td><input type="button" onclick="Display()" class="search" value="" /></td>
</tr>
</table>

<div id="results" style="display:none;">
<p><img src="../images/sale_details_header2.png" width="125" height="25" style="margin-left:3px;" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p>
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="100%">
<thead>
<tr class="ui-widget-header ">
<th>Sale ID</th>
<th>Creation Timestamp</th>
<th>Sale Timestamp</th>
</tr>
</thead>
<tbody id="details">
</tbody>
</table>
</div>
</div>

</div>

</div> 
<?php
include "../source/footer.php";
?>
</body>
</html>