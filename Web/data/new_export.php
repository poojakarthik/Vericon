<?php
$date = date("d.m.Y", strtotime($_GET["date"]));
$folder = $_GET["folder"];
$file = $_GET["file"];

header("Content-type: application/x-msdownload");
header("Content-Disposition: attachment; filename=$file");
header("Pragma: no-cache");
header("Expires: 0");
readfile("/var/vericon/dsr/" . date("Y/F", strtotime($_GET["date"])) . "/" . $date . "/" . $folder . "/" . $file);
?>