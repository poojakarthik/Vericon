<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$method = $_GET["method"];

if ($method == "search")
{
	$cen = $_GET["centres"];
	$term = explode(" ",$_GET["term"]);
	$centres = explode(",",$cen);
	
	foreach($centres as $row)
	{
		$inc .= "centre = '" . $row . "' OR ";
		
	}
	
	$inc = substr($inc, 0, -4);
	
	$q = mysql_query("SELECT * FROM auth WHERE (" . $inc . ") AND first LIKE '$term[0]%' AND last LIKE '$term[1]%' ORDER BY first,last ASC") or die(mysql_error());
	echo '[';
	while ($data = mysql_fetch_assoc($q))
	{
		$d[] = "{ \"id\": \"" . $data["user"] . "\", \"value\": \"" . $data["first"] . " " . $data["last"] . " (" . $data["user"] . ")\" }";
	}
	echo implode(", ",$d);
	echo ']';
}
?>