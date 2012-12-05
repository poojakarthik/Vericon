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
$count = array();
$expiry = array();
$q = mysql_query("SELECT centre, COUNT(cli), MAX(expiry_date) FROM leads.leads WHERE expiry_date >= '" . date("Y-m-d") . "' GROUP BY centre") or die(mysql_error());
while($data = mysql_fetch_row($q))
{
	$count[$data[0]] = $data[1];
	$expiry[$data[0]] = $data[2];
}

$total_num = 0;
//centres
$q = mysql_query("SELECT centre FROM vericon.centres WHERE status = 'Enabled' ORDER BY centre ASC") or die(mysql_error());
while ($centre = mysql_fetch_row($q))
{
	$total_num += $count[$centre[0]];
	$q1 = mysql_query("SELECT timestamp FROM leads.leads_time WHERE centre = '$centre[0]'") or die(mysql_error());
	$c_last = mysql_fetch_row($q1);
	
	if ($expiry[$centre[0]] != "") { $expiry_date = date("d/m/Y", strtotime($expiry[$centre[0]])); } else { $expiry_date = "-"; }
	
	echo "<tr>";
	echo "<td>" . $centre[0] . "</td>";
	echo "<td>" . number_format($count[$centre[0]]) . "</td>";
	echo "<td>" . $expiry_date . "</td>";
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
$total_num += $count["KAMAL"];
$q1 = mysql_query("SELECT timestamp FROM leads.leads_time WHERE centre = 'KAMAL'") or die(mysql_error());
$c_last = mysql_fetch_row($q1);

if ($expiry["KAMAL"] != "") { $expiry_date = date("d/m/Y", strtotime($expiry["KAMAL"])); } else { $expiry_date = "-"; }

echo "<tr>";
echo "<td>Kamal</td>";
echo "<td>" . number_format($count["KAMAL"]) . "</td>";
echo "<td>" . $expiry_date . "</td>";
echo "<td>" . date("d/m/Y H:i:s", strtotime($c_last[0])) . "</td>";
echo "<td><input type='button' onclick='Details(\"KAMAL\")' class='icon_view' title='View Details'></td>";
echo "<td><input type='button' onclick='Export(\"KAMAL\")' class='icon_excel' title='Export Leads'></td>";
echo "</tr>";

//rohan
$total_num += $count["ROHAN"];
$q1 = mysql_query("SELECT timestamp FROM leads.leads_time WHERE centre = 'ROHAN'") or die(mysql_error());
$c_last = mysql_fetch_row($q1);

if ($expiry["ROHAN"] != "") { $expiry_date = date("d/m/Y", strtotime($expiry["ROHAN"])); } else { $expiry_date = "-"; }

echo "<tr>";
echo "<td>Rohan</td>";
echo "<td>" . number_format($count["ROHAN"]) . "</td>";
echo "<td>" . $expiry_date . "</td>";
echo "<td>" . date("d/m/Y H:i:s", strtotime($c_last[0])) . "</td>";
echo "<td><input type='button' onclick='Details(\"ROHAN\")' class='icon_view' title='View Details'></td>";
echo "<td><input type='button' onclick='Export(\"ROHAN\")' class='icon_excel' title='Export Leads'></td>";
echo "</tr>";

//sanjay
$total_num += $count["SANJAY"];
$q1 = mysql_query("SELECT timestamp FROM leads.leads_time WHERE centre = 'SANJAY'") or die(mysql_error());
$c_last = mysql_fetch_row($q1);

if ($expiry["SANJAY"] != "") { $expiry_date = date("d/m/Y", strtotime($expiry["SANJAY"])); } else { $expiry_date = "-"; }

echo "<tr>";
echo "<td>Sanjay</td>";
echo "<td>" . number_format($count["SANJAY"]) . "</td>";
echo "<td>" . $expiry_date . "</td>";
echo "<td>" . date("d/m/Y H:i:s", strtotime($c_last[0])) . "</td>";
echo "<td><input type='button' onclick='Details(\"SANJAY\")' class='icon_view' title='View Details'></td>";
echo "<td><input type='button' onclick='Export(\"SANJAY\")' class='icon_excel' title='Export Leads'></td>";
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