<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align:left }
div#users-contain table td { border: 1px solid #eee; padding: .3em 5px; text-align:left }
.ui-dialog .ui-state-highlight { padding: .3em; }
.validateTips { border: 1px solid transparent; padding: 0.3em; }
.validateTips2 { border: 1px solid transparent; padding: 0.3em; }
</style>
<script> //add centre
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var centre = $( "#centre" ),
		campaign = $( "#campaign" ),
		type = $( "#type" ),
		leads = $( "#leads" ),
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
		height: 210,
		width: 275,
		modal: true,
		resizable: false,
		draggable: false,
		show: 'blind',
		hide: 'blind',
		buttons: {
			"Submit": function() {
				$.get("centres_process.php?method=add", { centre: centre.val(), campaign: campaign.val(), type: type.val(), leads: leads.val() }, function(data) {
					if (data == "added")
					{
						$( "#dialog-form" ).dialog( "close" );
						$( "#display" ).hide('blind', '' , 'slow',
						function() {
							$( "#display" ).load('centres_display.php',
							function() {
								$( "#display" ).show('blind', '' , 'slow');
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
			}
		}
	});
});

function Add_Centre()
{
	$( "#dialog-form" ).dialog( "open" );
}
</script>
<script> //edit centre
$(function() {
	$( "#dialog:ui-dialog2" ).dialog( "destroy" );
	
	var centre = $( "#e_centre" ),
		campaign = $( "#e_campaign" ),
		type = $( "#e_type" ),
		leads = $( "#e_leads" ),
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
		height: 210,
		width: 275,
		modal: true,
		resizable: false,
		draggable: false,
		show: 'blind',
		hide: 'blind',
		buttons: {
			"Submit": function() {
				$.get("centres_process.php?method=edit", { centre: centre.val(), campaign: campaign.val(), type: type.val(), leads: leads.val() }, function(data) {
					if (data == "editted")
					{
						$( "#dialog-form2" ).dialog( "close" );
						$( "#display" ).hide('blind', '' , 'slow',
						function() {
							$( "#display" ).load('centres_display.php',
							function() {
								$( "#display" ).show('blind', '' , 'slow');
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
			}
		}
	});
});

function Edit(centre)
{
	$( "#e_centre" ).val(centre);
	$.get("centres_process.php", { method: "campaign", centre: centre }, function(data) { $( "#e_campaign" ).val(data); });
	$.get("centres_process.php", { method: "type", centre: centre }, function(data) { $( "#e_type" ).val(data); });
	$.get("centres_process.php", { method: "leads", centre: centre }, function(data) { $( "#e_leads" ).val(data); });
	$( "#dialog-form2" ).dialog( "open" );
}
</script>
<script> //disable confirmation
$(function() {
	$( "#dialog:ui-dialog3" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		height:140,
		modal: true,
		show: 'blind',
		hide: 'blind',
		buttons: {
			"Disable": function() {
				var disable_centre = $( "#disable_centre" ).val();
				$.get("centres_process.php?method=disable", { centre: disable_centre }, function (data) {
					$( "#dialog-confirm" ).dialog( "close" );
					$( "#display" ).hide('blind', '' , 'slow',
					function() {
						$( "#display" ).load('centres_display.php',
						function() {
							$( "#display" ).show('blind', '' , 'slow');
						});
					});
				});
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
function Disable(centre)
{
	$( "#disable_centre" ).val(centre);
	$( "#dialog-confirm" ).dialog( "open" );
}
</script>
<script> //enable confirmation
$(function() {
	$( "#dialog:ui-dialog4" ).dialog( "destroy" );

	$( "#dialog-confirm2" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		height:140,
		modal: true,
		show: 'blind',
		hide: 'blind',
		buttons: {
			"Enable": function() {
				var enable_centre = $( "#enable_centre" ).val();
				$.get("centres_process.php?p=users&method=enable", { centre: enable_centre }, function (data) {
					$( "#dialog-confirm2" ).dialog( "close" );
					$( "#display" ).hide('blind', '' , 'slow',
					function() {
						$( "#display" ).load('centres_display.php',
						function() {
							$( "#display" ).show('blind', '' , 'slow');
						});
					});
				});
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
function Enable(centre)
{
	$( "#enable_centre" ).val(centre);
	$( "#dialog-confirm2" ).dialog( "open" );
}
</script>

<div id="dialog-form" title="Add Centre">
<p class="validateTips">All fields required</p>
<table>
<tr>
<td width="85px">Centre Code<span style="color:#ff0000;">*</span> </td>
<td><input type="text" id="centre" style="width:130px;" /></td>
</tr>
<tr>
<td width="85px">Campaign<span style="color:#ff0000;">*</span> </td>
<td><select id="campaign" style="width:132px;">
<option></option>
<?php
$q = mysql_query("SELECT `group` FROM vericon.campaigns GROUP BY `group` ORDER BY `group` ASC") or die(mysql_error());
while ($group = mysql_fetch_row($q))
{
	echo "<option disabled='disabled'>--- $group[0] ---</option>";
	$q1 = mysql_query("SELECT campaign FROM vericon.campaigns WHERE `group` = '$group[0]' ORDER BY id ASC") or die(mysql_error());
	while ($campaign = mysql_fetch_row($q1))
	{
		echo "<option>$campaign[0]</option>";
	}
}
?>
</select></td>
</tr>
<tr>
<td width="85px">Type<span style="color:#ff0000;">*</span> </td>
<td><select id="type" style="width:132px;">
<option></option>
<option>Captive</option>
<option>Outsourced</option>
<option>Self</option>
</select></td>
</tr>
<tr>
<td width="85px">Lead Validation<span style="color:#ff0000;">*</span> </td>
<td><select id="leads" style="width:132px;">
<option></option>
<option value="1">Active</option>
<option value="0">Inactive</option>
</select></td>
</tr>
</table>
</div>

<div id="dialog-form2" title="Edit Centre">
<p class="validateTips2">All fields required</p>
<table>
<tr>
<td width="85px">Centre Code </td>
<td><input type="text" id="e_centre" style="width:130px;" disabled="disabled" /></td>
</tr>
<tr>
<td width="85px">Campaign<span style="color:#ff0000;">*</span> </td>
<td><select id="e_campaign" style="width:132px;">
<?php
$q = mysql_query("SELECT `group` FROM vericon.campaigns GROUP BY `group` ORDER BY `group` ASC") or die(mysql_error());
while ($group = mysql_fetch_row($q))
{
	echo "<option disabled='disabled'>--- $group[0] ---</option>";
	$q1 = mysql_query("SELECT campaign FROM vericon.campaigns WHERE `group` = '$group[0]' ORDER BY id ASC") or die(mysql_error());
	while ($campaign = mysql_fetch_row($q1))
	{
		echo "<option>$campaign[0]</option>";
	}
}
?>
</select></td>
</tr>
<tr>
<td width="85px">Type<span style="color:#ff0000;">*</span> </td>
<td><select id="e_type" style="width:132px;">
<option>Captive</option>
<option>Outsourced</option>
<option>Self</option>
</select></td>
</tr>
<tr>
<td width="85px">Lead Validation<span style="color:#ff0000;">*</span> </td>
<td><select id="e_leads" style="width:132px;">
<option value="1">Active</option>
<option value="0">Inactive</option>
</select></td>
</tr>
</table>
</div>

<div id="dialog-confirm" title="Disable Centre?">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you would like to disable this centre and all it's users?</p>
    <input type="hidden" id="disable_centre" value="" />
</div>

<div id="dialog-confirm2" title="Enable Centre?">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you would like to enable this centre?</p>
    <input type="hidden" id="enable_centre" value="" />
</div>

<div id="display">
<script>
$( "#display" ).load('centres_display.php',
function() {
	$( "#display" ).show('blind', '' , 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>