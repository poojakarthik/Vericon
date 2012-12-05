<?php
include "../auth/iprestrict.php";
include "../source/header.php";

$q = mysql_query("SELECT centres FROM vericon.operations WHERE user = '$ac[user]'") or die(mysql_error());
$cen = mysql_fetch_row($q);
if ($cen[0] == "All")
{
	$centres = array();
	$q1 = mysql_query("SELECT centre FROM vericon.centres WHERE status = 'Enabled' ORDER BY centre ASC") or die(mysql_error());
	while ($centre = mysql_fetch_row($q1))
	{
		array_push($centres, $centre[0]);
	}
	$centres_link = implode(",", $centres);
}
elseif ($cen[0] == "Captive" || $cen[0] == "Self")
{
	$centres = array();
	$q1 = mysql_query("SELECT centre FROM vericon.centres WHERE status = 'Enabled' AND type = '$cen[0]' ORDER BY centre ASC") or die(mysql_error());
	while ($centre = mysql_fetch_row($q1))
	{
		array_push($centres, $centre[0]);
	}
	$centres_link = implode(",", $centres);
}
else
{
	$centres_link = $cen[0];
}
?>
<style>
div#users-contain table { margin: 1em 0; margin-bottom:0; border-collapse: collapse; }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid #eee; padding: .2em 5px; text-align: left; }
</style>
<script>
function View(centre)
{
	var date = $( "#datepicker" );
	
	$( "#display2" ).hide('blind', '' , 'slow', function() {
		$( "#display2" ).load('rejections_display2.php?centre=' + centre + '&date=' + date.val(), function() {
			$( "#display2" ).show('blind', '' , 'slow');
		});
	});
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog_notes" ).dialog( "destroy" );
	
	$( "#dialog-form_notes" ).dialog({
		autoOpen: false,
		height: 250,
		width: 425,
		modal: true,
		resizable: false,
		draggable: false,
		show: "blind",
		hide: "blind"
	});
});

function Notes(id)
{
	$.get("rejections_submit.php" , { method: "agent", id: id }, function(data) { $( ".agent" ).text(data); });
	$.get("rejections_submit.php" , { method: "sale_date", id: id }, function(data) { $( ".sale_date" ).text(data); });
	$.get("rejections_submit.php" , { method: "notes", id: id }, function(data) { $( "#notes" ).val(data); });
	$( "#dialog-form_notes" ).dialog( "open" );
}
</script>

<div id="dialog-form_notes" title="Reason">
<table width="100%">
<tr>
<td width="80px"><b>Agent</b></td>
<td><span class="agent"></span></td>
</tr>
<tr>
<td width="80px"><b>Date</b></td>
<td><span class="sale_date"></span></td>
</tr>
<tr>
<td><b>Reason</b></td>
</tr>
<tr>
<td colspan="2"><textarea id="notes" disabled="disabled" style="width:400px; height:150px; resize:none;"></textarea></td>
</tr>
</table>
</div>

<div id="display_loading">
<center><img src="../images/ajax-loader.gif" /><br /><br />
<p>Loading. Please Wait...</p></center>
</div>

<div id="display">
<script>
$( "#display" ).load('rejections_display.php?centres=<?php echo $centres_link; ?>&date=<?php echo date("Y-m-d"); ?>',
function() {
	$( "#display_loading" ).hide();
	$( "#display" ).show('blind', '', 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>