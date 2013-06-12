<?php
include "../auth/iprestrict.php";
include "../source/header.php";
include "../js/self-js.php";
?>
<link rel="stylesheet" href="../css/jqtransform.css" type="text/css" media="all" />
<script type="text/javascript" src="../js/jquery.jqtransform.js"></script>
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

<?php
if ($_GET["t"] == "fresh")
{
?>
<table width="100%" style="margin-bottom:30px; margin-top:10;">
<tr>
<td style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; color:#666666; line-height:1.5em;">Campaign: </td>
<td>
<input type="hidden" id="t" name="t" value="fresh" />
<form>
<select id="campaign" style="min-width:210px;">
<option>--- Campaign ---</option>
<?php
$q = mysql_query("SELECT campaign FROM campaigns ORDER BY campaign ASC");
while ($campaign = mysql_fetch_row($q))
{
	echo "<option>" . $campaign[0] . " Business</option>";
	echo "<option>" . $campaign[0] . " Residential</option>";
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
$q0 = mysql_query("SELECT * FROM plan_matrix WHERE status = 'Active' AND rating = 'Business' AND type = 'PSTN' AND name != 'Addon' ORDER BY id ASC");

while ($l_plan = mysql_fetch_assoc($q0))
{
	echo "<option>" . $l_plan["name"] . "</option>";
}
?>
<option disabled="disabled">--- Internet ---</option>
<option>ADSL 15GB 24 Month Contract</option>
<option>ADSL Unlimited 24 Month Contract</option>
<option>Wireless 2GB 24 Month Contract</option>
<option>Wireless 4GB 24 Month Contract</option>
<option>Wireless 6GB 24 Month Contract</option>
<option>Wireless 8GB 24 Month Contract</option>
<option disabled="disabled">--- Bundle ---</option>
<?php
$q0 = mysql_query("SELECT * FROM plan_matrix WHERE status = 'Active' AND rating = 'Business' AND type = 'Bundle' ORDER BY id ASC");

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
<?php
}
elseif ($_GET["t"] == "upgrade")
{
?>
<table width="100%" style="margin-bottom:30px; margin-top:10;">
<tr>
<td style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; color:#666666; line-height:1.5em;">Campaign: </td>
<td>
<input type="hidden" id="t" name="t" value="upgrade" />
<form>
<select id="campaign">
<option>--- Campaign ---</option>
<option value='Complete_Telecom_B'>Complete Telecom Business</option>
<option value='Complete_Telecom_R'>Complete Telecom Residential</option>
<option value='Fairtalk_B'>Fairtalk Business</option>
<option value='Fairtalk_R'>Fairtalk Residential</option>
<option value='Ourtel_B'>Ourtel Business</option>
<option value='Ourtel_R'>Ourtel Residential</option>
<option value='SimplyTel_B'>Simplytel Business</option>
<option value='SimplyTel_R'>Simplytel Residential</option>
<option value='TimeTelecom_B'>Time Telecom Business</option>
<option value='TimeTelecom_R'>Time Telecom Residential</option>
<option value='Touchtel_B'>Touchtel Business</option>
<option value='Touchtel_R'>Touchtel Residential</option>
<option value='ValueTel_B'>Valuetel Business</option>
<option value='ValueTel_R'>Valuetel Residential</option>
<option value='Yourtel_B'>Yourtel Business</option>
<option value='Yourtel_R'>Yourtel Residential</option>
</select>
</form>
</td>
<td style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; color:#666666; line-height:1.5em;">Plan: </td>
<td><form><select id="plan">
<option>--- Plan ---</option>
<option disabled="disabled">--- Landline ---</option>
<option>$39.95 No Contract</option>
<option>$59.95 12 Month Contract</option>
<option>$64.95 No Contract</option>
<option>$69.95 12 Month Contract</option>
<option>$74.95 No Contract</option>
<option>$99.95 No Contract</option>
<option>$129.95 12 Month Contract</option>
<option>$139.95 No Contract</option>
<option disabled="disabled">--- Internet ---</option>
<option>ADSL 15GB 24 Month Contract</option>
<option>ADSL 500GB 24 Month Contract</option>
<option>ADSL Unlimited 24 Month Contract</option>
<option>Wireless 2GB 24 Month Contract</option>
<option>Wireless 4GB 24 Month Contract</option>
<option>Wireless 6GB 24 Month Contract</option>
<option>Wireless 8GB 24 Month Contract</option>
<option disabled="disabled">--- Mobile ---</option>
<option>Sim Only $59.95 12 Month Contract</option>
<option disabled="disabled">--- Bundle ---</option>
<option>Bundle $89.95 24 Month Contract</option>
<option>Bundle $102.95 24 Month Contract</option>
<option>Bundle $104.95 24 Month Contract</option>
<option>Bundle $119.95 24 Month Contract</option>
<option>Bundle $129.95 24 Month Contract</option>
<option>Bundle $132.95 24 Month Contract</option>
<option>Bundle $134.95 24 Month Contract</option>
<option>Bundle $142.95 24 Month Contract</option>
<option>Bundle $144.95 24 Month Contract</option>
<option>Bundle $159.95 24 Month Contract</option>
</select>
</form>
</td>
<td colspan="3"><input type="button" onclick="LoadScript()" class="load" /></td>
</tr>
</table>
<?php
}
elseif ($_GET["t"] == "winback")
{
?>
<table width="100%" style="margin-bottom:30px; margin-top:10;">
<tr>
<td style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; color:#666666; line-height:1.5em;">Campaign: </td>
<td>
<input type="hidden" id="t" name="t" value="winback" />
<form>
<select id="campaign">
<option>--- Campaign ---</option>
<option value='Complete_Telecom_B'>Complete Telecom Business</option>
<option value='Complete_Telecom_R'>Complete Telecom Residential</option>
<option value='Fairtalk_B'>Fairtalk Business</option>
<option value='Fairtalk_R'>Fairtalk Residential</option>
<option value='Ourtel_B'>Ourtel Business</option>
<option value='Ourtel_R'>Ourtel Residential</option>
<option value='SimplyTel_B'>Simplytel Business</option>
<option value='SimplyTel_R'>Simplytel Residential</option>
<option value='TimeTelecom_B'>Time Telecom Business</option>
<option value='TimeTelecom_R'>Time Telecom Residential</option>
<option value='Touchtel_B'>Touchtel Business</option>
<option value='Touchtel_R'>Touchtel Residential</option>
<option value='ValueTel_B'>Valuetel Business</option>
<option value='ValueTel_R'>Valuetel Residential</option>
<option value='Yourtel_B'>Yourtel Business</option>
<option value='Yourtel_R'>Yourtel Residential</option>
</select>
</form>
</td>
<td style="font-family:Tahoma, Geneva, sans-serif; font-size:12px; color:#666666; line-height:1.5em;">Plan: </td>
<td><form><select id="plan">
<option>--- Plan ---</option>
<option disabled="disabled">--- Landline ---</option>
<option>$39.95 No Contract</option>
<option>$59.95 12 Month Contract</option>
<option>$64.95 No Contract</option>
<option>$69.95 12 Month Contract</option>
<option>$74.95 No Contract</option>
<option>$99.95 No Contract</option>
<option>$129.95 12 Month Contract</option>
<option>$139.95 No Contract</option>
<option disabled="disabled">--- Internet ---</option>
<option>ADSL 15GB 24 Month Contract</option>
<option>ADSL 500GB 24 Month Contract</option>
<option>ADSL Unlimited 24 Month Contract</option>
<option>Wireless 2GB 24 Month Contract</option>
<option>Wireless 4GB 24 Month Contract</option>
<option>Wireless 6GB 24 Month Contract</option>
<option>Wireless 8GB 24 Month Contract</option>
<option disabled="disabled">--- Bundle ---</option>
<option>Bundle $89.95 24 Month Contract</option>
<option>Bundle $102.95 24 Month Contract</option>
<option>Bundle $104.95 24 Month Contract</option>
<option>Bundle $119.95 24 Month Contract</option>
<option>Bundle $129.95 24 Month Contract</option>
<option>Bundle $132.95 24 Month Contract</option>
<option>Bundle $134.95 24 Month Contract</option>
<option>Bundle $142.95 24 Month Contract</option>
<option>Bundle $144.95 24 Month Contract</option>
<option>Bundle $159.95 24 Month Contract</option>
</select>
</form>
</td>
<td colspan="3"><input type="button" onclick="LoadScript()" class="load" /></td>
</tr>
</table>
<?php
}
else
{
	echo "Error!";
}
?>

<p><img src="../images/verification_script_header.png" width="195" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" alt="line" /></p>

</div>  

<div id="script_text" style="border:0; margin-top:0;">
<iframe src="../script/script.php" id="script" name="script" width="100%" height="350px" frameborder="0">
</iframe>

<?php
include "../source/footer.php";
?>