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

if (!CheckAccess())
{
	header('HTTP/1.1 403 Forbidden');
	include("error/forbidden.php");
	exit;
}

$browser = browser($_SERVER['HTTP_USER_AGENT']);

if ($browser["name"] != "Firefox" || $browser["version"] < 17)
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