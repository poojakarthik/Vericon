<?php
$mysqli = new mysqli('localhost','vericon','18450be');

function CheckAccess()
{
	$mysqli = new mysqli('localhost','vericon','18450be');
	
	$q = $mysqli->query("SELECT * FROM `vericon`.`allowedip` WHERE '" . $mysqli->real_escape_string(ip2long($_SERVER['REMOTE_ADDR'])) . "' BETWEEN `ip_start` AND `ip_end` AND `status` = 'Enabled'") or die($mysqli->error);
	
	if ($q->num_rows == 0) {
		return false;
	} else {
		return true;
	}
	
	$q->free();
	$mysqli->close();
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
	header('HTTP/1.1 420 Not Logged In');
	exit;
}

if ($ac["status"] != "Enabled")
{
	header('HTTP/1.1 421 Account Disabled');
	exit;
}
$q->free();

$v_page = explode("/", $_SERVER['REQUEST_URI']);

$q = $mysqli->query("SELECT `id`,`status` FROM `vericon`.`portals_pages` WHERE `portal` = '" . $mysqli->real_escape_string($v_page[1]) . "' AND `link` = '" . $mysqli->real_escape_string($v_page[2]) . "'") or die($mysqli->error);
$v_page_id = $q->fetch_row();
$q->free();

$q = $mysqli->query("SELECT `pages` FROM `vericon`.`portals_access` WHERE `user` = '" . $mysqli->real_escape_string($ac["user"]) . "'") or die($mysqli->error);
$ap = $q->fetch_row();
$q->free();
$access_pages = explode(",", $ap[0]);

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

$mysqli->query("UPDATE `vericon`.`current_users` SET `current_page` = '" . $mysqli->real_escape_string($v_page_id[0]) . "', `last_action` = NOW() WHERE `user` = '" . $mysqli->real_escape_string($ac["user"]) . "'") or die($mysqli->error);

$mysqli->query("INSERT INTO `logs`.`access` (`user`, `ip`, `page`, `timestamp`) VALUES ('" . $mysqli->real_escape_string($ac["user"]) . "', '" . $mysqli->real_escape_string(ip2long($_SERVER['REMOTE_ADDR'])) . "' ,'" . $mysqli->real_escape_string($v_page_id[0]) . "', NOW())") or die($mysqli->error);
?>