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
		exit;
	}
	
	$q = mysql_query("SELECT * FROM sales_customers WHERE id = '" . mysql_escape_string($id) . "'") or die(mysql_error());
	$data = mysql_fetch_assoc($q);
	$num = mysql_num_rows($q);
	
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
	elseif ($data["status"] == "Hold")
	{
		echo "3";
		$status = "On Hold";
	}
	elseif (strtotime(date("Y-m-d", strtotime($data["timestamp"])) . "+1 week") < strtotime(date("Y-m-d")) && $data["status"] != "Rework")
	{
		echo "4";
		$status = "ID Expired";
	}
	else
	{
		echo "1";
		$status = "Transferred";
	}
	
	mysql_query("INSERT INTO log_tpv_inbound (entered_id,centre,status) VALUES ('" . mysql_escape_string($log_id) . "','$data[centre]','$status')") or die(mysql_error());
}
elseif ($_GET["ext"] != "")
{
	$id = $_GET["ext"];
	
	$q = mysql_query("SELECT * FROM vicidial_pool WHERE number = '$id'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	mysql_query("INSERT INTO log_tpv_inbound (entered_id,actual_id,centre,status) VALUES ('" . mysql_escape_string($id) . "','$data[2]','$data[3]','Transferred')") or die(mysql_error());
	
	echo "1";
}
?>