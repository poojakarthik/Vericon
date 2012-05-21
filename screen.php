<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$p = $_GET["p"];
$date = date("Y-m-d");
if ($p == "stats")
{
	$cen = "CC51,CC52,CC53,CC54,CC61,CC63,CC71,CC72";
	$centre = explode(",", $cen);
	
	for ($i = 0; $i < count($centre); $i++)
	{
		$q = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND DATE(approved_timestamp) = '$date' AND centre = '$centre[$i]'") or die(mysql_error());
		$stats[$centre[$i]] = mysql_num_rows($q);
	}
	
	$q1 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND DATE(approved_timestamp) = '$date' AND industry = 'SELF'") or die(mysql_error());
	$melb = mysql_num_rows($q1);
	
	$q2 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND DATE(approved_timestamp) = '$date' AND industry = 'TPV'") or die(mysql_error());
	$india = mysql_num_rows($q2);
?>
<center><table id="users" style="margin-top:-15px; width:99%; padding:0px;">
<thead>
<tr class="ui-widget-header ">
<th colspan="4" style="border: 1px solid #eee; padding: .6em 10px; text-align: center;">Albert Road</th>
</tr>
</thead>
<tr>
<td width="25%" height="100%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; height:125px;">
<thead>
<tr class="ui-widget-header ">
<th>CC51 (Liam)</th>
</tr>
</thead>
<tbody>
<tr height="75%">
<td><span style="font-size:36px"><?php echo $stats["CC51"]; ?></span></td>
</tr>
</tbody>
</table>
</div></center>
</td>
<td width="25%" height="100%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; height:125px;">
<thead>
<tr class="ui-widget-header ">
<th>CC52 (Sanu)</th>
</tr>
</thead>
<tbody>
<tr height="75%">
<td><span style="font-size:36px"><?php echo $stats["CC52"]; ?></span></td>
</tr>
</tbody>
</table>
</div></center>
</td>
<td width="25%" height="100%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; height:125px;">
<thead>
<tr class="ui-widget-header ">
<th>CC53 (Daniel)</th>
</tr>
</thead>
<tbody>
<tr height="75%">
<td><span style="font-size:36px"><?php echo $stats["CC53"]; ?></span></td>
</tr>
</tbody>
</table>
</div></center>
</td>
<td width="25%" height="100%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; height:125px;">
<thead>
<tr class="ui-widget-header ">
<th>CC54 (Ash)</th>
</tr>
</thead>
<tbody>
<tr height="75%">
<td><span style="font-size:36px"><?php echo $stats["CC54"]; ?></span></td>
</tr>
</tbody>
</table>
</div></center>
</td>
</tr>
</table></center>

<center><table id="users" style="margin-top:0px; width:99%;">
<thead>
<tr class="ui-widget-header ">
<th colspan="2" style="border: 1px solid #eee; padding: .6em 10px; text-align: center;">Queen Street</th>
<th colspan="2" style="border: 1px solid #eee; padding: .6em 10px; text-align: center;">Lt Lonsdale</th>
</tr>
</thead>
<tr>
<td width="25%" height="100%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; height:125px;">
<thead>
<tr class="ui-widget-header ">
<th>CC61 (Jesse)</th>
</tr>
</thead>
<tbody>
<tr height="75%">
<td><span style="font-size:36px"><?php echo $stats["CC61"]; ?></span></td>
</tr>
</tbody>
</table>
</div></center>
</td>
<td width="25%" height="100%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; height:125px;">
<thead>
<tr class="ui-widget-header ">
<th>CC63 (Damith)</th>
</tr>
</thead>
<tbody>
<tr height="75%">
<td><span style="font-size:36px"><?php echo $stats["CC63"]; ?></span></td>
</tr>
</tbody>
</table>
</div></center>
</td>
<td width="25%" height="100%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; height:125px;">
<thead>
<tr class="ui-widget-header ">
<th>CC71 (Julie)</th>
</tr>
</thead>
<tbody>
<tr height="75%">
<td><span style="font-size:36px"><?php echo $stats["CC71"]; ?></span></td>
</tr>
</tbody>
</table>
</div></center>
</td>
<td width="25%" height="100%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; height:125px;">
<thead>
<tr class="ui-widget-header ">
<th>CC72 (Monika)</th>
</tr>
</thead>
<tbody>
<tr height="75%">
<td><span style="font-size:36px"><?php echo $stats["CC72"]; ?></span></td>
</tr>
</tbody>
</table>
</div></center>
</td>
</tr>
</table></center>

<center><table id="users" style="margin-top:0px; width:99%;">
<thead>
<tr class="ui-widget-header ">
<th colspan="2" style="border: 1px solid #eee; padding: .6em 10px; text-align: center;">Total</th>
</tr>
</thead>
<tr>
<td width="50%" height="100%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; height:125px;">
<thead>
<tr class="ui-widget-header ">
<th>Melbourne</th>
</tr>
</thead>
<tbody>
<tr height="75%">
<td><span style="font-size:36px"><?php echo $melb; ?></span></td>
</tr>
</tbody>
</table>
</div></center>
</td>
<td width="50%" height="100%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; height:125px;">
<thead>
<tr class="ui-widget-header ">
<th>India</th>
</tr>
</thead>
<tbody>
<tr height="75%">
<td><span style="font-size:36px"><?php echo $india; ?></span></td>
</tr>
</tbody>
</table>
</div></center>
</td>
</tr>
</table></center>

<center><table id="users" style="margin-top:0px; width:99%;">
<thead>
<tr class="ui-widget-header ">
<th style="border: 1px solid #eee; padding: .6em 10px; text-align: center;">These stats are based on Actual Sales</th>
</tr>
</thead>
</table></center>
<?php
exit;
}
else
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Stats</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<style>
div#users-contain table { margin: 0; border-collapse: collapse; }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: center; }
div#users-contain table td { border: 1px solid #eee; padding: .6em 5px; text-align: center; }
</style>
<?php
include "source/jquery.php";
?>
<?php
$time = date("H:i:s");
$time = explode(":", $time);

