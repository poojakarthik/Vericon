<?php
mysql_connect('localhost','vericon','18450be');

$centre = $_GET["centre"];
$date = $_GET["date"];
$week = date("W", strtotime($date));
$year = date("Y", strtotime($date));
$date1 = date("Y-m-d", strtotime($year . "W" . $week . "1"));
$date2 = date("Y-m-d", strtotime($year . "W" . $week . "7"));
?>
<input type="hidden" id="centre_store" value="<?php echo $centre; ?>">
<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/rejection_details_header.png" width="175" height="25" /></td>
<td align="right" style="padding-right:10px;"><b><?php echo $centre; ?></b></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<table width="100%">
<tr>
<td width="50%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="98%" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th style="text-align:center;" colspan="4">In-House</th>
</tr>
<tr class="ui-widget-header ">
<th style="text-align:center;">Sale ID</th>
<th style="text-align:center;">Campaign</th>
<th style="text-align:center;">Type</th>
<th style="text-align:center;"></th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT * FROM vericon.qa_customers WHERE status = 'Rejected' AND centre = '$centre' AND DATE(sale_timestamp) BETWEEN '$date1' AND '$date2' ORDER BY type,id ASC") or die(mysql_error());

if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td colspan='4' style='text-align:center;'>No In-House Rejections</td>";
	echo "</tr>";
}
else
{
	while ($data = mysql_fetch_assoc($q))
	{
		echo "<tr>";
		echo "<td style='text-align:center;'>" . $data["id"] . "</td>";
		echo "<td style='text-align:center;'>" . $data["campaign"] . "</td>";
		echo "<td style='text-align:center;'>" . $data["type"] . "</td>";
		echo "<td style='text-align:center;'><button onclick='Notes(\"$data[id]\")' class='icon_notes' title='Reason'></button></td>";
		echo "</tr>";
	}
}
?>
</tbody>
</table>
</div></center>
</td>
<td width="50%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="98%" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th style="text-align:center;" colspan="4">Rework</th>
</tr>
<tr class="ui-widget-header ">
<th style="text-align:center;">Sale ID</th>
<th style="text-align:center;">Campaign</th>
<th style="text-align:center;">Type</th>
<th style="text-align:center;"></th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT * FROM vericon.qa_customers WHERE status = 'Rework' AND centre = '$centre' AND DATE(sale_timestamp) BETWEEN '$date1' AND '$date2' ORDER BY id ASC") or die(mysql_error());

if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td colspan='4' style='text-align:center;'>No Reworks</td>";
	echo "</tr>";
}
else
{
	while ($data = mysql_fetch_assoc($q))
	{
		echo "<tr>";
		echo "<td style='text-align:center;'>" . $data["id"] . "</td>";
		echo "<td style='text-align:center;'>" . $data["campaign"] . "</td>";
		echo "<td style='text-align:center;'>" . $data["type"] . "</td>";
		echo "<td style='text-align:center;'><button onclick='Notes(\"$data[id]\")' class='icon_notes' title='Reason'></button></td>";
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