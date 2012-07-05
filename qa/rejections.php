<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
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
function Display()
{
	var centre = $( "#centre" ),
		date = $( "#datepicker" );
		
	$( "#stats" ).load('admin_rejection_submit.php?method=stats&centre=' + centre.val() + '&date=' + date.val());
	$( "#download" ).load('admin_rejection_submit.php?method=download&centre=' + centre.val() + '&date=' + date.val());
}
</script>
<p><img src="../images/rejection_report_header.png" width="170" height="25" style="margin-left:3px;" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p>

<table style="margin-top:5px;">
<tr>
<td>Centre </td>
<td width="80px"><select id="centre" style="height:20px; margin:0; padding:0; width:70px;">
<option>All</option>
<?php
$q = mysql_query("SELECT * FROM centres WHERE status = 'Enabled' ORDER BY centre ASC") or die(mysql_error());
while ($centres = mysql_fetch_row($q))
{
	echo "<option>" . $centres[0] . "</option>";
}
?>
</select>
</td>
<td>Date</td>
<td width="120px"><input type='text' size='11' id='datepicker2' readonly='readonly' style="height:20px;" value='<?php echo date("d/m/Y"); ?>' /><input type='hidden' id='datepicker' value='<?php echo date("Y-m-d"); ?>' /></td>
<td><input type="button" onclick="Display()" class="search" value="" /></td>
</tr>
</table>

<center>
<div id="users-contain" class="ui-widget" style="width:80%">
<table id="users" class="ui-widget ui-widget-content">
<thead>
<tr class="ui-widget-header ">
<th>Campaign</th>
<th style="text-align:center;">Total Sales</th>
<th style="text-align:center;">Reworks</th>
<th style="text-align:center;">Pending</th>
<th style="text-align:center;">Approved</th>
<th style="text-align:center;">Rejected</th>
</tr>
</thead>
<tbody id="stats">
<script>
var centre = $( "#centre" ),
	date = $( "#datepicker" );

$( "#stats" ).load('admin_rejection_submit.php?method=stats&centre=' + centre.val() + '&date=' + date.val());
</script>
</tbody>
</table>
</div>
</center>

<div id="download">
<script>
var centre = $( "#centre" ),
	date = $( "#datepicker" );

$( "#download" ).load('admin_rejection_submit.php?method=download&centre=' + centre.val() + '&date=' + date.val());
</script>
</div>

<?php
include "../source/footer.php";
?>