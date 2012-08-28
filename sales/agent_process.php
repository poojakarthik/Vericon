<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$method = $_GET["method"];

if ($method == "search")
{
	$term = explode(" ",$_GET["term"]);
	$centre = $_GET["centre"];
	
	$q = mysql_query("SELECT * FROM auth WHERE centre = '$centre' AND first LIKE '$term[0]%' AND last LIKE '$term[1]%' ORDER BY first,last ASC") or die(mysql_error());
	echo '[';
	while ($data = mysql_fetch_assoc($q))
	{
		$d[] = "{ \"id\": \"" . $data["user"] . "\", \"value\": \"" . $data["first"] . " " . $data["last"] . " (" . $data["user"] . ")\" }";
	}
	echo implode(", ",$d);
	echo ']';
}
?>