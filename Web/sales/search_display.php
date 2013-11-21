<?php
mysql_connect('localhost','vericon','18450be');
?>

<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/vericon_search_header.png" width="160" height="25" /></td>
<td align="right" style="padding-right:10px;"><button onClick="Search()" class="btn2">Search</button></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<div id="results" style="min-height:75px;">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:98%; margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th width="12%">ID</th>
<th width="10%">Status</th>
<th width="12%">Lead ID</th>
<th width="26%">Agent</th>
<th width="20%">Campaign</th>
<th width="10%">Type</th>
<th colspan="2" style='text-align:center;' width="10%"></th>
</tr>
</thead>
<tbody>
<tr>
<td colspan='8' style='text-align:center;'>Click the search button above to search for sales</td>
</tr>
</tbody>
</table>
</div></center>
</div>

<table width="100%" style="margin-top:40px;">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/submitted_sales_header.png" width="165" height="25" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:98%; margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th>Sale ID</th>
<th>Status</th>
<th>Lead ID</th>
<th>Date Submitted</th>
<th>Agent</th>
<th>Campaign</th>
<th>Type</th>
</tr>
</thead>
<tbody>
<?php
$weekago = date("Y-m-d", strtotime("-1 week"));
$sq = mysql_query("SELECT * FROM vericon.sales_customers WHERE centre = '$ac[centre]' AND DATE(timestamp) >= '$weekago' ORDER BY timestamp DESC") or die(mysql_error());
if (mysql_num_rows($sq) == 0)
{
	echo "<tr>";
	echo "<td colspan='7'><center>No sales submitted this week</center></td>";
	echo "</tr>";
}
else
{
	while ($sales_data = mysql_fetch_assoc($sq))
	{
		$aq = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$sales_data[agent]'") or die(mysql_error());
		$agent = mysql_fetch_assoc($aq);
		echo "<tr>";
		echo "<td>" . $sales_data["id"] . "</td>";
		echo "<td>" . $sales_data["status"] . "</td>";
		echo "<td>" . $sales_data["lead_id"] . "</td>";
		echo "<td>" . date("d/m/Y", strtotime($sales_data["timestamp"])) . "</td>";
		echo "<td>" . $agent["first"] . " " . $agent["last"] . "</td>";
		echo "<td>" . $sales_data["campaign"] . "</td>";
		echo "<td>" . $sales_data["type"] . "</td>";
		echo "</tr>";
	}
}
?>
</tbody>
</table>
</div></center>