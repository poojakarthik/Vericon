<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid #eee; padding: .3em 5px; text-align: left; }
</style>
<script>
$(function() {
	$( "#dialog:ui-dialog_view_switch" ).dialog( "destroy" );
	
	$( "#dialog-confirm_view_switch" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width: 375,
		height: 100,
		modal: true,
		show: "blind",
		hide: "blind"
	});
});

function View(type)
{
	var date = $( "#store_date" ),
		centre = $( "#store_centre" );
	
	$( "#store_type" ).val(type);
	$( "#dialog-confirm_view_switch" ).dialog( "close" );
	$( "#display2" ).hide('blind', '' , 'slow', function() {
		$( "#display2" ).load('sales_display2.php?date=' + date.val() + '&centre=' + centre.val() + '&type=' + type,
		function() {
			$( "#display2" ).show('blind', '' , 'slow');
		});
	});
}

function View_Switch(centre)
{
	$( "#store_centre" ).val(centre);
	$( "#dialog-confirm_view_switch" ).dialog( "open" );
}
</script>

<div id="dialog-confirm_view_switch" title="Sale Status Type">
<table width="100%" height="55px">
<tr height="100%">
<td valign="middle" align="center"><button onclick="View('Pending')" class="btn">Pending</button></td>
<td valign="middle" align="center"><button onclick="View('Approved')" class="btn">Approved</button></td>
<td valign="middle" align="center"><button onclick="View('Rejected')" class="btn">Rejected</button></td>
</tr>
</table>
</div>

<input type="hidden" id="store_date" value="" />
<input type="hidden" id="store_centre" value="" />
<input type="hidden" id="store_type" value="" />

<div id="display">
<script>
$( "#display" ).load('sales_display.php?date=<?php echo date("Y-m-d"); ?>',
function() {
	$( "#display" ).show('blind', '' , 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>