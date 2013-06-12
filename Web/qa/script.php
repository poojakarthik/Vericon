<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];
$id = $_GET['id'];
$plan = $_GET["plan"];
$in = $_GET["in"];
$date = "<span style=\"color:#FF0000;\">_______</span>";

$q = mysql_query("SELECT campaign FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
$da = mysql_fetch_row($q);

$q2 = mysql_query("SELECT id,number,website FROM vericon.campaigns WHERE campaign = '" . mysql_real_escape_string($da[0]) . "'") or die(mysql_error());
$da2 = mysql_fetch_row($q2);

$campaign = $da[0];
$campaign_id = $da2[0];
$alias = "<span style=\"color:#FF0000;\">_______</span>";
$number = $da2[1];
$website = $da2[2];
$rates = "";

$q0 = mysql_query("SELECT COUNT(id) FROM vericon.script_order WHERE script_order.id = '$plan' AND script_order.campaign = '$campaign_id' AND script_order.type = '$method'") or die(mysql_error());
$p = mysql_fetch_row($q0);
?>
<table width="100%" border="0" style="margin: 0; padding: 0;">
<?php
for ($page = 1; $page <= $p[0]; $page++)
{
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
	
	eval("\$question = \"$question\";");
?>
<tr>
<td>
<?php
echo $question;
?>
<br>
</td>
</tr>
<?php
}
?>
</table>