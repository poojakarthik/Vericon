<?php
mysql_connect('localhost','vericon','18450be');

$forbidden = "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">
<html><head>
<title>403 Forbidden</title>
</head><body>
<h1>Forbidden</h1>
<p>You don't have permission to access " . $_SERVER['REQUEST_URI'] . " on this server.</p>
<hr>
<address>" . $_SERVER['SERVER_SIGNATURE'] . "</address>
</body></html>";

$unsupported = "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">
<html><head>
<title>419 Unsupported Browser</title>
</head><body>
<h1>Unsupported Browser</h1>
<p>You're using a web browser we don't support.</p>
<p>Try one of these options to have a better experience on VeriCon.</p>
<p><a href='http://www.mozilla.org/en-US/firefox/new/'><img src='/images/firefox.png'></a></p>
<hr>
<address>" . $_SERVER['SERVER_SIGNATURE'] . "</address>
</body></html>";

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
	header('Content-Type: text/html; charset=iso-8859-1');
	echo $forbidden;
	exit;
}

$browser = browser($_SERVER['HTTP_USER_AGENT']);

if ($browser["name"] != "Firefox" || $browser["version"] < 17)
{
	header('HTTP/1.1 419 Unsupported Browser');
	header('Content-Type: text/html; charset=iso-8859-1');
	echo $unsupported;
	exit;
}
?>
<script>
window.location = '/login';
</script>
<noscript>
<h1>Javascript must be enabled</h1>
</noscript>