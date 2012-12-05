<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid #eee; padding: .3em 5px; text-align: left;}
.ui-dialog_notes { padding: .3em; }
</style>

<script>
function View(centre)
{
	var date1 = $( "#datepicker" ),
		date2 = $( "#datepicker3" );
	
	$( "#display2" ).hide('blind', '', 'slow', function() {
		$( "#display2" ).load("rejections_display2.php?centre=" + centre + "&date1=" + date1.val() + "&date2=" + date2.val(), function() {
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
	$.get("rejections_submit.php" , { method: "notes", id: id }, function(data) {
		$( "#notes" ).val(data);
	});
	$( "#dialog-form_notes" ).dialog( "open" );
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		width: 275,
		height: 100,
		modal: true,
		resizable: false,
		draggable: false,
		show: "blind",
		hide: "blind"
	});
});

function Export()
{
	$( "#dialog-confirm" ).dialog( "open" );
}

function Export_All()
{
	var date1 = $( "#datepicker" ),
		date2 = $( "#datepicker3" );
	window.location = "rejections_submit.php?method=export&centre=All&date1=" + date1.val() + "&date2=" + date2.val();
}

function Export_Centre()
{
	var date1 = $( "#datepicker" ),
		date2 = $( "#datepicker3" ),
		centre = $( "#centre_store" );
	
	if (!centre.val())
	{
		alert("No centre selected");
	}
	else
	{
		window.location = "rejections_submit.php?method=export&centre=" + centre.val() + "&date1=" + date1.val() + "&date2=" + date2.val();
	}
}
</script>

<div id="dialog-form_notes" title="Reason">
<textarea id="notes" disabled="disabled" style="width:400px; height:150px; resize:none;"></textarea>
</div>

<div id="dialog-confirm" title="Export Switch">
<table width="100%" height="55px">
<tr height="100%">
<td valign="middle" align="center"><button onclick="Export_All()" class="btn">All</button></td>
<td valign="middle" align="center"><button onclick="Export_Centre()" class="btn">Centre</button></td>
</tr>
</table>
</div>

<div id="display_loading">
<center><img src="../images/ajax-loader.gif" /><br /><br />
<p>Loading. Please Wait...</p></center>
</div>

<div id="display">
<script>
$( "#display" ).load("rejections_display.php?date1=<?php echo date("Y-m-d"); ?>&date2=<?php echo date("Y-m-d"); ?>",
function() {
	$( "#display_loading" ).hide();
	$( "#display" ).show('blind', '' , 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>