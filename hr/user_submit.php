<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$method = $_GET["method"];

$username = $_GET["username"];
$password = $_GET["password"];
$password2 = $_GET["password2"];
$type = "Self";
$access = "Agent";
$designation = "Probation";
$status = $_GET["status"];
$first = strtoupper(substr($_GET["first"],0,1)) . strtolower(substr($_GET["first"],1));
$last = strtoupper(substr($_GET["last"],0,1)) . strtolower(substr($_GET["last"],1));
$centre = $_GET["centre"];
$alias = strtoupper(substr($_GET["alias"],0,1)) . strtolower(substr($_GET["alias"],1));
	
if ($method == "create")
{
	if ($first == "" || $last == "")
	{
		echo "Please enter a first and last name";
	}
	elseif ($password != $password2)
	{
		echo "Passwords do not match";
	}
	elseif ($centre == "")
	{
		echo "Please select a centre";
	}
	elseif ($alias == "")
	{
		echo "Please enter an alias";
	}
	else
	{
		if (strlen($last) == 2)
		{
			$user1 = strtolower(substr($first,0,2) . substr($last,0,2));
		}
		else
		{
			$user1 = strtolower(substr($first,0,1) . substr($last,0,3));
		}
		
		$q = mysql_query("SELECT COUNT(user) FROM `auth` WHERE `user` LIKE '$user1%'");
		$num = mysql_fetch_row($q);
		
		$username = $user1 . str_pad(($num[0]+1),3,"0",STR_PAD_LEFT);

		mysql_query("INSERT INTO `auth` (`user`,`pass`,`type`,`access`,`status`,`first`,`last`,`centre`,`alias`) VALUES ('$username','" . md5($password) . "','$type','$access','Enabled','" . mysql_escape_string($first) . "','" . mysql_escape_string($last) . "','$centre','" . mysql_escape_string($alias) . "')");
		
		mysql_query("INSERT INTO timesheet_designation (user,designation) VALUES ('$username','$designation')") or die(mysql_error());
		
		echo "createdYou have successfully created the user!<br><br>Username: <b>$username</b>";
	}
}
elseif ($method == "modify")
{
	if ($alias == "")
	{
		echo "Please enter an alias!";
	}
	elseif ($password != $password2)
	{
		echo "Passwords do not match!";
	}
	else
	{
		mysql_query("UPDATE auth SET pass = '" .  md5($password) . "', alias = '" . mysql_escape_string($alias) . "', centre = '$centre' WHERE user = '$username' LIMIT 1");
		echo "modified";
	}
}
elseif ($method == "disable")
{
	mysql_query("UPDATE auth SET status = 'Disabled' WHERE user = '$username' LIMIT 1");
}
elseif ($method == "enable")
{
	mysql_query("UPDATE auth SET status = 'Enabled' WHERE user = '$username' LIMIT 1");
}
elseif ($method == "search")
{
	$cen = $_GET["centres"];
	$term = explode(" ",$_GET["term"]);
	$centres = explode("_",$cen);
	
	foreach($centres as $row)
	{
		$inc .= "centre = '" . $row . "' OR ";
		
	}
	$inc = substr($inc, 0, -4);
	
	$q = mysql_query("SELECT * FROM auth WHERE (" . $inc . ") AND first LIKE '$term[0]%' AND last LIKE '$term[1]%'") or die(mysql_error());
	echo '[';
	while ($data = mysql_fetch_assoc($q))
	{
		$d[] = "{ \"id\": \"" . $data["user"] . "\", \"value\": \"" . $data["first"] . " " . $data["last"] . " (" . $data["user"] . ")\" }";
	}
	echo implode(", ",$d);
	echo ']';
}
elseif ($method == "check")
{
	$agent = $_GET["agent"];
	$q = mysql_query("SELECT * FROM auth WHERE user = '$agent'") or die(mysql_error());
	if (mysql_num_rows($q) == 0)
	{
		echo "Please Type the Agent's Name Below";
	}
	else
	{
		echo "valid";
	}
}
?>