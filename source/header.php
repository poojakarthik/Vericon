<div id="innerpage_logo">
<a href="../" style="border-style:none;"><img src="../images/logo.png"  width="252" height="65" alt="logo" /></a>
</div>
<script type="text/javascript">
function Logout()
{
	window.location = "../auth/logout.php";
}
</script>
<div id="logout">
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