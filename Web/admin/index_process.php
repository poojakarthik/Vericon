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
	header('HTTP/1.1 403 Forbidden');
	exit;
}
if ($ac["status"] != "Enabled")
{
	header('HTTP/1.1 403 Forbidden');
	exit;
}
$q->free();
$mysqli->close();

$method = $_POST["m"];

if ($method == "services")
{
?>
<tr>
<td>Load Balancer / Mail Server</td>
<td style="text-align:center">-</td>
<td style="text-align:center"><?php $checkport = fsockopen("lb01.vericon.com.au", "21119", $errnum, $errstr, 2); if(!$checkport) { echo "<img src='/images/down.png'>"; } else { echo "<img src='/images/up.png'>"; } ?></td>
<td style="text-align:center">-</td>
<td style="text-align:center"><?php $checkport = fsockopen("mail.vericon.com.au", "993", $errnum, $errstr, 2); if(!$checkport) { echo "<img src='/images/down.png'>"; } else { echo "<img src='/images/up.png'>"; } ?></td>
<td style="text-align:center"><?php $checkport = fsockopen("mail.vericon.com.au", "465", $errnum, $errstr, 2); if(!$checkport) { echo "<img src='/images/down.png'>"; } else { echo "<img src='/images/up.png'>"; } ?></td>
</tr>
<tr>
<td>VeriCon 01</td>
<td style="text-align:center"><?php $checkport = fsockopen("vc01.vericon.com.au", "443", $errnum, $errstr, 2); if(!$checkport) { echo "<img src='/images/down.png'>"; } else { echo "<img src='/images/up.png'>"; } ?></td>
<td style="text-align:center"><?php $checkport = fsockopen("vc01.vericon.com.au", "21119", $errnum, $errstr, 2); if(!$checkport) { echo "<img src='/images/down.png'>"; } else { echo "<img src='/images/up.png'>"; } ?></td>
<td style="text-align:center"><?php $checkport = fsockopen("vc01.vericon.com.au", "3306", $errnum, $errstr, 2); if(!$checkport) { echo "<img src='/images/down.png'>"; } else { echo "<img src='/images/up.png'>"; } ?></td>
<td style="text-align:center">-</td>
<td style="text-align:center">-</td>
</tr>
<tr>
<td>VeriCon 02</td>
<td style="text-align:center"><?php $checkport = fsockopen("vc02.vericon.com.au", "443", $errnum, $errstr, 2); if(!$checkport) { echo "<img src='/images/down.png'>"; } else { echo "<img src='/images/up.png'>"; } ?></td>
<td style="text-align:center"><?php $checkport = fsockopen("vc02.vericon.com.au", "21119", $errnum, $errstr, 2); if(!$checkport) { echo "<img src='/images/down.png'>"; } else { echo "<img src='/images/up.png'>"; } ?></td>
<td style="text-align:center"><?php $checkport = fsockopen("vc02.vericon.com.au", "3306", $errnum, $errstr, 2); if(!$checkport) { echo "<img src='/images/down.png'>"; } else { echo "<img src='/images/up.png'>"; } ?></td>
<td style="text-align:center">-</td>
<td style="text-align:center">-</td>
</tr>
<tr>
<td>VeriCon Storage</td>
<td style="text-align:center">-</td>
<td style="text-align:center"><?php $checkport = fsockopen("st01.vericon.com.au", "21119", $errnum, $errstr, 2); if(!$checkport) { echo "<img src='/images/down.png'>"; } else { echo "<img src='/images/up.png'>"; } ?></td>
<td style="text-align:center">-</td>
<td style="text-align:center">-</td>
<td style="text-align:center">-</td>
</tr>
<?php
}
elseif ($method == "load")
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