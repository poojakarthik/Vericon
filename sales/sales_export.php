<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$q0 = mysql_query("SELECT * FROM auth WHERE user = '$_GET[user]'") or die(mysql_error());
$centre = mysql_fetch_assoc($q0);

if ($centre["access"] != "Admin" || $_GET["user"] == "" || mysql_num_rows($q0) == 0)
{
	
}
else
{
	$header = "Sale ID" . "\t" . "Campaign" . "\t" . "Type" . "\t" . "Agent" . "\t" . "Lead ID" . "\t";
	
	$q1 = mysql_query("SELECT id,campaign,type,agent,lead_id FROM sales_customers WHERE centre = '$centre[centre]' AND status = 'Approved' AND DATE(timestamp) = '" . date("Y-m-d") . "'") or die(mysql_error());
	
	if (mysql_num_rows($q1) == 0)
	{
		
	}
	else
	{
		while ($sales = mysql_fetch_row($q1))
		{
			$data .= $sales[0] . "\t";
			$data .= $sales[1] . "\t";
			$data .= $sales[2] . "\t";
			$q2 = mysql_query("SELECT first,last FROM auth WHERE user = '$sales[3]'") or die(mysql_error());
			$name = mysql_fetch_row($q2);
			$data .= $name[0] . " " . $name[1] . "\t";
			$data .= $sales[4] . "\t";
			$data .= "\n";
		}
	}
	
	$date = date("d_m_Y");
	
	$filename = $centre["centre"] . "_Sales_" . $date . ".xls";
	
	header("Content-type: application/x-msdownload");
	header("Content-Disposition: attachment; filename=$filename");
	header("Pragma: no-cache");
	header("Expires: 0");
	print "$header\n$data";
}
?>