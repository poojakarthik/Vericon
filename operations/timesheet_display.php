<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$method = $_GET["method"];
$centre = $_GET["centre"];
$date1 = $_GET["date1"];
$date2 = $_GET["date2"];
$i = 1;

if ($method == "Active")
{
	if ($centre == "")
	{
		echo "<tr>";
		echo "<td colspan='6' style='text-align:center;'>Please Select a Centre From Above</td>";
		echo "</tr>";
	}
	else
	{
		$q = mysql_query("SELECT * FROM auth WHERE centre = '$centre' AND status = 'Enabled' ORDER BY first ASC") or die(mysql_error());
		while ($users = mysql_fetch_assoc($q))
		{
			$q1 = mysql_query("SELECT hours FROM timesheet WHERE user = '$users[user]' GROUP BY user") or die(mysql_error());
			$hours = mysql_fetch_row($q1);
			
			$q2 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND agent = '$users[user]'") or die(mysql_error());
			$sales = mysql_num_rows($q2);
			
			$sph = $sales / $hours[0];
			
			if ($sph < 0.15) { $grade = "D"; } elseif ($sph < 0.2) { $grade = "C"; } elseif ($sph < 0.25) { $grade = "B"; } else { $grade = "A"; }

			echo "<tr>";
			echo "<td>" . $i . "</td>";
			echo "<td>" . $users["first"] . " " . $users["last"] . "</td>";
			echo "<td style='text-align:center;'>" . number_format($hours[0],2) . "</td>";
			echo "<td style='text-align:center;'>" . $sales . "</td>";
			echo "<td style='text-align:center;'>" . number_format($sph,2) . "</td>";
			echo "<td style='text-align:center;'>" . $grade . "</td>";
			echo "</tr>";
			$i++;
		}
	}
}
elseif ($method == "All")
{
	if ($centre == "")
	{
		echo "<tr>";
		echo "<td colspan='6' style='text-align:center;'>Please Select a Centre From Above</td>";
		echo "</tr>";
	}
	else
	{
		$q = mysql_query("SELECT * FROM auth WHERE centre = '$centre' ORDER BY first ASC") or die(mysql_error());
		while ($users = mysql_fetch_assoc($q))
		{
			$q1 = mysql_query("SELECT hours FROM timesheet WHERE user = '$users[user]' GROUP BY user") or die(mysql_error());
			$hours = mysql_fetch_row($q1);
			
			$q2 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND agent = '$users[user]'") or die(mysql_error());
			$sales = mysql_num_rows($q2);
			
			$sph = $sales / $hours[0];
			
			if ($sph < 0.15) { $grade = "D"; } elseif ($sph < 0.2) { $grade = "C"; } elseif ($sph < 0.25) { $grade = "B"; } else { $grade = "A"; }

			echo "<tr>";
			echo "<td>" . $i . "</td>";
			echo "<td>" . $users["first"] . " " . $users["last"] . "</td>";
			echo "<td style='text-align:center;'>" . number_format($hours[0],2) . "</td>";
			echo "<td style='text-align:center;'>" . $sales . "</td>";
			echo "<td style='text-align:center;'>" . number_format($sph,2) . "</td>";
			echo "<td style='text-align:center;'>" . $grade . "</td>";
			echo "</tr>";
			$i++;
		}
	}
}
?>