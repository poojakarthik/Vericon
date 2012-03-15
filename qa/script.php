<?php
//declare variables
$campaign = $_GET['campaign'];
$campaign_check = "";
$website = "";
$number = "";
$plan = $_GET['plan'];
$plan_name = $_GET['plan'];
$date = "<span style=\"color:#FF0000;\">_______</span>";

include "../script/source/convert.php";
include "../script/source/questions.php";
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $campaign_name . " " . $plan_name; ?></title>
<link rel="shortcut icon" href="../images/vericon.ico">
<style type="text/css">
body{
	font-family:Arial, Helvetica, sans-serif;
	font-size:11px;
}
table td{
	font-family:Arial, Helvetica, sans-serif;
	font-size:11px;
}
.line{
	width:100%;
}
.dd{
	display:none;
}
.other_plans{
	display:none;
}
.addon{
	display:none;
}
</style>
</head>

<body>
<?php

echo "<h1><u>" . $campaign_name . " " . $plan_name . "</u></h1>";
//Landline
if ($plan[0] == 'T')
{
	//Business No Contract Script
	if($campaign_check[3] == 'B' && $plan[1] == 'N')
	{
		for($page=1;$page<=21;$page++)
		{
			include "../script/order/bus_nc.php";
			echo "<div class='line'><hr></hr></div>";
		}
	}
	
	//Business 12 Month Contract Script
	elseif($campaign_check[3] == 'B' && $plan[1] == 'C')
	{
		for($page=1;$page<=22;$page++)
		{
			include "../script/order/bus_c.php";
			echo "<div class='line'><hr></hr></div>";
		}
	}
	
	//Residential No Contract Script
	elseif($campaign_check[3] == 'R' && $plan[1] == 'N')
	{
		for($page=1;$page<=20;$page++)
		{
			include "../script/order/resi_nc.php";
			echo "<div class='line'><hr></hr></div>";
		}
	}
	
	//Business 12 Month Contract Script
	elseif($campaign_check[3] == 'R' && $plan[1] == 'C')
	{
		for($page=1;$page<=22;$page++)
		{
			include "../script/order/resi_c.php";
			echo "<div class='line'><hr></hr></div>";
		}
	}
}
elseif ($plan[0] == 'A')
{
	//Business ADSL Script
	if($campaign_check[3] == 'B')
	{
		for($page=1;$page<=24;$page++)
		{
			include "../script/order/bus_adsl.php";
			echo "<div class='line'><hr></hr></div>";
		}
	}
	
	//Residential ADSL Script
	elseif($campaign_check[3] == 'R')
	{
		for($page=1;$page<=24;$page++)
		{
			include "../script/order/resi_adsl.php";
			echo "<div class='line'><hr></hr></div>";
		}
	}
}
elseif ($plan[0] == 'W')
{
	//Business ADSL Script
	if($campaign_check[3] == 'B')
	{
		for($page=1;$page<=23;$page++)
		{
			include "../script/order/bus_wireless.php";
			echo "<div class='line'><hr></hr></div>";
		}
	}
	
	//Residential ADSL Script
	elseif($campaign_check[3] == 'R')
	{
		for($page=1;$page<=23;$page++)
		{
			include "../script/order/resi_wireless.php";
			echo "<div class='line'><hr></hr></div>";
		}
	}
}
elseif ($plan[0] == 'B')
{
	//Business 12 Month Contract Multiple Product Script
	if($campaign_check[3] == 'B' && $plan[1] == 'C')
	{
		for($page=1;$page<=24;$page++)
		{
			include "../script/order/bus_bundle.php";
			echo "<div class='line'><hr></hr></div>";
		}
	}
	
	//Business 12 Month Contract Multiple Product Script
	elseif($campaign_check[3] == 'R' && $plan[1] == 'C')
	{
		for($page=1;$page<=24;$page++)
		{
			include "../script/order/resi_bundle.php";
			echo "<div class='line'><hr></hr></div>";
		}
	}
}

?>
</body>
</html>