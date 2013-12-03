<?php
mysql_connect('localhost','vericon','18450be');

$date = $_GET["date"];
$verifier = array();
$approved = array();
$declined = array();
$line_issue = array();

if (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $date)) {
	$q = mysql_query("SELECT `tpv_notes`.`verifier`, `tpv_notes`.`status`, COUNT(`tpv_notes`.`id`), CONCAT(`auth`.`first`,' ',`auth`.`last`) FROM `vericon`.`tpv_notes`, `vericon`.`auth` WHERE `auth`.`type` LIKE '%TPV%' AND DATE(`tpv_notes`.`timestamp`) = '" . mysql_real_escape_string($date) . "' AND `auth`.`user` = `tpv_notes`.`verifier` GROUP BY `tpv_notes`.`verifier`, `tpv_notes`.`status` ORDER BY `tpv_notes`.`verifier` ASC") or die(mysql_error());
	while ($data = mysql_fetch_row($q)) {
		$verifier[$data[0]] = $data[3];
				
		if ($data[1] == "Approved") {
			$approved[$data[0]] = $data[2];
		} elseif ($data[1] == "Declined") {
			$declined[$data[0]] = $data[2];
		} elseif ($data[1] == "Line Issue") {
			$line_issue[$data[0]] = $data[2];
		}
	}
	
	$filename = "TPV_Stats_" . $date . ".csv";
	header("Content-type: application/x-msdownload");
	header("Content-Disposition: attachment; filename=$filename");
	header("Pragma: no-cache");
	header("Expires: 0");
	
	echo "Name,Approved,Declined,Line Issue\n";
	
	foreach ($verifier as $key => $value) {
		echo $value . "," . number_format($approved[$key], 0) . "," . number_format($declined[$key], 0) . "," . number_format($line_issue[$key], 0) . "\n";
	}
} else {
	echo "<h1>Forbidden</h1>";
}
?>