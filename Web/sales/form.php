<?php
include("../auth/restrict.php");
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; background:none; }
div#users-contain table th { border: 1px solid rgba(41,171,226,0.25); padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid rgba(41,171,226,0.25); padding: .6em 5px; text-align: left; }
div#users-contain table tbody tr:hover { background:rgba(255,255,255,0.25); }
</style>
<script>
function Sales02_Error(text)
{
	$( "#Sales02_error" ).html('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p style="padding: 9px 0;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>' + text + '</p></div>');
}

function Sales02_Load_Form()
{
	V_Loading_Start();
	
	var lead = $( "#Sales02_lead_id" ),
		campaign = $( "#Sales02_campaign" ),
		type = $( "#Sales02_type" );
	
	$.post("/sales/form_process.php", { m: "load_form", lead: lead.val(), campaign: campaign.val(), type: type.val() }, function(data) {
		var result = data.split("_");
		if (result[0] == "valid")
		{
			$( "#display" ).load("/sales/form_new_" + result[1] + ".php", { lead: lead.val() }, function(data, status, xhr){
				if (status == "error")
				{
					if (xhr.status == 403 || xhr.status == 0)
					{
						$(".loading_message").html("<p><b>Your session has expired.</b></p><p><b>You will be logged out shortly.</b></p>");
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
				}
				else
				{
					V_Loading_End();
				}
			});
		}
		else
		{
			V_Loading_End();
			Sales02_Error(data);
		}
	}).error( function(xhr, text, err) {
		if (xhr.status == 403 || xhr.status == 0)
		{
			$(".loading_message").html("<p><b>Your session has expired.</b></p><p><b>You will be logged out shortly.</b></p>");
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

<div class="head">
<table>
<tr>
<td style="width:90px;"><div class="dotted"></div></td>
<td valign="middle" nowrap="nowrap" width="1px"><h1>Sales Form</h1></td>
<td><div class="dotted"></div></td>
</tr>
</table>
</div>

<center><div style="width:98%;">
<table width="100%">
<tr>
<td width="50%" valign="top" rowspan="2">
<center><h2>Sale Details</h2></center>
<table width="100%">
<tr>
<td width="269px" height="2px"><div class="line_left"></div></td>
<td height="2px"><div class="line_repeat"></div></td>
<td width="269px" height="2px"><div class="line_right"></div></td>
</tr>
</table>
<form onsubmit="event.preventDefault();">
<table>
<tr>
<td colspan="2" id="Sales02_error"><p>Enter the lead details below to initiate the sales form.</p></td>
</tr>
<tr>
<td width="105px">Lead ID<span style="color:#ff0000;">*</span> </td>
<td><input id="Sales02_lead_id" autocomplete="off" type="text"></td>
</tr>
<tr>
<td>Campaign<span style="color:#ff0000;">*</span> </td>
<td><select id="Sales02_campaign">
<option></option>
<?php
$q = $mysqli->query("SELECT `campaigns`.`id`, `campaigns`.`campaign` FROM `vericon`.`campaigns`, `vericon`.`centre_campaigns` WHERE `centre_campaigns`.`centre` = '" . $mysqli->real_escape_string($ac["centre"]) . "' AND `centre_campaigns`.`campaign` = `campaigns`.`id`") or die($mysqli->error);
while ($campaign = $q->fetch_row())
{
	echo "<option value='" . $campaign[0] . "'>" . $campaign[1] . "</option>";
}
?>
</select></td>
</tr>
<tr>
<td>Type<span style="color:#ff0000;">*</span> </td>
<td><select id="Sales02_type">
<option></option>
<option>Business</option>
<option>Residential</option>
</select></td>
</tr>
<tr>
<td colspan="2" align="right"><button type="submit" class="btn" onclick="Sales02_Load_Form()" style="margin-top:5px;">Load Form</button></td>
</tr>
</table>
</form>
</td>
<td width="50%" valign="top">
<center><h2>Previous Sales</h2></center>
<table width="100%">
<tr>
<td width="269px" height="2px"><div class="line_left"></div></td>
<td height="2px"><div class="line_repeat"></div></td>
<td width="269px" height="2px"><div class="line_right"></div></td>
</tr>
</table>
<center>
<table style="width:95%">
<tr>
<td><p>Below are your 5 most recent sales.</p></td>
</tr>
</table>
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:95%; margin-top:0;">
<thead>
<tr class="ui-widget-header ">
<th width="20%">ID</th>
<th width="20%">Status</th>
<th width="60%">Customer Name</th>
</tr>
</thead>
<tbody>
<?php
$q = $mysqli->query("SELECT `id`, `status`, CONCAT(`firstname`,' ',`lastname`) FROM `vericon`.`sales_customers` WHERE `user` = '" . $mysqli->real_escape_string($ac["user"]) . "' ORDER BY `timestamp` DESC LIMIT 5") or die($mysqli->error);
if ($q->num_rows == 0)
{
	echo "<tr>";
	echo "<td colspan='3' style='text-align:center;'>No Sales</td>";
	echo "</tr>";
}
else
{
	while ($data = $q->fetch_row())
	{
		echo "<tr>";
		echo "<td>" . $data[0] . "</td>";
		echo "<td>" . $data[1] . "</td>";
		echo "<td>" . $data[2] . "</td>";
		echo "</tr>";
	}
}
$q->free();
$mysqli->close();
?>
</tbody>
</table>
</div></center>
</td>
</tr>
</table>
</div></center>

<script>
$( "#Sales02_lead_id" ).focus();
</script>