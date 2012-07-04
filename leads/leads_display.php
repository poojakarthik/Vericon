<?php
mysql_connect('localhost','vericon','18450be');
?>
<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/leads_dashboard_header.png" width="175" height="25"></td>
<td align="right" style="padding-right:10px;"><button onclick="Search()" class="btn2">Search</button></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="740" height="9" /></td>
</tr>
</table>

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
$q = mysql_query("SELECT centre FROM vericon.centres WHERE status = 'Enabled' ORDER BY centre ASC") or die(mysql_error());
while ($centre = mysql_fetch_row($q))
{
	$q1 = mysql_query("SELECT expiry_date FROM vericon.leads WHERE centre = '$centre[0]' AND expiry_date >= '" . date("Y-m-d") . "' ORDER BY expiry_date DESC") or die(mysql_error());
	$num = mysql_num_rows($q1);
	$c_exp = mysql_fetch_row($q1);
	$total_num += $num;

	$q2 = mysql_query("SELECT timestamp FROM vericon.leads WHERE centre = '$centre[0]' AND expiry_date >= '" . date("Y-m-d") . "' ORDER BY timestamp DESC LIMIT 1") or die(mysql_error());
	$c_last = mysql_fetch_row($q2);

	echo "<tr>";
	echo "<td>" . $centre[0] . "</td>";
	echo "<td>" . number_format($num) . "</td>";
	echo "<td>" . date("d/m/Y", strtotime($c_exp[0])) . "</td>";
	echo "<td>" . date("d/m/Y H:i:s", strtotime($c_last[0])) . "</td>";
	echo "<td><input type='button' onclick='Details(\"$centre[0]\")' class='icon_view' title='View Details'></td>";
	echo "<td><input type='button' onclick='Export(\"$centre[0]\")' class='icon_excel' title='Export Leads'></td>";
	echo "</tr>";
}

//total
echo "<tr>";
echo "<td><b>Total</b></td>";
echo "<td><b>" . number_format($total_num) . "</b></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td colspan='2'><input type='button' onclick='Export(\"All\")' class='icon_excel' title='Export Leads'></td>";
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
$q1 = mysql_query("SELECT expiry_date FROM vericon.leads WHERE centre = 'Kamal' AND expiry_date >= '" . date("Y-m-d") . "' ORDER BY expiry_date DESC") or die(mysql_error());
$num = mysql_num_rows($q1);
$total_num += $num;
$k_exp = mysql_fetch_row($q1);

$q2 = mysql_query("SELECT timestamp FROM vericon.leads WHERE centre = 'Kamal' AND expiry_date >= '" . date("Y-m-d") . "' ORDER BY timestamp DESC LIMIT 1") or die(mysql_error());
$k_last = mysql_fetch_row($q2);

echo "<tr>";
echo "<td>Kamal</td>";
echo "<td>" . number_format($num) . "</td>";
echo "<td>" . date("d/m/Y", strtotime($k_exp[0])) . "</td>";
echo "<td>" . date("d/m/Y H:i:s", strtotime($k_last[0])) . "</td>";
echo "<td><input type='button' onclick='Details(\"Kamal\")' class='icon_view' title='View Details'></td>";
echo "<td><input type='button' onclick='Export(\"Kamal\")' class='icon_excel' title='Export Leads'></td>";
echo "</tr>";

//rohan
$q1 = mysql_query("SELECT expiry_date FROM vericon.leads WHERE centre = 'Rohan' AND expiry_date >= '" . date("Y-m-d") . "' ORDER BY expiry_date DESC") or die(mysql_error());
$num = mysql_num_rows($q1);
$total_num += $num;
$k_exp = mysql_fetch_row($q1);

$q2 = mysql_query("SELECT timestamp FROM vericon.leads WHERE centre = 'Rohan' AND expiry_date >= '" . date("Y-m-d") . "' ORDER BY timestamp DESC LIMIT 1") or die(mysql_error());
$k_last = mysql_fetch_row($q2);

echo "<tr>";
echo "<td>Rohan</td>";
echo "<td>" . number_format($num) . "</td>";
echo "<td>" . date("d/m/Y", strtotime($k_exp[0])) . "</td>";
echo "<td>" . date("d/m/Y H:i:s", strtotime($k_last[0])) . "</td>";
echo "<td><input type='button' onclick='Details(\"Rohan\")' class='icon_view' title='View Details'></td>";
echo "<td><input type='button' onclick='Export(\"Rohan\")' class='icon_excel' title='Export Leads'></td>";
echo "</tr>";

//sanjay
$q1 = mysql_query("SELECT expiry_date FROM vericon.leads WHERE centre = 'Sanjay' AND expiry_date >= '" . date("Y-m-d") . "' ORDER BY expiry_date DESC") or die(mysql_error());
$num = mysql_num_rows($q1);
$total_num += $num;
$k_exp = mysql_fetch_row($q1);

$q2 = mysql_query("SELECT timestamp FROM vericon.leads WHERE centre = 'Sanjay' AND expiry_date >= '" . date("Y-m-d") . "' ORDER BY timestamp DESC LIMIT 1") or die(mysql_error());
$k_last = mysql_fetch_row($q2);

echo "<tr>";
echo "<td>Sanjay</td>";
echo "<td>" . number_format($num) . "</td>";
echo "<td>" . date("d/m/Y", strtotime($k_exp[0])) . "</td>";
echo "<td>" . date("d/m/Y H:i:s", strtotime($k_last[0])) . "</td>";
echo "<td><input type='button' onclick='Details(\"Sanjay\")' class='icon_view' title='View Details'></td>";
echo "<td><input type='button' onclick='Export(\"Sanjay\")' class='icon_excel' title='Export Leads'></td>";
echo "</tr>";

//total
echo "<tr>";
echo "<td><b>Total</b></td>";
echo "<td><b>" . number_format($total_num) . "</b></td>";
echo "<td></td>";
echo "<td></td>";
echo "<td colspan='2'><input type='button' onclick='Export(\"Special\")' class='icon_excel' title='Export Leads'></td>";
echo "</tr>";
?>
</tbody>
</table>
</div></center>

<?php
include "../source/footer.php";
?>