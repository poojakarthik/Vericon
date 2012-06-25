<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
.add_campaign
{
	background-image:url('../images/add_campaign_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
	margin-right:20px;
}

.add_campaign:hover
{
	background-image:url('../images/add_campaign_btn_hover.png');
	cursor:pointer;
}
.add_centre
{
	background-image:url('../images/add_centre_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
	margin-right:15px;
}

.add_centre:hover
{
	background-image:url('../images/add_centre_btn_hover.png');
	cursor:pointer;
}
div#users-contain table { margin: 1em 0; border-collapse: collapse; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .4em 10px; text-align:left }
</style>
<script> //add campaign
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var id = $( "#campaign_id" ),
		campaign = $( "#campaign_campaign" ),
		tips = $( ".error" );

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
		height: 200,
		width: 300,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Add": function() {
				if (id.val() == "")
				{
					updateTips("Enter an ID!");
				}
				else if (campaign.val() == "")
				{
					updateTips("Enter the campaign name!");
				}
				else
				{
					$.get("clients_submit.php?type=campaign&method=add", { id: id.val(), campaign: campaign.val() },
					function(data) {
						$( this ).dialog( "close" );
						location.reload();
					});
				}
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
		}
	});
});

function Add_Campaign()
{
	$( "#dialog-form" ).dialog( "open" );
}
</script>
<script> //delete campaign
$(function() {
	$( "#dialog:ui-dialog2" ).dialog( "destroy" );

	$( "#dialog-confirm2" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		height:140,
		modal: true,
		buttons: {
			"Delete": function() {
				var id = $( "#delete_campaign_id" ),
					campaign = $( "#delete_campaign_campaign" );
				
				$.get("clients_submit.php?type=campaign&method=delete", { id: id.val(), campaign: campaign.val() },
				function(data) {
					$( this ).dialog( "close" );
					location.reload();
				});
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
function Delete_Campaign(id,campaign)
{
	$( "#delete_campaign_id" ).val(id);
	$( "#delete_campaign_campaign" ).val(campaign);
	$( "#dialog-confirm2" ).dialog( "open" );
}
</script>
<script> //add centre
$(function() {
	$( "#dialog:ui-dialog3" ).dialog( "destroy" );
	
	var centre = $( "#centre" ),
		campaign = $( "#centre_campaign" ),
		tips = $( ".error2" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#dialog-form3" ).dialog({
		autoOpen: false,
		height: 250,
		width: 300,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Add": function() {
				if (centre.val() == "")
				{
					updateTips("Enter a centre code!");
				}
				else if (campaign.val() == "")
				{
					updateTips("Must select atleast 1 campaign!");
				}
				else
				{
					$.get("clients_submit.php?type=centre&method=add", { centre: centre.val(), campaign: campaign.val() },
					function(data) {
						$( this ).dialog( "close" );
						location.reload();
					});
				}
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
		}
	});
});

function Add_Centre()
{
	$( "#dialog-form3" ).dialog( "open" );
}
</script>
<script> //delete centre
$(function() {
	$( "#dialog:ui-dialog4" ).dialog( "destroy" );

	$( "#dialog-confirm4" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		height:140,
		modal: true,
		buttons: {
			"Delete": function() {
				var centre = $( "#delete_centre" );
				
				$.get("clients_submit.php?type=centre&method=delete", { centre: centre.val() },
				function(data) {
					$( this ).dialog( "close" );
					location.reload();
				});
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
function Delete_Centre(centre)
{
	$( "#delete_centre" ).val(centre);
	$( "#dialog-confirm4" ).dialog( "open" );
}
</script>
<script> //edit centre
$(function() {
	$( "#dialog:ui-dialog5" ).dialog( "destroy" );
	
	var centre = $( "#edit_centre" ),
		campaign = $( "#edit_centre_campaign" ),
		tips = $( ".error3" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#dialog-form5" ).dialog({
		autoOpen: false,
		height: 250,
		width: 300,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Modify": function() {
				if (campaign.val() == "")
				{
					updateTips("Must select atleast 1 campaign!");
				}
				else
				{
					$.get("clients_submit.php?type=centre&method=edit", { centre: centre.val(), campaign: campaign.val() },
					function(data) {
						$( this ).dialog( "close" );
						location.reload();
					});
				}
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
		}
	});
});

function Edit_Centre(centre,campaign)
{
	var centre_campaign = campaign.split(',');

	for (i = 0; i < centre_campaign.length; i++)
	{
		l = "#edit_centre_campaign option:contains('" + centre_campaign[i] + "'),";
		$( l ).attr("selected","selected");
	}

	$( "#edit_centre" ).val(centre);
	$( "#dialog-form5" ).dialog( "open" );
}
</script>
<div id="dialog-form" title="Add a Campaign">
<p class="error">All fields required</p><br />
<table>
<tr>
<td width="60px">ID<span style="color:#ff0000;">*</span> </td>
<td><input type="text" size="25" id="campaign_id" /></td>
</tr>
<tr>
<td width="60px">Campaign<span style="color:#ff0000;">*</span> </td>
<td><input type="text" size="25" id="campaign_campaign" /></td>
</tr>
</table>
</div>

<div id="dialog-confirm2" title="Delete Campaign?">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you would like to delete this campaign? This action cannot be undone.</p>
    <input type="hidden" id="delete_campaign_id" value="" /><input type="hidden" id="delete_campaign_campaign" value="" />
</div>

<div id="dialog-form3" title="Add a Centre">
<p class="error2">All fields required</p><br />
<table>
<tr>
<td width="85px">Centre Code<span style="color:#ff0000;">*</span> </td>
<td><input type="text" size="8" id="centre" /></td>
</tr>
<tr>
<td width="85px">Campaign<span style="color:#ff0000;">*</span> </td>
<td><select id="centre_campaign" name="centre_campaign" multiple="multiple" style="margin:0; width:165px; height:95px;">
<?php
$q0 = mysql_query("SELECT * FROM campaigns ORDER BY campaign ASC") or die(mysql_error());
while ($camps = mysql_fetch_assoc($q0))
{
	echo "<option>$camps[campaign]</option>";
}
?>
</select></td>
</tr>
</table>
</div>

<div id="dialog-confirm4" title="Delete Centre?">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you would like to delete this centre? This action cannot be undone.</p>
    <input type="hidden" id="delete_centre" value="" />
</div>

<div id="dialog-form5" title="Edit a Centre">
<p class="error3">All fields required</p><br />
<table>
<tr>
<td width="85px">Centre Code </td>
<td><input type="text" size="8" disabled="disabled" id="edit_centre" /></td>
</tr>
<tr>
<td width="85px">Campaign<span style="color:#ff0000;">*</span> </td>
<td><select id="edit_centre_campaign" name="edit_centre_campaign" multiple="multiple" style="margin:0; width:165px; height:95px;">
<?php
$q0 = mysql_query("SELECT * FROM campaigns ORDER BY campaign ASC") or die(mysql_error());
while ($camps = mysql_fetch_assoc($q0))
{
	echo "<option>$camps[campaign]</option>";
}
?>
</select></td>
</tr>
</table>
</div>

<table border="0" width="100%">
<tr>
<td width="45%" valign="top">
<p><img src="../images/telco_clients_header.png" width="135" height="25" style="margin-left:3px;" /></p>
<p><img src="../images/line.png" width="95%" height="9" alt="line" /></p>
<div id="users-contain" class="ui-widget" style="margin-left:3px; width:93%;">
<table id="users" class="ui-widget ui-widget-content sortable" width="100%" style="margin-top:3px;">
<thead>
<tr class="ui-widget-header ">
<th>ID</th>
<th>Name</th>
<th></th>
</tr>
</thead>
<tbody align="center">
<?php
$q = mysql_query("SELECT * FROM campaigns ORDER BY id ASC") or die(mysql_error());
while ($campaigns = mysql_fetch_assoc($q))
{
	echo "<tr>";
	echo "<td>" . $campaigns["id"] . "</td>";
	echo "<td>" . $campaigns["campaign"] . "</td>";
	echo "<td><a onclick='Delete_Campaign(\"$campaigns[id]\",\"$campaigns[campaign]\")' style='cursor:pointer; text-decoration:underline;'>Delete</a></td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div>
<input type="button" onclick="Add_Campaign()" class="add_campaign" style="float:right" />
</td>
<td width="55%" valign="top">
<p><img src="../images/call_centres_header.png" width="130" height="25" style="margin-left:3px;" /></p>
<p><img src="../images/line.png" width="98%" height="9" alt="line" /></p>
<div id="users-contain" class="ui-widget" style="margin-left:3px; width:96%;">
<table id="users" class="ui-widget ui-widget-content sortable" width="100%" style="margin-top:3px;">
<thead>
<tr class="ui-widget-header ">
<th>Centre</th>
<th>Campaigns</th>
<th colspan="2">Edit</th>
</tr>
</thead>
<tbody align="center">
<?php
$q = mysql_query("SELECT * FROM centres WHERE centre != 'TPV' ORDER BY centre ASC") or die(mysql_error());
while ($centres = mysql_fetch_assoc($q))
{
	$cen_cam = explode(",",$centres["campaign"]);
	$centre_campaigns = implode(", ", $cen_cam);
	
	echo "<tr>";
	echo "<td>" . $centres["centre"] . "</td>";
	echo "<td>" . $centre_campaigns . "</td>";
	echo "<td><a onclick='Edit_Centre(\"$centres[centre]\",\"$centres[campaign]\")' style='cursor:pointer; text-decoration:underline;'>Edit</a></td>";
	echo "<td><a onclick='Delete_Centre(\"$centres[centre]\")' style='cursor:pointer; text-decoration:underline;'>Delete</a></td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div>
<input type="button" onclick="Add_Centre()" class="add_centre" style="float:right" />
</td>
</tr>
</table>
<?php
include "../source/footer.php";
?>