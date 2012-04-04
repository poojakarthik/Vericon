<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$date = $_GET["date"];

$header = "Sale ID,Campaign,Type,Agent,Centre,CLI_1,Plan_1,CLI_2,Plan_2,CLI_3,Plan_3,CLI_4,Plan_4,CLI_5,Plan_5,CLI_6,Plan_6,CLI_7,Plan_7,CLI_8,Plan_8,CLI_9,Plan_9,CLI_10,Plan_10";

$q1 = mysql_query("SELECT id,campaign,type,agent,centre FROM sales_customers WHERE status = 'Approved' AND DATE(timestamp) = '" . $date . "'") or die(mysql_error());

if (mysql_num_rows($q1) != 0)
{
	while ($sales = mysql_fetch_row($q1))
	{
		$data .= "\"" . $sales[0] . "\",";
		$data .= "\"" . $sales[1] . "\",";
		$data .= "\"" . $sales[2] . "\",";
		$q2 = mysql_query("SELECT first,last FROM auth WHERE user = '$sales[3]'") or die(mysql_error());
		$name = mysql_fetch_row($q2);
		$data .= "\"" . $name[0] . " " . $name[1] . "\",";
		$data .= "\"" . $sales[4] . "\",";
		$q3 = mysql_query("SELECT * FROM sales_packages WHERE sid = '$sales[0]'") or die(mysql_error());
		while($packages = mysql_fetch_row($q3))
		{
			$data .= "=\"" . $packages[1] . "\",";
			$data .= "=\"" . $packages[2] . "\",";
		}
		$data .= "\n";
	}
}

$filename = "Sales_Export_" . date("d.m.Y", strtotime($date)) . ".csv";

header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
print "$header\n$data";
?>