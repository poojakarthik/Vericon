<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$method = $_GET["method"];

if ($method == "stats")
{
	$centre = $_GET["centre"];
	$team = $_GET["team"];
	$date = $_GET["date"];
	
	if ($centre == "CC12")
	{
		$q0 = mysql_query("SELECT campaign FROM centres WHERE centre = '$centre'") or die(mysql_error());
		$cam = mysql_fetch_row($q0);
		
		$campaign = explode(",",$cam[0]);
		$camlength = count($campaign);
		for ($i = 0; $i < $camlength; $i++)
		{
			$q = mysql_query("SELECT * FROM teams WHERE team = '$team'") or die(mysql_error());
		$total_sales = 0;
			while ($team_agent = mysql_fetch_row($q))
			{
				$q1 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND agent = '$team_agent[0]' AND campaign = '$campaign[$i]' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
				$total_sales += mysql_num_rows($q1);
				
				$q5 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Rework' AND agent = '$team_agent[0]' AND campaign = '$campaign[$i]' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
				$reworks += mysql_num_rows($q5);
				
				$q2e = mysql_query("SELECT * FROM qa_customers WHERE status = 'Approved' AND centre = '$centre' AND campaign = '$campaign[$i]' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
				if (mysql_num_rows($q2e) != 0)
				{
					while ($ex = mysql_fetch_row($q2e))
					{
						$exclude .= " AND id != '$ex[0]' ";
					}
				}
				$q2 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND agent = '$team_agent[0]' AND campaign = '$campaign[$i]'" . $exclude . "AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
				$pending += mysql_num_rows($q2);
				
				$q3 = mysql_query("SELECT * FROM qa_customers WHERE status = 'Approved' AND agent = '$team_agent[0]' AND campaign = '$campaign[$i]' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
				$approved += mysql_num_rows($q3);
				
				$q4 = mysql_query("SELECT * FROM qa_customers WHERE status = 'Rejected' AND agent = '$team_agent[0]' AND campaign = '$campaign[$i]' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
				$rejected = mysql_num_rows($q4);
			}

			echo "<tr>";
			echo "<td>" . $campaign[$i] . "</td>";
			echo "<td style='text-align:center'>" . $total_sales . "</td>";
			echo "<td style='text-align:center'>" . $reworks . "</td>";
			echo "<td style='text-align:center'>" . $pending . "</td>";
			echo "<td style='text-align:center'>" . $approved . "</td>";
			echo "<td style='text-align:center'>" . $rejected . "</td>";
			echo "</tr>";
		}
	}
	else
	{	
		$q0 = mysql_query("SELECT campaign FROM centres WHERE centre = '$centre'") or die(mysql_error());
		$cam = mysql_fetch_row($q0);
		
		$campaign = explode(",",$cam[0]);
		$camlength = count($campaign);
		for ($i = 0; $i < $camlength; $i++)
		{
			$q = mysql_query("SELECT COUNT(id) FROM sales_customers WHERE status = 'Approved' AND centre = '$centre' AND campaign = '$campaign[$i]' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
			$total_sales = mysql_fetch_row($q);
			$q5 = mysql_query("SELECT COUNT(id) FROM sales_customers WHERE status = 'Rework' AND centre = '$centre' AND campaign = '$campaign[$i]' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
			$reworks = mysql_fetch_row($q5);
			$q1e = mysql_query("SELECT id FROM qa_customers WHERE status = 'Approved' AND centre = '$centre' AND campaign = '$campaign[$i]' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
			if (mysql_num_rows($q1e) != 0)
			{
				while ($ex = mysql_fetch_row($q1e))
				{
					$exclude .= " AND id != '$ex[0]' ";
				}
			}
			$q1 = mysql_query("SELECT COUNT(id) FROM sales_customers WHERE status = 'Approved' AND centre = '$centre' AND campaign = '$campaign[$i]'" . $exclude . "AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
			$pending = mysql_fetch_row($q1);
			$q2 = mysql_query("SELECT COUNT(id) FROM qa_customers WHERE status = 'Approved' AND centre = '$centre' AND campaign = '$campaign[$i]' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
			$approved = mysql_fetch_row($q2);
			$q3 = mysql_query("SELECT COUNT(id) FROM qa_customers WHERE status = 'Rejected' AND centre = '$centre' AND campaign = '$campaign[$i]' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
			$rejected = mysql_fetch_row($q3);
			
			echo "<tr>";
			echo "<td>" . $campaign[$i] . "</td>";
			echo "<td style='text-align:center'>" . $total_sales[0] . "</td>";
			echo "<td style='text-align:center'>" . $reworks[0] . "</td>";
			echo "<td style='text-align:center'>" . $pending[0] . "</td>";
			echo "<td style='text-align:center'>" . $approved[0] . "</td>";
			echo "<td style='text-align:center'>" . $rejected[0] . "</td>";
			echo "</tr>";
		}
	}
}
elseif ($method == "pending")
{
	$centre = $_GET["centre"];
	$team = $_GET["team"];
	$date = $_GET["date"];
	
	if ($centre == "CC12")
	{
		$q0 = mysql_query("SELECT id FROM qa_customers WHERE status = 'Approved' AND centre = '$centre' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
		if (mysql_num_rows($q0) != 0)
		{
			while ($ex = mysql_fetch_row($q0))
			{
				$exclude .= " AND id != '$ex[0]' ";
			}
		}
		
		$q = mysql_query("SELECT * FROM teams WHERE team = '$team'") or die(mysql_error());
		
		while ($team_agent = mysql_fetch_row($q))
		{
			$q1 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND agent = '$team_agent[0]'" . $exclude . "AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
			
			if (mysql_num_rows($q1) == 0)
			{
				
			}
			else
			{
				while ($data = mysql_fetch_assoc($q1))
				{
					echo "<tr>";
					echo "<td><a href='../qa/process.php?id=$data[id]'>" . $data["id"] . "</a></td>";
					echo "<td>" . $data["lead_id"] . "</td>";
					echo "<td>" . $data["campaign"] . "</td>";
					echo "<td>" . $data["type"] . "</td>";
					echo "</tr>";
				}
			}
		}
	}
	else
	{
		$q0 = mysql_query("SELECT id FROM qa_customers WHERE status = 'Approved' AND centre = '$centre' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
		if (mysql_num_rows($q0) != 0)
		{
			while ($ex = mysql_fetch_row($q0))
			{
				$exclude .= " AND id != '$ex[0]' ";
			}
		}
		
		$q = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND centre = '$centre'" . $exclude . "AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
		
		while ($data = mysql_fetch_assoc($q))
		{
			echo "<tr>";
			echo "<td><a href='../qa/process.php?id=$data[id]'>" . $data["id"] . "</a></td>";
			echo "<td>" . $data["lead_id"] . "</td>";
			echo "<td>" . $data["campaign"] . "</td>";
			echo "<td>" . $data["type"] . "</td>";
			echo "</tr>";
		}
	}
}
elseif ($method == "approved")
{
	$centre = $_GET["centre"];
	$team = $_GET["team"];
	$date = $_GET["date"];
	
	if ($centre == "CC12")
	{
		$q = mysql_query("SELECT * FROM teams WHERE team = '$team'") or die(mysql_error());
		
		while ($team_agent = mysql_fetch_row($q))
		{
			$q1 = mysql_query("SELECT * FROM qa_customers WHERE status = 'Approved' AND agent = '$team_agent[0]' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
			
			if (mysql_num_rows($q1) == 0)
			{
				
			}
			else
			{
				while ($data = mysql_fetch_assoc($q1))
				{
					echo "<tr>";
					echo "<td><a href='../qa/process.php?id=$data[id]'>" . $data["id"] . "</a></td>";
					echo "<td>" . $data["lead_id"] . "</td>";
					echo "<td>" . $data["campaign"] . "</td>";
					echo "<td>" . $data["type"] . "</td>";
					echo "</tr>";
				}
			}
		}
	}
	else
	{
		$q = mysql_query("SELECT id FROM qa_customers WHERE status = 'Approved' AND centre = '$centre' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
		
		while ($data = mysql_fetch_assoc($q))
		{
			echo "<tr>";
			echo "<td><a href='../qa/process.php?id=$data[id]'>" . $data["id"] . "</a></td>";
			echo "<td>" . $data["lead_id"] . "</td>";
			echo "<td>" . $data["campaign"] . "</td>";
			echo "<td>" . $data["type"] . "</td>";
			echo "</tr>";
		}
	}
}
elseif ($method == "rejected")
{
	$centre = $_GET["centre"];
	$team = $_GET["team"];
	$date = $_GET["date"];
	
	if ($centre == "CC12")
	{
		$q = mysql_query("SELECT * FROM teams WHERE team = '$team'") or die(mysql_error());
		
		while ($team_agent = mysql_fetch_row($q))
		{
			$q1 = mysql_query("SELECT * FROM qa_customers WHERE status = 'Rejected' AND agent = '$team_agent[0]' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
			
			if (mysql_num_rows($q1) == 0)
			{
				
			}
			else
			{
				while ($data = mysql_fetch_assoc($q1))
				{
					echo "<tr>";
					echo "<td><a href='../qa/process.php?id=$data[id]'>" . $data["id"] . "</a></td>";
					echo "<td>" . $data["lead_id"] . "</td>";
					echo "<td>" . $data["campaign"] . "</td>";
					echo "<td>" . $data["type"] . "</td>";
					echo "</tr>";
				}
			}
		}
	}
	else
	{
		$q = mysql_query("SELECT id FROM qa_customers WHERE status = 'Rejected' AND centre = '$centre' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
		
		while ($data = mysql_fetch_assoc($q))
		{
			echo "<tr>";
			echo "<td><a href='../qa/process.php?id=$data[id]'>" . $data["id"] . "</a></td>";
			echo "<td>" . $data["lead_id"] . "</td>";
			echo "<td>" . $data["campaign"] . "</td>";
			echo "<td>" . $data["type"] . "</td>";
			echo "</tr>";
		}
	}
}
?>