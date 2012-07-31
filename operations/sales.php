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
		$( "#display2" ).load('sales_display2.php?method=sales&centre=' + centre + '&date=' + date.val(), function() {
			$( "#display2" ).show('blind', '' , 'slow');
		});
	});
}
</script>
<script> //notes button
$(function() {
	$( "#dialog:ui-dialog_notes" ).dialog( "destroy" );
	
	$( "#dialog-form_notes" ).dialog({
		autoOpen: false,
		height: 200,
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
	$.get("sales_display2.php" , { method: "notes", id: id }, function(data) {
		$( "#notes" ).val(data);
	});
	$( "#dialog-form_notes" ).dialog( "open" );
}
</script>

<div id="dialog-form_notes" title="Notes">
<textarea id="notes" disabled="disabled" style="width:400px; height:150px; resize:none;"></textarea>
</div>

<div id="display">
<script>
$( "#display" ).load('sales_display.php?centres=<?php echo $centres_link; ?>&date=<?php echo date("Y-m-d"); ?>',
function() {
	$( "#display" ).show('blind', '' , 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>