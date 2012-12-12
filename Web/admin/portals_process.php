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
elseif ($method == "page_disable")
{
	$id = $_POST["id"];
	
	mysql_query("UPDATE `vericon`.`portals_pages` SET `status` = 'Disabled' WHERE `id` = '" . mysql_real_escape_string($id) . "' LIMIT 1") or die(mysql_error());
}
elseif ($method == "page_enable")
{
	$id = $_POST["id"];
	
	mysql_query("UPDATE `vericon`.`portals_pages` SET `status` = 'Enabled' WHERE `id` = '" . mysql_real_escape_string($id) . "' LIMIT 1") or die(mysql_error());
}
elseif ($method == "page_add") // i know i've done this in a really stupid way, but i cbf
{
	$portal_id = $_POST["portal_id"];
	$name = trim($_POST["name"]);
	$link = trim($_POST["p_link"]);
	$jquery = trim($_POST["jquery"]);
	$level = trim($_POST["level"]);
	$sub_level = trim($_POST["sub_level"]);
	
	$q = mysql_query("SELECT `id` FROM `vericon`.`portals_pages` WHERE `portal` = '" . mysql_real_escape_string($portal_id) . "' AND `level` = '" . mysql_real_escape_string($level) . "' AND `sub_level` = '0'") or die(mysql_error());
	
	$q1 = mysql_query("SELECT `id` FROM `vericon`.`portals_pages` WHERE `portal` = '" . mysql_real_escape_string($portal_id) . "' AND `level` = '" . mysql_real_escape_string($level) . "' AND `sub_level` = '" . mysql_real_escape_string($sub_level) . "'") or die(mysql_error());
	
	if ($name == "")
	{
		echo "<b>Error: </b>Please enter a page name.";
	}
	elseif (!preg_match('/^[a-z ]+$/i', $name))
	{
		echo "<b>Error: </b>The name may only contain letters and spaces.";
	}
	elseif (!preg_match('/^[0-9]+$/i',$level))
	{
		echo "<b>Error: </b>Please enter a valid page level.";
	}
	elseif (!preg_match('/^[0-9]+$/i',$sub_level))
	{
		echo "<b>Error: </b>Please enter a valid page sub-level.";
	}
	elseif ($link == "" && $sub_level != 0)
	{
		echo "<b>Error: </b>Please enter a link or '0' for sub-level";
	}
	elseif ($link == "" && $jquery != "")
	{
		echo "<b>Error: </b>Please enter the jQuery onClick call.";
	}
	elseif ($sub_level > 0 && mysql_num_rows($q) == 0)
	{
		echo "<b>Error: </b>Cannot add sub-menu to non-existing level.";
	}
	elseif (mysql_num_rows($q1) != 0)
	{
		echo "<b>Error: </b>A page already exists at that level/sub-level.";
	}
	else
	{
		$q = mysql_query("SELECT COUNT(`id`) FROM `vericon`.`portals_pages` WHERE `portal` = '" . mysql_real_escape_string($portal_id) . "'") or die(mysql_error());
		$c = mysql_fetch_row($q);
		$id = $portal_id . str_pad(($c[0] + 1),2,'0',STR_PAD_LEFT);
		
		mysql_query("INSERT INTO `vericon`.`portals_pages` (`id`, `portal`, `name`, `link`, `jquery`, `level`, `sub_level`, `status`) VALUES ('" . mysql_real_escape_string($id) . "', '" . mysql_real_escape_string($portal_id) . "', '" . mysql_real_escape_string($name) . "', '" . mysql_real_escape_string($link) . "', '" . mysql_real_escape_string($jquery) . "', '" . mysql_real_escape_string($level) . "', '" . mysql_real_escape_string($sub_level) . "', 'Enabled')") or die(mysql_error());
		
		echo "valid";
	}
}
elseif ($method == "page_edit")
{
	$portal_id = $_POST["portal_id"];
	$id = $_POST["id"];
	$name = trim($_POST["name"]);
	$link = trim($_POST["p_link"]);
	$jquery = trim($_POST["jquery"]);
	$level = trim($_POST["level"]);
	$sub_level = trim($_POST["sub_level"]);
	
	$q = mysql_query("SELECT `id` FROM `vericon`.`portals_pages` WHERE `portal` = '" . mysql_real_escape_string($portal_id) . "' AND `level` = '" . mysql_real_escape_string($level) . "' AND `sub_level` = '0' AND `id` != '" . mysql_real_escape_string($id) . "'") or die(mysql_error());
	
	$q1 = mysql_query("SELECT `id` FROM `vericon`.`portals_pages` WHERE `portal` = '" . mysql_real_escape_string($portal_id) . "' AND `level` = '" . mysql_real_escape_string($level) . "' AND `sub_level` = '" . mysql_real_escape_string($sub_level) . "' AND `id` != '" . mysql_real_escape_string($id) . "'") or die(mysql_error());
	
	if ($name == "")
	{
		echo "<b>Error: </b>Please enter a page name.";
	}
	elseif (!preg_match('/^[a-z ]+$/i', $name))
	{
		echo "<b>Error: </b>The name may only contain letters and spaces.";
	}
	elseif (!preg_match('/^[0-9]+$/i',$level))
	{
		echo "<b>Error: </b>Please enter a valid page level.";
	}
	elseif (!preg_match('/^[0-9]+$/i',$sub_level))
	{
		echo "<b>Error: </b>Please enter a valid page sub-level.";
	}
	elseif ($link == "" && $sub_level != 0)
	{
		echo "<b>Error: </b>Please enter a link or '0' for sub-level";
	}
	elseif ($link == "" && $jquery != "")
	{
		echo "<b>Error: </b>Please enter the jQuery onClick call.";
	}
	elseif ($sub_level > 0 && mysql_num_rows($q) == 0)
	{
		echo "<b>Error: </b>Cannot add sub-menu to non-existing level.";
	}
	elseif (mysql_num_rows($q1) != 0)
	{
		echo "<b>Error: </b>A page already exists at that level/sub-level.";
	}
	else
	{
		mysql_query("UPDATE `vericon`.`portals_pages` SET `name` = '" . mysql_real_escape_string($name) . "', `link` = '" . mysql_real_escape_string($link) . "', `jquery` = '" . mysql_real_escape_string($jquery) . "', `level` = '" . mysql_real_escape_string($level) . "', `sub_level` = '" . mysql_real_escape_string($sub_level) . "' WHERE `id` = '" . mysql_real_escape_string($id) . "'") or die(mysql_error());
		
		echo "valid";
	}
}
?>