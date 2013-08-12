<?php
$file = $_GET["file"];
$filename = $_GET["filename"];

header("Content-type: application/pdf");
header("Content-Disposition: attachment; filename=$filename");
header("Pragma: no-cache");
header("Expires: 0");
readfile("/var/letters/new_letters/" . $file);
?>