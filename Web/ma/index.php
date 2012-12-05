<?php
include("../auth/restrict.php");
?>
<div class="head">
<table>
<tr>
<td style="width:90px;"><div class="dotted"></div></td>
<td valign="middle" nowrap="nowrap" width="1px"><h1>VeriCon Portals</h1></td>
<td><div class="dotted"></div></td>
</tr>
</table>
</div>
<div class="box">
<hr />
<table>
<tr>
<?php
$q = mysql_query("SELECT `id`,`name`,`status` FROM `vericon`.`portals` WHERE `id` != 'MA'") or die(mysql_error());
$i = 0;
$active_portals = explode(",", $ac["type"]);
while ($portals = mysql_fetch_row($q))
{
	if ($i == 5)
	{
		echo "</tr>";
		echo "<tr>";
		$i = 0;
	}
	
	if ((in_array($portals[0], $active_portals) || $ac["type"] == "Admin") && $portals[2] == "Enabled")
	{
		echo "<td><button class='portal' onclick='V_Page_Load(\"" . $portals[0] . "01\", \"\", \"/" . strtolower($portals[0]) . "/index.php\"); V_Menu_Load(\"" . strtolower($portals[0]) . "\")'>" . $portals[1] . "</button></td>";
	}
	else
	{
		echo "<td><button class='portal' disabled='disabled'>" . $portals[1] . "</button></td>";
	}
	
	$i++;
}
?>

</tr>
</table>
<hr />
</div>

<table width="100%">
<tr>
<td colspan="2" valign="top">
<div class="head">
<table>
<tr>
<td style="width:90px;"><div class="dotted"></div></td>
<td valign="middle" nowrap="nowrap" width="1px"><h1>VeriCon Updates</h1></td>
<td><div class="dotted"></div></td>
</tr>
</table>
</div>
</td>
</tr>
<tr>
<td width="75%" valign="top">
<div class="text">
<?php
$q = mysql_query("SELECT `updates`.`subject`, `updates`.`message`, `updates`.`date`, `auth`.`first`, `auth`.`last` FROM `vericon`.`updates`,`vericon`.`auth` WHERE `updates`.`poster` = `auth`.`user` ORDER BY `updates`.`id` DESC LIMIT 1") or die(mysql_error());
$update = mysql_fetch_assoc($q);
?>
<p><span style="font-size:14px;"><b><?php echo $update["subject"]; ?></b></span></p>
<?php
echo $update["message"];
?>
<hr style="width:70%; height:1px; margin-top:20px; border-top:1px dotted #3a65b4; background:none;" />
<span style="font-size:9px;">Posted by <?php echo $update["first"] . " " . $update["last"]; ?> | <?php echo date("d F Y", strtotime($update["date"])); ?></span>
</div>
</td>
<td width="25%" rowspan="2" valign="middle">
<div style="width:375px; height:auto; margin:0px; padding:10px 0 15px 0; float:left;">
<div style="width:103px; height:auto; margin:0px; padding:0px; float:left;"><img src="../images/icon.png" /></div>
<div class="links">
<ul>
<li>Optimising Efficiency</li>
<li>Unifying Systems</li>
<li>Streamlining Communications</li>
<li>Superior processes</li>
<li>Enhanced organization</li>
</ul>
</div>
</div>
</td>
</tr>
</table>