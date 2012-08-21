<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];

if ($method == "notes")
{
	$id = $_GET["id"];
	
	$q = mysql_query("SELECT rejection_reason, verifier, timestamp FROM vericon.qa_customers WHERE id = '$id'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	$q1 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$data[1]'") or die(mysql_error());
	$verifier = mysql_fetch_row($q1);
	
	echo "----- " . date("d/m/Y H:i:s", strtotime($data[2])) . " - " . $verifier[0] . " " . $verifier[1] . " -----" . "\n";
	echo $data[0] . "\n";
}
elseif ($method == "export")
{
	$centre = $_GET["centre"];
	$date = $_GET["date"];
	$week = date("W", strtotime($date));
	$year = date("Y", strtotime($date));
	$date1 = date("Y-m-d", strtotime($year . "W" . $week . "1"));
	$date2 = date("Y-m-d", strtotime($year . "W" . $week . "7"));	
	
	$header = "Sale ID,Status,Centre,Agent Name,Date of Sale,Campaign,Type,Plans,Account Type,Rejection Reason";
	
	if ($centre == "All")
	{
		$q = mysql_query("SELECT * FROM vericon.qa_customers WHERE (status = 'Rejected' OR status = 'Rework') AND DATE(sale_timestamp) BETWEEN '$date1' AND '$date2' ORDER BY centre,id ASC") or die(mysql_error());
	}
	else
	{
		$q = mysql_query("SELECT * FROM vericon.qa_customers WHERE (status = 'Rejected' OR status = 'Rework') AND centre = '$centre' AND DATE(sale_timestamp) BETWEEN '$date1' AND '$date2' ORDER BY id ASC") or die(mysql_error());
	}
	
	while ($data = mysql_fetch_assoc($q))
	{
		$q1 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$data[agent]'") or die(mysql_error());
		$agent = mysql_fetch_row($q1);
		
		$q2 = mysql_query("SELECT id FROM vericon.campaigns WHERE campaign = '" . mysql_real_escape_string($data["campaign"]) . "'") or die(mysql_error());
		$c = mysql_fetch_row($q2);
		$campaign_id = $c[0];
		
		$p_i = 0;
		$a_i = 0;
		$b_i = 0;
		$p = 1;
		$a = 1;
		$p_packages = array();
		$a_packages = array();
		$b_packages = array();
		$p_cli = array();
		$p_plan = array();
		$a_cli = array();
		$a_plan = array();
		
		$q2 = mysql_query("SELECT * FROM vericon.sales_packages WHERE sid = '$data[id]' ORDER BY plan DESC") or die(mysql_error());
		while ($pack = mysql_fetch_assoc($q2))
		{
			$q3 = mysql_query("SELECT * FROM vericon.plan_matrix WHERE id = '$pack[plan]' AND campaign = '" . mysql_real_escape_string($campaign_id) . "'") or die(mysql_error());
			$da = mysql_fetch_assoc($q3);
			if ($da["type"] == "PSTN")
			{
				$p_packages[$p_i] = $pack["cli"] . "," . $da["s_id"];
				$p_i++;
			}
			elseif ($da["type"] == "ADSL Metro" || $da["type"] == "ADSL Regional")
			{
				$a_packages[$a_i] = $pack["cli"] . "," . $da["s_id"];
				$a_i++;
			}
			elseif ($da["type"] == "Bundle")
			{
				$b_packages[$b_i] = $pack["cli"] . "," . $da["s_id"];
				$b_i++;
			}
		}
		
		if ($b_i >= 1)
		{
			foreach ($b_packages as $row)
			{
				$package = explode(",", $row);
				$p_cli[$p] = $package[0];
				$a_cli[$a] = "A" . substr($package[0],1);
				$p_plan[$p] = $package[1];
				$a_plan[$a] = $package[2];
				$p++;
				$a++;
			}
		}
		
		if ($p_i >= 1)
		{
			foreach ($p_packages as $row)
			{
				$package = explode(",", $row);
				$p_cli[$p] = $package[0];
				$p_plan[$p] = $package[1];
				$p++;
			}
		}
		
		if ($a_i >= 1)
		{
			foreach ($a_packages as $row)
			{
				$package = explode(",", $row);
				$a_cli[$a] = "A" . substr($package[0],1);
				$a_plan[$a] = $package[1];
				$a++;
			}
		}
		
		$b_type = "PSTN";
		
		if ($a_i >= 1)
		{
			$b_type = "ADSL";
		}
		
		if ($b_i >= 1)
		{
			$b_type = "ABUNDLE";
		}
		
		if ($data["status"] == "Rejected") { $status = "In-House"; } else { $status = "Rework"; }
		
		$body .= '"' . $data["id"] . '",';
		$body .= '"' . $status . '",';
		$body .= '"' . $data["centre"] . '",';
		$body .= '"' . $agent[0] . " " . $agent[1] . '",';
		$body .= '"' . date("d/m/Y", strtotime($data["sale_timestamp"])) . '",';
		$body .= '"' . $data["campaign"] . '",';
		$body .= '"' . $data["type"] . '",';
		$body .= '"';
		for ($i = 1; $i < $p; $i++) { $body .= "PLAN $i. $p_plan[$i]\n"; }
		for ($i = 1; $i < $a; $i++) { $body .= "APLAN $i. $a_plan[$i]\n"; }
		$body = substr($body,0,-2);
		$body .= '",';
		$body .= '"' . $b_type . '",';
		$body .= '"' . $data["rejection_reason"] . '",';
		$body .= "\n";
	}
	
	$filename = $centre . "_Rejections_" . $date1 . "_to_" . $date2 . ".csv";
	header("Content-type: application/x-msdownload");
	header("Content-Disposition: attachment; filename=$filename");
	header("Pragma: no-cache");
	header("Expires: 0");
	print("$header\n$body");
}
?>