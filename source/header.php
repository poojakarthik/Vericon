<div id="innerpage_logo">
<a href="../"><img src="../images/logo.png"  width="252" height="65" alt="logo" style="border-style:none;" /></a>
</div>
<script type="text/javascript">
function Logout()
{
	window.location = "../auth/logout.php";
}
</script>
<script>
setInterval(function() {
    $.get("../source/clock.php", function(date) { $( ".clock" ).html(date)});
}, 30000);
</script>
<div id="logout">
<table width="100%" height="17px" border="0" style="padding-right:22px; margin-top:-2px; margin-bottom:-2px;">
<tr valign="bottom">
<td align="right"><span class="clock" style="font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#0066cc; font-weight:bolder;"><?php echo date("d/m/Y h:i A"); ?></span></td>
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