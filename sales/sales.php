<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
</style>
<script>
function Get_Sale()
{
	var id = $( "#id" ),
	centre = "<?php echo $ac["centre"]; ?>";
	
	$.get("sales_submit.php?method=get", { id: id.val(), centre: centre}, function(data) {
		if (data == "valid")
		{
			$( ".error" ).html('');
			$( "#id_store" ).val(id.val());
			$( "#display" ).hide('blind', '' , 'slow', function() {
				$( "#display" ).load("sales_display.php?method=details&id=" + id.val(),
				function() {
					$( "#display" ).show('blind', '' , 'slow');
				});
			});
		}
		else
		{
			$( ".error" ).html(data);
		}
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

function Notes()
{
	id = $( "#id_store" );
	$.get("sales_submit.php" , { method: "notes", id: id.val() }, function(data) {
		$( "#notes" ).val(data);
	});
	$( "#dialog-form_notes" ).dialog( "open" );
}

function Notes_View(id)
{
	$.get("sales_submit.php" , { method: "notes", id: id }, function(data) {
		$( "#notes" ).val(data);
	});
	$( "#dialog-form_notes" ).dialog( "open" );
}
</script>
<script> //cancel form
function Cancel()
{
	$( "#display" ).hide('blind', '' , 'slow', function() {
		window.location = "sales.php";
	});
}
</script>

<div id="dialog-form_notes" title="Notes">
<textarea id="notes" disabled="disabled" style="width:400px; height:150px; resize:none;"></textarea>
</div>

<input type="hidden" id="id_store" value="" />

<div id="display">
<script>
$( "#display" ).load("sales_display.php?method=init&user=<?php echo $ac["user"]; ?>",
function() {
	$( "#display" ).show('blind', '' , 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>