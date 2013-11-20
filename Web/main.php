<?php
include "auth/iprestrict.php";
include "source/header.php";
?>
<style>
#vericon_portals{
width:740px;
height:162px;
background-image:url('../images/vericon_portals_bg.png');
background-repeat:no-repeat;
margin-left:auto;
margin-right:auto;
margin-top:0px;
}
</style>
<script>
function Go(link)
{
	if (keypressed == 91 || keypressed == 92 || keypressed == 17) {
		return true;
	}
	$( "#display" ).hide('blind', '' , 'slow', function() {
		location.href = "/" + link + "/";
	});
}
</script>

<div id="display">
<script>
$( "#display" ).load("main_display.php?p=<?php echo $p; ?>",
function() {
	$( "#display" ).show('blind', '' , 'slow');
});
</script>
</div>

<?php
include "source/footer.php";
?>