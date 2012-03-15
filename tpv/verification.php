<?php
include "../auth/iprestrict.php";
$id = $_GET["id"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: TPV :: Verification</title>
<link rel="shortcut icon" href="../images/vericon.ico">
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
.loadscript
{
	background-image:url('../images/load_script_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
	margin-right:10px;
}

.loadscript:hover
{
	background-image:url('../images/load_script_btn_hover.png');
	cursor:pointer;
}

.cancelform
{
	background-image:url('../images/cancel_form_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
	margin-right:10px;
}

.cancelform:hover
{
	background-image:url('../images/cancel_form_btn_hover.png');
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

.notes
{
	background-image:url('../images/notes_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
	margin-right:10px;
}

.notes:hover
{
	background-image:url('../images/notes_btn_hover.png');
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
<script> //init form
function Get_Sale()
{
	var id = $( "#id" );
	
	$.get("verification_submit.php?method=get", { id: id.val() },
	function(data) {
	   
	   if (data == "valid")
	   {
		   window.location = "verification.php?id=" + id.val();
	   }
	   else
	   {
			$( ".error" ).html(data);
	   }
	});
}
</script>
<script> //add packages
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var id = "<?php echo $id; ?>",
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
					$.get("verification_submit.php?method=add", { id: id, cli: cli.val(), plan: plan.val() },
					function(data) {
						if (data == "added")
						{
							$( "#dialog-form" ).dialog( "close" );
							window.location = "verification.php?id=" + id;
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
	$( "#dialog:ui-dialog4" ).dialog( "destroy" );
	
	var id = "<?php echo $id; ?>",
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

	$( "#dialog-form4" ).dialog({
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
					$.get("verification_submit.php?method=edit", { id: id, cli: cli.val(), plan: plan.val(), cli2: cli2.val() },
					function(data) {
						if (data == "editted")
						{
							$( "#dialog-form4" ).dialog( "close" );
							window.location = "verification.php?id=" + id;
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
	$( "#dialog-form4" ).dialog( "open" );
}
</script>
<script> //delete packages
function Delete_Package(cli)
{
	var id = "<?php echo $id; ?>",
		cli = cli;
	
	$.get("verification_submit.php?method=delete", { id: id, cli: cli},
	function(data) {
		if (data == "deleted")
		{
			window.location = "verification.php?id=" + id;
		}
	});
}
</script>
<script> //load script
function LoadScript()
{
	$( "#sale_details_edit" ).css("display","none");
	$( ".type" ).html($( "#type" ).val());
	$( "#sale_details").removeAttr("style");
	$( "#tpv_notes" ).css("display","none");
	$( "#prev_attempt" ).css("display","none");
	$( "#selected_packages" ).css("display","none");
	
	var campaign = $( ".campaign" ).html() + " " + $( ".type" ).html(),
		plan = $( "#plan" ),
		alias = "<?php echo $ac["alias"]; ?>",
		id = "<?php echo $id; ?>",
		l = "../script/script_tpv.php?campaign=" + campaign + "&plan=" + plan.val() + "&alias=" + alias + "&id=" + id + "&page=1";
	document.getElementById("script").src = l;
	
	if ( $( "#type" ).val() != "<?php echo $data["type"]; ?>" )
	{
		var type = $( "#type" );
		
		$.get("verification_submit.php?method=update_type", { id: id, type: type.val() }, function(data) { });
	}
	
	$( "#verification_script" ).css("display","table-row");
}
</script>
<script> //cancel button
$(function() {
$( "#dialog:ui-dialog2" ).dialog( "destroy" );

var id = "<?php echo $id; ?>",
	verifier = "<?php echo $ac["user"]; ?>",
	lead_id = $( "#lead_id" ),
	status = $( "#status" ),
	note = $( "#cancel_note" ),
	tips = $( ".error2" );

function updateTips( t ) {
	tips
		.text( t )
		.addClass( "ui-state-highlight" );
	setTimeout(function() {
		tips.removeClass( "ui-state-highlight", 1500 );
	}, 500 );
}

$( "#dialog-form2" ).dialog({
	autoOpen: false,
	height: 250,
	width: 425,
	modal: true,
	resizable: false,
	draggable: false,
	buttons: {
		"Submit": function() {
			if (status.val() == "")
			{
				updateTips("Select the Status!");
			}
			else if (note.val() == "")
			{
				updateTips("Enter a note!");
			}
			else
			{
				$.get("verification_submit.php?method=cancel", { id: id, verifier: verifier, lead_id: lead_id.val(), status: status.val(), note: note.val() },
				function(data) {
					$( "#dialog-form2" ).dialog( "close" );
					window.location = "verification.php";
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

function Cancel()
{
	$( "#dialog-form2" ).dialog( "open" );
}
</script>
<script> //notes button
$(function() {
$( "#dialog:ui-dialog3" ).dialog( "destroy" );

$( "#dialog-form3" ).dialog({
	autoOpen: false,
	height: 200,
	width: 425,
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

function Notes()
{
	$( "#dialog-form3" ).dialog( "open" );
}
</script>
<script> //submit error
$(function() {
	$( "#dialog:ui-dialog_submit" ).dialog( "destroy" );

	$( "#dialog-form_submit" ).dialog({
		autoOpen: false,
		width:300,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Close": function() {
				$( this ).dialog( "close" );
			}
		},
	});
});

function Submit_Error(data)
{
	$( ".submit_error" ).html(data);
	$( "#dialog-form_submit" ).dialog( "open" );
}
</script>
<script> //submit button
function Submit()
{
	var id = "<?php echo $id; ?>",
	verifier = "<?php echo $ac["user"]; ?>",
	lead_id = $( "#lead_id" ),
	note = $( "#notes" );
	
	$.get("verification_submit.php?method=submit", { id: id, verifier: verifier, lead_id: lead_id.val(), note: note.val() },
	function(data) {
		window.location = "verification.php";
	});
}
</script>
<script> //physical address
$(function() {
	$( "#dialog:ui-dialog6" ).dialog( "destroy" );
	
	var tips = $( ".error5" );
	
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#dialog-confirm6" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width: 450,
		height:370,
		modal: true,
		buttons: {
			"Select": function() {
				if ($("#physical_address_method:checked").val() == "gnaf")
				{
					if ($( "#address_code" ).val() == undefined || $( "#address_code" ).val() == "")
					{
						updateTips("You must select a valid address!");
					}
					else
					{
						$.get("../source/gnafGet.php?type=display&gnaf_id=" + $( "#address_code" ).val(), {  },
							function(data) {
								document.getElementById('script').contentWindow.Physical_Display();
							});
						document.getElementById('script').contentWindow.Physical_ID($( "#address_code" ).val());
						$( this ).dialog( "close" );
					}
				}
				else
				{
					if ($( "#m_street" ).val() == undefined || $( "#m_street" ).val() == "")
					{
						updateTips("You must enter a valid address!");
					}
					else
					{
						$.get("../source/gnafGet.php?type=manual&postcode=" + $( "#m_postcode" ).val() + "&suburb=" + $( "#m_suburb" ).val() + "&street=" + $( "#m_street" ).val(), {  },
							function(data) {
								document.getElementById('script').contentWindow.Physical_ID(data);
								$.get("../source/gnafGet.php?type=manualdisplay&id=" + data, {  },
									function(data2) {
										document.getElementById('script').contentWindow.Physical_Display();
									});
							});
						$( this ).dialog( "close" );
					}
				}
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

function Postcode_Physical()
{
	$("#suburb").html("<option>--- Loading ---</option>");
	$( "#suburb" ).load("../source/gnafGet.php?type=suburb&postcode=" + $('#postcode').val());
	$( "#suburb" ).removeAttr('disabled');
}

function Postcode_Physical_M()
{
	$("#m_suburb").html("<option>--- Loading ---</option>");
	$( "#m_suburb" ).load("../source/gnafGet.php?type=suburb&postcode=" + $('#m_postcode').val());
	$( "#m_suburb" ).removeAttr('disabled');
}

$(function() {
	$( "#street" ).autocomplete({
		source: function(request, response) {
        $.ajax({
          url: "../source/gnafGet.php",
               dataType: "json",
          data: {
			  type : "street",
			  postcode : $('#postcode').val(),
			  suburb : $('#suburb').val(),
  			  term : request.term
          },
          success: function(data) {
            response(data);
          }
        });
      },
		minLength: 2,
		select: function (event, ui) {
			$( "#street_type" ).val("--- Loading ---");
			var street_link = "../source/gnafGet.php?type=street_type&postcode=" + $('#postcode').val() + "&suburb=" + $('#suburb').val() + "&street=" + ui.item.value;
			$( "#street_type" ).load(street_link.replace(/ /g,"_"));
			$( "#street_type" ).removeAttr('disabled');
		}
	});
});

function Check_Address_Physical()
{
	var unit = $( "#unit" ),
		number = $( "#number" ),
		street_name = $( "#street" ),
		street_type = $( "#street_type" ),
		suburb = $( "#suburb" ),
		postcode = $( "#postcode" );
		
		var check_link = "../source/gnafGet.php?type=check&postcode=" + postcode.val() + "&suburb=" + suburb.val() + "&street=" + street_name.val() + "&street_type=" + street_type.val() + "&number=" + number.val() + "&unit=" + unit.val();
		$( "#results" ).load(check_link.replace(/ /g,"_"));
		$( ".results" ).removeAttr('style');
		$( "#results" ).removeAttr('style');
}

function Physical()
{
	$( "#dialog-confirm6" ).dialog( "open" );
}
</script>
<script> //postal address
$(function() {
	$( "#dialog:ui-dialog7" ).dialog( "destroy" );
	
	var tips = $( ".error6" );
	
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#dialog-confirm7" ).dialog({


		autoOpen: false,
		resizable: false,
		draggable: false,
		width: 450,
		height:370,
		modal: true,
		buttons: {
			"Select": function() {
				if ($("#postal_address_method:checked").val() == "gnaf")
				{
					if ($( "#address_code_p" ).val() == undefined || $( "#address_code_p" ).val() == "")
					{
						updateTips("You must select a valid address!");
					}
					else
					{
						$.get("../source/gnafGet.php?type=display&gnaf_id=" + $( "#address_code_p" ).val(), {  },
							function(data) {
								document.getElementById('script').contentWindow.Postal_Display();
							});
						document.getElementById('script').contentWindow.Postal_ID($( "#address_code_p" ).val());
						$( this ).dialog( "close" );
					}
				}
				else
				{
					if ($( "#m_street_p" ).val() == undefined || $( "#m_street_p" ).val() == "")
					{
						updateTips("You must enter a valid address!");
					}
					else
					{
						$.get("../source/gnafGet.php?type=manual&postcode=" + $( "#m_postcode_p" ).val() + "&suburb=" + $( "#m_suburb_p" ).val() + "&street=" + $( "#m_street_p" ).val(), {  },
							function(data) {
								document.getElementById('script').contentWindow.Postal_ID(data);
								$.get("../source/gnafGet.php?type=manualdisplay&id=" + data, {  },
									function(data2) {
										document.getElementById('script').contentWindow.Postal_Display();
									});
							});
						$( this ).dialog( "close" );
					}
				}
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

function Postcode_Postal()
{
	$("#suburb_p").html("<option>--- Loading ---</option>");
	$( "#suburb_p" ).load("../source/gnafGet.php?type=suburb&postcode=" + $('#postcode_p').val());
	$( "#suburb_p" ).removeAttr('disabled');
}

function Postcode_Postal_M()
{
	$("#m_suburb_p").html("<option>--- Loading ---</option>");
	$( "#m_suburb_p" ).load("../source/gnafGet.php?type=suburb&postcode=" + $('#m_postcode_p').val());
	$( "#m_suburb_p" ).removeAttr('disabled');
}

$(function() {
	$( "#street_p" ).autocomplete({
		source: function(request, response) {
        $.ajax({
          url: "../source/gnafGet.php",
               dataType: "json",
          data: {
			  type : "street",
			  postcode : $('#postcode_p').val(),
			  suburb : $('#suburb_p').val(),
  			  term : request.term
          },
          success: function(data) {
            response(data);
          }
        });
      },
		minLength: 2,
		select: function (event, ui) {
			$( "#street_type_p" ).val("--- Loading ---");
			var street_link = "../source/gnafGet.php?type=street_type&postcode=" + $('#postcode_p').val() + "&suburb=" + $('#suburb_p').val() + "&street=" + ui.item.value;
			$( "#street_type_p" ).load(street_link.replace(/ /g,"_"));
			$( "#street_type_p" ).removeAttr('disabled');
		}
	});
});

function Check_Address_Postal()
{
	var unit = $( "#unit_p" ),
		number = $( "#number_p" ),
		street_name = $( "#street_p" ),
		street_type = $( "#street_type_p" ),
		suburb = $( "#suburb_p" ),
		postcode = $( "#postcode_p" );
		
		var check_link = "../source/gnafGet.php?type=check2&postcode=" + postcode.val() + "&suburb=" + suburb.val() + "&street=" + street_name.val() + "&street_type=" + street_type.val() + "&number=" + number.val() + "&unit=" + unit.val();
		$( "#results_p" ).load(check_link.replace(/ /g,"_"));
		$( ".results_p" ).removeAttr('style');
		$( "#results_p" ).removeAttr('style');
}

function Postal()
{
	$( "#dialog-confirm7" ).dialog( "open" );
}
</script>
<script>
function GNAF()
{
	$( "#gnaf" ).removeAttr('style');
	$( "#manual" ).attr('style','display:none;');
}

function Manual()
{
	$( "#manual" ).removeAttr('style');
	$( "#gnaf" ).attr('style','display:none;');
}

function GNAF_P()
{
	$( "#gnaf_p" ).removeAttr('style');
	$( "#manual_p" ).attr('style','display:none;');
}

function Manual_P()
{
	$( "#manual_p" ).removeAttr('style');
	$( "#gnaf_p" ).attr('style','display:none;');
}
</script>
<script> //direct debit
$(function() {
	$( "#dialog:ui-dialog5" ).dialog( "destroy" );

	$( "#dialog-form5" ).dialog({
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
	$( "#dialog-form5" ).dialog( "open" );
}
</script>
</head>

<body>
<div style="display:none;">
<img src="../images/getsale_btn_hover.png" /><img src="../images/load_script_btn_hover.png" /><img src="../images/cancel_form_btn_hover.png" /><img src="../images/add_package_btn_hover.png" /><img src="../images/notes_btn_hover.png" />
</div>
<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/tpv_menu.php";
?>

<div id="text" class="demo">

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

<div id="dialog-form4" title="Edit Package">
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

<div id="dialog-form2" title="Cancel Verification">
<p class="error2">All fields are required</p>
<table>
<tr>
<td width="50px">Status </td>
<td><select id="status" style="width:120px; height:auto; padding:0px; margin-left:0;">
<option></option>
<option>Declined</option>
<option>Line Issue</option>
<option>Hold</option>
</select></td>
</tr>
<tr>
<td width="50px">Note </td>
<td><textarea id="cancel_note" rows="5" style="width:350px; resize:none;"></textarea></td>
</tr>
</table>
</div>

<div id="dialog-form3" title="Notes">
<table>
<tr>
<td width="50px">Notes </td>
<td><textarea id="notes" rows="5" style="width:350px; resize:none;"></textarea></td>
</tr>
</table>
</div>

<div id="dialog-form5" title="Direct Debit">
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

<div id="dialog-confirm6" title="Physical Address">
<form>
<table style="border:1px solid black;">
<tr>
<td width="60px"><input type="radio" name="address_method_p" id="physical_address_method" onclick="GNAF()" value="gnaf" checked="checked" style="height:auto;" /><label for="gnaf"> GNAF</label></td>
<td width="60px"><input type="radio" name="address_method_p" id="physical_address_method" onclick="Manual()" value="manual" style="height:auto;" /><label for="manual"> Manual</label></td>
</tr>
</table>
</form>
<br />
<div id="manual" style="display:none;">
<p class="error5">Enter the customer's address.</p>
<table>
<tr>
<td>Postcode</td>
<td>Suburb</td>
</tr>
<tr>
<td><input type="text" id="m_postcode" onchange="Postcode_Physical_M()" size="5" style="height:auto; padding-left:3px;" /></td>
<td><select id="m_suburb" onchange='$( "#m_street" ).removeAttr("disabled");' disabled="disabled" style="min-width:186px;height:auto; padding:0px; margin:0;">
<option>--- Enter a Postcode ---</option>
</select></td>
</tr>
<tr>
<td colspan="2">Street Address</td>
</tr>
<tr>
<td colspan="2"><input type="text" size="36" id="m_street" style="height:auto; padding-left:3px;" disabled="disabled" value="" /></td>
</tr>
</table>
</div>
<div id="gnaf">
<p class="error5">Start entering the customer's address to search the GNAF database.</p>
<table>
<tr>
<td colspan="2">Postcode</td>
<td colspan="3">Suburb</td>
<td rowspan="2" align="right" valign="bottom"><input type="button" onclick="Check_Address_Physical()" id="check_btn" value="" class="search" style="display:;" /></td>
</tr>
<tr>
<td colspan="2"><input type="text" id="postcode" onchange="Postcode_Physical()" size="5" style="height:auto; padding-left:3px;" /></td>
<td colspan="3"><select id="suburb" onchange='$( "#street" ).removeAttr("disabled"); $( "#unit" ).removeAttr("disabled"); $( "#number" ).removeAttr("disabled");' disabled="disabled" style="min-width:186px;height:auto; padding:0px; margin:0;">
<option>--- Enter a Postcode ---</option>
</select></td>
</tr>
<tr>
<td>Unit</td>
<td></td>
<td>No</td>
<td>Street Name</td>
<td>Street Type</td>
<td></td>
</tr>
<tr>
<td><input type="text" id="unit" size="5" style="height:auto; padding-left:3px;" disabled="disabled" /></td>
<td align="center">/</td>
<td><input type="text" id="number" size="5" style="height:auto; padding-left:3px;" disabled="disabled" /></td>
<td><input type="text" id="street" size="20" style="height:auto; padding-left:3px;" disabled="disabled" /></td>
<td colspan="2"><select id="street_type" disabled="disabled" onchange='$( "#check_btn" ).removeAttr("style");' style="min-width:155px;height:auto; padding:0px; margin:0;">
<option>--- Enter a Street Name ---</option>
</select></td>
</tr>
<tr>
<td colspan="6"><b class="results" style="display:none;"><u>Results:</u></b></td>
</tr>
<tr>
<td colspan="6"><div id="results" style="display:none;">
</div></td>
</tr>
</table>
</div>
</div>

<div id="dialog-confirm7" title="Postal Address">
<form>
<table style="border:1px solid black;">
<tr>
<td width="60px"><input type="radio" name="address_method_m" id="postal_address_method" onclick="GNAF_P()" value="gnaf" checked="checked" style="height:auto;" /><label for="gnaf"> GNAF</label></td>
<td width="60px"><input type="radio" name="address_method_m" id="postal_address_method" onclick="Manual_P()" value="manual" style="height:auto;" /><label for="manual"> Manual</label></td>
</tr>
</table>
</form>
<br />
<div id="manual_p" style="display:none;">
<p class="error6">Enter the customer's address.</p>
<table>
<tr>
<td>Postcode</td>
<td>Suburb</td>
</tr>
<tr>
<td><input type="text" id="m_postcode_p" onchange="Postcode_Postal_M()" size="5" style="height:auto; padding-left:3px;" /></td>
<td><select id="m_suburb_p" onchange='$( "#m_street_p" ).removeAttr("disabled");' disabled="disabled" style="min-width:186px;height:auto; padding:0px; margin:0;">
<option>--- Enter a Postcode ---</option>
</select></td>
</tr>
<tr>
<td colspan="2">Street Address</td>
</tr>
<tr>
<td colspan="2"><input type="text" size="36" id="m_street_p" style="height:auto; padding-left:3px;" disabled="disabled" value="" /></td>
</tr>
</table>
</div>
<div id="gnaf_p">
<p class="error6">Start entering the customer's address to search the GNAF database.</p>
<table>
<tr>
<td colspan="2">Postcode</td>
<td colspan="3">Suburb</td>
<td rowspan="2" align="right" valign="bottom"><input type="button" onclick="Check_Address_Postal()" id="check_btn_p" value="" class="search" style="display:;" /></td>
</tr>
<tr>
<td colspan="2"><input type="text" id="postcode_p" onchange="Postcode_Postal()" size="5" style="height:auto; padding-left:3px;" /></td>
<td colspan="3"><select id="suburb_p" onchange='$( "#street_p" ).removeAttr("disabled"); $( "#unit_p" ).removeAttr("disabled"); $( "#number_p" ).removeAttr("disabled");' disabled="disabled" style="min-width:186px;height:auto; padding:0px; margin:0;">
<option>--- Enter a Postcode ---</option>
</select></td>
</tr>
<tr>
<td>Unit</td>
<td></td>
<td>No</td>
<td>Street Name</td>
<td>Street Type</td>
<td></td>
</tr>
<tr>
<td><input type="text" id="unit_p" size="5" style="height:auto; padding-left:3px;" disabled="disabled" /></td>
<td align="center">/</td>
<td><input type="text" id="number_p" size="5" style="height:auto; padding-left:3px;" disabled="disabled" /></td>
<td><input type="text" id="street_p" size="20" style="height:auto; padding-left:3px;" disabled="disabled" /></td>
<td colspan="2"><select id="street_type_p" disabled="disabled" onchange='$( "#check_btn_p" ).removeAttr("style");' style="min-width:155px;height:auto; padding:0px; margin:0;">
<option>--- Enter a Street Name ---</option>
</select></td>
</tr>
<tr>
<td colspan="6"><b class="results_p" style="display:none;"><u>Results:</u></b></td>
</tr>
<tr>
<td colspan="6"><div id="results_p" style="display:none;">
</div></td>
</tr>
</table>
</div>
</div>

<div id="dialog-form_submit" title="Error!">
<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><span class="submit_error"></span></p>
</div>

<?php
if ($id == "")
{
?>
<div id="get_sale_table" style="margin-top:75px;">
<form onsubmit="event.preventDefault()">
    <table>
        <tr>
            <td><p>Enter the Customer's Sale ID</p></td>
            <td><input type="text" name="id" id="id" size="25"/></td>
            <td><input type="submit" class="get_sale_btn" onclick="Get_Sale()" value="" /></td>
        </tr>
    </table>
</form>
    <center><p class="error" style="color:#C00;"><?php if($_GET["er"] == "sap") { echo "Sale already Approved!"; } ?></p></center>
</div>
<?php
}
else
{
	$q = mysql_query("SELECT * FROM sales_customers WHERE id = '" . mysql_escape_string($id) . "'") or die (mysql_error());
	$data = mysql_fetch_assoc($q);
	
	$q2 = mysql_query("SELECT alias FROM auth WHERE user = '$data[agent]'") or die (mysql_error());
	$salias = mysql_fetch_row($q2);
	
	if ($data["status"] == "Approved")
	{
		$link = "../tpv/verification.php?er=sap";
		echo "<script>window.location = '$link';</script>";
		exit;
	}

	switch ($data["type"])
	{
		case "Business":
		$bus="selected";
		break;
		case "Residential":
		$res="selected";
		break;
	}
	
	if ($data["status"] == "Line Issue")
	{
		$status_text = "line_issue";
	}
	else
	{
		$status_text = strtolower($data["status"]);
	}
?>
<input type="hidden" id="lead_id" value="<?php echo $data["lead_id"]; ?>" />
<table border="0" width="100%">
<tr>
<td width="367px" valign="top" id="sale_details_edit">
<table border="0" width="100%">
<tr>
<td colspan="2"><img src="../images/sale_details_header.png" width="90" height="15" /><img src="../images/<?php echo $status_text; ?>_header.png" width="100" height="15" style="float:right; margin-right:80px;" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="80%" height="9" alt="line" /></td>
</tr>
<tr>
<td width="85px">Sale ID </td>
<td><b><?php echo $data["id"]; ?></b></td>
</tr>
<tr>
<td>Sale Agent </td>
<td><b><?php echo $data["agent"] . " (" . $salias[0] . ")"; ?></b></td>
</tr>
<tr>
<td>Centre </td>
<td><b><?php echo $data["centre"]; ?></b></td>
</tr>
<tr>
<td>Campaign </td>
<td><b class="campaign"><?php echo $data["campaign"]; ?></b></td>
</tr>
<tr>
<td>Type </td>
<td><select id="type" style="margin:0; width:100px; height:20px; padding:0; font-family:Tahoma, Geneva, sans-serif; font-size:11px;">
<option <?php echo $bus; ?>>Business</option>
<option <?php echo $res; ?>>Residential</option>
</select></td>
</tr>
</table>
</td>
<td width="367px" valign="top" id="sale_details" style="display:none;">
<table border="0" width="100%">
<tr>
<td colspan="2"><img src="../images/sale_details_header.png" width="90" height="15" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="80%" height="9" alt="line" /></td>
</tr>
<tr>
<td width="85px">Sale ID </td>
<td><b><?php echo $data["id"]; ?></b></td>
</tr>
<tr>
<td>Sale Agent </td>
<td><b><?php echo $data["agent"] . " (" . $salias[0] . ")"; ?></b></td>
</tr>
<tr>
<td>Centre </td>
<td><b><?php echo $data["centre"]; ?></b></td>
</tr>
<tr>
<td>Campaign </td>
<td><b class="campaign"><?php echo $data["campaign"]; ?></b></td>
</tr>
<tr>
<td>Type </td>
<td><b class="type"></b></td>
</tr>
</table>
</td>
<td width="367px" valign="top">
<table border="0" width="100%">
<tr>
<td colspan="2"><img src="../images/customer_details_header.png" width="128" height="15" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="80%" height="9" alt="line" /></td>
</tr>
<tr>
<td width="85px">Title </td>
<td><b><?php echo $data["title"]; ?></b></td>
</tr>
<tr>
<td>First Name </td>
<td><b><?php echo $data["firstname"]; ?></b></td>
</tr>
<tr>
<td>Middle Name </td>
<td><b><?php echo $data["middlename"]; ?></b></td>
</tr>
<tr>
<td>Last Name </td>
<td><b><?php echo $data["lastname"]; ?></b></td>
</tr>
</table>
</td>
</tr>
<tr id="tpv_notes" style="display:table-row;">
<td colspan="2">
<table border="0" width="100%">
<tr>
<td><br /><img src="../images/tpv_notes_header.png" width="80" height="15" style="padding-left:3px;"/></td>
</tr>
<tr>
<td><img src="../images/line.png" width="100%" height="9" alt="line" /></td>
</tr>
<tr>
<td>
<div style="height:125px; width:99%; overflow:auto; border: 1px solid #eee;">
<?php
$q3 = mysql_query("SELECT * FROM tpv_notes WHERE id = '$id' ORDER BY timestamp DESC") or die (mysql_error());

echo "<table border='0' width='100%'>";
if (mysql_num_rows($q3) == 0)
{
	echo "<tr>";
	echo "<td>No Notes</td>";
	echo "</tr>";
}
else
{
	while ($tpv_notes = mysql_fetch_assoc($q3))
	{
		$q7 = mysql_query("SELECT * FROM auth WHERE user = '$tpv_notes[verifier]'") or die(mysql_error());
		$vname = mysql_fetch_assoc($q7);
		
		echo "<tr>";
		echo "<td>----- " . date("d/m/Y H:i:s", strtotime($tpv_notes["timestamp"])) . " - " . $vname["first"] . " " . $vname["last"] . " -----" . " (" . $tpv_notes["status"] . ")</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>" . $tpv_notes["note"] . "</td>";
		echo "</tr>";
	}
}
echo "</table>";
?>
</div>
</td>
</tr>
</table>
</td>
</tr>
<tr id="prev_attempt" style="display:table-row;">
<td colspan="2">
<table width="100%" border="0">
<tr>
<td width="55%" valign="top">
<table border="0" width="100%">
<tr>
<td><img src="../images/previous_attempt_header.png" width="140" height="15" style="padding-left:3px;"/></td>
</tr>
<tr>
<td><img src="../images/line.png" width="100%" height="9" alt="line" /></td>
</tr>
<tr>
<td>
<div style="height:100px; width:99%; overflow:auto; border: 1px solid #eee;">
<?php
$q4 = mysql_query("SELECT * FROM sales_packages WHERE sid = '$id'") or die (mysql_error());
while ($package = mysql_fetch_assoc($q4))
{
	$q5 = mysql_query("SELECT sid FROM sales_packages WHERE cli = '$package[cli]' AND sid != '$id'") or die (mysql_error());
	echo "<table border='0' width='100%'>";
	if (mysql_num_rows($q5) == 0)
	{
		if ($display1 == 0)
		{
			echo "<tr>";
			echo "<td>No Previous Sign Up Attempts</td>";
			echo "</tr>";
			$display1 = 1;
		}
	}
	else
	{
		while ($sid = mysql_fetch_row($q5))
		{
			$q6 = mysql_query("SELECT * FROM sales_customers WHERE id = '$sid[0]'") or die(mysql_error());
			$prev = mysql_fetch_assoc($q6);
			
            echo "<tr>";
			echo "<td><a onclick='View(\"$prev[id]\")' style='cursor:pointer; text-decoration:underline;'>" . $prev["id"] . "</a></td>";
            echo "<td>" . date("d/m/Y", strtotime($prev["timestamp"])) . "</td>";
			echo "<td>(" . $package["cli"] . ")</td>";
			echo "<td>" . $prev["centre"] . "</td>";
			echo "<td>" . $prev["status"] . "</td>";
            echo "</tr>";
		}
	}
	echo "</table>";
}
?>
</div>
</td>
</tr>
</table>
</td>
<td width="45%" valign="bottom">
<table border="0" width="100%">
<tr>
<td>
<img src="../images/verification_fill_bg.png" width="315" height="104" />
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<tr id="selected_packages" style="display:table-row;">
<td colspan="2">
<table border="0" width="100%">
<tr>
<td colspan="2"><img src="../images/selected_packages_header.png" width="134" height="15" style="padding-left:3px;"/></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" alt="line" /></td>
</tr>
<tr>
<td colspan="2">
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="99%" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th>CLI</th>
<th colspan="3">Plan</th>
</tr>
</thead>
<tbody id="packages">
<script>
var id = "<?php echo $_GET['id']; ?>";
$( "#packages" ).load('packages.php?id=' + id);
</script>
</tbody>
</table>
</div>
</td>
</tr>
<tr valign="bottom">
<td align="left"><input type="button" onclick="Add_Package()" class="addpackage" /></td>
<td align="right">
<?php
$q8 = mysql_query("SELECT * FROM sales_packages WHERE sid = '$id'") or die (mysql_error());
if (mysql_num_rows($q8) == 1)
{
	$pack = mysql_fetch_assoc($q8);
?>
<input type="hidden" id="plan" value="<?php echo $pack["plan"] ?>" />
<?php
}
else
{
?>
<select id="plan" style="margin:0 1px 10px 0; width:200px; height:20px; padding:1px 0 0; font-family:Tahoma, Geneva, sans-serif; font-size:11px;">
<?php
while ($package2 = mysql_fetch_assoc($q8))
{
	echo "<option>" . $package2["plan"] . "</option>";
}
?>
</select>
<?php
}
?>
<input type="button" onclick="LoadScript()" class="loadscript" />
<input type="button" onclick="Cancel()" class="cancelform" />
</td>
</tr>
</table>
</td>
</tr>
<tr id="verification_script" style="display:none;">
<td colspan="2">
<table border="0" width="100%">
<tr valign="bottom">
<td align="left"><br /><img src="../images/verification_script_header2.png" width="140" height="15" style="padding-left:3px;"/></td>
<td align="right"><input type="button" onclick="Notes()" class="notes" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" alt="line" /></td>
</tr>
<tr>
<td colspan="2">
<div id="script_text" style="border:0; margin-top:0; padding:5px 5px 0; width:100%;">
<iframe src="../script/script_tpv.php" id="script" name="script" width="100%" height="380px" frameborder="0">
</iframe>
</td>
</tr>
</table>
</td>
</tr>
</table>
<?php
}
?>

</div>
</div> 
<?php
include "../source/footer.php";
?>
</body>
</html>