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

$portal = strtolower($_POST["p"]);

$q = $mysqli->query("SELECT `pages` FROM `vericon`.`portals_access` WHERE `user` = '" . $mysqli->real_escape_string($ac["user"]) . "'") or die($mysqli->error);
$ap = $q->fetch_row();
$access_pages = explode(",", $ap[0]);
$q->free();

$q = $mysqli->query("SELECT `status` FROM `vericon`.`portals` WHERE `id` = '" . $mysqli->real_escape_string($portal) . "'") or die($mysqli->error);
$portal_status = $q->fetch_row();
$q->free();

$q = $mysqli->query("SELECT * FROM `vericon`.`portals_pages` WHERE `portal` = '" . $mysqli->real_escape_string($portal) . "' AND `status` = 'Enabled' AND `sub_level` = '0' ORDER BY `level` ASC") or die($mysqli->error);
if ($q->num_rows == 0 || $portal_status[0] != "Enabled")
{
	header('HTTP/1.1 403 Forbidden');
	include("../error/forbidden.php");
	exit;
}
else
{
	$q1 = $mysqli->query("SELECT * FROM `vericon`.`mail_pending` WHERE `user` = '" . $mysqli->real_escape_string($ac["user"]) . "'") or die($mysqli->error);
	if ($q1->num_rows != 0)
	{
		$email_count_text = "Inbox (??)";
	}
	else
	{
		$email_count = CountUnreadMails('mail.vericon.com.au', $ac["user"], $ac["pass"]);
		if ($email_count > 0) { $email_count_text = "Inbox <b>(" . $email_count . ")</b>"; } else { $email_count_text = "Inbox (" . $email_count . ")"; }
	}
	$q1->free();
	
	echo "<ul id='menu'>";
	echo "<li style='float:right;'><a onclick='V_Mail_Open()'><img border='0' src='/images/email.png' style='vertical-align:middle;'><span id='inbox' style='margin-left:7px;'>" . $email_count_text . "</span></a></li>";
	while ($pages = $q->fetch_assoc())
	{
		if (in_array($pages["id"], $access_pages) || $ac["type"] == "Admin")
		{
			if ($pages["link"] != "" && $pages["jquery"] == "")
			{
				if ($pages["name"] == "Home")
				{
					echo "<li><a id='" . $pages["id"] . "' onclick='V_Page_Load(\"" . $pages["id"] . "\",\"\",\"/" . $portal . "/" . $pages["link"] . "\")' class='active'><img border='0' src='/images/home.png'></a></li>";
				}
				else
				{
					echo "<li><a id='" . $pages["id"] . "' onclick='V_Page_Load(\"" . $pages["id"] . "\",\"\",\"/" . $portal . "/" . $pages["link"] . "\")'>" . $pages["name"] . "</a></li>";
				}
			}
			elseif ($pages["link"] != "" && $pages["jquery"] != "")
			{
				echo $pages["jquery"];
				echo "<li><a id='" . $pages["id"] . "' onclick='" . $pages["link"] . "'>" . $pages["name"] . "</a></li>";
			}
			else
			{
				echo  "<li><a id='" . $pages["id"] . "' style='cursor:default;'>" . $pages["name"] . "</a><ul>";
				$q1 = $mysqli->query("SELECT * FROM `vericon`.`portals_pages` WHERE `portal` = '" . $mysqli->real_escape_string($portal) . "' AND `status` = 'Enabled' AND `level` = '" . $mysqli->real_escape_string($pages["level"]) . "' AND `sub_level` > '0' ORDER BY `sub_level` ASC") or die($mysqli->error);
				while ($sub_pages = $q1->fetch_assoc())
				{
					if (in_array($sub_pages["id"], $access_pages) || $ac["type"] == "Admin")
					{
						echo "<li><a id='" . $sub_pages["id"] . "' onclick='V_Page_Load(\"" . $pages["id"] . "\",\"" . $sub_pages["id"] . "\",\"/" . $portal . "/" . $sub_pages["link"] . "\")'>" . $sub_pages["name"] . "</a></li>";
					}
				}
				$q1->free();
				echo "</ul></li>";
			}
		}
	}
	echo "</ul>";
}
$q->free();
$mysqli->close();
?>