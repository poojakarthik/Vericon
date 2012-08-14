<?php
mysql_connect('localhost','vericon','18450be');

//log everyone out
mysql_query("TRUNCATE TABLE vericon.currentuser") or die(mysql_error());

//clear sales customers temporary
mysql_query("TRUNCATE TABLE vericon.sales_customers_temp") or die(mysql_error());

//clear sales temporary packages
mysql_query("TRUNCATE TABLE vericon.sales_packages_temp") or die(mysql_error());

//clear welcome call backs
mysql_query("DELETE FROM vericon.welcome_cb WHERE DATE(time) < '" . mysql_real_escape_string(date("Y-m-d")) . "' OR DATE(time) > '" . mysql_real_escape_string(date("Y-m-d", strtotime("+2 days"))) . "'") or die(mysql_error());
?>