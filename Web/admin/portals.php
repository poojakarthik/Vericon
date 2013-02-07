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
function Admin06_Add_Portal()
{
	V_Loading_Start();
	$( "#display_inner" ).load("/admin/portals_new.php", { }, function(data, status, xhr){
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

function Admin06_Edit_Portal(id)
{
	if ($( ".blockUI" ).val() != "")
	{
		V_Loading_Start();
	}
	$( "#display_inner" ).load("/admin/portals_edit.php", { id: id }, function(data, status, xhr){
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

function Admin06_Toggle_Status(id, method)
{
	V_Loading_Start();
	$.post("/admin/portals_process.php", { m: method, id: id }, function(data) {
		V_Page_Reload();
	}).error( function(xhr, text, err) {
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
	});
}
</script>

<div class="head">
<table>
<tr>
<td style="width:90px;"><div class="dotted"></div></td>
<td valign="middle" nowrap="nowrap" width="1px"><h1>Portal Management</h1></td>
<td><div class="dotted"></div></td>
</tr>
</table>
</div>

<div id="display_inner">
<center><div style="width:98%">
<table width="100%">
<tr>
<td align="right"><button onclick="Admin06_Add_Portal()" id="Admin06_add_portal" class="btn">Add Portal</button></td>
</tr>
</table>
</div></center>

<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="98%">
<thead>
<tr class="ui-widget-header ">
<th width="30%" style="text-align:left;">ID</th>
<th width="40%">Name</th>
<th width="20%">Status</th>
<th width="10%" colspan="2">Edit</th>
</tr>
</thead>
<tbody>
<?php
$q = $mysqli->query("SELECT * FROM `vericon`.`portals` ORDER BY `id` ASC") or die($mysqli->error);
while ($portals = $q->fetch_assoc())
{
	echo "<tr>";
	echo "<td style='text-align:left'>" . $portals["id"] . "</td>";
	echo "<td style='text-align:left'>" . $portals["name"] . "</td>";
	echo "<td>" . $portals["status"] . "</td>";
	echo "<td><button onclick='Admin06_Edit_Portal(\"$portals[id]\")' class='icon_edit' title='Edit'></button></td>";
	if ($portals["status"] == "Enabled") {
		if ($portals["id"] == "MA" || $portals["id"] == "Admin") {
			echo "<td>-</td>";
		} else {
			echo "<td><button onclick='Admin06_Toggle_Status(\"$portals[id]\",\"disable\")' class='icon_disable' title='Disable'></button></td>";
		}
	} else {
		echo "<td><button onclick='Admin06_Toggle_Status(\"$portals[id]\",\"enable\")' class='icon_enable' title='Enable'></button></td>";
	}
	echo "</tr>";
}
$q->free();
$mysqli->close();
?>
</tbody>
</table>
</div></center>

</div>