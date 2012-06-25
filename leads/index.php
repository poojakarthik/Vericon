<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .3em 10px; text-align: center; }

.details {
	background-image:url('../images/details_icon.png');
	background-repeat:no-repeat;
	height:16px;
	width:16px;
	border:none;
	background-color:transparent;
	cursor:pointer;
}

.export {
	background-image:url('../images/export_excel_icon.png');
	background-repeat:no-repeat;
	height:16px;
	width:16px;
	border:none;
	background-color:transparent;
	cursor:pointer;
}

.search
{
	background-image:url('../images/search_btn_2.png');
	background-repeat:no-repeat;
	height:25px;
	width:85px;
	border:none;
	background-color:transparent;
	float:right;
	margin-right:10px;
}

.search:hover
{
	background-image:url('../images/search_btn_hover_2.png');
	cursor:pointer;
}
</style>
<script>
function Details(centre)
{
	
}
</script>
<script>
function Export(centre)
{
	//window.open("export.php?centre=" + centre);
}
</script>
<script> //Leads Search
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:275,
		height:200,
		modal: true,
		buttons: {
			"Search": function() {
				var lead = $( "#lead" );
				
				$( ".result" ).html('<table width="100%"><tr height="60px"><td valign="middle" align="center"><img src="../images/ajax-loader.gif" /></td></tr></table>');
				
				$.get("check.php", { lead: lead.val() },
				function(data) {
					$( ".result" ).html(data);
				});
			},
			
			"Close": function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

function Search()
{
	$( ".result" ).html("");
	$( "#dialog-confirm" ).dialog( "open" );
}
</script>

<div id="dialog-confirm" title="Lead Search">
	<p>Lead ID: &nbsp;&nbsp;<input type="text" size="25" id="lead" /></p>
    <p class="result" style="margin-top:10px;"></p>
</div>

<p><img src="../images/leads_dashboard_header.png" width="175" height="25" style="margin-left:3px;" /><input type="button" onclick="Search()" class="search" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p>

<center><div id="users-contain" class="ui-widget" style="width:99%;">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th>Centre</th>
<th>Total Running Data</th>
<th>Updated Till</th>
<th>Last Upload</th>
<th colspan="2"></th>
</tr>
</thead>
<tbody>
<?php
$total_num = 0;
//centres
$q = mysql_query("SELECT centre FROM centres WHERE status = 'Active' ORDER BY centre ASC") or die(mysql_error());
while ($centre = mysql_fetch_row($q))
{
	$q0 = mysql_query("SELECT COUNT(cli) FROM leads WHERE centre = '$centre[0]' AND expiry_date >= '" . date("Y-m-d") . "'") or die(mysql_error());
	$num = mysql_fetch_row($q0);
	$total_num += $num[0];

	$q1 = mysql_query("SELECT expiry_date FROM leads WHERE centre = '$centre[0]' AND expiry_date >= '" . date("Y-m-d") . "' ORDER BY expiry_date DESC LIMIT 1") or die(mysql_error());
	$c_exp = mysql_fetch_row($q1);

	$q2 = mysql_query("SELECT timestamp FROM leads WHERE centre = '$centre[0]' AND expiry_date >= '" . date("Y-m-d") . "' ORDER BY timestamp DESC LIMIT 1") or die(mysql_error());
	$c_last = mysql_fetch_row($q2);

	echo "<tr>";
	echo "<td>" . $centre[0] . "</td>";
	echo "<td>" . number_format($num[0]) . "</td>";
	echo "<td>" . date("d/m/Y", strtotime($c_exp[0])) . "</td>";
	echo "<td>" . date("d/m/Y H:i:s", strtotime($c_last[0])) . "</td>";
	echo "<td><input type='button' onclick='Details(\"$centre[0]\")' class='details' title='View Details'></td>";
	echo "<td><input type='button' onclick='Export(\"$centre[0]\")' class='export' title='Export Leads'></td>";
	echo "</tr>";
}

//total
echo "<tr>";
echo "<td><b>Total</b></td>";
echo "<td><b>" . number_format($total_num) . "</b></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td colspan='2'><input type='button' onclick='Export(\"All\")' class='export' title='Export Leads'></td>";
echo "</tr>";
?>
</tbody>
<thead>
<tr class="ui-widget-header ">
<th>Name</th>
<th>Total Running Data</th>
<th>Updated Till</th>
<th>Last Upload</th>
<th colspan="2"></th>
</tr>
</thead>
<tbody>
<?php
$total_num = 0;
//kamal
$q0 = mysql_query("SELECT COUNT(cli) FROM leads WHERE centre = 'Kamal' AND expiry_date >= '" . date("Y-m-d") . "'") or die(mysql_error());
$num = mysql_fetch_row($q0);
$total_num += $num[0];

$q1 = mysql_query("SELECT expiry_date FROM leads WHERE centre = 'Kamal' AND expiry_date >= '" . date("Y-m-d") . "' ORDER BY expiry_date DESC LIMIT 1") or die(mysql_error());
$k_exp = mysql_fetch_row($q1);

$q2 = mysql_query("SELECT timestamp FROM leads WHERE centre = 'Kamal' AND expiry_date >= '" . date("Y-m-d") . "' ORDER BY timestamp DESC LIMIT 1") or die(mysql_error());
$k_last = mysql_fetch_row($q2);

echo "<tr>";
echo "<td>Kamal</td>";
echo "<td>" . number_format($num[0]) . "</td>";
echo "<td>" . date("d/m/Y", strtotime($k_exp[0])) . "</td>";
echo "<td>" . date("d/m/Y H:i:s", strtotime($k_last[0])) . "</td>";
echo "<td><input type='button' onclick='Details(\"Kamal\")' class='details' title='View Details'></td>";
echo "<td><input type='button' onclick='Export(\"Kamal\")' class='export' title='Export Leads'></td>";
echo "</tr>";

//rohan
$q0 = mysql_query("SELECT COUNT(cli) FROM leads WHERE centre = 'Rohan' AND expiry_date >= '" . date("Y-m-d") . "'") or die(mysql_error());
$num = mysql_fetch_row($q0);
$total_num += $num[0];

$q1 = mysql_query("SELECT expiry_date FROM leads WHERE centre = 'Rohan' AND expiry_date >= '" . date("Y-m-d") . "' ORDER BY expiry_date DESC LIMIT 1") or die(mysql_error());
$k_exp = mysql_fetch_row($q1);

$q2 = mysql_query("SELECT timestamp FROM leads WHERE centre = 'Rohan' AND expiry_date >= '" . date("Y-m-d") . "' ORDER BY timestamp DESC LIMIT 1") or die(mysql_error());
$k_last = mysql_fetch_row($q2);

echo "<tr>";
echo "<td>Rohan</td>";
echo "<td>" . number_format($num[0]) . "</td>";
echo "<td>" . date("d/m/Y", strtotime($k_exp[0])) . "</td>";
echo "<td>" . date("d/m/Y H:i:s", strtotime($k_last[0])) . "</td>";
echo "<td><input type='button' onclick='Details(\"Rohan\")' class='details' title='View Details'></td>";
echo "<td><input type='button' onclick='Export(\"Rohan\")' class='export' title='Export Leads'></td>";
echo "</tr>";

//sanjay
$q0 = mysql_query("SELECT COUNT(cli) FROM leads WHERE centre = 'Sanjay' AND expiry_date >= '" . date("Y-m-d") . "'") or die(mysql_error());
$num = mysql_fetch_row($q0);
$total_num += $num[0];

$q1 = mysql_query("SELECT expiry_date FROM leads WHERE centre = 'Sanjay' AND expiry_date >= '" . date("Y-m-d") . "' ORDER BY expiry_date DESC LIMIT 1") or die(mysql_error());
$k_exp = mysql_fetch_row($q1);

$q2 = mysql_query("SELECT timestamp FROM leads WHERE centre = 'Sanjay' AND expiry_date >= '" . date("Y-m-d") . "' ORDER BY timestamp DESC LIMIT 1") or die(mysql_error());
$k_last = mysql_fetch_row($q2);

echo "<tr>";
echo "<td>Sanjay</td>";
echo "<td>" . number_format($num[0]) . "</td>";
echo "<td>" . date("d/m/Y", strtotime($k_exp[0])) . "</td>";
echo "<td>" . date("d/m/Y H:i:s", strtotime($k_last[0])) . "</td>";
echo "<td><input type='button' onclick='Details(\"Sanjay\")' class='details' title='View Details'></td>";
echo "<td><input type='button' onclick='Export(\"Sanjay\")' class='export' title='Export Leads'></td>";
echo "</tr>";

//total
echo "<tr>";
echo "<td><b>Total</b></td>";
echo "<td><b>" . number_format($total_num) . "</b></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td colspan='2'><input type='button' onclick='Export(\"Special\")' class='export' title='Export Leads'></td>";
echo "</tr>";
?>
</tbody>
</table>
</div></center>

<?php
include "../source/footer.php";
?>