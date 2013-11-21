<?php
mysql_connect('localhost','vericon','18450be');

$id = $_GET["campaign"];
$q = mysql_query("SELECT campaign FROM vericon.campaigns WHERE id = '" . mysql_real_escape_string($id) . "'") or die(mysql_error());
$campaign_name = mysql_fetch_row($q);
?>

<script>
$(function() {
	$( "#accordion" ).accordion({
		autoHeight: false
	});
});
</script>

<script>
function Done()
{
	var group = $( "#group_store" );
	
	$( "#display" ).hide('blind', '', 'slow', function() {
		$( "#display" ).load("clients_display.php", function() {
			$( "#display2" ).load("clients_display2.php?group=" + group.val(), function() {
				$( "#display" ).show('blind', '', 'slow');
			});
		});
	});
}
</script>

<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/plan_matrix_header.png" width="125" height="25"></td>
<td align="right" style="padding-right:10px;"><b><?php echo $campaign_name[0]; ?></b></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9"></td>
</tr>
</table>

<center><div id="accordion" style="width:98%;">
<h3 style="font-weight:bold;"><a href="">PSTN</a></h3>
<div id="users-contain" class="ui-widget" style="padding:0px;">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th style="text-align:center;">ID</th>
<th style="text-align:center;">Rating</th>
<th style="text-align:center;">Name</th>
<th style="text-align:center;">Start Date</th>
<th style="text-align:center;">End Date</th>
<th style="text-align:center;">Status</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT * FROM vericon.plan_matrix WHERE type = 'PSTN' AND campaign = '" . mysql_real_escape_string($id) . "' ORDER BY id ASC") or die(mysql_error());
if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td style='text-align:center;' colspan='8'>No Plans!</td>";
	echo "</tr>";
}
else
{
	while ($data = mysql_fetch_assoc($q))
	{
		$start = date("d/m/Y", strtotime($data["start"]));
		if ($data["end"] == "0000-00-00") {$end = "Current"; } else {$end = date("d/m/Y", strtotime($data["end"])); }
		
		echo "<tr>";
		echo "<td style='text-align:center;'>" . $data["id"] . "</td>";
		echo "<td style='text-align:center;'>" . $data["rating"] . "</td>";
		echo "<td style='text-align:center;'>" . $data["name"] . "</td>";
		echo "<td style='text-align:center;'>" . $start . "</td>";
		echo "<td style='text-align:center;'>" . $end . "</td>";
		echo "<td style='text-align:center;'>" . $data["status"] . "</td>";
		
	}
}
?>
</tbody>
</table>
</div>

<h3 style="font-weight:bold;"><a href="">ADSL Metro</a></h3>
<div id="users-contain" class="ui-widget" style="padding:0px;">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th style="text-align:center;">ID</th>
<th style="text-align:center;">Rating</th>
<th style="text-align:center;">Name</th>
<th style="text-align:center;">Start Date</th>
<th style="text-align:center;">End Date</th>
<th style="text-align:center;">Status</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT * FROM vericon.plan_matrix WHERE type = 'ADSL Metro' AND campaign = '" . mysql_real_escape_string($id) . "' ORDER BY id ASC") or die(mysql_error());
if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td style='text-align:center;' colspan='8'>No Plans!</td>";
	echo "</tr>";
}
else
{
	while ($data = mysql_fetch_assoc($q))
	{
		$start = date("d/m/Y", strtotime($data["start"]));
		if ($data["end"] == "0000-00-00") {$end = "Current"; } else {$end = date("d/m/Y", strtotime($data["end"])); }
		
		echo "<tr>";
		echo "<td style='text-align:center;'>" . $data["id"] . "</td>";
		echo "<td style='text-align:center;'>" . $data["rating"] . "</td>";
		echo "<td style='text-align:center;'>" . $data["name"] . "</td>";
		echo "<td style='text-align:center;'>" . $start . "</td>";
		echo "<td style='text-align:center;'>" . $end . "</td>";
		echo "<td style='text-align:center;'>" . $data["status"] . "</td>";
		
	}
}
?>
</tbody>
</table>
</div>

<h3 style="font-weight:bold;"><a href="">ADSL Regional</a></h3>
<div id="users-contain" class="ui-widget" style="padding:0px;">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th style="text-align:center;">ID</th>
<th style="text-align:center;">Rating</th>
<th style="text-align:center;">Name</th>
<th style="text-align:center;">Start Date</th>
<th style="text-align:center;">End Date</th>
<th style="text-align:center;">Status</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT * FROM vericon.plan_matrix WHERE type = 'ADSL Regional' AND campaign = '" . mysql_real_escape_string($id) . "' ORDER BY id ASC") or die(mysql_error());
if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td style='text-align:center;' colspan='8'>No Plans!</td>";
	echo "</tr>";
}
else
{
	while ($data = mysql_fetch_assoc($q))
	{
		$start = date("d/m/Y", strtotime($data["start"]));
		if ($data["end"] == "0000-00-00") {$end = "Current"; } else {$end = date("d/m/Y", strtotime($data["end"])); }
		
		echo "<tr>";
		echo "<td style='text-align:center;'>" . $data["id"] . "</td>";
		echo "<td style='text-align:center;'>" . $data["rating"] . "</td>";
		echo "<td style='text-align:center;'>" . $data["name"] . "</td>";
		echo "<td style='text-align:center;'>" . $start . "</td>";
		echo "<td style='text-align:center;'>" . $end . "</td>";
		echo "<td style='text-align:center;'>" . $data["status"] . "</td>";
		
	}
}
?>
</tbody>
</table>
</div>

<h3 style="font-weight:bold;"><a href="">Bundle</a></h3>
<div id="users-contain" class="ui-widget" style="padding:0px;">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th style="text-align:center;">ID</th>
<th style="text-align:center;">Rating</th>
<th style="text-align:center;">Name</th>
<th style="text-align:center;">Start Date</th>
<th style="text-align:center;">End Date</th>
<th style="text-align:center;">Status</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT * FROM vericon.plan_matrix WHERE type = 'Bundle' AND campaign = '" . mysql_real_escape_string($id) . "' ORDER BY id ASC") or die(mysql_error());
if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td style='text-align:center;' colspan='8'>No Plans!</td>";
	echo "</tr>";
}
else
{
	while ($data = mysql_fetch_assoc($q))
	{
		$start = date("d/m/Y", strtotime($data["start"]));
		if ($data["end"] == "0000-00-00") {$end = "Current"; } else {$end = date("d/m/Y", strtotime($data["end"])); }
		
		echo "<tr>";
		echo "<td style='text-align:center;'>" . $data["id"] . "</td>";
		echo "<td style='text-align:center;'>" . $data["rating"] . "</td>";
		echo "<td style='text-align:center;'>" . $data["name"] . "</td>";
		echo "<td style='text-align:center;'>" . $start . "</td>";
		echo "<td style='text-align:center;'>" . $end . "</td>";
		echo "<td style='text-align:center;'>" . $data["status"] . "</td>";
		
	}
}
?>
</tbody>
</table>
</div>
</div></center>
<br>
<table width="100%">
<tr>
<td align="left" style="padding-left:10px;"><button onClick="Done()" class="btn">Back</button></td>
</tr>
</table>