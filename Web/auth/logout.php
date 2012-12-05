<?php
mysql_connect('localhost','vericon','18450be');

$referer = $_SERVER['SERVER_NAME'] . "/main/";
$referer_check = split("//", $_SERVER['HTTP_REFERER']);
if ($_SERVER["REQUEST_METHOD"] != "POST" || $referer_check[1] != $referer)
{
	header('HTTP/1.1 404 Not Found');
	header('Content-Type: text/html; charset=iso-8859-1');
?>
<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL <?php echo $_SERVER['REQUEST_URI']; ?> was not found on this server.</p>
<hr>
<address><?php echo $_SERVER['SERVER_SIGNATURE']; ?></address>
</body></html>
<?php
	exit;
}

$token = $_COOKIE["vc_token"];;

if($token == ""){ exit; }

mysql_query("DELETE FROM `vericon`.`current_users` WHERE `token` = '" . mysql_real_escape_string($token) . "' LIMIT 1") or die(mysql_error());

setcookie('vc_token', null, time()-3600, '/');
?>