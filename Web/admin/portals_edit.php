<?php
include("../auth/restrict_inner.php");

$id = $_POST["id"];
?>
<script>
function Admin06_Edit_Portal_Cancel()
{
	V_Page_Reload();
}

function Admin06_Add_Page(portal_id)
{
	V_Loading_Start();
	$( "#display_inner" ).load("/admin/portals_new_page.php", { portal_id: portal_id }, function(data, status, xhr){
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

function Admin06_Edit_Page(portal_id,id)
{
	V_Loading_Start();
	$( "#display_inner" ).load("/admin/portals_edit_page.php", { portal_id: portal_id, id: id }, function(data, status, xhr){
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

function Admin06_Toggle_Page_Status(id, method)
{
	V_Loading_Start();
	$.post("/admin/portals_process.php", { m: "page_" + method, id: id }, function(data) {
		Admin06_Edit_Portal("<?php echo $id; ?>");
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

<center><div style="width:98%">
<table width="100%">
<tr>
<td align="right"><button onclick="Admin06_Add_Page('<?php echo $id; ?>')" id="Admin06_add_page" class="btn">Add Page</button><button onclick="Admin06_Edit_Portal_Cancel()" class="btn" style="margin-left:10px;">Cancel</button></td>
</tr>
</table>
</div></center>

<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="98%">
<thead>
<tr class="ui-widget-header ">
<th width="20%" style="text-align:left;">ID</th>
<th width="40%">Name</th>
<th width="15%">Level</th>
<th width="15%">Status</th>
<th width="10%" colspan="2">Edit</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT * FROM `vericon`.`portals_pages` WHERE `portal` = '" . mysql_real_escape_string($id) . "' ORDER BY `level`,`sub_level` ASC") or die(mysql_error());
while ($pages = mysql_fetch_assoc($q))
{
	if ($pages["sub_level"] != 0) { $level = $pages["level"] . " - " . $pages["sub_level"]; } else { $level = $pages["level"]; }
	
	echo "<tr>";
	echo "<td style='text-align:left'>" . $pages["id"] . "</td>";
	echo "<td style='text-align:left'>" . $pages["name"] . "</td>";
	echo "<td>" . $level . "</td>";
	echo "<td>" . $pages["status"] . "</td>";
	if ($pages["id"] == $id . "01") {
		echo "<td>-</td>";
	} else {
		echo "<td><button onclick='Admin06_Edit_Page(\"$id\",\"$pages[id]\")' class='icon_edit' title='Edit'></button></td>";
	}
	if ($pages["status"] == "Enabled") {
		if ($pages["id"] == $id . "01") {
			echo "<td>-</td>";
		} else {
			echo "<td><button onclick='Admin06_Toggle_Page_Status(\"$pages[id]\",\"disable\")' class='icon_disable' title='Disable'></button></td>";
		}
	} else {
		if ($pages["id"] == $id . "01") {
			echo "<td>-</td>";
		} else {
			echo "<td><button onclick='Admin06_Toggle_Page_Status(\"$pages[id]\",\"enable\")' class='icon_enable' title='Enable'></button></td>";
		}
	}
	echo "</tr>";
}
?>
</tbody>
</table>
</div></center>