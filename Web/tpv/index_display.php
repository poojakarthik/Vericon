<?php
include("../auth/restrict_inner.php");

$page = $_POST["page"];
?>
<table width="100%" height="500px">
<tr valign="top" height="95%">
<td>
<?php
$check = $mysqli->query("SELECT * FROM `vericon`.`announcements` WHERE `department` = 'TPV' AND `status` = 'Enabled'") or die($mysqli->error);
$rows = $check->num_rows;
$check->free();

if ($rows == 0)
{
	$st = 0;
	echo "<p>No Announcements</p>";
}
else
{
	$st = $page * 3;
	$q = $mysqli->query("SELECT `announcements`.`subject`, `announcements`.`message`, `announcements`.`timestamp`, CONCAT(`auth`.`first`, ' ', `auth`.`last`) AS poster FROM `vericon`.`announcements`, `vericon`.`auth` WHERE `announcements`.`department` = 'TPV' AND `announcements`.`status` = 'Enabled' AND `announcements`.`poster` = `auth`.`user` ORDER BY `announcements`.`id` DESC LIMIT $st , 3") or die($mysqli->error);
	while($r = $q->fetch_assoc())
	{
		echo "<p><span style='font-size:14px;'><b>" . $r["subject"] . "</b></span></p>";
		echo $r["message"];
		echo "<hr style='width:70%; height:1px; margin-top:10px; border-top:1px dotted #3a65b4; background:none;' />";
		echo "<p style='font-size:9px;'>Posted by " . $r["poster"] . " | " . date("d F Y h:i A", strtotime($r["timestamp"])) . "</p><br>";
	}
	$q->free();
}
$mysqli->close();
?>
</td>
</tr>
<tr valign="bottom">
<td>
<table width="100%">
<tr>
<td align="left" width="50%">
<?php
if (($st - 3) < $rows && $page > 0)
{
    $page_newer = $page - 1;
    echo "<button onClick='TPV01_More_Announcements(\"$page_newer\")' class='newer'></button>";
}
?>
</td>
<td align="right" width="50%">
<?php
if (($st + 3) < $rows)
{
	$page_older = $page + 1;
	echo "<button onClick='TPV01_More_Announcements(\"$page_older\")' class='older'></button>";
}
?>
</td>
</tr>
</table>
</td>
</tr>
</table>