<?php
mysql_connect('localhost','vericon','18450be');

$p = explode(",",$_GET["p"]);
?>
<p><img src="../images/video_tutorials_header.png" width="160" height="25" style="margin-left:3px;" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p>

<center><table width="98%">
<?php
for ($i = 0;$i < count($p);$i++)
{
	$q = mysql_query("SELECT * FROM vericon.tutorials WHERE portal = '$p[$i]'") or die(mysql_error());
	$q1 = mysql_query("SELECT name FROM vericon.portals WHERE id = '$p[$i]'") or die(mysql_error());
	$p_name = mysql_fetch_row($q1);
	echo "<tr>";
	echo "<td colspan='" . (mysql_num_rows($q) + 1) . "'><h1><u>" . $p_name[0] . "</u></h1></td>";
	echo "</tr>";
	echo "<tr>";
	if (mysql_num_rows($q) == 0)
	{
		echo "<td>Coming Soon</td>";
	}
	else
	{
		while ($data = mysql_fetch_assoc($q))
		{
			echo "<td>";
			echo "<table>";
			echo "<tr>";
			echo "<td align='center'><a onclick='Play(\"$data[id]\")' style='cursor:pointer;'><img src='thumbnail.php?id=$data[id]' style='border:1px solid black'></a></td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td align='center'>" . $data["name"] . "</td>";
			echo "</tr>";
			echo "</table>";
			echo "</td>";
		}
	}
	echo "</tr>";
}
?>
</table></center>