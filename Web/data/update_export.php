<?php
$method = $_GET["method"];
$d = date("Y-m-d", strtotime("-1day"));
$date = date("d.m.Y", strtotime($d));

$filename = "Update_DSR_" . $date . "_" . $method . ".csv";
header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
readfile("/var/vericon/dsr/" . date("Y/F", strtotime($d)) . "/" . $date . "/Update/" . $filename);
?>