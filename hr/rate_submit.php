<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];

if ($method == "edit")
{
	$user = $_GET["user"];
	$rate = $_GET["rate"];
	
	if ($user == "")
	{
		echo "Error! Please contact your administrator";
	}
	elseif ($rate == "")
	{
		echo "Please enter a pay rate";
	}
	elseif (!preg_match('/[0-9.]/', $rate))
	{
		echo "Please enter a valid pay rate";
	}
	else
	{
		mysql_query("INSERT INTO vericon.timesheet_rate (user, rate) VALUES ('$user', '$rate') ON DUPLICATE KEY UPDATE rate = '$rate'") or die(mysql_error());

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
?>