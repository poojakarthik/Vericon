<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$id = $_GET["id"];

$q = mysql_query("SELECT * FROM sales_packages WHERE sid = '$id'");

if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td colspan='3' style='text-align:center;'>Customer has no packages!</td>";
	echo "</tr>";
}
else
{
	while ($package = mysql_fetch_assoc($q))
	{
		echo "<tr>";
		echo "<td>" . $package["cli"] . "</td>";
		echo "<td>" . $package["plan"] . "</td>";
		echo "<td><a onclick='Edit_Package(\"$package[cli]\",\"$package[plan]\")' style='cursor:pointer; text-decoration:underline;'>Edit</a></td>";
		echo "<td><a onclick='Delete_Package(\"$package[cli]\")' style='cursor:pointer; text-decoration:underline;'>Delete</a></td>";
		echo "</tr>";
	}
}
?>