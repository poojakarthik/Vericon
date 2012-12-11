<?php
include("../auth/restrict_inner.php");

$method = $_POST["m"];

if ($method == "add")
{
	$recipient = $_POST["recipient"];
	$department = trim($_POST["department"]);
	$user = trim($_POST["user"]);
	$expiry = explode(":", trim($_POST["expiry"]));
	$title = trim($_POST["title"]);
	$message = trim($_POST["message"]);
	$expiry_time = date("Y-m-d") . " " . implode(":", $expiry) . ":00";
	
	if ($recipient == "")
	{
		echo "<b>Error: </b>Please select a recipient.";
	}
	elseif ($recipient == "department" && $department == "")
	{
		echo "<b>Error: </b>Please select a department.";
	}
	elseif ($recipient == "user" && $user == "")
	{
		echo "<b>Error: </b>Please select a user.";
	}
	elseif (!preg_match("/^[0-9]{2}$/", $expiry[0]) || $expiry[0] > 23)
	{
		echo "<b>Error: </b>Please enter a valid expiry time.";
	}
	elseif (!preg_match("/^[0-9]{2}$/", $expiry[1]) || $expiry[1] > 59)
	{
		echo "<b>Error: </b>Please enter a valid expiry time.";
	}
	elseif (strtotime($expiry_time) <= strtotime(date("Y-m-d H:i")))
	{
		echo "<b>Error: </b>Expiry time must be in the future.";
	}
	elseif ($title == "")
	{
		echo "<b>Error: </b>Please enter a title.";
	}
	elseif ($message == "")
	{
		echo "<b>Error: </b>Please enter a message.";
	}
	else
	{
		if ($recipient == "all") {
			$all = 1;
			$department = "";
			$user = "";
		} elseif ($recipient == "department") {
			$all = 0;
			$user = "";
		} elseif ($recipient == "user") {
			$all = 0;
			$department = "";
		}
		
		mysql_query("INSERT INTO `vericon`.`broadcast` (`poster`, `title`, `message`, `all`, `department`, `user`, `timestamp`, `end_timestamp`) VALUES ('" . mysql_real_escape_string($ac["user"]) . "', '" . mysql_real_escape_string($title) . "', '" . mysql_real_escape_string($message) . "', '" . mysql_real_escape_string($all) . "', '" . mysql_real_escape_string($department) . "', '" . mysql_real_escape_string($user) . "', NOW(), '" . mysql_real_escape_string($expiry_time) . "')") or die(mysql_error());
		
		echo "valid";
	}
}
?>