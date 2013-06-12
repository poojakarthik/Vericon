<?php
mysql_connect('localhost','mobile','18450be');
mysql_select_db('mobile');

$user = $_GET["user"];
$hash = hash('whirlpool', rand());

$q = mysql_query("SELECT * FROM current WHERE user = '$user'") or die(mysql_error());

if (mysql_num_rows($q) == 0)
{
   mysql_query("INSERT INTO current (user,hash) VALUES ('$user', '$hash')") or die(mysql_error());
}
else
{
   mysql_query("UPDATE current SET hash = '$hash' WHERE user = '$user' LIMIT 1") or die(mysql_error());
}

echo $hash;
?>