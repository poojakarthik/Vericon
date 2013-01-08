<?php
include("../auth/restrict_inner.php");

$method = $_POST["m"];

if ($method == "check")
{
	$field = $_POST["field"];
	$input = trim($_POST["input"]);
	$input2 = trim($_POST["input2"]);
	
	$q = $mysqli->query("SELECT `pass` FROM `vericon`.`auth` WHERE `user` = '" . $mysqli->real_escape_string($ac["user"]) . "' LIMIT 1") or die($mysqli->error);
	$real_current_pass = $q->fetch_row();
	$q->free();
	
	if ($field == "new_pass")
	{
		if ($input == "")
		{
			echo "<b>Error: </b>Please enter a password.";
		}
		elseif (!preg_match("/[a-zA-Z]/", $input) || !preg_match("/[0-9]/", $input))
		{
			echo "<b>Error: </b>Password must be alphanumeric.";
		}
		elseif (!preg_match("/[A-Z]/", $input) || !preg_match("/[a-z]/", $input))
		{
			echo "<b>Error: </b>Password must contain upper & lower case letters.";
		}
		elseif (strlen($input) < 8)
		{
			echo "<b>Error: </b>Password must be at least 8 characters long.";
		}
		elseif (md5($input) == $real_current_pass[0])
		{
			echo "<b>Error: </b>The current password cannot be reused.";
		}
		else
		{
			echo "valid";
		}
	}
	elseif ($field == "new_pass2")
	{
		if ($input == "")
		{
			echo "<b>Error: </b>Please re-enter the password.";
		}
		elseif ($input != $input2)
		{
			echo "<b>Error: </b>Passwords do not match.";
		}
		elseif (md5($input) == $real_current_pass[0])
		{
			echo "<b>Error: </b>The current password cannot be reused.";
		}
		else
		{
			echo "valid";
		}
	}
}
elseif ($method == "submit")
{
	$current_pass = trim($_POST["current_pass"]);
	$new_pass = trim($_POST["new_pass"]);
	$new_pass2 = trim($_POST["new_pass2"]);
	
	$q = $mysqli->query("SELECT `pass` FROM `vericon`.`auth` WHERE `user` = '" . $mysqli->real_escape_string($ac["user"]) . "' LIMIT 1") or die($mysqli->error);
	$real_current_pass = $q->fetch_row();
	$q->free();
	
	if ($real_current_pass[0] != md5($current_pass))
	{
		echo "<b>Error: </b>Current password is incorrect.";
	}
	elseif ($new_pass == "")
	{
		echo "<b>Error: </b>Please enter a password.";
	}
	elseif (!preg_match("/[a-zA-Z]/", $new_pass) || !preg_match("/[0-9]/", $new_pass))
	{
		echo "<b>Error: </b>Password must be alphanumeric.";
	}
	elseif (!preg_match("/[A-Z]/", $new_pass) || !preg_match("/[a-z]/", $new_pass))
	{
		echo "<b>Error: </b>Password must contain upper & lower case letters.";
	}
	elseif (strlen($new_pass) < 8)
	{
		echo "<b>Error: </b>Password must be at least 8 characters long.";
	}
	elseif ($new_pass2 == "")
	{
		echo "<b>Error: </b>Please re-enter the password.";
	}
	elseif ($new_pass2 != $new_pass)
	{
		echo "<b>Error: </b>Passwords do not match.";
	}
	elseif (md5($new_pass) == $real_current_pass[0])
	{
		echo "<b>Error: </b>The current password cannot be reused.";
	}
	else
	{
		$mysqli->query("UPDATE `vericon`.`auth` SET `pass` = '" . md5($new_pass) . "', `pass_reset` = NOW() WHERE `user` = '" . $mysqli->real_escape_string($ac["user"]) . "' LIMIT 1") or die($mysqli->error);
		
		$mysqli->query("INSERT INTO `vericon`.`mail_pending` (`user`, `action`) VALUES ('" . $mysqli->real_escape_string($ac["user"]) . "', 'edit') ON DUPLICATE KEY UPDATE `action` = 'edit'") or die($mysqli->error);
		
		exec("echo \"" . $ac["user"] . " " . md5($new_pass) . "\" >> /var/vc_tmp/edit_email");
		
		echo "valid";
	}
}

$mysqli->close();
?>