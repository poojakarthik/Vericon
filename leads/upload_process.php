<?php
mysql_connect('localhost','vericon','18450be');

$reportFile = "/var/vtmp/leads_report.txt";
$fh = fopen($reportFile, 'a') or die("can't open file");

$LeadsTmp = "/var/vtmp/leads_tmp.csv";
$fh0 = fopen($LeadsTmp, 'w+') or die("can't open file");

$LeadsCountFile = "/var/vtmp/leads_count.txt";
$fh1 = fopen($LeadsCountFile, 'a') or die("can't open file");

$LeadsTmpCountFile = "/var/vtmp/leads_tmp_count.txt";
$fh2 = fopen($LeadsTmpCountFile, 'a') or die("can't open file");

// Begin Report File
fwrite($fh, "<-- " . date("Y-m-d H:i:s") . " -->\n");

// Dos2Unix
exec("dos2unix /var/vtmp/leads.csv");
fwrite($fh, "Dos2Unix Completed\n");

// Remove Blanks and Move to Temporary CSV
$lines = file("/var/vtmp/leads.csv");
$i = 0;
$data = "";

foreach ($lines as $row)
{
	if (!file_exists("/var/vtmp/leads_cancel.txt"))
	{
		$da = explode(",", $row);
		if (preg_match('/([2378])([0-9]{8})/', $da[0]) && $da[1] != "")
		{
			$data .= $da[0] . "," . $da[1] . "," . $da[2] . "," . $da[3] . "," . $da[4];
		}
		$i++;
		fwrite($fh2, $i . "\n");
	}
	else
	{
		break;
	}
}
fclose($fh2);
fwrite($fh0, $data);
fclose($fh0);
fwrite($fh, "Copied to Temporary Leads CSV\n");

// Upload Leads
$lines = file("/var/vtmp/leads_tmp.csv");
$i = 0;

foreach ($lines as $row)
{
	if (!file_exists("/var/vtmp/leads_cancel.txt"))
	{
		$da = explode(",", $row);
		// check centre format
		if (substr($da[1],0,2) == "CC")
		{
			$centre = substr($da[1],0,4);
		}
		else
		{
			$centre = $da[1];
		}
		$timestamp = date("Y-m-d H:i:s");
		
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
		
		mysql_query("INSERT INTO leads.leads (`cli`, `centre`, `issue_date`, `expiry_date`, `packet_expiry`) VALUES ('$da[0]', '$centre', '$issue_date', '$expiry_date', '$packet_expiry') ON DUPLICATE KEY UPDATE centre = '$centre', issue_date = '$issue_date', expiry_date = '$expiry_date', packet_expiry = '$packet_expiry'") or die(mysql_error());
		
		mysql_query("INSERT INTO leads.log_leads (`cli`, `centre`, `issue_date`, `expiry_date`, `packet_expiry`) VALUES ('$da[0]', '$centre', '$issue_date', '$expiry_date', '$packet_expiry')") or die(mysql_error());
		
		mysql_query("INSERT INTO leads.leads_time (`centre`, `timestamp`) VALUES ('$centre', '$timestamp')  ON DUPLICATE KEY UPDATE `timestamp` = '$timestamp'") or die(mysql_error());
		
		$i++;
		fwrite($fh1, $i . "\n");
	}
	else
	{
		break;
	}
}
fclose($fh1);
fwrite($fh, "Uploaded " . $i . " Leads\n");

// Remove tmp Files
unlink($LeadsCountFile);
unlink($LeadsTmpCountFile);
unlink("/var/vtmp/leads_tmp.csv");
fwrite($fh, "Removed tmp Files\n");

// Move leads File to Archive
exec("mv /var/vtmp/leads.csv /home/leads/archive/" . date("Y_m_d-H_i_s") . ".csv");
fwrite($fh, "Moved leads File to Archive\n");

// Check if cancelled
if (!file_exists("/var/vtmp/leads_cancel.txt"))
{
	fwrite($fh, "Successfully Uploaded Leads\n");
}
else
{
	fwrite($fh, "Leads Upload was Cancelled\n");
	unlink("/var/vtmp/leads_cancel.txt");
}

// Complete Report
fwrite($fh, "<-- " . date("Y-m-d H:i:s") . " -->");
fclose($fh);
exec("mv /var/vtmp/leads_report.txt /home/leads/log/" . date("Y_m_d-H_i_s") . ".txt");
?>