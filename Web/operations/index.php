<?php
include "../auth/iprestrict.php";
include "../source/header.php";

$q = mysql_query("SELECT centres FROM vericon.operations WHERE user = '$ac[user]'") or die(mysql_error());
$cen = mysql_fetch_row($q);
if ($cen[0] == "All")
{
	$centres = array();
	$q1 = mysql_query("SELECT centre FROM vericon.centres WHERE status = 'Enabled' ORDER BY centre ASC") or die(mysql_error());
	while ($centre = mysql_fetch_row($q1))
	{
		array_push($centres, $centre[0]);
	}
	$centres_link = implode(",", $centres);
}
elseif ($cen[0] == "Captive" || $cen[0] == "Self")
{
	$centres = array();
	$q1 = mysql_query("SELECT centre FROM vericon.centres WHERE status = 'Enabled' AND type = '$cen[0]' ORDER BY centre ASC") or die(mysql_error());
	while ($centre = mysql_fetch_row($q1))
	{
		array_push($centres, $centre[0]);
	}
	$centres_link = implode(",", $centres);
}
else
{
	$centres_link = $cen[0];
}
?>
<style>
div#users-contain table { margin: 1em 0; margin-bottom:0; border-collapse: collapse; }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
</style>

<div id="display_loading">
<center><img src="../images/ajax-loader.gif" /><br /><br />
<p>Loading Sale Stats. Please Wait...</p></center>
</div>

<div id="display">
<script>
var centres = "<?php echo $centres_link; ?>",
	date1 = "<?php echo date("Y-m-d"); ?>",
	date2 = "<?php echo date("Y-m-d"); ?>";

$( "#display" ).load('index_display.php?centres=' + centres + '&date1=' + date1 + '&date2=' + date2,
function() {
	$( "#display_loading" ).hide();
	$( "#display" ).show('blind', '' , 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>