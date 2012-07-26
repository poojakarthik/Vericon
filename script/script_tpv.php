<?php
mysql_connect('localhost','vericon','18450be');

$id = $_GET['id'];
$user = $_GET["user"];

$q = mysql_query("SELECT campaign,type FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
$da = mysql_fetch_row($q);

$q1 = mysql_query("SELECT alias FROM vericon.auth WHERE user = '$user'") or die(mysql_error());
$da2 = mysql_fetch_row($q1);

$alias = $da2[0];
$campaign = $da[0] . " " . $da[1];
$campaign_check = "";
$website = "";
$number = "";
$plan = str_replace("_", " ", $_GET['plan']);
$page = $_GET['page'];
$date = date('jS \of F Y');

include "source/convert.php";
include "source/input.php";
include "source/questions.php";
?>

<script>
if ($( "#Btn_Next") != null)
{
	setTimeout(function() {$( "#Btn_Next").removeAttr("style"); }, 1000);
}
</script>

<table width="100%" border="0" id="script_text2" style="border-collapse: collapse; margin: 0; padding: 0; height:380px;">
<tr height="98%" valign="top">
<td colspan="2">
<?php
$end1 = '</td>
</tr>
<tr height="2%" valign="bottom">
<td valign="bottom">
<table width="100%" style="margin-top:10px;">
<tr valign="middle"><td width="33.33%" align="left">';

$back = '<input type="button" onClick="Back()" id="Btn_Back" class="back" />';

$cancel_btn = '</td><td width="33.33%" align="center"><button id="Btn_Cancel" onClick="Cancel()" class="btn_red">Cancel</button><img src="../images/loading.gif" id="image_load" style="display:none;"></td>';

$next_btn = '<td width="33.33%" align="right"></td>';

$end2 = '</tr>
</table>
</td>
</tr>
</table>';

//Landline
if ($plan[0] == 'T')
{
	//Business No Contract Script
	if($campaign_check[3] == 'B' && $plan[1] == 'N')
	{
		include ("order_tpv/bus_nc.php");
	}
	
	//Business 12 Month Contract Script
	elseif($campaign_check[3] == 'B' && $plan[1] == 'C')
	{
		include ("order_tpv/bus_c.php");
	}
	
	//Residential No Contract Script
	elseif($campaign_check[3] == 'R' && $plan[1] == 'N')
	{
		include ("order_tpv/resi_nc.php");
	}
	
	//Business 12 Month Contract Script
	elseif($campaign_check[3] == 'R' && $plan[1] == 'C')
	{
		include ("order_tpv/resi_c.php");
	}
	
	echo $end1;
	if ($page > 1)
	{
		echo $back;
	}
	echo $cancel_btn;
	echo $next_btn;
	echo $end2;
}
elseif ($plan[0] == 'A')
{
	//Business ADSL Script
	if($campaign_check[3] == 'B')
	{
		include ("order_tpv/bus_adsl.php");
	}
	
	//Residential ADSL Script
	elseif($campaign_check[3] == 'R')
	{
		include ("order_tpv/resi_adsl.php");
	}
	
	echo $end1;
	if ($page > 1)
	{
		echo $back;
	}
	echo $cancel_btn;
	echo $next_btn;
	echo $end2;
}
elseif ($plan[0] == 'B')
{
	//Business Bundle Script
	if($campaign_check[3] == 'B')
	{
		include ("order_tpv/bus_bundle.php");
	}
	
	//Residential Bundle Script
	elseif($campaign_check[3] == 'R')
	{
		include ("order_tpv/resi_bundle.php");
	}
	
	echo $end1;
	if ($page > 1)
	{
		echo $back;
	}
	echo $cancel_btn;
	echo $next_btn;
	echo $end2;
}
else
{
	echo "Error! Script does not exist for this plan";
}

?>
</td>
</tr>
</table>