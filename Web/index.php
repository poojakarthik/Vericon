<title>Login</title>
<?php
$mysqli = new mysqli('localhost','vericon','18450be');

header("Content-Type: text/html; charset=iso-8859-1");

define('SALT', 'IISp3dwbJu4UuMxWJWSfLrzR');

function encrypt($text)
{
	return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, SALT, $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
}

if (isset($_SERVER['HTTP_MAC'])) {
	$tracker = encrypt($_SERVER['HTTP_MAC']);
} else {
	$tracker = encrypt($_SERVER['REMOTE_ADDR'] . date("Y-m-d H:i:s"));
}
setcookie("vc_tracker", $tracker, strtotime("+1 month"), '/');

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
	if (preg_match('/vericon/i', $ua)) {
		preg_match('/VeriCon\/([0-9\.]+)(\+)?/i', $ua, $b);
		$return['name'] = 'VeriCon';
		unset($b[0]);
		$return['version'] = implode('', $b);
	} else {
		$return['name'] = 'Other';
		$return['version'] = '0';
	}
	return $return;
}

if (!CheckAccess())
{
	header('HTTP/1.1 403 Forbidden');
	include("error/forbidden.php");
	exit;
}

$browser = browser($_SERVER['HTTP_USER_AGENT']);
$server_name = $_SERVER['SERVER_NAME'];

$q = $mysqli->query("SELECT `subject` FROM `vericon`.`updates` ORDER BY `id` DESC LIMIT 1") or die($mysqli->error);
$ver = $q->fetch_row();
$q->free();
$version = explode(" ", $ver[0]);

if (($browser["name"] != "VeriCon" || version_compare($browser["version"], $version[1], '<')) && $server_name != "lb01.vericon.com.au")
{
	header('HTTP/1.1 419 Unsupported Browser');
	include("error/unsupported.php");
	exit;
}

if ($tracker == "")
{
	header('HTTP/1.1 419 Unsupported Browser');
	include("error/unsupported.php");
	exit;
}

$maintenance = $mysqli->query("SELECT `message` FROM `vericon`.`maintenance` WHERE `status` = 'Enabled'") or die($mysqli->error);

if ($maintenance->num_rows != 0)
{
	echo "<script>window.location = '/maintenance';</script>";
}
else
{
?>
<script>
window.location = '/login';
</script>
<?php
}
$maintenance->free();
?>
<noscript>
<h1>Javascript must be enabled</h1>
</noscript>
<?php
$mysqli->close();
?>