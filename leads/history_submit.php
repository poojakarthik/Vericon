<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$id = $_GET["lead"];

if (preg_match('/0([2378])([0-9]{8})/',$id))
{
	$id = substr($id,1,9);
	
	$q = mysql_query("SELECT * FROM log_leads WHERE cli = '$id'") or die(mysql_error());
	
	if (mysql_num_rows($q) == 0)
	{
		echo "<tr><td colspan='4'>Lead Not Found!</td></tr>";
	}
	else
	{
		while ($data = mysql_fetch_assoc($q))
		{
			echo "<tr>";
			echo "<td>" . $data["centre"] . "</td>";
			echo "<td>" . date("d/m/Y", strtotime($data["issue_date"])) . "</td>";
			echo "<td>" . date("d/m/Y", strtotime($data["expiry_date"])) . "</td>";
			echo "<td>" . date("d/m/Y", strtotime($data["packet_expiry"])) . "</td>";
			echo "</tr>";
		}
	}
}
elseif (preg_match('/([2378])([0-9]{8})/',$id))
{
	$q = mysql_query("SELECT * FROM log_leads WHERE cli = '$id'") or die(mysql_error());
	
	if (mysql_num_rows($q) == 0)
	{
		echo "<tr><td colspan='4'>Lead Not Found!</td></tr>";
	}
	else
	{
		while ($data = mysql_fetch_assoc($q))
		{
			echo "<tr>";
			echo "<td>" . $data["centre"] . "</td>";
			echo "<td>" . date("d/m/Y", strtotime($data["issue_date"])) . "</td>";
			echo "<td>" . date("d/m/Y", strtotime($data["expiry_date"])) . "</td>";
			echo "<td>" . date("d/m/Y", strtotime($data["packet_expiry"])) . "</td>";
			echo "</tr>";
		}
	}
}
else
{
	echo "<tr><td colspan='4'>Invalid Lead ID!</td></tr>";
}

?>