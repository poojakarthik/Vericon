<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>

<p><img src="../images/tpv_announcements_header.png" width="215" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p><br />

<?php
$check = mysql_query("SELECT * FROM announcements WHERE (department = 'tpv' OR department = 'all') AND display = 'Yes'") or die(mysql_error());
$rows = mysql_num_rows($check);

if ($rows == 0)
{
	echo "<p>No Announcements!</p>";
}
else
{
	$st = $_GET["page"]*4;
	
	$q = mysql_query("SELECT * FROM announcements WHERE (department = 'tpv' OR department = 'all') AND display = 'Yes' ORDER BY id DESC LIMIT $st , 4") or die(mysql_error());
	
	if ($rows <= 4)
	{
		while($r = mysql_fetch_assoc($q))
		{
			$icon = "";
			if ($r["department"] == "all")
			{
				$icon = "&nbsp;<img src='../images/star.gif'>";
			}
			echo "<p><b>" . $r["subject"] . $icon . "</b></p>";
			echo "<p>" . nl2br($r["message"]) . "</p>";
			echo "<img src='../images/line.png' width='200' height='9'>";
			echo "<p style='font-size:9px;'>Posted by " . $r["poster"] . " | " . $r["date"] . "</p><br>";
		}
	}
	else
	{
		while($r = mysql_fetch_assoc($q))
		{
			$icon = "";
			if ($r["department"] == "all")
			{
				$icon = "&nbsp;<img src='../images/star.gif'>";
			}
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
		if (($st - 4) < $rows && $_GET["page"] > 0)
		{
			$page = $_GET["page"]-1;
			echo "<a href='?page=$page' class='newer'></a>";
		}
		?>
        </td>
        <td align="right">
        <?php
		if (($st + 4) < $rows)
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
}

include "../source/footer.php";
?>