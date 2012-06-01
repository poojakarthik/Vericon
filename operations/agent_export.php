<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$user = $_GET["user"];
$date1 = $_GET["date1"];
$week1 = date("W", strtotime($date1));
$year1 = date("Y", strtotime($date1));
$date2 = $_GET["date2"];
$week2 = date("W", strtotime($date2));
$year2 = date("Y", strtotime($date2));

$q0 = mysql_query("SELECT first,last FROM auth WHERE user = '$user'") or die(mysql_error());
$ag = mysql_fetch_row($q0);
$agent = $ag[0] . " " . $ag[1];

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
			->setCellValue('A1', $agent . " Timesheet Report - Daily Breakdown");

// Merge cells
$objPHPExcel->getActiveSheet()->mergeCells('A1:K2');

// Set fonts
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);

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
$q = mysql_query("SELECT SUM(hours),SUM(bonus),date FROM timesheet WHERE user = '$user' AND WEEK(date) BETWEEN '$week1' AND '$week2' GROUP BY WEEK(date) ORDER BY date ASC") or die(mysql_error());

if (mysql_num_rows($q) != 0)
{
	while ($data = mysql_fetch_assoc($q))
	{
		$week = date("W", strtotime($data["date"]));
		$year = date("Y", strtotime($data["date"]));
		$we = date("Y-m-d", strtotime($year . "W" . $week . "7"));
		
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('B' . $row, "W.E. " . date("d/m/Y", strtotime($we)));
		// Merge cells
		$objPHPExcel->getActiveSheet()->mergeCells('B' . $row . ':J' . $row);
		// Set fonts
		$objPHPExcel->getActiveSheet()->getStyle('B' . $row)->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('B' . $row)->getFont()->setBold(true);
		// Set alignments
		$objPHPExcel->getActiveSheet()->getStyle('B' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		// Set medium border outline
		$objPHPExcel->getActiveSheet()->getStyle('B' . $row . ':J' . $row)->applyFromArray($styleMediumBorderOutline);
		$row++;
		
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('B' . $row, "Day")
					->setCellValue('C' . $row, "Date")
					->setCellValue('D' . $row, "Start Time")
					->setCellValue('E' . $row, "End Time")
					->setCellValue('F' . $row, "Hours")
					->setCellValue('G' . $row, "Bonus")
					->setCellValue('H' . $row, "Sales")
					->setCellValue('I' . $row, "SPH")
					->setCellValue('J' . $row, "Estimated CPS");
		// Set fonts
		$objPHPExcel->getActiveSheet()->getStyle('B' . $row . ':J' . $row)->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('B' . $row . ':J' . $row)->getFont()->setBold(true);
		// Set alignments
		$objPHPExcel->getActiveSheet()->getStyle('C' . $row . ':J' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		// Set medium border outline medium border inside
		$objPHPExcel->getActiveSheet()->getStyle('B' . $row . ':J' . $row)->applyFromArray($styleMediumBorderFill);
		$row++;
		
		$init_row = $row;
		for ($day=1; $day <= 7; $day++)
		{
			$date = date('Y-m-d', strtotime(date("Y", strtotime($we)) . "W" . date("W", strtotime($we)) . $day));
			$week = date("W", strtotime($date));	
			
			$q1 = mysql_query("SELECT start,end,hours,bonus FROM timesheet WHERE user = '$user' AND DATE(date) = '$date'") or die(mysql_error());
			$da = mysql_fetch_row($q1);
			$start = $da[0];
			$end = $da[1];
			$hours = $da[2];
			$bonus = $da[3];
			
			$q2 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND agent = '$user' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
			$sales = mysql_num_rows($q2);
			
			$q3 = mysql_query("SELECT rate FROM timesheet_other WHERE user = '$user' AND week = '$week'") or die(mysql_error());
			if (mysql_num_rows($q3) == 0)
			{
				$q4 = mysql_query("SELECT rate FROM timesheet_rate WHERE user = '$user'") or die(mysql_error());
				if (mysql_num_rows($q4) == 0)
				{
					$rate = 16.57;
				}
				else
				{
					$ra = mysql_fetch_row($q4);
					$rate = $ra[0];
				}
			}
			else
			{
				$ra = mysql_fetch_row($q3);
				$rate = $ra[0];
			}
			
			$sph = $sales / $hours;
			$gross = (($rate * $hours) + $bonus) * 1.09;
			if ($sales > 0) { $cps = $gross / $sales; } else { $cps = $gross; }
			
			$start_d = date("h:i A", strtotime($start));
			$end_d = date("h:i A", strtotime($end));
			$hours_d = number_format($hours,2);
			$bonus_d = number_format($bonus,2);
			$sales_d = $sales;
			$sph_d = number_format($sph,2);
			$cps_d = number_format($cps,2);
			
			if ($hours == 0)
			{
				$start_d = "-";
				$end_d = "-";
				$hours_d = "-";
				$bonus_d = "-";
				$sales_d = "-";
				$sph_d = "-";
				$cps_d = "-";
			}
			
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('B' . $row, date("l", strtotime($date)))
						->setCellValue('C' . $row, date("d/m/Y", strtotime($date)))
						->setCellValue('D' . $row, $start_d)
						->setCellValue('E' . $row, $end_d)
						->setCellValue('F' . $row, $hours_d)
						->setCellValue('G' . $row, $bonus_d)
						->setCellValue('H' . $row, $sales_d)
						->setCellValue('I' . $row, $sph_d)
						->setCellValue('J' . $row, $cps_d);
			$row++;
			// Set fonts
			$objPHPExcel->getActiveSheet()->getStyle('B' . $init_row . ':J' . $row)->getFont()->setSize(10);
			// Set alignments
			$objPHPExcel->getActiveSheet()->getStyle('C' . $init_row . ':J' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			// Set medium border outline thin border inside
			$objPHPExcel->getActiveSheet()->getStyle('B' . $init_row . ':J' . $row)->applyFromArray($styleMediumThinBorderFill);
		}
		
		// Totals
		$total_hours = $data["SUM(hours)"];
		$total_bonus = $data["SUM(bonus)"];
		
		$q1 = mysql_query("SELECT * FROM sales_customers WHERE agent = '$user' AND WEEK(approved_timestamp) = '$week' AND status = 'Approved'") or die(mysql_error());
		$total_sales = mysql_num_rows($q1);
		
		$total_sph = $total_sales / $total_hours;
		$gross = (($rate * $total_hours) + $total_bonus) * 1.09;
		if ($total_sales > 0) { $total_cps = $gross / $total_sales; } else { $total_cps = $gross; }
		
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('B' . $row, "Total")
					->setCellValue('F' . $row, number_format($total_hours,2))
					->setCellValue('G' . $row, number_format($total_bonus,2))
					->setCellValue('H' . $row, $total_sales)
					->setCellValue('I' . $row, number_format($total_sph,2))
					->setCellValue('J' . $row, $total_cps);
		// Merge cells
		$objPHPExcel->getActiveSheet()->mergeCells('B' . $row . ':E' . $row);
		// Set fonts
		$objPHPExcel->getActiveSheet()->getStyle('B' . $row . ':J' . $row)->getFont()->setSize(10);
		$objPHPExcel->getActiveSheet()->getStyle('B' . $row . ':J' . $row)->getFont()->setBold(true);
		// Set alignments
		$objPHPExcel->getActiveSheet()->getStyle('C' . $row . ':J' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$objPHPExcel->getActiveSheet()->getStyle('B' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
		// Set medium border outline thin border inside
		$objPHPExcel->getActiveSheet()->getStyle('B' . $row . ':J' . $row)->applyFromArray($styleMediumThinBorderFill);
		$row++;
		$row++;
	}
}

// Set cell number formats
$objPHPExcel->getActiveSheet()->getStyle('G6:G' . $row)->getNumberFormat()->setFormatCode('$#,##0.00_);[Red]($#,##0.00)');
$objPHPExcel->getActiveSheet()->getStyle('J6:J' . $row)->getNumberFormat()->setFormatCode('$#,##0.00_);[Red]($#,##0.00)');

// Set column widths
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15.71);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10.71);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(11.43);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(9.29);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(9.29);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(15.71);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Sheet1');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
$agent = str_replace(" ","_",$agent);
$filename = $agent . "_Timesheet_Report.xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=' . $filename);
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>