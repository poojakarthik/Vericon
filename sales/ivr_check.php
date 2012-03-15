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

$q2 = mysql_query("SELECT * FROM vicidial_live WHERE sale_id = '$data[id]'") or die(mysql_error());
$num2 = mysql_num_rows($q2);

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
elseif (strtotime(date("Y-m-d", strtotime($data["timestamp"])) . "+1 week") < strtotime(date("Y-m-d")))
{
	echo "<span style='color:#FF0000'>Sale ID has Expired</span>";
}
/*elseif ($num2 != 0)
{
	echo "<span style='color:#FF0000'>Call Already in TPV Queue</span>";
}*/
else
{
	echo "<span style='color:#090'>OK to Transfer</span>";
}
?>