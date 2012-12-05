<?php
mysql_connect('localhost','mobile','18450be');
mysql_select_db('mobile');

$q = mysql_query("SELECT * FROM auth WHERE user = '" . mysql_escape_string($_GET["user"]) . "' AND pass = '" . md5($_GET['pass']) . "'") or die(mysql_error());
if(mysql_num_rows($q) != 1)
{
   echo "Invalid Username or Password!";
}
else
{
   echo "valid";
}
?>