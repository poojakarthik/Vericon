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

$date = date("Y") . "-" . date("m") . "-01";
$week = date("W", strtotime($date));
$year = date("Y", strtotime($date));
$default_date1 = date("Y-m-d", strtotime($year . "W" . $week . "1"));
?>
<style>
div#users-contain table { margin: 1em 0; margin-bottom:0; border-collapse: collapse; width:98% }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid #eee; padding: .1em 10px; text-align: left; }
.ui-dialog { padding: .3em; }
</style>
<script>
function Display(centre)
{
	var date1 = $( "#datepicker" ),
		date2 = $( "#datepicker3" );
	
	$( "#display2" ).hide('blind','','slow', function() {
		$( "#display2" ).load('centre_display2.php?centre=' + centre + '&date1=' + date1.val() + '&date2=' + date2.val(), function() {
			$( "#display2" ).show('blind','','slow');
		});
	});
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:650,
		height:250,
		modal: true,
		show: 'blind',
		hide: 'blind'
	});
});

function View(centre,we)
{
	var d = we.split("-");
		date = d[2] + "/" + d[1] + "/" + d[0];
	
	$( ".we" ).html(date);
	$( "#display3" ).html('<br><br><br><center><img src="../images/ajax-loader.gif" /><br /><br /><p>Loading. Please Wait...</p></center>');
	$( "#display3" ).load('centre_display3.php?centre=' + centre + '&we=' + we);
	$( "#dialog-confirm" ).dialog( "open" );
}
</script>
<script>
function Export()
{
	var date1 = $( "#datepicker" ),
		date2 = $( "#datepicker3" );
	
	window.location = 'centre_export.php?centre=<?php echo $centres_link; ?>&date1=' + date1.val() + '&date2=' + date2.val();
}

function Daily_Export(date)
{
	var centre = $( "#centre" );
	
	window.location = "../sales/timesheet_export.php?centre=" + centre.val() + "&date=" + date;
}
</script>

<div id="dialog-confirm" title="Daily Breakdown for W.E. <span class='we'></span>">
<div id="display3">
</div>
</div>

<div id="display_loading">
<center><img src="../images/ajax-loader.gif" /><br /><br />
<p>Loading. Please Wait...</p></center>
</div>

<div id="display">
<script>
$( "#display" ).load('centre_display.php?centres=<?php echo $centres_link; ?>&date1=<?php echo $default_date1; ?>&date2=<?php echo date("Y-m-d"); ?>',
function() {
	$( "#display_loading" ).hide();
	$( "#display" ).show('blind', '', 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>