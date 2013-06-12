<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid #eee; padding: .6em 5px; text-align: left; }
</style>

<div id="display">
<script>
$( "#display" ).load("index_display.php",
function() {
	$( "#display" ).show('blind', '' , 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>