<?php
mysql_connect('localhost','vericon','18450be');

$p = explode(",",$_GET["p"]);
?>

<div id="vericon_portals">
<table class="script_table" style="padding: 15px 15px 7px;">
<tr><td><img src="../images/vericon_portals_header.png" width="170" height="25" /></td></tr>
<tr><td><img src="images/line.png" width="715" height="9" /></td></tr>
</table>
<table width="100%">
<tr>
<?php
for ($i = 0;$i < count($p);$i++)
{
	$q = mysql_query("SELECT name FROM vericon.portals WHERE id = '$p[$i]'") or die(mysql_error());
	$p_name = mysql_fetch_row($q);
	echo "<td align='center' valign='middle'>";
	echo "<button onclick='Go(\"$p[$i]\")' class='portal_btn'>$p_name[0]</button>";
	echo "</td>";
}
?>
</tr>
</table>
</div>
<br>
<p><img src="../images/vericon_updates_header.png" width="175" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p><br />

<?php
$q = mysql_query("SELECT * FROM vericon.updates ORDER BY id DESC LIMIT 1") or die(mysql_error());

while($r = mysql_fetch_assoc($q))
{
	echo "<p><b>" . $r["subject"] . $icon . "</b></p>";
	echo "<p>" . nl2br($r["message"]) . "</p>";
	echo "<img src='../images/line.png' width='200' height='9'>";
	echo "<p style='font-size:9px;'>Posted by " . $r["poster"] . " | " . $r["date"] . "</p><br>";
}
?>