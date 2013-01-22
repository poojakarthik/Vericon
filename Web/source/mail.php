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

$q = $mysqli->query("SELECT `auth`.`user`, `auth`.`pass`, `auth`.`type`, `auth`.`centre`, `auth`.`status`, `auth`.`first`, `auth`.`last`, `auth`.`alias` FROM `vericon`.`auth`, `vericon`.`current_users` WHERE `current_users`.`token` = '" . $mysqli->real_escape_string($token) . "' AND `current_users`.`user` = `auth`.`user`") or die($mysqli->error);
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

function CountUnreadMails($host, $login, $passwd)
{
	$mbox = imap_open("{{$host}:993/ssl}", $login, $passwd);
	
	$count = 0;
	if (!$mbox)
	{
		$count = "0";
	}
	else
	{
		$headers = imap_headers($mbox);
		foreach ($headers as $mail)
		{
			$flags = substr($mail, 0, 4);
			$isunr = (strpos($flags, "U") !== false);
			if ($isunr) {
				$count++;
			}
		}
	}
	imap_close($mbox);
	return $count;
}

$q = $mysqli->query("SELECT * FROM `vericon`.`mail_pending` WHERE `user` = '" . $mysqli->real_escape_string($ac["user"]) . "'") or die($mysqli->error);
if ($q->num_rows != 0)
{
	$email_count_text = "Inbox (??)";
}
else
{
	$email_count = CountUnreadMails('mail.vericon.com.au', $ac["user"], $ac["pass"]);
	if ($email_count > 0) { $email_count_text = "Inbox <b>(" . $email_count . ")</b>"; } else { $email_count_text = "Inbox (" . $email_count . ")"; }
}
$q->free();

echo $email_count_text;

$mysqli->close();
?>