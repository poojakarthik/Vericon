<?php
include "auth/iprestrict.php";
include "source/header.php";
?>
<script>
function Go(link)
{
	window.location = "/" + link + "/";
}
</script>
<style>
#vericon_portals{
width:753px;
height:162px;
background-image:url('../images/vericon_portals_bg.png');
background-repeat:no-repeat;
margin-left:auto;
margin-right:auto;
margin-top:10px;
}
</style>
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
	$q = mysql_query("SELECT name FROM vericon.portals WHERE id = '$p1[$i]'") or die(mysql_error());
	$p_name = mysql_fetch_row($q);
	echo "<td align='center' valign='middle'>";
	echo "<button onclick='Go(\"$p1[$i]\")' class='portal_btn'>$p_name[0]</button>";
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