<?php
mysql_connect('localhost','vericon','18450be');

//log everyone out
mysql_query("TRUNCATE TABLE vericon.currentuser") or die(mysql_error());

//clear sales customers temporary
mysql_query("TRUNCATE TABLE vericon.sales_customers_temp") or die(mysql_error());

//clear sales temporary packages
mysql_query("TRUNCATE TABLE vericon.sales_packages_temp") or die(mysql_error());

//chown DSR to www-data
exec("chown -R www-data:www-data /var/dsr/");
?>