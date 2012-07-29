<?php
mysql_connect('localhost','vericon','18450be');

//log everyone out
mysql_query("DELETE FROM vericon.currentuser") or die(mysql_error());

//clear sales customers temporary
mysql_query("DELETE FROM vericon.sales_customers_temp") or die(mysql_error());

//clear sales temporary packages
mysql_query("DELETE FROM vericon.sales_packages_temp") or die(mysql_error());
?>