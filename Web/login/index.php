<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Coming Soon</title>
<style>
body {
	background:url(bg.jpg) center top no-repeat #73d6f6; margin:0 auto;
	font:normal 12px Tahoma; line-height:18px; color:#165C80;
}
#wrapper {
	background:url(comingsoon.png) center top no-repeat rgba(255, 255, 255, 0.31); margin:100px auto 0;
	width:910px; padding:150px 0 50px; box-shadow:0 0 5px 5px rgba(0, 0, 0, 0.22);
}
#content {
	padding:0 25px;
}
@font-face {
	font-family:'SansationRegular';
	src:url('Sansation_Regular-webfont.eot');
	src:url('Sansation_Regular-webfont.eot?#iefix') format('embedded-opentype'),
		url('Sansation_Regular-webfont.woff') format('woff'),
		url('Sansation_Regular-webfont.ttf') format('truetype'),
		url('Sansation_Regular-webfont.svg#SansationRegular') format('svg');
	font-weight:normal;
	font-style:normal;
}
h1 {
	font-size:24px; font-weight:normal; color:#165C80; padding:0 20px; text-shadow:0 1px 0 #FFF;
	font-family:SansationRegular;
}
#countdown {
	font-size:28px; font-weight:normal; color:color:#165C80; padding:0 20px; text-shadow:0 1px 0 #FFF;
	font-family:SansationRegular; margin:100px auto 0 auto; width:660px; text-align:center;
}
img {
	border:none;
}
.hidden_btn {
	background:none; border:none; width:16px; height:16px;
}
</style>
<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
<?php
$time_remaining = strtotime("2013-06-10 07:00:00") - time();

$time_string = "";
$secs = $time_remaining % 60;
$mins = $time_remaining / 60 % 60;
$hours = $time_remaining / 3600 % 24;
$days = $time_remaining / 86400 % 356;

if ($days == 1) {
	$time_string = str_pad($days, 2) . " day ";
} else {
	$time_string = str_pad($days, 2) . " days ";
}

if ($hours == 1) {
	$time_string .= str_pad($hours, 2) . " hour ";
} else {
	$time_string .= str_pad($hours, 2) . " hours ";
}

if ($mins == 1) {
	$time_string .= str_pad($mins, 2) . " minute ";
} else {
	$time_string .= str_pad($mins, 2) . " minutes ";
}

if ($secs == 1) {
	$time_string .= str_pad($secs, 2) . " second remaining";
} else {
	$time_string .= str_pad($secs, 2) . " seconds remaining";
}
?>
<script>
function pad(number, length) {
	var str = '' + number;
	while (str.length < length) {
		str = '0' + str;
	}
	return str;
}

var time_remaining = <?php echo $time_remaining; ?>;
function Time_Remaining()
{
	var time_string = "";
	var secs = parseInt(time_remaining % 60);
	var mins = parseInt(time_remaining / 60 % 60);
	var hours = parseInt(time_remaining / 3600 % 24);
	var days = parseInt(time_remaining / 86400);
	
	if (time_remaining <= 0) {
		window.location = "/";
		time_string = "now";
	} else {
		if (days == 1) {
			time_string = pad(days, 2) + " day ";
		} else {
			time_string = pad(days, 2) + " days ";
		}
		
		if (hours == 1) {
			time_string += pad(hours, 2) + " hour ";
		} else {
			time_string += pad(hours, 2) + " hours ";
		}
		
		if (mins == 1) {
			time_string += pad(mins, 2) + " minute ";
		} else {
			time_string += pad(mins, 2) + " minutes ";
		}
		
		if (secs == 1) {
			time_string += pad(secs, 2) + " second remaining";
		} else {
			time_string += pad(secs, 2) + " seconds remaining";
		}
	}
	$( "#time_remaining" ).html(time_string);
	time_remaining--;
}

//Time_Remaining();
//setInterval("Time_Remaining()", 1000);
</script>
<script>
function Redirect()
{
	window.location = "https://mail.vericon.com.au";
}
</script>
</head>

<body>
<button onclick="Redirect()" class="hidden_btn"></button>
<div id="wrapper">
<div id="content">
<h1>Coming Soon</h1>
<p>Check back for more information!</p>
<!--<div id="countdown">
<p id="time_remaining"><?php echo $time_string; ?></p>
</div>-->
</div>
</div>
</body>
</html>