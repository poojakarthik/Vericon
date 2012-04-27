<p><img src="../images/centre_stats_header.png" width="135" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p>

<p><center><img src="../sales/chart.php?method=centre&centre=<?php echo $ac["centre"]; ?>" /></center></p><br />

<p><img src="../images/submitted_sales_header.png" width="165" height="25" style="margin-left:3px;" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p>

<center><div id="users-contain" class="ui-widget" style="width:98%; margin-left:3px; margin-top:-5px;">
<table id="users" class="ui-widget ui-widget-content">
<thead>
<tr class="ui-widget-header ">
<th>Sale ID</th>
<th>Status</th>
<th>Lead ID</th>
<th>Agent</th>
<th>Campaign</th>
<th>Type</th>
</tr>
</thead>
<tbody>
<?php
$today = date("Y-m-d");
$sq = mysql_query("SELECT * FROM sales_customers WHERE centre = '$ac[centre]' AND DATE(timestamp) = '$today' ORDER BY approved_timestamp ASC") or die(mysql_error());
if (mysql_num_rows($sq) == 0)
{
	echo "<tr>";
	echo "<td colspan='6'><center>No Sales Submitted Today!</center></td>";
	echo "</tr>";
}
else
{
	while ($sales_data = mysql_fetch_assoc($sq))
	{
		$aq = mysql_query("SELECT first,last FROM auth WHERE user = '$sales_data[agent]'") or die(mysql_error());
		$agent = mysql_fetch_assoc($aq);
		echo "<tr>";
		echo "<td>" . $sales_data["id"] . "</td>";
		echo "<td>" . $sales_data["status"] . "</td>";
		echo "<td>" . $sales_data["lead_id"] . "</td>";
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