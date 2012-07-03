<?php
include "auth/iprestrict.php";
include "source/header.php";
?>
<link rel="stylesheet" href="../css/main.css" type="text/css"/>
<div id="vericon_portals" style="margin-top:-10px;">
<table class="script_table" style="padding: 15px 15px 7px;">
<tr><td><img src="../images/vericon_portals_header.png" width="170" height="25" /></td></tr>
<tr><td><img src="images/line.png" width="715" height="9" alt="line" /></td></tr>
</table>
<table width="100%">
<tr>
<?php
for ($i = 0;$i < count($p1);$i++)
{
	echo "<td align='center' valign='middle'>";
	echo "<a href=\"$p1[$i]\" class=\"" . $p1[$i] . "_portal\"></a>";
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
$check = mysql_query("SELECT * FROM updates") or die(mysql_error());
$rows = mysql_num_rows($check);

if ($rows == 0)
{
	echo "<p>No Updates?</p>";
}
else
{
	$st = $_GET["page"]*1;
	
	$q = mysql_query("SELECT * FROM updates ORDER BY id DESC LIMIT $st , 1") or die(mysql_error());

	while($r = mysql_fetch_assoc($q))
	{
		echo "<p><b>" . $r["subject"] . $icon . "</b></p>";
		echo "<p>" . nl2br($r["message"]) . "</p>";
		echo "<img src='../images/line.png' width='200' height='9'>";
		echo "<p style='font-size:9px;'>Posted by " . $r["poster"] . " | " . $r["date"] . "</p><br>";
	}
	?>
	<table width="100%">
	<tr>
	<td align="left">
	<?php
	if (($st - 1) < $rows && $_GET["page"] > 0)
	{
		$page = $_GET["page"]-1;
		echo "<a href='?page=$page' class='newer'></a>";
	}
	?>
	</td>
	<td align="right">
	<?php
	if (($st + 1) < $rows)
	{
		$page = $_GET["page"]+1;
		echo "<a href='?page=$page' class='older'></a>";
	}
	?>
	</td>
	</tr>
	</table>
	<?php
}

include "source/footer.php";
?>