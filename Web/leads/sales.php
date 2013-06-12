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
div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align:left }
div#users-contain table td { border: 1px solid #eee; padding: .3em 5px; text-align:left }
</style>

<script>
function Export()
{
	var date = $( "#datepicker" );
	
	window.location = "sales_export.php?date=" + date.val();
}
</script>

<div id="display_loading">
<center><img src="../images/ajax-loader.gif" /><br /><br />
<p>Loading. Please Wait...</p></center>
</div>

<div id="display">
<script>
$( "#display" ).load('sales_display.php?centres=<?php echo $centres; ?>&date=<?php echo date("Y-m-d"); ?>',
function() {
	$( "#display_loading" ).hide();
	$( "#display" ).show('blind', '', 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>