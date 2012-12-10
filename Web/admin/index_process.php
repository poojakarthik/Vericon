<?php
mysql_connect('localhost','vericon','18450be');

$forbidden = "<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">
<html><head>
<title>404 Not Found</title>
</head><body>
<h1>Not Found</h1>
<p>The requested URL " . $_SERVER['REQUEST_URI'] . " was not found on this server.</p>
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

$referer = $_SERVER['SERVER_NAME'] . "/main/";
$referer_check = split("//", $_SERVER['HTTP_REFERER']);

if ($referer_check[1] != $referer || !CheckAccess())
{
	header('HTTP/1.1 404 Not Found');
	header('Content-Type: text/html; charset=iso-8859-1');
	echo $forbidden;
	exit;
}

$token = $_COOKIE['vc_token'];

$q = mysql_query("SELECT `auth`.`user`, `auth`.`type`, `auth`.`centre`, `auth`.`status`, `auth`.`first`, `auth`.`last`, `auth`.`alias` FROM `vericon`.`auth`, `vericon`.`current_users` WHERE `current_users`.`token` = '" . mysql_real_escape_string($token) . "' AND `current_users`.`user` = `auth`.`user`") or die(mysql_error());
$ac = mysql_fetch_assoc($q);

if (mysql_num_rows($q) == 0)
{
	header('HTTP/1.1 420 Not Logged In');
	header('Content-Type: text/html; charset=iso-8859-1');
	echo $forbidden;
	exit;
}

if ($ac["status"] != "Enabled")
{
	header('HTTP/1.1 421 Account Disabled');
	header('Content-Type: text/html; charset=iso-8859-1');
	echo $forbidden;
	exit;
}

$method = $_POST["m"];

if ($method == "load")
{
	$load = exec("cat /proc/loadavg");
	$load = split(" ",$load);
	$proc = exec("cat /proc/cpuinfo | grep \"processor\"");
	$proc = substr($proc,-2) + 1;
?>
<div style="margin:5px 10px 0px 15px;"><span style="font-size:32px;"><?php echo $load[0]; ?></span><span style="font-size:20px;"> / <?php echo $proc; ?></span></div>
<center><div style="margin-top:10px; margin-left:15px; float:left"><?php echo $load[1] . "<br>5 Mins"; ?></div><div style="margin-top:10px; margin-right:15px; float:right"><?php echo $load[2] . "<br>15 Mins"; ?></div></center>
<?php
}
elseif ($method == "mem")
{
	$mem = exec("cat /proc/meminfo | grep MemTotal");
	$mem = str_replace(" ","",$mem);
	$mem = split(":",$mem);
	$mem_t = substr($mem[1],0,-2);
	$mem_total = number_format((substr($mem[1],0,-2) / 1048576),2) . " GB";
	$mem = exec("cat /proc/meminfo | grep MemFree");
	$mem = str_replace(" ","",$mem);
	$mem = split(":",$mem);
	$mem_used = number_format((($mem_t - substr($mem[1],0,-2)) / 1048576),2) . " GB";
?>
<div style="margin:5px 10px 0px 15px;"><span style="font-size:32px;"><?php echo $mem_used; ?></span></div><br>
<div style="margin:5px 10px 0px 15px;"><span style="font-size:20px;"> / <?php echo $mem_total; ?></span></div>
<?php
}
elseif ($method == "swap")
{
	$swap = exec("free -k | grep Swap:");
	$swap = preg_replace('!\s+!', ' ', $swap);
	$swap = explode(" ", $swap);
	$swap_total = number_format($swap[1] / 1048576, 2) . " GB";
	$swap_used = number_format($swap[2] / 1048576, 2) . " GB";
?>
<div style="margin:5px 10px 0px 15px;"><span style="font-size:32px;"><?php echo $swap_used; ?></span></div><br>
<div style="margin:5px 10px 0px 15px;"><span style="font-size:20px;"> / <?php echo $swap_total; ?></span></div>
<?php
}
?>