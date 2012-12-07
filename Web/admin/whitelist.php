<?php
include("../auth/restrict.php");
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; background:none; }
div#users-contain table th { border: 1px solid rgba(41,171,226,0.25); padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid rgba(41,171,226,0.25); padding: .6em 5px; text-align: left; }
.ui-autocomplete { max-height: 150px; overflow-y: auto; overflow-x: hidden; }
.ui-autocomplete-category { font-weight: bold; padding: .2em .4em; margin: .8em 0 .2em; line-height: 1.5; }
</style>

<script>
function Admin04_More_IPs(page)
{
	var method = $( "#Admin04_method" ),
		query = $( "#Admin04_query" );
	
	V_Loading_Start();
	$( "#display_inner" ).load("/admin/whitelist_display.php", { m: method.val(), page: page, query: query.val() }, function(data, status, xhr){
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

function Admin04_Search(id)
{
	V_Loading_Start();
	$( "#display_inner" ).load("/admin/whitelist_display.php", { m: "search", query: id }, function(data, status, xhr){
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

function Admin04_Display_Reload()
{
	var method = $( "#Admin04_method" ),
		page = $( "#Admin04_page" ),
		query = $( "#Admin04_query" );
	
	V_Loading_Start();
	$( "#display_inner" ).load("/admin/whitelist_display.php", { m: method.val(), page: page.val(), query: query.val() }, function(data, status, xhr){
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

function Admin03_Add_IP()
{
	V_Loading_Start();
	$( "#display_inner" ).load("/admin/whitelist_new.php", { }, function(data, status, xhr){
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
			$( "#Admin04_search_bar" ).attr("style","display:none;");
			$( "#Admin04_search" ).attr("disabled","disabled");
			$( "#Admin04_add_ip" ).attr("disabled","disabled");
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
	$( "#Admin04_search" ).catcomplete({
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
			Admin04_Search(ui.item.id);
		}
	});
});

function Admin04_Toggle_Status(ip_start,ip_end,method)
{
	V_Loading_Start();
	$.post("/admin/whitelist_process.php", { m: method, ip_start: ip_start, ip_end: ip_end }, function(data) {
		Admin04_Display_Reload();
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
<td valign="middle" nowrap="nowrap" width="1px"><h1>IP Whitelist</h1></td>
<td><div class="dotted"></div></td>
</tr>
</table>
</div>

<div id="Admin04_search_bar" style="width:98%; margin:0 auto 10px;">
<table width="100%">
<tr>
<td><input type="search" id="Admin04_search" placeholder="Search..."></td>
<td align="right"><button onclick="Admin04_Add_IP()" id="Admin04_add_ip" class="btn">Add IP</button></td>
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
<th width="20%">IP</th>
<th width="35%">Description</th>
<th width="15%" style='text-align:center;'>Date Added</th>
<th width="15%" style='text-align:center;'>Added By</th>
<th width="10%" style='text-align:center;'>Status</th>
<th width="5%" style='text-align:center;'></th>
</tr>
</thead>
<tbody>
<?php
$check = mysql_query("SELECT * FROM `vericon`.`allowedip`") or die(mysql_error());
$rows = mysql_num_rows($check);

if($rows == 0)
{
	echo "<tr>";
	echo "<td colspan='6'>No IPs?!?!?!</td>";
	echo "</tr>";
}
else
{
	$q = mysql_query("SELECT INET_NTOA(`allowedip`.`ip_start`) AS ip_start, INET_NTOA(`allowedip`.`ip_end`) AS ip_end, `allowedip`.`description`, `allowedip`.`status`, `allowedip`.`timestamp`, CONCAT(`auth`.`first`, ' ', `auth`.`last`) AS name FROM `vericon`.`allowedip`, `vericon`.`auth` WHERE `allowedip`.`added_by` = `auth`.`user` ORDER BY INET_NTOA(`ip_start`) ASC LIMIT 0 , 13") or die(mysql_error());
	while($r = mysql_fetch_assoc($q))
	{
		if ($r["ip_start"] != $r["ip_end"]) {
			$ip = $r["ip_start"] . " - " . $r["ip_end"];
		} else {
			$ip = $r["ip_start"];
		}
		
		echo "<tr>";
		echo "<td>" . $ip . "</td>";
		echo "<td>" . $r["description"] . "</td>";
		echo "<td style='text-align:center;'>" . date("d/m/Y H:i:s", strtotime($r["timestamp"])) . "</td>";
		echo "<td style='text-align:center;'>" . $r["name"] . "</td>";
		echo "<td style='text-align:center;'>" . $r["status"] . "</td>";
		if($r["status"] == "Enabled") {
			echo "<td style='text-align:center;'><button onclick='Admin04_Toggle_Status(\"$r[ip_start]\",\"$r[ip_end]\",\"disable\")' class='icon_disable' title='Disable'></button></td>";
		} else {
			echo "<td style='text-align:center;'><button onclick='Admin04_Toggle_Status(\"$r[ip_start]\",\"$r[ip_end]\",\"enable\")' class='icon_enable' title='Enable'></button></td>";
		}
		echo "</tr>";
	}
}
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
	echo "<button onClick='Admin04_More_IPs(\"$page\")' class='next'></button>";
}
?>
</td>
</tr>
</table>
</td>
</tr>
</table></center>
<input type="hidden" id="Admin04_method" value="display" />
<input type="hidden" id="Admin04_page" value="0" />
<input type="hidden" id="Admin04_query" value="" />
</div>