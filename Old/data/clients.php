<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align:left }
div#users-contain table td { border: 1px solid #eee; padding: .3em 5px; text-align:left }
.ui-dialog .ui-state-highlight { padding: .3em; }
.validateTips { border: 1px solid transparent; padding: 0.3em; }
</style>
<script> 
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var id = $( "#group_id" ),
		name = $( "#group_name" ),
		allFields = $( [] ).add( id ).add( name ),
		tips = $( ".validateTips" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#dialog-form" ).dialog({
		autoOpen: false,
		height: 175,
		width: 275,
		modal: true,
		resizable: false,
		draggable: false,
		show: 'blind',
		hide: 'blind',
		buttons: {
			"Submit": function() {
				$.get("clients_submit.php?method=add_group", { id: id.val(), name: name.val() },
				function(data) {
					if (data == "added")
					{
						allFields.val( "" );
						tips.html('<span style="color:#ff0000;">*</span> Required Fields');
						$( "#dialog-form" ).dialog( "close" );
						$( "#display" ).hide('blind', '', 'slow', function() {
							$( "#display" ).load("clients_display.php", function() {
								$( "#display" ).show('blind', '', 'slow');
							});
						});
					}
					else
					{
						updateTips(data);
					}
				});
			},
			Cancel: function() {
				$( this ).dialog( "close" );
				allFields.val( "" );
				tips.html('<span style="color:#ff0000;">*</span> Required Fields');
			}
		},
		close: function() {
			allFields.val( "" );
			tips.html('<span style="color:#ff0000;">*</span> Required Fields');
		}
	});
});

function Add_Group()
{
	$( "#dialog-form" ).dialog( "open" );
}
</script>
<script>
function View(group)
{
	$( "#group_store" ).val(group);
	$( "#display2" ).hide('blind', '', 'slow', function() {
		$( "#display2" ).load("clients_display2.php?group=" + group, function() {
			$( "#display2" ).show('blind', '', 'slow');
		});
	});
}
</script>
<script> 
$(function() {
	$( "#dialog:ui-dialog2" ).dialog( "destroy" );
	
	var id = $( "#campaign_id" ),
		name = $( "#campaign_name" ),
		allFields = $( [] ).add( id ).add( name ),
		tips = $( ".validateTips2" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#dialog-form2" ).dialog({
		autoOpen: false,
		height: 175,
		width: 275,
		modal: true,
		resizable: false,
		draggable: false,
		show: 'blind',
		hide: 'blind',
		buttons: {
			"Submit": function() {
				var group = $( "#group_store" );
				$.get("clients_submit.php?method=add_campaign", { id: id.val(), group: group.val(), name: name.val() },
				function(data) {
					if (data == "added")
					{
						allFields.val( "" );
						tips.html('<span style="color:#ff0000;">*</span> Required Fields');
						$( "#dialog-form2" ).dialog( "close" );
						$( "#display" ).hide('blind', '', 'slow', function() {
							$( "#display" ).load("clients_display.php", function () {
								$( "#display2" ).load("clients_display2.php?group=" + group.val(), function() {
									$( "#display" ).show('blind', '', 'slow');
								});
							});
						});
					}
					else
					{
						updateTips(data);
					}
				});
			},
			Cancel: function() {
				$( this ).dialog( "close" );
				allFields.val( "" );
				tips.html('<span style="color:#ff0000;">*</span> Required Fields');
			}
		},
		close: function() {
			allFields.val( "" );
			tips.html('<span style="color:#ff0000;">*</span> Required Fields');
		}
	});
});

function Add_Campaign()
{
	$( "#dialog-form2" ).dialog( "open" );
}
</script>
<script>
function View_Matrix(campaign)
{
	$( "#campaign_store" ).val(campaign);
	$( "#display" ).hide('blind', '', 'slow', function() {
		$( "#display" ).load("clients_display_plans.php?campaign=" + campaign, function() {
			$( "#display" ).show('blind', '', 'slow');
		});
	});
}
</script>

<div id="dialog-form" title="Add Group">
	<p class="validateTips"><span style="color:#ff0000;">*</span> Required Fields</p>
<table>
<tr>
<td width="65px">ID<span style="color:#ff0000;">*</span> </td>
<td><input id="group_id" type="text" style="width:150px;"></td>
</tr>
<tr>
<td>Name<span style="color:#ff0000;">*</span> </td>
<td><input id="group_name" type="text" style="width:150px;"></td>
</tr>
</table>
</div>

<div id="dialog-form2" title="Add Campaign">
	<p class="validateTips2"><span style="color:#ff0000;">*</span> Required Fields</p>
<table>
<tr>
<td width="65px">ID<span style="color:#ff0000;">*</span> </td>
<td><input id="campaign_id" type="text" style="width:150px;"></td>
</tr>
<tr>
<td>Name<span style="color:#ff0000;">*</span> </td>
<td><input id="campaign_name" type="text" style="width:150px;"></td>
</tr>
</table>
</div>

<input type="hidden" id="group_store" value="" />
<input type="hidden" id="campaign_store" value="" />

<div id="display">
<script>
$( "#display" ).load("clients_display.php", function() {
	$( "#display" ).show("blind", "", "slow");
});
</script>
</div>

<?php
include "../source/footer.php";
?>