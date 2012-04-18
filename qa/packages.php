<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$id = $_GET["id"];

$q = mysql_query("SELECT * FROM sales_packages WHERE sid = '" . mysql_escape_string($id) . "'");

if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td colspan='2' style='text-align:center;'>Customer has no packages!</td>";
	echo "</tr>";
}
else
{
	while ($package = mysql_fetch_assoc($q))
	{
		echo "<tr>";
		echo "<td>" . $package["cli"] . "</td>";
		$q1 = mysql_query("SELECT name FROM plan_matrix WHERE id = '$package[plan]'") or die(mysql_error());
		$package_name = mysql_fetch_row($q1);
		echo "<td>" . $package_name[0] . "</td>";
		echo "</tr>";
	}
}
?>