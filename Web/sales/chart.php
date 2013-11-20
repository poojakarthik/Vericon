<?php
// Standard inclusions   
include("../lib/pChart/pData.class");
include("../lib/pChart/pChart.class");

$user = $_GET["user"];

mysql_connect('localhost','vericon','18450be');

for ($i = 0; $i <= 5; $i++)
{
	$approved[$i] = 0;
	$declined[$i] = 0;
	$lineissue[$i] = 0;
	
	$q = mysql_query("SELECT status, COUNT(id) FROM vericon.sales_customers WHERE agent = '$user' AND WEEK(approved_timestamp) = WEEK(CURRENT_DATE) AND WEEKDAY(approved_timestamp) = '$i' GROUP BY status") or die(mysql_error());
	while ($data = mysql_fetch_row($q))
	{
		if ($data[0] == "Approved")
		{
			$approved[$i] = $data[1];
		}
		elseif ($data[0] == "Declined")
		{
			$declined[$i] = $data[1];
		}
		elseif ($data[0] == "Line Issue")
		{
			$lineissue[$i] = $data[1];
		}
	}
}

// Dataset definition 
$DataSet = new pData;
$DataSet->AddPoint(array($approved[0],$approved[1],$approved[2],$approved[3],$approved[4],$approved[5]),"Serie1");
$DataSet->AddPoint(array($declined[0],$declined[1],$declined[2],$declined[3],$declined[4],$declined[5]),"Serie2");
$DataSet->AddPoint(array($lineissue[0],$lineissue[1],$lineissue[2],$lineissue[3],$lineissue[4],$lineissue[5]),"Serie3");
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
$Test = new pChart(435,200);
$Test->drawGraphAreaGradient(132,153,172,50,TARGET_BACKGROUND);
$Test->setFontProperties("../lib/Fonts/tahoma.ttf",8);
$Test->setGraphArea(50,10,410,175);
$Test->drawGraphArea(213,217,221,FALSE);
$Test->drawScale($DataSet->GetData(),$DataSet->GetDataDescription(),SCALE_NORMAL,213,217,221,TRUE,0,2,TRUE);
$Test->drawGraphAreaGradient(162,183,202,50);
$Test->drawGrid(4,TRUE,230,230,230,20);

// Draw the bar graph
$Test->drawBarGraph($DataSet->GetData(),$DataSet->GetDataDescription(),70);

// Draw the legend
$Test->setFontProperties("../lib/Fonts/tahoma.ttf",7);
$Test->drawLegend(355,5,$DataSet->GetDataDescription(),236,238,240,52,58,82);

// Display the picture
$Test->AddBorder(2);
$Test->Stroke();
?>