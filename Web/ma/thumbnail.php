<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$id = $_GET["id"];

$q = mysql_query("SELECT thumbnail FROM tutorials WHERE id = '$id'") or die(mysql_error());
$data = mysql_fetch_row($q);

$image = $data[0];
header("Content-type: image/gif");

echo $image;