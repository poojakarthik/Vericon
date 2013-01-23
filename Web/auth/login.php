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
	if (preg_match('/firefox/i', $ua)) {
		preg_match('/Firefox\/([0-9\.]+)(\+)?/i', $ua, $b);
		$return['name'] = 'Firefox';
		unset($b[0]);
		$return['version'] = implode('', $b);
	} elseif (preg_match('/chrome/i', $ua)) {
		preg_match('/Chrome\/([0-9\.]+)(\+)?/i', $ua, $b);
		$return['name'] = 'Chrome';
		unset($b[0]);
		$return['version'] = implode('', $b);
	} else {
		$return['name'] = 'Other';
		$return['version'] = 'Other';
	}
	return $return;
}

$username = $_POST["username"];
$password = $_POST["password"];
$tracker = $_POST["tracker"];
$remember = $_POST["remember"];
$browser = browser($_SERVER['HTTP_USER_AGENT']);

$q = $mysqli->query("SELECT * FROM `vericon`.`auth` WHERE `user` = '" . $mysqli->real_escape_string($username) . "' AND `pass` = '" . md5($password) . "'") or die($mysqli->error);
$data = $q->fetch_assoc();

$maintenance = $mysqli->query("SELECT `message` FROM `vericon`.`maintenance` WHERE `status` = 'Enabled'") or die($mysqli->error);

if (!CheckAccess())
{
	echo "<b>Error: </b>IP is not within the whitelist range. <a href=\"/\">Click here for more details.</a>";
}
elseif ($browser["name"] != "Firefox" || $browser["version"] < 17)
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
elseif($q->num_rows != 1)
{
	echo "<b>Error: </b>Incorrect username or password.";
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