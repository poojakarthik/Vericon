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
?>
<script>
<?php
$messages = $_COOKIE["vc_broadcast"];
$my_departments = explode(",", $ac["type"]);

$q = mysql_query("SELECT `broadcast`.`id`, `broadcast`.`title`, `broadcast`.`message`, `broadcast`.`all`, `broadcast`.`department`, `broadcast`.`user`, `broadcast`.`timestamp`, `auth`.`first`, `auth`.`last` FROM `vericon`.`broadcast`, `vericon`.`auth` WHERE `broadcast`.`end_timestamp` >= NOW() AND `broadcast`.`poster` = `auth`.`user` ORDER BY `broadcast`.`id` ASC") or die(mysql_error());
while($broadcast = mysql_fetch_assoc($q))
{
	$seen = explode(",", $_COOKIE["vc_broadcast"]);
	if (!in_array($broadcast["id"], $seen))
	{
		$department_count = 0;
		$user_count = 0;
		
		if ($broadcast["department"] != "")
		{
			$departments = explode(",", $broadcast["department"]);
			for ($i = 0; $i <= count($my_departments); $i++)
			{
				if (in_array($my_departments[$i], $departments))
				{
					$department_count++;
				}
			}
		}
		
		if ($broadcast["user"] != "")
		{
			$users = explode(",", $broadcast["user"]);
			if (in_array($ac["user"], $users))
			{
				$user_count++;
			}
		}
		
		if ($broadcast["all"] == "1")
		{
			echo "$.jGrowl('" . $broadcast["message"] . "<hr style=\"width:75%; height:1px; margin-top:5px; border-top:1px dotted #3a65b4; background:none;\" />" . $broadcast["first"] . " " . $broadcast["last"] . " | " . date("d/m/Y h:i A", strtotime($broadcast["timestamp"])) . "', { sticky: true, theme: 'jgrowl_theme', header: '" . $broadcast["title"] . "', open: function (e,m,o) { V_Notification_Open(); }, close: function (e,m,o) { V_Notification_Close(); } });";
		}
		elseif ($department_count > 0)
		{
			echo "$.jGrowl('" . $broadcast["message"] . "<hr style=\"width:75%; height:1px; margin-top:5px; border-top:1px dotted #3a65b4; background:none;\" />" . $broadcast["first"] . " " . $broadcast["last"] . " | " . date("d/m/Y h:i A", strtotime($broadcast["timestamp"])) . "', { sticky: true, theme: 'jgrowl_theme', header: '" . $broadcast["title"] . "', open: function (e,m,o) { V_Notification_Open(); }, close: function (e,m,o) { V_Notification_Close(); } });";
		}
		elseif ($user_count > 0)
		{
			echo "$.jGrowl('" . $broadcast["message"] . "<hr style=\"width:75%; height:1px; margin-top:5px; border-top:1px dotted #3a65b4; background:none;\" />" . $broadcast["first"] . " " . $broadcast["last"] . " | " . date("d/m/Y h:i A", strtotime($broadcast["timestamp"])) . "', { sticky: true, theme: 'jgrowl_theme', header: '" . $broadcast["title"] . "', open: function (e,m,o) { V_Notification_Open(); }, close: function (e,m,o) { V_Notification_Close(); } });";
		}
		
		$messages .= "," . $broadcast["id"];
	}
}

$messages = trim(substr($messages,-512), ",");

setcookie("vc_broadcast", $messages, strtotime("+1 month"), "/");

$q = mysql_query("SELECT `last_action` FROM `vericon`.`current_users` WHERE `user` = '" . mysql_real_escape_string($ac["user"]) . "'") or die(mysql_error());
$last_action = mysql_fetch_row($q);

if (strtotime($last_action[0]) < strtotime("-12 minutes"))
{
	echo "$.jGrowl('Your session is about to expire, please perform an action to remain logged in.<hr style=\"width:75%; height:1px; margin-top:5px; border-top:1px dotted #3a65b4; background:none;\" />VeriCon | " . date("d/m/Y h:i A") . "', { sticky: true, theme: 'jgrowl_theme', header: 'Inactive Session Warning', open: function (e,m,o) { V_Notification_Open(); }, close: function (e,m,o) { V_Notification_Close(); } });";
}
?>
</script>