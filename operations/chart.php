<?php
// Standard inclusions   
include("../lib/pChart/pData.class");
include("../lib/pChart/pChart.class");

$centre = $_GET["centre"];
$query = "centre = '$centre'";
$date1 = $_GET["date1"];
$date2 = $_GET["date2"];
$centres = explode(",",$centre);

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

// Dataset definition 
$DataSet = new pData;
for ($i = 0; $i < count($centres); $i++)
{
	$q = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND centre = '$centres[$i]' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
	$approved = mysql_num_rows($q);
	$q2 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Declined' AND centre = '$centres[$i]' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
	$declined = mysql_num_rows($q2);
	$q3 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Line Issue' AND centre = '$centres[$i]' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
	$line_issue = mysql_num_rows($q3);
	
	$DataSet->AddPoint(array($approved),"Serie1");
	$DataSet->AddPoint(array($declined),"Serie2");
	$DataSet->AddPoint(array($line_issue),"Serie3");
	$DataSet->AddPoint(array($centres[$i]),"Serie4");
}
$DataSet->AddSerie("Serie1");
$DataSet->AddSerie("Serie2");
$DataSet->AddSerie("Serie3");
$DataSet->SetAbsciseLabelSerie("Serie4");
$DataSet->SetSerieName("Approved","Serie1");
$DataSet->SetSerieName("Declined","Serie2");
$DataSet->SetSerieName("Line Issue","Serie3");
$DataSet->SetYAxisName("Sales");

// Initialise the graph
$Test = new pChart(700,230);
$Test->drawGraphAreaGradient(132,153,172,50,TARGET_BACKGROUND);
$Test->setFontProperties("../lib/Fonts/tahoma.ttf",8);
$Test->setGraphArea(50,10,680,200);
$Test->drawGraphArea(213,217,221,FALSE);
$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,213,217,221,TRUE,0,2,TRUE);
$Test->drawGraphAreaGradient(162,183,202,50);
$Test->drawGrid(4,TRUE,230,230,230,20);

// Draw the bar graph
$Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),70);

// Draw the legend
$Test->setFontProperties("../lib/Fonts/tahoma.ttf",8);
$Test->drawLegend(605,5,$DataSet->GetDataDescription(),236,238,240,52,58,82);

// Display the picture
$Test->AddBorder(2);
$Test->Stroke();
?>