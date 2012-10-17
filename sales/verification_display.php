<?php
mysql_connect('localhost','vericon','18450be');

$q = mysql_query("SELECT user FROM vericon.currentuser WHERE hash = '" . $_COOKIE["hash"] . "'") or die(mysql_error());
$user = mysql_fetch_row($q);

$q1 = mysql_query("SELECT * FROM vericon.auth WHERE user = '$user[0]'") or die(mysql_error());
$ac = mysql_fetch_assoc($q1);
?>
<style>
.ui-dialog { padding: .3em; }
.ui-state-highlight { padding: .3em; }
.validateTips { border: 1px solid transparent; padding: 0.3em; }
div#users-contain table { margin: 1em 0; border-collapse: collapse; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
</style>
<script> //init form
function Get_Sale()
{
	var id = $( "#id" ),
		centre = "<?php echo $ac["centre"]; ?>";
		
	$( ".error" ).html('<img src="../images/ajax-loader.gif" />');
	
	$.get("verification_submit.php?method=get", { id: id.val(), centre: centre }, function(data) {
		if (data == "valid")
		{
			$( ".id2" ).html(id.val());
			$( "#dialog-form" ).dialog( "open" );
			$( ".error" ).html('');
		}
		else
		{
			$( ".error" ).html(data);
		}
	});
}
</script>
<script> //load form
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var tips = $( ".validateTips" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#dialog-form" ).dialog({
		autoOpen: false,
		height: 225,
		width: 300,
		modal: true,
		resizable: false,
		draggable: false,
		show: "blind",
		hide: "blind",
		buttons: {
			"Load Form": function() {
				var id = $( "#id" ),
					agent = "<?php echo $ac["user"]; ?>",
					centre = "<?php echo $ac["centre"]; ?>",
					campaign = $( "#campaign" ),
					type = $( "#type" );

				$.get("verification_submit.php?method=load", { id: id.val(), agent: agent, centre: centre, campaign: campaign.val(), type: type.val() },
				function(data) {
					d = data.split("_");
					if (d[0] == "valid")
					{
						id = d[2];
						$( "#dialog-form" ).dialog( "close" );
						$( "#display" ).hide('blind', '' , 'slow', function() {
							$( "#display" ).load('verification_dash_' + d[1] + '.php?id=' + id, function() {
								$( "#packages" ).load('../tpv/packages_' + d[1] + '.php?id=' + id, function() {
									$( "#display" ).show('blind', '' , 'slow');
								});
							});
						});
					}
					else
					{
						updateTips(data);
					}
				});
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
</script>

<div id="dialog-form" title="Sale Details">
<p class="validateTips">Please select a campaign and sale type</p><br />
<table>
<tr>
<td width="60px">Lead ID </td>
<td><b><p class="id2"></p></b></td>
</tr>
<tr>
<td>Agent </td>
<td><b><?php echo $ac["user"] . " (" . $ac["alias"] . ")"; ?></b></td>
</tr>
<tr>
<td>Centre </td>
<td><b><?php echo $ac["centre"]; ?></b></td>
</tr>
<tr>
<td>Campaign </td>
<td>
<?php
$q = mysql_query("SELECT campaign FROM vericon.centres WHERE centre = '$ac[centre]'") or die(mysql_error());
$cam = mysql_fetch_row($q);
$campaign = explode(",",$cam[0]);
if (count($campaign) > 1)
{
?>
<select id="campaign" style="width:120px; height:auto; padding:0px; margin:0px;">
<option></option>
<?php
for ($i = 0; $i < count($campaign); $i++)
{
	echo "<option>" . $campaign[$i] . "</option>";
}
?>
</select>
<?php
}
else
{
?>
<input type="hidden" id="campaign" value="<?php echo $campaign[0]; ?>" />
<b><?php echo $campaign[0]; ?></b>
<?php
}
?>
</td>
</tr>
<tr>
<td>Type </td>
<td><select id="type" style="width:120px; height:auto; padding:0px; margin:0;">
<option></option>
<option>Business</option>
<option>Residential</option>
</select></td>
</tr>
</table>
</div>

<div id="get_sale_table" style="margin-top:15px; margin-bottom:15px;">
<form onsubmit="event.preventDefault()">
<table>
<tr>
<td><p>Enter the Customer's Lead ID</p></td>
<td><input type="text" id="id" size="25" autocomplete="off" /></td>
<td><button type="submit" class="get_sale_btn" onclick="Get_Sale()"></button></td>
</tr>
</table>
</form>
<center><p class="error" style="color:#C00;"></p></center>
</div>
<br />
<p><img src="../images/submitted_sales_header.png" width="165" height="25" style="margin-left:3px;" /></p>
<p><img src="../images/line.png" width="715" height="9" /></p>
<div id="users-contain" class="ui-widget" style="width:100%; margin-left:5px; margin-top:-5px;">
<table id="users" class="ui-widget ui-widget-content" width="95%">
<thead>
<tr class="ui-widget-header ">
<th>Sale ID</th>
<th>Lead ID</th>
<th>Status</th>
<th>Date/Time</th>
</tr>
</thead>
<tbody>
<?php
$weekago = date("Y-m-d", strtotime("-1 week"));
$q = mysql_query("SELECT id,status,timestamp,lead_id FROM vericon.sales_customers WHERE agent = '$ac[user]' AND DATE(timestamp) >= '$weekago' ORDER BY timestamp DESC") or die(mysql_error());
if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td colspan='4'><center>No Sales Submitted!</center></td>";
	echo "</tr>";
}
else
{
	while ($sales_data = mysql_fetch_assoc($q))
	{
		echo "<tr>";
		echo "<td>" . $sales_data["id"] . "</td>";
		echo "<td>" . $sales_data["lead_id"] . "</td>";
		echo "<td>" . $sales_data["status"] . "</td>";
		echo "<td>" . date("d/m/Y H:i", strtotime($sales_data["timestamp"])) . "</td>";
		echo "</tr>";
	}
}
?>
</tbody>
</table>
</div>