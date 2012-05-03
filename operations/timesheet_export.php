<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$method = $_GET["method"];
$type = $_GET["type"];

if ($method == "centre")
{
	$centre = $_GET["centre"];
	$header = "#,Agent Name,Total Hours,Total Sales,Average SPH,Grade";
	
	if ($type == "Active")
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

			$data .= "\"" . $i . "\",";
			$data .= "\"" . $users["first"] . " " . $users["last"] . "\",";
			$data .= "\"" . number_format($hours,2) . "\",";
			$data .= "\"" . $sales . "\",";
			$data .= "\"" . number_format($sph,2) . "\",";
			$data .= "\"" . $grade . "\"";
			$data .= "\n";
			$i++;
		}
		
		$filename = $centre . "_Active_Agents_Timesheet" . ".csv";
	}
	elseif ($type == "All")
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

			$data .= "\"" . $i . "\",";
			$data .= "\"" . $users["first"] . " " . $users["last"] . "\",";
			$data .= "\"" . number_format($hours,2) . "\",";
			$data .= "\"" . $sales . "\",";
			$data .= "\"" . number_format($sph,2) . "\",";
			$data .= "\"" . $grade . "\"";
			$data .= "\n";
			$i++;
		}
		
		$filename = $centre . "_All_Agents_Timesheet" . ".csv";
	}
}
elseif ($method == "agent")
{
	$user = $_GET["user"];
	
	if ($type == "weekly")
	{
		$header = "Week Ending,Total Hours,Total Sales,Average SPH,Grade";
		
		$q = mysql_query("SELECT * FROM timesheet WHERE user = '$user' GROUP BY WEEK(date) ORDER BY date ASC") or die(mysql_error());

		while ($da = mysql_fetch_assoc($q))
		{
			$week = date("W", strtotime($da["date"]));
			$year = date("Y", strtotime($da["date"]));
			$we = date("Y-m-d", strtotime($year . "W" . $week . "7"));
			
			$hours = 0;
			$q0 = mysql_query("SELECT hours FROM timesheet WHERE user = '$user' AND WEEK(date) = '$week'") or die(mysql_error());
			while ($h = mysql_fetch_row($q0))
			{
				$hours += $h[0];
			}
			$total_hours += $hours;
			
			$q1 = mysql_query("SELECT * FROM sales_customers WHERE agent = '$user' AND WEEK(approved_timestamp) = '$week' AND status = 'Approved'") or die(mysql_error());
			$sales = mysql_num_rows($q1);
			$total_sales += $sales;
			
			$sph = $sales / $da["hours"];
			
			if ($sph < 0.15) { $grade = "D"; } elseif ($sph < 0.2) { $grade = "C"; } elseif ($sph < 0.25) { $grade = "B"; } else { $grade = "A"; }
			
			$data .= "\"" . "W.E. " . date("d/m/Y", strtotime($we)) . "\",";
			$data .= "\"" . $hours . "\",";
			$data .= "\"" . $sales . "\",";
			$data .= "\"" . number_format($sph,2) . "\",";
			$data .= "\"" . $grade . "\"";
			$data .= "\n";
		}
		
		$total_sph = $total_sales / $total_hours;
		
		if ($total_sph < 0.15) { $total_grade = "D"; } elseif ($total_sph < 0.2) { $total_grade = "C"; } elseif ($total_sph < 0.25) { $total_grade = "B"; } else { $total_grade = "A"; }
		
		$data .= "\"" . "Total" . "\",";
		$data .= "\"" . $total_hours . "\",";
		$data .= "\"" . $total_sales . "\",";
		$data .= "\"" . number_format($total_sph,2) . "\",";
		$data .= "\"" . $total_grade . "\"";
		$data .= "\n";
		
		$q2 = mysql_query("SELECT first,last FROM auth WHERE user = '$user'") or die(mysql_error());
		$n = mysql_fetch_row($q2);
		
		$name = $n[0] . "_" . $n[1];
		$filename = $name . "_Weekly_Timesheets" . ".csv";
	}
	elseif ($type == "daily")
	{
		$header = "Date,Start Time,End Time,Hours,Sales,SPH,Grade";
		
		$qs = mysql_query("SELECT date FROM timesheet WHERE user = '$user' ORDER BY date ASC") or die(mysql_error());
		$start_date = mysql_fetch_row($qs);
		
		$qe = mysql_query("SELECT date FROM timesheet WHERE user = '$user' ORDER BY date DESC") or die(mysql_error());
		$end_date = mysql_fetch_row($qe);
		
		$start_week = date("W", strtotime($start_date[0]));
		$start_year = date("Y", strtotime($start_date[0]));
		$end_week = date("W", strtotime($end_date[0]));
		$end_year = date("Y", strtotime($end_date[0]));
		
		for ($year=$start_year; $year <= $end_year; $year++)
		{
			for ($week=$start_week; $week <= $end_week; $week++)
			{
				for ($day=1; $day <= 7; $day++)
				{
					$date = date('Y-m-d', strtotime($year . "W" . $week . $day));
					
					$q1 = mysql_query("SELECT * FROM timesheet WHERE user = '$user' AND date = '$date'") or die(mysql_error());
					$h = mysql_fetch_assoc($q1);
					if ($h["hours"] == "") { $hours = 0; } else { $hours = $h["hours"]; }
					$total_hours += $hours;
					
					$q2 = mysql_query("SELECT * FROM sales_customers WHERE agent = '$user' AND DATE(approved_timestamp) = '$date' AND status = 'Approved'") or die(mysql_error());
					$sales = mysql_num_rows($q2);
					$total_sales += $sales;
					
					if ($h["start"] == "") { $start = "-"; } else { $start = date("H:i", strtotime($h["start"])); }
					if ($h["end"] == "") { $end = "-"; } else { $end = date("H:i", strtotime($h["end"])); }
					
					$sph = $sales / $hours;
					
					if ($sph < 0.15) { $grade = "D"; } elseif ($sph < 0.2) { $grade = "C"; } elseif ($sph < 0.25) { $grade = "B"; } else { $grade = "A"; }
					
					$data .= "\"" . date("d/m/Y", strtotime($date)) . "\",";
					$data .= "\"" . $start . "\",";
					$data .= "\"" . $end . "\",";
					$data .= "\"" . $hours . "\",";
					$data .= "\"" . $sales . "\",";
					$data .= "\"" . number_format($sph,2) . "\",";
					$data .= "\"" . $grade  . "\"";
					$data .= "\n";
				}
			}
		}
		
		$total_sph = $total_sales / $total_hours;
		
		if ($total_sph < 0.15) { $total_grade = "D"; } elseif ($total_sph < 0.2) { $total_grade = "C"; } elseif ($total_sph < 0.25) { $total_grade = "B"; } else { $total_grade = "A"; }
		
		$data .= "\"" . "Total" . "\",";
		$data .= "\"" . "-" . "\",";
		$data .= "\"" . "-" . "\",";
		$data .= "\"" . $total_hours . "\",";
		$data .= "\"" . $total_sales . "\",";
		$data .= "\"" . number_format($total_sph,2) . "\",";
		$data .= "\"" . $total_grade . "\"";
		$data .= "\n";
		
		$q2 = mysql_query("SELECT first,last FROM auth WHERE user = '$user'") or die(mysql_error());
		$n = mysql_fetch_row($q2);
		
		$name = $n[0] . "_" . $n[1];
		$filename = $name . "_Daily_Timesheets" . ".csv";
	}
}

header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
print "$header\n$data";
?>