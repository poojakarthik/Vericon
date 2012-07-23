<?php
$method = $_GET["method"];
$date = date("d.m.Y", strtotime($_GET["date"]));

$filename = "DSR_" . $date . "_" . $method . ".csv";
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
readfile("/var/dsr/" . date("Y/F", strtotime($_GET["date"])) . "/" . $date . "/New/" . $filename);
?>