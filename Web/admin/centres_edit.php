<?php
include("../auth/restrict_inner.php");

$centre = $_POST["centre"];
$q = mysql_query("SELECT * FROM `vericon`.`centres` WHERE `id` = '" . mysql_real_escape_string($centre) . "'") or die(mysql_error());
$data = mysql_fetch_assoc($q);
?>
<script>
function Admin05_Edit_Error(text)
{
	$( "#Admin05_edit_error" ).html('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p style="padding: 9px 0;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>' + text + '</p></div>');
}

function Admin05_Edit_Centre_Cancel()
{
	V_Page_Reload();
}

function Admin05_Edit_Centre_Submit()
{
	var centre = "<?php echo $centre; ?>",
		campaign = $( "#Admin05_campaign" ),
		type = $( "#Admin05_type" ),
		leads = $( "#Admin05_leads" );
	
	$.post("/admin/centres_process.php", { m: "edit", centre: centre, campaign: campaign.val(), type: type.val(), leads: leads.val() }, function(data) {
		if (data == "valid")
		{
			V_Page_Reload();
		}
		else
		{
			Admin05_Edit_Error(data);
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
<td colspan="2" id="Admin05_edit_error"><p>Enter the details below to edit the centre.</p></td>
</tr>
<tr>
<td width="85px">Centre Code </td>
<td><input type="text" id="Admin05_centre" disabled="disabled" value="<?php echo $centre; ?>" /></td>
</tr>
<tr>
<td width="85px">Campaign<span style="color:#ff0000;">*</span> </td>
<td><select id="Admin05_campaign" multiple="multiple">
<?php
$q = mysql_query("SELECT `id`, `name` FROM `vericon`.`groups` ORDER BY `id` ASC") or die(mysql_error());
while ($group = mysql_fetch_row($q))
{
	echo "<option disabled='disabled'>--- $group[1] ---</option>";
	$q1 = mysql_query("SELECT `id`, `campaign` FROM `vericon`.`campaigns` WHERE `group` = '" . mysql_real_escape_string($group[0]) . "' ORDER BY `id` ASC") or die(mysql_error());
	while ($campaign = mysql_fetch_row($q1))
	{
		echo "<option value='$campaign[0]'>$campaign[1]</option>";
	}
}
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
<button onclick="Admin05_Edit_Centre_Submit()" class="btn">Submit</button> <button onclick="Admin05_Edit_Centre_Cancel()" class="btn" style="margin-left:10px; margin-top:10px;">Cancel</button>
</td>
</tr>
</table>
</div></center>

<script>
var c_campaigns = "<?php echo $data["campaign"]; ?>".split(',');
for (i = 0; i < c_campaigns.length; i++)
{
	l = "#Admin05_campaign option[value='" + c_campaigns[i] + "']";
	$( l ).attr("selected","selected");
}

$( "#Admin05_type" ).val("<?php echo $data["type"]; ?>");
$( "#Admin05_leads" ).val("<?php echo $data["leads"]; ?>");
</script>