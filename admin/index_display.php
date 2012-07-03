<?php
function format_uptime($seconds) {
  $secs = intval($seconds % 60);
  $mins = intval($seconds / 60 % 60);
  $hours = intval($seconds / 3600 % 24);
  $days = intval($seconds / 86400);
  
  if ($days > 0) {
    $uptimeString .= $days;
    $uptimeString .= (($days == 1) ? " day" : " days");
  }
  if ($hours > 0) {
    $uptimeString .= (($days > 0) ? ", " : "") . $hours;
    $uptimeString .= (($hours == 1) ? " hour" : " hours");
  }
  if ($mins > 0) {
    $uptimeString .= (($days > 0 || $hours > 0) ? ", " : "") . $mins;
    $uptimeString .= (($mins == 1) ? " minute" : " minutes");
  }
  if ($secs > 0) {
    $uptimeString .= (($days > 0 || $hours > 0 || $mins > 0) ? ", " : "") . $secs;
    $uptimeString .= (($secs == 1) ? " second" : " seconds");
  }
  return $uptimeString;
}

$uptime = exec("cat /proc/uptime");
$uptime = split(" ",$uptime);
$uptimeSecs = $uptime[0];

$staticUptime = format_uptime($uptimeSecs);

$time = date("H:i:s");
$time = explode(":", $time);

$t = 0;
$t += ($time[0] * 60 * 60);
$t += ($time[1] * 60);
$t += ($time[2]);
?>
<script language="javascript">
var upSeconds=<?php echo $uptimeSecs; ?>;
function doUptime()
{
	var uptimeString1 = "";
	var uptimeString2 = "";
	var uptimeString3 = "";
	var secs = parseInt(upSeconds % 60);
	var mins = parseInt(upSeconds / 60 % 60);
	var hours = parseInt(upSeconds / 3600 % 24);
	var days = parseInt(upSeconds / 86400);
	if (days > 0) {
	  uptimeString1 += days;
	  uptimeString3 += ((days == 1) ? " day" : " days");
	}
	if (hours > 0) {
	  uptimeString2 += ((days > 0) ? "" : "") + pad(hours,2);
	  uptimeString2 += ((hours == 1) ? " hour" : " hours");
	}
	if (mins > 0) {
	  uptimeString2 += ((days > 0 || hours > 0) ? ", " : "") + pad(mins,2);
	  uptimeString2 += ((mins == 1) ? " minute" : " minutes");
	}
	if (secs > 0) {
	  uptimeString2 += ((days > 0 || hours > 0 || mins > 0) ? ", " : "") + pad(secs,2);
	  uptimeString2 += ((secs == 1) ? " second" : " seconds");
	}
	var span_el = document.getElementById("uptime1");
	var replaceWith = document.createTextNode(uptimeString1);
	span_el.replaceChild(replaceWith, span_el.childNodes[0]);
	var span_el = document.getElementById("uptime2");
	var replaceWith = document.createTextNode(uptimeString2);
	span_el.replaceChild(replaceWith, span_el.childNodes[0]);
	var span_el = document.getElementById("uptime3");
	var replaceWith = document.createTextNode(uptimeString3);
	span_el.replaceChild(replaceWith, span_el.childNodes[0]);
	upSeconds++;
}
</script>
<script>
function doLoad(){
	$( "#load" ).load("index_process.php?method=load");
}
</script>
<script>
function doMem(){
	$( "#mem" ).load("index_process.php?method=mem");
}
</script>
<script>
doUptime();
setInterval("doUptime()",1000);
setInterval("doLoad()",5000);
setInterval("doMem()",10000);
</script>

<p><img src="../images/server_dashboard_header.png" width="190" height="25" style="margin-left:3px;" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p>

