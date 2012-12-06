<?php
mysql_connect('localhost','vericon','18450be');

$forbidden = "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL " . $_SERVER['REQUEST_URI'] . " was not found on this server.</p>
<hr>
<address>" . $_SERVER['SERVER_SIGNATURE'] . "</address>
</body></html>";

function CheckAccess()
{
	$q = mysql_query("SELECT * FROM `vericon`.`allowedip` WHERE '" . mysql_real_escape_string($_SERVER['REMOTE_ADDR']) . "' BETWEEN INET_NTOA(`ip_start`) AND INET_NTOA(`ip_end`) AND `status` = 'Enabled'") or die(mysql_error());
	
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
	header('HTTP/1.1 404 Not Found');
	header('Content-Type: text/html; charset=iso-8859-1');
	echo $forbidden;
	exit;
}

$token = $_COOKIE['vc_token'];

$q = mysql_query("SELECT `auth`.`user`, `auth`.`type`, `auth`.`centre`, `auth`.`status`, `auth`.`first`, `auth`.`last`, `auth`.`alias` FROM `vericon`.`auth`, `vericon`.`current_users` WHERE `current_users`.`token` = '" . mysql_real_escape_string($token) . "' AND `current_users`.`user` = `auth`.`user`") or die(mysql_error());
$ac = mysql_fetch_assoc($q);

if (mysql_num_rows($q) == 0)
{
	header('HTTP/1.1 420 Not Logged In');
	header('Content-Type: text/html; charset=iso-8859-1');
	echo $forbidden;
	exit;
}

if ($ac["status"] != "Enabled")
{
	header('HTTP/1.1 421 Account Disabled');
	header('Content-Type: text/html; charset=iso-8859-1');
	echo $forbidden;
	exit;
}

$term = trim($_POST["term"]);

echo '[';
//User
$q = mysql_query("SELECT `user`, CONCAT(`first`, ' ', `last`) AS name FROM `vericon`.`auth` WHERE `user` LIKE '%" . mysql_real_escape_string($term) . "%' OR CONCAT(`first`, ' ', `last`) LIKE '%" . mysql_real_escape_string($term) . "%' ORDER BY `user` ASC") or die(mysql_error());
while ($data = mysql_fetch_assoc($q))
{
	$d[] = "{ \"id\": \"" . $data["user"] . "\", \"category\": \"Users\", \"label\": \"" . $data["name"] . " (" . $data["user"] . ")\" }";
}
//Type
$q = mysql_query("SELECT `id`, `name` FROM `vericon`.`portals` WHERE `id` LIKE '%" . mysql_real_escape_string($term) . "%' OR `name` LIKE '%" . mysql_real_escape_string($term) . "%' ORDER BY `id` ASC") or die(mysql_error());
while ($data = mysql_fetch_assoc($q))
{
	$d[] = "{ \"id\": \"" . $data["id"] . "\", \"category\": \"Departments\", \"label\": \"" . $data["name"] . "\" }";
}
//Centre
/*$q = mysql_query("SELECT `id` FROM `vericon`.`centres` WHERE `id` LIKE '%" . mysql_real_escape_string($term) . "%' ORDER BY `id` ASC") or die(mysql_error());
while ($data = mysql_fetch_assoc($q))
{
	$d[] = "{ \"id\": \"" . $data["id"] . "\", \"category\": \"Centres\", \"label\": \"" . $data["id"] . "\" }";
}*/
echo implode(", ",$d);
echo ']';

sleep(10);
?>