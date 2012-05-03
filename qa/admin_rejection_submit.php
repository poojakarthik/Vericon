<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$method = $_GET["method"];

if ($method == "stats")
{
	$centre = $_GET["centre"];
	$date = $_GET["date"];
	
	if ($centre == "All")
	{
		$q0 = mysql_query("SELECT campaign FROM campaigns ORDER BY campaign ASC") or die(mysql_error());
		while ($campaign = mysql_fetch_row($q0))
		{
			$q = mysql_query("SELECT COUNT(id) FROM sales_customers WHERE status = 'Approved' AND campaign = '$campaign[0]' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
			$total_sales = mysql_fetch_row($q);
			$q5 = mysql_query("SELECT COUNT(id) FROM sales_customers WHERE status = 'Rework' AND campaign = '$campaign[0]' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
			$reworks = mysql_fetch_row($q5);
			$q1e = mysql_query("SELECT id FROM qa_customers WHERE campaign = '$campaign[0]' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
			if (mysql_num_rows($q1e) != 0)
			{
				while ($ex = mysql_fetch_row($q1e))
				{
					$exclude .= " AND id != '$ex[0]' ";
				}
			}
			$q1 = mysql_query("SELECT COUNT(id) FROM sales_customers WHERE status = 'Approved' AND campaign = '$campaign[0]'" . $exclude . "AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
			$pending = mysql_fetch_row($q1);
			$q2 = mysql_query("SELECT COUNT(id) FROM qa_customers WHERE status = 'Approved' AND campaign = '$campaign[0]' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
			$approved = mysql_fetch_row($q2);
			$q3 = mysql_query("SELECT COUNT(id) FROM qa_customers WHERE status = 'Rejected' AND campaign = '$campaign[0]' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
			$rejected = mysql_fetch_row($q3);
			
			echo "<tr>";
			echo "<td>" . $campaign[0] . "</td>";
			echo "<td style='text-align:center'>" . $total_sales[0] . "</td>";
			echo "<td style='text-align:center'>" . $reworks[0] . "</td>";
			echo "<td style='text-align:center'>" . $pending[0] . "</td>";
			echo "<td style='text-align:center'>" . $approved[0] . "</td>";
			echo "<td style='text-align:center'>" . $rejected[0] . "</td>";
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
			$q1e = mysql_query("SELECT id FROM qa_customers WHERE centre = '$centre' AND campaign = '$campaign[$i]' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
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
elseif ($method == "download")
{
	$centre = $_GET["centre"];
	$date = $_GET["date"];
	
	echo "<a href='download_rej.php?date=$date&centre=$centre' style='color:inherit;'>Download - Rejection_Report_" . date("d.m.Y", strtotime($date)) . "_" . $centre . ".csv</a>";
}
?>