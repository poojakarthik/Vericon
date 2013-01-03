<?php
$rc_link = mysql_connect('localhost','roundcube','18450be');
$vc_link = mysql_connect('localhost','vericon','18450be');

$q = mysql_query("SELECT `users`.`user_id`, `users`.`username`, `identities`.`name` FROM `roundcubemail`.`users`, `roundcubemail`.`identities` WHERE `users`.`user_id` = `identities`.`user_id`", $rc_link) or die(mysql_error());
while ($rc_user = mysql_fetch_assoc($q))
{
        $q1 = mysql_query("SELECT CONCAT(`first`, ' ', `last`) FROM `vericon`.`auth` WHERE `user` = '" . mysql_real_escape_string($rc_user["username"]) . "'", $vc_link) or die(mysql_error());
        $vc_name = mysql_fetch_row($q1);
        if ($rc_user["name"] != $vc_name[0])
        {
                mysql_query("UPDATE `roundcubemail`.`identities` SET `name` = '" . mysql_real_escape_string($vc_name[0]) . "' WHERE `user_id` = '" . mysql_real_escape_string($rc_user["user_id"]) . "'", $rc_link) or die(mysql_error());
        }
}
?>