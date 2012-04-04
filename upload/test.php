<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$filePath=$PATH.'/home/odai/rec/'; # Specify the path you want to look in. 
$dir = opendir($filePath); # Open the path
while ($file = readdir($dir))
{
	if (eregi("\.gsm",$file))
	{
		$init = substr($file,0,10);
		$q = mysql_query("SELECT sales_customers.lead_id,sales_customers.status FROM sales_packages,sales_customers WHERE sales_packages.cli = '$init' AND id = sales_packages.sid ORDER BY sales_customers.approved_timestamp DESC") or die(mysql_error());
		$lead_id = mysql_fetch_row($q);
		if (mysql_num_rows($q) != 0)
		{
			echo $file . " => " . $lead_id[1] . "<br>";
		}
		else
		{
			echo $file . " => No Record Found!<br>";
		}
	}
}
?>