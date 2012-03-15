<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

function CheckAccess()
{
	$q = mysql_query("SELECT * FROM allowedip") or die(mysql_error());
	
	while ($iplist = mysql_fetch_assoc($q))
	{
		$allowedip[$iplist['IP']] = $iplist['status'];
	}
  	$ip = $_SERVER['REMOTE_ADDR'];
  	return ($allowedip[$ip]);
}

// check if username and password entered
if($_POST["password"] == "" || $_POST["username"] == ""){ die(header("Location: ../index.php?attempt=fail")); }

// check if username and password correct
$q = mysql_query("SELECT type FROM auth WHERE user = '" . mysql_escape_string($_POST["username"]) . "' AND pass = '" . md5($_POST['password']) . "'") 
  or die(mysql_error());

if(mysql_num_rows($q) != 1){die(header("Location: ../index.php?attempt=fail"));}

// check if IP allowed - Log unauthorised
if (!CheckAccess())
{
	mysql_query("INSERT INTO log_unauthorised (ip,user) VALUES ('$_SERVER[REMOTE_ADDR]','" . mysql_escape_string($_POST["username"]) . "')");
	header("Location: ../index.php?attempt=badip");
	exit;
}

// check if user already logged in
$q2 = mysql_query("SELECT * FROM currentuser WHERE user = '" . mysql_escape_string($_POST["username"]) . "'") or die(mysql_error());

if(mysql_num_rows($q2) != 0){
	mysql_query("INSERT INTO log_al (ip,user) VALUES ('$_SERVER[REMOTE_ADDR]','" . mysql_escape_string($_POST["username"]) . "')");
	die(header("Location: ../index.php?attempt=al"));}

// log user in
$d = mysql_fetch_row($q);

$hash = hash('whirlpool', rand());

mysql_query("INSERT INTO currentuser (hash, user, type, timestamp) VALUES ('$hash','" . mysql_escape_string($_POST["username"]) . "', '$d[0]', NOW())") or die(mysql_error());

mysql_query("INSERT INTO log_login (ip,user) VALUES ('$_SERVER[REMOTE_ADDR]','" . mysql_escape_string($_POST["username"]) . "')");

setcookie("hash", $hash, time()+(86400),'/');

$dept = strtolower($d[0]);

header("Location: ../main.php");
exit;
?>