<?php
include "../js/tpv-js.php";
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');
?>
<html>
<head>
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<link rel="stylesheet" href="../jquery/development-bundle/themes/custom-theme/jquery.ui.all.css">
<script src="../jquery/development-bundle/jquery-1.6.2.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.core.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.datepicker.js"></script>
<script src="../jquery/development-bundle/external/jquery.bgiframe-2.1.2.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.mouse.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.button.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.draggable.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.position.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.resizable.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.dialog.js"></script>
<script src="../jquery/development-bundle/ui/jquery.effects.core.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.autocomplete.js"></script>
<link rel="stylesheet" href="../jquery/development-bundle/demos/demos.css">
<style>
.cancel
{
	background-image:url('../images/cancel_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
}

.cancel:hover
{
	background-image:url('../images/cancel_btn_hover.png');
	cursor:pointer;
}

.submit
{
	background-image:url('../images/submit_form_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
	margin-right:10px;
}

.submit:hover
{
	background-image:url('../images/submit_form_btn_hover.png');
	cursor:pointer;
}

.addpackage
{
	background-image:url('../images/add_package_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
	margin-right:10px;
}

.addpackage:hover
{
	background-image:url('../images/add_package_btn_hover.png');
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

.search
{
	background-image:url('../images/search_btn_2.png');
	background-repeat:no-repeat;
	height:25px;
	width:85px;
	border:none;
	background-color:transparent;
	margin-right:10px;
}

.search:hover
{
	background-image:url('../images/search_btn_hover_2.png');
	cursor:pointer;
}

div#users-contain table { margin: 1em 0; border-collapse: collapse; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
</style>
<script> //add packages
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var id = "<?php echo $_GET["id"]; ?>",
		cli = $( "#add_cli" ),
		plan = $( "#add_plan" ),
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
		height: 200,
		width: 275,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Add Package": function() {
				if (cli.val() == "")
				{
					updateTips("Enter the CLI!");
				}
				else if (plan.val() == "")
				{
					updateTips("Select a plan!");
				}
				else
				{
					$.get("../../tpv/verification_submit.php?method=add", { id: id, cli: cli.val(), plan: plan.val() },
					function(data) {
						if (data == "added")
						{
							$( "#dialog-form" ).dialog( "close" );
							var id = '<?php echo $_GET["id"]; ?>';
							$( '#packages' ).load('../../tpv/packages.php?id=' + id);
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
		},
		close: function() {
		}
	});
});

function Add_Package()
{
	$( "#dialog-form" ).dialog( "open" );
}
</script>
<script> //edit packages
$(function() {
	$( "#dialog:ui-dialog3" ).dialog( "destroy" );
	
	var id = "<?php echo $_GET["id"]; ?>",
		cli = $( "#edit_cli" ),
		plan = $( "#edit_plan" ),
		cli2 = $( "#original_edit_cli" ),
		tips = $( ".error3" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#dialog-form3" ).dialog({
		autoOpen: false,
		height: 200,
		width: 275,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Edit Package": function() {
				if (cli.val() == "")
				{
					updateTips("Enter the CLI!");
				}
				else if (plan.val() == "")
				{
					updateTips("Select a plan!");
				}
				else
				{
					$.get("../../tpv/verification_submit.php?method=edit", { id: id, cli: cli.val(), plan: plan.val(), cli2: cli2.val() },
					function(data) {
						if (data == "editted")
						{
							$( "#dialog-form3" ).dialog( "close" );
							var id = '<?php echo $_GET["id"]; ?>';
							$( '#packages' ).load('../../tpv/packages.php?id=' + id);
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
		},
		close: function() {
		}
	});
});

function Edit_Package(cli,plan)
{
	$( "#edit_cli" ).val(cli);
	$( "#edit_plan" ).val(plan);
	$( "#original_edit_cli" ).val(cli);
	$( "#dialog-form3" ).dialog( "open" );
}
</script>
<script> //delete packages
function Delete_Package(cli)
{
	var id = "<?php echo $_GET["id"]; ?>",
		cli = cli;
	
	$.get("../../tpv/verification_submit.php?method=delete", { id: id, cli: cli},
	function(data) {
		if (data == "deleted")
		{
			var id = '<?php echo $_GET["id"]; ?>';
			$( '#packages' ).load('../../tpv/packages.php?id=' + id);
		}
	});
}
</script>
<script>
function Physical_ID(id)
{
	$( "#physical" ).val(id)
}

function Physical_Display()
{
	var id = $( "#physical" );
	$( '#physical_address' ).load('../../tpv/address.php?id=' + id.val());
}

function Postal_ID(id)
{
	$( "#postal" ).val(id)
}

function Postal_Display(text)
{
	var id = $( "#postal" );
	$( '#postal_address' ).load('../../tpv/address.php?id=' + id.val());
}
</script>

</head>
<body>

<div id="dialog-form" title="Add a Package">
<p class="error">All fields are required</p><br />
<table>
<tr>
<td width="50px">CLI </td>
<td><input type="text" size="15" id="add_cli" style="margin-top:0px;" /></td>
</tr>
<tr>
<td>Plan </td>
<td><select id="add_plan" style="margin-left:0px; width:210px; height:25px; padding:1px 0 0;">
<option></option>
<option disabled="disabled">--- Landline ---</option>
<?php
$qp = mysql_query("SELECT * FROM plan_matrix WHERE status = 'Active' AND type = 'Landline'");

while ($l_plan = mysql_fetch_assoc($qp))
{
	echo "<option>" . $l_plan["name"] . "</option>";
}
?>
<option>Addon</option>
<option>Duet</option>
<option disabled="disabled">--- Internet ---</option>
<?php
$qp = mysql_query("SELECT * FROM plan_matrix WHERE status = 'Active' AND type = 'ADSL'");

while ($a_plan = mysql_fetch_assoc($qp))
{
	echo "<option>" . $a_plan["name"] . "</option>";
}
?>
<option disabled="disabled">--- Bundle ---</option>
<?php
$qp = mysql_query("SELECT * FROM plan_matrix WHERE status = 'Active' AND type = 'Bundle'");

while ($b_plan = mysql_fetch_assoc($qp))
{
	echo "<option>" . $b_plan["name"] . "</option>";
}
?>
</select></td>
</tr>
</table>
</div>

<div id="dialog-form3" title="Edit Package">
<p class="error3">All fields are required</p><br />
<input type="hidden" id="original_edit_cli" value="" />
<table>
<tr>
<td width="50px">CLI </td>
<td><input type="text" size="15" id="edit_cli" style="margin-top:0px;" /></td>
</tr>
<tr>
<td>Plan </td>
<td><select id="edit_plan" style="margin-left:0px; width:210px; height:25px; padding:1px 0 0;">
<option></option>
<option disabled="disabled">--- Landline ---</option>
<?php
$qp = mysql_query("SELECT * FROM plan_matrix WHERE type = 'Landline'");

while ($l_plan = mysql_fetch_assoc($qp))
{
	echo "<option>" . $l_plan["name"] . "</option>";
}
?>
<option>Addon</option>
<option>Duet</option>
<option disabled="disabled">--- Internet ---</option>
<?php
$qp = mysql_query("SELECT * FROM plan_matrix WHERE type = 'ADSL'");

while ($a_plan = mysql_fetch_assoc($qp))
{
	echo "<option>" . $a_plan["name"] . "</option>";
}
?>
<option disabled="disabled">--- Bundle ---</option>
<?php
$qp = mysql_query("SELECT * FROM plan_matrix WHERE type = 'Bundle'");

while ($b_plan = mysql_fetch_assoc($qp))
{
	echo "<option>" . $b_plan["name"] . "</option>";
}
?>
</select></td>
</tr>
</table>
</div>

<div style="display:none;">
<img src="../images/back_hover_btn.png" /><img src="../images/next_hover_btn.png" /><img src="../images/add_package_btn_hover.png" /><img src="../images/submit_form_btn_hover.png" /><img src="../images/cancel_btn_hover.png" /><img src="../images/loading.gif"><img src="../images/dd_btn_hover.png">
</div>

<?php
//declare variables
$id = $_GET['id'];
$alias = $_GET['alias'];
$campaign = $_GET['campaign'];
$campaign_check = "";
$website = "";
$number = "";
$plan = $_GET['plan'];
$page = $_GET['page'];
$date = date('jS \of F Y');

include "source/convert.php";
include "source/input.php";
include "source/questions.php";

?>

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

$back = '<input type="button" onClick="Back()" style="display: none;" id="Btn_Back" class="back" />';

$cancel_btn = '</td><td width="33.33%" align="center"><input type="button" id="Btn_Cancel" onClick="parent.Cancel()" class="cancel" /><img src="../images/loading.gif" id="image_load" style="display:none;"></td>';

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
	echo "Loading...";
}

?>
</body>
</html>