<?php
include("../auth/restrict_inner.php");

$user = $_POST["user"];
$method = $_POST["method"];
$page = $_POST["page"];
$query = $_POST["query"];
?>
<script>
function Admin03_Edit_Error(text)
{
	$( "#Admin03_edit_error" ).html('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p style="padding: 9px 0;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>' + text + '</p></div>');
}

function Admin03_Edit_User_Cancel()
{
	$( "#Admin03_search_bar" ).removeAttr("style");
	$( "#Admin03_search" ).removeAttr("disabled");
	$( "#Admin03_create_user" ).removeAttr("disabled");
	<?php
	$q = mysql_query("SELECT `first` FROM `vericon`.`auth_temp`") or die(mysql_error());
	if (mysql_num_rows($q) > 0) {
		echo '$( "#Admin03_pending_users" ).removeAttr("disabled");';
	}
	?>
	Admin03_Display_Reload();
}

function Admin03_Portal_Select(portal)
{
	if ($.inArray("Sales",$( "#Admin03_access" ).val()) != -1)
	{
		$( "#Admin03_eu_centre_tr" ).removeAttr("style");
		$( "#Admin03_eu_designation_tr" ).removeAttr("style");
	}
	else
	{
		$( "#Admin03_eu_centre_tr" ).attr("style","display:none;");
		$( "#Admin03_eu_designation_tr" ).attr("style","display:none;");
	}
	
	if ($.inArray("CS",$( "#Admin03_access" ).val()) != -1 || $.inArray("TPV",$( "#Admin03_access" ).val()) != -1 || $.inArray("Sales",$( "#Admin03_access" ).val()) != -1)
	{
		$( "#Admin03_eu_alias_tr" ).removeAttr("style");
	}
	else
	{
		$( "#Admin03_eu_alias_tr" ).attr("style","display:none;");
	}
	
	if ($.inArray("Operations",$( "#Admin03_access" ).val()) != -1 || $.inArray("HR",$( "#Admin03_access" ).val()) != -1)
	{
		$( "#Admin03_eu_centres_tr" ).removeAttr("style");
	}
	else
	{
		$( "#Admin03_eu_centres_tr" ).attr("style","display:none;");
	}
	
	$('#users [id^="Admin03_tr_"]').attr("style","display: none;");
	
	for (var i = 0; i < $( "#Admin03_access" ).val().length; i++) {
    	$('[id^="Admin03_tr_' + $( "#Admin03_access" ).val()[i] + '"]').removeAttr("style");
	}
}

