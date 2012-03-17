<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$user = $_GET["user"];
$centre = $_GET["centre"];
for ($day = 1; $day <= 5; $day++)
{
	$start[$day] = $_GET["start_hour$day"] . ":" . $_GET["start_minute$day"] . ":00";
	$end[$day] = $_GET["end_hour$day"] . ":" . $_GET["end_minute$day"] . ":00";
	$na[$day] = $_GET["na$day"];
}

if ($na[1] == 1) {$start[1] = ""; $end[1] = "";}
if ($na[2] == 1) {$start[2] = ""; $end[2] = "";}
if ($na[3] == 1) {$start[3] = ""; $end[3] = "";}
if ($na[4] == 1) {$start[4] = ""; $end[4] = "";}
if ($na[5] == 1) {$start[5] = ""; $end[5] = "";}

if ($user == "" || $centre == "")
{
	echo "Error! Please contact your administrator";
	exit;
}
for ($day = 1; $day <= 5; $day++)
{
	if (strtotime($start[$day]) == "" && $na[$day] == 0 || strtotime($end[$day]) == "" && $na[$day] == 0)
	{
		echo "Please enter both the start and end time otherwise click on 'N/A'";
		exit;
	}
	elseif (strtotime($start[$day]) >= strtotime($end[$day]) && $na[$day] == 0)
	{
		echo "The start time must be less than the end time";
		exit;
	}
	else
	{
		$check[$day] = 1;
	}
}
if ($check[1] == 1 && $check[2] == 1 && $check[2] == 1 && $check[4] == 1 && $check[5] == 1)
{
	for ($day=1; $day <= 5; $day++)
	{
		$date = date('Y-m-d', strtotime(date("Y") . "W" . date("W", strtotime("+1 week")) . $day));
		mysql_query("INSERT INTO roster (date,agent,centre,av_start,av_end,na) VALUES ('$date','$user','$centre','$start[$day]','$end[$day]','$na[$day]')") or die(mysql_error());
	}
	echo "submitted";
}
?>