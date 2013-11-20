<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

if ($_GET["sale_id"] != "")
{
	$id = $_GET["sale_id"];
	$log_id = $_GET["sale_id"];
	
	if (strlen($id) != 9)
	{
		echo "0";
		$status = "Invalid ID";
	}
	else
	{
		$q = mysql_query("SELECT * FROM sales_customers WHERE id = '" . mysql_escape_string($id) . "'") or die(mysql_error());
		$data = mysql_fetch_assoc($q);
		$num = mysql_num_rows($q);
		
		$state = str_replace("_", "", substr($data["physical"], 2, 3));
		
		$q1 = mysql_query("SELECT * FROM `vicidial_live` WHERE `id` = '" . mysql_real_escape_string($data["id"]) . "'") or die(mysql_error());
		
		if ($num == 0)
		{
			echo "0";
			$status = "Invalid ID";
			exit;
		}
		elseif ($data["status"] == "Approved")
		{
			echo "2";
			$status = "Already Approved";
		}
		elseif (mysql_num_rows($q1) != 0)
		{
			echo "3";
			$status = "Call Active";
		}
		elseif (strtotime(date("Y-m-d", strtotime($data["timestamp"])) . "+1 week") < strtotime(date("Y-m-d")) && $data["status"] != "Rework")
		{
			echo "4";
			$status = "ID Expired";
		}
		elseif (($state == "VIC" || $state == "NSW" || $state == "TAS") && date('H') >= 20)
		{
			echo "5";
			$status = "Restricted";
		}
		elseif ($state == "QLD" && date('H') >= 21)
		{
			echo "5";
			$status = "Restricted";
		}
		elseif ($state == "NZ" && date('H') >= 18)
		{
			echo "5";
			$status = "Restricted";
		}
		else
		{
			mysql_query("INSERT INTO `vicidial_live` (`id`, `timestamp`) VALUES ('" . mysql_real_escape_string($log_id) . "', NOW())") or die(mysql_error());
			
			echo "1";
			$status = "Transferred";
		}
	}
	
	mysql_query("INSERT INTO `vicidial_log` (`timestamp`, `input`, `result`) VALUES (NOW(), '" . mysql_real_escape_string($log_id) . "', '" . mysql_real_escape_string($status) . "')") or die(mysql_error());
}
elseif ($_GET["ext"] != "")
{
	echo "1";
}
else
{
	echo "0";
}
?>