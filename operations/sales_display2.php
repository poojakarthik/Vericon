<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];

if ($method == "sales")
{
	$centre = $_GET["centre"];
	$date = $_GET["date"];
?>
<table width="100%" style="margin-top:10px;">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/sale_details_header2.png" width="125" height="25"></td>
<td align="right" style="padding-right:10px;"><b><?php echo $centre; ?></b></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<table width="100%">
<tr>
<td valign="top" width="50%">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="98%" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th style="text-align:center;" colspan="4">Business</th>
</tr>
<tr class="ui-widget-header ">
<th style="text-align:center;">Sale ID</th>
<th style="text-align:center;">Status</th>
<th style="text-align:center;">Agent</th>
<th></th>
</tr>
</thead>
<tbody>
<?php	
$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE status != 'Queue' AND type = 'Business' AND centre = '$centre' AND DATE(approved_timestamp) = '$date' ORDER BY approved_timestamp ASC") or die(mysql_error());

if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td style='text-align:center;' colspan='4'>No Business Sales Made by $centre</td>";
	echo "</tr>";
}
else
{
	while ($data = mysql_fetch_assoc($q))
	{
		$q1 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$data[agent]'") or die(mysql_error());
		$agent = mysql_fetch_row($q1);
		
		echo "<tr>";
		echo "<td style='text-align:center;'>" . $data["id"] . "</td>";
		echo "<td style='text-align:center;'>" . $data["status"] . "</td>";
		echo "<td style='text-align:center;'>" . $agent[0] . " " . $agent[1] . "</td>";
		echo "<td style='text-align:center;'><button onclick='Notes(\"$data[id]\")' class='icon_notes' title='Notes'></button></td>";
		echo "</tr>";
	}
}
?>
</tbody>
</table>
</div></center>
</td>
<td valign="top" width="50%">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="98%" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th style="text-align:center;" colspan="4">Residential</th>
</tr>
<tr class="ui-widget-header ">
<th style="text-align:center;">Sale ID</th>
<th style="text-align:center;">Status</th>
<th style="text-align:center;">Agent</th>
<th></th>
</tr>
</thead>
<tbody>
<?php	
$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE status != 'Queue' AND type = 'Residential' AND centre = '$centre' AND DATE(approved_timestamp) = '$date' ORDER BY approved_timestamp ASC") or die(mysql_error());

if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td style='text-align:center;' colspan='4'>No Residential Sales Made by $centre</td>";
	echo "</tr>";
}
else
{
	while ($data = mysql_fetch_assoc($q))
	{
		$q1 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$data[agent]'") or die(mysql_error());
		$agent = mysql_fetch_row($q1);
		
		echo "<tr>";
		echo "<td style='text-align:center;'>" . $data["id"] . "</td>";
		echo "<td style='text-align:center;'>" . $data["status"] . "</td>";
		echo "<td style='text-align:center;'>" . $agent[0] . " " . $agent[1] . "</td>";
		echo "<td style='text-align:center;'><button onclick='Notes(\"$data[id]\")' class='icon_notes' title='Notes'></button></td>";
		echo "</tr>";
	}
}
?>
</tbody>
</table>
</div></center>
</td>
</tr>
</table>
<?php
}
elseif ($method == "notes")
{
	$id = $_GET["id"];
	
	$q = mysql_query("SELECT * FROM vericon.tpv_notes WHERE id = '$id' ORDER BY timestamp DESC") or die (mysql_error());
	while ($tpv_notes = mysql_fetch_assoc($q))
	{
		$q1 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$tpv_notes[verifier]'") or die(mysql_error());
		$vname = mysql_fetch_row($q1);
		
		echo "----- " . date("d/m/Y H:i:s", strtotime($tpv_notes["timestamp"])) . " - " . $vname[0] . " " . $vname[1] . " -----" . " (" . $tpv_notes["status"] . ")\n";
		echo $tpv_notes["note"] . "\n";
	}
}
?>