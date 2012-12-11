<?php
include("../auth/restrict_inner.php");
?>
<script>
function Admin07_Add_Error(text)
{
	$( "#Admin07_add_error" ).html('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p style="padding: 9px 0;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>' + text + '</p></div>');
}

function Admin07_Add_Message_Cancel()
{
	V_Page_Reload();
}

var recipient = "";
function Admin07_Recipient(object)
{
	if (object == "all") {
		$( "#Admin07_department_tr" ).attr("style","display:none;");
		$( "#Admin07_user_tr" ).attr("style","display:none;");
	} else if (object == "department") {
		$( "#Admin07_department_tr" ).removeAttr("style");
		$( "#Admin07_user_tr" ).attr("style","display:none;");
	} else if (object == "user") {
		$( "#Admin07_department_tr" ).attr("style","display:none;");
		$( "#Admin07_user_tr" ).removeAttr("style");
	}
	recipient = object;
}

$(function() {
	$( "#Admin07_user_box" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "/admin/users_search.php",
				dataType: "json",
				type: "POST",
				data: {
					term: request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 2,
		select: function (event, ui) {
			$( "#Admin07_user" ).val(ui.item.id);
		}
	});
});

function Admin07_Add_Message_Submit()
{
	V_Loading_Start();
	
	var department = $( "#Admin07_department" ),
		user = $( "#Admin07_user" ),
		expiry = $( "#Admin07_expiry" ),
		title = $( "#Admin07_title" ),
		message = $( "#Admin07_message" );
	
	$.post("/admin/broadcast_process.php", { m: "add", recipient: recipient, department: department.val(), user: user.val(), expiry: expiry.val(), title: title.val(), message: message.val() }, function(data) {
		if (data == "valid")
		{
			V_Page_Reload();
		}
		else
		{
			Admin07_Add_Error(data);
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
<td colspan="2" id="Admin07_add_error"><p>Enter below the message that you wish to broadcast.</p></td>
</tr>
<tr>
<td width="85px">Recipient<span style="color:#ff0000;">*</span> </td>
<td><input type="radio" name="Admin07_receiver" onClick="Admin07_Recipient('all')" id="Admin07_receiver_all" value="all"><label for="Admin07_receiver_all"></label><span style="vertical-align:middle;"> All </span><input type="radio" name="Admin07_receiver" onClick="Admin07_Recipient('department')" id="Admin07_receiver_department" value="department"><label for="Admin07_receiver_department"></label><span style="vertical-align:middle;"> Department </span><input type="radio" name="Admin07_receiver" onClick="Admin07_Recipient('user')" id="Admin07_receiver_user" value="user"><label for="Admin07_receiver_user"></label><span style="vertical-align:middle;"> User </span></td>
</tr>
<tr id="Admin07_department_tr" style="display:none;">
<td>Department<span style="color:#ff0000;">*</span> </td>
<td><select id="Admin07_department">
<option></option>
<?php
$q = mysql_query("SELECT `id`, `name` FROM `vericon`.`portals` WHERE `id` != 'MA' AND `status` = 'Enabled' ORDER BY `name` ASC") or die(mysql_error());
while($portals = mysql_fetch_row($q))
{
	echo "<option value='$portals[0]'>" . $portals[1] . "</option>";
}
?>
</select></td>
</tr>
<tr id="Admin07_user_tr" style="display:none;">
<td>User<span style="color:#ff0000;">*</span> </td>
<td><input type="hidden" id="Admin07_user" value="" /><input type="text" id="Admin07_user_box" /></td>
</tr>
<tr>
<td>Expiry Time<span style="color:#ff0000;">*</span> </td>
<td><input type="text" id="Admin07_expiry" placeholder="HH:MM" style="width:75px;" /></td>
</tr>
<tr>
<td>Title<span style="color:#ff0000;">*</span> </td>
<td><input type="text" id="Admin07_title" /></td>
</tr>
<tr>
<td>Message<span style="color:#ff0000;">*</span> </td>
<td><textarea id="Admin07_message" style="resize:none; width:450px;"></textarea></td>
</tr>
<tr>
<td colspan="2" align="right" valign="bottom">
<button onclick="Admin07_Add_Message_Submit()" class="btn">Submit</button> <button onclick="Admin07_Add_Message_Cancel()" class="btn" style="margin-left:10px; margin-top:10px;">Cancel</button>
</td>
</tr>
</table>
</div></center>