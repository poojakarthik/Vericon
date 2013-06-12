<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];
$query = $_GET["query"];

if ($method == "line")
{
	$q = mysql_query("SELECT sales_customers.id, sales_customers.status, sales_customers.centre, sales_customers.agent, sales_customers.campaign, sales_customers.type FROM vericon.sales_customers, vericon.sales_packages WHERE sales_packages.cli = '" . mysql_real_escape_string($query) . "' AND sales_customers.id = sales_packages.sid ORDER BY sales_customers.id DESC") or die(mysql_error());
}
elseif ($method == "id")
{
	$q = mysql_query("SELECT id,status,centre,agent,campaign,type FROM vericon.sales_customers WHERE id = '" . mysql_real_escape_string($query) . "'") or die(mysql_error());
}
elseif ($method == "lead")
{
	$q = mysql_query("SELECT id,status,centre,agent,campaign,type FROM vericon.sales_customers WHERE lead_id = '" . mysql_real_escape_string($query) . "'") or die(mysql_error());
}
?>
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:98%; margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th width="12%">ID</th>
<th width="12%">Status</th>
<th width="10%">Centre</th>
<th width="26%">Agent</th>
<th width="20%">Campaign</th>
<th width="10%">Type</th>
<th colspan="2" style='text-align:center;' width="10%"></th>
</tr>
</thead>
<tbody>
<?php
if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td colspan='8' style='text-align:center;'>No Results Found</td>";
	echo "</tr>";
}
else
{
	while ($data = mysql_fetch_row($q))
	{
		$q1 = mysql_query("SELECT user,first,last FROM vericon.auth WHERE user = '$data[3]'") or die(mysql_error());
		$agent = mysql_fetch_row($q1);
		
		echo "<tr>";
		echo "<td>" . $data[0] . "</td>";
		echo "<td>" . $data[1] . "</td>";
		echo "<td>" . $data[2] . "</td>";
		echo "<td>" . $agent[1] . " " . $agent[2] . " (" . $agent[0] . ")</td>";
		echo "<td>" . $data[4] . "</td>";
		echo "<td>" . $data[5] . "</td>";
		echo "<td style='text-align:center;'><button onclick='View_Search(\"$data[0]\")' class='icon_view'></button></td>";
		echo "<td style='text-align:center;'><button onclick='Edit_Switch(\"$data[0]\")' class='icon_edit'></button></td>";
		echo "</tr>";
	}
}
?>
</tbody>
</table>
</div></center>