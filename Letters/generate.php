<?php
$mysqli = new mysqli('localhost','vericon','18450be');

define('FPDF_FONTPATH','/var/vericon/letters/lib/fpdf/fonts');
require_once('/var/vericon/letters/lib/fpdf/fpdf.php');
require_once('/var/vericon/letters/lib/fpdi/fpdi.php');
require_once ("Mail.php");
require_once ("Mail/mime.php");

function ftp_putAll($conn_id, $src_dir, $dst_dir) {
   $d = dir($src_dir);
   while($file = $d->read()) { // do this for each file in the directory
       if ($file != "." && $file != "..") { // to prevent an infinite loop
           if (is_dir($src_dir."/".$file)) { // do the following if it is a directory
               if (!@ftp_nlist($conn_id, $dst_dir."/".$file)) {
                   ftp_mkdir($conn_id, $dst_dir."/".$file); // create directories that do not yet exist
               }
               ftp_putAll($conn_id, $src_dir."/".$file, $dst_dir."/".$file); // recursive part
           } else {
               $upload = ftp_put($conn_id, $dst_dir."/".$file, $src_dir."/".$file, FTP_BINARY); // put the files
           }
       }
   }
   $d->close();
}

$date = date("Y-m-d");

// WELCOME LETTERS - START
$total_letters = 0;
$email_letters = 0;
$posted_letters = 0;

$letter_count = "";
$headCountContent = "Campaign,Count\n";
$q = $mysqli->query("SELECT `customers`.`campaign`, `campaigns`.`campaign` FROM `letters`.`customers`, `letters`.`campaigns` WHERE `campaigns`.`id` = `customers`.`campaign` GROUP BY `customers`.`campaign`") or die($mysqli->error);
while ($campaign = $q->fetch_row())
{
	$email_count = 0;
	$post_count = 0;
	
	$q1 = $mysqli->query("SELECT COUNT(`id`), `delivery` FROM `letters`.`customers` WHERE `campaign` = '" . $campaign[0] . "' GROUP BY `delivery`") or die($mysqli->error);
	while ($data = $q1->fetch_row())
	{
		if ($data[1] == "E") {
			$email_count = $data[0];
		} elseif($data[1] == "P") {
			$post_count = $data[0];
		}
	}
	$q1->free();
	
	$q1 = $mysqli->query("SELECT COUNT(`id`) FROM `letters`.`customers` WHERE `campaign` = '" . $campaign[0] . "' AND `delivery` = 'P'") or die($mysqli->error);
	while ($data = $q1->fetch_row())
	{
		$headCountContent .= '"' . $campaign[1] . '",';
		$headCountContent .= '"' . $data[0] . '"';
		$headCountContent .= "\n";
	}
	$q1->free();
	
	$letter_count .= "
--- " . $campaign[1] . " ---
- Email: " . $email_count . " (" . number_format(($email_count/($email_count + $post_count)) * 100, 2) . "%)
- Post:  " . $post_count . " (" . number_format(($post_count/($email_count + $post_count)) * 100, 2) . "%)
";
}
$q->free();

