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
elseif ($method == "agent")
{
	$id = $_GET["id"];
	
	$q = mysql_query("SELECT auth.first, auth.last FROM vericon.auth, vericon.sales_customers WHERE sales_customers.id = '$id' AND sales_customers.agent = auth.user") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo $data[0] . " " . $data[1];
}
elseif ($method == "sale_date")
{
	$id = $_GET["id"];
	
	$q = mysql_query("SELECT sale_timestamp FROM vericon.qa_customers WHERE id = '$id'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo date("d/m/Y", strtotime($data[0]));
}
?>