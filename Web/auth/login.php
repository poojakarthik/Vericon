<?php
mysql_connect('localhost','vericon','18450be');

$referer = $_SERVER['SERVER_NAME'] . "/login/";
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

define('SALT', '95da736efb1fac79a9da5e02285ffb7617b3894283542cb3ce5d32e74ecf3241849fadb3df285afab6e0f81238c1421128b80b91ece99b17af346b013187e69a');

function encrypt($text)
{
	return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
}

function CheckAccess()
{
	$q = mysql_query("SELECT * FROM `vericon`.`allowedip` WHERE '" . mysql_real_escape_string($_SERVER['REMOTE_ADDR']) . "' BETWEEN INET_NTOA(`ip_start`) AND INET_NTOA(`ip_end`) AND `status` = 'Enabled'") or die(mysql_error());
	
	if (mysql_num_rows($q) == 0) {
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

$q = mysql_query("SELECT * FROM `vericon`.`auth` WHERE `user` = '" . mysql_real_escape_string($username) . "' AND `pass` = '" . md5($password) . "'") or die(mysql_error());
$data = mysql_fetch_assoc($q);

if ($username == "" || $password == "")
{
	echo "<b>Error: </b>Incorrect username or password.";
}
elseif(mysql_num_rows($q) != 1)
{
	echo "<b>Error: </b>Incorrect username or password.";
}
elseif (!CheckAccess())
{
	mysql_query("INSERT INTO `logs`.`unauthorised` (`user`, `ip`, `timestamp`) VALUES ('" . mysql_real_escape_string($username) . "', '" . mysql_real_escape_string(ip2long($_SERVER['REMOTE_ADDR'])) . "', NOW())") or die(mysql_error());
	echo "<b>Error: </b>IP is not within the whitelist range.";
}
elseif ($browser["name"] != "Firefox" || $browser["version"] < 17)
{
	echo "<b>Error: </b>Unsupported browser. <a href=\"/\">Click here</a> for more details.";
}
elseif ($data["status"] == "Disabled")
{
	echo "<b>Error: </b>Your account is disabled.";
}
else
{
	$token = hash('whirlpool', $username . rand());
	
	mysql_query("INSERT INTO `vericon`.`current_users` (`user`, `token`, `ip`, `timestamp`, `current_page`, `last_action`) VALUES ('" . mysql_real_escape_string($username) . "', '" . mysql_real_escape_string($token) . "', '" . mysql_real_escape_string(ip2long($_SERVER['REMOTE_ADDR'])) . "', NOW(), 'MA01', NOW()) ON DUPLICATE KEY UPDATE `token` = '" . mysql_real_escape_string($token) . "', `ip` = '" . mysql_real_escape_string(ip2long($_SERVER['REMOTE_ADDR'])) . "', `timestamp` = NOW(), `current_page` = 'MA01', `last_action` = NOW()") or die(mysql_error());
	
	mysql_query("INSERT INTO `logs`.`login` (`user`, `ip`, `token`, `timestamp`) VALUES ('" . mysql_real_escape_string($username) . "', '" . mysql_real_escape_string(ip2long($_SERVER['REMOTE_ADDR'])) . "', '" . mysql_real_escape_string($token) . "', NOW())") or die(mysql_error());
	
	setcookie("vc_token", $token, strtotime("+1 month"), '/');
	
	if ($remember == 1)
	{
		setcookie("vc_username", encrypt($username), strtotime("+1 year"), '/');
	}
	
	echo "token=" . $token;
}
?>