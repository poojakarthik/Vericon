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

$q = mysql_query("SELECT `auth`.`user`, `auth`.`type`, `auth`.`centre`, `auth`.`status`, `auth`.`first`, `auth`.`last`, `auth`.`alias` FROM `vericon`.`auth`, `vericon`.`current_users` WHERE `current_users`.`token` = '" . mysql_real_escape_string($token) . "' AND `current_users`.`user` = `auth`.`user`") or die(mysql_error());
$ac = mysql_fetch_assoc($q);

$v_page = explode("/", $_SERVER['REQUEST_URI']);

$q = mysql_query("SELECT `id`,`status` FROM `vericon`.`portals_pages` WHERE `portal` = '" . mysql_real_escape_string($v_page[1]) . "' AND `link` = '" . mysql_real_escape_string($v_page[2]) . "'") or die(mysql_error());
$v_page_id = mysql_fetch_row($q);

$q = mysql_query("SELECT `pages` FROM `vericon`.`portals_access` WHERE `user` = '" . mysql_real_escape_string($ac["user"]) . "'") or die(mysql_error());
$ap = mysql_fetch_row($q);
$access_pages = explode(",", $ap[0]);

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

if ($v_page_id[1] != "Enabled")
{
	header('HTTP/1.1 403 Forbidden');
	include("../error/forbidden.php");
	exit;
}

if (!in_array($v_page_id[0], $access_pages) && $ac["type"] != "Admin")
{
	header('HTTP/1.1 403 Forbidden');
	include("../error/forbidden.php");
	exit;
}

mysql_query("UPDATE `vericon`.`current_users` SET `current_page` = '" . mysql_real_escape_string($v_page_id[0]) . "', `last_action` = NOW() WHERE `user` = '" . mysql_real_escape_string($ac["user"]) . "'") or die(mysql_error());

mysql_query("INSERT INTO `logs`.`access` (`user`, `ip`, `page`, `timestamp`) VALUES ('" . mysql_real_escape_string($ac["user"]) . "', '" . mysql_real_escape_string(ip2long($_SERVER['REMOTE_ADDR'])) . "' ,'" . mysql_real_escape_string($v_page_id[0]) . "', NOW())") or die(mysql_error());
?>