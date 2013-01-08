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
?>
<script>
<?php
if (isset($_COOKIE["vc_broadcast"])) {
	$messages = $_COOKIE["vc_broadcast"];
} else {
	$messages = "";
}
$my_departments = explode(",", $ac["type"]);

$q = $mysqli->query("SELECT `broadcast`.`id`, `broadcast`.`title`, `broadcast`.`message`, `broadcast`.`all`, `broadcast`.`department`, `broadcast`.`user`, `broadcast`.`timestamp`, `auth`.`first`, `auth`.`last` FROM `vericon`.`broadcast`, `vericon`.`auth` WHERE `broadcast`.`end_timestamp` >= NOW() AND `broadcast`.`poster` = `auth`.`user` ORDER BY `broadcast`.`id` ASC") or die($mysqli->error);
while($broadcast = $q->fetch_assoc())
{
	$seen = explode(",", $messages);
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
			echo "$.jGrowl('" . $broadcast["message"] . "<hr style=\"width:75%; height:1px; margin-top:5px; border-style:dotted none none; border-width:1px; border-color:#3a65b4; background:none;\" />" . $broadcast["first"] . " " . $broadcast["last"] . " | " . date("d/m/Y h:i A", strtotime($broadcast["timestamp"])) . "', { sticky: true, theme: 'jgrowl_theme', header: '" . $broadcast["title"] . "', open: function (e,m,o) { V_Notification_Open(); }, close: function (e,m,o) { V_Notification_Close(); } });";
		}
		elseif ($department_count > 0)
		{
			echo "$.jGrowl('" . $broadcast["message"] . "<hr style=\"width:75%; height:1px; margin-top:5px; border-style:dotted none none; border-width:1px; border-color:#3a65b4; background:none;\" />" . $broadcast["first"] . " " . $broadcast["last"] . " | " . date("d/m/Y h:i A", strtotime($broadcast["timestamp"])) . "', { sticky: true, theme: 'jgrowl_theme', header: '" . $broadcast["title"] . "', open: function (e,m,o) { V_Notification_Open(); }, close: function (e,m,o) { V_Notification_Close(); } });";
		}
		elseif ($user_count > 0)
		{
			echo "$.jGrowl('" . $broadcast["message"] . "<hr style=\"width:75%; height:1px; margin-top:5px; border-style:dotted none none; border-width:1px; border-color:#3a65b4; background:none;\" />" . $broadcast["first"] . " " . $broadcast["last"] . " | " . date("d/m/Y h:i A", strtotime($broadcast["timestamp"])) . "', { sticky: true, theme: 'jgrowl_theme', header: '" . $broadcast["title"] . "', open: function (e,m,o) { V_Notification_Open(); }, close: function (e,m,o) { V_Notification_Close(); } });";
		}
		
		$messages .= "," . $broadcast["id"];
	}
}
$q->free();

$messages = trim(substr($messages,-512), ",");

setcookie("vc_broadcast", $messages, strtotime("+1 month"), "/");

$q = $mysqli->query("SELECT `last_action` FROM `vericon`.`current_users` WHERE `user` = '" . $mysqli->real_escape_string($ac["user"]) . "'") or die($mysqli->error);
$last_action = $q->fetch_row();
$q->free();

if (strtotime($last_action[0]) < strtotime("-12 minutes"))
{
	echo "$.jGrowl('Your session is about to expire, please perform an action to remain logged in.<hr style=\"width:75%; height:1px; margin-top:5px; border-style:dotted none none; border-width:1px; border-color:#3a65b4; background:none;\" />VeriCon | " . date("d/m/Y h:i A") . "', { sticky: true, theme: 'jgrowl_theme', header: 'Inactive Session Warning', open: function (e,m,o) { V_Notification_Open(); }, close: function (e,m,o) { V_Notification_Close(); } });";
}

$mysqli->close();
?>
</script>