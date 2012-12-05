<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>

<div id="display">
<script>
$( "#display" ).load('scripts_display.php',
function() {
	$( "#display" ).show('blind', '', 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>