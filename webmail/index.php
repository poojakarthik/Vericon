<?php

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$user = $_GET["u"];

if ($user == "")
{
	header("Location: ../index.php");
	exit;
}

$q = mysql_query("SELECT * FROM auth WHERE user = '$user'") or die(mysql_error());
$data = mysql_fetch_assoc($q);

$username = strtolower($data["first"]) . strtolower(substr($data["last"],0,1));
$password = strtolower($data["first"]) . "pass";

$link = "http://mail.telgroup.com.au/zimbra/?loginOp=login&username=" . $username . "&password=" . $password . "&client=preferred";
header("Location: $link");

?>