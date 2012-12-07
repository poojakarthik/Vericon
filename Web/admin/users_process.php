<?php
include("../auth/restrict_inner.php");

$method = $_POST["m"];

if ($method == "disable")
{
	$user = $_POST["user"];
	
	mysql_query("UPDATE `vericon`.`auth` SET `status` = 'Disabled' WHERE `user` = '" . mysql_real_escape_string($user) . "' LIMIT 1") or die(mysql_error());
}
elseif ($method == "enable")
{
	$user = $_POST["user"];
	
	mysql_query("UPDATE `vericon`.`auth` SET `status` = 'Enabled' WHERE `user` = '" . mysql_real_escape_string($user) . "' LIMIT 1") or die(mysql_error());
}
elseif ($method == "check")
{
	$field = $_POST["field"];
	$input = trim($_POST["input"]);
	$input2 = trim($_POST["input2"]);
	
	if ($field == "first")
	{
		if ($input == "")
		{
			echo "<b>Error: </b>Please enter a first name.";
		}
		elseif (!preg_match("/^[a-zA-Z '-]+$/i", $input))
		{
			echo "<b>Error: </b>First name may only contain (a-zA-Z '-).";
		}
		elseif (!preg_match("/[A-Z]/", $input) || !preg_match("/[a-z]/", $input))
		{
			echo "<b>Error: </b>First name must contain upper & lower case letters.";
		}
		elseif (strlen($input) < 2 || strlen($input) > 32)
		{
			echo "<b>Error: </b>First name must be between 2 & 32 characters.";
		}
		else
		{
			echo "valid";
		}
	}
	elseif ($field == "last")
	{
		if ($input == "")
		{
			echo "<b>Error: </b>Please enter a last name.";
		}
		elseif (!preg_match("/^[a-zA-Z '-]+$/i", $input))
		{
			echo "<b>Error: </b>Lasat name may only contain (a-zA-Z '-).";
		}
		elseif (!preg_match("/[A-Z]/", $input) || !preg_match("/[a-z]/", $input))
		{
			echo "<b>Error: </b>Last name must contain upper & lower case letters.";
		}
		elseif (strlen($input) < 2 || strlen($input) > 32)
		{
			echo "<b>Error: </b>Last name must be between 2 & 32 characters.";
		}
		else
		{
			echo "valid";
		}
	}
	elseif ($field == "password")
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
		else
		{
			echo "valid";
		}
	}
	elseif ($field == "password2")
	{
		if ($input == "")
		{
			echo "<b>Error: </b>Please re-enter the password.";
		}
		elseif ($input != $input2)
		{
			echo "<b>Error: </b>Passwords do not match.";
		}
		else
		{
			echo "valid";
		}
	}
	if ($field == "alias")
	{
		if ($input == "")
		{
			echo "<b>Error: </b>Please enter a first name.";
		}
		elseif (!preg_match("/^[a-zA-Z '-]+$/i", $input))
		{
			echo "<b>Error: </b>Alias may only contain (a-zA-Z '-).";
		}
		elseif (!preg_match("/[A-Z]/", $input) || !preg_match("/[a-z]/", $input))
		{
			echo "<b>Error: </b>Alias must contain upper & lower case letters.";
		}
		elseif (strlen($input) < 2 || strlen($input) > 32)
		{
			echo "<b>Error: </b>Alias must be between 2 & 32 characters.";
		}
		else
		{
			echo "valid";
		}
	}
}
elseif ($method == "create")
{
	$first = trim($_POST["first"]);
	$last = trim($_POST["last"]);
	$password = trim($_POST["password"]);
	$password2 = trim($_POST["password2"]);
	$access = $_POST["access"];
	$centres = $_POST["centres"];
	$centre = $_POST["centre"];
	$designation = $_POST["designation"];
	$alias = trim($_POST["alias"]);
	$p = $_POST["pages"];
	
	if (strlen(preg_replace("/[^A-Za-z]/", "", $last)) == 2)
	{
		$user1 = strtolower(substr(preg_replace("/[^A-Za-z]/", "", $first),0,2) . substr(preg_replace("/[^A-Za-z]/", "", $last),0,2));
	}
	else
	{
		$user1 = strtolower(substr(preg_replace("/[^A-Za-z]/", "", $first),0,1) . substr(preg_replace("/[^A-Za-z]/", "", $last),0,3));
	}
	
	if ($first == "")
	{
		echo "<b>Error: </b>Please enter a first name.";
	}
	elseif (!preg_match("/^[a-zA-Z '-]+$/i", $first))
	{
		echo "<b>Error: </b>First name may only contain (a-zA-Z '-).";
	}
	elseif (!preg_match("/[A-Z]/", $first) || !preg_match("/[a-z]/", $first))
	{
		echo "<b>Error: </b>First name must contain upper & lower case letters.";
	}
	elseif (strlen($first) < 2 || strlen($first) > 32)
	{
		echo "<b>Error: </b>First name must be between 2 & 32 characters.";
	}
	elseif ($last == "")
	{
		echo "<b>Error: </b>Please enter a last name.";
	}
	elseif (!preg_match("/^[a-zA-Z '-]+$/i", $last))
	{
		echo "<b>Error: </b>Lasat name may only contain (a-zA-Z '-).";
	}
	elseif (!preg_match("/[A-Z]/", $last) || !preg_match("/[a-z]/", $last))
	{
		echo "<b>Error: </b>Last name must contain upper & lower case letters.";
	}
	elseif (strlen($last) < 2 || strlen($last) > 32)
	{
		echo "<b>Error: </b>Last name must be between 2 & 32 characters.";
	}
	elseif (strlen($user1) < 4)
	{
		echo "<b>Error: </b>First and last name not long enough.";
	}
	elseif ($password == "")
	{
		echo "<b>Error: </b>Please enter a password.";
	}
	elseif (!preg_match("/[a-zA-Z]/", $password) || !preg_match("/[0-9]/", $password))
	{
		echo "<b>Error: </b>Password must be alphanumeric.";
	}
	elseif (!preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password))
	{
		echo "<b>Error: </b>Password must contain upper & lower case letters.";
	}
	elseif (strlen($password) < 8)
	{
		echo "<b>Error: </b>Password must be at least 8 characters long.";
	}
	elseif ($password2 == "")
	{
		echo "<b>Error: </b>Please re-enter the password.";
	}
	elseif ($password != $password2)
	{
		echo "<b>Error: </b>Passwords do not match.";
	}
	elseif ($access == "")
	{
		echo "<b>Error: </b>Please select a department.";
	}
	elseif ((in_array("Operations", $access) || in_array("HR", $access)) && $centres == "")
	{
		echo "<b>Error: </b>Please select at least one centre.";
	}
	elseif (in_array("Sales", $access) && $centre == "")
	{
		echo "<b>Error: </b>Please select a centre.";
	}
	elseif (in_array("Sales", $access) && $designation == "")
	{
		echo "<b>Error: </b>Please select a designation.";
	}
	elseif ((in_array("Sales", $access) || in_array("CS", $access) || in_array("TPV", $access)) && $alias == "")
	{
		echo "<b>Error: </b>Please enter an alias";
	}
	elseif ((in_array("Sales", $access) || in_array("CS", $access) || in_array("TPV", $access)) && !preg_match("/^[a-zA-Z '-]+$/i", $alias))
	{
		echo "<b>Error: </b>Alias may only contain (a-zA-Z '-).";
	}
	elseif ((in_array("Sales", $access) || in_array("CS", $access) || in_array("TPV", $access)) && (!preg_match("/[A-Z]/", $alias) || !preg_match("/[a-z]/", $alias)))
	{
		echo "<b>Error: </b>Alias must contain upper & lower case letters.";
	}
	elseif ((in_array("Sales", $access) || in_array("CS", $access) || in_array("TPV", $access)) && (strlen($alias) < 2 || strlen($alias) > 32))
	{
		echo "<b>Error: </b>Alias must be between 2 & 32 characters.";
	}
	else
	{
		if (!in_array("Sales", $access)) {
			$centre = "";
			$designation = "";
		}
		if (!in_array("Sales", $access) && !in_array("CS", $access) && !in_array("TPV", $access)) {
			$alias = "";
		}
		if (!in_array("Operations", $access) && !in_array("HR", $access)) {
			$centres = "";
		}
		
		$q = mysql_query("SELECT COUNT(`user`) FROM `vericon`.`auth` WHERE `user` LIKE '" . mysql_real_escape_string($user1) . "%'");
		$num = mysql_fetch_row($q);
		
		$username = $user1 . str_pad(($num[0]+1),3,"0",STR_PAD_LEFT);
		$type = implode(",",$access);
		
		mysql_query("INSERT INTO `vericon`.`auth` (`user`, `pass`, `type`, `centre`, `status`, `first`, `last`, `alias`, `timestamp`) VALUES ('" . mysql_real_escape_string($username) . "' ,'" . md5($password) . "', '" . mysql_real_escape_string($type) . "', '" . mysql_real_escape_string($centre) . "', 'Enabled', '" . mysql_real_escape_string($first) . "', '" . mysql_real_escape_string($last) . "', '" . mysql_real_escape_string($alias) . "', NOW())");
		
		if (in_array("Sales", $access))
		{
			mysql_query("INSERT INTO `vericon`.`timesheet_designation` (`user`, `designation`) VALUES ('" . mysql_real_escape_string($username) . "','" . mysql_real_escape_string($designation) . "')") or die(mysql_error());
		}
		
		$pages = array( );
		for ($i = 0; $i < count($p); $i++)
		{
			if (in_array(substr($p[$i],0,-2), $access) || substr($p[$i],0,-2) == "MA")
			{
				array_push($pages ,$p[$i]);
			}
		}
		$pages = implode(",", array_unique($pages));
		
		mysql_query("INSERT INTO `vericon`.`portals_access` (`user`, `pages`) VALUES ('" . mysql_real_escape_string($username) . "', '" . mysql_real_escape_string($pages) . "') ON DUPLICATE KEY UPDATE `pages` = '" . mysql_real_escape_string($pages) . "'") or die(mysql_error());
		
		echo "valid" . $username;
	}
}
elseif ($method == "edit")
{
	$user = $_POST["user"];
	$password = trim($_POST["password"]);
	$password2 = trim($_POST["password2"]);
	$access = $_POST["access"];
	$centres = $_POST["centres"];
	$centre = $_POST["centre"];
	$designation = $_POST["designation"];
	$alias = trim($_POST["alias"]);
	$p = $_POST["pages"];
	
	if ($password != "" && (!preg_match("/[a-zA-Z]/", $password) || !preg_match("/[0-9]/", $password)))
	{
		echo "<b>Error: </b>Password must be alphanumeric.";
	}
	elseif ($password != "" && (!preg_match("/[A-Z]/", $password) || !preg_match("/[a-z]/", $password)))
	{
		echo "<b>Error: </b>Password must contain upper & lower case letters.";
	}
	elseif ($password != "" && strlen($password) < 8)
	{
		echo "<b>Error: </b>Password must be at least 8 characters long.";
	}
	elseif ($password != $password2)
	{
		echo "<b>Error: </b>Passwords do not match.";
	}
	elseif ($access == "")
	{
		echo "<b>Error: </b>Please select a department.";
	}
	elseif ((in_array("Operations", $access) || in_array("HR", $access)) && $centres == "")
	{
		echo "<b>Error: </b>Please select at least one centre.";
	}
	elseif (in_array("Sales", $access) && $centre == "")
	{
		echo "<b>Error: </b>Please select a centre.";
	}
	elseif (in_array("Sales", $access) && $designation == "")
	{
		echo "<b>Error: </b>Please select a designation.";
	}
	elseif ((in_array("Sales", $access) || in_array("CS", $access) || in_array("TPV", $access)) && $alias == "")
	{
		echo "<b>Error: </b>Please enter an alias";
	}
	elseif ((in_array("Sales", $access) || in_array("CS", $access) || in_array("TPV", $access)) && !preg_match("/^[a-zA-Z '-]+$/i", $alias))
	{
		echo "<b>Error: </b>Alias may only contain (a-zA-Z '-).";
	}
	elseif ((in_array("Sales", $access) || in_array("CS", $access) || in_array("TPV", $access)) && (!preg_match("/[A-Z]/", $alias) || !preg_match("/[a-z]/", $alias)))
	{
		echo "<b>Error: </b>Alias must contain upper & lower case letters.";
	}
	elseif ((in_array("Sales", $access) || in_array("CS", $access) || in_array("TPV", $access)) && (strlen($alias) < 2 || strlen($alias) > 32))
	{
		echo "<b>Error: </b>Alias must be between 2 & 32 characters.";
	}
	else
	{
		if (!in_array("Sales", $access)) {
			$centre = "";
			$designation = "";
		}
		if (!in_array("Sales", $access) && !in_array("CS", $access) && !in_array("TPV", $access)) {
			$alias = "";
		}
		if (!in_array("Operations", $access) && !in_array("HR", $access)) {
			$centres = "";
		}
		
		$type = implode(",",$access);
		
		mysql_query("UPDATE `vericon`.`auth` SET `type` = '" . mysql_real_escape_string($type) . "', `centre` = '" . mysql_real_escape_string($centre) . "', `alias` = '" . mysql_real_escape_string($alias) . "' WHERE `user` = '" . mysql_real_escape_string($user) . "' LIMIT 1");
		
		if ($password != "")
		{
			mysql_query("UPDATE `vericon`.`auth` SET `pass` = '" . mysql_real_escape_string($password) . "' WHERE `user` = '" . mysql_real_escape_string($user) . "' LIMIT 1");
		}
		
		if (in_array("Sales", $access))
		{
			mysql_query("INSERT INTO `vericon`.`timesheet_designation` (`user`, `designation`) VALUES ('" . mysql_real_escape_string($user) . "','" . mysql_real_escape_string($designation) . "') ON DUPLICATE KEY UPDATE `designation` = '" . mysql_real_escape_string($designation) . "'") or die(mysql_error());
		}
		
		$pages = array( );
		for ($i = 0; $i < count($p); $i++)
		{
			if (in_array(substr($p[$i],0,-2), $access) || substr($p[$i],0,-2) == "MA")
			{
				array_push($pages ,$p[$i]);
			}
		}
		$pages = implode(",", array_unique($pages));
		
		mysql_query("INSERT INTO `vericon`.`portals_access` (`user`, `pages`) VALUES ('" . mysql_real_escape_string($user) . "', '" . mysql_real_escape_string($pages) . "') ON DUPLICATE KEY UPDATE `pages` = '" . mysql_real_escape_string($pages) . "'") or die(mysql_error());
		
		echo "valid";
	}
}
?>