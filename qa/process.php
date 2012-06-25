<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
.loadscript
{
	background-image:url('../images/load_script2_btn.png');
	background-repeat:no-repeat;
	height:25px;
	width:85px;
	border:none;
	background-color:transparent;
	margin-left:5px;
}

.loadscript:hover
{
	background-image:url('../images/load_script2_btn_hover.png');
	cursor:pointer;
}

.details
{
	background-image:url('../images/details_btn.png');
	background-repeat:no-repeat;
	height:25px;
	width:85px;
	border:none;
	background-color:transparent;
}

.details:hover
{
	background-image:url('../images/details_btn_hover.png');
	cursor:pointer;
}

.dsr
{
	background-image:url('../images/dsr_btn.png');
	background-repeat:no-repeat;
	height:25px;
	width:85px;
	border:none;
	background-color:transparent;
	margin-right:10px;
}

.dsr:hover
{
	background-image:url('../images/dsr_btn_hover.png');
	cursor:pointer;
}

.plans
{
	background-image:url('../images/plans_btn.png');
	background-repeat:no-repeat;
	height:25px;
	width:85px;
	border:none;
	background-color:transparent;
}

.plans:hover
{
	background-image:url('../images/plans_btn_hover.png');
	cursor:pointer;
}

.approve
{
	background-image:url('../images/approve_sale_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
	float:left;
}

.approve:hover
{
	background-image:url('../images/approve_sale_btn_hover.png');
	cursor:pointer;
}

.reject
{
	background-image:url('../images/reject_sale_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
	float:right;
}

.reject:hover
{
	background-image:url('../images/reject_sale_btn_hover.png');
	cursor:pointer;
}

input {
	height:20px;
}

div#users-contain table { margin: 1em 0; margin-bottom:0; border-collapse: collapse; }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid #eee; padding: .1em 10px; text-align: left; }
</style>
<script>
var dsr = 0;
var plans = 0;
</script>
<script> //approve sale
function Approve_Sale()
{
	$( "#dsr_plans" ).load('process_submit.php?method=plans&id=<?php echo $_GET["id"] . "&type=" . $data["type"]; ?>');
	
	var id = "<?php echo $_GET["id"]; ?>",
		verifier = "<?php echo $ac["user"]; ?>",
		lead_id = $( ".lead_id" ),
		lead = 0,
		recording = 0,
		details = 0;
		
	var account_status = $( "#account_status" ),
		adsl_status = $( "#adsl_status" ),
		wireless_status = $( "#wireless_status" );
		
	var building_type = $( "#building_type" ),
		building_number = $( "#building_number" ),
		building_number_suffix = $( "#building_number_suffix" ),
		building_name = $( "#building_name" ),
		street_number_start = $( "#street_number_start" ),
		street_number_end = $( "#street_number_end" ),
		street_name = $( "#street_name" ),
		street_type = $( "#street_type" ),
		suburb = $( "#suburb" ),
		state = $( "#state" ),
		postcode = $( "#postcode" );
		
	var	po_box_number = $( "#po_box_number" ),
		mail_street_number = $( "#mail_street_number" ),
		mail_street = $( "#mail_street" ),
		mail_suburb = $( "#mail_suburb" ),
		mail_state = $( "#mail_state" ),
		mail_postcode = $( "#mail_postcode" );
		
	var	contract_months = $( "#contract_months" ),
		credit_offered = $( "#credit_offered" ),
		payway = $( "#payway" ),
		direct_debit = $( "#direct_debit" );
		
	var	additional_information = $( "#additional_information" ),
		billing_comment = $( "#billing_comment" ),
		provisioning_comment = $( "#provisioning_comment" ),
		mobile_comment = $( "#mobile_comment" ),
		other_comment = $( "#other_comment" );
		
	var cli_1 = $( "#cli_1" ),
		plan_1 = $( "#plan_1" ),
		cli_2 = $( "#cli_2" ),
		plan_2 = $( "#plan_2" ),
		cli_3 = $( "#cli_3" ),
		plan_3 = $( "#plan_3" ),
		cli_4 = $( "#cli_4" ),
		plan_4 = $( "#plan_4" ),
		cli_5 = $( "#cli_5" ),
		plan_5 = $( "#plan_5" ),
		cli_6 = $( "#cli_6" ),
		plan_6 = $( "#plan_6" ),
		cli_7 = $( "#cli_7" ),
		plan_7 = $( "#plan_7" ),
		cli_8 = $( "#cli_8" ),
		plan_8 = $( "#plan_8" ),
		cli_9 = $( "#cli_9" ),
		plan_9 = $( "#plan_9" ),
		cli_10 = $( "#cli_10" ),
		plan_10 = $( "#plan_10" );
		
	var msn_1 = $( "#msn_1" ),
		mplan_1 = $( "#mplan_1" ),
		msn_2 = $( "#msn_2" ),
		mplan_2 = $( "#mplan_2" ),
		msn_3 = $( "#msn_3" ),
		mplan_3 = $( "#mplan_3" );
		
	var wmsn_1 = $( "#wmsn_1" ),
		wplan_1 = $( "#wplan_1" ),
		wmsn_2 = $( "#wmsn_2" ),
		wplan_2 = $( "#wplan_2" );
		
	var acli = $( "#acli" ),
		aplan = $( "#aplan" ),
		bundle = $( "#bundle" );
		
	var account_name = $( "#account_name" );

	if ($('#lead_check').attr('checked')) {
		lead = 1;
	}
	
	if ($('#recording_check').attr('checked')) {
		recording = 1;
	}
	
	if ($('#details_check').attr('checked')) {
		details = 1;
	}
	
	if (dsr == 0)
	{
		$( ".error2" ).html("Please check that the DSR details are correct!");
		$( "#dialog-confirm2" ).dialog( "open" );
	}
	else if (plans == 0)
	{
		$( ".error2" ).html("Please check that the Plan details are correct!");
		$( "#dialog-confirm2" ).dialog( "open" );
	}
	else
	{
		$.get("process_submit.php?method=approve", { id: id, verifier: verifier, lead_id: lead_id.html(), lead: lead, recording: recording, details: details },
		function(data) {
			if (data == 1)
			{
				$.get("dsr_submit.php", { id: id, account_status: account_status.val(), adsl_status: adsl_status.val(), wireless_status: wireless_status.val(), building_type: building_type.val(), building_number: building_number.val(), building_number_suffix: building_number_suffix.val(), building_name: building_name.val(), street_number_start: street_number_start.val(), street_number_end: street_number_end.val(), street_name: street_name.val(), street_type: street_type.val(), suburb: suburb.val(), state: state.val(), postcode: postcode.val(), po_box_number: po_box_number.val(), mail_street_number: mail_street_number.val(), mail_street: mail_street.val(), mail_suburb: mail_suburb.val(), mail_state: mail_state.val(), mail_postcode: mail_postcode.val(), contract_months: contract_months.val(), credit_offered: credit_offered.val(), payway: payway.val(), direct_debit: direct_debit.val(), additional_information: additional_information.val(), billing_comment: billing_comment.val(), provisioning_comment: provisioning_comment.val(), mobile_comment: mobile_comment.val(), other_comment: other_comment.val(), cli_1: cli_1.val(), plan_1: plan_1.val(), cli_2: cli_2.val(), plan_2: plan_2.val(), cli_3: cli_3.val(), plan_3: plan_3.val(), cli_4: cli_4.val(), plan_4: plan_4.val(), cli_5: cli_5.val(), plan_5: plan_5.val(), cli_6: cli_6.val(), plan_6: plan_6.val(), cli_7: cli_7.val(), plan_7: plan_7.val(), cli_8: cli_8.val(), plan_8: plan_8.val(), cli_9: cli_9.val(), plan_9: plan_9.val(), cli_10: cli_10.val(), plan_10: plan_10.val(), msn_1: msn_1.val(), mplan_1: mplan_1.val(), msn_2: msn_2.val(), mplan_2: mplan_2.val(), msn_3: msn_3.val(), mplan_3: mplan_3.val(), wmsn_1: wmsn_1.val(), wplan_1: wplan_1.val(), wmsn_2: wmsn_2.val(), wplan_2: wplan_2.val(), acli: acli.val(), aplan: aplan.val(), bundle: bundle.val(), account_name: account_name.val() },
				function(data) {
					if (data == 1)
					{
						window.location = "sales.php";
					}
					else
					{
						$( ".error2" ).html(data);
						$( "#dialog-confirm2" ).dialog( "open" );
					}
				});
			}
			else
			{
				$( ".error2" ).html(data);
				$( "#dialog-confirm2" ).dialog( "open" );
			}
		});
	}
}
</script>
<script> //reject sale
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var id = "<?php echo $_GET["id"]; ?>",
		verifier = "<?php echo $ac["user"]; ?>",
		status = $( "#status" ),
		reason = $( "#reason" ),
		tips = $( ".error" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#dialog-form" ).dialog({
		autoOpen: false,
		height: 250,
		width: 425,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Reject Sale": function() {
				if (status.val() == "")
				{
					updateTips("Select a status!");
				}
				else if (reason.val() == "")
				{
					updateTips("Please write a reason!");
				}
				else
				{
					$.get("process_submit.php?method=reject",{id: id, verifier: verifier, status: status.val(), reason: reason.val() },
					function(data) {
						$( "#dialog-form" ).dialog( "close" );
						window.location = "sales.php";
					});
				}
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
		}
	});
});

