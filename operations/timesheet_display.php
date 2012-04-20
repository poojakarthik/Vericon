<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$method = $_GET["method"];
$centre = $_GET["centre"];
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
			$hours = 0;
			$q1 = mysql_query("SELECT hours FROM timesheet WHERE user = '$users[user]'") or die(mysql_error());
			while ($h = mysql_fetch_row($q1))
			{
				$hours += $h[0];
			}
			
			$q2 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND agent = '$users[user]'") or die(mysql_error());
			$sales = mysql_num_rows($q2);
			
			$sph = $sales / $hours[0];
			
			if ($sph < 0.15) { $grade = "D"; } elseif ($sph < 0.2) { $grade = "C"; } elseif ($sph < 0.25) { $grade = "B"; } else { $grade = "A"; }

			echo "<tr>";
			echo "<td>" . $i . "</td>";
			echo "<td>" . $users["first"] . " " . $users["last"] . "</td>";
			echo "<td style='text-align:center;'>" . number_format($hours,2) . "</td>";
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
			$hours = 0;
			$q1 = mysql_query("SELECT hours FROM timesheet WHERE user = '$users[user]'") or die(mysql_error());
			while ($h = mysql_fetch_row($q1))
			{
				$hours += $h[0];
			}
			
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
elseif ($method == "search")
{
	$cen = $_GET["centres"];
	$term = explode(" ",$_GET["term"]);
	$centres = explode("_",$cen);
	
	foreach($centres as $row)
	{
		$inc .= "centre = '" . $row . "' OR ";
		
	}
	
	$inc = substr($inc, 0, -4);
	
	$q = mysql_query("SELECT * FROM auth WHERE (" . $inc . ") AND first LIKE '$term[0]%' AND last LIKE '$term[1]%'") or die(mysql_error());
	echo '[';
	while ($data = mysql_fetch_assoc($q))
	{
		$d[] = "{ \"id\": \"" . $data["user"] . "\", \"value\": \"" . $data["first"] . " " . $data["last"] . "\" }";
	}
	echo implode(", ",$d);
	echo ']';
}
elseif ($method == "check")
{
	$agent = $_GET["agent"];
	
	$q = mysql_query("SELECT * FROM auth WHERE user = '$agent'") or die(mysql_error());
	if (mysql_num_rows($q) == 0)
	{
		echo "Please Type the Agent's Name Below";
	}
	else
	{
		echo "valid";
	}
}
elseif ($method == "Agent")
{
	$agent = $_GET["agent"];
	$we = $_GET["we"];
	
	for ($day=1; $day <= 7; $day++)
	{
		$date = date('Y-m-d', strtotime(date("Y", strtotime($we)) . "W" . date("W", strtotime($we)) . $day));
		
		$q1 = mysql_query("SELECT hours FROM timesheet WHERE user = '$agent' AND date = '$date'") or die(mysql_error());
		$h = mysql_fetch_row($q1);
		if ($h[0] == "") { $hours = 0; } else { $hours = $h[0]; }
		$total_hours += $hours;
		
		$q2 = mysql_query("SELECT * FROM sales_customers WHERE agent = '$agent' AND approved_timestamp = '$date' AND status = 'Approved'") or die(mysql_error());
		$sales = mysql_num_rows($q2);
		$total_sales += $sales;
		
		$sph = $sales / $data["hours"];
		
		if ($sph < 0.15) { $grade = "D"; } elseif ($sph < 0.2) { $grade = "C"; } elseif ($sph < 0.25) { $grade = "B"; } else { $grade = "A"; }
		
		echo "<tr>";
		echo "<td>" . date("d/m/Y", strtotime($date)) . "</td>";
		echo "<td style='text-align:center;'>" . $hours . "</td>";
		echo "<td style='text-align:center;'>" . $sales . "</td>";
		echo "<td style='text-align:center;'>" . number_format($sph,2) . "</td>";
		echo "<td style='text-align:center;'>" . $grade . "</td>";
		echo "</tr>";
	}
	
	$total_sph = $total_sales / $total_hours;
	
	if ($total_sph < 0.15) { $total_grade = "D"; } elseif ($total_sph < 0.2) { $total_grade = "C"; } elseif ($total_sph < 0.25) { $total_grade = "B"; } else { $total_grade = "A"; }
	
	echo "<tr>";
	echo "<td><b>Total</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_hours  . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_sales . "</b></td>";
	echo "<td style='text-align:center;'><b>" . number_format($total_sph,2) . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_grade . "</b></td>";
	echo "</tr>";
}
?>