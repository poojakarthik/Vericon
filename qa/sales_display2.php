<?php
mysql_connect('localhost','vericon','18450be');

$date = $_GET["date"];
$centre = $_GET["centre"];
$type = $_GET["type"];
?>

<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/sale_details_header2.png" width="125" height="25" /></td>
<td align="right" style="padding-right:10px;"><b><?php echo $centre . " - " . $type; ?></b></td>
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
<th style="text-align:center;" colspan="4">Business</th>
</tr>
<tr class="ui-widget-header ">
<th style="text-align:center;">Sale ID</th>
<th style="text-align:center;">Lead ID</th>
<th style="text-align:center;">Campaign</th>
<th style="text-align:center;"></th>
</tr>
</thead>
<tbody>
<?php
if ($type == "Pending")
{
	$exclude = "";
	$qe = mysql_query("SELECT id FROM vericon.qa_customers WHERE centre = '$centre' AND type = 'Business' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
	if (mysql_num_rows($qe) != 0)
	{
		while ($ex = mysql_fetch_row($qe))
		{
			$exclude .= " AND id != '$ex[0]' ";
		}
	}
	
	$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE status = 'Approved' AND centre = '$centre' AND type = 'Business'" . $exclude . "AND DATE(approved_timestamp) = '$date' ORDER BY id ASC") or die(mysql_error());
}
elseif ($type == "Rejected")
{
	$q = mysql_query("SELECT * FROM vericon.qa_customers WHERE status = 'Rejected' AND centre = '$centre' AND type = 'Business' AND DATE(sale_timestamp) = '$date' ORDER BY id ASC") or die(mysql_error());
}
elseif ($type == "Approved")
{
	$q = mysql_query("SELECT * FROM vericon.qa_customers WHERE status = 'Approved' AND centre = '$centre' AND type = 'Business' AND DATE(sale_timestamp) = '$date' ORDER BY id ASC") or die(mysql_error());
}

if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td colspan='4' style='text-align:center;'>No " . $type . " Business Sales</td>";
	echo "</tr>";
}
else
{
	while ($data = mysql_fetch_assoc($q))
	{
		echo "<tr>";
		echo "<td style='text-align:center;'>" . $data["id"] . "</td>";
		echo "<td style='text-align:center;'>" . $data["lead_id"] . "</td>";
		echo "<td style='text-align:center;'>" . $data["campaign"] . "</td>";
		echo "<td style='text-align:center;'><button onclick='View_Sale(\"$data[id]\")' class='icon_view' title='View'></button></td>";
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
<th style="text-align:center;" colspan="4">Residential</th>
</tr>
<tr class="ui-widget-header ">
<th style="text-align:center;">Sale ID</th>
<th style="text-align:center;">Lead ID</th>
<th style="text-align:center;">Campaign</th>
<th style="text-align:center;"></th>
</tr>
</thead>
<tbody>
<?php
if ($type == "Pending")
{
	$exclude = "";
	$qe = mysql_query("SELECT id FROM vericon.qa_customers WHERE centre = '$centre' AND type = 'Residential' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
	if (mysql_num_rows($qe) != 0)
	{
		while ($ex = mysql_fetch_row($qe))
		{
			$exclude .= " AND id != '$ex[0]' ";
		}
	}
	
	$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE status = 'Approved' AND centre = '$centre' AND type = 'Residential'" . $exclude . "AND DATE(approved_timestamp) = '$date' ORDER BY id ASC") or die(mysql_error());
}
elseif ($type == "Rejected")
{
	$q = mysql_query("SELECT * FROM vericon.qa_customers WHERE status = 'Rejected' AND centre = '$centre' AND type = 'Residential' AND DATE(sale_timestamp) = '$date' ORDER BY id ASC") or die(mysql_error());
}
elseif ($type == "Approved")
{
	$q = mysql_query("SELECT * FROM vericon.qa_customers WHERE status = 'Approved' AND centre = '$centre' AND type = 'Residential' AND DATE(sale_timestamp) = '$date' ORDER BY id ASC") or die(mysql_error());
}

if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td colspan='4' style='text-align:center;'>No " . $type . " Residential Sales</td>";
	echo "</tr>";
}
else
{
	while ($data = mysql_fetch_assoc($q))
	{
		echo "<tr>";
		echo "<td style='text-align:center;'>" . $data["id"] . "</td>";
		echo "<td style='text-align:center;'>" . $data["lead_id"] . "</td>";
		echo "<td style='text-align:center;'>" . $data["campaign"] . "</td>";
		echo "<td style='text-align:center;'><button onclick='View_Sale(\"$data[id]\")' class='icon_view' title='View'></button></td>";
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