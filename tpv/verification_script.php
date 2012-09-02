<?php
mysql_connect('localhost','vericon','18450be');

$id = $_GET["id"];
$user = $_GET["user"];

$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
$data = mysql_fetch_assoc($q);

$q1 = mysql_query("SELECT first,last,alias FROM vericon.auth WHERE user = '$data[agent]'") or die(mysql_error());
$agent = mysql_fetch_assoc($q1);

$q2 = mysql_query("SELECT id FROM vericon.campaigns WHERE campaign = '" . mysql_real_escape_string($data["campaign"]) . "'") or die(mysql_error());
$c = mysql_fetch_row($q2);
$campaign_id = $c[0];

$contract_months = 0;
$p_i = 0;
$a_i = 0;
$b_i = 0;
$p = 1;
$a = 1;
$p_packages = array();
$a_packages = array();
$b_packages = array();
$p_plan = array();
$a_plan = array();

$q2 = mysql_query("SELECT * FROM vericon.sales_packages WHERE sid = '$id' ORDER BY plan DESC") or die(mysql_error());
while ($pack = mysql_fetch_assoc($q2))
{
	$q3 = mysql_query("SELECT * FROM vericon.plan_matrix WHERE id = '$pack[plan]' AND campaign = '" . mysql_real_escape_string($campaign_id) . "'") or die(mysql_error());
	$da = mysql_fetch_assoc($q3);
	
	if (preg_match("/24 Month Contract/", $da["name"]))
	{
		$contract = 24;
	}
	elseif (preg_match("/12 Month Contract/", $da["name"]))
	{
		$contract = 12;
	}
	else
	{
		$contract = 0;
	}
	
	if ($da["type"] == "PSTN")
	{
		$p_packages[$p_i] = $contract . "," . $da["id"];
		$p_i++;
	}
	elseif ($da["type"] == "ADSL Metro" || $da["type"] == "ADSL Regional")
	{
		$a_packages[$a_i] = $contract . "," . $da["id"];
		$a_i++;
	}
	elseif ($da["type"] == "Bundle")
	{
		$b_packages[$b_i] = $contract . "," . $da["id"];
		$b_i++;
	}
}

if ($b_i >= 1)
{
	$package = explode(",", $b_packages[0]);
	$plan = $package[1];
}
elseif ($a_i >= 1)
{
	$package = explode(",", $a_packages[0]);
	$plan = $package[1];
}
elseif ($p_i >= 1)
{
	rsort($p_packages);
	$package = explode(",", $p_packages[0]);
	$plan = $package[1];
}
?>

<input type="hidden" id="id" value="<?php echo $data["id"]; ?>" />
<input type="hidden" id="sale_type" value="<?php echo $data["type"]; ?>" />
<input type="hidden" id="script_plan" value="<?php echo $plan; ?>" />
<input type="hidden" id="page" value="1" />

<table width="100%">
<tr>
<td width="50%" valign="top" style="padding-right:20px;">
<table width="100%" style="margin-bottom:10px;">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/sale_details_header.png" width="90" height="15" /></td>
<td align="right" style="padding-right:10px;"></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
<tr>
<td width="100px" style="padding-left:5px;">Sale ID </td>
<td><b><?php echo $data["id"]; ?></b></td>
</tr>
<tr>
<td style="padding-left:5px;">Agent Name </td>
<td><b><?php echo $agent["first"] . " " . $agent["last"] . " (" . $agent["alias"] . ")"; ?></b></td>
</tr>
<tr>
<td style="padding-left:5px;">Centre </td>
<td><b><?php echo $data["centre"]; ?></b></td>
</tr>
<tr>
<td style="padding-left:5px;">Campaign </td>
<td><b><?php echo $data["campaign"]; ?></b></td>
</tr>
<tr>
<td style="padding-left:5px;">Type </td>
<td><b><?php echo $data["type"]; ?></b>
<?php
if ($data["type"] == "Residential")
{
	echo "<button onclick='Change_Type(\"Business\")' class='icon_business' style='float:right; margin-right:10px;' title='Business'></button>";
}
elseif ($data["type"] == "Business")
{
	echo "<button onclick='Change_Type(\"Residential\")' class='icon_residential' style='float:right; margin-right:10px;' title='Residential'></button>";
}
?>
</td>
</tr>
</table>
</td>
<td width="50%" valign="top" style="padding-left:20px;">
<table width="100%" style="margin-bottom:10px;">
<tr>
<td style="padding-left:5px;"><img src="../images/tpv_notes_header.png" width="80" height="15" /></td>
</tr>
<tr>
<td><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
<tr>
<td><center><textarea id="notes" rows="5" style="width:98%; resize:none;"></textarea></center></td>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan="2" valign="top">
<table width="100%">
<tr>
<td style="padding-left:5px;"><img src="../images/verification_script_header2.png" width="140" height="15"></td>
</tr>
<tr>
<td><img src="../images/line.png" width="100%" height="9"></td>
</tr>
<tr>
<td>
<center><div id="script_text" style="border:1px solid #eee; padding:3px; margin:0; width:98%; height:380px; overflow-y: auto;">
<script>
var id = $( "#id" ),
	plan = $( "#script_plan" ),
	user = "<?php echo $user; ?>",
	page = $( "#page" );

$( "#script_text" ).load("../script/script.php?method=New&in=1&id=" + id.val() + "&user=" + user + "&plan=" + plan.val() + "&page=" + page.val());
</script>
</div></center>
</td>
</tr>
</table>
</td>
</tr>
</table>