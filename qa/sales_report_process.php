<?php
if (!file_exists("/var/vtmp/dsr_loading.txt"))
{
	exec("touch /var/vtmp/dsr_loading.txt");
	exec("php /var/vericon/qa/sbt_dsr.php Business &> /dev/null");
	exec("php /var/vericon/qa/sbt_dsr.php Residential &> /dev/null");
	exec("php /var/vericon/qa/zen_dsr.php Business &> /dev/null");
	exec("php /var/vericon/qa/zen_dsr.php Residential &> /dev/null");
}
?>