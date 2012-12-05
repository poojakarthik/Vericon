<?php
mysql_connect('localhost','vericon','18450be');

$date = $_GET["date"];

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
$c_contract = 0;
$c_fraud = 0;
$c_rates = 0;
$c_telstra = 0;
$c_other = 0;
$m_contract = 0;
$m_fraud = 0;
$m_rates = 0;
$m_telstra = 0;
$m_other = 0;
$o_contract = 0;
$o_fraud = 0;
$o_rates = 0;
$o_telstra = 0;
$o_other = 0;

$q = mysql_query("SELECT centre, cancellation_reason, COUNT(id) FROM vericon.welcome WHERE DATE(timestamp) = '$date' AND status = 'Cancel' GROUP BY centre, cancellation_reason") or die(mysql_error());
while ($data = mysql_fetch_row($q))
{
	$q1 = mysql_query("SELECT type FROM vericon.centres WHERE centre = '$data[0]'") or die(mysql_error());
	$da = mysql_fetch_row($q1);
	
	if ($da[0] == "Captive")
	{
		if ($data[1] == "Contract") { $c_contract = $data[2]; }
		elseif ($data[1] == "Fraud") { $c_fraud = $data[2]; }
		elseif ($data[1] == "Rates") { $c_rates = $data[2]; }
		elseif ($data[1] == "Telstra") { $c_telstra = $data[2]; }
		elseif ($data[1] == "Other") { $c_other = $data[2]; }
	}
	elseif ($da[0] == "Self")
	{
		if ($data[1] == "Contract") { $m_contract = $data[2]; }
		elseif ($data[1] == "Fraud") { $m_fraud = $data[2]; }
		elseif ($data[1] == "Rates") { $m_rates = $data[2]; }
		elseif ($data[1] == "Telstra") { $m_telstra = $data[2]; }
		elseif ($data[1] == "Other") { $m_other = $data[2]; }
	}
	elseif ($da[0] == "Outsourced")
	{
		if ($data[1] == "Contract") { $o_contract = $data[2]; }
		elseif ($data[1] == "Fraud") { $o_fraud = $data[2]; }
		elseif ($data[1] == "Rates") { $o_rates = $data[2]; }
		elseif ($data[1] == "Telstra") { $o_telstra = $data[2]; }
		elseif ($data[1] == "Other") { $o_other = $data[2]; }
	}
}

$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('B2', 'Cancellations - ' . date("d/m/Y", strtotime($date)))
			->setCellValue('B6', 'Captive')
			->setCellValue('B7', 'Melbourne')
			->setCellValue('B8', 'Outsourced')
			->setCellValue('B9', 'Total')
			->setCellValue('B12', 'Chart Here')
			->setCellValue('C5', 'Contract')
			->setCellValue('C6', $c_contract)
			->setCellValue('C7', $m_contract)
			->setCellValue('C8', $o_contract)
			->setCellValue('C9', '=SUM(C6:C8)')
			->setCellValue('D5', 'Fraud')
			->setCellValue('D6', $c_fraud)
			->setCellValue('D7', $m_fraud)
			->setCellValue('D8', $o_fraud)
			->setCellValue('D9', '=SUM(D6:D8)')
			->setCellValue('E5', 'Rates')
			->setCellValue('E6', $c_rates)
			->setCellValue('E7', $m_rates)
			->setCellValue('E8', $o_rates)
			->setCellValue('E9', '=SUM(E6:E8)')
			->setCellValue('F5', 'Telstra')
			->setCellValue('F6', $c_telstra)
			->setCellValue('F7', $m_telstra)
			->setCellValue('F8', $o_telstra)
			->setCellValue('F9', '=SUM(F6:F8)')
			->setCellValue('G5', 'Other')
			->setCellValue('G6', $c_other)
			->setCellValue('G7', $m_other)
			->setCellValue('G8', $o_other)
			->setCellValue('G9', '=SUM(G6:G8)')
			->setCellValue('H5', 'Total')
			->setCellValue('H6', '=SUM(C6:G6)')
			->setCellValue('H7', '=SUM(C7:G7)')
			->setCellValue('H8', '=SUM(C8:G8)')
			->setCellValue('H9', '=SUM(H6:H8)');

// Merge cells
$objPHPExcel->getActiveSheet()->mergeCells('B2:H3');
$objPHPExcel->getActiveSheet()->mergeCells('B12:H27');

// Set column widths
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(11.57);

// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
$objPHPExcel->getActiveSheet()->getStyle('C5:H5')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B6:B8')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('H6:H8')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B9:H9')->getFont()->setBold(true);

// Set alignments
$objPHPExcel->getActiveSheet()->getStyle('A1:H30')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('C5:H9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// Set medium border outline
$styleMediumBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
			'color' => array('argb' => 'FF000000'),
		),
	),
);
$objPHPExcel->getActiveSheet()->getStyle('B9')->applyFromArray($styleMediumBorderOutline);
$objPHPExcel->getActiveSheet()->getStyle('H9')->applyFromArray($styleMediumBorderOutline);

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
$objPHPExcel->getActiveSheet()->getStyle('C5:H5')->applyFromArray($styleMediumBorderFill);

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
$objPHPExcel->getActiveSheet()->getStyle('B6:B8')->applyFromArray($styleMediumThinBorderFill);
$objPHPExcel->getActiveSheet()->getStyle('C6:G8')->applyFromArray($styleMediumThinBorderFill);
$objPHPExcel->getActiveSheet()->getStyle('C9:G9')->applyFromArray($styleMediumThinBorderFill);
$objPHPExcel->getActiveSheet()->getStyle('H6:H8')->applyFromArray($styleMediumThinBorderFill);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Summary');

// Create New Sheet
$objPHPExcel->createSheet();

// Add some data
$objPHPExcel->setActiveSheetIndex(1)
			->setCellValue('B2', 'Cancellations - ' . date("d/m/Y", strtotime($date)))
			->setCellValue('B5', 'Coming Soon');
			
// Merge cells
$objPHPExcel->getActiveSheet()->mergeCells('B2:H3');

// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);

// Set alignments
$objPHPExcel->getActiveSheet()->getStyle('A1:H30')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Captive');

// Create New Sheet
$objPHPExcel->createSheet();

// Add some data
$objPHPExcel->setActiveSheetIndex(2)
			->setCellValue('B2', 'Cancellations - ' . date("d/m/Y", strtotime($date)))
			->setCellValue('B5', 'Coming Soon');
			
// Merge cells
$objPHPExcel->getActiveSheet()->mergeCells('B2:H3');

// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);

// Set alignments
$objPHPExcel->getActiveSheet()->getStyle('A1:H30')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Melbourne');

// Create New Sheet
$objPHPExcel->createSheet();

// Add some data
$objPHPExcel->setActiveSheetIndex(3)
			->setCellValue('B2', 'Cancellations - ' . date("d/m/Y", strtotime($date)))
			->setCellValue('B5', 'Coming Soon');
			
// Merge cells
$objPHPExcel->getActiveSheet()->mergeCells('B2:H3');

// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);

// Set alignments
$objPHPExcel->getActiveSheet()->getStyle('A1:H30')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Outsourced');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
$date = date("d-m-Y", strtotime($date));
$filename = "Cancellations_" . $date . ".xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=' . $filename);
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>