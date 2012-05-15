<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$method = $_GET["method"];
$centre = $_GET["centre"];
$date = $_GET["date"];
$week = date("W", strtotime($date));

if ($method == "export_hours")
{
	$header = "Username,Full Name,Dialler Hours";
	$q = mysql_query("SELECT timesheet.user,timesheet.dialler_hours,auth.first,auth.last FROM timesheet,auth WHERE timesheet.date = '$date' AND auth.centre = '$centre' AND timesheet.user = auth.user ORDER BY timesheet.user ASC") or die(mysql_error());
	
	while ($da = mysql_fetch_row($q))
	{
		if ($da[1] == "0.00") { $hours = ""; } else { $hours = $da[1]; }
		
		$data .= $da[0] . ",";
		$data .= $da[2] . " " . $da[3] . ",";
		$data .= $hours . "\n";
	}
	
	$filename = "Dialler_Hours_" . $date . "_" . $centre . ".csv";
	header("Content-type: application/x-msdownload");
	header("Content-Disposition: attachment; filename=$filename");
	header("Pragma: no-cache");
	header("Expires: 0");
	print("$header\n$data");
}
elseif ($method == "import_hours")
{
	exec("dos2unix /var/vericon/accounts/tmp/hours.csv");
	
	$lines = file("/var/vericon/accounts/tmp/hours.csv");
	$i = 0;
	
	foreach ($lines as $row)
	{
		$data = explode(",", $row);
		if ($i > 0)
		{
			mysql_query("UPDATE timesheet SET dialler_hours = '$data[2]' WHERE user = '$data[0]' AND date = '$date'") or die(mysql_error());
		}
		$i++;
	}
	
	unlink("/var/vericon/accounts/tmp/hours.csv");
	
	echo "done";
}
elseif ($method == "export_cancellations")
{
	$header = "Username,Full Name,Cancellations";
	$q = mysql_query("SELECT timesheet.user,auth.first,auth.last FROM timesheet,auth WHERE WEEK(timesheet.date) = '$week' AND auth.centre = '$centre' AND timesheet.user = auth.user GROUP BY timesheet.user ORDER BY timesheet.user ASC") or die(mysql_error());
	
	while ($da = mysql_fetch_row($q))
	{
		$q1 = mysql_query("SELECT cancellations FROM timesheet_canc WHERE user = '$data[0]' AND week = '$week'") or die(mysql_error());
		$c = mysql_fetch_row($q1);
		
		if ($c[0] == "") { $cancellations = ""; } else { $cancellations = $c[0]; }
		
		$data .= $da[0] . ",";
		$data .= $da[1] . " " . $da[2] . ",";
		$data .= $cancellations . "\n";
	}
	
	$year = date("Y", strtotime($date));
	$we = date("Y-m-d", strtotime($year . "W" . $week . "7"));
	$filename = "Sale_Cancellations_WE" . $we . "_" . $centre . ".csv";
	header("Content-type: application/x-msdownload");
	header("Content-Disposition: attachment; filename=$filename");
	header("Pragma: no-cache");
	header("Expires: 0");
	print("$header\n$data");
}
elseif ($method == "import_cancellations")
{
	exec("dos2unix /var/vericon/accounts/tmp/cancellations.csv");
	
	$lines = file("/var/vericon/accounts/tmp/cancellations.csv");
	$i = 0;
	
	foreach ($lines as $row)
	{
		$data = explode(",", $row);
		if ($i > 0)
		{
			$q = mysql_query("SELECT * FROM timesheet_canc WHERE user = '$data[0]' AND week = '$week'") or die(mysql_error());
			
			if (mysql_num_rows($q) == 0)
			{
				mysql_query("INSERT INTO timesheet_canc (user, week, cancellations) VALUES ('$data[0]', '$week', '$data[2]')") or die(mysql_error());
			}
			else
			{
				mysql_query("UPDATE timesheet_canc SET cancellations = '$data[2]' WHERE user = '$data[0]' AND week = '$week'") or die(mysql_error());
			}
		}
		$i++;
	}
	
	unlink("/var/vericon/accounts/tmp/cancellations.csv");
	
	echo "done";
}
?>