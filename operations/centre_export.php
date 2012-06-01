<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$centre = $_GET["centre"];
$date1 = $_GET["date1"];
$week1 = date("W", strtotime($date1));
$year1 = date("Y", strtotime($date1));
$date2 = $_GET["date2"];
$week2 = date("W", strtotime($date2));
$year2 = date("Y", strtotime($date2));

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
			->setCellValue('A1', "Centre Timesheet Report - Weekly Breakdown");
			
// Merge cells
$objPHPExcel->getActiveSheet()->mergeCells('A1:H2');

// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);

// Set alignments
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

// Set medium border outline
$styleMediumBorderOutline = array(
	'borders' => array(
		'outline' => array(
			'style' => PHPExcel_Style_Border::BORDER_MEDIUM,
			'color' => array('argb' => 'FF000000'),
		),
	),
);

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

$row = 4;
$centres = explode("_",$centre);
for ($i = 0; $i < count($centres); $i++)
{
	$q = mysql_query("SELECT centre FROM centres WHERE centre = '$centres[$i]' AND type = 'Self'") or die(mysql_error());
	if (mysql_num_rows($q) != 0)
	{
		$self[$centres[$i]] = 1;
	}
}

if (array_sum($self) > 0)
{
	for ($i = 0; $i < count($centres); $i++)
    {
		if ($self[$centres[$i]] == 1)
        {
			$q = mysql_query("SELECT SUM(hours),date FROM timesheet WHERE centre = '$centres[$i]' AND WEEK(date) BETWEEN '$week1' AND '$week2' GROUP BY WEEK(date) ORDER BY date ASC") or die(mysql_error());
			
			if (mysql_num_rows($q) != 0)
			{
				$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('B' . $row, $centres[$i]);
				// Merge cells
				$objPHPExcel->getActiveSheet()->mergeCells('B' . $row . ':G' . $row);
				// Set fonts
				$objPHPExcel->getActiveSheet()->getStyle('B' . $row)->getFont()->setSize(14);
				$objPHPExcel->getActiveSheet()->getStyle('B' . $row)->getFont()->setBold(true);
				// Set alignments
				$objPHPExcel->getActiveSheet()->getStyle('B' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				// Set medium border outline
				$objPHPExcel->getActiveSheet()->getStyle('B' . $row . ':G' . $row)->applyFromArray($styleMediumBorderOutline);
				$row++;
				
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B' . $row, "Week Ending")
							->setCellValue('C' . $row, "Hours")
							->setCellValue('D' . $row, "Sales")
							->setCellValue('E' . $row, "SPH")
							->setCellValue('F' . $row, "SPA")
							->setCellValue('G' . $row, "Estimated CPS");
				// Set fonts
				$objPHPExcel->getActiveSheet()->getStyle('B' . $row . ':G' . $row)->getFont()->setSize(10);
				$objPHPExcel->getActiveSheet()->getStyle('B' . $row . ':G' . $row)->getFont()->setBold(true);
				// Set alignments
				$objPHPExcel->getActiveSheet()->getStyle('C' . $row . ':G' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				// Set medium border outline medium border inside
				$objPHPExcel->getActiveSheet()->getStyle('B' . $row . ':G' . $row)->applyFromArray($styleMediumBorderFill);
				$row++;
				
				$init_row = $row;
				$total_hours = 0;
				$total_sales = 0;
				while ($data = mysql_fetch_assoc($q))
				{
					$hours = $data["SUM(hours)"];
					$total_hours += $hours;
					
					$q1 = mysql_query("SELECT * FROM timesheet WHERE centre = '$centres[$i]' AND WEEK(date) = '" . date("W",strtotime($data["date"])) . "' GROUP BY user") or die(mysql_error());
					$agents = mysql_num_rows($q1);
					
					$q2 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND centre = '$centres[$i]' AND WEEK(approved_timestamp) = '" . date("W",strtotime($data["date"])) . "'") or die(mysql_error());
					$sales = mysql_num_rows($q2);
					$total_sales += $sales;
					
					$sph = $sales / $hours;
					$spa = $sales / $agents;
					$cps = ($hours*27) / ($sales*0.62);
					$we = date("Y-m-d", strtotime(date("Y",strtotime($data["date"]))."W".date("W",strtotime($data["date"]))."7"));
					
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('B' . $row, "W.E. " . date("d/m/Y", strtotime($we)))
								->setCellValue('C' . $row, number_format($hours,2))
								->setCellValue('D' . $row, $sales)
								->setCellValue('E' . $row, number_format($sph,2))
								->setCellValue('F' . $row, number_format($spa,2))
								->setCellValue('G' . $row, number_format($cps,2));
					$row++;
				}
				// Set fonts
				$objPHPExcel->getActiveSheet()->getStyle('B' . $init_row . ':G' . $row)->getFont()->setSize(10);
				// Set alignments
				$objPHPExcel->getActiveSheet()->getStyle('C' . $init_row . ':G' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				// Set medium border outline thin border inside
				$objPHPExcel->getActiveSheet()->getStyle('B' . $init_row . ':G' . $row)->applyFromArray($styleMediumThinBorderFill);
				
				// Totals
				$q1 = mysql_query("SELECT * FROM timesheet WHERE centre = '$centres[$i]' AND WEEK(date) BETWEEN '$week1' AND '$week2' GROUP BY user") or die(mysql_error());
				$total_agents = mysql_num_rows($q1);
				
				$total_sph = $total_sales / $total_hours;
				$total_spa = $total_sales / $total_agents;
				$total_cps = ($total_hours*27) / ($total_sales*0.62);
				
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B' . $row, "Total")
							->setCellValue('C' . $row, $total_hours)
							->setCellValue('D' . $row, $total_sales)
							->setCellValue('E' . $row, number_format($total_sph,2))
							->setCellValue('F' . $row, number_format($total_spa,2))
							->setCellValue('G' . $row, $total_cps);
				// Set fonts
				$objPHPExcel->getActiveSheet()->getStyle('B' . $row . ':G' . $row)->getFont()->setSize(10);
				$objPHPExcel->getActiveSheet()->getStyle('B' . $row . ':G' . $row)->getFont()->setBold(true);
				// Set alignments
				$objPHPExcel->getActiveSheet()->getStyle('C' . $row . ':G' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				// Set medium border outline thin border inside
				$objPHPExcel->getActiveSheet()->getStyle('B' . $row . ':G' . $row)->applyFromArray($styleMediumThinBorderFill);
				$row++;
				$row++;
			}
		}
	}
}
// Set cell number formats
$objPHPExcel->getActiveSheet()->getStyle('G6:G' . $row)->getNumberFormat()->setFormatCode('$#,##0.00_);[Red]($#,##0.00)');

// Set column widths
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10.57);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(14.57);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10.57);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Sheet1');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
$filename = "Centre_Timesheet_Report.xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=' . $filename);
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>