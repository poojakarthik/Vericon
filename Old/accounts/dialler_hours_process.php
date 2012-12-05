<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];
$centre = $_GET["centre"];
$date = $_GET["date"];
$week = date("W", strtotime($date));

if ($method == "export_hours")
{
	$header = "Username,Full Name,Dialler Hours";
	$q = mysql_query("SELECT user,dialler_hours FROM vericon.timesheet WHERE date = '$date' AND centre = '$centre' ORDER BY user ASC") or die(mysql_error());
	
	while ($da = mysql_fetch_row($q))
	{
		if ($da[1] == "0.00") { $hours = ""; } else { $hours = $da[1]; }
		
		$q0 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$da[0]'") or die(mysql_error());
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
			mysql_query("UPDATE vericon.timesheet SET dialler_hours = '$data[2]' WHERE user = '$data[0]' AND date = '$date'") or die(mysql_error());
		}
		$i++;
	}
	
	unlink("/var/vericon/accounts/tmp/hours.csv");
	
	echo "done";
}
?>