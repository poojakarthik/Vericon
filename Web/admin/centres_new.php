<?php
include("../auth/restrict_inner.php");
?>
<script>
function Admin05_Create_Error(text)
{
	$( "#Admin05_create_error" ).html('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p style="padding: 9px 0;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>' + text + '</p></div>');
}

function Admin05_Add_Centre_Cancel()
{
	V_Page_Reload();
}

function Admin05_Add_Centre_Submit()
{
	V_Loading_Start();
	
	var centre = $( "#Admin05_centre" ),
		campaign = $( "#Admin05_campaign" ),
		type = $( "#Admin05_type" ),
		leads = $( "#Admin05_leads" );
	
	$.post("/admin/centres_process.php", { m: "add", centre: centre.val(), campaign: campaign.val(), type: type.val(), leads: leads.val() }, function(data) {
		if (data == "valid")
		{
			V_Page_Reload();
		}
		else
		{
			Admin05_Create_Error(data);
			V_Loading_End();
		}
	}).error( function(xhr, text, err) {
		if (xhr.status == 420)
		{
			$(".loading_message").html("<p><b>Your session has expired.</b></p><p><b>You will be logged out shortly.</b></p>");
			setTimeout(function() {
				V_Logout();
			}, 2500);
		}
		else if (xhr.status == 421)
		{
			$(".loading_message").html("<p><b>Your account has been disabled.</b></p><p><b>You will be logged out shortly.</b></p>");
			setTimeout(function() {
				V_Logout();
			}, 2500);
		}
		else
		{
			$(".loading_message").html("<p><b>An error occurred while performing this action.</b></p><p><b>Error: " + xhr.status + " " + xhr.statusText + "</b></p>");
			setTimeout(function() {
				V_Loading_End();
			}, 2500);
		}
	});
}
</script>

<center><div style="width:98%; text-align:left;">
<table>
<tr>
<td colspan="2" id="Admin05_create_error"><p>Enter the details below to add the centre.</p></td>
</tr>
<tr>
<td width="85px">Centre Code<span style="color:#ff0000;">*</span> </td>
<td><input type="text" id="Admin05_centre" /></td>
</tr>
<tr>
<td width="85px">Campaign<span style="color:#ff0000;">*</span> </td>
<td><select id="Admin05_campaign" multiple="multiple">
<?php
$q = $mysqli->query("SELECT `id`, `name` FROM `vericon`.`groups` ORDER BY `id` ASC") or die($mysqli->error);
while ($group = $q->fetch_row())
{
	echo "<option disabled='disabled'>--- $group[1] ---</option>";
	$q1 = $mysqli->query("SELECT `id`, `campaign` FROM `vericon`.`campaigns` WHERE `group` = '" . $mysqli->real_escape_string($group[0]) . "' ORDER BY `id` ASC") or die($mysqli->error);
	while ($campaign = $q1->fetch_row())
	{
		echo "<option value='$campaign[0]'>$campaign[1]</option>";
	}
	$q1->free();
}
$q->free();
$mysqli->close();
?>
</select></td>
</tr>
<tr>
<td width="85px">Type<span style="color:#ff0000;">*</span> </td>
<td><select id="Admin05_type">
<option></option>
<option>Captive</option>
<option>Melbourne</option>
<option>Outsourced</option>
</select></td>
</tr>
<tr>
<td width="85px">Lead Validation<span style="color:#ff0000;">*</span> </td>
<td><select id="Admin05_leads">
<option></option>
<option>Active</option>
<option>Inactive</option>
</select></td>
</tr>
<tr>
<td colspan="2" align="right" valign="bottom">
<button onclick="Admin05_Add_Centre_Submit()" class="btn">Submit</button> <button onclick="Admin05_Add_Centre_Cancel()" class="btn" style="margin-left:10px; margin-top:10px;">Cancel</button>
</td>
</tr>
</table>
</div></center>