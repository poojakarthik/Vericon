<?php
mysql_connect('localhost','vericon','18450be');

function CheckAccess()
{
	$q = mysql_query("SELECT * FROM `vericon`.`allowedip` WHERE '" . mysql_real_escape_string(ip2long($_SERVER['REMOTE_ADDR'])) . "' BETWEEN `ip_start` AND `ip_end` AND `status` = 'Enabled'") or die(mysql_error());
	
	if (mysql_num_rows($q) == 0) {
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

$q = mysql_query("SELECT `auth`.`user`, `auth`.`pass`, `auth`.`type`, `auth`.`centre`, `auth`.`status`, `auth`.`first`, `auth`.`last`, `auth`.`alias` FROM `vericon`.`auth`, `vericon`.`current_users` WHERE `current_users`.`token` = '" . mysql_real_escape_string($token) . "' AND `current_users`.`user` = `auth`.`user`") or die(mysql_error());
$ac = mysql_fetch_assoc($q);

if (mysql_num_rows($q) == 0)
{
	header('HTTP/1.1 420 Not Logged In');
	exit;
}

if ($ac["status"] != "Enabled")
{
	header('HTTP/1.1 421 Account Disabled');
	exit;
}

$q = mysql_query("SELECT * FROM `vericon`.`mail_pending` WHERE `user` = '" . mysql_real_escape_string($ac["user"]) . "'") or die(mysql_error());
if (mysql_num_rows($q) != 0)
{
	echo "<h1>Your email account is currrently under maintenance, please check back in 2 minutes.</h1>";
	exit;
}
?>
<script type="text/javascript" src="/jquery/js/jquery-1.8.3.js"></script>

<form id="form" name="form" action="/mail/" method="post">
<input type="hidden" name="_action" value="login" />
<input type="hidden" name="_task" value="login" />
<input type="hidden" name="_autologin" value="1" />
</form>

<script>
$( "#form" ).submit();
</script>