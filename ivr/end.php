<?php

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$id = $_GET["sale_id"];

mysql_query("DELETE FROM vicidial_live WHERE sale_id = '" . mysql_escape_string($id) . "' LIMIT 1") or die(mysql_error());

?>