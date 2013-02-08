<?php
$mysqli = new mysqli('localhost','vericon','18450be');

$referer = $_SERVER['SERVER_NAME'] . "/login/";
$referer_check = split("//", $_SERVER['HTTP_REFERER']);
if ($_SERVER["REQUEST_METHOD"] != "POST" || $referer_check[1] != $referer)
{
	header('HTTP/1.1 403 Forbidden');
	include("../error/forbidden.php");
	exit;
}

define('SALT', 'IISp3dwbJu4UuMxWJWSfLrzR');

function encrypt($text)
{
	return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
}

function CheckAccess()
{
	if (ip2long($_SERVER['REMOTE_ADDR']) != ip2long("122.129.217.194")) {
		return false;
	} else {
		return true;
	}
}

function browser($ua)
{
	/*if (preg_match('/vericon/i', $ua)) {
		preg_match('/VeriCon\/([0-9\.]+)(\+)?/i', $ua, $b);
		$return['name'] = 'VeriCon';
		unset($b[0]);
		$return['version'] = implode('', $b);
	} else {
		$return['name'] = 'Other';
		$return['version'] = 'Other';
	}*/
	//Temp for Development
	if (preg_match('/firefox/i', $ua)) {
		preg_match('/Firefox\/([0-9\.]+)(\+)?/i', $ua, $b);
		$return['name'] = 'Firefox';
		unset($b[0]);
		$return['version'] = implode('', $b);
	} else {
		$return['name'] = 'Other';
		$return['version'] = 'Other';
	}
	//End Temp
	return $return;
}

$username = $_POST["username"];
$password = $_POST["password"];
$tracker_check = $_POST["tracker"];
$remember = $_POST["remember"];
$browser = browser($_SERVER['HTTP_USER_AGENT']);
if (!isset($_COOKIE["vc_tracker"])) {
	$tracker = "";
} else {
	$tracker = $_COOKIE["vc_tracker"];
}

$q1 = $mysqli->query("SELECT `attempts` FROM `vericon`.`invalid_login` WHERE `tracker` = '" . $mysqli->real_escape_string($tracker) . "'") or die($mysqli->error);
if ($q1->num_rows == 0) {
	$attempts_count = 0;
} else {
	$attempts = $q1->fetch_row();
	$attempts_count = $attempts[0];
}
$q1->free();

$q = $mysqli->query("SELECT * FROM `vericon`.`auth` WHERE `user` = '" . $mysqli->real_escape_string($username) . "' AND `pass` = '" . md5($password) . "'") or die($mysqli->error);
$data = $q->fetch_assoc();

$maintenance = $mysqli->query("SELECT `message` FROM `vericon`.`maintenance` WHERE `status` = 'Enabled'") or die($mysqli->error);

if (!CheckAccess())
{
	echo "<b>Error: </b>IP is not within the whitelist range. <a href=\"/\">Click here for more details.</a>";
}
elseif ($browser["name"] == "Other" || $browser["version"] == "Other" || $tracker == "" || $tracker != $tracker_check)
{
	echo "<b>Error: </b>Unsupported browser. <a href=\"/\">Click here for more details.</a>";
}
elseif($maintenance->num_rows != 0)
{
	echo "<b>Error: </b>VeriCon is currently under maintenance. <a href=\"/\">Click here for more details.</a>";
}
elseif ($username == "" || $password == "")
{
	echo "<b>Error: </b>Incorrect username or password.";
}
elseif ($attempts[0] >= 3)
{
	echo "<b>Error: </b>Exceeded allowed login attempts. Session locked for 2 minutes.";
}
elseif($q->num_rows != 1)
{
	$attempts_count = $attempts_count + 1;
	
	$mysqli->query("INSERT INTO `vericon`.`invalid_login` (`tracker`, `attempts`, `timestamp`) VALUES ('" . $mysqli->real_escape_string($tracker) . "', '" . $mysqli->real_escape_string($attempts_count) . "', NOW()) ON DUPLICATE KEY UPDATE `attempts` = '" . $mysqli->real_escape_string($attempts_count) . "', `timestamp` = NOW()") or die($mysqli->error);
	
	if ($attempts_count >= 3) {
		echo "<b>Error: </b>Exceeded allowed login attempts. Session locked for 2 minutes.";
	}
	else {
		echo "<b>Error: </b>Incorrect username or password. Attempt " . $attempts_count . " of 3.";
	}
}
elseif ($data["status"] == "Disabled")
{
	echo "<b>Error: </b>Your account is disabled.";
}
else
{
	$token = hash('whirlpool', $username . rand());
	
	$mysqli->query("INSERT INTO `vericon`.`current_users` (`user`, `token`, `timestamp`, `current_page`, `last_action`) VALUES ('" . $mysqli->real_escape_string($username) . "', '" . $mysqli->real_escape_string($token) . "', NOW(), 'MA01', NOW()) ON DUPLICATE KEY UPDATE `token` = '" . $mysqli->real_escape_string($token) . "', `timestamp` = NOW(), `current_page` = 'MA01', `last_action` = NOW()") or die($mysqli->error);
	
	$mysqli->query("INSERT INTO `logs`.`login` (`user`, `token`, `timestamp`) VALUES ('" . $mysqli->real_escape_string($username) . "', '" . $mysqli->real_escape_string($token) . "', NOW())") or die($mysqli->error);
	
	$mysqli->query("DELETE FROM `vericon`.`invalid_login` WHERE `tracker` = '" . $mysqli->real_escape_string($tracker) . "'") or die($mysqli->error);
	
	setcookie("vc_token", $token, strtotime("+1 month"), '/');
	
	if ($remember == 1)
	{
		setcookie("vc_username", encrypt($username), strtotime("+1 year"), '/');
	}
	
	echo "token=" . $token;
}

$q->free();
$maintenance->free();
$mysqli->close();
?>