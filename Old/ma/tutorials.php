<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>

<script>
function Play(id)
{
	var l = "play.php?id=" + id;
	window.open(l,'Video','menubar=no,scrollbars=no,width=1050px,height=825px,left=10px,top=10px');
}
</script>

<div id="display">
<script>
$( "#display" ).load('tutorials_display.php?p=<?php echo $p; ?>',
function() {
	$( "#display" ).show('blind', '', 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>