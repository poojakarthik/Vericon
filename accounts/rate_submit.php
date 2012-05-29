<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

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
		mysql_query("INSERT INTO timesheet_rate (user, rate) VALUES ('$user', '$rate') ON DUPLICATE KEY UPDATE rate = '$rate'") or die(mysql_error());

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
	
	$q = mysql_query("SELECT * FROM auth WHERE (" . $inc . ") AND first LIKE '$term[0]%' AND last LIKE '$term[1]%'") or die(mysql_error());
	echo '[';
	while ($data = mysql_fetch_assoc($q))
	{
		$d[] = "{ \"id\": \"" . $data["user"] . "\", \"value\": \"" . $data["first"] . " " . $data["last"] . " (" . $data["user"] . ")\" }";
	}
	echo implode(", ",$d);
	echo ']';
}
elseif ($method == "check")
{
	$agent = $_GET["agent"];
	$q = mysql_query("SELECT * FROM auth WHERE user = '$agent'") or die(mysql_error());
	if (mysql_num_rows($q) == 0)
	{
		echo "Please Type the Agent's Name Below";
	}
	else
	{
		echo "valid";
	}
}
?>