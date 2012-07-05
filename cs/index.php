<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>

<script>
function Display(page)
{
	$( "#display" ).load("index_display.php?page=" + page);
}
</script>

<div id="display">
<script>
$( "#display" ).load('index_display.php',
function() {
	$( "#display" ).show('blind', '', 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>