<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];

if ($method == "notes")
{
	$id = $_GET["id"];
	
	$q = mysql_query("SELECT * FROM vericon.tpv_notes WHERE id = '$id' ORDER BY timestamp DESC") or die (mysql_error());
	
	while ($tpv_notes = mysql_fetch_assoc($q))
	{
		$q1 = mysql_query("SELECT * FROM vericon.auth WHERE user = '$tpv_notes[verifier]'") or die(mysql_error());
		$vname = mysql_fetch_assoc($q1);
		
		echo "----- " . date("d/m/Y H:i:s", strtotime($tpv_notes["timestamp"])) . " - " . $vname["first"] . " " . $vname["last"] . " -----" . " (" . $tpv_notes["status"] . ")\n";
		echo $tpv_notes["note"] . "\n";
	}
}
?>