<?php
$referer = $_SERVER['SERVER_NAME'] . "/";
$referer_check = split("//", $_SERVER['HTTP_REFERER']);
if ($referer_check[1] != $referer)
{
	header("Location: /");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VeriCon :: Login</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../jquery/css/vc-theme/jquery-ui.custom.min.css">
<script src="../jquery/js/jquery.js"></script>
<script src="../jquery/js/jquery-ui.custom.min.js"></script>
<script src="../js/blockUI.js"></script>
<link href="../css/login.css" rel="stylesheet" type="text/css" />
<?php
define('SALT', 'IISp3dwbJu4UuMxWJWSfLrzR');

function decrypt($text)
{
	return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, SALT, base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
}

if (!isset($_COOKIE["vc_username"]))
{
	$username = "";
}
else
{
	$username = decrypt($_COOKIE["vc_username"]);
}

if (!isset($_COOKIE["vc_tracker"]))
{
	$tracker = hash('whirlpool', $_SERVER['REMOTE_ADDR'] . rand() . date("Y-m-d H:i:s"));
	setcookie("vc_tracker", $tracker, strtotime("+1 month"), '/');
}
else
{
	$tracker = $_COOKIE["vc_tracker"];
	setcookie("vc_tracker", $tracker, strtotime("+1 month"), '/');
}
?>
<script>
var cookie_check = 1;
if (document.cookie == "") 
{
	cookie_check = 0;
}
</script>
<script>
function V_Loading_Start()
{
	$.blockUI({ 
		message: '<div class="loading_message"><p><img src="../images/v_loading.gif"></p><b>Loading...</b></div>',
		overlayCSS: {
			cursor: 'default'
		},
		css: { 
			border: 'none', 
			padding: '15px', 
			backgroundColor: '#000', 
			'border-radius': '10px', 
			'-webkit-border-radius': '10px', 
			'-moz-border-radius': '10px', 
			opacity: .5, 
			color: '#fff',
			cursor: 'default'
		}
	});
}

function V_Loading_End()
{
	$.unblockUI();
}
</script>
<script>
function Error(text)
{
	$( "#login_error" ).html('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p style="padding: 9px 0;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>' + text + '</p></div>');
}
</script>
<script>
function Login()
{
	V_Loading_Start();
	
	var username = $( "#username" ),
		password = $( "#password" ),
		remember = 0;

	if ($( "#checkbox" ).attr('checked'))
	{
		remember = 1;
	}
	
	$.post("/auth/login.php", { username: username.val(), password: password.val(), tracker: "<?php echo $tracker; ?>", remember: remember }, function(data) {
		response = data.split("=");
		if (response[0] == "token")
		{
			var token = response[1];
			setTimeout(function() {
				V_Loading_End();
				window.location = "/main";
			}, 500);
		}
		else
		{
			setTimeout(function() {
				V_Loading_End();
				Error(data);
			}, 500);
		}
	}).error( function(xhr, text, err) {
		$(".loading_message").html("<p><b>An error occurred while performing this action.</b></p><p><b>Error: " + xhr.status + " " + xhr.statusText + "</b></p>");
		setTimeout(function() {
			V_Loading_End();
		}, 2500);
	});
}
</script>
</head>

<body>
<?php
$mysqli = new mysqli('localhost','vericon','18450be');
?>
<div id="preload">
<img src="../images/checkbox.png" /><img src="../images/checkbox_checked.png" /><img src="../images/v_loading.gif" />
</div>

<div id="wrapper">
<div id="container">
<div class="contents">
<div class="left_side">
<h1>Welcome to VeriCon...</h1>
<p>The VeriCon system is our inhouse unified sales and support portal, a CRM.</p>
<p>This system has been designed with the intention of simplifying your tasks as<br />
an end user of this system.</p>
<p>VeriCon provides for a secure method of tracking the end to end sales<br />
model used for the company.</p>
<div class="quicks">
<div class="icons"><img src="../images/icon.png" /></div>
<div class="links">
<ul>
<li>Optimising Efficiency</li>
<li>Unifying Systems</li>
<li>Streamlining Communications</li>
<li>Superior processes</li>
<li>Enhanced organization</li>
</ul>
</div>
</div>
</div>
<div class="right_side">
<div class="login">
<h1>Login here!</h1>
<div class="ui-widget" id="login_error" style="margin-right: 25px;">
<noscript>
<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;">
<p style="padding: 9px 0;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
<strong>Error: </strong>Please enable Javascript.</p>
</div>
</noscript>
<script>
if (cookie_check == 0)
{
	$( "#login_error" ).html('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p style="padding: 9px 0;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span><strong>Error: </strong>Please enable Cookies.</p></div>');
}
else
{
	$( "#login_error" ).html("<p><strong>Please enter your username and password below</strong></p>");
}
</script>
</div>

<form id="form1" name="form1" onsubmit="return false;">
<table width="422" border="0" cellspacing="0" cellpadding="0">
<tr>
<td colspan="3" height="12"></td>
</tr>
<tr>
<td width="31" align="left" id="login_username_image">
<noscript>
<img src="../images/user.png" style="opacity:0.4;" />
</noscript>
<script>
if (cookie_check == 0)
{
	$( "#login_username_image" ).html('<img src="../images/user.png" style="opacity:0.4;" />');
}
else
{
	$( "#login_username_image" ).html('<img src="../images/user.png" />');
}
</script>
</td>
<td width="81" align="left" class="font_12" id="login_username_text">
<noscript>
<strong style="opacity:0.4;">USERNAME</strong>
</noscript>
<script>
if (cookie_check == 0)
{
	$( "#login_username_text" ).html('<strong style="opacity:0.4;">USERNAME</strong>');
}
else
{
	$( "#login_username_text" ).html('<strong>USERNAME</strong>');
}
</script>
</td>
<td width="310" align="left" id="login_username">
<noscript>
<input id="username" disabled="disabled" type="text" class="input_form" autocomplete="off" placeholder="Enter your username" />
</noscript>
<script>
if (cookie_check == 0)
{
	$( "#login_username" ).html('<input id="username" disabled="disabled" type="text" class="input_form" autocomplete="off" placeholder="Enter your username" />');
}
else
{
	<?php if ($username != "") { ?>
	$( "#login_username" ).html('<input id="username" type="text" class="input_form" autocomplete="off" placeholder="Enter your username" value="<?php echo $username; ?>" />');
	<?php } else { ?>
	$( "#login_username" ).html('<input id="username" type="text" class="input_form" autocomplete="off" placeholder="Enter your username" value="" autofocus="autofocus" />');
	<?php } ?>
}
</script>
</td>
</tr>
<tr align="center">
<td height="22" colspan="3" align="left"></td>
</tr>
<tr>
<td align="left" id="login_password_image">
<noscript>
<img src="../images/pass.png" style="opacity:0.4;" />
</noscript>
<script>
if (cookie_check == 0)
{
	$( "#login_password_image" ).html('<img src="../images/pass.png" style="opacity:0.4;" />');
}
else
{
	$( "#login_password_image" ).html('<img src="../images/pass.png" />');
}
</script>
</td>
<td align="left" class="font_12" id="login_password_text">
<noscript>
<strong style="opacity:0.4;">PASSWORD</strong>
</noscript>
<script>
if (cookie_check == 0)
{
	$( "#login_password_text" ).html('<strong style="opacity:0.4;">PASSWORD</strong>');
}
else
{
	$( "#login_password_text" ).html('<strong>PASSWORD</strong>');
}
</script>
</td>
<td align="left" id="login_password">
<noscript>
<input id="password" disabled="disabled" type="password" class="input_form" autocomplete="off" placeholder="Enter your password" />
</noscript>
<script>
if (cookie_check == 0)
{
	$( "#login_password" ).html('<input id="password" disabled="disabled" type="password" class="input_form" autocomplete="off" placeholder="Enter your password" />');
}
else
{
	<?php if ($username != "") { ?>
	$( "#login_password" ).html('<input id="password" type="password" class="input_form" autocomplete="off" placeholder="Enter your password" autofocus="autofocus" />');
	<?php } else { ?>
	$( "#login_password" ).html('<input id="password" type="password" class="input_form" autocomplete="off" placeholder="Enter your password" />');
	<?php } ?>
}
</script>
</td>
</tr>
<tr>
<td height="16" colspan="3" align="left"></td>
</tr>
<tr>
<td align="left"></td>
<td align="left"></td>
<td align="left"><table width="286" border="0" cellspacing="0" cellpadding="0">
<tr>
<td width="22" align="left" style="padding-left:10px;" id="login_checkbox">
<noscript>
<input type="checkbox" disabled="disabled" id="checkbox" name="checkbox" value="checkbox" /><label for="checkbox"></label>
</noscript>
<script>
if (cookie_check == 0)
{
	$( "#login_checkbox" ).html('<input type="checkbox" disabled="disabled" id="checkbox" name="checkbox" value="checkbox" /><label for="checkbox"></label>');
}
else
{
	<?php if ($username != "") { ?>
	$( "#login_checkbox" ).html('<input type="checkbox" id="checkbox" name="checkbox" checked="checked" value="checkbox" /><label for="checkbox"></label>');
	<?php } else { ?>
	$( "#login_checkbox" ).html('<input type="checkbox" id="checkbox" name="checkbox" value="checkbox" /><label for="checkbox"></label>');
	<?php } ?>
}
</script>
</td>
<td width="141" class="font_12" id="login_checkbox_text">
<noscript>
<span style="opacity:0.4;">Remember username</span>
</noscript>
<script>
if (cookie_check == 0)
{
	$( "#login_checkbox_text" ).html('<span style="opacity:0.4;">Remember username</span>');
}
else
{
	$( "#login_checkbox_text" ).html('<span>Remember username</span>');
}
</script>
</td>
<td width="116" id="login_submit">
<noscript>
<input name="Submit" type="submit" disabled="disabled" class="login_bu" value="Login" />
</noscript>
<script>
if (cookie_check == 0)
{
	$( "#login_submit" ).html('<input name="Submit" type="submit" disabled="disabled" class="login_bu" value="Login" />');
}
else
{
	$( "#login_submit" ).html('<input name="Submit" onclick="Login()" type="submit" class="login_bu" value="Login" />');
}
</script>
</td>
</tr>
</table></td>
</tr>
</table>
</form>
</div>
<div class="vericon">
<ul>
<p class="font_12"><strong>Fun Facts @ VeriCon</strong>
</p>
<?php
$q = $mysqli->query("SELECT `fact` FROM `vericon`.`fun_facts` WHERE `status` = 'Enabled' ORDER BY RAND() LIMIT 3") or die($mysqli->error);
while ($ff = $q->fetch_row())
{
	echo "<li>" . $ff[0] . "</li>";
}
$q->free();
?>
</ul>
</div>
</div>
</div>
</div>
<div id="footer">
<div class="left_f">
Copyrights 2011-<?php echo date("Y"); ?> | All Rights Reserved @ VeriCon<br />Designed &amp; Developed by Team VeriCon
</div>
<div class="right_f">
<img src="../images/footer_logo.png" border="0" />
<?php
$q = $mysqli->query("SELECT `subject` FROM `vericon`.`updates` ORDER BY `id` DESC LIMIT 1") or die($mysqli->error);
$ver = $q->fetch_row();

$q->free();
$mysqli->close();
?>
<div style="width:133px; text-align:right; margin-top:-5px;"><?php echo $ver[0]; ?></div>
</div>
</div>
</div>
</body>
</html>