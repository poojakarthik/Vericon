<?php
mysql_connect('localhost','vericon','18450be');

$date = $_GET["date"];
$centre = $_GET["centre"];

$q = mysql_query("SELECT * FROM vericon.timesheet WHERE centre = '$centre' AND date = '$date' ORDER BY user ASC") or die(mysql_error());

if (mysql_num_rows($q) != 0)
{
	$q1 = mysql_query("SELECT campaign FROM vericon.centres WHERE centre = '$centre'") or die(mysql_error());
	$campaign = mysql_fetch_row($q1);
	
	$q2 = mysql_query("SELECT * FROM vericon.timesheet_designation,vericon.auth WHERE timesheet_designation.designation = 'Team Leader' AND auth.centre = '$centre' AND auth.status = 'Enabled' AND auth.user = timesheet_designation.user") or die(mysql_error());
	$ti = 0;
	while($t = mysql_fetch_assoc($q2))
	{
		$tl[$ti] = $t["first"] . " " . $t["last"];
		$ti++;
	}
	$tl = implode("/",$tl);
	
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
	$i = 16;
	
	// Team Leader
	$q = mysql_query("SELECT * FROM vericon.timesheet WHERE centre = '$centre' AND date = '$date' AND designation = 'Team Leader' ORDER BY user ASC") or die(mysql_error());
	if (mysql_num_rows($q) != 0)
	{
		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B' . $i, "Team Leader");
		$objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':H' . $i);
		$objPHPExcel->getActiveSheet()->getStyle('B' . $i)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
		$i++;
		
		while ($data = mysql_fetch_assoc($q))
		{
			$q0 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$data[user]'") or die(mysql_error());
			$user = mysql_fetch_row($q0);
			
			$q3 = mysql_query("SELECT * FROM vericon.sales_customers WHERE agent = '$data[user]' AND status = 'Approved' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
			$sales = mysql_num_rows($q3);
			
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('B' . $i, $user[0] . " " . $user[1])
						->setCellValue('C' . $i, date("H:i", strtotime($data["start"])))
						->setCellValue('D' . $i, date("H:i", strtotime($data["end"])))
						->setCellValue('E' . $i, $data["hours"])
						->setCellValue('F' . $i, $sales)
						->setCellValue('G' . $i, $data["bonus"]);
			$i++;
			$total_hours += $data["hours"];
			$total_sales += $sales;
			$total_agents++;
		}
	}
	
	// Closer
	$q = mysql_query("SELECT * FROM vericon.timesheet WHERE centre = '$centre' AND date = '$date' AND designation = 'Closer' ORDER BY user ASC") or die(mysql_error());
	if (mysql_num_rows($q) != 0)
	{
		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B' . $i, "Closer");
		$objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':H' . $i);
		$objPHPExcel->getActiveSheet()->getStyle('B' . $i)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
		
		$i++;
		
		while ($data = mysql_fetch_assoc($q))
		{
			$q0 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$data[user]'") or die(mysql_error());
			$user = mysql_fetch_row($q0);
			
			$q3 = mysql_query("SELECT * FROM vericon.sales_customers WHERE agent = '$data[user]' AND status = 'Approved' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
			$sales = mysql_num_rows($q3);
			
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('B' . $i, $user[0] . " " . $user[1])
						->setCellValue('C' . $i, date("H:i", strtotime($data["start"])))
						->setCellValue('D' . $i, date("H:i", strtotime($data["end"])))
						->setCellValue('E' . $i, $data["hours"])
						->setCellValue('F' . $i, $sales)
						->setCellValue('G' . $i, $data["bonus"]);
			$i++;
			$total_hours += $data["hours"];
			$total_sales += $sales;
			$total_agents++;
		}
	}
	
	// Agent
	$q = mysql_query("SELECT * FROM vericon.timesheet WHERE centre = '$centre' AND date = '$date' AND designation = 'Agent' ORDER BY user ASC") or die(mysql_error());
	if (mysql_num_rows($q) != 0)
	{
		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B' . $i, "Agent");
		$objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':H' . $i);
		$objPHPExcel->getActiveSheet()->getStyle('B' . $i)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
		$i++;
		
		while ($data = mysql_fetch_assoc($q))
		{
			$q0 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$data[user]'") or die(mysql_error());
			$user = mysql_fetch_row($q0);
			
			$q3 = mysql_query("SELECT * FROM vericon.sales_customers WHERE agent = '$data[user]' AND status = 'Approved' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
			$sales = mysql_num_rows($q3);
			
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('B' . $i, $user[0] . " " . $user[1])
						->setCellValue('C' . $i, date("H:i", strtotime($data["start"])))
						->setCellValue('D' . $i, date("H:i", strtotime($data["end"])))
						->setCellValue('E' . $i, $data["hours"])
						->setCellValue('F' . $i, $sales)
						->setCellValue('G' . $i, $data["bonus"]);
			$i++;
			$total_hours += $data["hours"];
			$total_sales += $sales;
			$total_agents++;
		}
	}
	
	// Probation
	$q = mysql_query("SELECT * FROM vericon.timesheet WHERE centre = '$centre' AND date = '$date' AND designation = 'Probation' ORDER BY user ASC") or die(mysql_error());
	if (mysql_num_rows($q) != 0)
	{
		$objPHPExcel->setActiveSheetIndex(0)
							->setCellValue('B' . $i, "Probation");
		$objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':H' . $i);
		$objPHPExcel->getActiveSheet()->getStyle('B' . $i)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
		$i++;
		
		while ($data = mysql_fetch_assoc($q))
		{
			$q0 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$data[user]'") or die(mysql_error());
			$user = mysql_fetch_row($q0);
			
			$q3 = mysql_query("SELECT * FROM vericon.sales_customers WHERE agent = '$data[user]' AND status = 'Approved' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
			$sales = mysql_num_rows($q3);
			
			$objPHPExcel->setActiveSheetIndex(0)
						->setCellValue('B' . $i, $user[0] . " " . $user[1])
						->setCellValue('C' . $i, date("H:i", strtotime($data["start"])))
						->setCellValue('D' . $i, date("H:i", strtotime($data["end"])))
						->setCellValue('E' . $i, $data["hours"])
						->setCellValue('F' . $i, $sales)
						->setCellValue('G' . $i, $data["bonus"]);
			$i++;
			$total_hours += $data["hours"];
			$total_sales += $sales;
			$total_agents++;
		}
	}
	
	// Total
	$sum = $i - 1;
	
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('B' . $i, 'Total')
				->setCellValue('E' . $i, '=SUM(E16:E' . $sum . ')')
				->setCellValue('F' . $i, '=SUM(F16:F' . $sum . ')')
				->setCellValue('G' . $i, '=SUM(G16:G' . $sum . ')')
				->setCellValue('H' . $i, '=SUM(H16:H' . $sum . ')');
	
	$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('B2', $centre . ' Timesheet')
				->setCellValue('B5', 'Campaign')
				->setCellValue('E5', $campaign[0])
				->setCellValue('B7', 'Team Leader')
				->setCellValue('E7', $tl)
				->setCellValue('B9', 'Date')
				->setCellValue('E9', date("d/m/Y", strtotime($date)))
				->setCellValue('B11', 'Total Hours')
				->setCellValue('C11', number_format($total_hours,2))
				->setCellValue('E11', 'CPS')
				->setCellValue('H11', number_format((($total_hours*27)/($total_sales*0.62)),2))
				->setCellValue('B12', 'Total Sales')
				->setCellValue('C12', $total_sales)
				->setCellValue('E12', 'Average SPH')
				->setCellValue('H12', number_format(($total_sales/$total_hours),2))
				->setCellValue('B13', 'Total Agents')
				->setCellValue('C13', $total_agents)
				->setCellValue('E13', 'Average SPA')
				->setCellValue('H13', number_format(($total_sales/$total_agents),2))
				->setCellValue('B15', 'Agent Name')
				->setCellValue('C15', 'Start Time')
				->setCellValue('D15', 'End Time')
				->setCellValue('E15', 'Hours')
				->setCellValue('F15', 'Sales')
				->setCellValue('G15', 'Bonus')
				->setCellValue('H15', 'Adjusted Sales');
	
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
	$objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':D' . $i);
	
	// Set cell number formats
	$objPHPExcel->getActiveSheet()->getStyle('H11')->getNumberFormat()->setFormatCode('$#,##0.00_);[Red]($#,##0.00)');
	$objPHPExcel->getActiveSheet()->getStyle('G16:G' . $i)->getNumberFormat()->setFormatCode('$#,##0_);[Red]($#,##0)');
	
	// Set column widths
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(28.68);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(12);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(12);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(8.14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(7.86);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(15.43);
	
	// Add conditional formatting
	$objConditional1 = new PHPExcel_Style_Conditional();
	$objConditional1->setConditionType(PHPExcel_Style_Conditional::CONDITION_CELLIS);
	$objConditional1->setOperatorType(PHPExcel_Style_Conditional::OPERATOR_LESSTHANOREQUAL);
	$objConditional1->addCondition('180');
	$objConditional1->getStyle()->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_DARKGREEN);
	$objConditional1->getStyle()->getFont()->setSize(12);
	$objConditional1->getStyle()->getFont()->setBold(true);
	$objConditional1->getStyle()->getNumberFormat()->setFormatCode('$#,##0.00_);[Red]($#,##0.00)');
	$objConditional1->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
	$objConditional2 = new PHPExcel_Style_Conditional();
	$objConditional2->setConditionType(PHPExcel_Style_Conditional::CONDITION_CELLIS);
	$objConditional2->setOperatorType(PHPExcel_Style_Conditional::OPERATOR_GREATERTHAN);
	$objConditional2->addCondition('180');
	$objConditional2->getStyle()->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
	$objConditional2->getStyle()->getFont()->setSize(12);
	$objConditional2->getStyle()->getFont()->setBold(true);
	$objConditional2->getStyle()->getNumberFormat()->setFormatCode('$#,##0.00_);[Red]($#,##0.00)');
	$objConditional2->getStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	
	$conditionalStyles = $objPHPExcel->getActiveSheet()->getStyle('H11')->getConditionalStyles();
	array_push($conditionalStyles, $objConditional1);
	array_push($conditionalStyles, $objConditional2);
	$objPHPExcel->getActiveSheet()->getStyle('H11')->setConditionalStyles($conditionalStyles);
	
	// Set fonts
	$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setSize(26);
	$objPHPExcel->getActiveSheet()->getStyle('B2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B5:E9')->getFont()->setSize(14);
	$objPHPExcel->getActiveSheet()->getStyle('B5:E9')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B11:H13')->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('B11:H13')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B11:B13')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
	$objPHPExcel->getActiveSheet()->getStyle('E11:E15')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
	$objPHPExcel->getActiveSheet()->getStyle('B15:H15')->getFont()->setSize(12);
	$objPHPExcel->getActiveSheet()->getStyle('B15:H15')->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
	$objPHPExcel->getActiveSheet()->getStyle('B15:H' . $i)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':H' . $i)->getFont()->setUnderline(PHPExcel_Style_Font::UNDERLINE_SINGLE);
	
	// Set alignments
	$objPHPExcel->getActiveSheet()->getStyle('A1:H15')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('E5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('E7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('E9')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('C11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('C12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('C13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('H11')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('H12')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('H13')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('C15:H' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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
	$objPHPExcel->getActiveSheet()->getStyle('B15:H15')->applyFromArray($styleMediumBorderFill);
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
	$objPHPExcel->getActiveSheet()->getStyle('B11:C13')->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('E11:H13')->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('B16:H' . $i)->applyFromArray($styleMediumThinBorderFill);
	
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