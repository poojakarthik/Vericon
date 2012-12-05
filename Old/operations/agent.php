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
div#users-contain table td { border: 1px solid #eee; padding: .1em 10px; text-align: left; }
.ui-dialog { padding: .3em; }
.ui-dialog2 { padding: .3em; }
.validateTips { border: 1px solid transparent; padding: 0.3em; }
.ui-autocomplete {
	max-height: 100px;
	overflow-y: auto;
	overflow-x: hidden;
	padding-right: 20px;
}
.ui-autocomplete-loading { background: white url('../images/ajax-loader.gif') right center no-repeat; }
</style>
<script>
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	$( "#dialog-form" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:225,
		height:125,
		modal: true,
		show: 'blind',
		hide: 'blind'
	});
});

$(function() {
	$( "#search_box" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "agent_process.php",
				dataType: "json",
				data: {
					method: "search",
					centres : "<?php echo $centres_link; ?>",
					term : request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 2,
		select: function( event, ui ) {
			$( "#display" ).hide('blind','','slow', function() {
				$( "#display" ).load("agent_display3.php?user=" + ui.item.id, function() {
					$( "#display2" ).load("agent_display4.php?user=" + ui.item.id, function() {
						$( "#display" ).show('blind','','slow');
					});
				});
			});
			$( "#dialog-form" ).dialog( "close" );
		}
	});
});

function Search()
{
	$( "#search_box" ).val("");
	$( "#dialog-form" ).dialog( "open" );
}
</script>
<script>
function View_Agents(centre)
{
	$( "#centre_store" ).val(centre);
	$( "#display2" ).hide('blind','','slow', function() {
		$( "#display2" ).load("agent_display2.php?centre=" + centre, function() {
			$( "#display2" ).show('blind','','slow');
		});
	});
}

function View_Agent(agent)
{
	$( "#display" ).hide('blind','','slow', function() {
		$( "#display" ).load("agent_display3.php?user=" + agent, function() {
			$( "#display2" ).load("agent_display4.php?user=" + agent, function() {
				$( "#display" ).show('blind','','slow');
			});
		});
	});
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog2" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:700,
		height:240,
		modal: true,
		show: 'blind',
		hide: 'blind'
	});
});

function View(we)
{
	var user = $( "#user" ),
		d = we.split("-"),
		date = d[2] + "/" + d[1] + "/" + d[0];
	
	$( ".we" ).html(date);
	$( "#display4" ).html('<br><br><br><center><img src="../images/ajax-loader.gif" /><br /><br /><p>Loading. Please Wait...</p></center>');
	$( "#display4" ).load('agent_display5.php?user=' + user.val() + '&we=' + we);
	$( "#dialog-confirm" ).dialog( "open" );
}
</script>
<script>
function Back()
{
	var centre = $( "#centre_store" );
	
	$( "#display" ).hide('blind','','slow', function() {
		$( "#display" ).load('agent_display.php?centres=<?php echo $centres_link; ?>', function() {
			$( "#display2" ).load("agent_display2.php?centre=" + centre.val(), function() {
				$( "#display" ).show('blind','','slow');
			});
		});
	});
}
</script>

<div id="dialog-form" title="Search">
<p class="validateTips">Type the agent's name below to search</p><br />
<input type="text" style="width:200px" id="search_box" value="" />
</div>

<div id="dialog-confirm" title="Daily Breakdown for W.E. <span class='we'></span>">
<div id="display4"></div>
</div>

<input type="hidden" id="centre_store" value="">

<div id="display">
<script>
$( "#display" ).load('agent_display.php?centres=<?php echo $centres_link; ?>', function() {
	$( "#display" ).show('blind', '', 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>