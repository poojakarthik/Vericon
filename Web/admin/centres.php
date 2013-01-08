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
function Admin05_Toggle_Status(centre,method)
{
	V_Loading_Start();
	$.post("/admin/centres_process.php", { m: method, centre: centre }, function(data) {
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

function Admin05_Add_Centre()
{
	V_Loading_Start();
	$( "#display_inner" ).load("/admin/centres_new.php", { }, function(data, status, xhr){
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

function Admin05_Edit_Centre(centre)
{
	V_Loading_Start();
	$( "#display_inner" ).load("/admin/centres_edit.php", { centre: centre }, function(data, status, xhr){
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
<td valign="middle" nowrap="nowrap" width="1px"><h1>Call Centres</h1></td>
<td><div class="dotted"></div></td>
</tr>
</table>
</div>

<div id="display_inner">
<center><div style="width:98%">
<table width="100%">
<tr>
<td align="right"><button onclick="Admin05_Add_Centre()" id="Admin05_add_centre" class="btn">Add Centre</button></td>
</tr>
</table>
</div></center>

<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="98%">
<thead>
<tr class="ui-widget-header ">
<th style="text-align:center;" width="8%">Centre</th>
<th style="text-align:center;" width="56%">Campaign</th>
<th style="text-align:center;" width="8%">Type</th>
<th style="text-align:center;" width="12%">Lead Validation</th>
<th style="text-align:center;" width="8%">Status</th>
<th style="text-align:center;" width="8%" colspan="2">Edit</th>
</tr>
</thead>
<tbody>
<?php
$q = $mysqli->query("SELECT * FROM `vericon`.`centres` WHERE `centres`.`id` != '' ORDER BY `id` ASC") or die($mysqli->error);
while ($centre = $q->fetch_assoc())
{
	$campaign = array();
	$cam = explode(",", $centre["campaign"]);
	foreach ($cam as $value)
	{
		$q1 = $mysqli->query("SELECT `campaign` FROM `vericon`.`campaigns` WHERE `id` = '" . $mysqli->real_escape_string($value) . "'") or die($mysqli->error);
		$data = $q1->fetch_row();
		$q1->free();
		array_push($campaign, $data[0]);
	}
	$campaign = implode(", ", $campaign);
	
	echo "<tr>";
	echo "<td style='text-align:center'>" . $centre["id"] . "</td>";
	echo "<td style='text-align:center'>" . $campaign . "</td>";
	echo "<td style='text-align:center'>" . $centre["type"] . "</td>";
	echo "<td style='text-align:center'>" . $centre["leads"] . "</td>";
	echo "<td style='text-align:center'>" . $centre["status"] . "</td>";
	echo "<td style='text-align:center'><button onclick='Admin05_Edit_Centre(\"$centre[id]\")' class='icon_edit' title='Edit'></button></td>";
	if($centre["status"] == "Enabled")
	{
		echo "<td style='text-align:center;'><button onclick='Admin05_Toggle_Status(\"$centre[id]\",\"disable\")' class='icon_disable' title='Disable'></button></td>";
	}
	else
	{
		echo "<td style='text-align:center;'><button onclick='Admin05_Toggle_Status(\"$centre[id]\",\"enable\")' class='icon_enable' title='Enable'></button></td>";
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