$t = 0;
$t += ($time[0] * 60 * 60);
$t += ($time[1] * 60);
$t += ($time[2]);
?>
<script>
function pad(number, length) {
	var str = '' + number;
	
	while (str.length < length) {
		str = '0' + str;
	}
	
	return str;
}

var Seconds=<?php echo $t; ?>;
function doTime() {
var timeString = "";
var secs = parseInt(Seconds % 60);
var mins = parseInt(Seconds / 60 % 60);
var hours = parseInt(Seconds / 3600 % 24);
var days = parseInt(Seconds / 86400);
period = ((hours > 11) ? " PM" : " AM");
if (hours > 12)
{
	hours = hours - 12;
}
hours = pad(hours,2);
mins = pad(mins,2);
secs = pad(secs,2);

timeString = hours + ":" + mins + ":" + secs + " " + period;
var span_el = document.getElementById("time");
var replaceWith = document.createTextNode(timeString);
span_el.replaceChild(replaceWith, span_el.childNodes[0]);
Seconds++;
setTimeout("doTime()",1000);
}

function Stats()
{
	$( "#text" ).load("screen.php?p=stats");
	setTimeout("Stats()",5000);
}

window.onload = function() {
	doTime();
	Stats();
}
</script>
</head>

<body>
<div id="main_wrapper" style="min-height:690px;">

<div id="innerpage_logo" style="padding-top:10px;">
<img src="../images/logo.png"  width="252" height="65" alt="logo" style="border-style:none;" />
</div>

<div id="logout" style="background-image:../images/clock.png; margin-top:-2px; height:auto;">
<table width="100%" height="17px" border="0" style="padding-right:22px; margin-top:-2px; margin-bottom:-2px;">
<tr valign="bottom">
<td align="right"><span class="clock" style="font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#0066cc; font-weight:bolder; margin-right:-10px;"><?php echo date("d/m/Y"); ?> <span id="time"><?php echo date("H:i:s A"); ?></span></span></td>
</tr>
</table>
</div>

<div id="menu" style="margin-top:10px; height:50px;">
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a>Sale Stats</a></li>
</ul>
</div>
</div>

<div style="display:none;">

</div>

<div id="text" style="margin-top:0px;">
<script>
$( "#text" ).load("screen.php?p=stats");
</script>
</div>

</div> 
<?php
include "source/footer.php";
?>
</body>
</html>
<?php
}
?>