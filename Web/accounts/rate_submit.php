<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];

if ($method == "edit")
{
	$user = $_GET["user"];
	$type = $_GET["type"];
	$rate = $_GET["rate"];
	
	if ($user == "")
	{
		echo "Error! Please contact your administrator";
	}
	elseif ($type == "")
	{
		echo "Please select a pay type";
	}
	elseif ($rate == "" && $type == "F")
	{
		echo "Please enter a pay rate";
	}
	elseif (!preg_match('/[0-9.]/', $rate) && $type == "F")
	{
		echo "Please enter a valid pay rate";
	}
	else
	{
		mysql_query("INSERT INTO vericon.timesheet_rate (user, rate, type) VALUES ('$user', '$rate', '$type') ON DUPLICATE KEY UPDATE rate = '$rate', type = '$type'") or die(mysql_error());

		echo "submitted";
	}
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
	
	$q = mysql_query("SELECT * FROM vericon.auth WHERE (" . $inc . ") AND first LIKE '$term[0]%' AND last LIKE '$term[1]%'") or die(mysql_error());
	echo '[';
	while ($data = mysql_fetch_assoc($q))
	{
		$d[] = "{ \"id\": \"" . $data["user"] . "\", \"value\": \"" . $data["first"] . " " . $data["last"] . " (" . $data["user"] . ")\" }";
	}
	echo implode(", ",$d);
	echo ']';
}
elseif ($method == "name")
{
	$user = $_GET["user"];
	$q = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$user'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	echo $data[0] . " " . $data[1];
}
elseif ($method == "rate_type")
{
	$user = $_GET["user"];
	$q = mysql_query("SELECT type FROM vericon.timesheet_rate WHERE user = '$user'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	echo $data[0];
}
?>