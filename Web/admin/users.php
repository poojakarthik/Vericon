<?php
include("../auth/restrict.php");
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; background:none; }
div#users-contain table th { border: 1px solid rgba(41,171,226,0.25); padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid rgba(41,171,226,0.25); padding: .6em 5px; text-align: left; }
div#users-contain table tbody tr:hover { background:rgba(255,255,255,0.25); }
.ui-autocomplete { max-height: 150px; overflow-y: auto; overflow-x: hidden; }
.ui-autocomplete-category { font-weight: bold; padding: .2em .4em; margin: .8em 0 .2em; line-height: 1.5; }
</style>

<script>
function Admin03_More_Users(page)
{
	var method = $( "#Admin03_method" ),
		query = $( "#Admin03_query" );
	
	V_Loading_Start();
	$( "#display_inner" ).load("/admin/users_display.php", { m: method.val(), page: page, query: query.val() }, function(data, status, xhr){
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

function Admin03_Search(category,id)
{
	V_Loading_Start();
	$( "#display_inner" ).load("/admin/users_display.php", { m: "search_" + category, query: id, page: 0 }, function(data, status, xhr){
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

function Admin03_Display_Reload()
{
	var method = $( "#Admin03_method" ),
		page = $( "#Admin03_page" ),
		query = $( "#Admin03_query" );
	
	if ($( ".blockUI" ).val() != "")
	{
		V_Loading_Start();
	}
	$( "#display_inner" ).load("/admin/users_display.php", { m: method.val(), page: page.val(), query: query.val() }, function(data, status, xhr){
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

function Admin03_Create_User()
{
	V_Loading_Start();
	$( "#display_inner" ).load("/admin/users_new.php", { }, function(data, status, xhr){
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
			$( "#Admin03_search_bar" ).attr("style","display:none;");
			$( "#Admin03_search" ).attr("disabled","disabled");
			$( "#Admin03_create_user" ).attr("disabled","disabled");
			$( "#Admin03_pending_users" ).attr("disabled","disabled");
			V_Loading_End();
		}
	});
}

$.widget( "custom.catcomplete", $.ui.autocomplete, {
	_renderMenu: function( ul, items ) {
		var that = this,
			currentCategory = "";
		$.each( items, function( index, item ) {
			if ( item.category != currentCategory ) {
				ul.append( "<li class='ui-autocomplete-category'>" + item.category + "</li>" );
				currentCategory = item.category;
			}
			that._renderItemData( ul, item );
		});
	}
});

$(function() {
	$( "#Admin03_search" ).catcomplete({
		source: function(request, response) {
			$.ajax({
				url: "/admin/users_search.php",
				dataType: "json",
				type: "POST",
				data: {
					term: request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 2,
		select: function (event, ui) {
			Admin03_Search(ui.item.category, ui.item.id);
		}
	});
});

function Admin03_Edit_User(user)
{
	var method = $( "#Admin03_method" ),
		page = $( "#Admin03_page" ),
		query = $( "#Admin03_query" );
	
	V_Loading_Start();
	$( "#display_inner" ).load("/admin/users_edit.php", { user: user, method: method.val(), page: page.val(), query: query.val() }, function(data, status, xhr){
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
			$( "#Admin03_search_bar" ).attr("style","display:none;");
			$( "#Admin03_search" ).attr("disabled","disabled");
			$( "#Admin03_create_user" ).attr("disabled","disabled");
			$( "#Admin03_pending_users" ).attr("disabled","disabled");
			V_Loading_End();
		}
	});
}

function Admin03_Toggle_Status(user,method)
{
	V_Loading_Start();
	$.post("/admin/users_process.php", { m: method, user: user }, function(data) {
		Admin03_Display_Reload();
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
<td valign="middle" nowrap="nowrap" width="1px"><h1>VeriCon Users</h1></td>
<td><div class="dotted"></div></td>
</tr>
</table>
</div>

<div id="Admin03_search_bar" style="width:98%; margin:0 auto 10px;">
<table width="100%">
<tr>
<td><input type="text" id="Admin03_search" style="padding:5px 10px 5px 25px; background:url('/images/search_icon.png') no-repeat scroll 5px center #FFFFFF;" placeholder="Search..."></td>
<td align="right"><button onclick="Admin03_Create_User()" id="Admin03_create_user" class="btn">Create User</button>
<?php
$q = $mysqli->query("SELECT `first` FROM `vericon`.`auth_temp`") or die($mysqli->error);
if ($q->num_rows == 0)
{
	echo "<button disabled='disabled' class='btn' style='margin-left:10px;'>Pending Users</button>";
}
else
{
	echo "<button onclick='Admin03_Pending_Users()' id='Admin03_pending_users' class='btn' style='margin-left:10px;'>Pending Users</button>";
}
$q->free();
?></td>
</tr>
</table>
</div>

<div id="display_inner">
<center><table width="98%" height="500px">
<tr valign="top" height="95%">
<td>
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th width="10%">Username</th>
<th width="18%">Full Name</th>
<th width="18%" style='text-align:center;'>Department</th>
<th width="8%" style='text-align:center;'>Centre</th>
<th width="13%" style='text-align:center;'>Joining Date</th>
<th width="13%" style='text-align:center;'>Last Login</th>
<th width="10%" style='text-align:center;'>Status</th>
<th width="10%" style='text-align:center;' colspan="2">Edit User</th>
</tr>
</thead>
<tbody>
<?php
$check = $mysqli->query("SELECT * FROM `vericon`.`auth`") or die($mysqli->error);
$rows = $check->num_rows;
$check->free();

if($rows == 0)
{
	$st = 0;
	echo "<tr>";
	echo "<td colspan='9'>No Users?!?!?!</td>";
	echo "</tr>";
}
else
{
	$st = 0;
	$q = $mysqli->query("SELECT * FROM `vericon`.`auth` ORDER BY `user` ASC LIMIT 0 , 13") or die($mysqli->error);
	while($r = $q->fetch_assoc())
	{
		$q1 = $mysqli->query("SELECT MAX(`timestamp`) FROM `logs`.`login` WHERE `user` = '" . $mysqli->real_escape_string($r["user"]) . "'") or die($mysqli->error);
		$l = $q1->fetch_row();
		$q1->free();
		if ($l[0] == null) {
			$last_login = "Never";
		} else {
			$last_login = date("d/m/Y H:i:s", strtotime($l[0]));
		}
		echo "<tr>";
		echo "<td>" . $r["user"] . "</td>";
		echo "<td>" . $r["first"] . " " . $r["last"] . "</td>";
		echo "<td style='text-align:center;'>" . $r["type"] . "</td>";
		echo "<td style='text-align:center;'>" . $r["centre"] . "</td>";
		echo "<td style='text-align:center;'>" . date("d/m/Y H:i:s", strtotime($r["timestamp"])) . "</td>";
		echo "<td style='text-align:center;'>" . $last_login . "</td>";
		echo "<td style='text-align:center;'>" . $r["status"] . "</td>";
		echo "<td style='text-align:center;'><button onclick='Admin03_Edit_User(\"$r[user]\")' class='icon_edit' title='Edit'></button></td>";
		if($r["status"] == "Enabled") {
			echo "<td style='text-align:center;'><button onclick='Admin03_Toggle_Status(\"$r[user]\",\"disable\")' class='icon_disable' title='Disable'></button></td>";
		} else {
			echo "<td style='text-align:center;'><button onclick='Admin03_Toggle_Status(\"$r[user]\",\"enable\")' class='icon_enable' title='Enable'></button></td>";
		}
		echo "</tr>";
	}
	$q->free();
}
$mysqli->close();
?>
</tbody>
</table>
</div>
</td>
</tr>
<tr valign="bottom">
<td>
<table width="100%">
<tr>
<td align="left" width="40%"></td>
<td align="center" width="20%">
<?php
$p_t = ceil($rows / 13);
echo "1 of " . $p_t;
?>
</td>
<td align="right" width="40%">
<?php
if (($st + 13) < $rows)
{
	$page = 1;
	echo "<button onClick='Admin03_More_Users(\"$page\")' class='next'></button>";
}
?>
</td>
</tr>
</table>
</td>
</tr>
</table></center>
<input type="hidden" id="Admin03_method" value="display" />
<input type="hidden" id="Admin03_page" value="0" />
<input type="hidden" id="Admin03_query" value="" />
</div>