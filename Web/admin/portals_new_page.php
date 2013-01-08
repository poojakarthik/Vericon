<?php
include("../auth/restrict_inner.php");
$mysqli->close();

$portal_id = $_POST["portal_id"];
?>
<script>
function Admin06_Add_Error(text)
{
	$( "#Admin06_add_error" ).html('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p style="padding: 9px 0;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>' + text + '</p></div>');
}

function Admin06_Add_Page_Cancel()
{
	Admin06_Edit_Portal("<?php echo $portal_id; ?>");
}

function Admin06_Add_Page_Submit()
{
	V_Loading_Start();
	
	var name = $( "#Admin06_name" ),
		p_link = $( "#Admin06_link" ),
		jquery = $( "#Admin06_jquery" ),
		level = $( "#Admin06_level" ),
		sub_level = $( "#Admin06_sub_level" );
		
	$.post("/admin/portals_process.php", { m: "page_add", portal_id: "<?php echo $portal_id; ?>", name: name.val(), p_link: p_link.val(), jquery: jquery.val(), level: level.val(), sub_level: sub_level.val() }, function(data) {
		if (data == "valid")
		{
			Admin06_Edit_Portal("<?php echo $portal_id; ?>");
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
<td colspan="2" id="Admin06_add_error"><p>Enter the details below to add the page.</p></td>
</tr>
<tr>
<td width="85px">Name<span style="color:#ff0000;">*</span> </td>
<td><input id="Admin06_name" type="text"></td>
</tr>
<tr>
<td>Link/jQuery Call </td>
<td><input id="Admin06_link" type="text" placeholder="Leave Blank if Sub Menu Parent"></td>
</tr>
<tr>
<td>jQuery Code </td>
<td><textarea id="Admin06_jquery" style="resize:none;"></textarea></td>
</tr>
<tr>
<td>Level<span style="color:#ff0000;">*</span> </td>
<td><input id="Admin06_level" type="text"></td>
</tr>
<tr>
<td>Sub-Level<span style="color:#ff0000;">*</span> </td>
<td><input id="Admin06_sub_level" type="text" placeholder="0 for Main Menu"></td>
</tr>
<tr>
<td colspan="2" align="right" valign="bottom">
<button onclick="Admin06_Add_Page_Submit()" class="btn">Submit</button> <button onclick="Admin06_Add_Page_Cancel()" class="btn" style="margin-left:10px; margin-top:10px;">Cancel</button>
</td>
</tr>
</table>
</div></center>