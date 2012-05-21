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
	$q = mysql_query("SELECT user,dialler_hours FROM timesheet WHERE date = '$date' AND centre = '$centre' ORDER BY user ASC") or die(mysql_error());
	
	while ($da = mysql_fetch_row($q))
	{
		if ($da[1] == "0.00") { $hours = ""; } else { $hours = $da[1]; }
		
		$q0 = mysql_query("SELECT first,last FROM auth WHERE user = '$da[0]'") or die(mysql_error());
		$user = mysql_fetch_row($q0);
		
		$data .= $da[0] . ",";
		$data .= $user[0] . " " . $user[1] . ",";
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
	$q = mysql_query("SELECT user FROM timesheet WHERE WEEK(date) = '$week' AND centre = '$centre' GROUP BY user ORDER BY user ASC") or die(mysql_error());
	
	while ($da = mysql_fetch_row($q))
	{
		$q0 = mysql_query("SELECT first,last FROM auth WHERE user = '$da[0]'") or die(mysql_error());
		$user = mysql_fetch_row($q0);
		
		$q1 = mysql_query("SELECT cancellations FROM timesheet_other WHERE user = '$da[0]' AND week = '$week'") or die(mysql_error());
		$c = mysql_fetch_row($q1);
		
		if ($c[0] == "") { $cancellations = "-"; } else { $cancellations = $c[0]; }
		
		$data .= $da[0] . ",";
		$data .= $user[0] . " " . $user[1] . ",";
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
			$q = mysql_query("SELECT * FROM timesheet_other WHERE user = '$data[0]' AND week = '$week'") or die(mysql_error());
			
			if (mysql_num_rows($q) == 0)
			{
				mysql_query("INSERT INTO timesheet_other (user, week, cancellations) VALUES ('$data[0]', '$week', '$data[2]')") or die(mysql_error());
			}
			else
			{
				mysql_query("UPDATE timesheet_other SET cancellations = '$data[2]' WHERE user = '$data[0]' AND week = '$week'") or die(mysql_error());
			}
		}
		$i++;
	}
	
	unlink("/var/vericon/accounts/tmp/cancellations.csv");
	
	echo "done";
}
?>