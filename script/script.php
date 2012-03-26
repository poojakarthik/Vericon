<?php
include "../js/self-js.php";
?>
<html>
<head>
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<style>
.addon
{
	background-image:url('../images/addon_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
	margin-right:10px;
}

.addon:hover
{
	background-image:url('../images/addon_btn_hover.png');
	cursor:pointer;
}
.other_plans
{
	background-image:url('../images/other_plans_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
}

.other_plans:hover
{
	background-image:url('../images/other_plans_btn_hover.png');
	cursor:pointer;
}
.dd
{
	background-image:url('../images/dd_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
	margin-top:10px;
}

.dd:hover
{
	background-image:url('../images/dd_btn_hover.png');
	cursor:pointer;
}

html
{
	overflow-y: auto;
}
</style>
</head>
<body>
<div style="display:none;">
<img src="../images/back_hover_btn.png" /><img src="../images/next_hover_btn.png" /><img src="../images/addon_btn_hover.png" /><img src="../images/other_plans_btn_hover.png" /><img src="../images/dd_btn_hover.png" />
</div>

<?php
//declare variables
$t = $_GET["t"];
$campaign = $_GET['campaign'];
$campaign_check = "";
$website = "";
$number = "";
$plan = $_GET['plan'];
$page = $_GET['page'];
$date = date('jS \of F Y');

include "source/convert.php";
include "source/questions.php";

?>

<table width="100%" border="0" id="script_text2" style="border-collapse: collapse; margin: 0; padding: 0;">
<tr height="98%" valign="top">
<td colspan="2">
<?php

$end1 = '</td>
</tr>
<tr height="2%" valign="bottom">
<td valign="bottom">
<table width="100%" style="margin-top:10px;">
<tr>';

$back = '<td align="left"><input type="button" onClick="Back()" style="display: none;" id="Btn_Back" class="back" /></td>';

$next = '<td align="right"><input type="button" onClick="Next()" style="display: none;"  id="Btn_Next" class="next" /></a></td>';

$end2 = '</tr>
</table>
</td>
</tr>
</table>';

//Fresh
if ($t == "fresh")
{
	//Landline
	if ($plan[0] == 'T')
	{
		//Business No Contract Script
		if($campaign_check[3] == 'B' && $plan[1] == 'N')
		{
			include ("order/bus_nc.php");
			echo $end1;
			if ($page > 1)
			{
				echo $back;
			}
			if ($page < 21)
			{
				echo $next;
			}
			echo $end2;
		}
		
		//Business 12 Month Contract Script
		elseif($campaign_check[3] == 'B' && $plan[1] == 'C')
		{
			include ("order/bus_c.php");
			echo $end1;
			if ($page > 1)
			{
				echo $back;
			}
			if ($page < 22)
			{
				echo $next;
			}
			echo $end2;
		}
		
		//Residential No Contract Script
		elseif($campaign_check[3] == 'R' && $plan[1] == 'N')
		{
			include ("order/resi_nc.php");
			echo $end1;
			if ($page > 1)
			{
				echo $back;
			}
			if ($page < 20)
			{
				echo $next;
			}
			echo $end2;
		}
		
		//Residential 12 Month Contract Script
		elseif($campaign_check[3] == 'R' && $plan[1] == 'C')
		{
			include ("order/resi_c.php");
			echo $end1;
			if ($page > 1)
			{
				echo $back;
			}
			if ($page < 22)
			{
				echo $next;
			}
			echo $end2;
		}
	}
	elseif ($plan[0] == 'A')
	{
		//Business ADSL Script
		if($campaign_check[3] == 'B')
		{
			include ("order/bus_adsl.php");
			echo $end1;
			if ($page > 1)
			{
				echo $back;
			}
			if ($page < 24)
			{
				echo $next;
			}
			echo $end2;
		}
		
		//Residential ADSL Script
		elseif($campaign_check[3] == 'R')
		{
			include ("order/resi_adsl.php");
			echo $end1;
			if ($page > 1)
			{
				echo $back;
			}
			if ($page < 24)
			{
				echo $next;
			}
			echo $end2;
		}
	}
	elseif ($plan[0] == 'W')
	{
		//Business Wireless Script
		if($campaign_check[3] == 'B')
		{
			include ("order/bus_wireless.php");
			echo $end1;
			if ($page > 1)
			{
				echo $back;
			}
			if ($page < 23)
			{
				echo $next;
			}
			echo $end2;
		}
		
		//Residential Wireless Script
		elseif($campaign_check[3] == 'R')
		{
			include ("order/resi_wireless.php");
			echo $end1;
			if ($page > 1)
			{
				echo $back;
			}
			if ($page < 23)
			{
				echo $next;
			}
			echo $end2;
		}
	}
	elseif ($plan[0] == 'B')
	{
		//Business Bundle Script
		if($campaign_check[3] == 'B')
		{
			include ("order/bus_bundle.php");
			echo $end1;
			if ($page > 1)
			{
				echo $back;
			}
			if ($page < 24)
			{
				echo $next;
			}
			echo $end2;
		}
		
		//Residential Bundle Script
		elseif($campaign_check[3] == 'R')
		{
			include ("order/resi_bundle.php");
			echo $end1;
			if ($page > 1)
			{
				echo $back;
			}
			if ($page < 24)
			{
				echo $next;
			}
			echo $end2;
		}
	}
	else
	{
		echo "Please select a Campaign and Plan then press Load Script";
	}
}
//Upgrade
elseif ($t == "upgrade")
{
	echo "upgrade script";
}
//Winback
elseif ($t == "winback")
{
	echo "winback script";
}
else
{
	echo "Please select a Campaign and Plan then press Load Script";
}

?>
</body>
</html>