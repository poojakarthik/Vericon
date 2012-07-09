<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];
$username = $_GET["username"];
$password = $_GET["password"];
$password2 = $_GET["password2"];
$type = "Welcome";
$status = $_GET["status"];
$first = trim($_GET["first"]);
$last = trim($_GET["last"]);
$alias = trim($_GET["alias"]);
$timestamp = date("Y-m-d H:i:s");

if ($method == "create")
{
	if (strlen(preg_replace("/[^A-Za-z]/", "", $last)) == 2)
	{
		$user1 = strtolower(substr(preg_replace("/[^A-Za-z]/", "", $first),0,2) . substr(preg_replace("/[^A-Za-z]/", "", $last),0,2));
	}
	else
	{
		$user1 = strtolower(substr(preg_replace("/[^A-Za-z]/", "", $first),0,1) . substr(preg_replace("/[^A-Za-z]/", "", $last),0,3));
	}
	
	if ($first == "" || $last == "")
	{
		echo "Please enter a first and last name";
	}
	elseif (strlen($user1) != 4)
	{
		echo "Invalid first and last name. Must contain at least 2 letters in each";
	}
	elseif ($password != $password2)
	{
		echo "Passwords do not match";
	}
	elseif ($alias == "")
	{
		echo "Please enter an alias";
	}
	else
	{
		$q = mysql_query("SELECT COUNT(user) FROM vericon.auth WHERE user LIKE '$user1%'");
		$num = mysql_fetch_row($q);
		
		$username = $user1 . str_pad(($num[0]+1),3,"0",STR_PAD_LEFT);

		mysql_query("INSERT INTO vericon.auth (user, pass, type, status, first, last, alias, timestamp) VALUES ('$username' ,'" . md5($password) . "', '$type', 'Enabled', '" . mysql_real_escape_string($first) . "', '" . mysql_real_escape_string($last) . "', '" . mysql_real_escape_string($alias) . "', '$timestamp')");
		
		$q1 = mysql_query("SELECT pages FROM vericon.portals_template WHERE user = 'Welcome'") or die(mysql_error());
		$pages = mysql_fetch_row($q1);
		
		mysql_query("INSERT INTO vericon.portals_access (user, pages) VALUES ('$username', '" . mysql_real_escape_string($pages[0]) . "')") or die(mysql_error());
		
		echo "createdYou have successfully created the user!<br><br>Username: <b>$username</b>";
	}
}
elseif ($method == "modify")
{
	if ($alias == "")
	{
		echo "Please enter an alias!";
	}
	else
	{
		mysql_query("UPDATE vericon.auth SET alias = '" . mysql_real_escape_string($alias) . "' WHERE user = '$username' LIMIT 1");
		
		echo "modified";
	}
}
elseif ($method == "modify_pw")
{
	if ($password != $password2)
	{
		echo "Passwords do not match";
	}
	else
	{
		mysql_query("UPDATE vericon.auth SET pass = '" . md5($password) . "' WHERE user = '$username' LIMIT 1");
		
		echo "modified";
	}
}
elseif ($method == "disable")
{
	mysql_query("UPDATE vericon.auth SET status = 'Disabled' WHERE user = '$username' LIMIT 1");
}
elseif ($method == "enable")
{
	mysql_query("UPDATE vericon.auth SET status = 'Enabled' WHERE user = '$username' LIMIT 1");
}
elseif ($method == "search")
{
	$term = explode(" ",$_GET["term"]);
	
	$q = mysql_query("SELECT * FROM vericon.auth WHERE type LIKE '%Welcome%' AND (first LIKE '" . mysql_real_escape_string($term[0]) . "%' AND last LIKE '" . mysql_real_escape_string($term[1]) . "%')") or die(mysql_error());
	echo '[';
	while ($data = mysql_fetch_assoc($q))
	{
		$d[] = "{ \"id\": \"" . $data["user"] . "\", \"value\": \"" . $data["first"] . " " . $data["last"] . " (" . $data["user"] . ")\" }";
	}
	echo implode(", ",$d);
	echo ']';
}
elseif ($method == "first")
{
	$user = $_GET["user"];
	$q = mysql_query("SELECT first FROM vericon.auth WHERE user = '$user'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo $data[0];
}
elseif ($method == "last")
{
	$user = $_GET["user"];
	$q = mysql_query("SELECT last FROM vericon.auth WHERE user = '$user'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo $data[0];
}
elseif ($method == "alias")
{
	$user = $_GET["user"];
	$q = mysql_query("SELECT alias FROM vericon.auth WHERE user = '$user'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo $data[0];
}
?>