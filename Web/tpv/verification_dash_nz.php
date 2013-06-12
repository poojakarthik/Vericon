<?php
mysql_connect('localhost','vericon','18450be');

$q = mysql_query("SELECT user FROM vericon.currentuser WHERE hash = '" . $_COOKIE["hash"] . "'") or die(mysql_error());
$user = mysql_fetch_row($q);

$q1 = mysql_query("SELECT * FROM vericon.auth WHERE user = '$user[0]'") or die(mysql_error());
$ac = mysql_fetch_assoc($q1);

$id = $_GET["id"];

$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE id = '" . mysql_escape_string($id) . "'") or die (mysql_error());
$data = mysql_fetch_assoc($q);

$status_text = strtolower(str_replace(" ", "_", $data["status"]));

$q1 = mysql_query("SELECT first,last,alias FROM vericon.auth WHERE user = '$data[agent]'") or die(mysql_error());
$agent = mysql_fetch_assoc($q1);
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
.ui-dialog_submit { padding: .3em; }
.ui-dialog2 { padding: .3em; }
.ui-dialog3 { padding: .3em; }
.ui-dialog4 { padding: .3em; }
.ui-state-highlight { padding: .3em; }
.validateTips2 { border: 1px solid transparent; padding: 0.3em; }
.validateTips3 { border: 1px solid transparent; padding: 0.3em; }
.validateTips4 { border: 1px solid transparent; padding: 0.3em; }
</style>
<script> //add packages
$(function() {
	$( "#dialog:ui-dialog2" ).dialog( "destroy" );
	
	var tips = $( ".validateTips2" );

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
		height: 280,
		width: 400,
		modal: true,
		resizable: false,
		draggable: false,
		show: "blind",
		hide: "blind",
		buttons: {
			"Add Package": function() {
				var id = $( "#id" ),
					cli = $( "#cli" ),
					plan = $( "#plan" ),
					plan_type = $( "#plan_type" ),
					provider = $( "#provider" ),
					ac_number = $( "#ac_number" ),
					adsl_provider = $( "#adsl_provider "),
					adsl_ac_number = $( "#adsl_ac_number ");
				
				if (cli.val() == "")
				{
					updateTips("Enter the CLI!");
				}
				else if (plan.val() == "")
				{
					updateTips("Select a plan!");
				}
				else
				{
					$.get("verification_submit.php?method=add_nz", { id: id.val(), cli: cli.val(), plan: plan.val(), plan_type: plan_type.val(), provider: provider.val(), ac_number: ac_number.val(), adsl_provider: adsl_provider.val(), adsl_ac_number: adsl_ac_number.val() },
					function(data) {
						if (data == "added")
						{
							$( "#packages" ).load('packages_nz.php?id=' + id.val());
							$( "#dialog-form2" ).dialog( "close" );
						}
						else
						{
							updateTips(data);
						}
					});
				}
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

function Add_Package()
{
	$( "#cli" ).val("");
	$( "#plan" ).val("");
	$( "#provider" ).val("");
	$( "#ac_number" ).val("");
	$( "#adsl_provider" ).val("");
	$( "#adsl_ac_number" ).val("");
	$( "#adsl_provider_tr" ).attr("style","display:none;");
	$( "#adsl_ac_number_tr" ).attr("style","display:none;");
	$( "#validateTips2" ).text("All fields are required");
	$( "#dialog-form2" ).dialog( "open" );
}

function Plan_Dropdown()
{
	$( "#plan" ).attr("disabled","disabled");
	$( "#plan" ).html("<option value=''>Loading...</option>");
	$( "#plan" ).load("plans_nz.php?option=add&id=" + $( "#id" ).val() + "&type=" + $( "#sale_type" ).val() + "&cli=" + $('#cli').val(), function() {
		$( "#plan" ).removeAttr("disabled");
	});
}
</script>
<script> //edit packages
$(function() {
	$( "#dialog:ui-dialog3" ).dialog( "destroy" );
	
	var tips = $( ".validateTips3" );

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
		height: 280,
		width: 400,
		modal: true,
		resizable: false,
		draggable: false,
		show: "blind",
		hide: "blind",
		buttons: {
			"Edit Package": function() {
				var id = $( "#id" ),
					cli = $( "#edit_cli" ),
					plan = $( "#edit_plan" ),
					plan_type = $( "#edit_plan_type" ),
					provider = $( "#edit_provider" ),
					ac_number = $( "#edit_ac_number" ),
					adsl_provider = $( "#edit_adsl_provider "),
					adsl_ac_number = $( "#edit_adsl_ac_number "),
					cli2 = $( "#original_edit_cli" );
				
				if (cli.val() == "")
				{
					updateTips("Enter the CLI!");
				}
				else if (plan.val() == "")
				{
					updateTips("Select a plan!");
				}
				else
				{
					$.get("verification_submit.php?method=edit_nz", { id: id.val(), cli: cli.val(), plan: plan.val(), plan_type: plan_type.val(), provider: provider.val(), ac_number: ac_number.val(), adsl_provider: adsl_provider.val(), adsl_ac_number: adsl_ac_number.val(), cli2: cli2.val() },
					function(data) {
						if (data == "editted")
						{
							$( "#packages" ).load('packages_nz.php?id=' + id.val());
							$( "#dialog-form3" ).dialog( "close" );
						}
						else
						{
							updateTips(data);
						}
					});
				}
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

function Edit_Package(cli,plan)
{
	var id = $( "#id" );
	
	$( "#edit_cli" ).val(cli);
	$( "#edit_plan_type" ).val("PSTN");
	$( "#edit_adsl_provider" ).val("");
	$( "#edit_adsl_ac_number" ).val("");
	$( "#edit_adsl_provider_tr" ).attr("style","display:none;");
	$( "#edit_adsl_ac_number_tr" ).attr("style","display:none;");
	$( "#edit_cli" ).attr("disabled","disabled");
	$( "#edit_plan" ).attr("disabled","disabled");
	$( "#edit_plan" ).html("<option value=''>Loading...</option>");
	$( "#edit_plan" ).load("plans_nz.php?option=edit&id=" + $( "#id" ).val() + "&type=" + $( "#sale_type" ).val() + "&cli=" + $('#edit_cli').val(),
	function() {
		$( "#edit_cli" ).removeAttr("disabled");
		$( "#edit_plan" ).removeAttr("disabled");
		$( "#edit_plan" ).val(plan);
	});
	$( "#original_edit_cli" ).val(cli);
	$.get("verification_submit.php?method=nz_provider", { id: id.val(), cli: cli }, function(data) { $( "#edit_provider" ).val(data); });
	$.get("verification_submit.php?method=nz_ac_number", { id: id.val(), cli: cli }, function(data) { $( "#edit_ac_number" ).val(data); });
	$.get("verification_submit.php?method=nz_adsl_provider", { id: id.val(), cli: cli }, function(data) {
		if (data != "")
		{
			$( "#edit_plan_type" ).val("Bundle");
			$( "#edit_adsl_provider_tr" ).removeAttr("style");
			$( "#edit_adsl_provider" ).val(data);
		}
	});
	$.get("verification_submit.php?method=nz_adsl_ac_number", { id: id.val(), cli: cli }, function(data) {
		if (data != "")
		{
			$( "#edit_plan_type" ).val("Bundle");
			$( "#edit_adsl_ac_number_tr" ).removeAttr("style");
			$( "#edit_adsl_ac_number" ).val(data);
		}
	});
	$( ".validateTips3" ).text("All fields are required");
	$( "#dialog-form3" ).dialog( "open" );
}

function Plan_Dropdown_Edit()
{
	$( "#edit_plan" ).attr("disabled","disabled");
	$( "#edit_plan" ).html("<option value=''>Loading...</option>");
	$( "#edit_plan" ).load("plans_nz.php?option=edit&id=" + $( "#id" ).val() + "&type=" + $( "#sale_type" ).val() + "&cli=" + $('#edit_cli').val(), function() {
		$( "#edit_plan" ).removeAttr("disabled");
	});
}
</script>
<script>
function Plan_Option(option,type)
{
	if (option == "add")
	{
		if (type == "PSTN")
		{
			$( "#adsl_provider" ).val("");
			$( "#adsl_ac_number" ).val("");
			$( "#adsl_provider_tr" ).attr("style","display:none;");
			$( "#adsl_ac_number_tr" ).attr("style","display:none;");
			$( "#plan_type" ).val("PSTN");
		}
		else if (type == "Bundle")
		{
			$( "#adsl_provider" ).val("");
			$( "#adsl_ac_number" ).val("");
			$( "#plan_type" ).val("Bundle");
			$( "#adsl_provider_tr" ).removeAttr("style");
			$( "#adsl_ac_number_tr" ).removeAttr("style");
		}
	}
	else if (option == "edit")
	{
		if (type == "PSTN")
		{
			$( "#edit_adsl_provider" ).val("");
			$( "#edit_adsl_ac_number" ).val("");
			$( "#edit_adsl_provider_tr" ).attr("style","display:none;");
			$( "#edit_adsl_ac_number_tr" ).attr("style","display:none;");
			$( "#edit_plan_type" ).val("PSTN");
		}
		else if (type == "Bundle")
		{
			$( "#edit_adsl_provider" ).val("");
			$( "#edit_adsl_ac_number" ).val("");
			$( "#edit_plan_type" ).val("Bundle");
			$( "#edit_adsl_provider_tr" ).removeAttr("style");
			$( "#edit_adsl_ac_number_tr" ).removeAttr("style");
		}
	}
}
</script>
<script> //delete packages
function Delete_Package(cli)
{
	var id = $( "#id" );
	
	$.get("verification_submit.php?method=delete", { id: id.val(), cli: cli},
	function(data) {
		if (data == "deleted")
		{
			$( "#packages" ).load('packages_nz.php?id=' + id.val());
		}
	});
}
</script>
<script> //change type
function Change_Type(type)
{
	var id = $( "#id" ),
		verifier = "<?php echo $ac["user"]; ?>";
	
	$.get("verification_submit.php?method=change_type", { id: id.val(), verifier: verifier, type: type }, function(data) {
		if (data == "done")
		{
			$( "#display" ).hide('blind', '' , 'slow', function() {
				$( "#display" ).load('verification_dash_nz.php?id=' + id.val(), function() {
					$( "#packages" ).load('packages_nz.php?id=' + id.val(), function() {
						$( "#display" ).show('blind', '' , 'slow');
					});
				});
			});
		}
		else
		{
			Submit_Error(data);
		}
	});
}
</script>
<script> //submit error
$(function() {
	$( "#dialog:ui-dialog_submit" ).dialog( "destroy" );

	$( "#dialog-form_submit" ).dialog({
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

function Submit_Error(data)
{
	$( ".submit_error" ).html(data);
	$( "#dialog-form_submit" ).dialog( "open" );
}
</script>
<script> //load script
function LoadScript()
{
	var id = $( "#id" );
	
	$( "#display" ).hide('blind', '' , 'slow', function() {
		$( "#display" ).load('verification_script_nz.php?id=' + id.val() + '&user=<?php echo $ac["user"]; ?>', function() {
			$( "#display" ).show('blind', '' , 'slow');
		});
	});
}
</script>
<script> //cancel
$(function() {
	$( "#dialog:ui-dialog4" ).dialog( "destroy" );
	
	var tips = $( ".validateTips4" );
	
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	
	$( "#dialog-form4" ).dialog({
		autoOpen: false,
		height: 250,
		width: 425,
		modal: true,
		resizable: false,
		draggable: false,
		show: "blind",
		hide: "blind",
		buttons: {
			"Submit": function() {
				var id = $( "#id" ),
					verifier = "<?php echo $ac["user"]; ?>",
					status = $( "#status" ),
					note = $( "#cancel_note" );
				
				$.get("verification_submit.php?method=cancel", { id: id.val(), verifier: verifier, status: status.val(), note: note.val() }, function(data) {
					if (data == "done")
					{
						$( "#dialog-form4" ).dialog( "close" );
						$( "#display" ).hide('blind', '', 'slow', function() {
							window.location = "verification.php";
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

function Cancel()
{
	$( ".validateTips4" ).text("All fields are required");
	$( "#dialog-form4" ).dialog( "open" );
}
</script>

<div id="dialog-form2" title="Add a Package">
<p class="validateTips2">All fields are required</p><br />
<table>
<tr>
<td width="125px">CLI </td>
<td><input type="text" id="cli" onchange="Plan_Dropdown()" style="width:125px;" /></td>
</tr>
<tr>
<td>Plan </td>
<td><input type="hidden" id="plan_type" value="" />
<select id="plan" style="width:210px;">
<option></option>
</select></td>
</tr>
<tr>
<td>Provider </td>
<td><select id="provider" style="width:210px;">
<option></option>
<?php
$q = mysql_query("SELECT value,name FROM vericon.providers ORDER BY name ASC") or die(mysql_error());
while ($providers = mysql_fetch_row($q))
{
	echo "<option value='" . $providers[0] . "'>" . $providers[1] . "</option>";
}
?>
<option>Other</option>
</select></td>
</tr>
<tr>
<td>Account Number </td>
<td><input type="text" size="15" id="ac_number" style="margin-top:0px;" /></td>
</tr>
<tr id="adsl_provider_tr" style="display:none;">
<td>ADSL Provider </td>
<td><select id="adsl_provider" style="width:210px;">
<option></option>
<?php
$q = mysql_query("SELECT value,name FROM vericon.providers ORDER BY name ASC") or die(mysql_error());
while ($providers = mysql_fetch_row($q))
{
	echo "<option value='" . $providers[0] . "'>" . $providers[1] . "</option>";
}
?>
<option>Other</option>
</select></td>
</tr>
<tr id="adsl_ac_number_tr" style="display:none;">
<td>ADSL Account Number </td>
<td><input type="text" size="15" id="adsl_ac_number" style="margin-top:0px;" /></td>
</tr>
</table>
</div>

<div id="dialog-form3" title="Edit Package">
<p class="validateTips3">All fields are required</p><br />
<input type="hidden" id="original_edit_cli" value="" />
<table>
<tr>
<td width="125px">CLI </td>
<td><input type="text" size="15" id="edit_cli" onchange="Plan_Dropdown_Edit()" style="margin-top:0px;" /></td>
</tr>
<tr>
<td>Plan </td>
<td><input type="hidden" id="edit_plan_type" value="" />
<select id="edit_plan" style="width:210px;">
<option></option>
</select></td>
</tr>
<tr>
<td>Provider </td>
<td><select id="edit_provider" style="width:210px;">
<option></option>
<?php
$q = mysql_query("SELECT value,name FROM vericon.providers ORDER BY name ASC") or die(mysql_error());
while ($providers = mysql_fetch_row($q))
{
	echo "<option value='" . $providers[0] . "'>" . $providers[1] . "</option>";
}
?>
<option>Other</option>
</select></td>
</tr>
<tr>
<td>Account Number </td>
<td><input type="text" size="15" id="edit_ac_number" style="margin-top:0px;" /></td>
</tr>
<tr id="edit_adsl_provider_tr" style="display:none;">
<td>ADSL Provider </td>
<td><select id="edit_adsl_provider" style="width:210px;">
<option></option>
<?php
$q = mysql_query("SELECT value,name FROM vericon.providers ORDER BY name ASC") or die(mysql_error());
while ($providers = mysql_fetch_row($q))
{
	echo "<option value='" . $providers[0] . "'>" . $providers[1] . "</option>";
}
?>
<option>Other</option>
</select></td>
</tr>
<tr id="edit_adsl_ac_number_tr" style="display:none;">
<td>ADSL Account Number </td>
<td><input type="text" size="15" id="edit_adsl_ac_number" style="margin-top:0px;" /></td>
</tr>
</table>
</div>

<div id="dialog-form_submit" title="Error!">
<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><span class="submit_error"></span></p>
</div>

<div id="dialog-form4" title="Cancel Verification">
<p class="validateTips4">All fields are required</p><br />
<table>
<tr>
<td width="50px">Status </td>
<td><select id="status" style="width:120px;">
<option></option>
<option>Declined</option>
<option>Line Issue</option>
</select></td>
</tr>
<tr>
<td width="50px">Note </td>
<td><textarea id="cancel_note" rows="5" style="width:350px; resize:none;"></textarea></td>
</tr>
</table>
</div>

<input type="hidden" id="id" value="<?php echo $data["id"]; ?>" />
<input type="hidden" id="sale_type" value="<?php echo $data["type"]; ?>" />
<table width="100%">
<tr>
<td width="50%" valign="top" style="padding-right:20px;">
<table width="100%" style="margin-bottom:10px;">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/sale_details_header.png" width="90" height="15" /></td>
<td align="right" style="padding-right:10px;"><img src="../images/<?php echo $status_text; ?>_header.png" width="100" height="15"></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
<tr>
<td width="100px" style="padding-left:5px;">Sale ID </td>
<td><b><?php echo $data["id"]; ?></b></td>
</tr>
<tr>
<td style="padding-left:5px;">Agent Name </td>
<td><b><?php echo $agent["first"] . " " . $agent["last"] . " (" . $agent["alias"] . ")"; ?></b></td>
</tr>
<tr>
<td style="padding-left:5px;">Centre </td>
<td><b><?php echo $data["centre"]; ?></b></td>
</tr>
<tr>
<td style="padding-left:5px;">Campaign </td>
<td><b><?php echo $data["campaign"]; ?></b></td>
</tr>
<tr>
<td style="padding-left:5px;">Type </td>
<td><b><?php echo $data["type"]; ?></b>
<?php
if ($data["type"] == "Residential")
{
	echo "<button onclick='Change_Type(\"Business\")' class='icon_business' style='float:right; margin-right:10px;' title='Business'></button>";
}
elseif ($data["type"] == "Business")
{
	echo "<button onclick='Change_Type(\"Residential\")' class='icon_residential' style='float:right; margin-right:10px;' title='Residential'></button>";
}
?>
</td>
</tr>
</table>
</td>
<td width="50%" valign="top" style="padding-left:20px;">
<table width="100%" style="margin-bottom:10px;">
<tr>
<td colspan="2" style="padding-left:5px;"><img src="../images/customer_details_header.png" width="128" height="15" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
<tr>
<td style="padding-left:5px;">Lead ID </td>
<td><b><?php echo $data["lead_id"]; ?></b></td>
</tr>
<tr>
<td width="100px" style="padding-left:5px;">Customer Name </td>
<td><b><?php echo $data["title"] . " " . $data["firstname"] . " " . $data["lastname"]; ?></b></td>
</tr>
<tr>
<td style="padding-left:5px;">D.O.B </td>
<td><b><?php echo date("d/m/Y", strtotime($data["dob"])); ?></b></td>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan="2" valign="top">
<table width="100%">
<tr>
<td style="padding-left:5px;"><img src="../images/tpv_notes_header.png" width="80" height="15"></td>
</tr>
<tr>
<td><img src="../images/line.png" width="100%" height="9"></td>
</tr>
<tr>
<td>
<center><div style="height:125px; width:97%; padding:0 3px; overflow:auto; border: 1px solid #eee;">
<?php
$q3 = mysql_query("SELECT * FROM vericon.tpv_notes WHERE id = '$id' ORDER BY timestamp DESC") or die (mysql_error());

echo "<table width='100%'>";
if (mysql_num_rows($q3) == 0)
{
	echo "<tr>";
	echo "<td>No Notes</td>";
	echo "</tr>";
}
else
{
	while ($tpv_notes = mysql_fetch_assoc($q3))
	{
		$q4 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$tpv_notes[verifier]'") or die(mysql_error());
		$vname = mysql_fetch_assoc($q4);
		
		echo "<tr>";
		echo "<td>----- " . date("d/m/Y H:i:s", strtotime($tpv_notes["timestamp"])) . " - " . $vname["first"] . " " . $vname["last"] . " -----" . " (" . $tpv_notes["status"] . ")</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>" . $tpv_notes["note"] . "</td>";
		echo "</tr>";
	}
}
echo "</table>";
?>
</div></center>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan="2" valign="top">
<table width="100%" border="0">
<tr>
<td width="56%" valign="top">
<table width="100%">
<tr>
<td style="padding-left:5px;"><img src="../images/previous_attempt_header.png" width="140" height="15"></td>
</tr>
<tr>
<td><img src="../images/line.png" width="100%" height="9"></td>
</tr>
<tr>
<td>
<center><div style="height:100px; width:97%; padding:0 3px; overflow:auto; border: 1px solid #eee;">
<?php
echo "<table width='100%'>";
echo "<tr>";
echo "<td>Temporarily Unavailable</td>";
echo "</tr>";
echo "</table>";
?>
</div></center>
</td>
</tr>
</table>
</td>
<td width="44%" valign="bottom">
<table border="0" width="100%">
<tr>
<td align="right" style="padding-right:5px;">
<img src="../images/verification_fill_bg.png" width="315" height="104" />
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan="2" valign="top">
<table border="0" width="100%">
<tr>
<td colspan="2" style="padding-left:5px;"><img src="../images/selected_packages_header.png" width="134" height="15"></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" alt="line" /></td>
</tr>
<tr>
<td colspan="2">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="99%" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th width="12%">CLI</th>
<th width="30%">Plan</th>
<th width="28%">Provider</th>
<th width="20%">Account Number</th>
<th width="10%" colspan="2" style="text-align:center;">Edit</th>
</tr>
</thead>
<tbody id="packages">
</tbody>
</table>
</div></center>
</td>
</tr>
<tr valign="bottom">
<td align="left" style="padding-left:10px;"><button onclick="Add_Package()" class="btn">Add Package</button></td>
<td align="right" style="padding-right:10px;"><button onclick="LoadScript()" class="btn">Load Script</button> <button onclick="Cancel()" class="btn_red">Cancel</button></td>
</tr>
</table>
</td>
</tr>
</table>