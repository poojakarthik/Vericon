<?php
//declare variables
$campaign = str_replace("_", " ", $_GET['campaign']);
$campaign_check = "";
$website = "";
$number = "";
$plan = str_replace("_", " ", $_GET['plan']);
$plan_name = str_replace("_", " ", $_GET['plan']);
$date = "<span style=\"color:#FF0000;\">_______</span>";

include "../script/source/convert.php";
include "../script/source/questions.php";
?>

<style type="text/css">
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

//Landline
if ($plan[0] == 'T')
{
	//Business No Contract Script
	if($campaign_check[3] == 'B' && $plan[1] == 'N')
	{
		for($page=1;$page<=21;$page++)
		{
			include "../script/order/bus_nc.php";
			echo "<br>";
			echo "<div class='line'><hr></hr></div>";
			echo "<br>";
		}
	}
	
	//Business 12 Month Contract Script
	elseif($campaign_check[3] == 'B' && $plan[1] == 'C')
	{
		for($page=1;$page<=22;$page++)
		{
			include "../script/order/bus_c.php";
			echo "<br>";
			echo "<div class='line'><hr></hr></div>";
			echo "<br>";
		}
	}
	
	//Residential No Contract Script
	elseif($campaign_check[3] == 'R' && $plan[1] == 'N')
	{
		for($page=1;$page<=20;$page++)
		{
			include "../script/order/resi_nc.php";
			echo "<br>";
			echo "<div class='line'><hr></hr></div>";
			echo "<br>";
		}
	}
	
	//Business 12 Month Contract Script
	elseif($campaign_check[3] == 'R' && $plan[1] == 'C')
	{
		for($page=1;$page<=22;$page++)
		{
			include "../script/order/resi_c.php";
			echo "<br>";
			echo "<div class='line'><hr></hr></div>";
			echo "<br>";
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
			echo "<br>";
			echo "<div class='line'><hr></hr></div>";
			echo "<br>";
		}
	}
	
	//Residential ADSL Script
	elseif($campaign_check[3] == 'R')
	{
		for($page=1;$page<=24;$page++)
		{
			include "../script/order/resi_adsl.php";
			echo "<br>";
			echo "<div class='line'><hr></hr></div>";
			echo "<br>";
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
			echo "<br>";
			echo "<div class='line'><hr></hr></div>";
			echo "<br>";
		}
	}
	
	//Residential ADSL Script
	elseif($campaign_check[3] == 'R')
	{
		for($page=1;$page<=23;$page++)
		{
			include "../script/order/resi_wireless.php";
			echo "<br>";
			echo "<div class='line'><hr></hr></div>";
			echo "<br>";
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
			echo "<br>";
			echo "<div class='line'><hr></hr></div>";
			echo "<br>";
		}
	}
	
	//Business 12 Month Contract Multiple Product Script
	elseif($campaign_check[3] == 'R' && $plan[1] == 'C')
	{
		for($page=1;$page<=24;$page++)
		{
			include "../script/order/resi_bundle.php";
			echo "<br>";
			echo "<div class='line'><hr></hr></div>";
			echo "<br>";
		}
	}
}

?>