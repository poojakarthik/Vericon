<?php
include "../auth/iprestrict.php";
include "../source/header.php";

$q = mysql_query("SELECT centre FROM vericon.centres ORDER BY centre ASC") or die(mysql_error());
while($centre = mysql_fetch_row($q))
{
	$centres .= $centre[0] . ",";
}
$centres = substr($centres,0,-1);
?>
<style>
div#users-contain table { margin: 1em 0; margin-bottom:0; border-collapse: collapse; }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid #eee; padding: .3em 5px; text-align: left; }
</style>

<div id="display">
<script>
var centres = "<?php echo $centres; ?>",
	date1 = "<?php echo date("Y-m-d"); ?>",
	date2 = "<?php echo date("Y-m-d"); ?>";

$( "#display" ).load('index_display.php?centres=' + centres + '&date1=' + date1 + '&date2=' + date2,
function() {
	$( "#display" ).show('blind', '' , 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>