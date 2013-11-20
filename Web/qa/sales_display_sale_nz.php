<?php
mysql_connect('localhost','vericon','18450be');

$q = mysql_query("SELECT user FROM vericon.currentuser WHERE hash = '" . $_COOKIE["hash"] . "'") or die(mysql_error());
$user = mysql_fetch_row($q);

$q1 = mysql_query("SELECT * FROM vericon.auth WHERE user = '$user[0]'") or die(mysql_error());
$ac = mysql_fetch_assoc($q1);

$id = $_GET["id"];
$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
$data = mysql_fetch_assoc($q);

$q3 = mysql_query("SELECT cli FROM vericon.sales_packages WHERE sid = '$id'") or die(mysql_error());
$cli = mysql_fetch_row($q3);
?>
<script>
$(function() {
	$( "#dialog:ui-dialog_lead" ).dialog( "destroy" );
	
	$( "#dialog-form_lead" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:250,
		height:130,
		modal: true,
		show: "blind",
		hide: "blind",
		buttons: {
			"Validate": function() {
				$( "#lead_icon" ).html('<img src="../images/check_icon.png">');
				$( "#lead_check" ).val('1');
				$( "#lead_btn" ).attr("disabled", "disabled");
				$( "#dialog-form_lead" ).dialog( "close" );
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

function Validate_Lead()
{
	$( "#dialog-form_lead" ).dialog( "open" );
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog_script" ).dialog( "destroy" );
	
	$( "#dialog-form_script" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:1000,
		height:850,
		modal: true,
		show: "blind",
		hide: "blind",
		buttons: {
			"Complete": function() {
				$( "#recording_icon" ).html('<img src="../images/check_icon.png">');
				$( "#recording_check" ).val('1');
				$( "#dialog-form_script" ).dialog( "close" );
			},
			"Incomplete": function() {
				$( "#recording_icon" ).html('<img src="../images/delete_icon.png">');
				$( "#recording_check" ).val('0');
				$( "#dialog-form_script" ).dialog( "close" );
			}
		}
	});
});

function Load_Script()
{
	var campaign = "<?php echo $data["campaign"] . " " . $data["type"]; ?>",
		plan = $( "#plan" ),
		id = "<?php echo $data["id"]; ?>";
	
	$( ".script_name" ).html("Script");
	$( "#script" ).load("script.php?method=New&in=0&id=" + id + "&plan=" + plan.val(), function() {
		$( "#dialog-form_script" ).dialog( "open" );
	});
}
</script>
<script>
function Details()
{
	var id = "<?php echo $id; ?>";
	var l = "details.php?id=" + id;
	window.open(l,'details','menubar=no,scrollbars=yes,width=1000px,height=900px,left=1px,top=1px');
}
</script>
<script>
function Details_Check()
{
	$( "#details_icon" ).html('<img src="../images/check_icon.png">');
	$( "#details_check" ).val('1');
}
</script>
<link rel="stylesheet" type="text/css" href="upload/uploadify.css" />
<script type="text/javascript" src="upload/jquery.uploadify-3.1.min.js"></script>
<script>
$(function() {
    $('#file_upload').uploadify({
		'fileSizeLimit' : '20MB',
		'fileTypeDesc' : 'GSM',
        'fileTypeExts' : '*.gsm',
		'multi'    : false,
		'progressData' : 'speed',
		'removeTimeout' : 0,
        'swf'      : 'upload/uploadify.swf',
        'uploader' : 'upload/uploadify.php',
        'buttonText' : 'BROWSE...',
		'buttonClass' : 'btn',
		'width'    : 102,
		'onUploadSuccess' : function(file, data, response) {
			$( "#rec" ).attr("style", "display:none;");
			$( "#rec2" ).html('<br><img src="../images/ajax-loader.gif"> Processing Voice File...');
			$( "#rec2" ).removeAttr("style");
			$.get("sales_process.php", { method: "rename_rec", file: file, lead_id: "<?php echo $data["lead_id"]; ?>" },
			function(data2) {
				if (data2 == 1)
				{
					$( "#rec2" ).html("<br>Voice File Successfully Uploaded");
					$( "#voicefile_icon" ).html('<img src="../images/check_icon.png">');
					setTimeout(function() { $( "#rec2" ).attr("style", "display:none;"); }, 2000);
				}
				else
				{
					$( "#rec2" ).html("<br>Voice File Didn't Uploaded Successfully. Please try again");
					setTimeout(function() {
						$( "#rec" ).removeAttr("style");
						$( "#rec2" ).attr("style", "display:none;");
					}, 2000);
				}
			});
		}
    });
});
</script>
<script>
function Approve()
{
	var id = $( "#sale_id" ),
		verifier = "<?php echo $ac["user"]; ?>",
		lead = $( "#lead_check" ),
		recording = $( "#recording_check" ),
		details = $( "#details_check" ),
		billing = $( "#billing_comments"),
		other = $( "#other_comments" );
	
	$.get("sales_process.php?method=approve_nz", { id: id.val(), verifier: verifier, lead: lead.val(), recording: recording.val(), details: details.val(), billing: billing.val(), other: other.val() }, function (data) {
		if (data == 1)
		{
			var date = $( "#store_date" ),
				centre = $( "#store_centre" ),
				type = $( "#store_type" );
			
			$( "#display" ).hide('blind', '' , 'slow', function() {
				$( "#display_loading" ).show();
				$( "#display" ).load('sales_display.php?date=' + date.val(),
				function() {
					$( "#display2" ).load('sales_display2.php?date=' + date.val() + '&centre=' + centre.val() + '&type=' + type.val(),
					function() {
						$( "#display_loading" ).hide();
						$( "#display" ).show('blind', '' , 'slow');
					});
				});
			});
		}
		else
		{
			$( ".validateTips" ).html(data);
			$( "#dialog-form" ).dialog( "open" );
		}
	});
}
</script>
<script> //reject sale
$(function() {
	$( "#dialog:ui-dialog_reject" ).dialog( "destroy" );
	
	var tips = $( ".validateTips3" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#dialog-form_reject" ).dialog({
		autoOpen: false,
		height: 225,
		width: 425,
		modal: true,
		resizable: false,
		draggable: false,
		show: 'blind',
		hide: 'blind',
		buttons: {
			"Reject Sale": function() {
				var id = $( "#sale_id" ),
					verifier = "<?php echo $ac["user"]; ?>",
					reason = $( "#reason" ),
					lead = $( "#lead_check" ),
					recording = $( "#recording_check" ),
					details = $( "#details_check" );
				
				if (reason.val() == "")
				{
					updateTips("Please Write a Reason for Rejecting the Sale Below");
				}
				else
				{
					$.get("sales_process.php?method=reject",{id: id.val(), verifier: verifier, reason: reason.val(), lead: lead.val(), recording: recording.val(), details: details.val() },
					function(data) {
						if (data == "submitted")
						{
							$( "#dialog-form_reject" ).dialog( "close" );
							var date = $( "#store_date" ),
								centre = $( "#store_centre" ),
								type = $( "#store_type" );
							
							$( "#display" ).hide('blind', '' , 'slow', function() {
								$( "#display_loading" ).show();
								$( "#display" ).load('sales_display.php?date=' + date.val(),
								function() {
									$( "#display2" ).load('sales_display2.php?date=' + date.val() + '&centre=' + centre.val() + '&type=' + type.val(),
									function() {
										$( "#display_loading" ).hide();
										$( "#display" ).show('blind', '' , 'slow');
									});
								});
							});
						}
						else
						{
							updateTips(data);
						}
					});
				}
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

function Reject()
{
	$( "#dialog-form_reject" ).dialog( "open" );
}
</script>
<script>
var cli = "<?php echo $cli[0]; ?>";

$.get("sales_process.php?method=nz_address_check", { cli: cli }, function(data) {
	if (data == "Invalid Line" || data == "Application Error" || data == "Quota Limit Reached" || data == "Version Mismatch")
	{
		$( "#address_icon" ).html("<img src='../images/delete_icon.png'>");
	}
	else
	{
		$( "#address_icon" ).html("<img src='../images/check_icon.png'>");
	}
	
	$( "#nz_address_check" ).html(data);
});
</script>

<div id="dialog-form_lead" title="Validate Lead">
<span class='ui-icon ui-icon-alert' style='float:left; margin-right:.3em; margin-top:4px'></span><p class="validateTips2">Are you sure you would like to validate this lead? This action cannot be reversed.</p>
</div>

<div id="dialog-form_script" title="<span class='script_name'></span>">
<div id="script"></div>
</div>

<div id="dialog-form_reject" title="Reject Sale">
<p class="validateTips3">Please Write a Reason for Rejecting the Sale Below</p>
<table width="100%">
<tr>
<td width="50px">Reason </td>
<td><textarea id="reason" style="width:100%; height:100px; resize:none;"></textarea></td>
</tr>
</table>
</div>

<input type="hidden" id="sale_id" value="<?php echo $_GET["id"]; ?>" />

<table width="100%">
<tr>
<td style="padding-left:5px;"><img src="../images/process_sale_header.png" width="130" height="25" /></td>
</tr>
<tr>
<td><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="80%" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th style="text-align:center;">Status</th>
<th style="text-align:center;" colspan="3">Process</th>
</tr>
</thead>
<tbody>
<tr>
<td style="padding: .6em 10px; text-align:center;">
<span id="lead_icon">
<?php
$q1 = mysql_query("SELECT lead_check FROM vericon.qa_customers WHERE id = '$data[id]'") or die(mysql_error());
$l = mysql_fetch_row($q1);
if ($l[0] == 0)
{
	$dof = date("Y-m-d", strtotime($data["timestamp"]));
	$dos = date("Y-m-d", strtotime($data["approved_timestamp"]));
	$ql = mysql_query("SELECT centres FROM leads.groups WHERE centres LIKE '%$data[centre]%'") or die(mysql_error());
	$cq = "";
	if (mysql_num_rows($ql) == 0)
	{
		$cq = "centre = '$data[centre]' OR ";
	}
	else
	{
		$cen = mysql_fetch_row($ql);
		$centres = explode(",",$cen[0]);
		for ($i = 0; $i < count($centres); $i++)
		{
			$cq .= "centre = '" . $centres[$i] . "' OR ";
		}
	}
	$q2 = mysql_query("SELECT * FROM leads.log_leads WHERE cli = '$data[lead_id]' AND (" . $cq . "centre NOT LIKE 'CC%') AND issue_date <= '$dof' AND expiry_date >= '$dof' AND packet_expiry >= '$dos'") or die(mysql_error());
	
	if (mysql_num_rows($q2) == 0)
	{
		echo '<img src="../images/delete_icon.png">';
		$l_check = 0;
	}
	else
	{
		echo '<img src="../images/check_icon.png">';
		$l_check = 1;
	}
}
else
{
	echo '<img src="../images/check_icon.png">';
	$l_check = 1;
}
?>
</span>
</td>
<td width="175px">Is Lead Valid?</td>
<td style="text-align:center;"><b><?php echo $data["lead_id"]; ?></b></td>
<td style="text-align:center;"><?php if ($l_check == 0) { echo '<button id="lead_btn" onclick="Validate_Lead()" class="btn2" style="font-size:9px;">Validate</button>'; } ?></td>
</tr>
<tr>
<td style="padding: .6em 10px; text-align:center;">
<span id="recording_icon">
<?php
$q1 = mysql_query("SELECT recording_check FROM vericon.qa_customers WHERE id = '$data[id]'") or die(mysql_error());
$r = mysql_fetch_row($q1);
if ($r[0] == 0)
{
	echo '<img src="../images/delete_icon.png">';
	$r_check = 0;
}
else
{
	echo '<img src="../images/check_icon.png">';
	$r_check = 1;
}
?>
</span>
</td>
<td width="175px">Listened to Recording?</td>
<td width="210px">
<?php
$q0 = mysql_query("SELECT id FROM vericon.campaigns WHERE campaign = '" . mysql_real_escape_string($data["campaign"]) . "'") or die(mysql_error());
$c_id = mysql_fetch_row($q0);

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

$q1 = mysql_query("SELECT * FROM vericon.sales_packages WHERE sid = '$data[id]' ORDER BY plan DESC") or die(mysql_error());
while ($pack = mysql_fetch_assoc($q1))
{
	$q2 = mysql_query("SELECT * FROM vericon.plan_matrix WHERE id = '$pack[plan]' AND campaign = '" . mysql_real_escape_string($c_id[0]) . "'") or die(mysql_error());
	$da = mysql_fetch_assoc($q2);
	
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
<input type="hidden" id="plan" value="<?php echo $plan; ?>">
</td>
<td style="text-align:center;"><button onclick="Load_Script()" class="btn2" style="font-size:9px;">Load Script</button></td>
</tr>
<tr>
<td style="padding: .6em 10px; text-align:center;">
<span id="details_icon">
<?php
$q1 = mysql_query("SELECT details_check FROM vericon.qa_customers WHERE id = '$data[id]'") or die(mysql_error());
$d = mysql_fetch_row($q1);
if ($d[0] == 0)
{
	echo '<img src="../images/delete_icon.png">';
	$d_check = 0;
}
else
{
	echo '<img src="../images/check_icon.png">';
	$d_check = 1;
}
?>
</span>
</td>
<td width="175px">Validated Customer Details?</td>
<td></td>
<td style="text-align:center;"><button onclick="Details()" class="btn2" style="font-size:9px;">Details</button></td>
</tr>
<tr>
<td style="padding: .6em 10px; text-align:center;">
<span id="address_icon">
<img src="../images/ajax-loader.gif" width="16" height="16">
</span>
</td>
<td width="175px">Address Pre-Check</td>
<td colspan="2">
<span id="nz_address_check" style="font-weight:bold;">Loading...</span>
</td>
</tr>
<tr>
<td style="padding: .6em 10px; text-align:center;">
<span id="voicefile_icon">
<?php
$q1 = mysql_query("SELECT * FROM vericon.recordings WHERE sale_id = '$data[id]'") or die(mysql_error());
$v = mysql_fetch_row($q1);
if ($v[0] == 0)
{
	if (file_exists("/var/vericon/temp/ns_" . $data["lead_id"] . ".gsm"))
	{
		echo '<img src="../images/check_icon.png">';
		$v_check = 1;
	}
	else
	{
		echo '<img src="../images/delete_icon.png">';
		$v_check = 0;
	}
}
else
{
	echo '<img src="../images/check_icon.png">';
	$v_check = 1;
}
?>
</span>
</td>
<td colspan="3">Voice File Uploaded?</td>
</tr>
</tbody>
</table>
</div></center>

<center><table width="98%" style="margin-bottom:10px;">
<tr>
<td width="50%">
<table width="100%">
<tr>
<td style="padding-left:5px;"><img src="../images/billing_comments_header.png" width="130" height="15" /></td>
</tr>
<tr>
<td><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
<tr>
<td><textarea id="billing_comments" style="width:100%; height:100px; resize:none;"></textarea></td>
</tr>
</table>
</td>
<td width="50%">
<table width="100%">
<tr>
<td style="padding-left:5px;"><img src="../images/other_comments_header.png" width="125" height="15" /></td>
</tr>
<tr>
<td><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
<tr>
<td><textarea id="other_comments" style="width:100%; height:100px; resize:none;"></textarea></td>
</tr>
</table>
</td>
</tr>
</table></center>

<input type="hidden" id="lead_check" value="<?php echo $l_check; ?>">
<input type="hidden" id="recording_check" value="<?php echo $r_check; ?>">
<input type="hidden" id="details_check" value="<?php echo $d_check; ?>">

<table width="100%">
<tr>
<td align="left" valign="top" style="padding-left:10px;">
<?php
if ($v_check == 0)
{
?>
<div id="rec">
<style type="text/css">
.uploadify-button {
	background-image:url('../images/btn.png');
	background-color: transparent;
	border: none;
	text-transform:uppercase;
	padding: 0;
	font-weight: bold;
	font-family: Tahoma,Geneva,sans-serif;
	font-size: 11px;
	text-shadow:none;
}
.uploadify:hover .uploadify-button {
	background-color: transparent;
	background-image:url('../images/btn_hover.png');
}
</style>
<input type="file" name="file_upload" id="file_upload" />
</div>
<div id="rec2" style="display:none;">
</div>
<?php
}
?>
</td>
<td align="right" valign="top" style="padding-right:10px;">
<button onClick="Approve()" class="btn" style="margin-right:20px;">Approve</button> <button onClick="Reject()" class="btn_red">Reject</button>
</td>
</tr>
</table>