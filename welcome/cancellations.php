<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: center; }
</style>
<script>
function Export()
{
	date = $( "#datepicker" );
	
	window.location = "cancellations_export.php?date=" + date.val();
}
</script>

<div id="display">
<script>
$( "#display" ).load('cancellations_display.php?date=<?php echo date("Y-m-d"); ?>',
function() {
	$( "#display" ).show('blind', '', 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>