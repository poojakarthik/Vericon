<?php
$mysqli = new mysqli('localhost','letters','18450be');

define('FPDF_FONTPATH','lib/fpdf/fonts');
require_once('lib/fpdf/fpdf.php');
require_once('lib/fpdi/fpdi.php');
require_once ("Mail.php");
require_once ("Mail/mime.php");

$q = $mysqli->query("SELECT * FROM `letters`.`temp`") or die($mysqli->error);
while($data = $q->fetch_assoc())
{
	$group = $data["group"];
	$campaign = $data["campaign"];
	$q1 = $mysqli->query("SELECT `campaign`, `number`, `website` FROM `letters`.`campaigns` WHERE `id` = '" . $data["campaign"] . "'") or die($mysqli->error);
	$da = $q1->fetch_assoc();
	$campaign_name = $da["campaign"];
	$website = $da["website"];
	$number = $da["number"];
	$q1->free();
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
	$plans = split(";", $data["plans"]);
	$spend = 0;
	
	$pdf = new FPDI();
	$pdf->AliasNbPages();
	
	// Front Page
	$pdf->AddPage();
	
	$pdf->setSourceFile('/var/letters/templates/' . $group . '/' . $campaign . '/wl.pdf');
	
	$tplIdx = $pdf->importPage(1);
	
	$pdf->useTemplate($tplIdx, 0, 0);
	
	$pdf->AddFont('Barcode','','barcode.php');
	$pdf->AddFont('Century Gothic','','century_gothic.php');
	
	$pdf->SetFont('Century Gothic','',9);
	$pdf->SetTextColor(0,0,0);
	$pdf->SetXY(18, 35);
	$pdf->MultiCell(0, 0, date("d F Y"), 0, 'L', false);
	
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
	
	$pdf->setSourceFile('/var/letters/templates/' . $group . '/prorata.pdf');
	
	$tplIdx = $pdf->importPage(1);
	
	$pdf->useTemplate($tplIdx, 0, 0);
	
	// CIS
	$pl = array();
	
	foreach($plans as $plan_code)
	{
		$q1 = $mysqli->query("SELECT `cost` FROM `letters`.`plan_matrix` WHERE `id` = '" . $plan_code . "' AND `campaign` = '" . $campaign . "'") or die($mysqli->error);
		$da = $q1->fetch_row();
		$q1->free();
		$spend += $da[0];
		
		if($pl[$plan_code] == 0)
		{
			// page 1
			$pdf->AddPage();
			
			$pdf->setSourceFile('/var/letters/templates/' . $group . '/' . $campaign . '/cis/' . $plan_code . '.pdf');
			
			$tplIdx = $pdf->importPage(1);
			
			$pdf->useTemplate($tplIdx, 0, 0);
			
			// page 2
			$pdf->AddPage();
			
			$pdf->setSourceFile('/var/letters/templates/' . $group . '/' . $campaign . '/cis/' . $plan_code . '.pdf');
			
			$tplIdx = $pdf->importPage(2);
			
			$pdf->useTemplate($tplIdx, 0, 0);
			
			$pl[$plan_code] = 1;
		}
	}
	
	$min_spend = "Minimum cost of \$" . round($spend, 2) . " per month";
	
	// DD
	$pdf->AddPage();
	
	$pdf->setSourceFile('/var/letters/templates/' . $group . '/' . $campaign . '/dd.pdf');
	
	$tplIdx = $pdf->importPage(1);
	
	$pdf->useTemplate($tplIdx, 0, 0);
	
	$pdf->AddPage();
	
	$pdf->setSourceFile('/var/letters/templates/' . $group . '/' . $campaign . '/dd.pdf');
	
	$tplIdx = $pdf->importPage(2);
	
	$pdf->useTemplate($tplIdx, 0, 0);
	
	// SFOA
	$pdf->AddPage();
	
	$pdf->setSourceFile('/var/letters/templates/' . $group . '/' . $campaign . '/sfoa.pdf');
	
	$tplIdx = $pdf->importPage(1);
	
	$pdf->useTemplate($tplIdx, 0, 0);
	
	// Blank Page
	$pdf->AddPage();
	
	$pdf->SetXY(0, 145);
	$pdf->SetTextColor(102,102,102);
	$pdf->MultiCell(210, 0, "Blank Page", 0, 'C', false);
	
	// Section 82
	$pdf->AddPage();
	
	$pdf->setSourceFile('/var/letters/templates/' . $group . '/' . $campaign . '/section82.pdf');
	
	$tplIdx = $pdf->importPage(1);
	
	$pdf->useTemplate($tplIdx, 0, 0);
	
	$pdf->SetTextColor(0,0,0);
	$pdf->SetXY(69, 162.5);
	$pdf->Write(0, $service);
	$pdf->SetXY(69, 170);
	$pdf->Write(0, $min_spend);
	$pdf->SetXY(69, 177.5);
	$pdf->Write(0, $sale_date);
	$pdf->SetXY(69, 185);
	$pdf->Write(0, $vericon_id);
	
	$pdf->SetXY(69, 209.75);
	$pdf->Write(0, $consumer);
	$pdf->SetXY(69, 217.25);
	$pdf->Write(0, $address);
	
	$file_name = '/var/letters/new_letters/' . md5($data["id"] . date("Y-m-d H:i:s")) . '.pdf';
	
	$pdf->Output($file_name, 'F');
	
	// Email or Post Welcome Letter	
	if ($data["delivery"] == "E")
	{
		$to = array();
		$to[] = $first_name . " " . $last_name . " <" . $email . ">";
		
		$text_body = "Hello " . $first_name . ",
		
		Welcome to " . $campaign_name . ". Please find your attached Welcome Pack, containing the full details of your great new plan.
		
		If you encounter any service difficulties or faults then you simply need to contact us and we will arrange the appropriate Telstra technicians and linesmen to get your problem fixed ASAP. Just call us and we'll do the lot!
		
		If you have any problems opening the attachment, please make sure you have the latest version of Acrobat Reader, or view the Acrobat Reader Help Site. If you are still unable to view your Welcome Letter, please contact us via care@" . str_replace("www.","",$website) . " or on " . $number . ".";
		
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
				  <td align="left" style="padding-left:25px; padding-right:25px;"><font face="Tahoma, Geneva, sans-serif" color="#666666" size="-1">Welcome to ' . $campaign_name . '. Please find your attached Welcome Pack, containing the full details of your great new plan.</font></td>
				</tr>
				<tr>
				  <td>&nbsp;</td>
				</tr>
				<tr>
				  <td align="left" style="padding-left:25px; padding-right:25px;"><font face="Tahoma, Geneva, sans-serif" color="#666666" size="-1">If you encounter any service difficulties or faults then you simply need to contact us and we will arrange the appropriate Telstra technicians and linesmen to get <b>your</b> problem fixed ASAP. Just call us and we&acute;ll do the lot!</font></td>
				</tr>
				<tr>
				  <td>&nbsp;</td>
				</tr>
				<tr>
				  <td align="left" style="padding-left:25px; padding-right:25px;"><font face="Tahoma, Geneva, sans-serif" color="#666666" size="-1">If you have any problems opening the attachment, please make sure you have the latest version of <a href="http://get.adobe.com/reader/">Acrobat Reader</a>, or view the <a href="http://helpx.adobe.com/reader.html">Acrobat Reader Help Site</a>. If you are still unable to view your Welcome Letter, please contact us via <a href="mailto:care@' . str_replace("www.","",$website) . '">care@' . str_replace("www.","",$website) . '</a> or on <b>' . $number . '</b>.</font></td>
				</tr>
				<tr>
				  <td align="center" style="border-bottom:1px #CCCCCC solid;"><img src="http://' . $website . '/images/wl_emails/footer.jpg" align="middle" alt="Footer" /></td>
				</tr>
			  </table></td>
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
		$mime->addAttachment($file, 'application/pdf', "WL_" . $vericon_id . "_" . date("Ymd") . ".pdf");
		
		$mimeparams=array();
		$mimeparams['text_encoding']="8bit";
		$mimeparams['text_charset']="UTF-8";
		$mimeparams['html_charset']="UTF-8";
		$mimeparams['head_charset']="UTF-8";
		
		$body = $mime->get($mimeparams);
		$headers = $mime->headers($headers);
		$page_content = "Mail now.";
		
		$smtpinfo["host"] = "mail.customercareteam.com.au";
		$smtpinfo["port"] = "25";
		$smtpinfo["auth"] = true;
		$smtpinfo["username"] = "sys.alerts@customercareteam.com.au";
		$smtpinfo["password"] = "!Qwer$321";
		
		$mail=& Mail::factory("smtp", $smtpinfo);
		$mail->send($to, $headers, $body);
	}
	else
	{
		//copy to print folder
	}
}
$q->free();

$mysqli->close();
?>