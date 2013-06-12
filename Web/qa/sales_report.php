<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
.ui-dialog { padding: .3em; }
div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid #eee; padding: .3em 5px; text-align: left; }
</style>
<script>
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	$( "#dialog-form" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:250,
		height:160,
		modal: true,
		show: "blind",
		hide: "blind",
		buttons: {
			"Yes": function() {
				$( "#display2" ).html("The DSR generation process has begun. Please refresh this page in 2 minutes.");
				$.get("sales_report_process.php", { }, function (data) {
					$( "#dialog-form" ).dialog( "close" );
				});
			},
			"No": function() {
				$( "#dialog-form" ).dialog( "close" );
			}
		}
	});
});

function Generate()
{
	$( "#dialog-form" ).dialog( "open" );
}
</script>
<script>
function Export(folder,file)
{
	var date = $( "#datepicker" );
	window.location = "sales_report_export.php?date=" + date.val() + "&folder=" + folder + "&file=" + file;
}
</script>

<div id="dialog-form" title="Warning">
<span class='ui-icon ui-icon-alert' style='float:left; margin-right:.3em; margin-top:4px'></span>You cannot undo this action.<br /><br />Are you sure you would like to manually trigger the DSR generation?
</div>

<div id="display">
<script>
$( "#display" ).load("sales_report_display.php?centre=All&date=<?php echo date("Y-m-d"); ?>",
function() {
	$( "#display" ).show('blind', '' , 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>