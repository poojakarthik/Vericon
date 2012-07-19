<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align:left }
div#users-contain table td { border: 1px solid #eee; padding: .3em 5px; text-align:left }
</style>

<script>
function Business()
{
	window.location = "new_export.php?method=Business&date=" + $( "#datepicker" ).val();
}
</script>
<script>
function Residential()
{
	window.location = "new_export.php?method=Residential&date=" + $( "#datepicker" ).val();
}
</script>

<div id="display">
<script>
$( "#display" ).load('new_display.php?date=<?php echo date("Y-m-d", strtotime("-1day")); ?>',
function() {
	$( "#display" ).show('blind', '', 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>