<?php
mysql_connect("localhost","vericon","18450be");

$id = $_GET["id"];

if (strlen($id) == 9) {
	mysql_query("DELETE FROM `vericon`.`vicidial_live` WHERE `id` = '" . mysql_real_escape_string($id) . "'") or die(mysql_error());
}
?>