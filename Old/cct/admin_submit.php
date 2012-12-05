<?php
$p = $_GET["p"];
$method = $_GET["method"];

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

if ($p == "announcements")
{
	if ($method == "post")
	{
		$poster = $_GET["poster"];
		$subject = $_GET["subject"];
		$message = $_GET["message"];
		$date = date("d F Y");
		if ($poster == "")
		{
			echo "Error!!! Contact Your Administrator!";
			exit;
		}
		elseif ($subject == "")
		{
			echo "Please enter a subject!";
			exit;
		}
		elseif ($message == "")
		{
			echo "Please enter a message!";
			exit;
		}
		else
		{
			mysql_query("INSERT INTO `announcements` (`date`, `poster`, `department`, `display`, `subject`, `message`) VALUES ('$date', '$poster', 'cct', 'Yes', '" . mysql_escape_string($subject) . "', '" . mysql_escape_string($message) . "');");
			echo "posted";
			exit;
		}
	}
	elseif ($method == "edit")
	{
		$id = $_GET["id"];
		$subject = $_GET["subject"];
		$message = $_GET["message"];
		if ($subject == "")
		{
			echo "Please enter a subject!";
			exit;
		}
		elseif ($message == "")
		{
			echo "Please enter a message!";
			exit;
		}
		else
		{
			mysql_query("UPDATE announcements SET subject = '" . mysql_escape_string($subject) . "', message = '" . mysql_escape_string($message) . "' WHERE id = '$id' LIMIT 1");
			echo "edited";
			exit;
		}
	}
	elseif ($method == "hide")
	{
		$id = $_GET["id"];
		mysql_query("UPDATE announcements SET display = 'No' WHERE id = '$id' LIMIT 1");
		exit;
	}
	elseif ($method == "display")
	{
		$id = $_GET["id"];
		mysql_query("UPDATE announcements SET display = 'Yes' WHERE id = '$id' LIMIT 1");
		exit;
	}
}
elseif ($p == "users")
{
	$username = $_GET["username"];
	$password = $_GET["password"];
	$password2 = $_GET["password2"];
	$type = "CCT";
	$access = "Agent";
	$status = $_GET["status"];
	$first = $_GET["first"];
	$last = $_GET["last"];
		
	if ($method == "create")
	{
		if ($first == "" || $last == "")
		{
			echo "Please enter a first and last name!";
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

			mysql_query("INSERT INTO `auth` (`user`,`pass`,`type`,`access`,`status`,`first`,`last`) VALUES ('$username','" . md5($password) . "','$type','$access','Enabled','" . mysql_escape_string($first) . "','" . mysql_escape_string($last) . "')");
			echo "createdYou have successfully created the user!<br><br>Username: <b>$username</b>";
			exit;
		}
	}
	elseif ($method == "modify")
	{
		if ($password != $password2)
		{
			echo "Passwords do not match!";
			exit;
		}
		else
		{
			mysql_query("UPDATE auth SET pass = '" .  md5($password) . "' WHERE user = '$username' LIMIT 1");
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
?>