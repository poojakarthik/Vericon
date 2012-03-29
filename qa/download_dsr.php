<?php
$date = date("d.m.Y", strtotime($_GET["date"]));

$filename = "DSR_" . $date . "_QA.csv";

header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
readfile("/home/dsr/" . date("Y/F", strtotime($_GET["date"])) . "/DSR_" . $date . "_QA.csv")
?>