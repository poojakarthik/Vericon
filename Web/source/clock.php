<?php
$dateTimeKolkata = new DateTime("now", new DateTimeZone("Asia/Kolkata"));

echo date("d/m/Y") . ";" . time() . ";" . $dateTimeKolkata->format("d/m/Y") . ";" . strtotime($dateTimeKolkata->format("Y-m-d H:i:s"));
?>