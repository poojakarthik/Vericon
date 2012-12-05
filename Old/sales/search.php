<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
.ui-dialog_search { padding: .3em; }
.ui-dialog_edit_switch { padding: .3em; }
.ui-dialog_edit_error { padding: .3em; }
.ui-dialog_edit_status { padding: .3em; }
.validateTipsStatus { border: 1px solid transparent; padding: 0.3em; }
.ui-state-highlight { padding: .3em; }
</style>
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
					centre = "<?php echo $ac["centre"]; ?>";
					
				$( "#results" ).html("<center><br><br><img src='../images/ajax-loader.gif'> Searching...");
				$( "#results" ).load("search_results.php?method=" +  method.val() + "&query=" + query.val() + "&centre=" + centre);
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
	var centre = "<?php echo $ac["centre"]; ?>";
	$.get("search_submit.php", { method: "edit_check", id: id, centre: centre }, function(data) {
		if (data == "valid")
		{
			$( "#dialog-form_edit_switch" ).dialog( "open" );
			$( "#id_store" ).val(id);
		}
		else
		{
			$( ".edit_error" ).text(data);
			$( "#dialog-form_edit_error" ).dialog( "open" );
		}
	});
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog_edit_error" ).dialog( "destroy" );

	$( "#dialog-form_edit_error" ).dialog({
		autoOpen: false,
		width:250,
		height:100,
		modal: true,
		resizable: false,
		draggable: false,
		show: 'blind',
		hide: 'blind'
	});
});
</script>
<script>
function View_Search(id)
{
	$.get('search_submit.php?method=country', { id: id }, function(data) {
		$( "#display" ).hide('blind', '', 'slow', function() {
			$( "#display" ).load('search_view_' + data + '.php?id=' + id, function() {
				$( "#display" ).show('blind', '', 'slow');
			});
		});
	});
}

function Cancel_Search()
{
	var method = $( "#method" ),
		query = $( "#query" ),
		centre = "<?php echo $ac["centre"]; ?>";
		
	$( "#display" ).hide('blind', '', 'slow', function() {
		$( "#display" ).load('search_display.php', function() {
			$( "#results" ).load("search_results.php?method=" +  method.val() + "&query=" + query.val() + "&centre=" + centre, function() {
				$( "#display" ).show('blind', '', 'slow');
			});
		});
	});
	
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog_edit_status" ).dialog( "destroy" );
	
	var tips = $( ".validateTipsStatus" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	
	$( "#dialog-form_edit_status" ).dialog({
		autoOpen: false,
		height: 250,
		width: 425,
		modal: true,
		resizable: false,
		draggable: false,
		show: 'blind',
		hide: 'blind',
		buttons: {
			"Submit": function() {
				var id = $( "#id_store" ),
					user = "<?php echo $ac["user"]; ?>",
					status = $( "#edit_status" ),
					note = $( "#edit_status_note" ),
					method = $( "#method" ),
					query = $( "#query" ),
					centre = "<?php echo $ac["centre"]; ?>";
				
				$.get("search_submit.php", { method: "edit_status", id: id.val(), user: user, status: status.val(), note: note.val() }, function(data) {
					if (data == "done")
					{
						$( "#dialog-form_edit_status" ).dialog( "close" );
						$( "#results" ).html("<center><br><br><img src='../images/ajax-loader.gif'> Loading...");
						$( "#results" ).load("search_results.php?method=" +  method.val() + "&query=" + query.val() + "&centre=" + centre);
					}
					else
					{
						updateTips(data);
					}
				});
			},
			"Cancel": function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

function Edit_Status()
{
	var id = $( "#id_store" );
	
	$.get("search_submit.php", { method: "get_status", id: id.val() }, function(data) { $( "#edit_status" ).val(data); });
	$( "#edit_status_note" ).val("");
	$( "#dialog-form_edit_switch" ).dialog( "close" );
	$( ".validateTipsStatus" ).text("All fields are required");
	$( "#dialog-form_edit_status" ).dialog( "open" );
}
</script>
<script>
function Edit_Details()
{
	var id = $( "#id_store" ).val();
	
	$.get('search_submit.php?method=country', { id: id }, function(data) {
		$( "#dialog-form_edit_switch" ).dialog( "close" );
		$( "#display" ).hide('blind', '', 'slow', function() {
			$( "#display" ).load('search_edit_' + data + '.php?id=' + id, function() {
				$( "#packages" ).load('../tpv/packages_' + data + '.php?id=' + id, function() {
					$( "#display" ).show('blind', '' , 'slow');
				});
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

<div id="dialog-form_edit_error" title="Error!">
<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><span class="edit_error"></span></p>
</div>

<div id="dialog-form_edit_switch" title="Edit Switch">
<table width="100%" height="55px">
<tr height="100%">
<td valign="middle" align="center"><button onclick="Edit_Status()" class="btn">Status</button></td>
<td valign="middle" align="center"><button onclick="Edit_Details()" class="btn">Details</button></td>
</tr>
</table>
</div>

<div id="dialog-form_edit_status" title="Change Status">
<p class="validateTipsStatus">All fields are required</p><br />
<table>
<tr>
<td width="50px">Status </td>
<td><select id="edit_status" style="width:120px;">
<option></option>
<option>Approved</option>
<option>Declined</option>
<option>Line Issue</option>
</select></td>
</tr>
<tr>
<td width="50px">Note </td>
<td><textarea id="edit_status_note" rows="5" style="width:350px; resize:none;"></textarea></td>
</tr>
</table>
</div>

<input type="hidden" id="id_store" value="" />
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