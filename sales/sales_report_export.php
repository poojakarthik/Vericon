<?php
mysql_connect('localhost','vericon','18450be');

$date1 = $_GET["date1"];
$date2 = $_GET["date2"];
$centre = $_GET["centre"];

$q0 = mysql_query("SELECT campaigns.id FROM vericon.centres,vericon.campaigns WHERE centres.centre = '$centre' AND campaigns.campaign = centres.campaign") or die(mysql_error());
$c = mysql_fetch_row($q0);
$campaign_id = $c[0];

$q = mysql_query("SELECT user,first,last FROM vericon.auth WHERE centre = '$centre' AND status = 'Enabled' ORDER BY user ASC") or die(mysql_error());

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
				->setCellValue('B1', $centre . ' CSR')
				->setCellValue('B2', date("d/m/Y", strtotime($date1)) . ' - ' . date("d/m/Y", strtotime($date2)))
				->setCellValue('B4', 'Employee ID')
				->setCellValue('C4', 'Agent Name')
				->setCellValue('D4', 'Sales')
				->setCellValue('E4', 'Adjusted Sales');
	$i = 5;
	
	while($data = mysql_fetch_row($q))
	{
		$q1 = mysql_query("SELECT id FROM vericon.employees WHERE user = '$data[0]'") or die(mysql_error());
		$employee_id = mysql_fetch_row($q1);
		
		$q2 = mysql_query("SELECT id,type FROM vericon.sales_customers WHERE agent = '$data[0]' AND status = 'Approved' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
		$sales = mysql_num_rows($q2);
		
		$adjusted = 0;
		
		while ($sale_id = mysql_fetch_row($q2))
		{
			$p_i = 0;
			$a_i = 0;
			$b_i = 0;
			
			$q3 = mysql_query("SELECT * FROM vericon.sales_packages WHERE sid = '$sale_id[0]' ORDER BY plan DESC") or die(mysql_error());
			
			while ($pack = mysql_fetch_assoc($q3))
			{
				$q4 = mysql_query("SELECT * FROM vericon.plan_matrix WHERE id = '$pack[plan]' AND campaign = '" . mysql_real_escape_string($campaign_id) . "'") or die(mysql_error());
				$da = mysql_fetch_assoc($q4);
				
				if ($da["type"] == "PSTN")
				{
					$p_i++;
				}
				elseif ($da["type"] == "ADSL Metro" || $da["type"] == "ADSL Regional")
				{
					$a_i++;
				}
				elseif ($da["type"] == "Bundle")
				{
					$b_i++;
				}
			}
			
			$b_type = "ADSL";
			if ($p_i >= 1) { $b_type = "PSTN"; }
			if ($b_i >= 1) { $b_type = "ABUNDLE"; }
			
			if ($b_type == "PSTN" && $sale_id[1] == "Business")
			{
				$adjusted += 1;
			}
			elseif ($b_type == "PSTN" && $sale_id[1] == "Residential")
			{
				$adjusted += 0.5;
			}
			elseif ($b_type == "ADSL" && $sale_id[1] == "Business")
			{
				$adjusted += 0.5;
			}
			elseif ($b_type == "ADSL" && $sale_id[1] == "Residential")
			{
				$adjusted += 0.25;
			}
			elseif ($b_type == "ABUNDLE")
			{
				$adjusted += 1.5;
			}
		}
		
		$objPHPExcel->setActiveSheetIndex(0)
					->setCellValue('B' . $i, $employee_id[0])
					->setCellValue('C' . $i, $data[1] . " " . $data[2])
					->setCellValue('D' . $i, $sales)
					->setCellValue('E' . $i, $adjusted);
		$i++;
	}
	
	$i--;
	
	// Merge cells
	$objPHPExcel->getActiveSheet()->mergeCells('B1:E1');
	$objPHPExcel->getActiveSheet()->mergeCells('B2:E2');
	
	// Set column widths
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(22);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
	
	// Set fonts
	$objPHPExcel->getActiveSheet()->getStyle('B1')->getFont()->setSize(24);
	$objPHPExcel->getActiveSheet()->getStyle('B1:B2')->getFont()->setBold(true);
	$objPHPExcel->getActiveSheet()->getStyle('B4:E4')->getFont()->setBold(true);
	
	// Set alignments
	$objPHPExcel->getActiveSheet()->getStyle('B1:E2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('B1:E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	$objPHPExcel->getActiveSheet()->getStyle('D4:E' . $i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
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
	$objPHPExcel->getActiveSheet()->getStyle('B4:E4')->applyFromArray($styleMediumBorderFill);
	
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
	$objPHPExcel->getActiveSheet()->getStyle('B5:E' . $i)->applyFromArray($styleMediumThinBorderFill);
	
	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('CSR');
	
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// Create New Sheet
	$objPHPExcel->createSheet();
	
	// Add some data
	$objPHPExcel->setActiveSheetIndex(1)
				->setCellValue('A1', 'Sale ID')
				->setCellValue('B1', 'Employee ID')
				->setCellValue('C1', 'Agent Name')
				->setCellValue('D1', 'Date')
				->setCellValue('E1', 'Type')
				->setCellValue('F1', 'Sale Type')
				->setCellValue('G1', 'Plan 1')
				->setCellValue('H1', 'Plan 2')
				->setCellValue('I1', 'Plan 3')
				->setCellValue('J1', 'Plan 4')
				->setCellValue('K1', 'Plan 5')
				->setCellValue('L1', 'Plan 6')
				->setCellValue('M1', 'Plan 7')
				->setCellValue('N1', 'Plan 8')
				->setCellValue('O1', 'Plan 9')
				->setCellValue('P1', 'Plan 10');
	$i = 2;
	
	$q = mysql_query("SELECT user,first,last FROM vericon.auth WHERE centre = '$centre' AND status = 'Enabled' ORDER BY user ASC") or die(mysql_error());
	
	while ($data2 = mysql_fetch_row($q))
	{
		$q1 = mysql_query("SELECT id,type,agent,approved_timestamp FROM vericon.sales_customers WHERE agent = '$data2[0]' AND status = 'Approved' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2' ORDER BY id ASC") or die(mysql_error());
		
		while($data = mysql_fetch_row($q1))
		{
			$q2 = mysql_query("SELECT id FROM vericon.employees WHERE user = '$data[2]'") or die(mysql_error());
			$employee_id = mysql_fetch_row($q2);
			
			$plans = array();
			$p_c = 1;
			$p_i = 0;
			$a_i = 0;
			$b_i = 0;
			
			$q3 = mysql_query("SELECT * FROM vericon.sales_packages WHERE sid = '$data[0]' ORDER BY plan DESC") or die(mysql_error());
			
			while ($pack = mysql_fetch_assoc($q3))
			{
				$plans[$p_c] = $pack["plan"];
				$p_c++;
				
				$q4 = mysql_query("SELECT * FROM vericon.plan_matrix WHERE id = '$pack[plan]' AND campaign = '" . mysql_real_escape_string($campaign_id) . "'") or die(mysql_error());
				$da = mysql_fetch_assoc($q4);
				
				if ($da["type"] == "PSTN")
				{
					$p_i++;
				}
				elseif ($da["type"] == "ADSL Metro" || $da["type"] == "ADSL Regional")
				{
					$a_i++;
				}
				elseif ($da["type"] == "Bundle")
				{
					$b_i++;
				}
			}
			
			$b_type = "ADSL";
			if ($p_i >= 1) { $b_type = "PSTN"; }
			if ($b_i >= 1) { $b_type = "ABUNDLE"; }
			
			$objPHPExcel->setActiveSheetIndex(1)
						->setCellValue('A' . $i, $data[0])
						->setCellValue('B' . $i, $employee_id[0])
						->setCellValue('C' . $i, $data2[1] . " " . $data2[2])
						->setCellValue('D' . $i, date("d/m/Y", strtotime($data[3])))
						->setCellValue('E' . $i, $data[1])
						->setCellValue('F' . $i, $b_type)
						->setCellValue('G' . $i, $plans[1])
						->setCellValue('H' . $i, $plans[2])
						->setCellValue('I' . $i, $plans[3])
						->setCellValue('J' . $i, $plans[4])
						->setCellValue('K' . $i, $plans[5])
						->setCellValue('L' . $i, $plans[6])
						->setCellValue('M' . $i, $plans[7])
						->setCellValue('N' . $i, $plans[8])
						->setCellValue('O' . $i, $plans[9])
						->setCellValue('P' . $i, $plans[10]);
			$i++;
		}
	}
	
	$i--;
	
	// Set column widths
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(14);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(22);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(11);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(11);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(10);
	$objPHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(10);
	
	// Set fonts
	$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getFont()->setBold(true);
	
	// Set alignments
	$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
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
	$objPHPExcel->getActiveSheet()->getStyle('A1:P1')->applyFromArray($styleMediumBorderFill);
	
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
	$objPHPExcel->getActiveSheet()->getStyle('A2:P' . $i)->applyFromArray($styleMediumThinBorderFill);
	
	// Rename sheet
	$objPHPExcel->getActiveSheet()->setTitle('Sales');
	
	// Set active sheet index to the first sheet, so Excel opens this as the first sheet
	$objPHPExcel->setActiveSheetIndex(0);
	
	// Redirect output to a client’s web browser (Excel2007)
	$filename = $centre . "_CSR_" . date("d-m-Y", strtotime($date1)) . "_to_" . date("d-m-Y", strtotime($date2)) . ".xlsx";
	
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