<?php
$p = $_GET["p"];
$method = $_GET["method"];

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

if ($p == "users")
{
	$username = $_GET["username"];
	$password = $_GET["password"];
	$password2 = $_GET["password2"];
	$type = "Melbourne";
	$access = "Agent";
	$status = $_GET["status"];
	$first = strtoupper(substr($_GET["first"],0,1)) . strtolower(substr($_GET["first"],1));
	$last = strtoupper(substr($_GET["last"],0,1)) . strtolower(substr($_GET["last"],1));
	$centre = $_GET["centre"];
	$alias = strtoupper(substr($_GET["alias"],0,1)) . strtolower(substr($_GET["alias"],1));
		
	if ($method == "create")
	{
		if ($first == "" || $last == "")
		{
			echo "Please enter a first and last name!";
			exit;
		}
		elseif ($alias == "")
		{
			echo "Please enter an alias!";
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

			mysql_query("INSERT INTO `auth` (`user`,`pass`,`type`,`access`,`status`,`first`,`last`,`centre`,`alias`) VALUES ('$username','" . md5($password) . "','$type','$access','Enabled','" . mysql_escape_string($first) . "','" . mysql_escape_string($last) . "','$centre','" . mysql_escape_string($alias) . "')");
			echo "createdYou have successfully created the user!<br><br>Username: <b>$username</b>";
			exit;
		}
	}
	elseif ($method == "modify")
	{
		if ($alias == "")
		{
			echo "Please enter an alias!";
			exit;
		}
		elseif ($password != $password2)
		{
			echo "Passwords do not match!";
			exit;
		}
		else
		{
			mysql_query("UPDATE auth SET pass = '" .  md5($password) . "', alias = '" . mysql_escape_string($alias) . "' WHERE user = '$username' LIMIT 1");
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
}
elseif ($p == "roster")
{
	$user = $_GET["user"];
	$centre = $_GET["centre"];
	for ($day = 1; $day <= 5; $day++)
	{
		$start[$day] = $_GET["start_hour$day"] . ":" . $_GET["start_minute$day"] . ":00";
		$end[$day] = $_GET["end_hour$day"] . ":" . $_GET["end_minute$day"] . ":00";
		$na[$day] = $_GET["na$day"];
	}
	
	if ($na[1] == 1) {$start[1] = ""; $end[1] = "";}
	if ($na[2] == 1) {$start[2] = ""; $end[2] = "";}
	if ($na[3] == 1) {$start[3] = ""; $end[3] = "";}
	if ($na[4] == 1) {$start[4] = ""; $end[4] = "";}
	if ($na[5] == 1) {$start[5] = ""; $end[5] = "";}
	
	if ($user == "" || $centre == "")
	{
		echo "Error! Please contact your administrator";
		exit;
	}
	for ($day = 1; $day <= 5; $day++)
	{
		if (strtotime($start[$day]) == "" && $na[$day] == 0 || strtotime($end[$day]) == "" && $na[$day] == 0)
		{
			echo "Please enter both the start and end time otherwise click on 'N/A'";
			exit;
		}
		elseif (strtotime($start[$day]) >= strtotime($end[$day]) && $na[$day] == 0)
		{
			echo "The start time must be less than the end time";
			exit;
		}
		else
		{
			$check[$day] = 1;
		}
	}
	if ($check[1] == 1 && $check[2] == 1 && $check[2] == 1 && $check[4] == 1 && $check[5] == 1)
	{
		for ($day=1; $day <= 5; $day++)
		{
			$date = date('Y-m-d', strtotime(date("Y") . "W" . date("W", strtotime("+1 week")) . $day));
			$q = mysql_query("SELECT * FROM roster WHERE agent = '$user' AND date = '$date'") or die(mysql_error());
			if (mysql_num_rows($q) == 0)
			{
				mysql_query("INSERT INTO roster (date,agent,centre,start,end,na) VALUES ('$date','$user','$centre','$start[$day]','$end[$day]','$na[$day]')") or die(mysql_error());
			}
			else
			{
				mysql_query("UPDATE roster SET start = '$start[$day]', end = '$end[$day]', na = '$na[$day]' WHERE date = '$date' AND agent = '$user'") or die(mysql_error());
			}
		}
		echo "submitted";
	}
}
elseif ($method == "get") //get sale
{
	$id = $_GET["id"];
	$centre = $_GET["centre"];
	
	$q = mysql_query("SELECT * FROM sales_customers WHERE id = '" . mysql_escape_string($id) . "'") or die(mysql_error());
	$check = mysql_fetch_assoc($q);
	
	if ($id == "" || mysql_num_rows($q) == 0)
	{
		echo "Invalid ID!";
	}
	elseif ($centre != $check["centre"])
	{
		echo "Sale Not for " . $centre;
	}
	else
	{
		echo "valid";
	}
}
elseif ($p == "teams")
{
	if ($method == "assign")
	{
		$user = $_GET["username"];
		$team = $_GET["team"];
		
		$q = mysql_query("SELECT * FROM teams WHERE user = '$user'") or die(mysql_error());
		if (mysql_num_rows($q) == "0")
		{
			mysql_query("INSERT INTO teams (user,team) VALUES ('$user','$team')") or die(mysql_error());
		}
		else
		{
			mysql_query("UPDATE teams SET team = '$team' WHERE user = '$user' LIMIT 1") or die(mysql_error());
		}
		echo "assigned";
	}
}
?>