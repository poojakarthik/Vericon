<?php
$method = $_GET["method"];
$username = $_GET["username"];
$password = $_GET["password"];
$password2 = $_GET["password2"];
$type = $_GET["type"];
$access = $_GET["access"];
$centre = $_GET["centre"];
$status = $_GET["status"];
$first = strtoupper(substr($_GET["first"],0,1)) . strtolower(substr($_GET["first"],1));
$last = strtoupper(substr($_GET["last"],0,1)) . strtolower(substr($_GET["last"],1));
$alias = strtoupper(substr($_GET["alias"],0,1)) . strtolower(substr($_GET["alias"],1));

$type2 = implode(",",$type);

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

if ($method == "create")
{	
	if ($first == "" || $last == "")
	{
		echo "Please enter a first and last name!";
		exit;
	}
	elseif ($type2 == "" || $access == "")
	{
		echo "Please select an account type and access level!";
		exit;
	}
	elseif ($password != $password2)
	{
		echo "Passwords do not match!";
		exit;
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
		
		mysql_query("INSERT INTO `auth` (`user`,`pass`,`type`,`access`,`centre`,`status`,`first`,`last`,`alias`) VALUES ('$username','" . md5($password) . "','$type2','$access','" . mysql_escape_string($centre) . "','Enabled','" . mysql_escape_string($first) . "','" . mysql_escape_string($last) . "','" . mysql_escape_string($alias) . "')");
			echo "createdYou have successfully created the user!<br><br>Username: <b>$username</b>";
			exit;
	}
}
elseif ($method == "modify")
{
	if ($type2 == "" || $access == "")
	{
		echo "Please select an account type and access level!";
		exit;
	}
	elseif ($password != $password2)
	{
		echo "Passwords do not match!";
		exit;
	}
	else
	{	
		mysql_query("UPDATE auth SET pass = '" .  md5($password) . "', type = '$type2', access = '$access', centre = '" . mysql_escape_string($centre) . "', alias = '" . mysql_escape_string($alias) . "' WHERE user = '$username' LIMIT 1");
		echo "modified";
		exit;
	}
}
elseif ($method == "disable")
{
	mysql_query("UPDATE auth SET status = 'Disabled' WHERE user = '$username' LIMIT 1");
	exit;
}
elseif ($method == "enable")
{
	mysql_query("UPDATE auth SET status = 'Enabled' WHERE user = '$username' LIMIT 1");
	exit;
}
elseif ($method == "logout")
{
	$username = $_GET["username"];
	$hash = $_GET["hash"];
	mysql_query("DELETE FROM currentuser WHERE hash = '$hash' AND user = '$username' LIMIT 1");
	exit;
}
?>