<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$date = $_GET["date"];
$date2 = date("d.m.Y", strtotime($date));
$timestamp = date("Y-m-d H:i:s");
$year = date("Y", strtotime($date));
$month = date("F", strtotime($date));
$year_path = "/home/dsr/" . $year;
$month_path = $year_path . "/" . $month;
$directory_path = $month_path . "/" . $date2;
$report_path = $directory_path . "/DSR_" . $date2 . "_Report.txt";
$dsr_b_path = $directory_path . "/DSR_" . $date2 . "_Business.csv";
$dsr_r_path = $directory_path . "/DSR_" . $date2 . "_Residential.csv";
$tmp_path = "/home/dsr/lock/tmp.txt";

//Create temp Report
$fr = fopen($tmp_path, 'w+');

fwrite($fr,"<--Beginning DSR Process-->\n");
fwrite($fr,"<-- $timestamp -->\n");

//Check if Report Already Exists
if (file_exists($report_path))
{
	fwrite($fr,"DSR Already Exists\n");
	fclose($fr);
	exec("rm $tmp_path");
}
else
{
	//Check if Directory Exists
	if (file_exists($month_path))
	{
		fwrite($fr,"Month Directory Already Exists\n");
	}
	else
	{
		//Create Directories
		fwrite($fr,"Creating Month Directory\n");
		mkdir($month_path,0755);
	}
	
	fwrite($fr,"Creating Directory\n");
	mkdir($directory_path,0755);	
	
	//Create Business DSR
	fwrite($fr,"Generating Business DSR\n");
	
	$fdb = fopen($dsr_b_path, 'w+');
	
	$header = "DSR#,Account ID,Account Number,Recording,Sale ID,Account Status,ADSL Status,Wireless Status,Agent,Centre,Date of Sale,Whoisit,Telco Name,Rating,Industry,Title,First Name,Middle Name,Last Name,Position,DOB,Account Name,ABN,CLI 1,Plan 1,CLI 2,Plan 2,CLI 3,Plan 3,CLI 4,Plan 4,CLI 5,Plan 5,CLI 6,Plan 6,CLI 7,Plan 7,CLI 8,Plan 8,CLI 9,Plan 9,CLI 10,Plan 10,MSN 1,Mplan 1,MSN 2,Mplan 2,MSN 3,Mplan 3,WMSN 1,Wplan 1,WMSN 2,Wplan 2,ACLI,APLAN,Bundle,Building Type,Building Number,Building Number Suffix,Building Name,Street Number Start,Street Number End,Street Name,Street Type,Suburb,State,Post Code,PO Box Number Only,Mail Street Number,Mail Street,Mail Suburb,Mail State,Mail Post Code,Contract Months,Credit Offered,Welcome Email,PayWay,Direct Debit,E-Bill,Sale Type,Mobile Contact,Home Number,Current Provider,Email Address ,Additional Information,Billing Comment,Provisioning Comment,Mobile Comment,Other Comment";
		
	$q1 = mysql_query("SELECT * FROM tmp_dsr WHERE date_of_sale <= '$date' AND rating LIKE '%Business'") or die(mysql_error());
	$data = "";
	
	if (mysql_num_rows($q1) != 0)
	{
		while ($da = mysql_fetch_row($q1))
		{
			foreach ($da as $col_value)
			{
				if (substr($col_value,0,1) != "0" || $col_value == "")
				{
					$data .= '"' . $col_value . '",';
				}
				elseif (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/",$col_value))
				{
					$data .= '"' . date("d/m/Y",strtotime($col_value));
				}
				else
				{
					$data .= '="' . $col_value . '",';
				}
			}
			$data = substr_replace($data,"",-1);
			$data .= "\n";
		}
	}
	fwrite($fdb, $header);
	fwrite($fdb, "\n");
	fwrite($fdb, $data);
	fclose($fdb);
	
	$bus_rows = mysql_num_rows($q1);
	fwrite($fr,"--Added $bus_rows Rows\n");
	
	//Create Residential DSR	
	fwrite($fr,"Generating Residential DSR\n");
	
	$fdr = fopen($dsr_r_path, 'w+');
	
	$header = "DSR#,Account ID,Account Number,Recording,Sale ID,Account Status,ADSL Status,Wireless Status,Agent,Centre,Date of Sale,Whoisit,Telco Name,Rating,Industry,Title,First Name,Middle Name,Last Name,Position,DOB,Account Name,ABN,CLI 1,Plan 1,CLI 2,Plan 2,CLI 3,Plan 3,CLI 4,Plan 4,CLI 5,Plan 5,CLI 6,Plan 6,CLI 7,Plan 7,CLI 8,Plan 8,CLI 9,Plan 9,CLI 10,Plan 10,MSN 1,Mplan 1,MSN 2,Mplan 2,MSN 3,Mplan 3,WMSN 1,Wplan 1,WMSN 2,Wplan 2,ACLI,APLAN,Bundle,Building Type,Building Number,Building Number Suffix,Building Name,Street Number Start,Street Number End,Street Name,Street Type,Suburb,State,Post Code,PO Box Number Only,Mail Street Number,Mail Street,Mail Suburb,Mail State,Mail Post Code,Contract Months,Credit Offered,Welcome Email,PayWay,Direct Debit,E-Bill,Sale Type,Mobile Contact,Home Number,Current Provider,Email Address ,Additional Information,Billing Comment,Provisioning Comment,Mobile Comment,Other Comment";
		
	$q1 = mysql_query("SELECT * FROM tmp_dsr WHERE date_of_sale <= '$date' AND rating LIKE '%Residential'") or die(mysql_error());
	$data = "";
	
	if (mysql_num_rows($q1) != 0)
	{
		while ($da = mysql_fetch_row($q1))
		{
			foreach ($da as $col_value)
			{
				if (substr($col_value,0,1) != "0" || $col_value == "")
				{
					$data .= '"' . $col_value . '",';
				}
				elseif (preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/",$col_value))
				{
					$data .= '"' . date("d/m/Y",strtotime($col_value));
				}
				else
				{
					$data .= '="' . $col_value . '",';
				}
			}
			$data = substr_replace($data,"",-1);
			$data .= "\n";
		}
	}
	fwrite($fdr, $header);
	fwrite($fdr, "\n");
	fwrite($fdr, $data);
	fclose($fdr);
	
	$resi_rows = mysql_num_rows($q1);
	fwrite($fr,"--Added $resi_rows Rows\n");
	
	//Remove all exported records from tmp_dsr
	fwrite($fr,"Deleting Rows from Temporary DSR Table\n");
	mysql_query("DELETE FROM tmp_dsr WHERE date_of_sale <= '$date'") or die(mysql_error());
	
	//Complete Report
	fwrite($fr,"Generating Report");
	exec("mv $tmp_path $report_path");
}

fclose($fr);
?>