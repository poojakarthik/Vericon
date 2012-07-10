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
	var date = $( "#datepicker" );
	
	window.open("new_process.php?type=Business&date=" + date.val(), 'Business', 'width=1, height=1, x=800');
}
</script>
<script>
function Residential()
{
	var date = $( "#datepicker" );
	
	window.open("new_process.php?type=Residential&date=" + date.val(), 'Residential', 'width=1, height=1, x=800');
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