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

$referer = $_SERVER['SERVER_NAME'] . "/";
$referer1 = $_SERVER['SERVER_NAME'] . "/maintenance/";
$referer_check = split("//", $_SERVER['HTTP_REFERER']);

if (($referer_check[1] != $referer && $referer_check[1] != $referer1) || !CheckAccess())
{
	header('HTTP/1.1 403 Forbidden');
	include("../error/forbidden.php");
	exit;
}

$q = $mysqli->query("SELECT `message` FROM `vericon`.`maintenance` WHERE `status` = 'Enabled'") or die($mysqli->error);
if ($q->num_rows == 0)
{
	header('Location: /');
	exit;
}
$data = $q->fetch_assoc();

$q->free();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>VeriCon :: Maintenance</title>
<link rel="shortcut icon" href="/images/vericon.ico">
<link rel="stylesheet" href="../jquery/css/vc-theme/jquery-ui-1.9.2.custom.min.css">
<script type="text/javascript" src="../jquery/js/jquery-1.8.3.js"></script>
<script type="text/javascript" src="../jquery/js/jquery-ui-1.9.2.custom.min.js"></script>
<style>
body {
	background:url(/images/bg.jpg) center top no-repeat #73d6f6; margin:0 auto;
	font:normal 12px Tahoma; line-height:18px; color:#165C80;
}
#wrapper {
	background:url(/images/maintenance.png) center top no-repeat rgba(255, 255, 255, 0.31); margin:100px auto 0;
	width:910px; padding:150px 0 50px; box-shadow:0 0 5px 5px rgba(0, 0, 0, 0.22);
}
#content {
	padding:0 25px;
}
@font-face {
	font-family:'SansationRegular';
	src:url('/lib/fonts/Sansation_Regular-webfont.eot');
	src:url('/lib/fonts/Sansation_Regular-webfont.eot?#iefix') format('embedded-opentype'),
		url('/lib/fonts/Sansation_Regular-webfont.woff') format('woff'),
		url('/lib/fonts/Sansation_Regular-webfont.ttf') format('truetype'),
		url('/lib/fonts/Sansation_Regular-webfont.svg#SansationRegular') format('svg');
	font-weight:normal;
	font-style:normal;
}
h1 {
	font-size:28px; font-weight:normal; color:#165C80; padding:0 20px; text-shadow:0 1px 0 #FFF;
	font-family:SansationRegular;
}
h3{
	font-size:14px; font-weight:normal; color:#165C80; padding:0; text-shadow:0 1px 0 #FFF;
	font-family:SansationRegular; text-align:center;
}
a{
	text-decoration:none; color:#165C80; outline:none; border:none;
}
a:hover{
	text-decoration:underline; color:#165C80; outline:none; border:none;
}
img {
	border:none;
}
</style>
<script>
function pad(number, length) {
	var str = '' + number;
	while (str.length < length) {
		str = '0' + str;
	}
	return str;
}

var time_remaining = 30;
function Time_Remaining()
{
	if (time_remaining == 0) {
		window.location = "/maintenance/";
		var time_string = "now";
	} else if (time_remaining == 1) {
		var time_string = pad(time_remaining, 2) + " second";
	} else {
		var time_string = pad(time_remaining, 2) + " seconds";
	}
	$( "#time_remaining" ).html(time_string);
	time_remaining--;
}

Time_Remaining();
setInterval("Time_Remaining()", 1000);
</script>
</head>

<body>
<div id="wrapper">
<div id="content">
<h1>We will return shortly</h1>
<p><?php echo $data["message"]; ?></p>
<br>
<h3>This page will automatically refresh in <span id="time_remaining">30 seconds</span></h3>
</div>
</div>
</body>
</html>