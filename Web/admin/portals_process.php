<?php
include("../auth/restrict_inner.php");

$method = $_POST["m"];

if ($method == "disable")
{
	$id = $_POST["id"];
	
	mysql_query("UPDATE `vericon`.`portals` SET `status` = 'Disabled' WHERE `id` = '" . mysql_real_escape_string($id) . "' LIMIT 1") or die(mysql_error());
}
elseif ($method == "enable")
{
	$id = $_POST["id"];
	
	mysql_query("UPDATE `vericon`.`portals` SET `status` = 'Enabled' WHERE `id` = '" . mysql_real_escape_string($id) . "' LIMIT 1") or die(mysql_error());
}
elseif ($method == "add")
{
	$id = trim($_POST["id"]);
	$name = trim($_POST["name"]);
	
	$q = mysql_query("SELECT * FROM `vericon`.`portals` WHERE `id` = '" . mysql_real_escape_string($id) . "' OR `name` = '" . mysql_real_escape_string($name) . "'") or die(mysql_error());
	
	if ($id == "")
	{
		echo "<b>Error: </b>Please enter an portal ID.";
	}
	elseif (!preg_match('/^[a-z]+$/i', $id))
	{
		echo "<b>Error: </b>The ID may only contain letters.";
	}
	elseif ($name == "")
	{
		echo "<b>Error: </b>Please enter a portal name.";
	}
	elseif (!preg_match('/^[a-z ]+$/i', $name))
	{
		echo "<b>Error: </b>The name may only contain letters and spaces.";
	}
	elseif (mysql_num_rows($q) != 0)
	{
		echo "<b>Error: </b>Portal already exists.";
	}
	else
	{
		mysql_query("INSERT INTO `vericon`.`portals` (`id`, `name`, `status`) VALUES ('" . mysql_real_escape_string($id) . "', '" . mysql_real_escape_string($name) . "', 'Enabled')") or die(mysql_error());
		
		mysql_query("INSERT INTO `vericon`.`portals_pages` (`id`, `portal`, `name`, `link`, `level`, `sub_level`, `status`) VALUES ('" . mysql_real_escape_string($id . "01") . "', '" . mysql_real_escape_string($id) . "', 'Home', '" . mysql_real_escape_string("index.php") . "', '1', '0', 'Enabled')") or die(mysql_error());
		
		echo "valid";
	}
}
?>