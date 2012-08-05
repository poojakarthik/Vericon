<?php
mysql_connect('localhost','vericon','18450be');

$id = $_GET["id"];

$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
$data = mysql_fetch_assoc($q);
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
	if($( "#plan" ).val() == "--- Select Script ---")
	{
		$( ".validateTips" ).html('Select a Script!');
		$( "#dialog-form" ).dialog( "open" );
	}
	else
	{
		var campaign = "<?php echo $data["campaign"] . " " . $data["type"]; ?>",
			plan = $( "#plan" ).val(),
			l = "script.php?campaign=" + campaign + "&plan=" + plan;
		
		$( ".script_name" ).html(campaign + " " + plan);
		l = l.replace(/ /g,"_");
		$( "#script" ).load(l, function() {
			$( "#dialog-form_script" ).dialog( "open" );
		});
	}
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
		'checkExisting' : 'upload/check-exists.php',
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

<div id="dialog-form_lead" title="Validate Lead">
<span class='ui-icon ui-icon-alert' style='float:left; margin-right:.3em; margin-top:4px'></span><p class="validateTips2">Are you sure you would like to validate this lead? This action cannot be reversed.</p>
</div>

<div id="dialog-form_script" title="<span class='script_name'></span>">
<div id="script"></div>
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
	$q2 = mysql_query("SELECT * FROM leads.log_leads WHERE cli = '$data[lead_id]' AND (centre = '$data[centre]' OR centre NOT LIKE 'CC%') AND issue_date <= '$dof' AND expiry_date >= '$dof' AND packet_expiry >= '$dos'") or die(mysql_error());
	
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
<td><b><?php echo $data["lead_id"]; ?></b></td>
<td align="center"><?php if ($l_check == 0) { echo '<button id="lead_btn" onclick="Validate_Lead()" class="btn2" style="font-size:9px;">Validate</button>'; } ?></td>
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
<td>
<select id="plan" style="width:210px;">
<option>--- Select Script ---</option>
<?php
$q0 = mysql_query("SELECT id FROM vericon.campaigns WHERE campaign = '" . mysql_real_escape_string($data["campaign"]) . "'") or die(mysql_error());
$c_id = mysql_fetch_row($q0);

$qp = mysql_query("SELECT plan FROM vericon.sales_packages WHERE sid = '$id'") or die(mysql_error());
while ($plan = mysql_fetch_row($qp))
{
	$q1 = mysql_query("SELECT name FROM vericon.plan_matrix WHERE id = '$plan[0]' AND campaign = '" . mysql_real_escape_string($c_id[0]) . "'") or die(mysql_error());
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
</select>
</td>
<td align="center"><button onclick="Load_Script()" class="btn2" style="font-size:9px;">Load Script</button></td>
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
<td align="center"><button onclick="Details()" class="btn2" style="font-size:9px;">Details</button></td>
</tr>
<tr>
<td style="padding: .6em 10px; text-align:center;">
<span id="voicefile_icon">
<?php
$q1 = mysql_query("SELECT * FROM vericon.recordings WHERE sale_id = '$data[id]'") or die(mysql_error());
$v = mysql_fetch_row($q1);
if ($v[0] == 0)
{
	if (file_exists("/var/vtmp/ns_" . $data["lead_id"] . ".gsm"))
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