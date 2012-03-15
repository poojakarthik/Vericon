<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$hash = $_COOKIE["hash"];;

if($hash == ""){ die(header("Location: ../index.php")); }

mysql_query("DELETE FROM currentuser WHERE hash = '$hash' LIMIT 1")
or die(mysql_error());

setcookie("hash", "", time()-86400);

header("Location: ../index.php");
exit;
?>