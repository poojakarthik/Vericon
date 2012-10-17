<?php
mysql_connect('localhost','vericon','18450be');

$q = mysql_query("SELECT user FROM vericon.currentuser WHERE hash = '" . $_COOKIE["hash"] . "'") or die(mysql_error());
$user = mysql_fetch_row($q);

$q1 = mysql_query("SELECT * FROM vericon.auth WHERE user = '$user[0]'") or die(mysql_error());
$ac = mysql_fetch_assoc($q1);

$id = $_GET["id"];
$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
$data = mysql_fetch_assoc($q);

$q1 = mysql_query("SELECT id FROM vericon.campaigns WHERE campaign = '" . mysql_real_escape_string($data["campaign"]) . "'") or die(mysql_error());
$c = mysql_fetch_row($q1);
$campaign_id = $c[0];

$contract_months = 0;
$p_i = 0;
$a_i = 0;
$b_i = 0;
$p_packages = array();
$a_packages = array();
$b_packages = array();

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
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
.ui-dialog_submit { padding: .3em; }
.ui-dialog2 { padding: .3em; }
.ui-dialog3 { padding: .3em; }
.ui-dialog4 { padding: .3em; }
.ui-state-highlight { padding: .3em; }
.validateTips2 { border: 1px solid transparent; padding: 0.3em; }
.validateTips3 { border: 1px solid transparent; padding: 0.3em; }
.validateTips4 { border: 1px solid transparent; padding: 0.3em; }
#physical_address_code { height:120px; margin:0px; overflow-y:auto; border:1px solid black; padding:3px; }
#postal_address_code  { height:120px; margin:0px; overflow-y:auto; border:1px solid black; padding:3px; }
.ui-dialog_physical { padding: .3em; }
.ui-dialog_physical_manual { padding: .3em; }
.ui-dialog_physical_confirm { padding: .3em; }
.ui-dialog_postal { padding: .3em; }
.ui-dialog_postal_manual { padding: .3em; }
.ui-dialog_postal_mailbox { padding: .3em; }
.ui-dialog_postal_confirm { padding: .3em; }
.ui-dialog_postal_confirm_switch { padding: .3em; }
.validateTipsPhysical { border: 1px solid transparent; padding: 0.3em; }
.validateTipsPhysicalManual { border: 1px solid transparent; padding: 0.3em; }
.validateTipsPostal { border: 1px solid transparent; padding: 0.3em; }
.validateTipsPostalManual { border: 1px solid transparent; padding: 0.3em; }
.validateTipsMB { border: 1px solid transparent; padding: 0.3em; }
.ui-autocomplete { max-height: 300px; overflow-y: auto; overflow-x: hidden; padding-right: 20px; }
.ui-autocomplete-loading { background: white url('../images/ajax-loader.gif') right center no-repeat; }
</style>
<script>
$(function() {
	$( "#sale_verifier_d" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "verification_submit.php",
				dataType: "json",
				data: {
					method : "verifier",
					centre: "<?php echo $ac["centre"]; ?>",
					term : request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 2,
		select: function (event, ui) {
			$( "#sale_verifier" ).val(ui.item.id);
		}
	});
});
</script>
<script> //add packages
$(function() {
	$( "#dialog:ui-dialog2" ).dialog( "destroy" );
	
	var tips = $( ".validateTips2" );

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
		height: 200,
		width: 275,
		modal: true,
		resizable: false,
		draggable: false,
		show: "blind",
		hide: "blind",
		buttons: {
			"Add Package": function() {
				var id = $( "#id" ),
					cli = $( "#cli" ),
					plan = $( "#plan" );
				
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
					$.get("verification_submit.php?method=add_au", { id: id.val(), cli: cli.val(), plan: plan.val() },
					function(data) {
						if (data == "added")
						{
							$( "#packages" ).load('../tpv/packages_au.php?id=' + id.val());
							$( "#dialog-form2" ).dialog( "close" );
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

function Add_Package()
{
	$( "#cli" ).val("");
	$( "#plan" ).val("");
	$( ".validateTips2" ).text("All fields are required");
	$( "#dialog-form2" ).dialog( "open" );
}

function Plan_Dropdown()
{
	$( "#plan" ).val("");
	$( "#plan" ).load("../tpv/plans_au.php?id=" + $( "#id" ).val() + "&type=" + $( "#sale_type" ).val() + "&cli=" + $('#cli').val());
}
</script>
<script> //edit packages
$(function() {
	$( "#dialog:ui-dialog3" ).dialog( "destroy" );
	
	var tips = $( ".validateTips3" );

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
		show: "blind",
		hide: "blind",
		buttons: {
			"Edit Package": function() {
				var id = $( "#id" ),
					cli = $( "#edit_cli" ),
					plan = $( "#edit_plan" ),
					cli2 = $( "#original_edit_cli" );
				
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
					$.get("verification_submit.php?method=edit_au", { id: id.val(), cli: cli.val(), plan: plan.val(), cli2: cli2.val() },
					function(data) {
						if (data == "editted")
						{
							$( "#packages" ).load('../tpv/packages_au.php?id=' + id.val());
							$( "#dialog-form3" ).dialog( "close" );
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

function Edit_Package(cli,plan)
{
	$( "#edit_cli" ).val(cli);
	$( "#edit_plan" ).load("../tpv/plans_au.php?id=" + $( "#id" ).val() + "&type=" + $( "#sale_type" ).val() + "&cli=" + $('#edit_cli').val(),
	function() {
		$( "#edit_plan" ).val(plan);
	});
	$( "#original_edit_cli" ).val(cli);
	$( ".validateTips3" ).text("All fields are required");
	$( "#dialog-form3" ).dialog( "open" );
}

function Plan_Dropdown_Edit()
{
	$( "#edit_plan" ).load("../tpv/plans_au.php?id=" + $( "#id" ).val() + "&type=" + $( "#sale_type" ).val() + "&cli=" + $('#edit_cli').val());
}
</script>
<script> //delete packages
function Delete_Package(cli)
{
	var id = $( "#id" );
	
	$.get("verification_submit.php?method=delete", { id: id.val(), cli: cli},
	function(data) {
		if (data == "deleted")
		{
			$( "#packages" ).load('../tpv/packages_au.php?id=' + id.val());
		}
	});
}
</script>
<script> //submit error
$(function() {
	$( "#dialog:ui-dialog_submit" ).dialog( "destroy" );

	$( "#dialog-form_submit" ).dialog({
		autoOpen: false,
		width:250,
		height:100,
		modal: true,
		resizable: false,
		draggable: false,
		show: 'blind',
		hide: 'blind'
	});
});

function Submit_Error(data)
{
	$( ".submit_error" ).html(data);
	$( "#dialog-form_submit" ).dialog( "open" );
}
</script>
<script> //cancel
$(function() {
	$( "#dialog:ui-dialog4" ).dialog( "destroy" );
	
	var tips = $( ".validateTips4" );
	
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
		height: 250,
		width: 425,
		modal: true,
		resizable: false,
		draggable: false,
		show: "blind",
		hide: "blind",
		buttons: {
			"Submit": function() {
				var id = $( "#id" ),
					verifier = "<?php echo $ac["user"]; ?>",
					status = $( "#status" ),
					note = $( "#cancel_note" );
				
				$.get("verification_submit.php?method=cancel", { id: id.val(), verifier: verifier, status: status.val(), note: note.val() }, function(data) {
					if (data == "done")
					{
						$( "#dialog-form4" ).dialog( "close" );
						$( "#display" ).hide('blind', '', 'slow', function() {
							window.location = "verification.php";
						});
					}
					else
					{
						updateTips(data);
					}
				});
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

function Cancel()
{
	$( ".validateTips4" ).text("All fields are required");
	$( "#dialog-form4" ).dialog( "open" );
}
</script>
<script> //submit
function Submit()
{
	var id = $( "#id" ),
		verifier = $( "#sale_verifier" ),
		dialled = $( "#dialled" ),
		note = $( "#notes" );
	
	$.get("verification_submit.php?method=submit_au", { id: id.val(), verifier: verifier.val(), dialled: dialled.val(), note: note.val() },
	function(data) {
		if (data == "done")
		{
			$( "#display" ).hide('blind', '', 'slow', function() {
				window.location = "verification.php";
			});
		}
		else
		{
			Submit_Error(data);
		}
	});
}
</script>
<script>
function Back()
{
	var id = $( "#id" ),
		plan = $( "#script_plan" ),
		user = "<?php echo $ac["user"]; ?>",
		page = parseInt($( "#page" ).val()) - 1;
	
	$( "#Btn_Back").attr("style", "display:none;");
	$("#page" ).val(page);
	$( "#script_text" ).load("../script/script.php?method=New&in=1&id=" + id.val() + "&user=" + user + "&plan=" + plan.val() + "&page=" + page);
}

function N()
{
	var id = $( "#id" ),
		plan = $( "#script_plan" ),
		user = "<?php echo $ac["user"]; ?>",
		page = parseInt($( "#page" ).val()) + 1;
	
	$( "#Btn_Next").attr("style", "display:none;");
	$("#page" ).val(page);
	$( "#script_text" ).load("../script/script.php?method=New&in=1&id=" + id.val() + "&user=" + user + "&plan=" + plan.val() + "&page=" + page);
}

function Next(id,action)
{
	if (action == "bus_info")
	{
		var abn = $( "#abn" ),
			abn_status = $( ".abn_status" ),
			position = $( "#position" );
			
		$.get("../script/submit.php", { id: id, action: action, abn: abn.val(), abn_status: abn_status.html(), position: position.val() },
			function(data) {
				if (data == "submitted")
				{
					N();
				}
				else
				{
					Submit_Error(data);
				}
			});
	}
	else if (action == "name")
	{
		var title = $( "#title" ),
			first = $( "#first" ),
			middle = $( "#middle" ),
			last = $( "#last" );
			
		$.get("../script/submit.php", { id: id, action: action, title: title.val(), first: first.val(), middle: middle.val(), last: last.val() },
			function(data) {
				if (data == "submitted")
				{
					N();
				}
				else
				{
					Submit_Error(data);
				}
			});
	}
	else if (action == "dob")
	{
		var dob = $( "#datepicker" );
			
		$.get("../script/submit.php", { id: id, action: action, dob: dob.val() },
			function(data) {
				if (data == "submitted")
				{
					N();
				}
				else
				{
					Submit_Error(data);
				}
			});
	}
	else if (action == "id_info")
	{
		var id_type = $( "#id_type" ),
			id_num = $( "#id_num" );
			
		$.get("../script/submit.php", { id: id, action: action, id_type: id_type.val(), id_num: id_num.val() },
			function(data) {
				if (data == "submitted")
				{
					N();
				}
				else
				{
					Submit_Error(data);
				}
			});
	}
	else if (action == "physical")
	{
		var physical = $( "#physical" );
			
		$.get("../script/submit.php", { id: id, action: action, physical: physical.val() },
			function(data) {
				if (data == "submitted")
				{
					N();
				}
				else
				{
					Submit_Error(data);
				}
			});
	}
	else if (action == "postal")
	{
		var postal = $( "#postal" ).val();
		
		if ($('#postal_same').attr('checked'))
		{
			postal = "same";
		}
		
		$.get("../script/submit.php", { id: id, action: action, postal: postal },
			function(data) {
				if (data == "submitted")
				{
					N();
				}
				else
				{
					Submit_Error(data);
				}
			});
	}
	else if (action == "mobile")
	{
		var mobile = $( "#mobile" );
			
		$.get("../script/submit.php", { id: id, action: action, mobile: mobile.val() },
			function(data) {
				if (data == "submitted")
				{
					N();
				}
				else
				{
					Submit_Error(data);
				}
			});
	}
	else if (action == "email")
	{
		var email = $( "#email" ),
			promotions = $('input[name=promotions]:checked');
			
		$.get("../script/submit.php", { id: id, action: action, email: email.val(), promotions: promotions.val() },
			function(data) {
				if (data == "submitted")
				{
					N();
				}
				else
				{
					Submit_Error(data);
				}
			});
	}
	else if (action == "email2")
	{
		var email = $( "#email2" ),
			promotions = $('input[name=promotions]:checked');
		
		$.get("../script/submit.php", { id: id, action: "email2", email: email.val(), promotions: promotions.val() },
			function(data) {
				if (data == "submitted")
				{
					N();
				}
				else
				{
					Submit_Error(data);
				}
			});
	}
	else if (action == "best_buddy")
	{
		var best_buddy = $( "#best_buddy" );
			
		$.get("../script/submit.php", { id: id, action: action, best_buddy: best_buddy.val() },
			function(data) {
				if (data == "submitted")
				{
					N();
				}
				else
				{
					Submit_Error(data);
				}
			});
	}
	else
	{
		N();
	}
}
</script>
<script> //direct debit
$(function() {
	$( "#dialog:ui-dialog5" ).dialog( "destroy" );

	$( "#dialog-form5" ).dialog({
		autoOpen: false,
		height: 465,
		width: 575,
		modal: true,
		resizable: false,
		draggable: false,
		show: 'blind',
		hide: 'blind'
	});
});

function DD(campaign)
{
	$( ".dd_campaign" ).html(campaign);
	$( "#dialog-form5" ).dialog( "open" );
}
</script>
<!--#########################################################-->
<!--##													   ##-->
<!--##					PHYSICAL ADDRESS				   ##-->
<!--##													   ##-->
<!--#########################################################-->
<script>
$(function() {
	$( "#dialog:ui-dialog_physical_confirm" ).dialog( "destroy" );

	$( "#dialog-confirm_physical2" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width: 400,
		height: 235,
		modal: true,
		buttons: {
			"Select": function() {
				if ($( "input[name=address_code]:checked" ).val() != undefined)
				{
					$.get("../source/gnafGet.php?type=display", { id: $( "input[name=address_code]:checked" ).val() },
						function(data) {
							var n = data.split("}");
							$( "#display_physical1" ).val(n[0]);
							$( "#display_physical2" ).val(n[1]);
							$( "#display_physical3" ).val(n[2]);
							$( "#display_physical4" ).val(n[3]);
						});
					$( "#physical" ).val($( "input[name=address_code]:checked" ).val());
					$( "#dialog-confirm_physical2" ).dialog( "close" );
					$( "#dialog-confirm_physical" ).dialog( "close" );
				}
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
</script>
<script>
$(function() {
	$( "#dialog:.ui-dialog_physical" ).dialog( "destroy" );
	
	var tips = $( ".validateTipsPhysical" );
	
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#dialog-confirm_physical" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width: 475,
		height:350,
		modal: true,
		show: "blind",
		hide: "blind",
		buttons: {
			"Verify Address": function() {
				var address_type = $('input[name=physical_type]:checked'),
					building_type = $( "#physical_building_type" ),
					building_number = $( "#physical_building_number" ),
					building_name = $( "#physical_building_name" ),
					street_number = $( "#physical_street_number" ),
					street_name = $( "#physical_street_name" ),
					street_type = $( "#physical_street_type" ),
					l_pid = $('#physical_locality_pid');
					
					$.get("../source/gnafGet.php?type=check", { address_type: address_type.val(), l_pid: l_pid.val(), building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data) {
						if (data == "valid")
						{
							$( "#physical_address_code" ).attr("style","display:none;");
							$( "#physical_manual_store" ).attr('style','display:none;');
							$( "#dialog-confirm_physical2" ).dialog( "open" );
							$( "#physical_search_div" ).html("<center><br><br><br><br><p><img src='../images/ajax-loader.gif'>&nbsp;&nbsp;&nbsp;&nbsp;Please wait. Verifying your address...</p></center>");
							$( "#physical_search_div" ).removeAttr('style');
							
							$.get("../source/gnafGet.php?type=search", { address_type: address_type.val(), l_pid: l_pid.val(), building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data) {
								if (data == 'No Results Found')
								{
									$( "#physical_search_div" ).html("<center><br><br><br><br><p><img src='../images/ajax-loader.gif'>&nbsp;&nbsp;&nbsp;&nbsp;Exact match not found. Looking up similar...</p></center>");
									
									$.get("../source/gnafGet.php?type=format", { l_pid: l_pid.val(), building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data0) {
										/// CLOUD GEOCODER API GET
										$.get("../source/gnafXML.php", { input: data0 }, function(data2) {
											$.get("../source/gnafGet.php?type=search2", { address_type: address_type.val(), a_pid: data2, building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data3) {
												$( "#physical_address_code" ).html(data3);
												$( "#physical_search_div" ).attr("style","display:none;");
												$( "#physical_address_code" ).removeAttr('style');
												$( "#physical_manual_store" ).removeAttr('style');
											});
										});
										///
									});
								}
								else
								{
									$( "#physical_address_code" ).html(data);
									$( "#physical_search_div" ).attr("style","display:none;");
									$( "#physical_address_code" ).removeAttr('style');
									$( "#physical_manual_store" ).removeAttr('style');
								}
							});
						}
						else
						{
							updateTips(data);
						}
					});
			},
			"Reset": function() {
				$( "#physical_input" ).val("");
				$( "#physical_input2" ).val("");
				$( "#physical_building_type_tr" ).attr("style","display:none;");
				$( "#physical_building_number_tr" ).attr("style","display:none;");
				$( "#physical_building_name_tr" ).attr("style","display:none;");
				$( "#physical_street_number_tr" ).attr("style","display:none;");
				$( "#physical_street_tr" ).attr("style","display:none;");
				$( "#physical_suburb_tr" ).attr("style","display:none;");
				$( "#physical_state_tr" ).attr("style","display:none;");
				$( "#physical_postcode_tr" ).attr("style","display:none;");
				$( "#physical_input_tr" ).attr("style","display:none;");
				$( "#physical_type_tr" ).attr("style","display:none;");
				$( "#physical_input_tr" ).removeAttr("style");
				$( "#physical_building_type option" ).remove();
				$( "#physical_building_number" ).val("");
				$( "#physical_building_name" ).val("");
				$( "#physical_street_number" ).val("");
				$( "#physical_street_name" ).val("");
				$( "#physical_street_type" ).val("");
				$( "#physical_suburb" ).val("");
				$( "#physical_state" ).val("");
				$( "#physical_postcode" ).val("");
				$('input[name=physical_type]:checked').removeAttr("checked");
			}
		}
	});
});

$(function() {
	$( "#physical_input" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "../source/gnafGet.php",
				dataType: "json",
				data: {
					type : "input",
					term : request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 3,
		select: function (event, ui) {
			$( "#physical_locality_pid" ).val(ui.item.id);
			$.get("../source/gnafGet.php?type=input_check", { l_pid: ui.item.id }, function(data) {
				var data2 = data.split(",");
				
				$( "#physical_suburb" ).val(data2[0]);
				$( "#physical_state" ).val(data2[1]);
				$( "#physical_postcode" ).val(data2[2]);
				$( "#physical_input_tr" ).attr("style","display:none;");
				$( "#physical_type_tr" ).removeAttr("style");
			});
		}
	});
});

$(function() {
	$( "#physical_input2" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "../source/gnafGet.php",
				dataType: "json",
				data: {
					type : "input2",
					term : request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 2,
		select: function (event, ui) {
			$( "#physical_locality_pid" ).val(ui.item.id);
			$.get("../source/gnafGet.php?type=input_check", { l_pid: ui.item.id }, function(data) {
				var data2 = data.split(",");
				
				$( "#physical_suburb" ).val(data2[0]);
				$( "#physical_state" ).val(data2[1]);
				$( "#physical_postcode" ).val(data2[2]);
				$( "#physical_input_tr" ).attr("style","display:none;");
				$( "#physical_type_tr" ).removeAttr("style");
			});
		}
	});
});

function FS_Physical()
{
	$( "#physical_building_type option" ).remove();
	$( "#physical_building_type" ).append(new Option('', '', true, true));
	$( "#physical_street_number_tr" ).removeAttr("style");
	$( "#physical_street_tr" ).removeAttr("style");
	$( "#physical_suburb_tr" ).removeAttr("style");
	$( "#physical_state_tr" ).removeAttr("style");
	$( "#physical_postcode_tr" ).removeAttr("style");
	$( "#physical_building_type_tr" ).attr("style","display:none;");
	$( "#physical_building_number_tr" ).attr("style","display:none;");
	$( "#physical_building_name_tr" ).attr("style","display:none;");
}

function OB_Physical()
{
	$( "#physical_building_type option" ).remove();
	$( "#physical_building_type" ).append(new Option('LEVEL', 'LEVEL', true, true));
	$( "#physical_building_number_span" ).html("Level Number ");
	$( "#physical_building_number_tr" ).removeAttr("style");
	$( "#physical_street_number_tr" ).removeAttr("style");
	$( "#physical_street_tr" ).removeAttr("style");
	$( "#physical_suburb_tr" ).removeAttr("style");
	$( "#physical_state_tr" ).removeAttr("style");
	$( "#physical_postcode_tr" ).removeAttr("style");
	$( "#physical_building_type_tr" ).attr("style","display:none;");
	$( "#physical_building_name_tr" ).attr("style","display:none;");
}

function BU_Physical()
{
	var newOptions = {
		'' : '',
		'APARTMENT' : 'APARTMENT',
		'DUPLEX' : 'DUPLEX',
		'FACTORY' : 'FACTORY',
		'FLAT' : 'FLAT',
		'HALL' : 'HALL',
		'OFFICE' : 'OFFICE',
		'PENTHOUSE' : 'PENTHOUSE',
		'ROOM' : 'ROOM',
		'SECTION' : 'SECTION',
		'SHOP' : 'SHOP',
		'SITE' : 'SITE',
		'STORE' : 'STORE',
		'STUDIO' : 'STUDIO',
		'SUITE' : 'SUITE',
		'TOWNHOUSE' : 'TOWNHOUSE',
		'UNIT' : 'UNIT',
		'VILLA' : 'VILLA'
	};
	var selectedOption = '';
	
	var select = $('#physical_building_type');
	if(select.prop) {
	  var options = select.prop('options');
	}
	else {
	  var options = select.attr('options');
	}
	$( "#physical_building_type option" ).remove();
	
	$.each(newOptions, function(val, text) {
		options[options.length] = new Option(text, val);
	});
	select.val(selectedOption);
	$( "#physical_building_type_tr" ).removeAttr("style");
	$( "#physical_building_number_span" ).html("Building Number ");
	$( "#physical_building_number_tr" ).removeAttr("style");
	$( "#physical_street_number_tr" ).removeAttr("style");
	$( "#physical_street_tr" ).removeAttr("style");
	$( "#physical_suburb_tr" ).removeAttr("style");
	$( "#physical_state_tr" ).removeAttr("style");
	$( "#physical_postcode_tr" ).removeAttr("style");
	$( "#physical_building_name_tr" ).attr("style","display:none;");
}

function LOT_Physical()
{
	$( "#physical_building_type option" ).remove();
	$( "#physical_building_type" ).append(new Option('LOT', 'LOT', true, true));
	$( "#physical_building_number_span" ).html("Lot Number ");
	$( "#physical_building_number_tr" ).removeAttr("style");
	$( "#physical_street_tr" ).removeAttr("style");
	$( "#physical_suburb_tr" ).removeAttr("style");
	$( "#physical_state_tr" ).removeAttr("style");
	$( "#physical_postcode_tr" ).removeAttr("style");
	$( "#physical_street_number_tr" ).attr("style","display:none;");
	$( "#physical_building_type_tr" ).attr("style","display:none;");
	$( "#physical_building_name_tr" ).attr("style","display:none;");
}

$(function() {
	$( "#physical_street_name" ).autocomplete({
		source: function(request, response) {
        $.ajax({
          url: "../source/gnafGet.php",
               dataType: "json",
          data: {
			  type : "street_name",
			  l_pid : $('#physical_locality_pid').val(),
  			  term : request.term
          },
          success: function(data) {
            response(data);
          }
        });
      },
		minLength: 1
	});
});

$(function() {
	$( "#physical_street_type" ).autocomplete({
		source: function(request, response) {
        $.ajax({
          url: "../source/gnafGet.php",
               dataType: "json",
          data: {
			  type : "street_type",
  			  term : request.term
          },
          success: function(data) {
            response(data);
          }
        });
      },
		minLength: 1
	});
});

function Physical()
{
	$( "#dialog-confirm_physical" ).dialog( "open" );
}
</script>
<script>
$(function() {
	$( "#dialog:.ui-dialog_physical_manual" ).dialog( "destroy" );
	
	var tips = $( ".validateTipsPhysicalManual" );
	
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#dialog-confirm_physical_manual" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width: 400,
		height:300,
		modal: true,
		buttons: {
			"Submit": function() {
				var building_type = $( "#physical_manual_building_type" ),
					building_number = $( "#physical_manual_building_number" ),
					building_name = $( "#physical_manual_building_name" ),
					street_number = $( "#physical_manual_street_number" ),
					street_name = $( "#physical_manual_street_name" ),
					street_type = $( "#physical_manual_street_type" ),
					l_pid = $('#physical_locality_pid');
					
				$.get("../source/gnafGet.php?type=check", { address_type: "MA", l_pid: l_pid.val(), building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data0) {
					if (data0 == "valid")
					{
						$.get("../source/gnafGet.php?type=manual", { l_pid: l_pid.val(), building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data) {
							$( "#physical" ).val(data);
							$.get("../source/gnafGet.php?type=display", { id: data }, function(data2) {
								var n = data2.split("}");
								$( "#display_physical1" ).val(n[0]);
								$( "#display_physical2" ).val(n[1]);
								$( "#display_physical3" ).val(n[2]);
								$( "#display_physical4" ).val(n[3]);
								$( "#dialog-confirm_physical_manual" ).dialog( "close" );
								$( "#dialog-confirm_physical2" ).dialog( "close" );
								$( "#dialog-confirm_physical" ).dialog( "close" );
							});
						});
					}
					else
					{
						updateTips(data0);
					}
				});
			},
			"Cancel": function() {
				$( "#dialog-confirm_physical_manual" ).dialog( "close" );
			}
		}
	});
});

$(function() {
	$( "#physical_manual_street_name" ).autocomplete({
		source: function(request, response) {
        $.ajax({
          url: "../source/gnafGet.php",
               dataType: "json",
          data: {
			  type : "street_name",
			  l_pid : $('#physical_locality_pid').val(),
  			  term : request.term
          },
          success: function(data) {
            response(data);
          }
        });
      },
		minLength: 1
	});
});

$(function() {
	$( "#physical_manual_street_type" ).autocomplete({
		source: function(request, response) {
        $.ajax({
          url: "../source/gnafGet.php",
               dataType: "json",
          data: {
			  type : "street_type",
  			  term : request.term
          },
          success: function(data) {
            response(data);
          }
        });
      },
		minLength: 1
	});
});

function Manual_Physical()
{
	var newOptions = {
		'' : '',
		'APARTMENT' : 'APARTMENT',
		'BLOCK' : 'BLOCK',
		'BUILDING' : 'BUILDING',
		'DUPLEX' : 'DUPLEX',
		'FACTORY' : 'FACTORY',
		'FLAT' : 'FLAT',
		'HALL' : 'HALL',
		'LEVEL' : 'LEVEL',
		'LOT' : 'LOT',
		'OFFICE' : 'OFFICE',
		'PENTHOUSE' : 'PENTHOUSE',
		'ROOM' : 'ROOM',
		'SECTION' : 'SECTION',
		'SHOP' : 'SHOP',
		'SITE' : 'SITE',
		'STORE' : 'STORE',
		'STUDIO' : 'STUDIO',
		'SUITE' : 'SUITE',
		'TOWNHOUSE' : 'TOWNHOUSE',
		'UNIT' : 'UNIT',
		'VILLA' : 'VILLA'
	};
	var selectedOption = $( "#physical_building_type" ).val();
	
	var select = $('#physical_manual_building_type');
	if(select.prop) {
	  var options = select.prop('options');
	}
	else {
	  var options = select.attr('options');
	}


	$( "#physical_manual_building_type option" ).remove();

	$.each(newOptions, function(val, text) {
		options[options.length] = new Option(text, val);
	});
	select.val(selectedOption);
	$( "#physical_manual_building_number" ).val($( "#physical_building_number" ).val());
	$( "#physical_manual_building_name" ).val("");
	$( "#physical_manual_street_number" ).val($( "#physical_street_number" ).val());
	$( "#physical_manual_street_name" ).val($( "#physical_street_name" ).val());
	$( "#physical_manual_street_type" ).val($( "#physical_street_type" ).val());
	$( "#physical_manual_suburb" ).val($( "#physical_suburb" ).val());
	$( "#physical_manual_state" ).val($( "#physical_state" ).val());
	$( "#physical_manual_postcode" ).val($( "#physical_postcode" ).val());
	$( "#dialog-confirm_physical_manual" ).dialog( "open" );
}
</script>
<!--#########################################################-->
<!--##													   ##-->
<!--##					POSTAL ADDRESS					   ##-->
<!--##													   ##-->
<!--#########################################################-->
<script>
$(function() {
	$( "#dialog:ui-dialog_postal_confirm_switch" ).dialog( "destroy" );
	
	$( "#dialog-confirm_postal4" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width: 275,
		height: 100,
		modal: true,
		show: "blind",
	});
});

function MailBox()
{
	$( "#dialog-confirm_postal2" ).dialog( "open" );
	$( "#dialog-confirm_postal4" ).dialog( "close" );
}

function MailAddress()
{
	$( "#dialog-confirm_postal" ).dialog( "open" );
	$( "#dialog-confirm_postal4" ).dialog( "close" );
}

function Postal()
{
	$( "#dialog-confirm_postal4" ).dialog( "open" );
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog_postal_confirm" ).dialog( "destroy" );

	$( "#dialog-confirm_postal3" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width: 400,
		height: 235,
		modal: true,
		buttons: {
			"Select": function() {
				if ($( "input[name=address_code]:checked" ).val() != undefined)
				{
					$.get("../source/gnafGet.php?type=display", { id: $( "input[name=address_code]:checked" ).val() },
						function(data) {
							var n = data.split("}");
							$( "#display_postal1" ).val(n[0]);
							$( "#display_postal2" ).val(n[1]);
							$( "#display_postal3" ).val(n[2]);
							$( "#display_postal4" ).val(n[3]);
						});
					$( "#postal" ).val($( "input[name=address_code]:checked" ).val());
					$( "#dialog-confirm_postal3" ).dialog( "close" );
					$( "#dialog-confirm_postal2" ).dialog( "close" );
					$( "#dialog-confirm_postal" ).dialog( "close" );
				}
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
</script>
<script> //Mail Box Address
$(function() {
	$( "#dialog:.ui-dialog_postal_mailbox" ).dialog( "destroy" );
	
	var tips = $( ".validateTipsMB" );
	
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#dialog-confirm_postal2" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width: 475,
		height:350,
		modal: true,
		show: "blind",
		hide: "blind",
		buttons: {
			"Verify Address": function() {
				var address_type = "MB",
					building_type = $( "#mb_building_type" ),
					building_number = $( "#mb_building_number" ),
					suburb = $( "#mb_suburb" ),
					state = $( "#mb_state" ),
					postcode = $( "#mb_postcode" );
				
				$.get("../source/gnafGet.php?type=check2", { address_type: address_type, building_type: building_type.val(), building_number: building_number.val(), suburb: suburb.val(), state: state.val(), postcode: postcode.val() }, function(data) {
					if (data == "valid")
					{
						$( "#postal_address_code" ).attr("style","display:none;");
						$( "#postal_manual_store" ).attr('style','display:none;');
						$( "#postal_search_div" ).html("<center><br><br><br><br><p><img src='../images/ajax-loader.gif'>&nbsp;&nbsp;&nbsp;&nbsp;Please wait. Saving your address...</p></center>");
						$( "#postal_search_div" ).removeAttr('style');
						$( "#dialog-confirm_postal3" ).dialog( "open" );
						
						$.get("../source/gnafGet.php?type=mailbox", { building_type: building_type.val(), building_number: building_number.val(), suburb: suburb.val(), state: state.val(), postcode: postcode.val() }, function(data2) {
							$( "#postal" ).val(data2);
							$.get("../source/gnafGet.php?type=display", { id: data2 }, function(data3) {
								var n = data3.split("}");
								$( "#display_postal1" ).val(n[0]);
								$( "#display_postal2" ).val(n[1]);
								$( "#display_postal3" ).val(n[2]);
								$( "#display_postal4" ).val(n[3]);
								$( "#dialog-confirm_postal3" ).dialog( "close" );
								$( "#dialog-confirm_postal2" ).dialog( "close" );
							});
						});
					}
					else
					{
						updateTips(data);
					}
				});
			},
			"Reset": function() {
				$( "#mb_input" ).val("");
				$( "#mb_input2" ).val("");
				$( "#mb_building_type_tr" ).attr("style","display:none;");
				$( "#mb_building_number_tr" ).attr("style","display:none;");
				$( "#mb_suburb_tr" ).attr("style","display:none;");
				$( "#mb_state_tr" ).attr("style","display:none;");
				$( "#mb_postcode_tr" ).attr("style","display:none;");
				$( "#mb_input_tr" ).removeAttr("style");
				$( "#mb_building_type" ).val("");
				$( "#mb_building_number" ).val("");
				$( "#mb_suburb" ).val("");
				$( "#mb_state" ).val("");
				$( "#mb_postcode" ).val("");
			}
		}
	});
});

$(function() {
	$( "#mb_input" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "../source/gnafGet.php",
				dataType: "json",
				data: {
					type : "input3",
					term : request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 3,
		select: function (event, ui) {
			var data2 = ui.item.id.split(",");
			$( "#mb_suburb" ).val(data2[0]);
			$( "#mb_state" ).val(data2[1]);
			$( "#mb_postcode" ).val(data2[2]);
			$( "#mb_input_tr" ).attr("style","display:none;");
			$( "#mb_suburb_tr" ).removeAttr("style");
			$( "#mb_state_tr" ).removeAttr("style");
			$( "#mb_postcode_tr" ).removeAttr("style");
			$( "#mb_building_type_tr" ).removeAttr("style");
			$( "#mb_building_number_tr" ).removeAttr("style");
		}
	});
});

$(function() {
	$( "#mb_input2" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "../source/gnafGet.php",
				dataType: "json",
				data: {
					type : "input4",
					term : request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 2,
		select: function (event, ui) {
			var data2 = ui.item.id.split(",");
			$( "#mb_suburb" ).val(data2[0]);
			$( "#mb_state" ).val(data2[1]);
			$( "#mb_postcode" ).val(data2[2]);
			$( "#mb_input_tr" ).attr("style","display:none;");
			$( "#mb_suburb_tr" ).removeAttr("style");
			$( "#mb_state_tr" ).removeAttr("style");
			$( "#mb_postcode_tr" ).removeAttr("style");
			$( "#mb_building_type_tr" ).removeAttr("style");
			$( "#mb_building_number_tr" ).removeAttr("style");
		}
	});
});
</script>
<script> // Postal Fixed Address
$(function() {
	$( "#dialog:.ui-dialog_postal" ).dialog( "destroy" );
	
	var tips = $( ".validateTipsPostal" );
	
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#dialog-confirm_postal" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width: 475,
		height:350,
		modal: true,
		show: "blind",
		hide: "blind",
		buttons: {
			"Verify Address": function() {
				var address_type = $('input[name=postal_type]:checked'),
					building_type = $( "#postal_building_type" ),
					building_number = $( "#postal_building_number" ),
					building_name = $( "#postal_building_name" ),
					street_number = $( "#postal_street_number" ),
					street_name = $( "#postal_street_name" ),
					street_type = $( "#postal_street_type" ),
					l_pid = $('#postal_locality_pid');
					
					$.get("../source/gnafGet.php?type=check", { address_type: address_type.val(), l_pid: l_pid.val(), building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data) {
						if (data == "valid")
						{
							$( "#postal_address_code" ).attr("style","display:none;");
							$( "#postal_manual_store" ).attr('style','display:none;');
							$( "#dialog-confirm_postal3" ).dialog( "open" );
							$( "#postal_search_div" ).html("<center><br><br><br><br><p><img src='../images/ajax-loader.gif'>&nbsp;&nbsp;&nbsp;&nbsp;Please wait. Verifying your address...</p></center>");
							$( "#postal_search_div" ).removeAttr('style');
							
							$.get("../source/gnafGet.php?type=search", { address_type: address_type.val(), l_pid: l_pid.val(), building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data) {
								if (data == 'No Results Found')
								{
									$( "#postal_search_div" ).html("<center><br><br><br><br><p><img src='../images/ajax-loader.gif'>&nbsp;&nbsp;&nbsp;&nbsp;Exact match not found. Looking up similar...</p></center>");
									
									$.get("../source/gnafGet.php?type=format", { l_pid: l_pid.val(), building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data0) {
										/// CLOUD GEOCODER API GET
										$.get("../source/gnafXML.php", { input: data0 }, function(data2) {
											$.get("../source/gnafGet.php?type=search2", { address_type: address_type.val(), a_pid: data2, building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data3) {
												$( "#postal_address_code" ).html(data3);
												$( "#postal_search_div" ).attr("style","display:none;");
												$( "#postal_address_code" ).removeAttr('style');
												$( "#postal_manual_store" ).removeAttr('style');
											});
										});
										///
									});
								}
								else
								{
									$( "#postal_address_code" ).html(data);
									$( "#postal_search_div" ).attr("style","display:none;");
									$( "#postal_address_code" ).removeAttr('style');
									$( "#postal_manual_store" ).removeAttr('style');
								}
							});
						}
						else
						{
							updateTips(data);
						}
					});
			},
			"Reset": function() {
				$( "#postal_input" ).val("");
				$( "#postal_input2" ).val("");
				$( "#postal_building_type_tr" ).attr("style","display:none;");
				$( "#postal_building_number_tr" ).attr("style","display:none;");
				$( "#postal_building_name_tr" ).attr("style","display:none;");
				$( "#postal_street_number_tr" ).attr("style","display:none;");
				$( "#postal_street_tr" ).attr("style","display:none;");
				$( "#postal_suburb_tr" ).attr("style","display:none;");
				$( "#postal_state_tr" ).attr("style","display:none;");
				$( "#postal_postcode_tr" ).attr("style","display:none;");
				$( "#postal_input_tr" ).attr("style","display:none;");
				$( "#postal_type_tr" ).attr("style","display:none;");
				$( "#postal_input_tr" ).removeAttr("style");
				$( "#postal_building_type option" ).remove();
				$( "#postal_building_number" ).val("");
				$( "#postal_building_name" ).val("");
				$( "#postal_street_number" ).val("");
				$( "#postal_street_name" ).val("");
				$( "#postal_street_type" ).val("");
				$( "#postal_suburb" ).val("");
				$( "#postal_state" ).val("");
				$( "#postal_postcode" ).val("");
				$('input[name=postal_type]:checked').removeAttr("checked");
			}
		}
	});
});

$(function() {
	$( "#postal_input" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "../source/gnafGet.php",
				dataType: "json",
				data: {
					type : "input",
					term : request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 3,
		select: function (event, ui) {
			$( "#postal_locality_pid" ).val(ui.item.id);
			$.get("../source/gnafGet.php?type=input_check", { l_pid: ui.item.id }, function(data) {
				var data2 = data.split(",");
				
				$( "#postal_suburb" ).val(data2[0]);
				$( "#postal_state" ).val(data2[1]);
				$( "#postal_postcode" ).val(data2[2]);
				$( "#postal_input_tr" ).attr("style","display:none;");
				$( "#postal_type_tr" ).removeAttr("style");
			});
		}
	});
});

$(function() {
	$( "#postal_input2" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "../source/gnafGet.php",
				dataType: "json",
				data: {
					type : "input2",
					term : request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 2,
		select: function (event, ui) {
			$( "#postal_locality_pid" ).val(ui.item.id);
			$.get("../source/gnafGet.php?type=input_check", { l_pid: ui.item.id }, function(data) {
				var data2 = data.split(",");
				
				$( "#postal_suburb" ).val(data2[0]);
				$( "#postal_state" ).val(data2[1]);
				$( "#postal_postcode" ).val(data2[2]);
				$( "#postal_input_tr" ).attr("style","display:none;");
				$( "#postal_type_tr" ).removeAttr("style");
			});
		}
	});
});

function FS_Postal()
{
	$( "#postal_building_type option" ).remove();
	$( "#postal_building_type" ).append(new Option('', '', true, true));
	$( "#postal_street_number_tr" ).removeAttr("style");
	$( "#postal_street_tr" ).removeAttr("style");
	$( "#postal_suburb_tr" ).removeAttr("style");
	$( "#postal_state_tr" ).removeAttr("style");
	$( "#postal_postcode_tr" ).removeAttr("style");
	$( "#postal_building_type_tr" ).attr("style","display:none;");
	$( "#postal_building_number_tr" ).attr("style","display:none;");
	$( "#postal_building_name_tr" ).attr("style","display:none;");
}

function OB_Postal()
{
	$( "#postal_building_type option" ).remove();
	$( "#postal_building_type" ).append(new Option('LEVEL', 'LEVEL', true, true));
	$( "#postal_building_number_span" ).html("Level Number ");
	$( "#postal_building_number_tr" ).removeAttr("style");
	$( "#postal_street_number_tr" ).removeAttr("style");
	$( "#postal_street_tr" ).removeAttr("style");
	$( "#postal_suburb_tr" ).removeAttr("style");
	$( "#postal_state_tr" ).removeAttr("style");
	$( "#postal_postcode_tr" ).removeAttr("style");
	$( "#postal_building_type_tr" ).attr("style","display:none;");
	$( "#postal_building_name_tr" ).attr("style","display:none;");
}

function BU_Postal()
{
	var newOptions = {
		'' : '',
		'APARTMENT' : 'APARTMENT',
		'DUPLEX' : 'DUPLEX',
		'FACTORY' : 'FACTORY',
		'FLAT' : 'FLAT',
		'HALL' : 'HALL',
		'OFFICE' : 'OFFICE',
		'PENTHOUSE' : 'PENTHOUSE',
		'ROOM' : 'ROOM',
		'SECTION' : 'SECTION',
		'SHOP' : 'SHOP',
		'SITE' : 'SITE',
		'STORE' : 'STORE',
		'STUDIO' : 'STUDIO',
		'SUITE' : 'SUITE',
		'TOWNHOUSE' : 'TOWNHOUSE',
		'UNIT' : 'UNIT',
		'VILLA' : 'VILLA'
	};
	var selectedOption = '';
	
	var select = $('#postal_building_type');
	if(select.prop) {
	  var options = select.prop('options');
	}
	else {
	  var options = select.attr('options');
	}
	$( "#postal_building_type option" ).remove();
	
	$.each(newOptions, function(val, text) {
		options[options.length] = new Option(text, val);
	});
	select.val(selectedOption);
	$( "#postal_building_type_tr" ).removeAttr("style");
	$( "#postal_building_number_span" ).html("Building Number ");
	$( "#postal_building_number_tr" ).removeAttr("style");
	$( "#postal_street_number_tr" ).removeAttr("style");
	$( "#postal_street_tr" ).removeAttr("style");
	$( "#postal_suburb_tr" ).removeAttr("style");
	$( "#postal_state_tr" ).removeAttr("style");
	$( "#postal_postcode_tr" ).removeAttr("style");
	$( "#postal_building_name_tr" ).attr("style","display:none;");
}

function LOT_Postal()
{
	$( "#postal_building_type option" ).remove();
	$( "#postal_building_type" ).append(new Option('LOT', 'LOT', true, true));
	$( "#postal_building_number_span" ).html("Lot Number ");
	$( "#postal_building_number_tr" ).removeAttr("style");
	$( "#postal_street_tr" ).removeAttr("style");
	$( "#postal_suburb_tr" ).removeAttr("style");
	$( "#postal_state_tr" ).removeAttr("style");
	$( "#postal_postcode_tr" ).removeAttr("style");
	$( "#postal_street_number_tr" ).attr("style","display:none;");
	$( "#postal_building_type_tr" ).attr("style","display:none;");
	$( "#postal_building_name_tr" ).attr("style","display:none;");
}

$(function() {
	$( "#postal_street_name" ).autocomplete({
		source: function(request, response) {
        $.ajax({
          url: "../source/gnafGet.php",
               dataType: "json",
          data: {
			  type : "street_name",
			  l_pid : $('#postal_locality_pid').val(),
  			  term : request.term
          },
          success: function(data) {
            response(data);
          }
        });
      },
		minLength: 1
	});
});

$(function() {
	$( "#postal_street_type" ).autocomplete({
		source: function(request, response) {
        $.ajax({
          url: "../source/gnafGet.php",
               dataType: "json",
          data: {
			  type : "street_type",
  			  term : request.term
          },
          success: function(data) {
            response(data);
          }
        });
      },
		minLength: 1
	});
});
</script>
<script>
$(function() {
	$( "#dialog:.ui-dialog_postal_manual" ).dialog( "destroy" );
	
	var tips = $( ".validateTipsPostalManual" );
	
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#dialog-confirm_postal_manual" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width: 400,
		height:300,
		modal: true,
		buttons: {
			"Submit": function() {
				var building_type = $( "#postal_manual_building_type" ),
					building_number = $( "#postal_manual_building_number" ),
					building_name = $( "#postal_manual_building_name" ),
					street_number = $( "#postal_manual_street_number" ),
					street_name = $( "#postal_manual_street_name" ),
					street_type = $( "#postal_manual_street_type" ),
					l_pid = $('#postal_locality_pid');
					
				$.get("../source/gnafGet.php?type=check", { address_type: "MA", l_pid: l_pid.val(), building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data0) {
					if (data0 == "valid")
					{
						$.get("../source/gnafGet.php?type=manual", { l_pid: l_pid.val(), building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data) {
							$( "#postal" ).val(data);
							$.get("../source/gnafGet.php?type=display", { id: data }, function(data2) {
								var n = data2.split("}");
								$( "#display_postal1" ).val(n[0]);
								$( "#display_postal2" ).val(n[1]);
								$( "#display_postal3" ).val(n[2]);
								$( "#display_postal4" ).val(n[3]);
								$( "#dialog-confirm_postal_manual" ).dialog( "close" );
								$( "#dialog-confirm_postal3" ).dialog( "close" );
								$( "#dialog-confirm_postal2" ).dialog( "close" );
								$( "#dialog-confirm_postal" ).dialog( "close" );
							});
						});
					}
					else
					{
						updateTips(data0);
					}
				});
			},
			"Cancel": function() {
				$( "#dialog-confirm_postal_manual" ).dialog( "close" );
			}
		}
	});
});

$(function() {
	$( "#postal_manual_street_name" ).autocomplete({
		source: function(request, response) {
        $.ajax({
          url: "../source/gnafGet.php",
               dataType: "json",
          data: {
			  type : "street_name",
			  l_pid : $('#postal_locality_pid').val(),
  			  term : request.term
          },
          success: function(data) {
            response(data);
          }
        });
      },
		minLength: 1
	});
});

$(function() {
	$( "#postal_manual_street_type" ).autocomplete({
		source: function(request, response) {
        $.ajax({
          url: "../source/gnafGet.php",
               dataType: "json",
          data: {
			  type : "street_type",
  			  term : request.term
          },
          success: function(data) {
            response(data);
          }
        });
      },
		minLength: 1
	});
});

function Manual_Postal()
{
	var newOptions = {
		'' : '',
		'APARTMENT' : 'APARTMENT',
		'BLOCK' : 'BLOCK',
		'BUILDING' : 'BUILDING',
		'DUPLEX' : 'DUPLEX',
		'FACTORY' : 'FACTORY',
		'FLAT' : 'FLAT',
		'HALL' : 'HALL',
		'LEVEL' : 'LEVEL',
		'LOT' : 'LOT',
		'OFFICE' : 'OFFICE',
		'PENTHOUSE' : 'PENTHOUSE',
		'ROOM' : 'ROOM',
		'SECTION' : 'SECTION',
		'SHOP' : 'SHOP',
		'SITE' : 'SITE',
		'STORE' : 'STORE',
		'STUDIO' : 'STUDIO',
		'SUITE' : 'SUITE',
		'TOWNHOUSE' : 'TOWNHOUSE',
		'UNIT' : 'UNIT',
		'VILLA' : 'VILLA'
	};
	var selectedOption = $( "#postal_building_type" ).val();
	
	var select = $('#postal_manual_building_type');
	if(select.prop) {
	  var options = select.prop('options');
	}
	else {
	  var options = select.attr('options');
	}
	$( "#postal_manual_building_type option" ).remove();

	
	$.each(newOptions, function(val, text) {
		options[options.length] = new Option(text, val);
	});
	select.val(selectedOption);
	$( "#postal_manual_building_number" ).val($( "#postal_building_number" ).val());
	$( "#postal_manual_building_name" ).val("");
	$( "#postal_manual_street_number" ).val($( "#postal_street_number" ).val());
	$( "#postal_manual_street_name" ).val($( "#postal_street_name" ).val());
	$( "#postal_manual_street_type" ).val($( "#postal_street_type" ).val());
	$( "#postal_manual_suburb" ).val($( "#postal_suburb" ).val());
	$( "#postal_manual_state" ).val($( "#postal_state" ).val());
	$( "#postal_manual_postcode" ).val($( "#postal_postcode" ).val());
	$( "#dialog-confirm_postal_manual" ).dialog( "open" );
}
</script>
<script>
function Postal_Same()
{
	if ($('#postal_same').attr('checked'))
	{
		$( "#display_postal1" ).val("SAME AS PHYSICAL");
		$( "#display_postal2" ).val("");
		$( "#display_postal3" ).val("");
		$( "#display_postal4" ).val("");
		$( "#display_postal1" ).attr("disabled","disabled");
		$( "#display_postal2" ).attr("disabled","disabled");
		$( "#display_postal3" ).attr("disabled","disabled");
		$( "#display_postal4" ).attr("disabled","disabled");
		$( "#postal_link" ).attr("disabled","disabled");
		$( "#postal_link" ).removeAttr("onclick");
	}
	else
	{
		$( "#display_postal1" ).val("");
		$( "#display_postal2" ).val("");
		$( "#display_postal3" ).val("");
		$( "#display_postal4" ).val("");
		$( "#display_postal1" ).removeAttr("disabled");
		$( "#display_postal2" ).removeAttr("disabled");
		$( "#display_postal3" ).removeAttr("disabled");
		$( "#display_postal4" ).removeAttr("disabled");
		$( "#postal" ).val("");
		$( "#postal_link" ).removeAttr("disabled");
		$( "#postal_link" ).attr("onclick", "Postal()");
	}
}
</script>

<div id="dialog-form2" title="Add a Package">
<p class="validateTips2">All fields are required</p><br />
<table>
<tr>
<td width="50px">CLI </td>
<td><input type="text" id="cli" onchange="Plan_Dropdown()" style="width:125px;" /></td>
</tr>
<td>Plan </td>
<td><select id="plan" style="width:210px;">
<option></option>
</select></td>
</tr>
</table>
</div>

<div id="dialog-form3" title="Edit Package">
<p class="validateTips3">All fields are required</p><br />
<input type="hidden" id="original_edit_cli" value="" />
<table>
<tr>
<td width="50px">CLI </td>
<td><input type="text" size="15" id="edit_cli" onchange="Plan_Dropdown_Edit()" style="margin-top:0px;" /></td>
</tr>
<tr>
<td>Plan </td>
<td><select id="edit_plan" style="width:210px;">
<option></option>
</select></td>
</tr>
</table>
</div>

<div id="dialog-form_submit" title="Error!">
<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><span class="submit_error"></span></p>
</div>

<div id="dialog-form4" title="Cancel Verification">
<p class="validateTips4">All fields are required</p><br />
<table>
<tr>
<td width="50px">Status </td>
<td><select id="status" style="width:120px;">
<option></option>
<option>Declined</option>
<option>Line Issue</option>
</select></td>
</tr>
<tr>
<td width="50px">Note </td>
<td><textarea id="cancel_note" rows="5" style="width:350px; resize:none;"></textarea></td>
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

<div id="dialog-confirm_physical2" title="Verifying Physical Address">
<table width="100%">
<tr>
<td>
<div id="physical_search_div">
</div>
<div id="physical_address_code" style="display:none;">
</div>
</td>
</tr>
<tr>
<td><div id="physical_manual_store" style="display:none;">
<a onclick="Manual_Physical()" style="cursor:pointer; text-decoration:underline;">Address not found? Click here to store it manually</a>
</div></td>
</tr>
</table>
</div>

<div id="dialog-confirm_physical" title="Physical Address">
<p class="validateTipsPhysical">Enter the customer's suburb or postcode below to begin searching the GNAF dataset.</p><br />
<input type="hidden" id="physical_locality_pid" value="" />
<table width="100%">
<tr id="physical_input_tr">
<td width="200px" align="center">Suburb<br /><input type="text" id="physical_input" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
<td align="center">OR</td>
<td width="200px" align="center">Postcode<br /><input type="text" id="physical_input2" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
</tr>
</table>
<center><table width="95%">
<tr id="physical_type_tr" style="display:none;">
<td><input type="radio" name="physical_type" value="FS" onclick="FS_Physical()" style="height:auto; margin:0 3px;" />Freestanding Premises</td>
<td><input type="radio" name="physical_type" value="OB" onclick="OB_Physical()" style="height:auto; margin:0 3px;" />Office Building</td>
<td><input type="radio" name="physical_type" value="BU" onclick="BU_Physical()" style="height:auto; margin:0 3px;" />Flat, Unit or Apartment</td>
<td><input type="radio" name="physical_type" value="LOT" onclick="LOT_Physical()" style="height:auto; margin:0 3px;" />Lot</td>
</tr>
</table></center>
<table width="100%" style="margin-top:10px;">
<tr id="physical_building_type_tr" style="display:none;">
<td width="80px">Building Type</td>
<td><select id="physical_building_type" style="width:95px; height:auto; padding:0px; margin:0px;"></select></td>
</tr>
<tr id="physical_building_number_tr" style="display:none;">
<td width="80px"><span id="physical_building_number_span">Building Number </span></td>
<td><input type="text" id="physical_building_number" value="" style="width:50px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="physical_building_name_tr" style="display:none;">
<td width="80px">Building Name </td>
<td><input type="text" id="physical_building_name" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="physical_street_number_tr" style="display:none;">
<td width="80px">Street Number </td>
<td><input type="text" id="physical_street_number" value="" style="width:50px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="physical_street_tr" style="display:none;">
<td width="80px">Street</td>
<td><input type="text" id="physical_street_name" value="" style="width:107px; height:auto; padding-left:3px;" /> <input type="text" id="physical_street_type" value="" style="width:75px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="physical_suburb_tr" style="display:none;">
<td width="80px">Suburb </td>
<td><input type="text" id="physical_suburb" disabled="disabled" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="physical_state_tr" style="display:none;">
<td width="80px">State </td>
<td><input type="text" id="physical_state" disabled="disabled" value="" style="width:30px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="physical_postcode_tr" style="display:none;">
<td width="80px">Postcode </td>
<td><input type="text" id="physical_postcode" disabled="disabled" value="" style="width:30px; height:auto; padding-left:3px;" /></td>
</tr>
</table>
</div>

<div id="dialog-confirm_physical_manual" title="Physical Address Manual">
<p class="validateTipsPhysicalManual">Enter the customer's address in the appropriate fields.</p><br />
<table width="100%" style="margin-top:10px;">
<tr>
<td width="80px">Building Type </td>
<td><select id="physical_manual_building_type" style="width:95px; height:auto; padding:0px; margin:0px;"></select></td>
</tr>
<tr>
<td width="80px">Building Number </td>
<td><input type="text" id="physical_manual_building_number" value="" style="width:50px; height:auto; padding-left:3px;" /></td>
</tr>
<tr>
<td width="80px">Building Name </td>
<td><input type="text" id="physical_manual_building_name" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
</tr>
<tr>
<td width="80px">Street Number </td>
<td><input type="text" id="physical_manual_street_number" value="" style="width:50px; height:auto; padding-left:3px;" /></td>
</tr>
<tr>
<td width="80px">Street</td>
<td><input type="text" id="physical_manual_street_name" value="" style="width:107px; height:auto; padding-left:3px;" /> <input type="text" id="physical_manual_street_type" value="" style="width:75px; height:auto; padding-left:3px;" /></td>
</tr>
<tr>
<td width="80px">Suburb </td>
<td><input type="text" id="physical_manual_suburb" disabled="disabled" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
</tr>
<tr>
<td width="80px">State </td>
<td><input type="text" id="physical_manual_state" disabled="disabled" value="" style="width:30px; height:auto; padding-left:3px;" /></td>
</tr>
<tr>
<td width="80px">Postcode </td>
<td><input type="text" id="physical_manual_postcode" disabled="disabled" value="" style="width:30px; height:auto; padding-left:3px;" /></td>
</tr>
</table>
</div>

<div id="dialog-confirm_postal4" title="Postal Address Switch">
<table width="100%" height="55px">
<tr height="100%">
<td valign="middle" align="center"><button onclick="MailBox()" class="btn">Mail Box</button></td>
<td valign="middle" align="center"><button onclick="MailAddress()" class="btn">Address</button></td>
</tr>
</table>
</div>

<div id="dialog-confirm_postal3" title="Verifying Postal Address">
<table width="100%">
<tr>
<td>
<div id="postal_search_div">
</div>
<div id="postal_address_code" style="display:none;">
</div>
</td>
</tr>
<tr>
<td><div id="postal_manual_store" style="display:none;">
<a onclick="Manual_Postal()" style="cursor:pointer; text-decoration:underline;">Address not found? Click here to store it manually</a>
</div></td>
</tr>
</table>
</div>

<div id="dialog-confirm_postal2" title="Postal Mail Box">
<p class="validateTipsMB">Enter the customer's suburb or postcode below to begin searching the PAF dataset.</p><br />
<table width="100%">
<tr id="mb_input_tr">
<td width="200px" align="center">Suburb<br /><input type="text" id="mb_input" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
<td align="center">OR</td>
<td width="200px" align="center">Postcode<br /><input type="text" id="mb_input2" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
</tr>
</table>
<table width="100%" style="margin-top:10px;">
<tr id="mb_building_type_tr" style="display:none;">
<td width="80px">Mail Box Type</td>
<td><select id="mb_building_type" style="width:115px; height:auto; padding:0px; margin:0px;">
<option></option>
<option>Care of Post Office</option>
<option>LOCKED BAG</option>
<option>PO BOX</option>
<option>PRIVATE BAG</option>
<option>RMB</option>
<option>RMD</option>
<option>RSD</option>
</select></td>
</tr>
<tr id="mb_building_number_tr" style="display:none;">
<td width="80px">Mail Box Number </td>
<td><input type="text" id="mb_building_number" value="" style="width:50px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="mb_suburb_tr" style="display:none;">
<td width="80px">Suburb </td>
<td><input type="text" id="mb_suburb" disabled="disabled" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="mb_state_tr" style="display:none;">
<td width="80px">State </td>
<td><input type="text" id="mb_state" disabled="disabled" value="" style="width:30px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="mb_postcode_tr" style="display:none;">
<td width="80px">Postcode </td>
<td><input type="text" id="mb_postcode" disabled="disabled" value="" style="width:30px; height:auto; padding-left:3px;" /></td>
</tr>
</table>
</div>

<div id="dialog-confirm_postal" title="Postal Address">
<p class="validateTipsPostal">Enter the customer's suburb or postcode below to begin searching the GNAF dataset.</p><br />
<input type="hidden" id="postal_locality_pid" value="" />
<table width="100%">
<tr id="postal_input_tr">
<td width="200px" align="center">Suburb<br /><input type="text" id="postal_input" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
<td align="center">OR</td>
<td width="200px" align="center">Postcode<br /><input type="text" id="postal_input2" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
</tr>
</table>
<center><table width="95%">
<tr id="postal_type_tr" style="display:none;">
<td><input type="radio" name="postal_type" value="FS" onclick="FS_Postal()" style="height:auto; margin:0 3px;" />Freestanding Premises</td>
<td><input type="radio" name="postal_type" value="OB" onclick="OB_Postal()" style="height:auto; margin:0 3px;" />Office Building</td>
<td><input type="radio" name="postal_type" value="BU" onclick="BU_Postal()" style="height:auto; margin:0 3px;" />Flat, Unit or Apartment</td>
<td><input type="radio" name="postal_type" value="LOT" onclick="LOT_Postal()" style="height:auto; margin:0 3px;" />Lot</td>
</tr>
</table></center>
<table width="100%" style="margin-top:10px;">
<tr id="postal_building_type_tr" style="display:none;">
<td width="80px">Building Type</td>
<td><select id="postal_building_type" style="width:95px; height:auto; padding:0px; margin:0px;"></select></td>
</tr>
<tr id="postal_building_number_tr" style="display:none;">
<td width="80px"><span id="postal_building_number_span">Building Number </span></td>
<td><input type="text" id="postal_building_number" value="" style="width:50px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="postal_building_name_tr" style="display:none;">
<td width="80px">Building Name </td>
<td><input type="text" id="postal_building_name" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="postal_street_number_tr" style="display:none;">
<td width="80px">Street Number </td>
<td><input type="text" id="postal_street_number" value="" style="width:50px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="postal_street_tr" style="display:none;">
<td width="80px">Street</td>
<td><input type="text" id="postal_street_name" value="" style="width:107px; height:auto; padding-left:3px;" /> <input type="text" id="postal_street_type" value="" style="width:75px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="postal_suburb_tr" style="display:none;">
<td width="80px">Suburb </td>
<td><input type="text" id="postal_suburb" disabled="disabled" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="postal_state_tr" style="display:none;">
<td width="80px">State </td>
<td><input type="text" id="postal_state" disabled="disabled" value="" style="width:30px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="postal_postcode_tr" style="display:none;">
<td width="80px">Postcode </td>
<td><input type="text" id="postal_postcode" disabled="disabled" value="" style="width:30px; height:auto; padding-left:3px;" /></td>
</tr>
</table>
</div>

<div id="dialog-confirm_postal_manual" title="Postal Address Manual">
<p class="validateTipsPhysicalManual">Enter the customer's address in the appropriate fields.</p><br />
<table width="100%" style="margin-top:10px;">
<tr>
<td width="80px">Building Type </td>
<td><select id="postal_manual_building_type" style="width:95px; height:auto; padding:0px; margin:0px;"></select></td>
</tr>
<tr>
<td width="80px">Building Number </td>
<td><input type="text" id="postal_manual_building_number" value="" style="width:50px; height:auto; padding-left:3px;" /></td>
</tr>
<tr>
<td width="80px">Building Name </td>
<td><input type="text" id="postal_manual_building_name" value="" style="width:190px; height:auto; padding-left:3px;" /></td>


</tr>
<tr>
<td width="80px">Street Number </td>
<td><input type="text" id="postal_manual_street_number" value="" style="width:50px; height:auto; padding-left:3px;" /></td>
</tr>
<tr>
<td width="80px">Street</td>
<td><input type="text" id="postal_manual_street_name" value="" style="width:107px; height:auto; padding-left:3px;" /> <input type="text" id="postal_manual_street_type" value="" style="width:75px; height:auto; padding-left:3px;" /></td>
</tr>
<tr>
<td width="80px">Suburb </td>
<td><input type="text" id="postal_manual_suburb" disabled="disabled" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
</tr>
<tr>
<td width="80px">State </td>
<td><input type="text" id="postal_manual_state" disabled="disabled" value="" style="width:30px; height:auto; padding-left:3px;" /></td>
</tr>
<tr>
<td width="80px">Postcode </td>
<td><input type="text" id="postal_manual_postcode" disabled="disabled" value="" style="width:30px; height:auto; padding-left:3px;" /></td>
</tr>
</table>
</div>

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
<td width="100px" style="padding-left:5px;">Lead ID </td>
<td><b><?php echo $data["lead_id"]; ?></b></td>
</tr>
<tr>
<td style="padding-left:5px;">Agent Name </td>
<td><b><?php echo $ac["first"] . " " . $ac["last"] . " (" . $ac["alias"] . ")"; ?></b></td>
</tr>
<tr>
<td style="padding-left:5px;">Centre </td>
<td><b><?php echo $ac["centre"]; ?></b></td>
</tr>
<tr>
<td style="padding-left:5px;">Campaign </td>
<td><b><?php echo $data["campaign"]; ?></b></td>
</tr>
<tr>
<td style="padding-left:5px;">Type </td>
<td><b><?php echo $data["type"]; ?></b></td>
</tr>
</table>
</td>
<td width="50%" valign="top" style="padding-left:20px;">
<table width="100%" style="margin-bottom:10px;">
<tr>
<td colspan="2" style="padding-left:5px;"><img src="../images/other_details_header.png" width="105" height="15" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
<tr>
<td style="padding-left:2px;">Dialled Number<span style="color:#ff0000;">*</span> </td>
<td><input type="text" id="dialled" style="width:150px;" /></td>
</tr>
<tr>
<td style="padding-left:2px;">Verifier<span style="color:#ff0000;">*</span> </td>
<td><input type="text" id="sale_verifier_d" style="width:150px;" /><input type="hidden" id="sale_verifier" /></td>
</tr>
<tr>
<td style="padding-left:2px;">Sale Notes </td>
<td><textarea id="notes" rows="4" style="width:98%; resize:none;"></textarea></td>
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
	user = "<?php echo $ac["user"]; ?>",
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