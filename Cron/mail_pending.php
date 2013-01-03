<?php
$mysqli = new mysqli('localhost','vericon','18450be');

$q = $mysqli->query("SELECT * FROM `vericon`.`mail_pending`") or die($mysqli->error);
while ($users = $q->fetch_assoc())
{
	if ($users["action"] == "create")
	{
		$result = exec("cat /var/vc_tmp/new_email | grep " . $users["user"]);
		if ($result == "" || $result == "cat: /var/vc_tmp/new_email: No such file or directory")
		{
			$mysqli->query("DELETE FROM `vericon`.`mail_pending` WHERE `user` = '" . $mysqli->real_escape_string($users["user"]) . "' LIMIT 1") or die($mysqli->error);
		}
	}
	elseif ($users["action"] == "edit")
	{
		$result = exec("cat /var/vc_tmp/edit_email | grep " . $users["user"]);
		if ($result == "" || $result == "cat: /var/vc_tmp/edit_email: No such file or directory")
		{
			$mysqli->query("DELETE FROM `vericon`.`mail_pending` WHERE `user` = '" . $mysqli->real_escape_string($users["user"]) . "' LIMIT 1") or die($mysqli->error);
		}
	}
}
$q->free();

$mysqli->close();
?>