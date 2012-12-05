<?php
mysql_connect('localhost','vericon','18450be');

$time = date("Y-m-d H:i:s", strtotime("-30 minutes"));

//clear verification lock where open for longer than 30 minutes
mysql_query("DELETE FROM vericon.verification_lock WHERE `timestamp` < '$time'") or die(mysql_error());
?>