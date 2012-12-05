<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<link rel="stylesheet" type="text/css" href="upload/uploadify.css" />
<script type="text/javascript" src="upload/jquery.uploadify-3.1.min.js"></script>
<style>
div#users-contain table { margin: 1em 0; margin-bottom:0; border-collapse: collapse; width:98% }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid #eee; padding: .1em 5px; text-align: left; }
</style>

<script>
function Centre()
{
	var centre = $( "#centre" ),
		date = $( "#datepicker" );
	
	$( "#display" ).hide('blind', '', 'slow', function() {
		$( "#display" ).load('dialler_hours_display.php?centre=' + centre.val() + '&date=' + date.val(), function() {
			$( "#display" ).show('blind', '', 'slow');
		});
	});
}
</script>
<script>
function Export_Hours()
{
	var centre = $( "#centre" ),
		date = $( "#datepicker" );
		
	window.location = "dialler_hours_process.php?method=export_hours&centre=" + centre.val() + '&date=' + date.val();
}
</script>

<div id="display">
<script>
$( "#display" ).load('dialler_hours_display.php?centre=Centre&date=<?php echo date("Y-m-d"); ?>', function() {
	$( "#display" ).show('blind', '', 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>