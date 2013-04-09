<?php
$mysqli = new mysqli('localhost','vericon','18450be');

function CheckAccess()
{
	if (ip2long($_SERVER['REMOTE_ADDR']) != ip2long("122.129.217.194")) {
		return false;
	} else {
		return true;
	}
}

$referer = $_SERVER['SERVER_NAME'] . "/main/";
$referer_check = split("//", $_SERVER['HTTP_REFERER']);

if ($referer_check[1] != $referer || !CheckAccess())
{
	header('HTTP/1.1 403 Forbidden');
	include("../error/forbidden.php");
	exit;
}

$token = $_COOKIE['vc_token'];

$q = $mysqli->query("SELECT `auth`.`user`, `auth`.`type`, `auth`.`centre`, `auth`.`status`, `auth`.`first`, `auth`.`last`, `auth`.`alias` FROM `vericon`.`auth`, `vericon`.`current_users` WHERE `current_users`.`token` = '" . $mysqli->real_escape_string($token) . "' AND `current_users`.`user` = `auth`.`user`") or die($mysqli->error);
$ac = $q->fetch_assoc();

if ($q->num_rows == 0)
{
	header('HTTP/1.1 403 Forbidden');
	exit;
}
if ($ac["status"] != "Enabled")
{
	header('HTTP/1.1 403 Forbidden');
	exit;
}
$q->free();
$mysqli->close();
?>

<script>
function Pass_Reset_Error(text)
{
	$( "#Pass_Reset_error" ).html('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p style="padding: 9px 0;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>' + text + '</p></div>');
}

function Pass_Reset_Check(field)
{
	var input = $( "#Pass_Reset_" + field );
	
	if (field == "new_pass2") { input2 = $( "#Pass_Reset_new_pass" ).val(); } else { input2 = ""; }
	
	$.post("/ma/pass_reset_process.php", { m: "check", field: field, input: input.val(), input2: input2 }, function(data) {
		if (data == "valid")
		{
			$( "#Pass_Reset_" + field + "_check" ).html("<img src='/images/enable_icon.png'>");
			$( "#Pass_Reset_error" ).html("<p>Your password has expired. Please change it below to access VeriCon.</p>");
		}
		else
		{
			$( "#Pass_Reset_" + field + "_check" ).html("<img src='/images/delete_icon.png'>");
			Pass_Reset_Error(data);
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

function Pass_Reset_Submit()
{
	V_Loading_Start();
	
	var current_pass = $( "#Pass_Reset_current_pass" ),
		new_pass = $( "#Pass_Reset_new_pass" ),
		new_pass2 = $( "#Pass_Reset_new_pass2" );
	
	$.post("/ma/pass_reset_process.php", { m: "submit", current_pass: current_pass.val(), new_pass: new_pass.val(), new_pass2: new_pass2.val() }, function(data) {
		if (data == "valid")
		{
			$(".loading_message").html("<p><b>Your password has been reset.</b></p><p><b>You will be required to log in again shortly.</b></p>");
			setTimeout(function() {
				V_Logout();
			}, 2500);
		}
		else
		{
			Pass_Reset_Error(data);
			V_Loading_End();
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
<td valign="middle" nowrap="nowrap" width="1px"><h1>Expired Password</h1></td>
<td><div class="dotted"></div></td>
</tr>
</table>
</div>

<div style="margin-left:10px;">
<table>
<tr>
<td colspan="2" id="Pass_Reset_error"><p>Your password has expired. Please change it below to access VeriCon.</p></td>
<td></td>
</tr>
<tr>
<td width="115px">Current Password<span style="color:#ff0000;">*</span> </td>
<td width="272px"><input id="Pass_Reset_current_pass" autofocus="autofocus" autocomplete="off" type="password"></td>
<td></td>
</tr>
<tr>
<td>New Password<span style="color:#ff0000;">*</span> </td>
<td><input id="Pass_Reset_new_pass" onchange="Pass_Reset_Check('new_pass')" autocomplete="off" type="password"></td>
<td id="Pass_Reset_new_pass_check" style="padding-left:5px;"></td>
</tr>
<tr>
<td>Re-Type Password<span style="color:#ff0000;">*</span> </td>
<td><input id="Pass_Reset_new_pass2" onchange="Pass_Reset_Check('new_pass2')" autocomplete="off" type="password"></td>
<td id="Pass_Reset_new_pass2_check" style="padding-left:5px;"></td>
</tr>
<tr>
<td colspan="2" align="right"><button onClick="Pass_Reset_Submit()" class="btn" style="margin-top:5px;">Submit</button></td>
<td></td>
</tr>
</table>
</div>