$q = $mysqli->query("SELECT * FROM `letters`.`customers`") or die($mysqli->error);
while($data = $q->fetch_assoc())
{
	$q1 = $mysqli->query("SELECT `campaign`, `number`, `website` FROM `letters`.`campaigns` WHERE `id` = '" . $data["campaign"] . "'") or die($mysqli->error);
	$da = $q1->fetch_assoc();
	$campaign_count = $q1->num_rows;
	$q1->free();
	if ($campaign_count > 0)
	{
		$total_letters++;
		
		$group = $data["group"];
		$campaign = $data["campaign"];
		$campaign_name = $da["campaign"];
		$website = $da["website"];
		$number = $da["number"];
		$barcode = " ";
		$bsp = "";
		if ($data["type"] == "Residential")
		{
			$line1 = $data["title"] . " " . $data["firstname"] . " " . $data["lastname"];
			$line2 = $data["address_line1"];
			$line3 = $data["address_line2"];
			$line4 = "";
			$consumer = $data["title"] . " " . $data["firstname"] . " " . $data["lastname"];
		}
		else
		{
			$line1 = $data["title"] . " " . $data["firstname"] . " " . $data["lastname"];
			$line2 = $data["bus_name"];
			$line3 = $data["address_line1"];
			$line4 = $data["address_line2"];
			$consumer = $data["bus_name"];
		}
		$first_name = $data["firstname"];
		$last_name = $data["lastname"];
		$email = $data["email"];
		$service = $data["package_type"] . " Service";
		$sale_date = date("d/m/Y", strtotime($data["sale_date"]));
		$vericon_id = $data["id"];
		$address = $data["address_line1"] . ", " . $data["address_line2"];
		$spend = 0;
		
		$pdf = new FPDI();
		$pdf->AliasNbPages();
		
		// Front Page
		$pdf->AddPage();
		
		$pdf->setSourceFile('/var/vericon/letters/templates/' . $group . '/' . $campaign . '/wl-' . strtolower($data["letter_type"]) . '.pdf');
		
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
		$pdf->Write(0, "Hello " . $first_name . ",");
		
		// ProRata
		$pdf->AddPage();
		
		$pdf->setSourceFile('/var/vericon/letters/templates/' . $group . '/prorata.pdf');
		
		$tplIdx = $pdf->importPage(1);
		
		$pdf->useTemplate($tplIdx, 0, 0);
		
		// CIS
		$pl = array();
		
		$q1 = $mysqli->query("SELECT * FROM `letters`.`packages` WHERE `id` = '" . $data["id"] . "'") or die($mysqli->error);
		while ($plan = $q1->fetch_assoc())
		{
			// page 1
			$pdf->AddPage();
			
			$pdf->setSourceFile('/var/vericon/letters/templates/' . $group . '/' . $campaign . '/cis/' . $plan["plan"] . '.pdf');
			
			$tplIdx = $pdf->importPage(1);
			
			$pdf->useTemplate($tplIdx, 0, 0);
			
			$pdf->SetFont('Century Gothic','',11);
			$pdf->SetTextColor(232,108,38);
			$pdf->SetXY(81, 64);
			if (preg_match("/^[2378][0-9]{8}$/",$plan["cli"])) {
				$pdf->Write(0, "(0" . substr($plan["cli"],0,1) . ") " . substr($plan["cli"],1,4) . " " . substr($plan["cli"],-4));
			} else {
				$pdf->Write(0, "New Connection");
			}
			
			// page 2
			$pdf->AddPage();
			
			$pdf->setSourceFile('/var/vericon/letters/templates/' . $group . '/' . $campaign . '/cis/' . $plan["plan"] . '.pdf');
			
			$tplIdx = $pdf->importPage(2);
			
			$pdf->useTemplate($tplIdx, 0, 0);
		}
		$q1->free();
		
		// DD
		$pdf->AddPage();
		
		$pdf->setSourceFile('/var/vericon/letters/templates/' . $group . '/' . $campaign . '/dd.pdf');
		
		$tplIdx = $pdf->importPage(1);
		
		$pdf->useTemplate($tplIdx, 0, 0);
		
		$pdf->AddPage();
		
		$pdf->setSourceFile('/var/vericon/letters/templates/' . $group . '/' . $campaign . '/dd.pdf');
		
		$tplIdx = $pdf->importPage(2);
		
		$pdf->useTemplate($tplIdx, 0, 0);
		
		// SFOA
		$pdf->AddPage();
		
		$pdf->setSourceFile('/var/vericon/letters/templates/' . $group . '/' . $campaign . '/sfoa.pdf');
		
		$tplIdx = $pdf->importPage(1);
		
		$pdf->useTemplate($tplIdx, 0, 0);
		
		// Service Details
		$pdf->AddPage();
		
		$pdf->setSourceFile('/var/vericon/letters/templates/' . $group . '/services.pdf');
		
		$tplIdx = $pdf->importPage(1);
		
		$pdf->useTemplate($tplIdx, 0, 0);
		
		$pdf->SetFont('Century Gothic','',9);
		$pdf->SetTextColor(0,0,0);
		$y = 42.5;
		
		$q1 = $mysqli->query("SELECT * FROM `letters`.`packages` WHERE `id` = '" . $data["id"] . "'") or die($mysqli->error);
		while ($plan = $q1->fetch_assoc())
		{
			$q2 = $mysqli->query("SELECT `name`, `cost` FROM `letters`.`plan_matrix` WHERE `id` = '" . $plan["plan"] . "' AND `campaign` = '" . $campaign . "'") or die($mysqli->error);
			$da = $q2->fetch_row();
			$q2->free();
			$spend += $da[1];
			
			$pdf->SetXY(21, $y);
			if (preg_match("/^[2378][0-9]{8}$/",$plan["cli"])) {
				$pdf->Write(0, "(0" . substr($plan["cli"],0,1) . ") " . substr($plan["cli"],1,4) . " " . substr($plan["cli"],-4));
			} else {
				$pdf->Write(0, "New Connection");
			}
			$pdf->SetXY(58, $y);
			$pdf->Write(0, $da[0]);
			$pdf->SetXY(133, $y);
			$pdf->Write(0, "\$" . round($da[1],2));
			
			$y = $y + 7.5;
		}
		$q1->free();
		
		$y = $y - 2.5;
		$pdf->SetDrawColor(232,108,38);
		$pdf->SetLineWidth(0.4);
		$pdf->Line(19.25, $y, 191.75, $y);
		$pdf->Line(19.25, ($y + 10), 191.75, ($y + 10));
		
		$y = $y + 5;
		$pdf->SetXY(21, $y);
		$pdf->Write(0, "Total minimum cost of goods or services");
		$pdf->SetXY(133, $y);
		$pdf->Write(0, "\$" . round($spend, 2) . " per Month (Inc. GST)");
	
		// Section 82
		$pdf->AddPage();
		
		$pdf->setSourceFile('/var/vericon/letters/templates/' . $group . '/' . $campaign . '/section82.pdf');
		
		$tplIdx = $pdf->importPage(1);
		
		$pdf->useTemplate($tplIdx, 0, 0);
		
		$pdf->SetXY(69, 162.5);
		$pdf->Write(0, $service);
		$pdf->SetXY(69, 170);
		$pdf->Write(0, "Minimum cost of \$" . round($spend, 2) . " per month (as explained in the previous page)");
		$pdf->SetXY(69, 177.5);
		$pdf->Write(0, $sale_date);
		$pdf->SetXY(69, 185);
		$pdf->Write(0, $vericon_id);
		
		$pdf->SetXY(69, 209.75);
		$pdf->Write(0, $consumer);
		$pdf->SetXY(69, 217.25);
		$pdf->Write(0, $address);
		
		$file_name = '/var/vericon/letters/new_letters/' . md5($data["id"] . date("Y-m-d H:i:s", strtotime($date))) . '.pdf';
		
		$pdf->Output($file_name, 'F');
		
		// Email or Post Welcome Letter	
		if ($data["delivery"] == "E")
		{
			$email_letters++;
			
			$to = array();
			$to[] = $first_name . " " . $last_name . " <" . $email . ">";
			
			$text_body = "Hello " . $first_name . ",
		
We are so pleased that you have chosen to move your services to " . $campaign_name . ", and we look forward to providing you with great service for years to come.

Please find your attached Welcome Pack, containing the full details and terms of your great new plan.

If you have any problems opening the attachment, please make sure you have the latest version of Acrobat Reader, or view the Acrobat Reader Help Site. If you are still unable to view your Welcome Letter, please contact us via care@" . str_replace("www.","",$website) . " or on " . $number . ".

Thanks again and enjoy the service.

-----------------------------------
THIS IS A SYSTEM GENERATED EMAIL.
PLEASE DO NOT REPLY TO THIS EMAIL";
			
			$html_body = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<title>Welcome Letter</title>
</head>
<body>
<table width="100%" border="0" cellspacing="1" cellpadding="1" align="center">
  <tr>
	<td align="center"><table width="620" cellpadding="1" cellspacing="0" style="background-image:url(http://' . $website . '/images/wl_emails/shade.jpg); background-repeat:repeat-y;">
		<tr>
		  <td align="center" style="border-top:1px #CCCCCC solid;"><img src="http://' . $website . '/images/wl_emails/header.jpg" align="middle" alt="Header" /></td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td align="left" style="padding-left:25px; padding-right:25px;"><font face="Tahoma, Geneva, sans-serif" color="#666666" size="-1">Hello ' . $first_name . ',</font></td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td align="left" style="padding-left:25px; padding-right:25px;"><font face="Tahoma, Geneva, sans-serif" color="#666666" size="-1">We are so pleased that you have chosen to move your services to ' . $campaign_name . ', and we look forward to providing you with great service for years to come.</font></td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td align="left" style="padding-left:25px; padding-right:25px;"><font face="Tahoma, Geneva, sans-serif" color="#666666" size="-1">Please find your attached Welcome Pack, containing the full details and terms of your great new plan.</font></td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td align="left" style="padding-left:25px; padding-right:25px;"><font face="Tahoma, Geneva, sans-serif" color="#666666" size="-1">If you have any problems opening the attachment, please make sure you have the latest version of <a href="http://get.adobe.com/reader/">Acrobat Reader</a>, or view the <a href="http://helpx.adobe.com/reader.html">Acrobat Reader Help Site</a>. If you are still unable to view your Welcome Letter, please contact us via <a href="mailto:care@' . str_replace("www.","",$website) . '">care@' . str_replace("www.","",$website) . '</a> or on <b>' . $number . '</b>.</font></td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td align="left" style="padding-left:25px; padding-right:25px;"><font face="Tahoma, Geneva, sans-serif" color="#666666" size="-1">Thanks again and enjoy the service.</font></td>
		</tr>
		<tr>
		  <td align="center" style="border-bottom:1px #CCCCCC solid;"><img src="http://' . $website . '/images/wl_emails/footer.jpg" align="middle" alt="Footer" /></td>
		</tr>
	  </td>
  </tr>
</table>
<table width="100%" border="0">
  <tr>
	<td style="padding-top:10px;text-align:center;"><font face="Tahoma, Geneva, sans-serif" color="#666666" size="-2"><b>THIS IS A SYSTEM GENERATED EMAIL. PLEASE DO NOT REPLY TO THIS EMAIL</b></font></td>
  </tr>
</table>
</body>
</html>';
			
			$file = $file_name;
			
			$headers["From"] = $campaign_name . " <welcome@" . str_replace("www.","",$website) . ">";
			$headers["To"] = $to;
			$headers["Subject"] = "Welcome Letter";
			$headers["Content-Type"] = 'text/html; charset=UTF-8';
			$headers["Content-Transfer-Encoding"]= "8bit";
			
			$mime = new Mail_mime;
			$mime->setTXTBody($text_body);
			$mime->setHTMLBody($html_body);
			$mime->addAttachment($file, 'application/pdf', "WL_" . $vericon_id . "_" . date("Ymd", strtotime($date)) . ".pdf");
			
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
		else
		{
			$posted_letters++;
			
			$print_dir = '/var/vericon/letters/new_letters/pending/VeriCon_' . date("Ymd", strtotime($date)) . '/';
			if (!file_exists($print_dir)) {
				mkdir($print_dir);
			}
			$print_dir = '/var/vericon/letters/new_letters/pending/VeriCon_' . date("Ymd", strtotime($date)) . '/' . $group . '/';
			if (!file_exists($print_dir)) {
				mkdir($print_dir);
			}
			$print_dir = '/var/vericon/letters/new_letters/pending/VeriCon_' . date("Ymd", strtotime($date)) . '/' . $group . '/' . $campaign_name . '/';
			if (!file_exists($print_dir)) {
				mkdir($print_dir);
			}
			$pdf->Output(($print_dir . 'WL_' . $data["id"] . '_' . date("Ymd", strtotime($date)) . '.pdf'), 'F');
		}
		
		$mysqli->query("INSERT INTO `letters`.`log` (`id`, `wl_date`, `file_name`) VALUES ('" . $mysqli->real_escape_string($data["id"]) . "', '" . date("Y-m-d", strtotime($date)) . "', '" . str_replace('/var/vericon/letters/new_letters/', '', $file_name) . "')") or die($mysqli->error);
		
		$mysqli->query("DELETE FROM `letters`.`customers` WHERE `id` = '" . $mysqli->real_escape_string($data["id"]) . "'") or die($mysqli->error);
		$mysqli->query("DELETE FROM `letters`.`packages` WHERE `id` = '" . $mysqli->real_escape_string($data["id"]) . "'") or die($mysqli->error);
	}
}
$q->free();

if ($total_letters > 0)
{
	$headCount = '/var/vericon/letters/new_letters/pending/VeriCon_' . date("Ymd", strtotime($date)) . '/headCount.csv';
	
	$fh = fopen($headCount, 'a') or die("can't open file");
	fwrite($fh, $headCountContent);
	fclose($fh);
	
	$ftp_server = "internal.telgroup.com.au";
	$ftp_port = 5151;
	$ftp_user_name = "vericon";
	$ftp_user_pass = "18450be";
	
	$destination = "/FTP/SBT_Welcome_Letters/To_Check/";
	$source = "/var/vericon/letters/new_letters/pending/";
	
	$ftp_conn = ftp_connect($ftp_server, $ftp_port);
	$ftp_login = ftp_login($ftp_conn, $ftp_user_name, $ftp_user_pass);
	if ((!$ftp_conn) || (!$ftp_login)) {
		$email_ftp_result = "FTP Status: Couldn't Connect to FTP";
	} else {
		ftp_putAll($ftp_conn, $source, $destination);
		exec('rm -R /var/vericon/letters/new_letters/pending/VeriCon_' . date("Ymd", strtotime($date)) . '/');
		$email_ftp_result = "FTP Status: All Files Uploaded";
	}
	ftp_close($ftp_conn);
	
	$to = array();
	$to[] = "Sanjay <sanjay@smartbusinesstelecom.com.au>, Sachin <sachin@smartbusinesstelecom.com.au>, Sushma <sushma@smartbusinesstelecom.com.au>, Narayan <narayan@smartbusinesstelecom.com.au>, Kamal <kamal@smartbusinesstelecom.com.au>, Odai <odai@smartbusinesstelecom.com.au>, Printing <printing.reports@smartbusinesstelecom.com.au>";

	$text_body = "Hi All,

" . $total_letters . " have been generated today of which " . $email_letters . " (" . number_format(($email_letters/$total_letters) * 100, 2) . "%) have been emailed.
" . $email_ftp_result . "
" . $letter_count . "
Thanks";
	
	$headers["From"] = "VeriCon Reports <reports@vericon.com.au>";
	$headers["To"] = $to;
	$headers["Subject"] = "Welcome Letter Report";
	$headers["Content-Type"] = 'text/html; charset=UTF-8';
	$headers["Content-Transfer-Encoding"]= "8bit";
	
	$mime = new Mail_mime;
	$mime->setTXTBody($text_body);
	
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
// WELCOME LETTERS - END

$mysqli->close();
?>