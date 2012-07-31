<?php
mysql_connect('localhost','vericon','18450be');

$date = $_GET["date"];

$q = mysql_query("SELECT * FROM vericon.timesheet WHERE centre = 'Welcome' AND date = '$date' ORDER BY user ASC")or die(mysql_error());

if (mysql_num_rows($q) != 0)
{
	/** Error reporting */
	error_reporting(E_ALL);
	
	/** PHPExcel */
	require_once '../lib/PHPExcel.php';
	
	// Create new PHPExcel object
	$objPHPExcel = new PHPExcel();
	
	// Set properties
	$objPHPExcel->getProperties()->setCreator("VeriCon")
								 ->setLastModifiedBy("VeriCon");
	
	// Add some data
	$i = 12;
	while ($data = mysql_fetch_assoc($q))
	{
		$approved = 0;
		$cancelled = 0;
		$upgrade = 0;
		
		$q0 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$data[user]'") or die(mysql_error());
		$user = mysql_fetch_row($q0);
		
		$q1 = mysql_query("SELECT status, COUNT(id) FROM vericon.welcome WHERE user = '$data[user]' AND DATE(timestamp) = '$date' GROUP BY status") or die(mysql_error());
		while($data2 = mysql_fetch_row($q1))
		{		
			if ($data2[0] == "Approve") { $approved = $data2[1]; }
			elseif ($data2[0] == "Cancel") { $cancelled = $data2[1]; }
			elseif ($data2[0] == "Upgrade") { $upgrade = $data2[1]; }
		}
		
		$q2 = mysql_query("SELECT COUNT(id) FROM vericon.welcome WHERE user = '$data[user]' AND DATE(timestamp) = '$date' AND dd = '1'") or die(mysql_error());
		$dd = mysql_fetch_row($q2);
		
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('B' . $i, $user[0] . " " . $user[1])
					->setCellValue('C' . $i, date("H:i", strtotime($data["start"])))
					->setCellValue('D' . $i, date("H:i", strtotime($data["end"])))
					->setCellValue('E' . $i, $data["hours"])
					->setCellValue('F' . $i, $approved)
					->setCellValue('G' . $i, $upgrade)
					->setCellValue('H' . $i, $cancelled)
					->setCellValue('I' . $i, $dd[0]);
		$i++;
	}
	
	$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B2', 'Welcome Team Timesheet')
			->setCellValue('B5', 'Date')
			->setCellValue('F5', date("d/m/Y", strtotime($date)))
			->setCellValue('B7', 'Total Hours')
			->setCellValue('C7', '=E' . $i)
			->setCellValue('F7', 'Total Approved')
			->setCellValue('I7', '=F' . $i)
			->setCellValue('B8', 'Total Calls')
			->setCellValue('C8', '=SUM(F' . $i . ':H' . $i . ')')
			->setCellValue('F8', 'Total Upgrades')
			->setCellValue('I8', '=G' . $i)
			->setCellValue('B9', 'Total DD')
			->setCellValue('C9', '=I' . $i)
			->setCellValue('F9', 'Total Cancelled')
			->setCellValue('I9', '=H' . $i)
			->setCellValue('B11', 'Agent Name')
			->setCellValue('C11', 'Start Time')
			->setCellValue('D11', 'End Time')
			->setCellValue('E11', 'Hours')
			->setCellValue('F11', 'Approved')
			->setCellValue('G11', 'Upgrade')
			->setCellValue('H11', 'Cancelled')
			->setCellValue('I11', 'DD');
	
	// Total
	$sum = $i - 1;
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('B' . $i, 'Total')
				->setCellValue('E' . $i, '=SUM(E12:E' . $sum . ')')
				->setCellValue('F' . $i, '=SUM(F12:F' . $sum . ')')
				->setCellValue('G' . $i, '=SUM(G12:G' . $sum . ')')
				->setCellValue('H' . $i, '=SUM(H12:H' . $sum . ')')
				->setCellValue('I' . $i, '=SUM(I12:I' . $sum . ')');
	
	// Merge cells
	$objPHPExcel->getActiveSheet()->mergeCells('B2:I3');
	$objPHPExcel->getActiveSheet()->mergeCells('B5:C5');
	$objPHPExcel->getActiveSheet()->mergeCells('F5:I5');
	$objPHPExcel->getActiveSheet()->mergeCells('F7:H7');
	$objPHPExcel->getActiveSheet()->mergeCells('F8:H8');
	$objPHPExcel->getActiveSheet()->mergeCells('F9:H9');
	$objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':D' . $i);
	
	// Set column widths
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(28.68);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10.71);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10.71);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10.71);
	
	// Set fonts
	$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setSize(26);
	$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B5:I5')->getFont()->setSize(14);
	$objPHPExcel->getActiveSheet()->getStyle('B5:I5')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B7:I11')->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('B7:I11')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B7:B9')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
	$objPHPExcel->getActiveSheet()->getStyle('F7:F9')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
	$objPHPExcel->getActiveSheet()->getStyle('B11:I11')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
	$objPHPExcel->getActiveSheet()->getStyle('B12:I' . $i)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':I' . $i)->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
	
	// Set alignments
	$objPHPExcel->getActiveSheet()->getStyle('A1:I11')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('F5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('C7:C9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('I7:I9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('C11:I' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('B' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
	
	// Set medium border outline
	$styleMediumBorderOutline = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
				'color' => array('argb' => 'FF000000'),
			),
		),
	);
	$objPHPExcel->getActiveSheet()->getStyle('B2:I3')->applyFromArray($styleMediumBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('B5:C5')->applyFromArray($styleMediumBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('F5:I5')->applyFromArray($styleMediumBorderOutline);
	
	// Set medium border outline medium border inside
	$styleMediumBorderFill = array(
		'borders' => array(
			'inside' => array(
				'style' => PHPExcel_Style_Border::BORDER_MEDIUM
			),
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_MEDIUM
			),
		),
	);
	$objPHPExcel->getActiveSheet()->getStyle('B11:I11')->applyFromArray($styleMediumBorderFill);
	
	// Set medium border outline thin border inside
	$styleMediumThinBorderFill = array(
		'borders' => array(
			'inside' => array(
				'style' => PHPExcel_Style_Border::BORDER_THIN
			),
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_MEDIUM
			),
		),
	);
	$objPHPExcel->getActiveSheet()->getStyle('B7:C9')->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('F7:I9')->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('B12:I' . $i)->applyFromArray($styleMediumThinBorderFill);
	
	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('Sheet1');
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	// Redirect output to a client’s web browser (Excel2007)
	$date = date("d-m-Y", strtotime($date));
	$filename = "Welcome_Team_Timesheet_" . $date . ".xlsx";
	
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename=' . $filename);
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;
}
?>