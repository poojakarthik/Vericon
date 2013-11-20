<?php
mysql_connect('localhost','vericon','18450be');
?>

<table width="100%">
<tr>
<td style="padding-left:5px;"><img src="../images/cs_announcements_header.png" width="200" height="25" /></td>
</tr>
<tr>
<td><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<div style="width:98%; margin-left:auto; margin-right:auto;">
<?php
$check = mysql_query("SELECT * FROM vericon.announcements WHERE (department = 'cs' OR department = 'all') AND display = 'Yes'") or die(mysql_error());
$rows = mysql_num_rows($check);

if ($rows == 0)
{
	echo "<p>No Announcements!</p>";
}
else
{
	$st = $_GET["page"]*4;
	$q = mysql_query("SELECT * FROM vericon.announcements WHERE (department = 'cs' OR department = 'all') AND display = 'Yes' ORDER BY id DESC LIMIT $st , 4") or die(mysql_error());

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
?>

<table width="100%">
<tr>
<td align="left" width="50%">
<?php
if (($st - 4) < $rows && $_GET["page"] > 0)
{
    $page = $_GET["page"]-1;
    echo "<input type='button' onClick='Display(\"$page\")' class='newer'>";
}
?>
</td>
<td align="right" width="50%">
<?php
if (($st + 4) < $rows)
{
	$page = $_GET["page"]+1;
	echo "<input type='button' onClick='Display(\"$page\")' class='older'>";
}
?>
</td>
</tr>
</table>
</div>