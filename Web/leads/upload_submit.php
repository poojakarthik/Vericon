<?php
$method = $_GET["method"];

if ($method == "begin")
{
	if (!file_exists("/var/vericon/temp/leads_report.txt"))
	{
		exec('php /var/vericon/www/leads/upload_process.php > /dev/null 2>/dev/null &');
		echo 1;
	}
}
elseif ($method == "check")
{
	if (file_exists("/var/vericon/temp/leads_report.txt"))
	{
		echo 1;
	}
	else
	{
		echo 0;
	}
}
elseif ($method == "cancel")
{
	exec("touch /var/vericon/temp/leads_cancel.txt");
}
elseif ($method == "complete")
{
	unlink("/var/vericon/temp/leads_tmp.csv");
	unlink("/var/vericon/temp/leads_count.txt");
	unlink("/var/vericon/temp/leads_tmp_count.txt");
	unlink("/var/vericon/temp/leads_cancel.txt");
}
?>