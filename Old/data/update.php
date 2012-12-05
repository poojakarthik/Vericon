<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<link rel="stylesheet" type="text/css" href="upload/uploadify.css" />
<script type="text/javascript" src="upload/jquery.uploadify-3.1.min.js"></script>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align:left }
div#users-contain table td { border: 1px solid #eee; padding: .3em 5px; text-align:left }
</style>
<script>
function Business()
{
	window.location = "update_export.php?method=Business";
}
</script>
<script>
function Residential()
{
	window.location = "update_export.php?method=Residential";
}
</script>

<div id="display">
<script>
$( "#display" ).load('update_display.php', function() {
	$( "#display" ).show('blind', '', 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>