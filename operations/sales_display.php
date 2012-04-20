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
		echo "<td>" . $data["id"] . "</td>";
		echo "<td>" . $data["status"] . "</td>";
		echo "<td>" . $data["agent"] . "</td>";
		echo "<td>" . $data["type"] . "</td>";
		echo "<td>" . date("l, d/m/Y H:i A", strtotime($data["approved_timestamp"])) . "</td>";
		echo "</tr>";
	}
}
?>