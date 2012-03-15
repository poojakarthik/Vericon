<?php

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$id = $_GET["sale_id"];

mysql_query("INSERT INTO vicidial_live (sale_id) VALUES ('" . mysql_escape_string($id) . "')") or die(mysql_error());

?>