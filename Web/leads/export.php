<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$centre = $_GET["centre"];

if (substr($centre,0,2) == "CC")
{
	$q = mysql_query("SELECT * FROM leads WHERE centre = '$centre' AND expiry_date >= '" . date("Y-m-d") . "' ORDER BY expiry_date ASC") or die(mysql_error());
	
	while ($da = mysql_fetch_row($q))
	{
		foreach ($da as $row)
		{
			$data .= $row . ",";
		}
		$data = substr_replace($data,"",-1);
		$data .= "\n";
	}
}
elseif ($centre == "All")
{
	$q = mysql_query("SELECT * FROM leads WHERE centre LIKE 'CC%' AND expiry_date >= '" . date("Y-m-d") . "' ORDER BY expiry_date ASC") or die(mysql_error());
	
	while ($da = mysql_fetch_row($q))
	{
		foreach ($da as $row)
		{
			$data .= $row . ",";
		}
		$data = substr_replace($data,"",-1);
		$data .= "\n";
	}
}
elseif ($centre == "Kamal" || $centre == "Rohan" || $centre == "Sanjay")
{
	$q = mysql_query("SELECT * FROM leads WHERE centre = '$centre' AND expiry_date >= '" . date("Y-m-d") . "' ORDER BY expiry_date ASC") or die(mysql_error());
	
	while ($da = mysql_fetch_row($q))
	{
		foreach ($da as $row)
		{
			$data .= $row . ",";
		}
		$data = substr_replace($data,"",-1);
		$data .= "\n";
	}
}
elseif ($centre == "Special")
{
	$q = mysql_query("SELECT * FROM leads WHERE centre NOT LIKE 'CC%' AND expiry_date >= '" . date("Y-m-d") . "' ORDER BY expiry_date ASC") or die(mysql_error());
	
	while ($da = mysql_fetch_row($q))
	{
		foreach ($da as $row)
		{
			$data .= $row . ",";
		}
		$data = substr_replace($data,"",-1);
		$data .= "\n";
	}
}

$header = "Lead ID,Centre,Issue Date,Expiry Date,Packet Expiry,Timestamp Added";

$filename = $centre . "_Running_Data_" . date("Y-m-d") . ".csv";

header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
print "$header\n$data";
?>