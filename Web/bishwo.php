<?php
mysql_connect("localhost","vericon","18450be");

$date = $_GET["date"];
$header = "Centre,CLI";
$body = "";
$body_tmp = "";

if ($date == "")
{
	echo "Please insert a date";
	exit;
}

$lead = mysql_query("SELECT `centre`, `lead_id` FROM `vericon`.`sales_customers` WHERE `status` = 'Approved' AND DATE(`approved_timestamp`) = '" . mysql_real_escape_string($date) . "'") or die(mysql_error());
while ($data1 = mysql_fetch_row($lead))
{
	$body_tmp .= '"' . $data1[0] . '",';
	$body_tmp .= '"' . $data1[1] . '"';
	$body_tmp .= "\n";
}

$mobile = mysql_query("SELECT `centre`, `mobile` FROM `vericon`.`sales_customers` WHERE `status` = 'Approved' AND DATE(`approved_timestamp`) = '" . mysql_real_escape_string($date) . "' AND `mobile` != 'N/A'") or die(mysql_error());
while ($data2 = mysql_fetch_row($mobile))
{
	$body_tmp .= '"' . $data2[0] . '",';
	$body_tmp .= '"' . $data2[1] . '"';
	$body_tmp .= "\n";
}

$packages = mysql_query("SELECT `sales_customers`.`centre`, `sales_packages`.`cli` FROM `vericon`.`sales_customers`, `vericon`.`sales_packages` WHERE `sales_customers`.`status` = 'Approved' AND DATE(`sales_customers`.`approved_timestamp`) = '" . mysql_real_escape_string($date) . "' AND `sales_customers`.`id` = `sales_packages`.`sid`") or die(mysql_error());
while ($data3 = mysql_fetch_row($packages))
{
	$body_tmp .= '"' . $data3[0] . '",';
	$body_tmp .= '"' . $data3[1] . '"';
	$body_tmp .= "\n";
}

$body_tmp = explode("\n", $body_tmp);
$body_tmp = array_unique($body_tmp);
$body = implode("\n", $body_tmp);
$file = "CLI_Report_" . $date . ".csv";

header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$file");
header("Pragma: no-cache");
header("Expires: 0");
print("$header\n$body");
?>