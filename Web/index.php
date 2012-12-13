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

$maintenance = mysql_query("SELECT `message` FROM `vericon`.`maintenance` WHERE `status` = 'Enabled'") or die(mysql_error());

if (mysql_num_rows($maintenance) != 0)
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
?>
<noscript>
<h1>Javascript must be enabled</h1>
</noscript>