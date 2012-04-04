<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$date = date("d.m.Y", strtotime($_GET["date"]));
$team = $_GET["team"];
$centre = $_GET["centre"];

if ($centre == "CC12")
{
	if ($_GET["user"] == "dsad001")
	{
		$team = "Damith";
	}
	elseif ($_GET["user"] == "djen001")
	{
		$team = "Daniel";
	}
	elseif ($_GET["user"] == "ldon001")
	{
		$team = "Liam";
	}
	elseif ($_GET["user"] == "ssha001")
	{
		$team = "Sanu";
	}
	
	$header = "Agent,Centre,Date of Sale,Campaign,Type,Lead ID,Reason";
	
	$q = mysql_query("SELECT * FROM teams WHERE team = '$team'") or die(mysql_error());
	while ($team_agent = mysql_fetch_row($q))
	{
		$q1 = mysql_query("SELECT * FROM qa_customers WHERE agent = '$team_agent[0]' AND status = 'Rejected' AND DATE(sale_timestamp) = '$_GET[date]'") or die(mysql_error());
		if (mysql_num_rows($q1) != 0)
		{
			while ($da = mysql_fetch_row($q1))
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
	}
}
elseif ($centre == "All")
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

if ($centre == "CC12")
{
	$centre = $centre . "_" . $team;
}
$filename = "Rejection_Report_" . $date . "_" . $centre . ".csv";
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
print("$header\n$data");
?>