<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$method = $_GET["method"];

if ($method == "sales")
{
	$centre = $_GET["centre"];
	$team = $_GET["team"];
	$date = $_GET["date"];
	
	if ($centre == "CC12")
	{
		$q = mysql_query("SELECT * FROM teams WHERE team = '$team'") or die(mysql_error());
		
		while ($team_agent = mysql_fetch_row($q))
		{
			$q1 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND agent = '$team_agent[0]' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
			
			if (mysql_num_rows($q1) == 0)
			{
				
			}
			else
			{
				while ($data = mysql_fetch_assoc($q1))
				{
					echo "<tr>";
					echo "<td>" . $data["id"] . "</td>";
					echo "<td>" . date("l, d/m/Y H:i A", strtotime($data["timestamp"])) . "</td>";
					echo "<td>" . date("l, d/m/Y H:i A", strtotime($data["approved_timestamp"])) . "</td>";
					echo "</tr>";
				}
			}
		}
	}
	else
	{
		$q = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND centre = '$centre' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
		
		while ($data = mysql_fetch_assoc($q))
		{
			echo "<tr>";
			echo "<td>" . $data["id"] . "</td>";
			echo "<td>" . date("l, d/m/Y H:i A", strtotime($data["timestamp"])) . "</td>";
			echo "<td>" . date("l, d/m/Y H:i A", strtotime($data["approved_timestamp"])) . "</td>";
			echo "</tr>";
		}
	}
}
?>