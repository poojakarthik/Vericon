<?php
if (!file_exists("/var/vericon/temp/dsr_loading.txt"))
{
	exec("touch /var/vericon/temp/dsr_loading.txt");
	exec("php /var/vericon/www/qa/sbt_dsr.php Business &> /dev/null");
	exec("php /var/vericon/www/qa/sbt_dsr.php Residential &> /dev/null");
	exec("php /var/vericon/www/qa/zen_dsr.php Business &> /dev/null");
	exec("php /var/vericon/www/qa/zen_dsr.php Residential &> /dev/null");
	exec("php /var/vericon/www/qa/nxt_dsr.php Business &> /dev/null");
	exec("php /var/vericon/www/qa/nxt_dsr.php Residential &> /dev/null");
	exec("php /var/vericon/www/qa/ztg_dsr.php Business &> /dev/null");
	exec("php /var/vericon/www/qa/ztg_dsr.php Residential &> /dev/null");
	exec("php /var/vericon/www/qa/ltg_dsr.php Business &> /dev/null");
	exec("php /var/vericon/www/qa/ltg_dsr.php Residential &> /dev/null");
}
?>