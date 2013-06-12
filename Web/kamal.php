<?php
mysql_connect("localhost","vericon","18450be");

$date = $_GET["date"];
$header = "Lead,Centre,Agent,India Feedback, Melbourne Feedback";
$body = "";

if ($date == "")
{
	echo "Please insert a date";
	exit;
}

$lead = mysql_query("SELECT `sales_customers`.`lead_id`, `sales_customers`.`centre`, CONCAT(`auth`.`first`,' ',`auth`.`last`) FROM `vericon`.`sales_customers`, `vericon`.`auth` WHERE `sales_customers`.`status` = 'Approved' AND `sales_customers`.`industry` = 'SELF' AND DATE(`sales_customers`.`approved_timestamp`) = '" . mysql_real_escape_string($date) . "' AND `sales_customers`.`agent` = `auth`.`user` ORDER BY `sales_customers`.`centre`, `sales_customers`.`agent` ASC") or die(mysql_error());
while ($data1 = mysql_fetch_row($lead))
{
	$body .= '"' . $data1[0] . '",';
	$body .= '"' . $data1[1] . '",';
	$body .= '"' . $data1[2] . '",';
	$body .= '"' . '",';
	$body .= '"' . '"';
	$body .= "\n";
}

$file = "Quality_Report_" . $date . ".csv";

header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$file");
header("Pragma: no-cache");
header("Expires: 0");
print("$header\n$body");
?>