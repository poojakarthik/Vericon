<?php
$plan = $_GET["plan"];

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$q = mysql_query("SELECT * FROM plan_rates WHERE plan_code = '" . mysql_escape_string($plan) . "'");
$r = mysql_fetch_assoc($q);
if (mysql_num_rows($q) == 0)
{
	echo "Please check the plan and enter it again!";
	exit;
}
elseif ($r["data"] == "")
{
	echo "<h1>" . $r["plan_code"] . "</h1>"; 
?>
<img src="../images/line.png" width="420" height="9" alt="line" />
<table width="420" cellspacing="0" cellpadding="5">
<tr>
<td width="60" height="35"><div align="center"><strong><img src="../images/planfee_icon.jpg" width="23" height="23" /></strong></div></td>
<td width="150" height="35"><b>Plan Fee</b></td>
<td width="210" height="35"><?php echo $r["plan_fee"]; ?></td>
</tr>

<tr bgcolor="#EFEFEF">
<td height="35"><div align="center"><img src="../images/contract_icon_orange.png" width="32" height="33" /></div></td>
<td width="150" height="35"><b>Contract Length</b></td>
<td width="210" height="35"><?php echo $r["contract_term"]; ?></td>
</tr>

<tr>
<td height="35"><div align="center"><img src="../images/localcalls_icon_transparent.png" width="32" height="33" /></div></td>
<td width="150" height="35"><b>Local Calls</b></td>
<td width="210" height="35"><?php echo $r["local"]; ?></td>
</tr>

<tr bgcolor="#EFEFEF">
<td height="35"><div align="center"><img src="../images/localcalls_icon_transparent.png" width="32" height="33" /></div></td>
<td width="150" height="35"><b>National Calls</b></td>
<td width="210" height="35"><?php echo $r["national"]; ?></td>
</tr>

<tr>
<td height="35"><div align="center"><img src="../images/mobilecappedcalls_icon_oran.png" width="32" height="33" /></div></td>
<td width="150" height="35"><b>Mobile Calls</b></td>
<td width="210" height="35"><?php echo $r["mobile"]; ?></td>
</tr>

<tr bgcolor="#EFEFEF">
<td height="35"><div align="center"><img src="../images/businesshours_icon_tranpare.png" alt="img" width="32" height="33" /></div></td>
<td width="150" height="35"><b>Early Termination Fee</b></td>
<td width="210" height="35"><?php echo $r["etf"]; ?></td>
</tr>
</table>

<?php
}
elseif ($r["local"] == "")
{
	echo "<h1>" . $r["plan_code"] . "</h1>";
?>
<img src="../images/line.png" width="420" height="9" alt="line" />
<table width="420" cellspacing="0" cellpadding="5">
<tr>
<td width="60" height="35"><div align="center"><strong><img src="../images/planfee_icon.jpg" width="23" height="23" /></strong></div></td>
<td width="150" height="35"><b>Plan Fee</b></td>
<td width="210" height="35"><?php echo $r["plan_fee"]; ?></td>
</tr>

<tr bgcolor="#EFEFEF">
<td height="35"><div align="center"><img src="../images/contract_icon_orange.png" width="32" height="33" /></div></td>
<td width="150" height="35"><b>Contract Length</b></td>
<td width="210" height="35"><?php echo $r["contract_term"]; ?></td>
</tr>

<tr>
<td height="35"><div align="center"><img src="../images/download_icon.png" width="30" height="31" /></div></td>
<td width="150" height="35"><b>Data Usage</b></td>
<td width="210" height="35"><?php echo $r["data"]; ?></td>
</tr>

<tr bgcolor="#EFEFEF">
<td height="35"><div align="center"><img src="../images/businesshours_icon_tranpare.png" alt="img" width="32" height="33" /></div></td>
<td width="150" height="35"><b>Early Termination Fee</b></td>
<td width="210" height="35"><?php echo $r["etf"]; ?></td>
</tr>
</table>

<?php
}
?>