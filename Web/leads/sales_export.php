<?php
mysql_connect('localhost','vericon','18450be');

$date = $_GET["date"];

$header = "Sale ID,Centre,Campaign,Type,Sale Type,CLI,Plan";

$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE status = 'Approved' AND DATE(approved_timestamp) = '" . $date . "' ORDER BY centre,id ASC") or die(mysql_error());

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
	
	$body .= '"' . $data["id"] . '",';
	$body .= '"' . $data["centre"] . '",';
	$body .= '"' . $data["campaign"] . '",';
	$body .= '"' . $data["type"] . '",';
	$body .= '"' . $b_type . '",';
	$q4 = mysql_query("SELECT * FROM vericon.sales_packages WHERE sid = '$data[id]' ORDER BY plan DESC") or die(mysql_error());
	while ($pack = mysql_fetch_assoc($q4))
	{
		$body .= '="' . $pack["cli"] . '",';
		$body .= '"' . $pack["plan"] . '",';
	}
	$body .= "\n";
}

$filename = "Sale_Report_" . $date . ".csv";
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
print("$header\n$body");
?>