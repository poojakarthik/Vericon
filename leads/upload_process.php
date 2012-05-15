<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$reportFile = "/var/vericon/leads/process/report.txt";
$fh = fopen($reportFile, 'a') or die("can't open file");

$ConvertCountFile = "/var/vericon/leads/process/convert.txt";
$fh0 = fopen($ConvertCountFile, 'a') or die("can't open file");

$LeadsCountFile = "/var/vericon/leads/process/leads.txt";
$fh1 = fopen($LeadsCountFile, 'a') or die("can't open file");

$LeadsLogCountFile = "/var/vericon/leads/process/leads_log.txt";
$fh2 = fopen($LeadsLogCountFile, 'a') or die("can't open file");

$tmpFile = "/var/vericon/leads/process/tmp.csv";
$fh3 = fopen($tmpFile, 'w+') or die("can't open file");

// Begin Report File
fwrite($fh, "<-- " . date("Y-m-d H:i:s") . " -->\n");

// Dos2Unix
exec("dos2unix /var/vericon/leads/process/leads.csv");
fwrite($fh, "Dos2Unix Completed\n");

// Format Date
$lines = file("/var/vericon/leads/process/leads.csv");
$i = 0;

foreach ($lines as $row)
{
	if ($i > 0)
	{
		$da = explode(",", $row);
		if ($da[0] != "")
		{
			// format issue date
			$i_d = explode("/", trim($da[2]));
			$i_d = $i_d[2] . "-" . $i_d[1] . "-" . $i_d[0];
			$issue_date = date("Y-m-d", strtotime($i_d));
			
			// format expiry date
			$e_d = explode("/", trim($da[3]));
			$e_d = $e_d[2] . "-" . $e_d[1] . "-" . $e_d[0];
			$expiry_date = date("Y-m-d", strtotime($e_d));
			
			// format packet expiry
			$p_e = explode("/", trim($da[4]));
			$p_e = $p_e[2] . "-" . $p_e[1] . "-" . $p_e[0];
			$packet_expiry = date("Y-m-d", strtotime($p_e));
			
			$data .= $da[0] . "," . $da[1] . "," . $issue_date . "," . $expiry_date . "," . $packet_expiry . "\n";
		}
	}
	$i++;
	fwrite($fh0, $i . "\n");
}
fclose($fh0);
fwrite($fh3, $data);
fclose($fh3);
fwrite($fh, "Converted Dates to Correct Format\n");

// Upload to Leads Table
$lines = file("/var/vericon/leads/process/tmp.csv");
$i = 0;

foreach ($lines as $row)
{
	$data = explode(",", $row);
	if ($data[0] != "")
	{
		mysql_query("INSERT INTO leads (cli, centre, issue_date, expiry_date, packet_expiry) VALUES ('$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]') ON DUPLICATE KEY UPDATE centre = '$data[1]', issue_date = '$data[2]', expiry_date = '$data[3]', packet_expiry = '$data[4]'") or die(mysql_error());
	}
	$i++;
	fwrite($fh1, $i . "\n");
}
fclose($fh1);

fwrite($fh, "Uploaded " . ($i) . " Rows to Leads Table\n");

// Upload to Leads Log Table
$i = 0;

foreach ($lines as $row)
{
	$data = explode(",", $row);
	if ($data[0] != "")
	{
		mysql_query("INSERT INTO log_leads (cli, centre, issue_date, expiry_date, packet_expiry) VALUES ('$data[0]', '$data[1]', '$data[2]', '$data[3]', '$data[4]')") or die(mysql_error());
	}
	$i++;
	fwrite($fh2, $i . "\n");
}
fclose($fh2);

fwrite($fh, "Uploaded " . ($i) . " Rows to Leads Log Table\n");

// Remove tmp Files
unlink("/var/vericon/leads/process/tmp.csv");
unlink("/var/vericon/leads/process/convert.txt");
unlink("/var/vericon/leads/process/leads.txt");
unlink("/var/vericon/leads/process/leads_log.txt");
fwrite($fh, "Removed tmp Files\n");

// Move leads File to Archive
exec("mv /var/vericon/leads/process/leads.csv /home/leads/archive/" . date("Y_m_d-H_i_s") . ".csv");
fwrite($fh, "Moved leads File to Archive\n");

// Complete Report
fwrite($fh, "<-- " . date("Y-m-d H:i:s") . " -->");
fclose($fh);
exec("mv /var/vericon/leads/process/report.txt /home/leads/log/" . date("Y_m_d-H_i_s") . ".txt");
?>