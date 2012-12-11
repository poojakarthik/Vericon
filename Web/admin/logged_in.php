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
function Admin02_Logout_User(user)
{
	V_Loading_Start();
	$.post("/admin/logged_in_process.php", { user: user }, function(data) {
		V_Page_Reload();
	}).error( function(xhr, text, err) {
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
	});
}
</script>

<div class="head">
<table>
<tr>
<td style="width:90px;"><div class="dotted"></div></td>
<td valign="middle" nowrap="nowrap" width="1px"><h1>Logged In Users</h1></td>
<td><div class="dotted"></div></td>
</tr>
</table>
</div>

<center><div id="users-contain" class="ui-widget" style="width:98%;">
<table id="users" class="ui-widget ui-widget-content" width="100%" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th width='10%' style='text-align:left;'>Username</th>
<th width='20%' style='text-align:left;'>Name</th>
<th width='15%'>Account Access</th>
<th width='15%'>Login Time</th>
<th width='20%'>Current Page</th>
<th width='15%'>Last Action</th>
<th width='5%'>Logout</th>
</tr>
</thead>
<tbody>
<?php
$i = 0;
$q = mysql_query("SELECT `auth`.`user`, `auth`.`type`, `auth`.`centre`, `auth`.`first`, `auth`.`last`, `current_users`.`timestamp`, `current_users`.`last_action`, `portals_pages`.`name` AS page_name, `portals`.`name` AS portal_name FROM `vericon`.`current_users`,`vericon`.`auth`,`vericon`.`portals_pages`,`vericon`.`portals` WHERE `current_users`.`user` = `auth`.`user` AND `current_users`.`current_page` = `portals_pages`.`id` AND `portals_pages`.`portal` = `portals`.`id` ORDER BY `current_users`.`user` ASC") or die(mysql_error());
while($current = mysql_fetch_assoc($q))
{
	if ($i == 25)
	{
		echo "<tr class='ui-widget-header'>";
		echo "<th width='10%' style='text-align:left;'>Username</th>";
		echo "<th width='20%' style='text-align:left;'>Name</th>";
		echo "<th width='15%'>Account Access</th>";
		echo "<th width='15%'>Login Time</th>";
		echo "<th width='20%'>Current Page</th>";
		echo "<th width='15%'>Last Action Time</th>";
		echo "<th width='5%'>Logout</th>";
		echo "</tr>";
		$i = 0;
	}
	
	if ($current["type"] == "Sales") {
		$type = $current["type"] . " - " . $current["centre"];
	} else {
		$type = $current["type"];
	}
	
	echo "<tr>";
	echo "<td style='text-align:left;'>" . $current["user"] . "</td>";
	echo "<td style='text-align:left;'>" . $current["first"] . " " . $current["last"] . "</td>";
	echo "<td>" . $type . "</td>";
	echo "<td>" . date("d/m/Y H:i:s", strtotime($current["timestamp"])) . "</td>";
	echo "<td>" . $current["portal_name"] . " :: " . $current["page_name"] . "</td>";
	echo "<td>" . date("d/m/Y H:i:s", strtotime($current["last_action"])) . "</td>";
	echo "<td><button onclick='Admin02_Logout_User(\"$current[user]\")' class='icon_logout' title='Logout'></button></td>";
	echo "</tr>";
	$i++;
}
?>
</tbody>
</table>
</div></center>