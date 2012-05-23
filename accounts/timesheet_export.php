<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$centre = $_GET["centre"];
$date = $_GET["date"];
$week = date("W", strtotime($date));
$year = date("Y", strtotime($date));
$date1 = date("Y-m-d", strtotime($year . "W" . $week . "1"));
$week1 = date("W", strtotime($date1));
$date2 = date("Y-m-d", strtotime($year . "W" . $week . "7"));
$week2 = date("W", strtotime($date2));

$q = mysql_query("SELECT * FROM timesheet WHERE centre = '$centre' AND date BETWEEN '$date1' AND '$date2' GROUP BY user ORDER BY user ASC") or die(mysql_error());

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
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', $centre . ' Timesheet ' . date("d/m/Y", strtotime($date1)) . " to " . date("d/m/Y", strtotime($date2)))
				->setCellValue('E3', 'Hours')
				->setCellValue('H3', 'Sales')
				->setCellValue('K3', 'Bonus')
				->setCellValue('M3', 'Pay')
				->setCellValue('B4', '#')
				->setCellValue('C4', 'Agent Name')
				->setCellValue('D4', 'Agent ID')
				->setCellValue('E4', 'Timesheet Hours')
				->setCellValue('F4', 'Dialler Hours')
				->setCellValue('G4', 'Operational Hours')
				->setCellValue('H4', 'Timesheet Sales')
				->setCellValue('I4', 'Cancellations')
				->setCellValue('J4', 'Net Sales')
				->setCellValue('K4', 'Timesheet Bonus')
				->setCellValue('L4', 'Payable Bonus')
				->setCellValue('M4', 'Rate')
				->setCellValue('N4', 'Gross Pay')
				->setCellValue('O4', 'Gross Pay (Inc. Super)')
				->setCellValue('P4', 'PAYG')
				->setCellValue('Q4', 'Net Pay')
				->setCellValue('R4', 'CPS');
	
	$i = 5;
	$count = 1;
	while ($data = mysql_fetch_assoc($q))
	{
		$q0 = mysql_query("SELECT first,last FROM auth WHERE user = '$data[user]'") or die(mysql_error());
		$user = mysql_fetch_row($q0);
		
		$q1 = mysql_query("SELECT SUM(hours),SUM(dialler_hours),SUM(bonus) FROM timesheet WHERE date BETWEEN '$date1' AND '$date2' AND user = '$data[user]'") or die(mysql_error());
		$da = mysql_fetch_row($q1);
		
		$q2 = mysql_query("SELECT SUM(cancellations),SUM(op_hours),SUM(op_bonus),AVG(rate),SUM(payg) FROM timesheet_other WHERE user = '$data[user]' AND week BETWEEN '$week1' AND '$week2'") or die(mysql_error());
		$da2 = mysql_fetch_row($q2);
		
		$q3 = mysql_query("SELECT * FROM sales_customers WHERE agent = '$data[user]' AND status = 'Approved' AND approved_timestamp BETWEEN '$date1' AND '$date2'") or die(mysql_error());
		$sales = mysql_num_rows($q3);
		
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('B' . $i, $count)
					->setCellValue('C' . $i, $user[0] . " " . $user[1])
					->setCellValue('D' . $i, $data["user"])
					->setCellValue('E' . $i, $da[0])
					->setCellValue('F' . $i, $da[1])
					->setCellValue('G' . $i, $da2[1])
					->setCellValue('H' . $i, $sales)
					->setCellValue('I' . $i, $da2[0])
					->setCellValue('J' . $i, "=H" . $i . "-I" . $i)
					->setCellValue('K' . $i, $da[2])
					->setCellValue('L' . $i, $da2[2])
					->setCellValue('M' . $i, $da2[3])
					->setCellValue('N' . $i, '=(G' . $i . '*M' . $i . ')+L' . $i)
					->setCellValue('O' . $i, '=(N' . $i . '*9%)+N' . $i)
					->setCellValue('P' . $i, $da2[4])
					->setCellValue('Q' . $i, '=N' . $i . '-P' . $i)
					->setCellValue('R' . $i, "=IF(J" . $i . ">0,O" . $i . "/J" . $i . ",O" . $i . ")");
		$i++;
		$count++;
	}
	
	$q1 = mysql_query("SELECT SUM(m_cost) FROM timesheet_mcost WHERE centre = '$centre' AND week BETWEEN '$week1' AND '$week2'") or die(mysql_error());
	$m_cost = mysql_fetch_row($q1);
	$sum = $i - 1;
	
	$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('B' . $i, 'Total')
					->setCellValue('E' . $i, '=SUM(E5:E' . $sum . ')')
					->setCellValue('F' . $i, '=SUM(F5:F' . $sum . ')')
					->setCellValue('G' . $i, '=SUM(G5:G' . $sum . ')')
					->setCellValue('H' . $i, '=SUM(H5:H' . $sum . ')')
					->setCellValue('I' . $i, '=SUM(I5:I' . $sum . ')')
					->setCellValue('J' . $i, '=SUM(J5:J' . $sum . ')')
					->setCellValue('K' . $i, '=SUM(K5:K' . $sum . ')')
					->setCellValue('L' . $i, '=SUM(L5:L' . $sum . ')')
					->setCellValue('N' . $i, '=SUM(N5:N' . $sum . ')')
					->setCellValue('O' . $i, '=SUM(O5:O' . $sum . ')')
					->setCellValue('P' . $i, '=SUM(P5:P' . $sum . ')')
					->setCellValue('Q' . $i, '=SUM(Q5:Q' . $sum . ')')
					->setCellValue('R' . $i, "=IF(J" . $i . ">0,O" . $i . "/J" . $i . ",O" . $i . ")");
					
	$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('B' . ($i + 2), 'Management Cost')
					->setCellValue('D' . ($i + 2), $m_cost[0])
					->setCellValue('B' . ($i + 3), 'Total Gross Pay')
					->setCellValue('D' . ($i + 3), '=O' . $i . '+D' . ($i + 2))
					->setCellValue('B' . ($i + 4), 'Final CPS')
					->setCellValue('D' . ($i + 4), '=D' . ($i + 3) . '/J' . $i);
	
	// Merge cells
	$objPHPExcel->getActiveSheet()->mergeCells('A1:R2');
	$objPHPExcel->getActiveSheet()->mergeCells('E3:G3');
	$objPHPExcel->getActiveSheet()->mergeCells('H3:J3');
	$objPHPExcel->getActiveSheet()->mergeCells('K3:L3');
	$objPHPExcel->getActiveSheet()->mergeCells('M3:R3');
	$objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':D' . $i);
	$objPHPExcel->getActiveSheet()->mergeCells('B' . ($i + 2) . ':C' . ($i + 2));
	$objPHPExcel->getActiveSheet()->mergeCells('B' . ($i + 3) . ':C' . ($i + 3));
	$objPHPExcel->getActiveSheet()->mergeCells('B' . ($i + 4) . ':C' . ($i + 4));
	
	// Set cell number formats
	$objPHPExcel->getActiveSheet()->getStyle('K5:R' . $i)->getNumberFormat()->setFormatCode('$#,##0.00_);[Red]($#,##0.00)');
	$objPHPExcel->getActiveSheet()->getStyle('D' . ($i + 2) . ':D' . ($i + 4))->getNumberFormat()->setFormatCode('$#,##0.00_);[Red]($#,##0.00)');
	
	// Set column widths
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(1);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(2.71);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15.43);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(11.71);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(14.86);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12.14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15.71);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(13.29);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(11);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(18);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(11);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(11);

	// Set fonts
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B3:R4')->getFont()->setSize(10);
	$objPHPExcel->getActiveSheet()->getStyle('B3:R4')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B5:R' . $i)->getFont()->setSize(10);
	$objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':R' . $i)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B' . ($i + 2) . ':D' . ($i + 4))->getFont()->setSize(10);
	$objPHPExcel->getActiveSheet()->getStyle('B' . ($i + 2) . ':D' . ($i + 4))->getFont()->setBold(true);
	
	// Set alignments
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('B4:B' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('E3:R' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('C4:D' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':R' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('B' . ($i + 2) . ':C' . ($i + 4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('D' . ($i + 2) . ':D' . ($i + 4))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
	// Set medium border outline
	$styleMediumBorderOutline = array(
		'borders' => array(
			'outline' => array(
				'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
				'color' => array('argb' => 'FF000000'),
			),
		),
	);
	$objPHPExcel->getActiveSheet()->getStyle('E3:G3')->applyFromArray($styleMediumBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('H3:J3')->applyFromArray($styleMediumBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('K3:L3')->applyFromArray($styleMediumBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('M3:R3')->applyFromArray($styleMediumBorderOutline);
	
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
	$objPHPExcel->getActiveSheet()->getStyle('B4:R4')->applyFromArray($styleMediumBorderFill);
	
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
	$objPHPExcel->getActiveSheet()->getStyle('B5:D' . $i)->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('E5:G' . $i)->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('H5:J' . $i)->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('K5:L' . $i)->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('M5:R' . $i)->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':D' . $i)->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('E' . $i . ':G' . $i)->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('H' . $i . ':J' . $i)->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('K' . $i . ':L' . $i)->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('M' . $i . ':R' . $i)->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('B' . ($i + 2) . ':D' . ($i+4))->applyFromArray($styleMediumThinBorderFill);
	
	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('Sheet1');
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	// Redirect output to a client’s web browser (Excel2007)
	$date1 = date("d-m-Y", strtotime($date1));
	$date2 = date("d-m-Y", strtotime($date2));
	$filename = $centre . "_Timesheet_" . $date1 . "_to_" . $date2 . ".xlsx";
	
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename=' . $filename);
	header('Cache-Control: max-age=0');
	
	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$objWriter->save('php://output');
	exit;
}
else
{
?>
<script>
window.close();
</script>
<?php
}
?>