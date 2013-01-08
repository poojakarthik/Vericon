<?php
include("../auth/restrict_inner.php");
$mysqli->close();
?>
<script>
function Admin06_Add_Error(text)
{
	$( "#Admin06_add_error" ).html('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p style="padding: 9px 0;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>' + text + '</p></div>');
}

function Admin06_Add_Portal_Cancel()
{
	V_Page_Reload();
}

function Admin06_Add_Portal_Submit()
{
	V_Loading_Start();
	
	var id = $( "#Admin06_id" ),
		name = $( "#Admin06_name" );
		
	$.post("/admin/portals_process.php", { m: "add", id: id.val(), name: name.val() }, function(data) {
		if (data == "valid")
		{
			V_Page_Reload();
		}
		else
		{
			Admin06_Add_Error(data);
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
<td colspan="2" id="Admin06_add_error"><p>Enter the details below to add the portal.</p></td>
</tr>
<tr>
<td width="85px">ID<span style="color:#ff0000;">*</span> </td>
<td><input id="Admin06_id" type="text"></td>
</tr>
<tr>
<td>Name<span style="color:#ff0000;">*</span> </td>
<td><input id="Admin06_name" type="text"></td>
</tr>
<tr>
<td colspan="2" align="right" valign="bottom">
<button onclick="Admin06_Add_Portal_Submit()" class="btn">Submit</button> <button onclick="Admin06_Add_Portal_Cancel()" class="btn" style="margin-left:10px; margin-top:10px;">Cancel</button>
</td>
</tr>
</table>
</div></center>