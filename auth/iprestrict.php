<?php
function browser($ua)
{
    if (preg_match('/bot/i', $ua) || preg_match('/crawl/i', $ua) || preg_match('/yahoo\!/i', $ua)) {
        $return['name'] = 'Bot';
        $return['version'] = 'Unknown';
    } elseif (preg_match('/opera/i', $ua)) {
        preg_match('/Opera(\/| )([0-9\.]+)(u)?(\d+)?/i', $ua, $b);
        $return['name'] = 'Opera';
        unset($b[0], $b[1]);
        $return['version'] = implode('', $b);
    } elseif (preg_match('/msie/i', $ua)) {
        preg_match('/MSIE ([0-9\.]+)(b)?/i', $ua, $b);
        $return['name'] = 'Internet Explorer';
        unset($b[0]);
        $return['version'] = implode('', $b);
    } elseif (preg_match('/omniweb/i', $ua)) {
        preg_match('/OmniWeb\/([0-9\.]+)/i', $ua, $b);
        $return['name'] = 'OmniWeb';
        if (isset($b[1]))
            $return['version'] = $b[1];
        else
            $return['version'] = 'Unknown';
    } elseif (preg_match('/icab/i', $ua)) {
        preg_match('/iCab\/([0-9\.]+)/i', $ua, $b);
        $return['name'] = 'iCab';
        $return['version'] = $b[1];
    } elseif (preg_match('/safari/i', $ua)) {
        preg_match('/Safari\/([0-9\.]+)/i', $ua, $b);
        $return['name'] = 'Safari';
        $return['version'] = $b[1];
        switch ($return['version'])
        {
            case '412':
            case '412.2':
            case '412.2.2':
                $return['version'] = '2.0';
            break;
            case '412.5':
            $return['version'] = '2.0.1';
            break;
            case '416.12':
            case '416.13':
                $return['version'] = '2.0.2';
            break;
            case '100':
                $return['version'] = '1.1';
            break;
            case '100.1':
                $return['version'] = '1.1.1';
            break;
            case '125.7':
            case '125.8':
                $return['version'] = '1.2.2';
            break;
            case '125.9':
                $return['version'] = '1.2.3';
            break;
            case '125.11':
            case '125.12':
                $return['version'] = '1.2.4';
            break;
            case '312':
                $return['version'] = '1.3';
            break;
            case '312.3':
            case '312.3.1':
                $return['version'] = '1.3.1';
            break;
            case '85.5':
                $return['version'] = '1.0';
            break;
            case '85.7':
                $return['version'] = '1.0.2';
            break;
            case '85.8':
            case '85.8.1':
                $return['version'] = '1.0.3';
            break;
        }
    } elseif (preg_match('/konqueror/i', $ua)) {
        preg_match('/Konqueror\/([0-9\.]+)(\-rc)?(\d+)?/i', $ua, $b);
        $return['name'] = 'Konqueror';
        unset($b[0]);
        $return['version'] = implode('', $b);
    } elseif (preg_match('/Flock/i', $ua)) {
        preg_match('/Flock\/([0-9\.]+)(\+)?/i', $ua, $b);
        $return['name'] = 'Flock';
        unset($b[0]);
        $return['version'] = implode('', $b);
    } elseif (preg_match('/firebird/i', $ua)) {
        preg_match('/Firebird\/([0-9\.]+)(\+)?/i', $ua, $b);
        $return['name'] = 'Firebird';
        unset($b[0]);
        $return['version'] = implode('', $b);
    } elseif (preg_match('/phoenix/i', $ua)) {
        preg_match('/Phoenix\/([0-9\.]+)(\+)?/i', $ua, $b);
        $return['name'] = 'Phoenix';
        unset($b[0]);
        $return['version'] = implode('', $b);
    } elseif (preg_match('/firefox/i', $ua)) {
        preg_match('/Firefox\/([0-9\.]+)(\+)?/i', $ua, $b);
        $return['name'] = 'Firefox';
        unset($b[0]);
        $return['version'] = implode('', $b);
    } elseif (preg_match('/chimera/i', $ua)) {
        preg_match('/Chimera\/([0-9\.]+)(a|b)?(\d+)?(\+)?/i', $ua, $b);
        $return['name'] = 'Chimera';
        unset($b[0]);
        $return['version'] = implode('', $b);
    } elseif (preg_match('/camino/i', $ua)) {
        preg_match('/Camino\/([0-9\.]+)(a|b)?(\d+)?(\+)?/i', $ua, $b);
        $return['name'] = 'Camino';
        unset($b[0]);
        $return['version'] = implode('', $b);
    } elseif (preg_match('/seamonkey/i', $ua)) {
        preg_match('/SeaMonkey\/([0-9\.]+)(a|b)?/i', $ua, $b);
        $return['name'] = 'SeaMonkey';
        unset($b[0]);
        $return['version'] = implode('', $b);
    } elseif (preg_match('/galeon/i', $ua)) {
        preg_match('/Galeon\/([0-9\.]+)/i', $ua, $b);
        $return['name'] = 'Galeon';
        $return['version'] = $b[1];
    } elseif (preg_match('/epiphany/i', $ua)) {
        preg_match('/Epiphany\/([0-9\.]+)/i', $ua, $b);
        $return['name'] = 'Epiphany';
        $return['version'] = $b[1];
    } elseif (preg_match('/mozilla\/5/i', $ua) || preg_match('/gecko/i', $ua)) {
        preg_match('/rv(:| )([0-9\.]+)(a|b)?/i', $ua, $b);
        $return['name'] = 'Mozilla';
        unset($b[0], $b[1]);
        $return['version'] = implode('', $b);
    } elseif (preg_match('/mozilla\/4/i', $ua)) {
        preg_match('/Mozilla\/([0-9\.]+)/i', $ua, $b);
        $return['name'] = 'Netscape';
        $return['version'] = $b[1];
    } elseif (preg_match('/lynx/i', $ua)) {
        preg_match('/Lynx\/([0-9\.]+)/i', $ua, $b);
        $return['name'] = 'Lynx';
        $return['version'] = $b[1];
    } elseif (preg_match('/links/i', $ua)) {
        preg_match('/Links \(([0-9\.]+)(pre)?(\d+)?/i', $ua, $b);
        $return['name'] = 'Links';
        unset($b[0]);
        $return['version'] = implode('', $b);
    } elseif (preg_match('/curl/i', $ua)) {
        preg_match('/curl\/([0-9\.]+)/i', $ua, $b);
        $return['name'] = 'cURL';
        $return['version'] = $b[1];
    } elseif (preg_match('/wget/i', $ua)) {
        preg_match('/Wget\/([0-9\.]+)/i', $ua, $b);
        $return['name'] = 'Wget';
        $return['version'] = $b[1];
    } else {
        $return['name'] = 'Unknown';
        $return['version'] = 'Unknown';
    }
    return $return;
}

