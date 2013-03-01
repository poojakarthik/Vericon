<?php
include("../auth/restrict.php");
$mysqli->close();

$uptime = exec("cat /proc/uptime");
$uptime = split(" ",$uptime);
$uptimeSecs = substr($uptime[0],0,-3);
?>
<script>
function doServices(){
	$( "#services" ).load("/admin/index_process.php", { m: "services" });
}

var upSeconds = "<?php echo $uptimeSecs; ?>";
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
	$( "#uptime_days" ).html(uptimeString1);
	$( "#uptime_days_text" ).html(uptimeString3);
	$( "#uptime_time" ).html(uptimeString2);
	upSeconds++;
}

function doLoad(){
	$( "#load" ).load("/admin/index_process.php", { m: "load" });
}

function doMem(){
	$( "#mem" ).load("/admin/index_process.php", { m: "mem" });
}

function doSwap(){
	$( "#swap" ).load("/admin/index_process.php", { m: "swap" });
}
</script>
<script>
clearInterval(doServicesInterval);
clearInterval(doUptimeInterval);
clearInterval(doLoadInterval);
clearInterval(doMemInterval);
clearInterval(doSwapInterval);

var doServicesInterval = setInterval("doServices()",15000);
var doUptimeInterval = setInterval("doUptime()",1000);
var doLoadInterval = setInterval("doLoad()",5000);
var doMemInterval = setInterval("doMem()",10000);
var doSwapInterval = setInterval("doSwap()",10000);
</script>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; background:none; }
div#users-contain table th { border: 1px solid rgba(41,171,226,0.25); padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid rgba(41,171,226,0.25); padding: .6em 5px; text-align: left; }
.ui-widget-content { background: none; border: 1px solid rgba(41,171,226,0.25); }
</style>

<div class="head">
<table>
<tr>
<td style="width:90px;"><div class="dotted"></div></td>
<td valign="middle" nowrap="nowrap" width="1px"><h1>Server Dashboard</h1></td>
<td><div class="dotted"></div></td>
</tr>
</table>
</div>

<center><div id="users-contain" class="ui-widget" style="margin-top:-5px;">
<table id="users" class="ui-widget ui-widget-content" style="width: 98%;">
<thead>
<tr class="ui-widget-header ">
<th width="25%">Services</th>
<th width="15%" style="text-align:center;">HTTPS (443)</th>
<th width="15%" style="text-align:center;">SFTP/SSH (21119)</th>
<th width="15%" style="text-align:center;">MySQL (3306)</th>
<th width="15%" style="text-align:center;">IMAPS (993)</th>
<th width="15%" style="text-align:center;">SMTPS (465)</th>
</tr>
</thead>
<tbody id="services">
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
</tbody>
</table>
</div></center>

<center><table width="99%" style="margin-top:-10px; margin-bottom:-10px;">
<tr>
<td width="25%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:99%; height:150px;">
<thead>
<tr class="ui-widget-header ">
<th>Uptime</th>
</tr>
</thead>
<tbody>
<tr height="75%">
<td style="padding-left:15px;">
<div style="margin:5px 10px 0px 10px;"><span id="uptime_days" style="font-size:32px;"></span><span id="uptime_days_text" style="font-size:24px;"></span></div>
<div id="uptime_time" style="margin-top:10px;"></div>
</td>
</tr>
<script>
doUptime();
</script>
<tr class="ui-widget-header">
<th><?php echo date("d F, Y"); ?></th>
</tr>
</tbody>
</table>
</div></center>
</td>
<td width="25%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:99%; height:150px;">
<thead>
<tr class="ui-widget-header ">
<th>CPU Load</th>
</tr>
</thead>
<tbody>
<tr height="75%">
<td><div id="load">
<script>
doLoad();
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
<td width="25%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:99%; height:150px;">
<thead>
<tr class="ui-widget-header ">
<th>Memory</th>
</tr>
</thead>
<tbody>
<tr height="75%">
<td><div id="mem">
<script>
doMem();
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
<td width="25%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:99%; height:150px;">
<thead>
<tr class="ui-widget-header ">
<th>Swap</th>
</tr>
</thead>
<tbody>
<tr height="75%">
<td><div id="swap">
<script>
doSwap();
</script>
</div></td>
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
exec("df -h | tail -n +2 | awk '{print $1 \",\" $2 \",\" $3\",\" $4 \",\" $5 \",\" $6}'",$drives);
foreach ($drives as $row)
{
	$disk_data = explode(",",$row);
	if ($disk_data[5] != "" && $disk_data[4] != "" && $disk_data[3] != "" && $disk_data[2] != "" && $disk_data[1] != "")
	{
?>
<tr>
<td width="10%"><?php echo $disk_data[5]; ?></td>
<td width="63%"><div class="ui-progressbar ui-widget ui-widget-content ui-corner-all">
<div style="width: <?php echo $disk_data[4]; ?>;" class="ui-progressbar-value ui-widget-header ui-corner-left"></div>
</div></td>
<td width="7%" style="text-align:center"><?php echo $disk_data[4]; ?></td>
<td width="10%" style="text-align:center"><?php echo $disk_data[2] . "/" . $disk_data[1]; ?></td>
</tr>
<?php
	}
	elseif ($disk_data[4] == "/var/vericon")
	{
?>
<tr>
<td width="10%">/mnt/lb01</td>
<td width="63%"><div class="ui-progressbar ui-widget ui-widget-content ui-corner-all">
<div style="width: <?php echo $disk_data[3]; ?>;" class="ui-progressbar-value ui-widget-header ui-corner-left"></div>
</div></td>
<td width="7%" style="text-align:center"><?php echo $disk_data[3]; ?></td>
<td width="10%" style="text-align:center"><?php echo $disk_data[1] . "/" . $disk_data[0]; ?></td>
</tr>
<?php
	}
	elseif ($disk_data[4] == "/var/rec")
	{
?>
<tr>
<td width="10%">/mnt/st01</td>
<td width="63%"><div class="ui-progressbar ui-widget ui-widget-content ui-corner-all">
<div style="width: <?php echo $disk_data[3]; ?>;" class="ui-progressbar-value ui-widget-header ui-corner-left"></div>
</div></td>
<td width="7%" style="text-align:center"><?php echo $disk_data[3]; ?></td>
<td width="10%" style="text-align:center"><?php echo $disk_data[1] . "/" . $disk_data[0]; ?></td>
</tr>
<?php
	}
}
?>
</tbody>
</table>
</div></center>