<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .4em 10px; }
</style>
<script>
function Top_Ten()
{
	var method = $( "#method" );
	$( "#top_ten" ).load('top_ten.php?method=' + method.val() + '&centre=<?php echo $ac["centre"]; ?>');
}
</script>

<div id="display">
<script>
$( "#display" ).load("index_display.php?user=<?php echo $ac["user"]; ?>",
function() {
	$( "#display" ).show('blind', '' , 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>