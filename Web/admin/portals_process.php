<?php
include("../auth/restrict_inner.php");

$method = $_POST["m"];

if ($method == "disable")
{
	$id = $_POST["id"];
	
	$mysqli->query("UPDATE `vericon`.`portals` SET `status` = 'Disabled' WHERE `id` = '" . $mysqli->real_escape_string($id) . "' LIMIT 1") or die($mysqli->error);
}
elseif ($method == "enable")
{
	$id = $_POST["id"];
	
	$mysqli->query("UPDATE `vericon`.`portals` SET `status` = 'Enabled' WHERE `id` = '" . $mysqli->real_escape_string($id) . "' LIMIT 1") or die($mysqli->error);
}
elseif ($method == "add")
{
	$id = trim($_POST["id"]);
	$name = trim($_POST["name"]);
	
	$q = $mysqli->query("SELECT * FROM `vericon`.`portals` WHERE `id` = '" . $mysqli->real_escape_string($id) . "' OR `name` = '" . $mysqli->real_escape_string($name) . "'") or die($mysqli->error);
	
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
	elseif ($q->num_rows != 0)
	{
		echo "<b>Error: </b>Portal already exists.";
	}
	else
	{
		$mysqli->query("INSERT INTO `vericon`.`portals` (`id`, `name`, `status`) VALUES ('" . $mysqli->real_escape_string($id) . "', '" . $mysqli->real_escape_string($name) . "', 'Enabled')") or die($mysqli->error);
		
		$mysqli->query("INSERT INTO `vericon`.`portals_pages` (`id`, `portal`, `name`, `link`, `level`, `sub_level`, `status`) VALUES ('" . $mysqli->real_escape_string($id . "01") . "', '" . $mysqli->real_escape_string($id) . "', 'Home', '" . $mysqli->real_escape_string("index.php") . "', '1', '0', 'Enabled')") or die($mysqli->error);
		
		echo "valid";
	}
	$q->free();
}
elseif ($method == "page_disable")
{
	$id = $_POST["id"];
	
	$mysqli->query("UPDATE `vericon`.`portals_pages` SET `status` = 'Disabled' WHERE `id` = '" . $mysqli->real_escape_string($id) . "' LIMIT 1") or die($mysqli->error);
}
elseif ($method == "page_enable")
{
	$id = $_POST["id"];
	
	$mysqli->query("UPDATE `vericon`.`portals_pages` SET `status` = 'Enabled' WHERE `id` = '" . $mysqli->real_escape_string($id) . "' LIMIT 1") or die($mysqli->error);
}
elseif ($method == "page_add") // i know i've done this in a really stupid way, but i cbf
{
	$portal_id = $_POST["portal_id"];
	$name = trim($_POST["name"]);
	$link = trim($_POST["p_link"]);
	$jquery = trim($_POST["jquery"]);
	$level = trim($_POST["level"]);
	$sub_level = trim($_POST["sub_level"]);
	
	$q = $mysqli->query("SELECT `id` FROM `vericon`.`portals_pages` WHERE `portal` = '" . $mysqli->real_escape_string($portal_id) . "' AND `level` = '" . $mysqli->real_escape_string($level) . "' AND `sub_level` = '0'") or die($mysqli->error);
	
	$q1 = $mysqli->query("SELECT `id` FROM `vericon`.`portals_pages` WHERE `portal` = '" . $mysqli->real_escape_string($portal_id) . "' AND `level` = '" . $mysqli->real_escape_string($level) . "' AND `sub_level` = '" . $mysqli->real_escape_string($sub_level) . "'") or die($mysqli->error);
	
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
	elseif ($sub_level > 0 && $q->num_rows == 0)
	{
		echo "<b>Error: </b>Cannot add sub-menu to non-existing level.";
	}
	elseif ($q1->num_rows != 0)
	{
		echo "<b>Error: </b>A page already exists at that level/sub-level.";
	}
	else
	{
		$q->free();
		$q1->free();
		
		$q = $mysqli->query("SELECT COUNT(`id`) FROM `vericon`.`portals_pages` WHERE `portal` = '" . $mysqli->real_escape_string($portal_id) . "'") or die($mysqli->error);
		$c = $q->fetch_row();
		$q->free();
		$id = $portal_id . str_pad(($c[0] + 1),2,'0',STR_PAD_LEFT);
		
		$mysqli->query("INSERT INTO `vericon`.`portals_pages` (`id`, `portal`, `name`, `link`, `jquery`, `level`, `sub_level`, `status`) VALUES ('" . $mysqli->real_escape_string($id) . "', '" . $mysqli->real_escape_string($portal_id) . "', '" . $mysqli->real_escape_string($name) . "', '" . $mysqli->real_escape_string($link) . "', '" . $mysqli->real_escape_string($jquery) . "', '" . $mysqli->real_escape_string($level) . "', '" . $mysqli->real_escape_string($sub_level) . "', 'Enabled')") or die($mysqli->error);
		
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
	
	$q = $mysqli->query("SELECT `id` FROM `vericon`.`portals_pages` WHERE `portal` = '" . $mysqli->real_escape_string($portal_id) . "' AND `level` = '" . $mysqli->real_escape_string($level) . "' AND `sub_level` = '0' AND `id` != '" . $mysqli->real_escape_string($id) . "'") or die($mysqli->error);
	
	$q1 = $mysqli->query("SELECT `id` FROM `vericon`.`portals_pages` WHERE `portal` = '" . $mysqli->real_escape_string($portal_id) . "' AND `level` = '" . $mysqli->real_escape_string($level) . "' AND `sub_level` = '" . $mysqli->real_escape_string($sub_level) . "' AND `id` != '" . $mysqli->real_escape_string($id) . "'") or die($mysqli->error);
	
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
	elseif ($sub_level > 0 && $q->num_rows == 0)
	{
		echo "<b>Error: </b>Cannot add sub-menu to non-existing level.";
	}
	elseif ($q1->num_rows != 0)
	{
		echo "<b>Error: </b>A page already exists at that level/sub-level.";
	}
	else
	{
		$q->free();
		$q1->free();
		
		$mysqli->query("UPDATE `vericon`.`portals_pages` SET `name` = '" . $mysqli->real_escape_string($name) . "', `link` = '" . $mysqli->real_escape_string($link) . "', `jquery` = '" . $mysqli->real_escape_string($jquery) . "', `level` = '" . $mysqli->real_escape_string($level) . "', `sub_level` = '" . $mysqli->real_escape_string($sub_level) . "' WHERE `id` = '" . $mysqli->real_escape_string($id) . "'") or die($mysqli->error);
		
		echo "valid";
	}
}
$mysqli->close();
?>