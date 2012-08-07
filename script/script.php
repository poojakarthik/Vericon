<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];
$id = $_GET['id'];
$user = $_GET["user"];
$plan = $_GET["plan"];
$page = $_GET["page"];
$in = $_GET["in"];
$date = date('jS \of F Y');

$q = mysql_query("SELECT campaign FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
$da = mysql_fetch_row($q);

$q1 = mysql_query("SELECT alias FROM vericon.auth WHERE user = '$user'") or die(mysql_error());
$da1 = mysql_fetch_row($q1);

$q2 = mysql_query("SELECT id,number,website FROM vericon.campaigns WHERE campaign = '" . mysql_real_escape_string($da[0]) . "'") or die(mysql_error());
$da2 = mysql_fetch_row($q2);

$campaign = $da[0];
$campaign_id = $da2[0];
$alias = $da1[0];
$number = $da2[1];
$website = $da2[2];
$rates = "";

$q3 = mysql_query("SELECT script_questions.question,script_order.back,script_order.next,script_questions.input FROM vericon.script_order,vericon.script_questions WHERE script_order.id = '$plan' AND script_order.campaign = '$campaign_id' AND script_order.type = '$method' AND script_order.page = '$page' AND script_order.question = script_questions.id") or die(mysql_error());
$da3 = mysql_fetch_row($q3);

$q4 = mysql_query("SELECT * FROM vericon.sales_packages WHERE sid = '$id' ORDER BY plan DESC") or die(mysql_error());
while ($plan_rate = mysql_fetch_assoc($q4))
{
	$q5 = mysql_query("SELECT * FROM vericon.script_plans WHERE id = '$plan_rate[plan]' AND campaign = '$campaign_id'") or die(mysql_error());
	$plan_script = mysql_fetch_assoc($q5);
	if($pl[$plan_rate["plan"]] == 0)
	{
		$rates .= "<table width='100%'>";
		$rates .= "<tr>";
		$rates .= "<td style='padding:4.9pt;border-top:1pt solid black;border-right:1pt solid black;border-bottom:1pt solid black;border-left:1pt solid black;'>";
		$rates .= $plan_script["script"];
		$rates .= "</td>";
		$rates .= "</tr>";
		$rates .= "</table><br>";
		$pl[$plan_rate["plan"]] = 1;
	}
}

if ($da3[0] == "") {
	$question = "Error! Script does not exist for this plan";
} else {
	$question = $da3[0];
}

if ($da3[1] == "Y") {
	$back = '<td width="33.33%" align="left"><button onClick="Back()" id="Btn_Back" class="back"></button></td>';
} else {
	$back = '<td width="33.33%" align="left"></td>';
}

if ($in == 1)
{
	if ($da3[2] == "Y") {
		$next = '<td width="33.33%" align="right"><button onClick="Next(\'' . $id . '\',\'' . $da3[3] . '\')" style="display:none;" id="Btn_Next" class="next"></button></td>';
	} elseif ($da3[2] == "S") {
		$next = '<td width="33.33%" align="right"><button onClick="Submit()" style="display:none;" id="Btn_Next" class="btn">SUBMIT</button></td>';
	} else {
		$next = '<td width="33.33%" align="left"></td>';
	}
}
else
{
	if ($da3[2] == "Y") {
		$next = '<td width="33.33%" align="right"><button onClick="Next()" style="display:none;" id="Btn_Next" class="next"></button></td>';
	} else {
		$next = '<td width="33.33%" align="left"></td>';
	}

}

include("input.php");
eval("\$question = \"$question\";");
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
echo $question;
if ($da3[3] != "" && $in == 1)
{
	echo $input[$da3[3]];
}
?>
</td>
</tr>
<tr height="2%" valign="bottom">
<td valign="bottom">
<table width="100%" style="margin-top:10px;">
<tr valign="middle">
<?php echo $back; ?>
<td width="33.33%" align="center"><button id="Btn_Cancel" onClick="Cancel()" class="btn_red">Cancel</button></td>
<?php echo $next; ?>
</tr>
</table>
</td>
</tr>
</table>