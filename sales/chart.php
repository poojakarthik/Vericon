<?php
// Standard inclusions   
include("../lib/pChart/pData.class");
include("../lib/pChart/pChart.class");

$method = $_GET["method"];

if ($method == "user")
{
	$user = $_GET["user"];
	$query = "agent = '$user'";
}
elseif ($method == "centre")
{
	$centre = $_GET["centre"];
	$query = "centre = '$centre'";
}
else
{
	exit;
}

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

// Monday
$q1a = mysql_query("SELECT * FROM sales_customers WHERE " . $query . " AND status = 'Approved' AND YEARWEEK(timestamp) = YEARWEEK(CURRENT_DATE) AND WEEKDAY(timestamp) = '0'") or die(mysql_error());
$monday_a = mysql_num_rows($q1a);

$q1d = mysql_query("SELECT * FROM sales_customers WHERE " . $query . " AND status = 'Declined' AND YEARWEEK(timestamp) = YEARWEEK(CURRENT_DATE) AND WEEKDAY(timestamp) = '0'") or die(mysql_error());
$monday_d = mysql_num_rows($q1d);

$q1l = mysql_query("SELECT * FROM sales_customers WHERE " . $query . " AND status = 'Line Issue' AND YEARWEEK(timestamp) = YEARWEEK(CURRENT_DATE) AND WEEKDAY(timestamp) = '0'") or die(mysql_error());
$monday_l = mysql_num_rows($q1l);

//Tuesday
$q2a = mysql_query("SELECT * FROM sales_customers WHERE " . $query . " AND status = 'Approved' AND YEARWEEK(timestamp) = YEARWEEK(CURRENT_DATE) AND WEEKDAY(timestamp) = '1'") or die(mysql_error());
$tuesday_a = mysql_num_rows($q2a);

$q2d = mysql_query("SELECT * FROM sales_customers WHERE " . $query . " AND status = 'Declined' AND YEARWEEK(timestamp) = YEARWEEK(CURRENT_DATE) AND WEEKDAY(timestamp) = '1'") or die(mysql_error());
$tuesday_d = mysql_num_rows($q2d);

$q2l = mysql_query("SELECT * FROM sales_customers WHERE " . $query . " AND status = 'Line Issue' AND YEARWEEK(timestamp) = YEARWEEK(CURRENT_DATE) AND WEEKDAY(timestamp) = '1'") or die(mysql_error());
$tuesday_l = mysql_num_rows($q2l);

//Wednesday
$q3a = mysql_query("SELECT * FROM sales_customers WHERE " . $query . " AND status = 'Approved' AND YEARWEEK(timestamp) = YEARWEEK(CURRENT_DATE) AND WEEKDAY(timestamp) = '2'") or die(mysql_error());
$wednesday_a = mysql_num_rows($q3a);

$q3d = mysql_query("SELECT * FROM sales_customers WHERE " . $query . " AND status = 'Declined' AND YEARWEEK(timestamp) = YEARWEEK(CURRENT_DATE) AND WEEKDAY(timestamp) = '2'") or die(mysql_error());
$wednesday_d = mysql_num_rows($q3d);

$q3l = mysql_query("SELECT * FROM sales_customers WHERE " . $query . " AND status = 'Line Issue' AND YEARWEEK(timestamp) = YEARWEEK(CURRENT_DATE) AND WEEKDAY(timestamp) = '2'") or die(mysql_error());
$wednesday_l = mysql_num_rows($q3l);

//Thursday
$q4a = mysql_query("SELECT * FROM sales_customers WHERE " . $query . " AND status = 'Approved' AND YEARWEEK(timestamp) = YEARWEEK(CURRENT_DATE) AND WEEKDAY(timestamp) = '3'") or die(mysql_error());
$thursday_a = mysql_num_rows($q4a);

$q4d = mysql_query("SELECT * FROM sales_customers WHERE " . $query . " AND status = 'Declined' AND YEARWEEK(timestamp) = YEARWEEK(CURRENT_DATE) AND WEEKDAY(timestamp) = '3'") or die(mysql_error());
$thursday_d = mysql_num_rows($q4d);

$q4l = mysql_query("SELECT * FROM sales_customers WHERE " . $query . " AND status = 'Line Issue' AND YEARWEEK(timestamp) = YEARWEEK(CURRENT_DATE) AND WEEKDAY(timestamp) = '3'") or die(mysql_error());
$thursday_l = mysql_num_rows($q4l);

//Friday
$q5a = mysql_query("SELECT * FROM sales_customers WHERE " . $query . " AND status = 'Approved' AND YEARWEEK(timestamp) = YEARWEEK(CURRENT_DATE) AND WEEKDAY(timestamp) = '4'") or die(mysql_error());
$friday_a = mysql_num_rows($q5a);

$q5d = mysql_query("SELECT * FROM sales_customers WHERE " . $query . " AND status = 'Declined' AND YEARWEEK(timestamp) = YEARWEEK(CURRENT_DATE) AND WEEKDAY(timestamp) = '4'") or die(mysql_error());
$friday_d = mysql_num_rows($q5d);

$q5l = mysql_query("SELECT * FROM sales_customers WHERE " . $query . " AND status = 'Line Issue' AND YEARWEEK(timestamp) = YEARWEEK(CURRENT_DATE) AND WEEKDAY(timestamp) = '4'") or die(mysql_error());
$friday_l = mysql_num_rows($q5l);

//Saturday
$q6a = mysql_query("SELECT * FROM sales_customers WHERE " . $query . " AND status = 'Approved' AND YEARWEEK(timestamp) = YEARWEEK(CURRENT_DATE) AND WEEKDAY(timestamp) = '5'") or die(mysql_error());
$saturday_a = mysql_num_rows($q6a);

$q6d = mysql_query("SELECT * FROM sales_customers WHERE " . $query . " AND status = 'Declined' AND YEARWEEK(timestamp) = YEARWEEK(CURRENT_DATE) AND WEEKDAY(timestamp) = '5'") or die(mysql_error());
$saturday_d = mysql_num_rows($q6d);

$q6l = mysql_query("SELECT * FROM sales_customers WHERE " . $query . " AND status = 'Line Issue' AND YEARWEEK(timestamp) = YEARWEEK(CURRENT_DATE) AND WEEKDAY(timestamp) = '5'") or die(mysql_error());
$saturday_l = mysql_num_rows($q6l);

// Dataset definition 
$DataSet = new pData;
$DataSet->AddPoint(array($monday_a,$tuesday_a,$wednesday_a,$thursday_a,$friday_a,$saturday_a),"Serie1");
$DataSet->AddPoint(array($monday_d,$tuesday_d,$wednesday_d,$thursday_d,$friday_d,$saturday_d),"Serie2");
$DataSet->AddPoint(array($monday_l,$tuesday_l,$wednesday_l,$thursday_l,$friday_l,$saturday_l),"Serie3");
$DataSet->AddPoint(array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"),"Serie4");
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