<?php
include "../auth/iprestrict.php";
include "../js/self-js.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Self Verification :: Script</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<link rel="stylesheet" href="../css/jqtransform.css" type="text/css" media="all" />
<link rel="stylesheet" href="../jquery/development-bundle/themes/custom-theme/jquery.ui.all.css">
<script src="../jquery/development-bundle/jquery-1.6.2.js"></script>
<script type="text/javascript" src="../js/jquery.jqtransform.js"></script>
<script src="../jquery/development-bundle/external/jquery.bgiframe-2.1.2.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.core.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.mouse.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.button.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.draggable.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.position.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.resizable.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.dialog.js"></script>
<link rel="stylesheet" href="../jquery/development-bundle/demos/demos.css">
<script type="text/javascript">
$(function() {
    $('form').jqTransform({imgPath:'../images/'});
});
</script>
<script> // Campaign Error
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:300,
		modal: true,
		buttons: {
			"OK": function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
</script>
<script> // Plan Error
$(function() {
	$( "#dialog:ui-dialog2" ).dialog( "destroy" );

	$( "#dialog-confirm2" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:300,
		modal: true,
		buttons: {
			"OK": function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
</script>
<script> //addon
$(function() {
	$( "#dialog:ui-dialog3" ).dialog( "destroy" );

	$( "#dialog-form3" ).dialog({
		autoOpen: false,
		height: 300,
		width: 500,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Close": function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
		}
	});
});

function Addon()
{
	$( "#dialog-form3" ).dialog( "open" );
}
</script>
<script> //other plans (contract)
$(function() {
	$( "#dialog:ui-dialog4" ).dialog( "destroy" );

	$( "#dialog-form4" ).dialog({
		autoOpen: false,
		height: 340,
		width: 550,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Close": function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
		}
	});
});

function Other_C()
{
	$( "#dialog-form4" ).dialog( "open" );
}
</script>
<script> //other plans (no contract)
$(function() {
	$( "#dialog:ui-dialog5" ).dialog( "destroy" );

	$( "#dialog-form5" ).dialog({
		autoOpen: false,
		height: 300,
		width: 725,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Close": function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
		}
	});
});

function Other_NC()
{
	$( "#dialog-form5" ).dialog( "open" );
}
</script>
<script> //direct debit
$(function() {
	$( "#dialog:ui-dialog6" ).dialog( "destroy" );

	$( "#dialog-form6" ).dialog({
		autoOpen: false,
		height: 525,
		width: 575,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Close": function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
		}
	});
});

function DD(campaign,website)
{
	$( ".dd_campaign" ).html(campaign);
	$( "#dialog-form6" ).dialog( "open" );
}
</script>
</head>

<body>
<div style="display:none;">
<img src="../images/load_script_btn_hover.png" />
</div>
<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/self_menu.php";
?>

<div id="text" class="demo">
<p><img src="../images/script_options_heading.png" width="150" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" alt="line" /></p><br />

<div id="dialog-confirm" title="Error!">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Please Select a Campaign and Try Again!</p>
</div>

<div id="dialog-confirm2" title="Error!">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Please Select a Plan and Try Again!</p>
</div>

<div id="dialog-form3" title="Addon Plan">
<p><b><span style="color:#000080;">If your stated line is a duet line, a standard charge of $6 including GST will apply. If not, the line will be charged as an additional addon line.</span></b></p>
<br />
<p><b><u><span style="color:#008000;">FOR ADDITIONAL LINES (ADD-ON)</span></u></b></p>
<table style="table-layout:fixed; border-collapse:collapse; margin-top:5px;">
<tr align="left" valign="top">
<td style="padding:4.9pt; border:1pt solid black;">
<p>Line rental is $39.95 per line.</p>
<p><span style="color:#008000;">Your local calls are 20c per call/fax.</span></p>
<p><span style="color:#008000;">National calls/faxes are 20c per minute with a 39c flagfall and capped at $1.10 for 30 mins.</span></p>
<p><span style="color:#008000;">Calls to mobiles are 33c per minute with a 39c flagfall and capped at $1.35 for 10 mins.</span></p>
<p><b><u><span style="color:#000080;">All charges include GST</span></u></b></p>
<p><b><span style="color:#FF0000;">Please confirm that you accept these rates by saying a clear YES.</span></b></p>
<p><b><u>CUSTOMER MUST SAY <span style="color:#FF0000;">YES</span></u></b></p>
</td>
</tr>
</table>
</div>

<div id="dialog-form4" title="Other Plans">
<table style="width:495px; table-layout:fixed; border-collapse:collapse; margin-left:auto; margin-right:auto;">
<tr align="left" valign="top">
<td style="width:165px;padding:4.9px;border:1px solid black;">
<p><b><span style=" color:#000080;">$59.95 12 Month Contract</span></b></p>
</td>
<td style="width:165px;padding:4.9px;border:1px solid black;">
<p><b><span style=" color:#000080;">$69.95 12 Month Contract</span></b></p>
</td>
<td style="width:165px;padding:4.9px;border:1px solid black;">
<p><b><span style=" color:#000080;">$129.95 12 Month Contract</span></b></p>
</td>
</tr>
<tr align="left" valign="top">
<td style="width:165px;height:58.9px;padding:4.9px;border:1px solid black;">
<p><span style="color:#000080;">Monthly plan fee of <b>$59.95 inc. GST</b> per line including the line rental with a minimum spend of $719.40 inc. GST over a 12 month contract.</span></p>
<p>All local calls are included.</p>
<p>National calls and calls to mobiles are charged at 99c per call.</p>
</td>
<td style="width:165px;height:58.9px;padding:4.9px;border:1px solid black;">
<p><span style="color:#000080;">Monthly plan fee of <b>$69.95 inc. GST</b> per line including the line rental with a minimum spend of $839.40 inc. GST over a 12 month contract.</span></p>
<p>All local & national calls are included.</p>
<p>Calls to mobiles are 99c per call.</p>
</td>
<td style="width:165px;height:58.9px;padding:4.9px;border:1px solid black;">
<p><span style="color:#000080;">Monthly plan fee of <b>$129.95 inc. GST</b> per line including the line rental with a minimum spend of $1559.40 inc. GST over a 12 month contract.</span></p>
<p>All local, national & mobile calls within Australia are included.</p>
</td>
</tr>
<tr colspan="3">
<td colspan="3" style="width:495px;padding:4.9px;border:1px solid black;">
<p>All other calls are charged on top of <span style="color:#FF0000;"><b>(Monthly Plan Fee)</b></span> examples are, line features, 13 &1300 #, directory assistance #, 19 &1900 #, monitor alarm # and international calls.</p>
<p><b><span style="color:#FF0000;">Please confirm that you accept these rates by saying a clear YES, only if you understand, and accept the terms.</span></b></p>
<p><b><u>CUSTOMER MUST SAY <span style="color:#FF0000;">YES</span></u></b></p>
</td>
</tr>
</table>
</div>

<div id="dialog-form5" title="Other Plans">
<table style="width:640px;table-layout:fixed;border-collapse:collapse;margin-left:auto; margin-right:auto;">
<tr align="left" valign="top">
<td style="width:160px;padding:4.9px;border:1px solid black;">
<p><b><span style=" color:#000080;">$64.95 No Contract</span></b></p>
</td>
<td style="width:160px;padding:4.9px;border:1px solid black;">
<p><b><span style=" color:#000080;">$74.95 No Contract</span></b></p>
</td>
<td style="width:160px;padding:4.9px;border:1px solid black;">
<p><b><span style=" color:#000080;">$99.95 No Contract</span></b></p>
</td>
<td style="width:160px;padding:4.9px;border:1px solid black;">
<p><b><span style=" color:#000080;">$139.95 No Contract</span></b></p>
</td>
</tr>
<tr align="left" valign="top">
<td style="width:160px;height:58.9px;padding:4.9px;border:1px solid black;">
<p><span style=" color:#000080;">Monthly plan fee of <b>$64.95 inc. GST</b> per line including the line rental.</span></p>
<p>All local calls are included.</p>
<p>National calls and calls to mobiles are charged at 99c per call.</p>
</td>
<td style="width:160px;height:58.9px;padding:4.9px;border:1px solid black;">
<p><span style=" color:#000080;">Monthly plan fee of <b>$74.95 inc. GST</b> per line including the line rental.</span></p>
<p>All local & national calls are included.</p>
<p>Calls to mobiles are 99c per call.</p>
</td>
<td style="width:160px;height:58.9px;padding:4.9px;border:1px solid black;">
<p><span style=" color:#000080;">Monthly plan fee of <b>$99.95 inc. GST</b> per line including the line rental.</span></p>
<p>All local & national calls are included.</p>
<p>Calls to mobiles are 20c per call.</p>
</td>
<td style="width:160px;height:58.9px;padding:4.9px;border:1px solid black;">
<p><span style=" color:#000080;">Monthly plan fee of <b>$139.95 inc. GST</b> per line including the line rental.</span></p>
<p>All local, national & mobile calls within Australia are included.</p>
</td>
</tr>
<tr colspan="4">
<td colspan="4" style="width:640px;padding:4.9px;border:1px solid black;">
<p>All other calls are charged on top of <span style="color:#FF0000;"><b>(Monthly Plan Fee)</b></span> examples are, line features, 13 &1300 #, directory assistance #, 19 &1900 #, monitor alarm # and international calls.</p>
<p><b><span style="color:#FF0000;">Please confirm that you accept these rates by saying a clear YES, only if you understand, and accept the terms.</span></b></p>
<p><b><u>CUSTOMER MUST SAY <span style="color:#FF0000;">YES</span></u></b></p>
</td>
</tr>
</table>
</div>

<div id="dialog-form6" title="Direct Debit">
<p>Do you agree to have either your credit card, or your bank account direct debited each month for any usage on your <b><span class="dd_campaign"></span></b> account?</p>
<p><b>->CUSTOMER MUST SAY <span style="color:#FF0000;">YES</span></b></p><br>
<p>Which account do you prefer, Credit Card or Bank Account?</p><br>
<table style="width:360pt;table-layout:fixed;border-collapse:collapse; margin-left:auto; margin-right:auto;">
<tr align="left" valign="top">
<td style="width:180pt;padding:4.9pt;border:1pt solid black;">
<p><b><span style="color:#000080;">Credit Card</span></b></p>
</td>
<td style="width:180pt;padding:4.9pt;border:1pt solid black;">
<p><b><span style="color:#000080;">Bank Account</span></b></p>
</td>
</tr>
<tr align="left" valign="top">
<td style="width:124.2pt;padding:4.9pt;border:1pt solid black;">
<span style="color:#000080;"><p>Please state the Card type<br>
<b>VISA, MASTERCARD, DINERS or AMEX</b></p></span>
<span style="color:#FF0000;"><b>Repeat the card type back</b></span><br>
<span style="color:#000080;"><p>Please state the number as it appears on the Card</p></span>
<span style="color:#FF0000;"><b>Repeat the number back</b></span><br>
<span style="color:#000080;"><p>Please state the name as it appears on the card</p></span>
<span style="color:#FF0000;"><b>Repeat, even spell the name back</b></span><br>
<span style="color:#000080;"><p>Please state the expiry date on the card</p></span>
<span style="color:#FF0000;"><b>Repeat the expiry date back</b></span><br>
<span style="color:#000080;"><p>Please state the CVV number (last 3 digits on the back of the card for VISA or MASTERCARD, last 4 digits for AMEX or DINERS)</p></span>
<span style="color:#FF0000;"><b>Repeat the CVV back</b></span>
</td>
<td style="width:124.2pt;padding:4.9pt;border:1pt solid black;">
<span style="color:#000080;"><p>Please name the Financial Institution</p></span>
<span style="color:#FF0000;"><b>Repeat the Institution back</b></span><br>
<span style="color:#000080;"><p>Please state the type of Bank Account<br>
(Savings, Credit, Cheque)</p></span>
<span style="color:#FF0000;"><b>Repeat the Account type back</b></span><br>
<span style="color:#000080;"><p>Please state the BSB number</p></span>
<span style="color:#FF0000;"><b>Repeat the number back</b></span><br>
<span style="color:#000080;"><p>Please state the Account number</p></span>
<span style="color:#FF0000;"><b>Repeat the number back</b></span><br>
<span style="color:#000080;"><p>Please state the name on the account as it appears on your bank statement</p></span>
<span style="color:#FF0000;"><b>Repeat the name back</b></span>
</td>
</tr>
</table><br>
<p>To check the authenticity of your banking details <b><span class="dd_campaign"></span></b> will debit a dollar from your account which will be credited back to your account within 7 working days. <b><span class="dd_campaign"></span>'s</b> terms and conditions for providing this telecommunications service and Direct Debit set-up to you are available for viewing or downloading at our website.</p>
</div>

<table width="100%" style="margin-bottom:30px; margin-top:10;">
<tr>
<td style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; color:#666666; line-height:1.5em;">Campaign: </td>
<td>
<input type="hidden" id="t" name="t" value="fresh" />
<form>
<select id="campaign" style="min-width:210px;">
<option>--- Campaign ---</option>
<?php
$q = mysql_query("SELECT campaign FROM centres WHERE centre = '$ac[centre]'");
$cam = mysql_fetch_assoc($q);

$campaign = explode(",",$cam["campaign"]);
$camlength = count($campaign);
for ($i = 0; $i < $camlength; $i++)
{
	echo "<option>" . $campaign[$i] . " Business</option>";
	echo "<option>" . $campaign[$i] . " Residential</option>";
}
?>
</select>
</form>
</td>
<td style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; color:#666666; line-height:1.5em;">Plan: </td>
<td><form><select id="plan" style="min-width:230px;">
<option>--- Plan ---</option>
<option disabled="disabled">--- Landline ---</option>
<?php
$q0 = mysql_query("SELECT * FROM plan_matrix WHERE status = 'Active' AND type = 'Landline'");

while ($l_plan = mysql_fetch_assoc($q0))
{
	echo "<option>" . $l_plan["name"] . "</option>";
}
?>
<option disabled="disabled">--- Internet ---</option>
<?php
$q0 = mysql_query("SELECT * FROM plan_matrix WHERE status = 'Active' AND type = 'ADSL'");

while ($a_plan = mysql_fetch_assoc($q0))
{
	echo "<option>" . $a_plan["name"] . "</option>";
}
?>
<option disabled="disabled">--- Bundle ---</option>
<?php
$q0 = mysql_query("SELECT * FROM plan_matrix WHERE status = 'Active' AND type = 'Bundle'");

while ($b_plan = mysql_fetch_assoc($q0))
{
	echo "<option>" . $b_plan["name"] . "</option>";
}
?>
</select>
</form>
</td>
<td colspan="3"><input type="button" onclick="LoadScript()" class="load" /></td>
</tr>
</table>

<p><img src="../images/verification_script_header.png" width="195" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" alt="line" /></p>

</div>  

<div id="script_text" style="border:0; margin-top:0;">
<iframe src="../script/script.php" id="script" name="script" width="100%" height="350px" frameborder="0">
</iframe>
</div>

</div>
<?php
include "../source/footer.php";
?>

</body>
</html>