<?php
$id = $_GET["sale_id"];

if (strlen($id) != 9)
{
	echo "<span style='color:#FF0000'>Invalid Sale ID</span>";
	exit;
}
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$q = mysql_query("SELECT * FROM sales_customers WHERE id = '" . mysql_escape_string($id) . "'") or die(mysql_error());
$data = mysql_fetch_assoc($q);
$num = mysql_num_rows($q);

if ($num == 0)
{
	echo "<span style='color:#FF0000'>Invalid Sale ID</span>";
}
elseif ($data["status"] == "Approved")
{
	echo "<span style='color:#FF0000'>Sale Already Approved</span>";
}
elseif ($data["status"] == "Hold")
{
	echo "<span style='color:#FF0000'>Sale on Hold</span>";
}
elseif (strtotime(date("Y-m-d", strtotime($data["timestamp"])) . "+1 week") < strtotime(date("Y-m-d")) && $data["status"] != "Rework")
{
	echo "<span style='color:#FF0000'>Sale ID has Expired</span>";
}
else
{
	echo "<span style='color:#090'>OK to Transfer</span>";
}
?>