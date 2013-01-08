<?php
include("../auth/restrict.php");
?>
<script>
function TPV01_More_Announcements(page)
{
	V_Loading_Start();
	$( "#display_inner" ).load("/tpv/index_display.php", { page: page }, function(data, status, xhr){
		if (status == "error")
		{
			if (xhr.status == 420)
			{
				$(".loading_message").html("<p><b>Your session has expired.</b></p><p><b>You will be logged out shortly.</b></p>");
				setTimeout(function() {
					V_Logout();
				}, 2500);
			}
			else if (xhr.status == 421)
			{
				$(".loading_message").html("<p><b>Your account has been disabled.</b></p><p><b>You will be logged out shortly.</b></p>");
				setTimeout(function() {
					V_Logout();
				}, 2500);
			}
			else
			{
				$(".loading_message").html("<p><b>An error occurred while performing this action.</b></p><p><b>Error: " + xhr.status + " " + xhr.statusText + "</b></p>");
				setTimeout(function() {
					V_Loading_End();
				}, 2500);
			}
		}
		else
		{
			V_Loading_End();
		}
	});
}
</script>

<div class="head">
<table>
<tr>
<td style="width:90px;"><div class="dotted"></div></td>
<td valign="middle" nowrap="nowrap" width="1px"><h1>Announcements</h1></td>
<td><div class="dotted"></div></td>
</tr>
</table>
</div>

<div id="display_inner" style="width:98%; margin:0 auto;">
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
	$st = 0;
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
<td align="left" width="50%"></td>
<td align="right" width="50%">
<?php
if (($st + 3) < $rows)
{
	$page = 1;
	echo "<button onClick='TPV01_More_Announcements(\"$page\")' class='older'></button>";
}
?>
</td>
</tr>
</table>
</td>
</tr>
</table>
</div>