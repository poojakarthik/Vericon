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

$q = $mysqli->query("SELECT `auth`.`user`, `auth`.`pass`, `auth`.`type`, `auth`.`centre`, `auth`.`status`, `auth`.`first`, `auth`.`last`, `auth`.`alias` FROM `vericon`.`auth`, `vericon`.`current_users` WHERE `current_users`.`token` = '" . $mysqli->real_escape_string($token) . "' AND `current_users`.`user` = `auth`.`user`") or die($mysqli->error);
$ac = $q->fetch_assoc();

if ($q->num_rows == 0)
{
	header('HTTP/1.1 403 Forbidden');
	exit;
}

$q->free();

if ($ac["status"] != "Enabled")
{
	header('HTTP/1.1 403 Forbidden');
	exit;
}

$q = $mysqli->query("SELECT * FROM `vericon`.`mail_pending` WHERE `user` = '" . $mysqli->real_escape_string($ac["user"]) . "'") or die($mysqli->error);
if ($q->num_rows != 0)
{
	echo "<h1>Your email account is currrently under maintenance, please check back in 2 minutes.</h1>";
	exit;
}

$q->free();
$mysqli->close();
?>
<script type="text/javascript" src="/jquery/js/jquery.js"></script>

<form id="form" name="form" action="/mail/" method="post">
<input type="hidden" name="_action" value="login" />
<input type="hidden" name="_task" value="login" />
<input type="hidden" name="_autologin" value="1" />
</form>

<script>
$( "#form" ).submit();
</script>