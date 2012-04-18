<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$q0 = mysql_query("SELECT * FROM auth WHERE user = '$_GET[user]'") or die(mysql_error());
$centre = mysql_fetch_assoc($q0);

if ($centre["access"] != "Admin" || $_GET["user"] == "" || mysql_num_rows($q0) == 0)
{
	
}
elseif ($centre["centre"] == "CC12")
{
	if ($_GET["user"] == "dsad001" || $_GET["user"] == "ddem001")
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
	
	$header = "Sale ID" . "," . "Campaign" . "," . "Type" . "," . "Agent" . "," . "Lead ID";
	
	$q = mysql_query("SELECT * FROM teams WHERE team = '$team'") or die(mysql_error());
	
	while ($team_agent = mysql_fetch_row($q))
	{
		$q1 = mysql_query("SELECT id,campaign,type,agent,lead_id FROM sales_customers WHERE agent = '$team_agent[0]' AND status = 'Approved' AND DATE(approved_timestamp) = '" . date("Y-m-d") . "'") or die(mysql_error());
		
		if (mysql_num_rows($q1) == 0)
		{
			
		}
		else
		{
			while ($sales = mysql_fetch_row($q1))
			{
				$data .= "\"" . $sales[0] . "\",";
				$data .= "\"" . $sales[1] . "\",";
				$data .= "\"" . $sales[2] . "\",";
				$q2 = mysql_query("SELECT first,last FROM auth WHERE user = '$sales[3]'") or die(mysql_error());
				$name = mysql_fetch_row($q2);
				$data .= "\"" . $name[0] . " " . $name[1] . "\",";
				$data .= "\"" . $sales[4] . "\"";
				$data .= "\n";
			}
		}
	}
	
	$date = date("d_m_Y");
	
	$filename = $team . "_" .$centre["centre"] . "_Sales_" . $date . ".csv";
	
	header("Content-type: application/x-msdownload");
	header("Content-Disposition: attachment; filename=$filename");
	header("Pragma: no-cache");
	header("Expires: 0");
	print "$header\n$data";
}
else
{
	$header = "Sale ID" . "," . "Campaign" . "," . "Type" . "," . "Agent" . "," . "Lead ID";
	
	$q1 = mysql_query("SELECT id,campaign,type,agent,lead_id FROM sales_customers WHERE centre = '$centre[centre]' AND status = 'Approved' AND DATE(approved_timestamp) = '" . date("Y-m-d") . "'") or die(mysql_error());
	
	if (mysql_num_rows($q1) == 0)
	{
		
	}
	else
	{
		while ($sales = mysql_fetch_row($q1))
		{
			$data .= "\"" . $sales[0] . "\",";
			$data .= "\"" . $sales[1] . "\",";
			$data .= "\"" . $sales[2] . "\",";
			$q2 = mysql_query("SELECT first,last FROM auth WHERE user = '$sales[3]'") or die(mysql_error());
			$name = mysql_fetch_row($q2);
			$data .= "\"" . $name[0] . " " . $name[1] . "\",";
			$data .= "\"" . $sales[4] . "\"";
			$data .= "\n";
		}
	}
	
	$date = date("d_m_Y");
	
	$filename = $centre["centre"] . "_Sales_" . $date . ".csv";
	
	header("Content-type: application/x-msdownload");
	header("Content-Disposition: attachment; filename=$filename");
	header("Pragma: no-cache");
	header("Expires: 0");
	print "$header\n$data";
}
?>