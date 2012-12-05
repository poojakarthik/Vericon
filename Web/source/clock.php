<?php
$dateTimeKolkata = new DateTime("now", new DateTimeZone("Asia/Kolkata"));

echo date("d/m/Y") . ";" . time() . ";" . $dateTimeKolkata->format("d/m/Y") . ";" . strtotime($dateTimeKolkata->format("d/m/Y H:i:s"));
?>