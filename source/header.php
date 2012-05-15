<div id="innerpage_logo">
<a href="../"><img src="../images/logo.png"  width="252" height="65" alt="logo" style="border-style:none;" /></a>
</div>
<script type="text/javascript">
function Logout()
{
	window.location = "../auth/logout.php";
}
</script>
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

window.onload = function() {
	doTime();
}
</script>
<div id="logout">
<table width="100%" height="17px" border="0" style="padding-right:22px; margin-top:-2px; margin-bottom:-2px;">
<tr valign="bottom">
<td align="right"><span class="clock" style="font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#0066cc; font-weight:bolder; margin-right:-10px;"><?php echo date("d/m/Y"); ?> <span id="time"><?php echo date("H:i:s A"); ?></span></span></td>
</tr>
</table>
<table width="100%" height="24px" border="0" style="padding-right:23px;">
<tr valign="bottom">
<td align="right"><span style="font-family:Tahoma, Geneva, sans-serif; font-size:11px; color:#666;"><?php echo $ac["user"]; ?></span></td>
</tr>
</table>
<table width="100%" height="24px" border="0" style="padding-right:23px;">
<tr valign="bottom">
<td align="right" valign="middle"><?php if($acc["tpv"] == true || $acc["cct"] == true || $acc["cs"] == true) { ?><img src="../images/webmail_icon.png" /> <a href="../webmail/?u=<?php echo $ac["user"]; ?>" target="_blank">Webmail</a>&nbsp;<?php } ?><img src="../images/logout_icon.png" /> <a onclick="Logout()">Logout</a></td>
</tr>
</table>
</div>