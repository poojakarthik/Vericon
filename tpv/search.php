<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
.ui-dialog_search { padding: .3em; }
.ui-dialog_edit_switch { padding: .3em; }
</style>
<script>
$(function() {
	$( "#dialog:ui-dialog_search" ).dialog( "destroy" );

	$( "#dialog-form_search" ).dialog({
		autoOpen: true,
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
					query = $( "#query" );
					
				$( "#results" ).html("<center><br><br><img src='../images/ajax-loader.gif'> Searching...");
				$( "#results" ).load("search_results.php?method=" +  method.val() + "&query=" + query.val());
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
$(function() {
	$( "#dialog:ui-dialog_edit_switch" ).dialog( "destroy" );

	$( "#dialog-form_edit_switch" ).dialog({
		autoOpen: false,
		width: 275,
		height: 100,
		modal: true,
		resizable: false,
		draggable: false,
		show: 'blind',
		hide: 'blind'
	});
});

function Edit_Switch(id)
{
	$( "#dialog-form_edit_switch" ).dialog( "open" );
}
</script>
<script>
function View_Search(id)
{
	$( "#display" ).hide('blind', '', 'slow', function() {
		$( "#display" ).load('search_view.php?id=' + id, function() {
			$( "#display" ).show('blind', '', 'slow');
		});
	});
}

function Edit_Search(id)
{
	$( "#display" ).hide('blind', '', 'slow', function() {
		$( "#display" ).load('search_edit.php?id=' + id, function() {
			$( "#display" ).show('blind', '', 'slow');
		});
	});
}

function Cancel_Search()
{
	var method = $( "#method" ),
		query = $( "#query" );
		
	$( "#display" ).hide('blind', '', 'slow', function() {
		$( "#display" ).load('search_display.php', function() {
			$( "#results" ).load("search_results.php?method=" +  method.val() + "&query=" + query.val(), function() {
				$( "#display" ).show('blind', '', 'slow');
			});
		});
	});
	
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

<div id="dialog-form_edit_switch" title="Edit Switch">
<br>
<center>Coming Soon</center>
</div>

<div id="display">
<script>
$( "#display" ).load('search_display.php',
function() {
	$( "#display" ).show('blind', '', 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>