<center><div id="users-contain" class="ui-widget" style="margin-top:-5px;">
<table id="users" class="ui-widget ui-widget-content" style="width: 98%;">
<thead>
<tr class="ui-widget-header ">
<th colspan="6">Services</th>
</tr>
</thead>
<tbody>
<tr>
<td><b>HTTP</b></td>
<td style="text-align:center">80</td>
<td style="text-align:center"><?php $checkport = fsockopen("localhost", "80", $errnum, $errstr, 2); if(!$checkport) { echo "<img src='../images/down.png'>"; } else { echo "<img src='../images/up.png'>"; } ?></td>
<td><b>HTTPS</b></td>
<td style="text-align:center">443</td>
<td style="text-align:center"><?php $checkport = fsockopen("localhost", "443", $errnum, $errstr, 2); if(!$checkport) { echo "<img src='../images/down.png'>"; } else { echo "<img src='../images/up.png'>"; } ?></td>
</tr>
<tr>
<td><b>FTP</b></td>
<td style="text-align:center">21</td>
<td style="text-align:center"><?php $checkport = fsockopen("localhost", "21", $errnum, $errstr, 2); if(!$checkport) { echo "<img src='../images/down.png'>"; } else { echo "<img src='../images/up.png'>"; } ?></td>
<td><b>SFTP</b></td>
<td style="text-align:center">22</td>
<td style="text-align:center"><?php $checkport = fsockopen("localhost", "22", $errnum, $errstr, 2); if(!$checkport) { echo "<img src='../images/down.png'>"; } else { echo "<img src='../images/up.png'>"; } ?></td>
</tr>
<tr>
<td><b>MySQL</b></td>
<td style="text-align:center">3306</td>
<td style="text-align:center"><?php $checkport = fsockopen("localhost", "3306", $errnum, $errstr, 2); if(!$checkport) { echo "<img src='../images/down.png'>"; } else { echo "<img src='../images/up.png'>"; } ?></td>
<td colspan="3"></td>
</tr>
</tbody>
</table>
</div></center>

<center><table width="99%" style="margin-top:-10px; margin-bottom:-10px;">
<tr>
<td width="25%" height="100%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:99%; height:125px;">
<thead>
<tr class="ui-widget-header ">
<th>Uptime</th>
</tr>
</thead>
<tbody>
<tr height="75%">
<td>
<?php

?>
<div style="margin:5px 10px 0px 10px;"><span id="uptime1" style="font-size:32px;"><?php echo $staticUptime; ?></span><span id="uptime3" style="font-size:24px;"><?php echo $staticUptime; ?></span></div>
<div id="uptime2" style="margin-top:10px;"><?php echo $staticUptime; ?></div>
</td>
</tr>
<tr class="ui-widget-header">
<th><?php echo date("d F, Y"); ?></th>
</tr>
</tbody>
</table>
</div></center>
</td>
<td width="25%" height="100%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:99%; height:125px;">
<thead>
<tr class="ui-widget-header ">
<th>CPU Load</th>
</tr>
</thead>
<tbody>
<tr height="75%">
<td><div id="load">
<script>
$( "#load" ).load("index_process.php?method=load");
</script>
</div></td>
</tr>
<tr class="ui-widget-header">
<th>/proc/loadavg</th>
</tr>
</tbody>
</table>
</div></center>
</td>
<td width="25%" height="100%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:99%; height:125px;">
<thead>
<tr class="ui-widget-header ">
<th>Memory</th>
</tr>
</thead>
<tbody>
<tr height="75%">
<td><div id="mem">
<script>
$( "#mem" ).load("index_process.php?method=mem");
</script>
</div></td>
</tr>
<tr class="ui-widget-header">
<th>/proc/meminfo</th>
</tr>
</tbody>
</table>
</div></center>
</td>
<td width="25%" height="100%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:99%; height:125px;">
<thead>
<tr class="ui-widget-header ">
<th>Swap</th>
</tr>
</thead>
<tbody>
<tr height="75%">
<td>Coming Soon</td>
</tr>
<tr class="ui-widget-header">
<th>Swap</th>
</tr>
</tbody>
</table>
</div></center>
</td>
</tr>
</table></center>

<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width: 98%;">
<thead>
<tr class="ui-widget-header ">
<th colspan="4">Drives</th>
</tr>
</thead>
<tbody>
<?php
exec("df -h | tail -n +2 | awk '{print $2 \",\" $3 \",\" $5 \",\" $6}'",$drives);
foreach ($drives as $row)
{
	$disk_data = explode(",",$row);
?>
<tr>
<td width="10%"><?php echo $disk_data[3]; ?></td>
<td width="63%"><div class="ui-progressbar ui-widget ui-widget-content ui-corner-all">
<div style="width: <?php echo $disk_data[2]; ?>;" class="ui-progressbar-value ui-widget-header ui-corner-left"></div>
</div></td>
<td width="7%" style="text-align:center"><?php echo $disk_data[2]; ?></td>
<td width="10%" style="text-align:center"><?php echo $disk_data[1] . "/" . $disk_data[0]; ?></td>
</tr>
<?php
}
?>
</tbody>
</table>
</div></center>