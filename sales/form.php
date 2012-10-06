<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>

<input type="hidden" id="lead_id" value="" />
<div id="display">
<script>
$( "#display" ).load("form_display.php", function() {
	$( "#display" ).show('blind', '' , 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>