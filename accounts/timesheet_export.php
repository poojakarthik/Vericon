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
				->setCellValue('J3', 'Sales')
				->setCellValue('M3', 'Bonus')
				->setCellValue('O3', 'Pay')
				->setCellValue('B4', '#')
				->setCellValue('C4', 'Agent Name')
				->setCellValue('D4', 'Agent ID')
				->setCellValue('E4', 'Timesheet Hours')
				->setCellValue('F4', 'Dialler Hours')
				->setCellValue('G4', 'Operational Hours')
				->setCellValue('H4', 'Annual')
				->setCellValue('I4', 'Sick')
				->setCellValue('J4', 'Timesheet Sales')
				->setCellValue('K4', 'Cancellations')
				->setCellValue('L4', 'Net Sales')
				->setCellValue('M4', 'Timesheet Bonus')
				->setCellValue('N4', 'Payable Bonus')
				->setCellValue('O4', 'Rate')
				->setCellValue('P4', 'Gross Pay')
				->setCellValue('Q4', 'Gross Pay (Inc. Super)')
				->setCellValue('R4', 'PAYG')
				->setCellValue('S4', 'Net Pay')
				->setCellValue('T4', 'CPS')
				->setCellValue('U4', 'Comments');
	
	$i = 5;
	$count = 1;
	while ($data = mysql_fetch_assoc($q))
	{
		$q0 = mysql_query("SELECT first,last FROM auth WHERE user = '$data[user]'") or die(mysql_error());
		$user = mysql_fetch_row($q0);
		
		$q1 = mysql_query("SELECT SUM(hours),SUM(dialler_hours),SUM(bonus) FROM timesheet WHERE date BETWEEN '$date1' AND '$date2' AND user = '$data[user]'") or die(mysql_error());
		$da = mysql_fetch_row($q1);
		
		$q2 = mysql_query("SELECT SUM(cancellations),SUM(op_hours),SUM(op_bonus),AVG(rate),SUM(payg),SUM(annual),SUM(sick),comment FROM timesheet_other WHERE user = '$data[user]' AND week BETWEEN '$week1' AND '$week2'") or die(mysql_error());
		$da2 = mysql_fetch_row($q2);
		
		$q3 = mysql_query("SELECT * FROM sales_customers WHERE agent = '$data[user]' AND status = 'Approved' AND approved_timestamp BETWEEN '$date1' AND '$date2'") or die(mysql_error());
		$sales = mysql_num_rows($q3);
		
		$q4 = mysql_query("SELECT rate FROM timesheet_rate WHERE user = '$data[user]'") or die(mysql_error());
		$r = mysql_fetch_row($q4);
		
		if ($da2[3] == 0) { $rate = $r[0]; } else { $rate = $da2[3]; }
		
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('B' . $i, $count)
					->setCellValue('C' . $i, $user[0] . " " . $user[1])
					->setCellValue('D' . $i, $data["user"])
					->setCellValue('E' . $i, $da[0])
					->setCellValue('F' . $i, $da[1])
					->setCellValue('G' . $i, $da2[1])
					->setCellValue('H' . $i, $da2[5])
					->setCellValue('I' . $i, $da2[6])
					->setCellValue('J' . $i, $sales)
					->setCellValue('K' . $i, $da2[0])
					->setCellValue('L' . $i, "=J" . $i . "-K" . $i)
					->setCellValue('M' . $i, $da[2])
					->setCellValue('N' . $i, $da2[2])
					->setCellValue('O' . $i, $rate)
					->setCellValue('P' . $i, '=IF(O' . $i . '>0,((G' . $i . '+H' . $i . '+I' . $i . ')*O' . $i . ')+N' . $i . ',0)')
					->setCellValue('Q' . $i, '=(P' . $i . '*9%)+P' . $i)
					->setCellValue('R' . $i, $da2[4])
					->setCellValue('S' . $i, '=P' . $i . '-R' . $i)
					->setCellValue('T' . $i, "=IF(L" . $i . ">0,Q" . $i . "/L" . $i . ",Q" . $i . ")")
					->setCellValue('U' . $i, $da2[7]);
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
					->setCellValue('M' . $i, '=SUM(M5:M' . $sum . ')')
					->setCellValue('N' . $i, '=SUM(N5:N' . $sum . ')')
					->setCellValue('P' . $i, '=SUM(P5:P' . $sum . ')')
					->setCellValue('Q' . $i, '=SUM(Q5:Q' . $sum . ')')
					->setCellValue('R' . $i, '=SUM(R5:R' . $sum . ')')
					->setCellValue('S' . $i, '=SUM(S5:S' . $sum . ')')
					->setCellValue('T' . $i, "=IF(L" . $i . ">0,Q" . $i . "/L" . $i . ",Q" . $i . ")");
					
	$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('B' . ($i + 2), 'Management Cost')
					->setCellValue('D' . ($i + 2), $m_cost[0])
					->setCellValue('B' . ($i + 3), 'Total Gross Pay')
					->setCellValue('D' . ($i + 3), '=Q' . $i . '+D' . ($i + 2))
					->setCellValue('B' . ($i + 4), 'Final CPS')
					->setCellValue('D' . ($i + 4), '=D' . ($i + 3) . '/L' . $i);
	
	// Merge cells
	$objPHPExcel->getActiveSheet()->mergeCells('A1:U2');
	$objPHPExcel->getActiveSheet()->mergeCells('E3:I3');
	$objPHPExcel->getActiveSheet()->mergeCells('J3:L3');
	$objPHPExcel->getActiveSheet()->mergeCells('M3:N3');
	$objPHPExcel->getActiveSheet()->mergeCells('O3:U3');
	$objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':D' . $i);
	$objPHPExcel->getActiveSheet()->mergeCells('B' . ($i + 2) . ':C' . ($i + 2));
	$objPHPExcel->getActiveSheet()->mergeCells('B' . ($i + 3) . ':C' . ($i + 3));
	$objPHPExcel->getActiveSheet()->mergeCells('B' . ($i + 4) . ':C' . ($i + 4));
	
	// Set cell number formats
	$objPHPExcel->getActiveSheet()->getStyle('M5:T' . $i)->getNumberFormat()->setFormatCode('$#,##0.00_);[Red]($#,##0.00)');
	$objPHPExcel->getActiveSheet()->getStyle('D' . ($i + 2) . ':D' . ($i + 4))->getNumberFormat()->setFormatCode('$#,##0.00_);[Red]($#,##0.00)');
	
	// Set column widths
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(1);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(2.71);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(25);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15.43);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(11.71);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(15.43);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(14.86);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(12.14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(15.71);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(13.29);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(11);
	$objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(18);
	$objPHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(11);
	$objPHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(11);
	$objPHPExcel->getActiveSheet()->getColumnDimension('U')->setWidth(50);

	// Set fonts
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B3:U4')->getFont()->setSize(10);
	$objPHPExcel->getActiveSheet()->getStyle('B3:U4')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B5:U' . $i)->getFont()->setSize(10);
	$objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':U' . $i)->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B' . ($i + 2) . ':D' . ($i + 4))->getFont()->setSize(10);
	$objPHPExcel->getActiveSheet()->getStyle('B' . ($i + 2) . ':D' . ($i + 4))->getFont()->setBold(true);
	
	// Set alignments
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('B4:B' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('E3:U' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('C4:D' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
	$objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':U' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
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
	$objPHPExcel->getActiveSheet()->getStyle('E3:I3')->applyFromArray($styleMediumBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('J3:L3')->applyFromArray($styleMediumBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('M3:N3')->applyFromArray($styleMediumBorderOutline);
	$objPHPExcel->getActiveSheet()->getStyle('O3:U3')->applyFromArray($styleMediumBorderOutline);
	
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
	$objPHPExcel->getActiveSheet()->getStyle('B4:U4')->applyFromArray($styleMediumBorderFill);
	
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
	$objPHPExcel->getActiveSheet()->getStyle('E5:I' . $i)->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('J5:L' . $i)->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('M5:N' . $i)->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('O5:U' . $i)->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('B' . $i . ':D' . $i)->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('E' . $i . ':I' . $i)->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('J' . $i . ':L' . $i)->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('M' . $i . ':N' . $i)->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('O' . $i . ':U' . $i)->applyFromArray($styleMediumThinBorderFill);
	$objPHPExcel->getActiveSheet()->getStyle('B' . ($i + 2) . ':D' . ($i+4))->applyFromArray($styleMediumThinBorderFill);
	
	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('Pay');
	
	// Daily Timesheet			
	$q1 = mysql_query("SELECT campaign FROM centres WHERE centre = '$centre'") or die(mysql_error());
	$campaign = mysql_fetch_row($q1);
	
	$q2 = mysql_query("SELECT * FROM timesheet_designation,auth WHERE timesheet_designation.designation = 'Team Leader' AND auth.centre = '$centre' AND auth.status = 'Enabled' AND auth.user = timesheet_designation.user") or die(mysql_error());
	$ti = 0;
	while($t = mysql_fetch_assoc($q2))
	{
		$tl[$ti] = $t["first"] . " " . $t["last"];
		$ti++;
	}
	$tl = implode("/",$tl);
	
	for ($day = 1; $day <= 5; $day++)
	{
		$date3 = date('Y-m-d', strtotime($year . "W" . $week . $day));
		$total_hours = 0;
		$total_sales = 0;
		$total_agents = 0;
		
		// Create New Sheet
		$objPHPExcel->createSheet();
		
		// Query
		$q = mysql_query("SELECT * FROM timesheet WHERE centre = '$centre' AND date = '$date3' AND designation != '' ORDER BY user ASC") or die(mysql_error());
		
		if (mysql_num_rows($q) != 0)
		{
			// Add some data
			$i = 16;
			
			// Team Leader
			$q = mysql_query("SELECT * FROM timesheet WHERE centre = '$centre' AND date = '$date3' AND designation = 'Team Leader' ORDER BY user ASC") or die(mysql_error());
			if (mysql_num_rows($q) != 0)
			{
				$objPHPExcel->setActiveSheetIndex($day)
									->setCellValue('B' . $i, "Team Leader");
				$objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':H' . $i);
				$objPHPExcel->getActiveSheet()->getStyle('B' . $i)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
				$i++;
				
				while ($data = mysql_fetch_assoc($q))
				{
					$q0 = mysql_query("SELECT first,last FROM auth WHERE user = '$data[user]'") or die(mysql_error());
					$user = mysql_fetch_row($q0);
					
					$q3 = mysql_query("SELECT * FROM sales_customers WHERE agent = '$data[user]' AND status = 'Approved' AND DATE(approved_timestamp) = '$date3'") or die(mysql_error());
					$sales = mysql_num_rows($q3);
					
					$objPHPExcel->setActiveSheetIndex($day)
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
			$q = mysql_query("SELECT * FROM timesheet WHERE centre = '$centre' AND date = '$date3' AND designation = 'Closer' ORDER BY user ASC") or die(mysql_error());
			if (mysql_num_rows($q) != 0)
			{
				$objPHPExcel->setActiveSheetIndex($day)
									->setCellValue('B' . $i, "Closer");
				$objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':H' . $i);
				$objPHPExcel->getActiveSheet()->getStyle('B' . $i)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
				
				$i++;
				
				while ($data = mysql_fetch_assoc($q))
				{
					$q0 = mysql_query("SELECT first,last FROM auth WHERE user = '$data[user]'") or die(mysql_error());
					$user = mysql_fetch_row($q0);
					
					$q3 = mysql_query("SELECT * FROM sales_customers WHERE agent = '$data[user]' AND status = 'Approved' AND DATE(approved_timestamp) = '$date3'") or die(mysql_error());
					$sales = mysql_num_rows($q3);
					
					$objPHPExcel->setActiveSheetIndex($day)
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
			$q = mysql_query("SELECT * FROM timesheet WHERE centre = '$centre' AND date = '$date3' AND designation = 'Agent' ORDER BY user ASC") or die(mysql_error());
			if (mysql_num_rows($q) != 0)
			{
				$objPHPExcel->setActiveSheetIndex($day)
									->setCellValue('B' . $i, "Agent");
				$objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':H' . $i);
				$objPHPExcel->getActiveSheet()->getStyle('B' . $i)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
				$i++;
				
				while ($data = mysql_fetch_assoc($q))
				{
					$q0 = mysql_query("SELECT first,last FROM auth WHERE user = '$data[user]'") or die(mysql_error());
					$user = mysql_fetch_row($q0);
					
					$q3 = mysql_query("SELECT * FROM sales_customers WHERE agent = '$data[user]' AND status = 'Approved' AND DATE(approved_timestamp) = '$date3'") or die(mysql_error());
					$sales = mysql_num_rows($q3);
					
					$objPHPExcel->setActiveSheetIndex($day)
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
			$q = mysql_query("SELECT * FROM timesheet WHERE centre = '$centre' AND date = '$date3' AND designation = 'Probation' ORDER BY user ASC") or die(mysql_error());
			if (mysql_num_rows($q) != 0)
			{
				$objPHPExcel->setActiveSheetIndex($day)
									->setCellValue('B' . $i, "Probation");
				$objPHPExcel->getActiveSheet()->mergeCells('B' . $i . ':H' . $i);
				$objPHPExcel->getActiveSheet()->getStyle('B' . $i)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_RED);
				$i++;
				
				while ($data = mysql_fetch_assoc($q))
				{
					$q0 = mysql_query("SELECT first,last FROM auth WHERE user = '$data[user]'") or die(mysql_error());
					$user = mysql_fetch_row($q0);
					
					$q3 = mysql_query("SELECT * FROM sales_customers WHERE agent = '$data[user]' AND status = 'Approved' AND DATE(approved_timestamp) = '$date3'") or die(mysql_error());
					$sales = mysql_num_rows($q3);
					
					$objPHPExcel->setActiveSheetIndex($day)
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
			
			$objPHPExcel->setActiveSheetIndex($day)
						->setCellValue('B' . $i, 'Total')
						->setCellValue('E' . $i, '=SUM(E16:E' . $sum . ')')
						->setCellValue('F' . $i, '=SUM(F16:F' . $sum . ')')
						->setCellValue('G' . $i, '=SUM(G16:G' . $sum . ')')
						->setCellValue('H' . $i, '=SUM(H16:H' . $sum . ')');
			
			$objPHPExcel->setActiveSheetIndex($day)
						->setCellValue('B2', $centre . ' Timesheet')
						->setCellValue('B5', 'Campaign')
						->setCellValue('E5', $campaign[0])
						->setCellValue('B7', 'Team Leader')
						->setCellValue('E7', $tl)
						->setCellValue('B9', 'Date')
						->setCellValue('E9', date("d/m/Y", strtotime($date3)))
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
			$objPHPExcel->getActiveSheet()->setTitle(date("l", strtotime($date3)));
		}
		else
		{
			// Add some data
			$i = 17;
			
			// Total
			$sum = $i - 1;
			
			$objPHPExcel->setActiveSheetIndex($day)
						->setCellValue('B' . $i, 'Total')
						->setCellValue('E' . $i, '=SUM(E16:E' . $sum . ')')
						->setCellValue('F' . $i, '=SUM(F16:F' . $sum . ')')
						->setCellValue('G' . $i, '=SUM(G16:G' . $sum . ')')
						->setCellValue('H' . $i, '=SUM(H16:H' . $sum . ')');
			
			$objPHPExcel->setActiveSheetIndex($day)
						->setCellValue('B2', $centre . ' Timesheet')
						->setCellValue('B5', 'Campaign')
						->setCellValue('E5', $campaign[0])
						->setCellValue('B7', 'Team Leader')
						->setCellValue('E7', $tl)
						->setCellValue('B9', 'Date')
						->setCellValue('E9', date("d/m/Y", strtotime($date3)))
						->setCellValue('B11', 'Total Hours')
						->setCellValue('E11', 'CPS')
						->setCellValue('B12', 'Total Sales')
						->setCellValue('E12', 'Average SPH')
						->setCellValue('B13', 'Total Agents')
						->setCellValue('E13', 'Average SPA')
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
			$objPHPExcel->getActiveSheet()->setTitle(date("l", strtotime($date3)));
		}
	}
	
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
?>