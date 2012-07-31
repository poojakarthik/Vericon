<?php
$method = $_GET["method"];

if ($method == "begin")
{
	if (!file_exists("/var/vtmp/leads_report.txt"))
	{
		exec('php /var/vericon/leads/upload_process.php > /dev/null 2>/dev/null &');
		echo 1;
	}
}
elseif ($method == "check")
{
	if (file_exists("/var/vtmp/leads_report.txt"))
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
	exec("touch /var/vtmp/leads_cancel.txt");
}
elseif ($method == "complete")
{
	unlink("/var/vtmp/leads_tmp.csv");
	unlink("/var/vtmp/leads_count.txt");
	unlink("/var/vtmp/leads_tmp_count.txt");
	unlink("/var/vtmp/leads_cancel.txt");
}
?>