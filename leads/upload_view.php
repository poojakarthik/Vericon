<?php
$method = $_GET["method"];

if ($method == "begin")
{
	if (!file_exists("/var/vericon/leads/process/report.txt"))
	{
		exec('php /var/vericon/leads/upload_process.php > /dev/null 2>/dev/null &');
		echo 1;
	}
}
elseif ($method == "view")
{
	$file0 = "/var/vericon/leads/process/leads.csv";
	$init_lines = count(file($file0));
	
	$file = "/var/vericon/leads/process/tmp.csv";
	$total_lines = count(file($file));
	
	$file1 = "/var/vericon/leads/process/leads.txt";
	$leads_lines = count(file($file1));
	if ($leads_lines != 0)
	{
		$leads_lines--;
	}
	
	$file2 = "/var/vericon/leads/process/leads_log.txt";
	$leads_log_lines = count(file($file2));
	if ($leads_log_lines != 0)
	{
		$leads_log_lines--;
	}
	
	$file3 = "/var/vericon/leads/process/convert.txt";
	$convert_lines = count(file($file3));
	if ($convert_lines != 0)
	{
		$convert_lines--;
	}
	
	echo "<br><center><table width='98%'>";
	echo "<tr>";
	echo "<td width='12%'><b>Format Date </b></td>";
	echo "<td width='78%' style='text-align:center;'>"; ?>
	<div class="ui-progressbar ui-widget ui-widget-content ui-corner-all">
		<div style="width: <?php echo number_format(($convert_lines/$init_lines)*100,0); ?>%;" class="ui-progressbar-value ui-widget-header ui-corner-left"></div>
	</div>
	<?php echo "</td>";
	echo "<td width='5%' style='text-align:center;'>" . number_format(($convert_lines/$init_lines)*100,0) . "%</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td width='12%'><b>Leads </b></td>";
	echo "<td width='78%' style='text-align:center;'>"; ?>
	<div class="ui-progressbar ui-widget ui-widget-content ui-corner-all">
		<div style="width: <?php echo number_format(($leads_lines/$total_lines)*100,0); ?>%;" class="ui-progressbar-value ui-widget-header ui-corner-left"></div>
	</div>
	<?php echo "</td>";
	echo "<td width='5%' style='text-align:center;'>" . number_format(($leads_lines/$total_lines)*100,0) . "%</td>";
	echo "</tr>";
	echo "<tr>";
	echo "<td width='12%'><b>Leads Log </b></td>";
	echo "<td width='78%' style='text-align:center;'>"; ?>
	<div class="ui-progressbar ui-widget ui-widget-content ui-corner-all">
		<div style="width: <?php echo number_format(($leads_log_lines/$total_lines)*100,0); ?>%;" class="ui-progressbar-value ui-widget-header ui-corner-left"></div>
	</div>
	<?php echo "</td>";
	echo "<td width='5%' style='text-align:center;'>" . number_format(($leads_log_lines/$total_lines)*100,0) . "%</td>";
	echo "</tr>";
	echo "</table></center>";
}
elseif ($method == "check")
{
	if (file_exists("/var/vericon/leads/process/report.txt"))
	{
		echo 1;
	}
	else
	{
		echo 0;
	}
}
elseif ($method == "complete")
{
	unlink("/var/vericon/leads/process/tmp.csv");
	unlink("/var/vericon/leads/process/convert.txt");
	unlink("/var/vericon/leads/process/leads.txt");
	unlink("/var/vericon/leads/process/leads_log.txt");
}
elseif ($method == "last")
{
	$file = scandir("/home/leads/log", 1);
	echo "<pre>";
	readfile("/home/leads/log/" . $file[0]);
	echo "</pre>";
}
else
{
	if (file_exists("/var/vericon/leads/process/report.txt"))
	{
		echo "Already Uploading";
	}
}
?>