<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$method = $_GET["method"];

if ($method == "sales")
{
	$centre = $_GET["centre"];
	$date = $_GET["date"];
	
	$q = mysql_query("SELECT * FROM sales_customers WHERE status != 'Queue' AND centre = '$centre' AND DATE(approved_timestamp) = '$date' ORDER BY approved_timestamp ASC") or die(mysql_error());
	
	while ($data = mysql_fetch_assoc($q))
	{
		echo "<tr>";
		echo "<td><a onclick='Details(\"$data[id]\")' style='text-decoration:underline; cursor:pointer;'>" . $data["id"] . "</a></td>";
		echo "<td>" . $data["status"] . "</td>";
		echo "<td>" . $data["agent"] . "</td>";
		echo "<td>" . $data["type"] . "</td>";
		echo "<td>" . date("l, d/m/Y H:i A", strtotime($data["approved_timestamp"])) . "</td>";
		echo "</tr>";
	}
}
elseif ($method == "details")
{
	$id = $_GET["id"];
	
	$q = mysql_query("SELECT * FROM sales_customers WHERE id = '$id'") or die(mysql_error());
	$data = mysql_fetch_assoc($q);
	
	$q1 = mysql_query("SELECT * FROM auth WHERE user = '$data[agent]'") or die(mysql_error());
	$agent = mysql_fetch_assoc($q1);
?>
<table width="100%">
<tr>
<td width="70px"><b>Sale ID</b></td>
<td><?php echo $data["id"]; ?></td>
</tr>
<tr>
<td><b>Status</b></td>
<td><?php echo $data["status"]; ?></td>
</tr>
<tr>
<td><b>Agent</b></td>
<td><?php echo $agent["first"] . " " . $agent["last"]; ?></td>
</tr>
<tr>
<td><b>Campaign</b></td>
<td><?php echo $data["campaign"]; ?></td>
</tr>
<tr>
<td><b>Type</b></td>
<td><?php echo $data["type"]; ?></td>
</tr>
<tr>
<td><b>Notes</b></td>
<td><div style="height:75px; width:100%; overflow:auto; border: 1px solid #eee;">
<?php
$q2 = mysql_query("SELECT * FROM tpv_notes WHERE id = '$id' ORDER BY timestamp DESC") or die (mysql_error());

echo "<table border='0' width='100%'>";
if (mysql_num_rows($q2) == 0)
{
	echo "<tr>";
	echo "<td>No Notes</td>";
	echo "</tr>";
}
else
{
	while ($tpv_notes = mysql_fetch_assoc($q2))
	{
		$q3 = mysql_query("SELECT * FROM auth WHERE user = '$tpv_notes[verifier]'") or die(mysql_error());
		$vname = mysql_fetch_assoc($q3);
		
		echo "<tr>";
		echo "<td>----- " . date("d/m/Y H:i:s", strtotime($tpv_notes["timestamp"])) . " - " . $vname["first"] . " " . $vname["last"] . " -----" . " (" . $tpv_notes["status"] . ")</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>" . $tpv_notes["note"] . "</td>";
		echo "</tr>";
	}
}
echo "</table>";
?>
</div></td>
</tr>
</table>
<?php	
}
?>