function Admin03_Edit_User_Check(field)
{
	var input = $( "#Admin03_" + field );
	
	if (field == "password2") { input2 = $( "#Admin03_password" ).val(); } else { input2 = ""; }
	
	$.post("/admin/users_process.php", { m: "check", field: field, input: input.val(), input2: input2 }, function(data) {
		if (data == "valid")
		{
			$( "#Admin03_" + field + "_check" ).html("<img src='/images/enable_icon.png'>");
			$( "#Admin03_edit_error" ).html("<p>Enter the user's details below to create the account.</p>");
		}
		else
		{
			$( "#Admin03_" + field + "_check" ).html("<img src='/images/delete_icon.png'>");
			Admin03_Edit_Error(data);
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

function Admin03_Edit_User_Submit()
{
	V_Loading_Start();
	
	var user = $( "#Admin03_user" ),
		password = $( "#Admin03_password" ),
		password2 = $( "#Admin03_password2" ),
		access = $( "#Admin03_access" ),
		centres = $( "#Admin03_centres" ),
		centre = $( "#Admin03_centre" ),
		designation = $( "#Admin03_designation" ),
		alias = $( "#Admin03_alias" ),
		pages = new Array();
		
	$('#users input:checked').each(function() {
		var page = $(this).val();
		pages.push(page);
	});
	
	$.post("/admin/users_process.php", { m: "edit", user: user.val(), password: password.val(), password2: password2.val(), access: access.val(), centres: centres.val(), centre: centre.val(), designation: designation.val(), alias: alias.val(), pages: pages }, function(data) {
		if (data == "valid")
		{
			$( "#Admin03_search_bar" ).removeAttr("style");
			$( "#Admin03_search" ).removeAttr("disabled");
			$( "#Admin03_create_user" ).removeAttr("disabled");
			<?php
			$q = mysql_query("SELECT `first` FROM `vericon`.`auth_temp`") or die(mysql_error());
			if (mysql_num_rows($q) > 0) {
				echo '$( "#Admin03_pending_users" ).removeAttr("disabled");';
			}
			?>
			Admin03_Display_Reload();
		}
		else
		{
			Admin03_Edit_Error(data);
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

<?php
$q = mysql_query("SELECT `auth`.`user`, `auth`.`type`, `auth`.`centre`, `auth`.`first`, `auth`.`last`, `auth`.`alias`, `timesheet_designation`.`designation` FROM `vericon`.`auth` LEFT JOIN `vericon`.`timesheet_designation` ON `auth`.`user` = `timesheet_designation`.`user` WHERE `auth`.`user` = '" . mysql_real_escape_string($user) . "'") or die(mysql_error());
$data = mysql_fetch_assoc($q);
?>

<center><div style="width:98%;">
<table width="100%">
<tr>
<td width="50%" valign="top" rowspan="2">
<center><h2>User Details</h2></center>
<table width="100%">
<tr>
<td width="269px" height="2px"><div class="line_left"></div></td>
<td height="2px"><div class="line_repeat"></div></td>
<td width="269px" height="2px"><div class="line_right"></div></td>
</tr>
</table>
<table>
<tr>
<td colspan="2" id="Admin03_edit_error"><p>Edit the user's details below.</p></td>
<td></td>
</tr>
<tr>
<td width="105px">Username </td>
<td><input id="Admin03_user" type="text" value="<?php echo $data["user"]; ?>" disabled="disabled"></td>
<td></td>
</tr>
<tr>
<td>First Name </td>
<td><input id="Admin03_first" value="<?php echo $data["first"]; ?>" disabled="disabled" type="text"></td>
<td id="Admin03_first_check" style="padding-left:5px;"></td>
</tr>
<tr>
<td>Last Name </td>
<td><input id="Admin03_last" value="<?php echo $data["last"]; ?>" disabled="disabled" type="text"></td>
<td id="Admin03_last_check" style="padding-left:5px;"></td>
</tr>
<tr>
<td>Password </td>
<td><input id="Admin03_password" onchange="Admin03_Edit_User_Check('password')" placeholder="Leave blank unless you want to change it" type="password"></td>
<td id="Admin03_password_check" style="padding-left:5px;"></td>
</tr>
<tr>
<td>Re-Type Password </td>
<td><input id="Admin03_password2" onchange="Admin03_Edit_User_Check('password2')" placeholder="Leave blank unless you want to change it" type="password"></td>
<td id="Admin03_password2_check" style="padding-left:5px;"></td>
</tr>
<tr>
<td>Department<span style="color:#ff0000;">*</span> </td>
<td><select id="Admin03_access" multiple="multiple">
<?php
$q = mysql_query("SELECT `id`, `name` FROM `vericon`.`portals` WHERE `id` != 'MA' AND `status` = 'Enabled' ORDER BY `name` ASC") or die(mysql_error());
while($portals = mysql_fetch_row($q))
{
	echo "<option value='$portals[0]' onclick='Admin03_Portal_Select(\"$portals[0]\")'>" . $portals[1] . "</option>";
}
?>
</select></td>
<td></td>
</tr>
<tr id="Admin03_eu_centres_tr" style="display:none;">
<td>Centres<span style="color:#ff0000;">*</span> </td>
<td><select id="Admin03_centres" multiple="multiple">
<option>All</option>
<option>Captive</option>
<option>Melbourne</option>
<option disabled="disabled">---------------------------------------------------------</option>
<?php
$q = mysql_query("SELECT `id` FROM `vericon`.`centres` WHERE `id` != '' AND `status` = 'Enabled' ORDER BY `id` ASC") or die(mysql_error());
while($centres = mysql_fetch_row($q))
{
	echo "<option>" . $centres[0] . "</option>";
}
?>
</select></td>
<td></td>
</tr>
<tr id="Admin03_eu_centre_tr" style="display:none;">
<td>Centre<span style="color:#ff0000;">*</span> </td>
<td><select id="Admin03_centre">
<option></option>
<?php
$q = mysql_query("SELECT `id` FROM `vericon`.`centres` WHERE `id` != '' AND `status` = 'Enabled' ORDER BY `id` ASC") or die(mysql_error());
while($centres = mysql_fetch_row($q))
{
	echo "<option>" . $centres[0] . "</option>";
}
?>
</select></td>
<td></td>
</tr>
<tr id="Admin03_eu_designation_tr" style="display:none;">
<td>Designation<span style="color:#ff0000;">*</span> </td>
<td><select id="Admin03_designation">
<option></option>
<option>Team Leader</option>
<option>Closer</option>
<option>Agent</option>
<option>Probation</option>
</select></td>
<td></td>
</tr>
<tr id="Admin03_eu_alias_tr" style="display:none;">
<td>Alias<span style="color:#ff0000;">*</span> </td>
<td><input id="Admin03_alias" onchange="Admin03_Edit_User_Check('alias')" value="<?php echo $data["alias"]; ?>" type="text"></td>
<td id="Admin03_alias_check" style="padding-left:5px;"></td>
</tr>
</table>
</td>
<td width="50%" valign="top">
<center><h2>Page Access</h2></center>
<table width="100%">
<tr>
<td width="269px" height="2px"><div class="line_left"></div></td>
<td height="2px"><div class="line_repeat"></div></td>
<td width="269px" height="2px"><div class="line_right"></div></td>
</tr>
</table>
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%;">
<thead>
<tr class="ui-widget-header ">
<th width="10%"></th>
<th width="20%">ID</th>
<th width="35%">Portal</th>
<th width="35%">Page</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT * FROM `vericon`.`portals_pages` WHERE `portal` = 'MA' ORDER BY `id` ASC") or die(mysql_error());
while ($da = mysql_fetch_assoc($q))
{
	if ($da["sub_level"] != 0) { $level = $da["level"] . " - " . $da["sub_level"]; } else { $level = $da["level"]; }
	
	$checkbox = "<input type='checkbox' checked='checked' disabled='disabled' style='height: auto;' value='$da[id]' name='$da[id]'><label for='$da[id]'></label>";
	
	echo "<tr>";
	echo "<td style='text-align:center; padding: .3em 5px;'>" . $checkbox . "</td>";
	echo "<td style='padding: .3em 5px;'>" . $da["id"] . "</td>";
	echo "<td style='padding: .3em 5px;'>My Account</td>";
	echo "<td style='padding: .3em 5px;'>" . $da["name"] . "</td>";
	echo "</tr>";
}

$q = mysql_query("SELECT `portals_pages`.`id`, `portals_pages`.`name`, `portals_pages`.`level`, `portals_pages`.`sub_level`, `portals`.`name` AS portal_name FROM `vericon`.`portals_pages`, `vericon`.`portals` WHERE `portals_pages`.`portal` != 'MA' AND `portals_pages`.`portal` = `portals`.`id` ORDER BY `portals_pages`.`id` ASC") or die(mysql_error());
while ($da = mysql_fetch_assoc($q))
{
	if ($da["sub_level"] != 0) { $level = $da["level"] . " - " . $da["sub_level"]; } else { $level = $da["level"]; }
	
	if ($da["level"] == 1)
	{
		$checkbox = "<input type='checkbox' checked='checked' disabled='disabled' style='height: auto;' value='$da[id]' id='checkbox_$da[id]'><label for='checkbox_$da[id]'></label>";
	}
	else
	{
		$checkbox = "<input type='checkbox' style='height: auto;' value='$da[id]' id='checkbox_$da[id]'><label for='checkbox_$da[id]'></label>";
	}
	
	echo "<tr id='Admin03_tr_$da[id]' style='display:none;'>";
	echo "<td style='text-align:center; padding: .3em 5px;'>" . $checkbox . "</td>";
	echo "<td style='padding: .3em 5px;'>" . $da["id"] . "</td>";
	echo "<td style='padding: .3em 5px;'>" . $da["portal_name"] . "</td>";
	echo "<td style='padding: .3em 5px;'>" . $da["name"] . "</td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div>
</td>
</tr>
<tr>
<td align="right" valign="bottom">
<button onclick="Admin03_Edit_User_Submit()" class="btn">Submit</button> <button onclick="Admin03_Edit_User_Cancel()" class="btn" style="margin-left:10px;">Cancel</button>
</td>
</tr>
</table>
</div></center>

<script>
var acc_type = "<?php echo $data["type"]; ?>".split(',');
for (i = 0; i < acc_type.length; i++)
{
	l = "#Admin03_access option[value='" + acc_type[i] + "']";
	$( l ).attr("selected","selected");
}

$( "#Admin03_centre" ).val("<?php echo $data["centre"]; ?>");
$( "#Admin03_designation" ).val("<?php echo $data["designation"]; ?>");

if ($.inArray("Sales",$( "#Admin03_access" ).val()) != -1)
{
	$( "#Admin03_eu_centre_tr" ).removeAttr("style");
	$( "#Admin03_eu_designation_tr" ).removeAttr("style");
}
else
{
	$( "#Admin03_eu_centre_tr" ).attr("style","display:none;");
	$( "#Admin03_eu_designation_tr" ).attr("style","display:none;");
}

if ($.inArray("CS",$( "#Admin03_access" ).val()) != -1 || $.inArray("TPV",$( "#Admin03_access" ).val()) != -1 || $.inArray("Sales",$( "#Admin03_access" ).val()) != -1)
{
	$( "#Admin03_eu_alias_tr" ).removeAttr("style");
}
else
{
	$( "#Admin03_eu_alias_tr" ).attr("style","display:none;");
}

if ($.inArray("Operations",$( "#Admin03_access" ).val()) != -1 || $.inArray("HR",$( "#Admin03_access" ).val()) != -1)
{
	$( "#Admin03_eu_centres_tr" ).removeAttr("style");
}
else
{
	$( "#Admin03_eu_centres_tr" ).attr("style","display:none;");
}

$('#users [id^="Admin03_tr_"]').attr("style","display: none;");

for (var i = 0; i < $( "#Admin03_access" ).val().length; i++) {
	$('[id^="Admin03_tr_' + $( "#Admin03_access" ).val()[i] + '"]').removeAttr("style");
}
</script>

<input type="hidden" id="Admin03_method" value="<?php echo $method; ?>" />
<input type="hidden" id="Admin03_page" value="<?php echo $page; ?>" />
<input type="hidden" id="Admin03_query" value="<?php echo $query; ?>" />