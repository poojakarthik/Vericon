<?php
$mysqli_rc = new mysqli('localhost','roundcube','18450be');
$mysqli_vc = new mysqli('localhost','vericon','18450be');

$q = $mysqli_rc->query("SELECT `users`.`user_id`, `users`.`username`, `identities`.`name` FROM `roundcubemail`.`users`, `roundcubemail`.`identities` WHERE `users`.`user_id` = `identities`.`user_id`") or die($mysqli_rc->error);
while ($rc_user = $q->fetch_assoc())
{
        $q1 = $mysqli_vc->query("SELECT CONCAT(`first`, ' ', `last`) FROM `vericon`.`auth` WHERE `user` = '" . $mysqli_vc->real_escape_string($rc_user["username"]) . "'") or die($mysqli_vc->error);
        $vc_name = $q1->fetch_row();
        if ($rc_user["name"] != $vc_name[0])
        {
                $mysqli_rc->query("UPDATE `roundcubemail`.`identities` SET `name` = '" . $mysqli_rc->real_escape_string($vc_name[0]) . "' WHERE `user_id` = '" . $mysqli_rc->real_escape_string($rc_user["user_id"]) . "'") or die($mysqli_rc->error);
        }
		$q1->free();
}
$q->free();

$mysqli_rc->close();
$mysqli_vc->close();
?>