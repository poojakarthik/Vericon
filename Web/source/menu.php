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

$portal = strtolower($_POST["p"]);

$q = mysql_query("SELECT `pages` FROM `vericon`.`portals_access` WHERE `user` = '" . mysql_real_escape_string($ac["user"]) . "'") or die(mysql_error());
$ap = mysql_fetch_row($q);
$access_pages = explode(",", $ap[0]);

echo "<ul id='menu'>";
$q = mysql_query("SELECT * FROM `vericon`.`portals_pages` WHERE `portal` = '" . mysql_real_escape_string($portal) . "' AND `status` = 'Enabled' AND `sub_level` = '0' ORDER BY `level` DESC") or die(mysql_error());
while ($pages = mysql_fetch_assoc($q))
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
			$q2 = mysql_query("SELECT * FROM `vericon`.`portals_pages` WHERE `portal` = '" . mysql_real_escape_string($portal) . "' AND `status` = 'Enabled' AND `level` = '" . mysql_real_escape_string($pages["level"]) . "' AND `sub_level` > '0' ORDER BY `sub_level` ASC") or die(mysql_error());
			while ($sub_pages = mysql_fetch_assoc($q2))
			{
				if (in_array($sub_pages["id"], $access_pages) || $ac["type"] == "Admin")
				{
					echo "<li><a id='" . $sub_pages["id"] . "' onclick='V_Page_Load(\"" . $pages["id"] . "\",\"" . $sub_pages["id"] . "\",\"/" . $portal . "/" . $sub_pages["link"] . "\")'>" . $sub_pages["name"] . "</a></li>";
				}
			}
			echo "</ul></li>";
		}
	}
}
echo "</ul>";
?>