function Reject_Sale()
{
	$( "#dialog-form" ).dialog( "open" );
}
</script>
<script> //error dialog
	$(function() {
		$( "#dialog:ui-dialog2" ).dialog( "destroy" );
	
		$( "#dialog-confirm2" ).dialog({
			autoOpen: false,
			resizable: false,
			draggable: false,
			height:140,
			modal: true,
			buttons: {
				"OK": function() {
					$( this ).dialog( "close" );
				}
			}
		});
	});
</script>
<script> //DSR
$(function() {
	$( "#dialog:ui-dialog3" ).dialog( "destroy" );

	$( "#dialog-form3" ).dialog({
		autoOpen: false,
		height: 630,
		width: 760,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Save": function() {
				dsr = 1;
				$( this ).dialog( "close" );
			}
		},
		close: function() {
		}
	});
});

function DSR()
{
	$( "#dialog-form3" ).dialog( "open" );
}
</script>
<script> //Plans
$(function() {
	$( "#dialog:ui-dialog4" ).dialog( "destroy" );

	$( "#dialog-form4" ).dialog({
		autoOpen: false,
		height: 630,
		width: 760,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Save": function() {
				plans = 1;
				$( this ).dialog( "close" );
			}
		},
		close: function() {
		}
	});
});

function Plans()
{
	$( "#dsr_plans" ).load('process_submit.php?method=plans&id=<?php echo $_GET["id"] . "&type=" . $data["type"]; ?>');
	$( "#dialog-form4" ).dialog( "open" );
}
</script>
<script>
function Load_Script()
{
	if($( "#plan" ).val() == "--- Select Script ---")
	{
		window.alert('Select a Script!')
	}
	else
	{
		var campaign = $( "#campaign" ).val();
		var plan = $( "#plan" ).val();
		var l = "script.php?campaign=" + campaign + "&plan=" + plan;
		window.open(l,'script','menubar=no,scrollbars=yes,width=1000px,height=900px,left=1px,top=1px');
	}
}

