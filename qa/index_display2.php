<?php
mysql_connect('localhost','vericon','18450be');

$date1 = $_GET["date1"];
$date2 = $_GET["date2"];
?>
<center><table width="100%">
<tr>
<td width="33%" align="center" valign="top">
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content">
<thead>
<tr class="ui-widget-header ">
<th colspan="4" style='text-align:center;'>Captive</th>
</tr>
<tr class="ui-widget-header ">
<th>Centre</th>
<th style='text-align:center;'>Bus</th>
<th style='text-align:center;'>Resi</th>
<th style='text-align:center;'>Total</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT centre FROM vericon.centres WHERE type = 'Captive' AND status = 'Active' ORDER BY centre ASC") or die(mysql_error());
$total_bus = 0;
$total_resi = 0;
while ($centres = mysql_fetch_row($q))
{
	$q1 = mysql_query("SELECT * FROM vericon.sales_customers WHERE status = 'Approved' AND centre = '$centres[0]' AND type = 'Business' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
	$business = mysql_num_rows($q1);
	$total_bus += $business;

	$q2 = mysql_query("SELECT * FROM vericon.sales_customers WHERE status = 'Approved' AND centre = '$centres[0]' AND type = 'Residential' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
	$residential = mysql_num_rows($q2);
	$total_resi += $residential;
	
	$total = $business + $residential;
	
	echo "<tr>";
	echo "<td>" . $centres[0] . "</td>";
	echo "<td style='text-align:center;'>" . $business . "</td>";
	echo "<td style='text-align:center;'>" . $residential . "</td>";
	echo "<td style='text-align:center;'><b>" . $total . "</b></td>";
	echo "</tr>";
}

$total = $total_bus + $total_resi;
echo "<tr>";
echo "<td><b>Total</b></td>";
echo "<td style='text-align:center;'><b>" . $total_bus . "</b></td>";
echo "<td style='text-align:center;'><b>" . $total_resi . "</b></td>";
echo "<td style='text-align:center;'><b>" . $total . "</b></td>";
echo "</tr>";
?>
</tbody>
</table>
</div>
</td>
<td width="33%" align="center" valign="top">
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content">
<thead>
<tr class="ui-widget-header ">
<th colspan="4" style='text-align:center;'>Outsource</th>
</tr>
<tr class="ui-widget-header ">
<th>Centre</th>
<th style='text-align:center;'>Bus</th>
<th style='text-align:center;'>Resi</th>
<th style='text-align:center;'>Total</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT centre FROM vericon.centres WHERE type = 'Outsourced' AND status = 'Active' ORDER BY centre ASC") or die(mysql_error());
$total_bus = 0;
$total_resi = 0;
while ($centres = mysql_fetch_row($q))
{
	$q1 = mysql_query("SELECT * FROM vericon.sales_customers WHERE status = 'Approved' AND centre = '$centres[0]' AND type = 'Business' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
	$business = mysql_num_rows($q1);
	$total_bus += $business;

	$q2 = mysql_query("SELECT * FROM vericon.sales_customers WHERE status = 'Approved' AND centre = '$centres[0]' AND type = 'Residential' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
	$residential = mysql_num_rows($q2);
	$total_resi += $residential;
	
	$total = $business + $residential;
	
	echo "<tr>";
	echo "<td>" . $centres[0] . "</td>";
	echo "<td style='text-align:center;'>" . $business . "</td>";
	echo "<td style='text-align:center;'>" . $residential . "</td>";
	echo "<td style='text-align:center;'><b>" . $total . "</b></td>";
	echo "</tr>";
}

$total = $total_bus + $total_resi;
echo "<tr>";
echo "<td><b>Total</b></td>";
echo "<td style='text-align:center;'><b>" . $total_bus . "</b></td>";
echo "<td style='text-align:center;'><b>" . $total_resi . "</b></td>";
echo "<td style='text-align:center;'><b>" . $total . "</b></td>";
echo "</tr>";
?>
</tbody>
</table>
</div>
</td>
<td width="33%" align="center" valign="top">
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content">
<thead>
<tr class="ui-widget-header ">
<th colspan="4" style='text-align:center;'>Melbourne</th>
</tr>
<tr class="ui-widget-header ">
<th>Centre</th>
<th style='text-align:center;'>Bus</th>
<th style='text-align:center;'>Resi</th>
<th style='text-align:center;'>Total</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT centre FROM vericon.centres WHERE type = 'Self' AND status = 'Active' ORDER BY centre ASC") or die(mysql_error());
$total_bus = 0;
$total_resi = 0;
while ($centres = mysql_fetch_row($q))
{
	$q1 = mysql_query("SELECT * FROM vericon.sales_customers WHERE status = 'Approved' AND centre = '$centres[0]' AND type = 'Business' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
	$business = mysql_num_rows($q1);
	$total_bus += $business;

	$q2 = mysql_query("SELECT * FROM vericon.sales_customers WHERE status = 'Approved' AND centre = '$centres[0]' AND type = 'Residential' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
	$residential = mysql_num_rows($q2);
	$total_resi += $residential;
	
	$total = $business + $residential;
	
	echo "<tr>";
	echo "<td>" . $centres[0] . "</td>";
	echo "<td style='text-align:center;'>" . $business . "</td>";
	echo "<td style='text-align:center;'>" . $residential . "</td>";
	echo "<td style='text-align:center;'><b>" . $total . "</b></td>";
	echo "</tr>";
}

$total = $total_bus + $total_resi;
echo "<tr>";
echo "<td><b>Total</b></td>";
echo "<td style='text-align:center;'><b>" . $total_bus . "</b></td>";
echo "<td style='text-align:center;'><b>" . $total_resi . "</b></td>";
echo "<td style='text-align:center;'><b>" . $total . "</b></td>";
echo "</tr>";
?>
</tbody>
</table>
</div>
</td>
</tr>
</table></center>