<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
</style>

<div id="display">
<script>
$( "#display" ).load('sales_report_display.php?user=<?php echo $ac["user"]; ?>&date1=<?php echo date("Y-m-d"); ?>&date2=<?php echo date("Y-m-d"); ?>', function() {
	$( "#display" ).show('blind', '', 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>