function Details()
{
	var id = "<?php echo $_GET["id"]; ?>";
	var l = "details.php?id=" + id;
	window.open(l,'details','menubar=no,scrollbars=yes,width=1000px,height=900px,left=1px,top=1px');
}
</script>

<?php

$id = $_GET["id"];
$q = mysql_query("SELECT * FROM sales_customers WHERE id = '$id'") or die(mysql_error());
$data = mysql_fetch_assoc($q);

if (mysql_num_rows($q) == 0 || $id == "")
{
	echo "<script>window.location = '../qa/sales.php';</script>";
	exit;
}
?>

<div id="dialog-form" title="Reject Sale">
<p class="error">All fields are required</p><br />
<table>
<tr>
<td width="50px">Status </td>
<td><select id="status" style="margin:0px; width:125px; height:auto; padding:0;">
<option></option>
<option>In-House Rejection</option>
<option>Rework</option>
</select></td>
</tr>
<td>Reason </td>
<td><textarea id="reason" style="width:350px; height:100px; resize:none;"></textarea></td>
</tr>
</table>
</div>

<div id="dialog-confirm2" title="Error">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><span class="error2"></span></p>
</div>

<div id="dialog-form3" title="Validate DSR Details">
<table width="100%">
<tr>
<td colspan="6"><img src="../images/other_details_header.png" width="105" height="15" style="margin-left:3px;" /></td>
</tr>
<tr>
<td colspan="6"><img src="../images/line.png" width="100%" height="9" alt="line" /></td>
</tr>
<tr>
<td>Credit Offered</td>
<td><input type="text" id="credit_offered" value="" /></td>
<td>PayWay</td>
<td><input type="text" id="payway" value="" /></td>
<td>Direct Debit</td>
<td><input type="text" id="direct_debit" value="" /></td>
</tr>
</table>
<table width="100%">
<tr>
<td colspan="4"><img src="../images/dsr_comments_header.png" width="115" height="15" style="margin-left:3px;" /></td>
</tr>
<tr>
<td colspan="4"><img src="../images/line.png" width="100%" height="9" alt="line" /></td>
</tr>
<tr>
<td colspan="4" align="center"><textarea id="other_comment" style="resize:none; width:95%; height:100px; border:1px solid #C6C6C6;">
</textarea></td>
</tr>
</table>
</div>