$browser = browser($_SERVER['HTTP_USER_AGENT']);

if ($browser["name"] == "Firefox" && $browser["version"] >= 11)
{
	
}
else
{
	echo "<h1>Sorry VeriCon is not supported by your web browser<br>Please use <b>Firefox version 11 or above</b> to access VeriCon</h1>";
	echo '<a onclick="Firefox()" target="_blank" style="cursor:pointer; margin-left:300px;"><img src="../images/firefox_download.png" /></a>';
	exit;
}

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

function CheckAccess()
{
	$q = mysql_query("SELECT * FROM allowedip") or die(mysql_error());
	
	while ($iplist = mysql_fetch_assoc($q))
	{
		$allowedip[$iplist['IP']] = $iplist['status'];
	}
  	$ip = $_SERVER['REMOTE_ADDR'];
	return ($allowedip[$ip]);
}

if (!CheckAccess())
{
	header("Location: ../index.php");
	exit;
}

$q1 = mysql_query("SELECT user FROM currentuser WHERE hash = '" . $_COOKIE["hash"] . "'") 
  or die(mysql_error());

$user = mysql_fetch_row($q1);

$q2 = mysql_query("SELECT * FROM auth WHERE user = '$user[0]'") or die(mysql_error());

$ac = mysql_fetch_assoc($q2);

$p = strtolower($ac["type"]);

$p1 = explode(",",$p);

for ($i = 0;$i < count($p1);$i++)
{
	foreach ($p1 as &$value)
	{
    	$acc[$p1[$i]] = true;
	}
}

$d = explode("/",$_SERVER['PHP_SELF']);

$q3 = mysql_query("SELECT * FROM vericon.portals_pages WHERE portal = '$d[1]' AND link = '" . mysql_real_escape_string($d[2]) . "'") or die(mysql_error());
$page_id = mysql_fetch_assoc($q3);

$q4 = mysql_query("SELECT pages FROM vericon.portals_access WHERE user = '$ac[user]'") or die(mysql_error());
$ap = mysql_fetch_row($q4);
$access_pages = explode(",", $ap[0]);

$q5 = mysql_query("SELECT * FROM vericon.portals WHERE id = '$d[1]'") or die(mysql_error());
$portal_name = mysql_fetch_assoc($q5);

if ($_SERVER[PHP_SELF] == "/index.php")
{
	if ($p != "")
	{
		header("Location: ../main.php");
	}
}
elseif (mysql_num_rows($q1) != 1)
{
	header("Location: ../index.php");
	exit;
}
elseif (preg_match("/admin/",$p) || $_SERVER[PHP_SELF] == "/main.php" || $d[1] == "ma")
{
	
}
elseif ($acc[$d[1]] != true)
{
	header("Location: ../index.php");
	exit;
}
elseif (!in_array($page_id["id"], $access_pages))
{
	header("Location: ../index.php");
	exit;
}

if ($ac["status"] == "Disabled")
{
	setcookie("hash", "", time()-86400);
	header("Location: ../index.php?attempt=banned");
	exit;
}

$current_page = $portal_name["name"] . " :: " . $page_id["name"];
if ($current_page == " :: ")
{
	$current_page = "Main";
}
mysql_query("UPDATE vericon.currentuser SET current_page = '" . mysql_real_escape_string($current_page) . "' WHERE hash = '" . $_COOKIE["hash"] . "' AND user = '$ac[user]'") or die(mysql_error());

mysql_query("INSERT INTO vericon.log_access (user, page) VALUES ('$ac[user]' ,'" . mysql_real_escape_string($current_page) . "')") or die(mysql_error());
?>