<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$date = $_GET["date"];
$centre = $_GET["centre"];

$header = "Agent Name,Start Time,End Time,Hours,Sales,Bonus";

$q = mysql_query("SELECT * FROM auth,timesheet WHERE auth.centre = '$centre' AND timesheet.date = '$date' AND auth.user = timesheet.user ORDER BY timesheet.user ASC") or die(mysql_error());

if (mysql_num_rows($q) == 0)
{
	
}
else
{
	while ($da = mysql_fetch_assoc($q))
	{
		$q1 = mysql_query("SELECT * FROM sales_customers WHERE agent = '$data[user]' AND status = 'Approved' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
		$sales = mysql_num_rows($q1);
		
		$data .= "\"" . $da["first"] . " " . $da["last"] . "\",";
		$data .= "\"" . date("H:i", strtotime($da["start"])) . "\",";
		$data .= "\"" . date("H:i", strtotime($da["end"])) . "\",";
		$data .= "\"" . $da["hours"] . "\",";
		$data .= "\"" . $sales . "\",";
		$data .= "\"" . $da["bonus"] . "\"";
		$data .= "\n";
		
		$total_hours += $da["hours"];
		$total_sales += $sales;
		$total_bonus += $da["bonus"];
	}
	
	$data .= "\"" . "Total" . "\",";
	$data .= "\"" . "\",";
	$data .= "\"" . "\",";
	$data .= "\"" . $total_hours . "\",";
	$data .= "\"" . $total_sales . "\",";
	$data .= "\"" . $total_bonus . "\"";
	$data .= "\n";
}

$date = date("d-m-Y", strtotime($date));

$filename = $centre . "_Timesheet_" . $date . ".csv";

header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
print "$header\n$data";
?>