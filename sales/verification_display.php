<?php
mysql_connect('localhost','vericon','18450be');

$user = $_GET["user"];
?>

<div id="get_sale_table" style="margin-top:15px; margin-bottom:15px;">
<form onsubmit="event.preventDefault()">
<table>
<tr>
<td><p>Enter the Customer's Lead ID</p></td>
<td><input type="text" id="id" size="25"/></td>
<td><button type="submit" class="get_sale_btn" onclick="Get_Sale()"></button></td>
</tr>
</table>
</form>
<center><p class="error" style="color:#C00;"></p></center>
</div>
<br />
<p><img src="../images/submitted_sales_header.png" width="165" height="25" style="margin-left:3px;" /></p>
<p><img src="../images/line.png" width="715" height="9" /></p>
<div id="users-contain" class="ui-widget" style="width:100%; margin-left:5px; margin-top:-5px;">
<table id="users" class="ui-widget ui-widget-content" width="95%">
<thead>
<tr class="ui-widget-header ">
<th>Sale ID</th>
<th>Lead ID</th>
<th>Status</th>
<th>Date/Time</th>
<th>Customer Name</th>
</tr>
</thead>
<tbody>
<?php
$weekago = date("Y-m-d", strtotime("-1 week"));
$q = mysql_query("SELECT id,status,timestamp,firstname,lastname,lead_id FROM vericon.sales_customers WHERE agent = '$user' AND DATE(timestamp) >= '$weekago' ORDER BY timestamp DESC") or die(mysql_error());
if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td colspan='4'><center>No Sales Submitted!</center></td>";
	echo "</tr>";
}
else
{
	while ($sales_data = mysql_fetch_assoc($q))
	{
		echo "<tr>";
		echo "<td>" . $sales_data["id"] . "</td>";
		echo "<td>" . $sales_data["lead_id"] . "</td>";
		echo "<td>" . $sales_data["status"] . "</td>";
		echo "<td>" . date("d/m/Y H:i", strtotime($sales_data["timestamp"])) . "</td>";
		echo "<td>" . $sales_data["firstname"] . " " . $sales_data["lastname"] . "</td>";
		echo "</tr>";
	}
}
?>
</tbody>
</table>
</div>