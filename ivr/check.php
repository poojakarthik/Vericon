<?php

$id = $_GET["sale_id"];
$log_id = $_GET["sale_id"];

if (strlen($id) != 9)
{
	echo "0";
	$status = "Invalid ID";
	exit;
}

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$q = mysql_query("SELECT * FROM sales_customers WHERE id = '" . mysql_escape_string($id) . "'") or die(mysql_error());
$data = mysql_fetch_assoc($q);
$num = mysql_num_rows($q);

$q2 = mysql_query("SELECT * FROM vicidial_live WHERE sale_id = '$data[id]'") or die(mysql_error());
$num2 = mysql_num_rows($q2);

// public holiday restriction
if (substr($data["physical"],0,2) == "GA")
{
	$q3 = mysql_query("SELECT state FROM gnaf WHERE address_detail_pid = '$data[physical]'") or die(mysql_error());
	$state = mysql_fetch_row($q3);
}
else
{
	$q3 = mysql_query("SELECT state FROM address WHERE id = '$data[physical]'") or die(mysql_error());
	$state = mysql_fetch_row($q3);
}

if ($num == 0)
{
	echo "0";
	$status = "Invalid ID";
	exit;
}
/*elseif ($state[0] == "VIC" || $state[0] == "ACT" || $state[0] == "TAS" || $state[0] == "SA")
{
	echo "0";
	$status = "Public Holiday";
	exit;
}*/
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
elseif (strtotime(date("Y-m-d", strtotime($data["timestamp"])) . "+1 week") < strtotime(date("Y-m-d")))
{
	echo "4";
	$status = "ID Expired";
}
/*elseif ($num2 != 0)
{
	echo "5";
	$status = "ID Locked";
}*/
else
{
	echo "1";
	$status = "Transferred";
}

mysql_query("INSERT INTO log_tpv_inbound (entered_id,centre,status) VALUES ('" . mysql_escape_string($log_id) . "','$data[centre]','$status')") or die(mysql_error());

?>