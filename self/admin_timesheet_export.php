<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$date = $_GET["date"];
$centre = $_GET["centre"];
$user = $_GET["user"];

$q = mysql_query("SELECT * FROM auth,timesheet WHERE auth.centre = '$centre' AND timesheet.date = '$date' AND auth.user = timesheet.user ORDER BY timesheet.user ASC") or die(mysql_error());

if (mysql_num_rows($q) != 0)
{
	$q1 = mysql_query("SELECT campaign FROM centres WHERE centre = '$centre'") or die(mysql_error());
	$campaign = mysql_fetch_row($q1);
	
	$q2 = mysql_query("SELECT first,last FROM auth WHERE user = '$user'") or die(mysql_error());
	$t = mysql_fetch_row($q2);
	$tl = $t[0] . " " . $t[1];
	
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
	$i = 17;
	while ($data = mysql_fetch_assoc($q))
	{
		$q3 = mysql_query("SELECT * FROM sales_customers WHERE agent = '$da[user]' AND status = 'Approved' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
		$sales = mysql_num_rows($q3);
		
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('B' . $i, $data["first"] . " " . $data["last"])
					->setCellValue('C' . $i, date("H:i", strtotime($data["start"])))
					->setCellValue('D' . $i, date("H:i", strtotime($data["end"])))
					->setCellValue('E' . $i, $data["hours"])
					->setCellValue('F' . $i, $sales)
					->setCellValue('G' . $i, $data["bonus"]);
		$i++;
	}
	
	$sum = $i - 1;
	
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('B' . $i, 'Total')
				->setCellValue('E' . $i, '=SUM(E17:E' . $sum . ')')
				->setCellValue('F' . $i, '=SUM(F17:F' . $sum . ')')
				->setCellValue('G' . $i, '=SUM(G17:G' . $sum . ')')
				->setCellValue('H' . $i, '=SUM(H17:H' . $sum . ')');
	
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('B2', $centre . ' Timesheet')
				->setCellValue('B5', 'Campaign')
				->setCellValue('E5', $campaign[0])
				->setCellValue('B7', 'Team Leader')
				->setCellValue('E7', $tl)
				->setCellValue('B9', 'Date')
				->setCellValue('E9', date("d/m/Y", strtotime($date)))
				->setCellValue('B11', 'Total Sales')
				->setCellValue('C11', '=F' . $i)
				->setCellValue('E11', 'Average SPH')
				->setCellValue('H11', '=(C11/C12)*0.65')
				->setCellValue('B12', 'Total Hours')
				->setCellValue('C12', '=E' . $i)
				->setCellValue('E12', 'Net Income')
				->setCellValue('H12', '=150*(C11*0.65)')
				->setCellValue('B13', 'Total Agents')
				->setCellValue('C13', mysql_num_rows($q))
				->setCellValue('E13', 'Net Expense')
				->setCellValue('H13', '=(C12*19)+G' . $i . '+350')
				->setCellValue('B14', 'Average SPD')
				->setCellValue('C14', '=C11/C13')
				->setCellValue('E14', 'Net Gain')
				->setCellValue('H14', '=H12-H13')
				->setCellValue('B16', 'Agent Name')
				->setCellValue('C16', 'Start Time')
				->setCellValue('D16', 'End Time')
				->setCellValue('E16', 'Hours')
				->setCellValue('F16', 'Sales')
				->setCellValue('G16', 'Bonus')
				->setCellValue('H16', 'Adjusted Sales');
	
	// Merge cells
	$objPHPExcel->getActiveSheet()->mergeCells('B2:H3');
	$objPHPExcel->getActiveSheet()->mergeCells('B5:C5');
	$objPHPExcel->getActiveSheet()->mergeCells('E5:H5');
	$objPHPExcel->getActiveSheet()->mergeCells('B7:C7');
	$objPHPExcel->getActiveSheet()->mergeCells('E7:H7');
	$objPHPExcel->getActiveSheet()->mergeCells('B9:C9');
	$objPHPExcel->getActiveSheet()->mergeCells('E9:H9');
	$objPHPExcel->getActiveSheet()->mergeCells('E11:G11');
	$objPHPExcel->getActiveSheet()->mergeCells('E12:G12');
	$objPHPExcel->getActiveSheet()->mergeCells('E13:G13');
	$objPHPExcel->getActiveSheet()->mergeCells('E14:G14');
	$objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':D' . $i);
	
	// Set document security
	$objPHPExcel->getSecurity()->setWorkbookPassword("testpass");
	$objPHPExcel->getSecurity()->setLockWindows(true);
	$objPHPExcel->getSecurity()->setLockStructure(true);
	
	// Protect cells
	$objPHPExcel->getActiveSheet()->getProtection()->setPassword("testpass");
	$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
	
	// Unprotect a cell
	$objPHPExcel->getActiveSheet()->getStyle('H17:H' . $sum)->getProtection()->setLocked(PHPExcel_Style_Protection::PROTECTION_UNPROTECTED);
	
	// Set cell number formats
	$objPHPExcel->getActiveSheet()->getStyle('H12')->getNumberFormat()->setFormatCode('$#,##0_);[Red]($#,##0)');
	$objPHPExcel->getActiveSheet()->getStyle('H13')->getNumberFormat()->setFormatCode('$#,##0_);[Red]($#,##0)');
	$objPHPExcel->getActiveSheet()->getStyle('H14')->getNumberFormat()->setFormatCode('$#,##0_);[Red]($#,##0)');
	
	// Set column widths
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(28.68);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8.14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(7.86);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15.43);
	
	// Set fonts
	$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setSize(26);
	$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B5:E9')->getFont()->setSize(14);
	$objPHPExcel->getActiveSheet()->getStyle('B5:E9')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B11:H14')->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('B11:H14')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B11:B14')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
	$objPHPExcel->getActiveSheet()->getStyle('E11:E14')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
	$objPHPExcel->getActiveSheet()->getStyle('B16:H16')->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('B16:H16')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
	$objPHPExcel->getActiveSheet()->getStyle('B16:H' . $i)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':H' . $i)->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
	
	// Set alignments
	$objPHPExcel->getActiveSheet()->getStyle('A1:H16')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('E7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('E9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('C11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('C12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('C13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('C14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('H11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('H12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('H13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('H14')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('C16:H' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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
	$objPHPExcel->getActiveSheet()->getStyle('B2:H3')->applyFromArray($styleMediumBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('B5:C5')->applyFromArray($styleMediumBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('E5:H5')->applyFromArray($styleMediumBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('B7:C7')->applyFromArray($styleMediumBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('E7:H7')->applyFromArray($styleMediumBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('B9:C9')->applyFromArray($styleMediumBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('E9:H9')->applyFromArray($styleMediumBorderOutline);
	
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
	$objPHPExcel->getActiveSheet()->getStyle('B16:H16')->applyFromArray($styleMediumBorderFill);
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
	$objPHPExcel->getActiveSheet()->getStyle('B11:C14')->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('E11:H14')->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('B17:H' . $i)->applyFromArray($styleMediumThinBorderFill);
	
	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('Sheet1');
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	// Redirect output to a client’s web browser (Excel2007)
	$date = date("d-m-Y", strtotime($date));
	$filename = $centre . "_Timesheet_" . $date . ".xlsx";
	
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename=' . $filename);
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;
}
else
{
	
}
?>