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
div#users-contain table td { border: 1px solid #eee; padding: .2em 5px; text-align: left; }
</style>
<script>
function View(centre)
{
	var date = $( "#datepicker" );
	
	$( "#display3" ).hide('blind', '' , 'slow', function() {
		$( "#display3" ).load('sales_display3.php?method=sales&centre=' + centre + '&date=' + date.val(), function() {
			$( "#display3" ).show('blind', '' , 'slow');
		});
	});
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog_search" ).dialog( "destroy" );

	$( "#dialog-form_search" ).dialog({
		autoOpen: false,
		height: 160,
		width: 225,
		modal: true,
		resizable: false,
		draggable: false,
		show: 'blind',
		hide: 'blind',
		buttons: {
			"Search": function() {
				var method = $( "#method" ),
					query = $( "#query" ),
					centre = "<?php echo $centres_link; ?>";
					
				$( "#results" ).html("<center><br><br><img src='../images/ajax-loader.gif'> Searching...");
				$( "#results" ).load("sales_results.php?method=" +  method.val() + "&query=" + query.val() + "&centre=" + centre);
				$( "#dialog-form_search" ).dialog( "close" );
			},
			"Cancel": function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
		}
	});
});

function Search()
{
	$( "#query" ).val("");
	$( "#dialog-form_search" ).dialog( "open" );
}
</script>
<script>
function View_Search(id)
{
	$.get('sales_submit.php?method=country', { id: id }, function(data) {
		$( "#display" ).hide('blind', '', 'slow', function() {
			$( "#display" ).load('sales_view_' + data + '.php?id=' + id, function() {
				$( "#display" ).show('blind', '', 'slow');
			});
		});
	});
}

function Cancel_Search()
{
	var method = $( "#method" ),
		query = $( "#query" ),
		centre = "<?php echo $centres_link; ?>",
		date = $( "#date_store" );
	
	
	$( "#display" ).hide('blind', '', 'slow', function() {
		$( "#display_loading" ).show();
		$( "#display" ).load('sales_display.php?centres=' + centre + '&date=' + date.val(), function() {
			$( "#display2" ).load('sales_display2.php?centres=' + centre + '&date=' + date.val(), function() {
				$( "#results" ).load("sales_results.php?method=" +  method.val() + "&query=" + query.val() + "&centre=" + centre, function() {
					$( "#display_loading2" ).hide();
					$( "#display_loading" ).hide();
					$( "#display" ).show('blind', '', 'slow');
				});
			});
		});
	});
	
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog_sale_notes" ).dialog( "destroy" );
	
	$( "#dialog-form_sale_notes" ).dialog({
		autoOpen: false,
		height: 200,
		width: 425,
		modal: true,
		resizable: false,
		draggable: false,
		show: 'blind',
		hide: 'blind'
	});
});

function Sale_Notes(id)
{
	$.get("sales_submit.php", { method: "notes", id: id }, function(data) {
		$( "#sale_notes" ).val(data);
	});
	$( "#dialog-form_sale_notes" ).dialog( "open" );
}
</script>

<div id="dialog-form_search" title="Search">
<br><table>
<tr>
<td width="50px">Method </td>
<td><select id="method" style="width:126px;">
<option value="line">Phone Number</option>
<option value="id">Sale ID</option>
<option value="lead">Lead ID</option>
</select></td>
</tr>
<tr>
<td>Query </td>
<td><input type="text" id="query" value="" style="width:125px;" /></td>
</tr>
</table>
</div>

<div id="dialog-form_sale_notes" title="Notes">
<textarea readonly="readonly" id="sale_notes" style="width:400px; height:150px; resize:none;">
</textarea>
</div>

<input type="hidden" id="date_store" value="<?php echo date("Y-m-d"); ?>" />

<div id="display_loading">
<center><img src="../images/ajax-loader.gif" /><br /><br />
<p>Loading Sales. Please Wait...</p></center>
</div>

<div id="display">
<script>
$( "#display" ).hide();
$( "#display" ).load('sales_display.php?centres=<?php echo $centres_link; ?>&date=<?php echo date("Y-m-d"); ?>',
function() {
	$( "#display_loading2" ).hide();
	$( "#display2" ).load('sales_display2.php?centres=<?php echo $centres_link; ?>&date=<?php echo date("Y-m-d"); ?>', function() {
		$( "#display_loading" ).hide();
		$( "#display" ).show('blind', '' , 'slow');
	});
});
</script>
</div>

<?php
include "../source/footer.php";
?>