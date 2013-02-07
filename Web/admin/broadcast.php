<?php
include("../auth/restrict.php");
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; background:none; }
div#users-contain table th { border: 1px solid rgba(41,171,226,0.25); padding: .6em 10px; text-align: center; }
div#users-contain table td { border: 1px solid rgba(41,171,226,0.25); padding: .6em 5px; text-align: center; }
div#users-contain table tbody tr:hover { background:rgba(255,255,255,0.25); }
</style>

<script>
function Admin07_Add_Message()
{
	V_Loading_Start();
	$( "#display_inner" ).load("/admin/broadcast_new.php", { }, function(data, status, xhr){
		if (status == "error")
		{
			if (xhr.status == 403 || xhr.status == 0)
			{
				$(".loading_message").html("<p><b>Your session has expired.</b></p><p><b>You will be logged out shortly.</b></p>");
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
<td valign="middle" nowrap="nowrap" width="1px"><h1>Broadcast Messages</h1></td>
<td><div class="dotted"></div></td>
</tr>
</table>
</div>

<div id="display_inner">
<center><div style="width:98%">
<table width="100%">
<tr>
<td align="right"><button onclick="Admin07_Add_Message()" id="Admin07_add_message" class="btn">Add Message</button></td>
</tr>
</table>
</div></center>

<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="98%">
<thead>
<tr class="ui-widget-header ">
<th width="40%" style="text-align:left;">Title</th>
<th width="30%">Recipient</th>
<th width="20%">Posted By</th>
<th width="10%">Expiry</th>
</tr>
</thead>
<tbody>
<?php
$q = $mysqli->query("SELECT `broadcast`.`title`, `broadcast`.`all`, `broadcast`.`department`, `broadcast`.`user`, `broadcast`.`end_timestamp`, CONCAT(`auth`.`first`, ' ', `auth`.`last`) AS name FROM `vericon`.`broadcast`, `vericon`.`auth` WHERE `broadcast`.`end_timestamp` > NOW() AND `broadcast`.`poster` = `auth`.`user` ORDER BY `id` ASC") or die($mysqli->error);
if ($q->num_rows == 0)
{
	echo "<tr>";
	echo "<td colspan='6' style='text-align:center;'>No current broadcast messages</td>";
	echo "</tr>";
}
else
{
	while ($broadcast = $q->fetch_assoc())
	{
		if ($broadcast["all"] == 1) {
			$recipient = "All";
		} elseif ($broadcast["department"] != "") {
			$q1 = $mysqli->query("SELECT `name` FROM `vericon`.`portals` WHERE `id` = '" . $mysqli->real_escape_string($broadcast["department"]) . "'") or die($mysqli->error);
			$data = $q1->fetch_row();
			$q1->free();
			$recipient = "Department - " . $data[0];
		} elseif ($broadcast["user"] != "") {
			$q1 = $mysqli->query("SELECT CONCAT(`first`, ' ', `last`) AS name FROM `vericon`.`auth` WHERE `user` = '" . $mysqli->real_escape_string($broadcast["user"]) . "'") or die($mysqli->error);
			$data = $q1->fetch_row();
			$q1->free();
			$recipient = "User - " . $data[0];
		} else {
			$recipient = "-";
		}
		
		echo "<tr>";
		echo "<td style='text-align:left'>" . $broadcast["title"] . "</td>";
		echo "<td>" . $recipient . "</td>";
		echo "<td>" . $broadcast["name"] . "</td>";
		echo "<td>" . date("h:i A", strtotime($broadcast["end_timestamp"])) . "</td>";
		echo "</tr>";
	}
}
$q->free();
$mysqli->close();
?>
</tbody>
</table>
</div></center>

</div>