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

$q = $mysqli->query("SELECT `auth`.`user`, `auth`.`type`, `auth`.`centre`, `auth`.`status`, `auth`.`first`, `auth`.`last`, `auth`.`alias` FROM `vericon`.`auth`, `vericon`.`current_users` WHERE `current_users`.`token` = '" . $mysqli->real_escape_string($token) . "' AND `current_users`.`user` = `auth`.`user`") or die($mysqli->error);
$ac = $q->fetch_assoc();

if ($q->num_rows == 0)
{
	header('HTTP/1.1 403 Forbidden');
	exit;
}
if ($ac["status"] != "Enabled")
{
	header('HTTP/1.1 403 Forbidden');
	exit;
}
$q->free();

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
	echo "<ul id='menu'>";
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