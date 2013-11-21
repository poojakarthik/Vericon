<?php
mysql_connect("localhost","vericon","18450be");

$id = $_GET["id"];

if (strlen($id) == 9)
{
	$q = mysql_query("SELECT `centre` FROM `vericon`.`sales_customers` WHERE `id` = '" . mysql_real_escape_string($id) . "'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo $data[0];
}
?>