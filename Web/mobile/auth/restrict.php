<?php
mysql_connect('localhost','mobile','18450be');
mysql_select_db('mobile');

$hash = $_GET["token"];

$q = mysql_query("SELECT * FROM current WHERE hash = '$hash'") or die(mysql_error());

if (mysql_num_rows($q) == 0)
{
   header("Location: index.php");
}
?>