<div id="dialog-form4" title="Validate DSR Plans">
<div id="dsr_plans">
<script>
$( "#dsr_plans" ).load('process_submit.php?method=plans&id=<?php echo $_GET["id"] . "&type=" . $data["type"]; ?>');
</script>
</div>
</div>

<p><img src="../images/process_sale_header.png" width="130" height="25" style="margin-left:3px;" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p>

<input type="hidden" id="abn" value="<?php echo $data["abn"] ?>" />
<input type="hidden" id="account_name" value="" />

<script> //get ABN
$.getJSON("../source/abrGet.php", {abn: $( "#abn" ).val() },
function(data){
	if( data['organisationName'] != null) {
		$("#account_name").val( data['organisationName'] );
	}
	else if (data['tradingName'] != null) {
		$("#account_name").val( data['tradingName'] );
	}
	else {
		$("#account_name").val( data['entityName'] );
	}
});
</script>

<table width="99%">
<tr>
<td width="20px" style="padding: .6em 10px;"><input type="checkbox" id="lead_check" /></td>
<td width="175px">Is Lead Valid?</td>
<td><b class="lead_id"><?php echo $data["lead_id"]; ?></b></td>
</tr>
<tr>
<td width="20px" style="padding: .6em 10px;"><input type="checkbox" id="recording_check" /></td>
<td width="175px">Listened to Recording?</td>
<td><input type="hidden" id="campaign" value="<?php echo $data["campaign"] . " " . $data["type"]; ?>" />
<select id="plan" style="width:210px; height:auto; padding:0px; margin:0;">
<option>--- Select Script ---</option>
<?php
$qp = mysql_query("SELECT plan FROM sales_packages WHERE sid = '$id'") or die(mysql_error());
while ($plan = mysql_fetch_row($qp))
{
	$q1 = mysql_query("SELECT name FROM plan_matrix WHERE id = '$plan[0]'") or die(mysql_error());
	$package_name = mysql_fetch_row($q1);
	
	if ($package_name[0] == "ADSL $54.95 24 Month Contract" || $package_name[0] == "ADSL $64.95 24 Month Contract")
	{
		$package_name[0] = "ADSL 15GB 24 Month Contract";
	}
	elseif ($package_name[0] == "ADSL $67.95 24 Month Contract" || $package_name[0] == "ADSL $77.95 24 Month Contract")
	{
		$package_name[0] = "ADSL 500GB 24 Month Contract";
	}
	elseif ($package_name[0] == "ADSL $69.95 24 Month Contract" || $package_name[0] == "ADSL $79.95 24 Month Contract")
	{
		$package_name[0] = "ADSL Unlimited 24 Month Contract";
	}
	
	echo "<option>" . $package_name[0] . "</option>";
}
?>
</select><input type="button" onclick="Load_Script()" class="loadscript" /></td>
</tr>
<tr>
<td width="20px" style="padding: .6em 10px;"><input type="checkbox" id="details_check" /></td>
<td width="175px">Validated Customer Details?</td>
<td><input type="button" onclick="Details()" class="details" /></td>
</tr>
<tr>
<td colspan="3">
<table width="99%">
<tr>
<td width="416px" rowspan="2"><iframe src="../upload/upload.php?lead_id=<?php echo $data["lead_id"] ?>" width="100%" height="125px" frameborder="0"></iframe></td>
<td valign="top" align="right"><input type="button" onclick="DSR()" class="dsr" /><input type="button" onclick="Plans()" class="plans" />
</tr>
<tr>
<td valign="bottom"><input type="button" onclick="Approve_Sale()" class="approve" /><input type="button" onclick="Reject_Sale()" class="reject" /></td>
</tr>
</table>
</td>
</tr>
</table>

<?php
include "../source/footer.php";
?>