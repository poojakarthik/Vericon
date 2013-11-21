<?php
$mysqli = new mysqli('localhost','vericon','18450be');

define('FPDF_FONTPATH','/var/vericon/letters/lib/fpdf/fonts');
require_once('/var/vericon/letters/lib/fpdf/fpdf.php');
require_once('/var/vericon/letters/lib/fpdi/fpdi.php');
require_once ("Mail.php");
require_once ("Mail/mime.php");

$date = date("Y-m-d");

// ADSL MODEM SLIPS - START
$total_adsl_letters = 0;

$q = $mysqli->query("SELECT * FROM `letters`.`adsl`") or die($mysqli->error);
while ($data = $q->fetch_assoc())
{
	$total_adsl_letters++;
	
	$group = $data["group"];
	$campaign = $data["campaign"];
	$barcode = " ";
	$bsp = "";
	if ($data["bus_name"] == "")
	{
		$line1 = $data["name"];
		$line2 = $data["address_line1"];
		$line3 = $data["address_line2"];
		$line4 = "";
	}
	else
	{
		$line1 = $data["name"];
		$line2 = $data["bus_name"];
		$line3 = $data["address_line1"];
		$line4 = $data["address_line2"];
	}
	$name = $data["name"];
	$cli = "0" . $data["cli"];
	$plan = $data["plan"];
	$id = $data["id"];
	$user = $data["user"];
	$pass = $data["pass"];
	$ssid = $data["ssid"];
	$w_pass = $data["w_pass"];
	
	$pdf = new FPDI();
	$pdf->AliasNbPages();
	
	$pdf->AddPage();
	
	$pdf->setSourceFile('/var/vericon/letters/templates/' . $group . '/' . $campaign . '/adsl.pdf');
	
	$tplIdx = $pdf->importPage(1);
	
	$pdf->useTemplate($tplIdx, 0, 0);
	
	$pdf->AddFont('Barcode','','barcode.php');
	$pdf->AddFont('Century Gothic','','century_gothic.php');
	
	$pdf->SetFont('Century Gothic','',9);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetXY(18, 35);
	$pdf->MultiCell(0, 0, date("d F Y", strtotime($date)), 0, 'L', false);
	
	$pdf->SetFont('Barcode','',16);
	$pdf->SetXY(33, 58);
	$pdf->CellFit(43, 0, $barcode, 0, 0, 'L', 0, '', 0, 1);
	
	$pdf->SetFont('Century Gothic','',6);
	$pdf->SetXY(82, 57);
	$pdf->Write(0, $bsp);
	
	$pdf->SetFont('Century Gothic','',9);
	$pdf->SetXY(33, 64);
	$pdf->Write(0, $line1);
	$pdf->SetXY(33, 68);
	$pdf->Write(0, $line2);
	$pdf->SetXY(33, 72);
	$pdf->Write(0, $line3);
	$pdf->SetXY(33, 76);
	$pdf->Write(0, $line4);
	
	$pdf->SetXY(18, 90);
	$pdf->Write(0, "Hello " . $name . ",");
	
	$pdf->SetXY(127, 98.7);
	$pdf->Write(0, $cli);
	
	$pdf->SetXY(49, 128.25);
	$pdf->Write(0, $plan);
	
	$pdf->SetXY(49, 133.25);
	$pdf->Write(0, $id);
	
	$pdf->SetXY(68, 153);
	$pdf->Write(0, $user);
	
	$pdf->SetXY(68, 163);
	$pdf->Write(0, $pass);
	
	$pdf->SetXY(68, 174);
	$pdf->Write(0, $ssid);
	
	$pdf->SetXY(68, 185);
	$pdf->Write(0, $w_pass);
	
	$pdf->SetXY(68, 196);
	$pdf->Write(0, "WPA2-PSK");
	
	if (!file_exists('/var/vericon/letters/new_letters/adsl/T' . substr($campaign,0,2) . '/')) {
		mkdir('/var/vericon/letters/new_letters/adsl/T' . substr($campaign,0,2) . '/');
	}
	
	$file_name = '/var/vericon/letters/new_letters/adsl/T' . substr($campaign,0,2) . '/' . $id . '_Modem_Slip_' . date("d-m-Y", strtotime($date)) . '.pdf';
	$pdf->Output($file_name, 'F');
	
	$headCount = '/var/vericon/letters/new_letters/adsl/headCount.csv';
	
	if (!file_exists($headCount)) {
		$control = "Account Number,File Path";
		$control .= '"' . $id . '",';
		$control .= '"T' . substr($campaign,0,2) . '/' . $id . '_Modem_Slip_' . date("d-m-Y", strtotime($date)) . '.pdf",';
		$control .= "\n";
	} else {
		$control = '"' . $id . '",';
		$control .= '"T' . substr($campaign,0,2) . '/' . $id . '_Modem_Slip_' . date("d-m-Y", strtotime($date)) . '.pdf",';
		$control .= "\n";
	}
	
	$fh = fopen($headCount, 'a') or die("can't open file");
	fwrite($fh, $control);
	fclose($fh);
	
	$mysqli->query("DELETE FROM `letters`.`adsl` WHERE `id` = '" . $mysqli->real_escape_string($id) . "'") or die($mysqli->error);
}

