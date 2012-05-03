<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$date = date("d.m.Y", strtotime($_GET["date"]));
$centre = $_GET["centre"];

if ($centre == "All")
{
	$header = "Agent,Centre,Date of Sale,Campaign,Type,Lead ID,Reason";

	$q = mysql_query("SELECT * FROM qa_customers WHERE status = 'Rejected' AND DATE(sale_timestamp) = '$_GET[date]'") or die(mysql_error());
	
	while ($da = mysql_fetch_assoc($q))
	{
		$data .= "\"" . $da["agent"] . "\",";
		$data .= "\"" . $da["centre"] . "\",";
		$data .= "\"" . date("d/m/Y", strtotime($da["sale_timestamp"])) . "\",";
		$data .= "\"" . $da["campaign"] . "\",";
		$data .= "\"" . $da["type"] . "\",";
		$data .= "=\"" . $da["lead_id"] . "\",";
		$data .= "\"" . $da["rejection_reason"] . "\"\n";
	}
}
else
{
	$header = "Agent,Centre,Date of Sale,Campaign,Type,Lead ID,Reason";

	$q = mysql_query("SELECT * FROM qa_customers WHERE centre = '$centre' AND status = 'Rejected' AND DATE(sale_timestamp) = '$_GET[date]'") or die(mysql_error());
	
	while ($da = mysql_fetch_assoc($q))
	{
		$data .= "\"" . $da["agent"] . "\",";
		$data .= "\"" . $da["centre"] . "\",";
		$data .= "\"" . date("d/m/Y", strtotime($da["sale_timestamp"])) . "\",";
		$data .= "\"" . $da["campaign"] . "\",";
		$data .= "\"" . $da["type"] . "\",";
		$data .= "=\"" . $da["lead_id"] . "\",";
		$data .= "\"" . $da["rejection_reason"] . "\"";
		$date .= "\n";
	}
}

$filename = "Rejection_Report_" . $date . "_" . $centre . ".csv";
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
print("$header\n$data");
?>