<?php
mysql_connect('localhost','vericon','18450be');

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
$centres = explode(",",$centre);
for ($i = 0; $i < count($centres); $i++)
{
	$q = mysql_query("SELECT centre FROM vericon.centres WHERE centre = '$centres[$i]' AND type = 'Self'") or die(mysql_error());
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
			$q0 = mysql_query("SELECT auth.first, auth.last FROM vericon.timesheet_designation,vericon.auth WHERE timesheet_designation.designation = 'Team Leader' AND auth.centre = '$centres[$i]' AND auth.status = 'Enabled' AND auth.user = timesheet_designation.user") or die(mysql_error());
			$t = mysql_fetch_row($q0);
			$tl = $t[0] . " " . $t[1];
			
			$q = mysql_query("SELECT SUM(hours),date FROM vericon.timesheet WHERE centre = '$centres[$i]' AND WEEK(date) BETWEEN '$week1' AND '$week2' GROUP BY WEEK(date) ORDER BY date ASC") or die(mysql_error());
			
			if (mysql_num_rows($q) != 0)
			{
				$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('B' . $row, $centres[$i] . " - " . $tl);
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
							->setCellValue('F' . $row, "Estimated CPS")
							->setCellValue('G' . $row, "Actual CPS");
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
					$gross = 0;
					$cancellations = 0;
					
					$q1 = mysql_query("SELECT * FROM vericon.timesheet WHERE centre = '$centres[$i]' AND WEEK(date) = '" . date("W",strtotime($data["date"])) . "' GROUP BY user ORDER BY user ASC") or die(mysql_error());
					while ($data2 = mysql_fetch_assoc($q1))
					{
						$q2 = mysql_query("SELECT SUM(op_hours), SUM(op_bonus), AVG(rate), SUM(payg), SUM(annual), SUM(sick), SUM(cancellations) FROM vericon.timesheet_other WHERE user = '$data2[user]' AND week = '" . date("W",strtotime($data["date"])) . "'") or die(mysql_error());
						$da = mysql_fetch_row($q2);
						
						$q3 = mysql_query("SELECT rate FROM vericon.timesheet_rate WHERE user = '$data2[user]'") or die(mysql_error());
						$r = mysql_fetch_row($q3);
						
						if ($da[2] <= 0) { $rate = $r[0]; } else { $rate = $da[2]; }
						$gross += (($rate * ($da[0] + $da[4] + $da[5])) + $da[1]) * 1.09;
						$cancellations += $da[6];
					}
					
					$q2 = mysql_query("SELECT * FROM vericon.sales_customers WHERE status = 'Approved' AND centre = '$centres[$i]' AND WEEK(approved_timestamp) = '" . date("W",strtotime($data["date"])) . "'") or die(mysql_error());
					$sales = mysql_num_rows($q2);
					$total_sales += $sales;
					
					$q3 = mysql_query("SELECT m_cost FROM vericon.timesheet_mcost WHERE centre = '$centres[$i]' AND week = '" . date("W",strtotime($data["date"])) . "'") or die(mysql_error());
					$da2 = mysql_fetch_row($q3);
					
					if ($da2[0] > 0)
					{
						$gross += $da2[0];
						$total_gross += $gross;
						$net_sales = $sales - $cancellations;
						$total_net_sales += $net_sales;
						if ($net_sales > 0) { $a_cps = number_format($gross/$net_sales,2); } else { $a_cps = number_format($gross,2); }
					}
					else
					{
						$a_cps = "-";
					}
					
					$sph = $sales / $hours;
					$cps = ($hours*27) / ($sales*0.62);
					$we = date("Y-m-d", strtotime(date("Y",strtotime($data["date"]))."W".date("W",strtotime($data["date"]))."7"));
					
					$objPHPExcel->setActiveSheetIndex(0)
								->setCellValue('B' . $row, "W.E. " . date("d/m/Y", strtotime($we)))
								->setCellValue('C' . $row, number_format($hours,2))
								->setCellValue('D' . $row, $sales)
								->setCellValue('E' . $row, number_format($sph,2))
								->setCellValue('F' . $row, number_format($cps,2))
								->setCellValue('G' . $row, $a_cps);
					$row++;
				}
				// Set fonts
				$objPHPExcel->getActiveSheet()->getStyle('B' . $init_row . ':G' . $row)->getFont()->setSize(10);
				// Set alignments
				$objPHPExcel->getActiveSheet()->getStyle('C' . $init_row . ':G' . $row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				// Set medium border outline thin border inside
				$objPHPExcel->getActiveSheet()->getStyle('B' . $init_row . ':G' . $row)->applyFromArray($styleMediumThinBorderFill);
				
				// Totals
				$total_sph = $total_sales / $total_hours;
				$total_cps = ($total_hours*27) / ($total_sales*0.62);
				
				if ($total_net_sales > 0) { $total_a_cps = $total_gross/$total_net_sales; } else { $total_a_cps = $total_gross; }
				
				$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B' . $row, "Total")
							->setCellValue('C' . $row, $total_hours)
							->setCellValue('D' . $row, $total_sales)
							->setCellValue('E' . $row, number_format($total_sph,2))
							->setCellValue('F' . $row, number_format($total_cps,2))
							->setCellValue('G' . $row, number_format($total_a_cps,2));
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
$objPHPExcel->getActiveSheet()->getStyle('F6:G' . $row)->getNumberFormat()->setFormatCode('$#,##0.00_);[Red]($#,##0.00)');

// Set column widths
$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10.57);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(14.57);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(8);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(13);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10.57);

// Rename sheet
$objPHPExcel->getActiveSheet()->setTitle('Sheet1');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
$filename = "Centre_Report_" . $date1 . "_to_" . $date2 . ".xlsx";

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename=' . $filename);
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
$objWriter->save('php://output');
exit;
?>