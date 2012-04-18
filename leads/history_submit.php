<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$id = $_GET["lead"];

if (preg_match('/0([2378])([0-9]{8})/',$id))
{
	$id = substr($id,1,9);
	
	$q = mysql_query("SELECT * FROM log_leads WHERE cli = '$id'") or die(mysql_error());
	while ($data = mysql_fetch_row($q))
	{
		
	}
}
elseif (preg_match('/([2378])([0-9]{8})/',$id))
{
	$q = mysql_query("SELECT * FROM log_leads WHERE cli = '$id'") or die(mysql_error());
}
else
{
	$result = "Invalid Lead ID";
}

?>