if ($total_adsl_letters > 0)
{
	$to = array();
	$to[] = "Odai <odai@smartbusinesstelecom.com.au>";

	$text_body = "Hi,

Your stuff is done.

Thanks";
	
	$headers["From"] = "VeriCon Reports <reports@vericon.com.au>";
	$headers["To"] = $to;
	$headers["Subject"] = "Modem Slip Report";
	$headers["Content-Type"] = 'text/html; charset=UTF-8';
	$headers["Content-Transfer-Encoding"]= "8bit";
	
	$mime = new Mail_mime;
	$mime->setTXTBody($text_body);
	$mime->addAttachment('/var/vericon/letters/new_letters/adsl/headCount.csv', 'text/csv', 'headCount_' . date("d-m-Y", strtotime($date)) . '.csv');
	
	$path = '/var/vericon/letters/new_letters/adsl/';
	$folders = scandir($path);
	foreach ($folders as $folder) {
		if ($folder == '.' || $folder == '..') continue;
		if (is_dir($path . '/' . $folder)) {
			$pdf = new FPDI();
			$pdf->AliasNbPages();
			
			$pdf->AddFont('Barcode','','barcode.php');
			$pdf->AddFont('Century Gothic','','century_gothic.php');
			
			$files = scandir($path . '/' . $folder);
			foreach ($files as $file) {
				if ($file == '.' || $file == '..') continue;
				if (is_file($path . '/' . $folder . '/' . $file)) {
					$pdf->AddPage();
					$pdf->setSourceFile($path . '/' . $folder . '/' . $file);
					$tplIdx = $pdf->importPage(1);
					$pdf->useTemplate($tplIdx, 0, 0);
				}
			}
			
			$file_name = $path . '/' . $folder . '/Modem_Slips_' . date("d-m-Y", strtotime($date)) . '.pdf';
			$pdf->Output($file_name, 'F');
			
			$mime->addAttachment($file_name, 'application/pdf', $folder . '_Modem_Slips_' . date("d-m-Y", strtotime($date)) . '.pdf');
		}
	}
	
	$mimeparams=array();
	$mimeparams['text_encoding']="8bit";
	$mimeparams['text_charset']="UTF-8";
	$mimeparams['html_charset']="UTF-8";
	$mimeparams['head_charset']="UTF-8";
	
	$body = $mime->get($mimeparams);
	$headers = $mime->headers($headers);
	$page_content = "Mail now.";
	
	$smtpinfo["host"] = 'relay.jangosmtp.net';
	$smtpinfo["port"] = '25';
	$smtpinfo["auth"] = true;
	$smtpinfo["username"] = 'sbt';
	$smtpinfo["password"] = '$mart5100';
	
	$mail=& Mail::factory("smtp", $smtpinfo);
	$mail->send($to, $headers, $body);
}

exec("chown -R letters:letters /var/vericon/letters/new_letters/adsl/");
unlink('/var/vericon/letters/new_letters/adsl/headCount.csv');
// ADSL MODEM SLIPS - END

$mysqli->close();
?>