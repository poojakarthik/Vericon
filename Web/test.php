<?php
$db = new mysqli("localhost", "vericon", "18450be", "letters");

$q = $db->query("SELECT `plan_matrix`.`id`, `plan_matrix`.`campaign`, `campaigns`.`group` FROM `plan_matrix`, `campaigns` WHERE `campaigns`.`id` = `plan_matrix`.`campaign`");
while ($data = $q->fetch_row())
{
	if (!file_exists("/var/vericon/letters/templates/" . $data[2] . "/" . $data[1] . "/cis/" . $data[0] . ".pdf"))
	{
		echo "/var/vericon/letters/templates/" . $data[2] . "/" . $data[1] . "/cis/" . $data[0] . ".pdf doesn't exist.<br>";
	}
}
?>