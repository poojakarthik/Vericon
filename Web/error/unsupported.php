<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>419 Unsupported Browser</title>
<style>
body {
	background:url(/images/bg.jpg) center top no-repeat #73d6f6; margin:0 auto;
	font:normal 12px Tahoma; line-height:18px; color:#165C80;
}
#wrapper {
	background:url(/images/error.png) center top no-repeat rgba(255, 255, 255, 0.31); margin:100px auto 0;
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
	font-family:SansationRegular;
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
</head>

<?php
$mysqli = new mysqli('localhost','vericon','18450be');
$q = $mysqli->query("SELECT `subject` FROM `vericon`.`updates` ORDER BY `id` DESC LIMIT 1") or die($mysqli->error);
$ver = $q->fetch_row();
$q->free();
$mysqli->close();
$version = explode(" ",$ver[0]);
?>
<body>
<div id="wrapper">
<div id="content">
<h1>Unsupported Browser</h1>
<p>You're using a web browser we don't support.</p>
<p>Try the below option to have a better experience on VeriCon.</p>
<h3 style="text-align:center;"><a href='#'><img src='/images/vericon_browser.png'><br />Download VeriCon Browser v<?php echo $version[1]; ?></a></h3>
</div>
</div>
</body>
</html>