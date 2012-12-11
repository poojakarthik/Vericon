<?php
include("../auth/restrict_inner.php");
?>
<script>
function Admin04_Create_Error(text)
{
	$( "#Admin04_create_error" ).html('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p style="padding: 9px 0;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>' + text + '</p></div>');
}

function Admin04_Add_IP_Cancel()
{
	V_Page_Reload();
}

function Admin04_Add_IP_Submit()
{
	V_Loading_Start();
	
	var ip_start = $( "#Admin04_ip_start" ),
		ip_end = $( "#Admin04_ip_end" ),
		description = $( "#Admin04_description" );
		
	$.post("/admin/whitelist_process.php", { m: "add", ip_start: ip_start.val(), ip_end: ip_end.val(), description: description.val() }, function(data) {
		if (data.substring(0,5) == "valid")
		{
			$( "#Admin04_search_bar" ).removeAttr("style");
			$( "#Admin04_search" ).removeAttr("disabled");
			$( "#Admin04_add_ip" ).removeAttr("disabled");
			Admin04_Search("IPs",data.substring(5));
		}
		else
		{
			Admin04_Create_Error(data);
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
<td colspan="2" id="Admin04_create_error"><p>Enter the details below to add the IP range.</p></td>
</tr>
<tr>
<td width="85px">IP Start<span style="color:#ff0000;">*</span> </td>
<td><input id="Admin04_ip_start" type="text"></td>
</tr>
<tr>
<td>IP End<span style="color:#ff0000;">*</span> </td>
<td><input id="Admin04_ip_end" type="text"></td>
</tr>
<tr>
<td>Description<span style="color:#ff0000;">*</span> </td>
<td><textarea id="Admin04_description" style="resize:none;"></textarea></td>
</tr>
<tr>
<td colspan="2" align="right" valign="bottom">
<button onclick="Admin04_Add_IP_Submit()" class="btn">Submit</button> <button onclick="Admin04_Add_IP_Cancel()" class="btn" style="margin-left:10px; margin-top:10px;">Cancel</button>
</td>
</tr>
</table>
</div></center>