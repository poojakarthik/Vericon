<?php
mysql_connect('localhost','vericon','18450be');

$q = mysql_query("SELECT user FROM vericon.currentuser WHERE hash = '" . $_COOKIE["hash"] . "'") or die(mysql_error());
$user = mysql_fetch_row($q);

$q1 = mysql_query("SELECT * FROM vericon.auth WHERE user = '$user[0]'") or die(mysql_error());
$ac = mysql_fetch_assoc($q1);

$id = $_GET["id"];
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
.ui-dialog_physical { padding: .3em; }
.ui-dialog_postal { padding: .3em; }
.ui-dialog_postal_mailbox { padding: .3em; }
.ui-dialog_postal_confirm_switch { padding: .3em; }
.ui-state-highlight { padding: .3em; }
.validateTips2 { border: 1px solid transparent; padding: 0.3em; }
.validateTips3 { border: 1px solid transparent; padding: 0.3em; }
.validateTips4 { border: 1px solid transparent; padding: 0.3em; }
.validateTipsPhysical { border: 1px solid transparent; padding: 0.3em; }
.validateTipsPostal { border: 1px solid transparent; padding: 0.3em; }
.validateTipsMB { border: 1px solid transparent; padding: 0.3em; }
.ui-autocomplete { max-height: 300px; overflow-y: auto; overflow-x: hidden; padding-right: 20px; }
.ui-autocomplete-loading { background: white url('../images/ajax-loader.gif') right center no-repeat; }
</style>
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
		height: 280,
		width: 400,
		modal: true,
		resizable: false,
		draggable: false,
		show: "blind",
		hide: "blind",
		buttons: {
			"Add Package": function() {
				var id = $( "#id" ),
					cli = $( "#cli" ),
					plan = $( "#plan" ),
					plan_type = $( "#plan_type" ),
					provider = $( "#provider" ),
					ac_number = $( "#ac_number" ),
					adsl_provider = $( "#adsl_provider "),
					adsl_ac_number = $( "#adsl_ac_number ");
				
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
					$.get("verification_submit.php?method=add_nz", { id: id.val(), cli: cli.val(), plan: plan.val(), plan_type: plan_type.val(), provider: provider.val(), ac_number: ac_number.val(), adsl_provider: adsl_provider.val(), adsl_ac_number: adsl_ac_number.val() },
					function(data) {
						if (data == "added")
						{
							$( "#packages" ).load('packages_nz.php?id=' + id.val());
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
	$( "#provider" ).val("");
	$( "#ac_number" ).val("");
	$( "#adsl_provider" ).val("");
	$( "#adsl_ac_number" ).val("");
	$( "#adsl_provider_tr" ).attr("style","display:none;");
	$( "#adsl_ac_number_tr" ).attr("style","display:none;");
	$( "#validateTips2" ).text("All fields are required");
	$( "#dialog-form2" ).dialog( "open" );
}

function Plan_Dropdown()
{
	$( "#plan" ).attr("disabled","disabled");
	$( "#plan" ).html("<option value=''>Loading...</option>");
	$( "#plan" ).load("plans_nz.php?option=add&id=" + $( "#id" ).val() + "&type=" + $( "#sale_type" ).val() + "&cli=" + $('#cli').val(), function() {
		$( "#plan" ).removeAttr("disabled");
	});
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
		height: 280,
		width: 400,
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
					plan_type = $( "#edit_plan_type" ),
					provider = $( "#edit_provider" ),
					ac_number = $( "#edit_ac_number" ),
					adsl_provider = $( "#edit_adsl_provider "),
					adsl_ac_number = $( "#edit_adsl_ac_number "),
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
					$.get("verification_submit.php?method=edit_nz", { id: id.val(), cli: cli.val(), plan: plan.val(), plan_type: plan_type.val(), provider: provider.val(), ac_number: ac_number.val(), adsl_provider: adsl_provider.val(), adsl_ac_number: adsl_ac_number.val(), cli2: cli2.val() },
					function(data) {
						if (data == "editted")
						{
							$( "#packages" ).load('packages_nz.php?id=' + id.val());
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
	var id = $( "#id" );
	
	$( "#edit_cli" ).val(cli);
	$( "#edit_plan_type" ).val("PSTN");
	$( "#edit_adsl_provider" ).val("");
	$( "#edit_adsl_ac_number" ).val("");
	$( "#edit_adsl_provider_tr" ).attr("style","display:none;");
	$( "#edit_adsl_ac_number_tr" ).attr("style","display:none;");
	$( "#edit_cli" ).attr("disabled","disabled");
	$( "#edit_plan" ).attr("disabled","disabled");
	$( "#edit_plan" ).html("<option value=''>Loading...</option>");
	$( "#edit_plan" ).load("plans_nz.php?option=edit&id=" + $( "#id" ).val() + "&type=" + $( "#sale_type" ).val() + "&cli=" + $('#edit_cli').val(),
	function() {
		$( "#edit_cli" ).removeAttr("disabled");
		$( "#edit_plan" ).removeAttr("disabled");
		$( "#edit_plan" ).val(plan);
	});
	$( "#original_edit_cli" ).val(cli);
	$.get("verification_submit.php?method=nz_provider", { id: id.val(), cli: cli }, function(data) { $( "#edit_provider" ).val(data); });
	$.get("verification_submit.php?method=nz_ac_number", { id: id.val(), cli: cli }, function(data) { $( "#edit_ac_number" ).val(data); });
	$.get("verification_submit.php?method=nz_adsl_provider", { id: id.val(), cli: cli }, function(data) {
		if (data != "")
		{
			$( "#edit_plan_type" ).val("Bundle");
			$( "#edit_adsl_provider_tr" ).removeAttr("style");
			$( "#edit_adsl_provider" ).val(data);
		}
	});
	$.get("verification_submit.php?method=nz_adsl_ac_number", { id: id.val(), cli: cli }, function(data) {
		if (data != "")
		{
			$( "#edit_plan_type" ).val("Bundle");
			$( "#edit_adsl_ac_number_tr" ).removeAttr("style");
			$( "#edit_adsl_ac_number" ).val(data);
		}
	});
	$( ".validateTips3" ).text("All fields are required");
	$( "#dialog-form3" ).dialog( "open" );
}

function Plan_Dropdown_Edit()
{
	$( "#edit_plan" ).attr("disabled","disabled");
	$( "#edit_plan" ).html("<option value=''>Loading...</option>");
	$( "#edit_plan" ).load("plans_nz.php?option=edit&id=" + $( "#id" ).val() + "&type=" + $( "#sale_type" ).val() + "&cli=" + $('#edit_cli').val(), function() {
		$( "#edit_plan" ).removeAttr("disabled");
	});
}
</script>
<script>
function Plan_Option(option,type)
{
	if (option == "add")
	{
		if (type == "PSTN")
		{
			$( "#adsl_provider" ).val("");
			$( "#adsl_ac_number" ).val("");
			$( "#adsl_provider_tr" ).attr("style","display:none;");
			$( "#adsl_ac_number_tr" ).attr("style","display:none;");
			$( "#plan_type" ).val("PSTN");
		}
		else if (type == "Bundle")
		{
			$( "#adsl_provider" ).val("");
			$( "#adsl_ac_number" ).val("");
			$( "#plan_type" ).val("Bundle");
			$( "#adsl_provider_tr" ).removeAttr("style");
			$( "#adsl_ac_number_tr" ).removeAttr("style");
		}
	}
	else if (option == "edit")
	{
		if (type == "PSTN")
		{
			$( "#edit_adsl_provider" ).val("");
			$( "#edit_adsl_ac_number" ).val("");
			$( "#edit_adsl_provider_tr" ).attr("style","display:none;");
			$( "#edit_adsl_ac_number_tr" ).attr("style","display:none;");
			$( "#edit_plan_type" ).val("PSTN");
		}
		else if (type == "Bundle")
		{
			$( "#edit_adsl_provider" ).val("");
			$( "#edit_adsl_ac_number" ).val("");
			$( "#edit_plan_type" ).val("Bundle");
			$( "#edit_adsl_provider_tr" ).removeAttr("style");
			$( "#edit_adsl_ac_number_tr" ).removeAttr("style");
		}
	}
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
			$( "#packages" ).load('packages_nz.php?id=' + id.val());
		}
	});
}
</script>
<script> //change type
function Change_Type(type)
{
	var id = $( "#id" ),
		verifier = "<?php echo $ac["user"]; ?>";
	
	$.get("verification_submit.php?method=change_type", { id: id.val(), verifier: verifier, type: type }, function(data) {
		if (data == "done")
		{
			$( "#display" ).hide('blind', '' , 'slow', function() {
				$( "#display" ).load('verification_dash_nz.php?id=' + id.val(), function() {
					$( "#packages" ).load('packages_nz.php?id=' + id.val(), function() {
						$( "#display" ).show('blind', '' , 'slow');
					});
				});
			});
		}
		else
		{
			Submit_Error(data);
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
	if (action == "bus_info2")
	{
		var bus_name = $( "#bus_name" ),
			position = $( "#position" );
			
		$.get("../script/submit.php", { id: id, action: action, bus_name: bus_name.val(), position: position.val() },
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
	else if (action == "id_info2")
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
	else if (action == "physical2")
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
	else if (action == "postal2")
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
	else if (action == "mobile2")
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
	else
	{
		N();
	}
}
</script>
<script> //submit
function Submit()
{
	var id = $( "#id" ),
	verifier = "<?php echo $ac["user"]; ?>",
	note = $( "#notes" );
	
	$.get("verification_submit.php?method=submit_nz", { id: id.val(), verifier: verifier, note: note.val() },
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
<!--#########################################################-->
<!--##													   ##-->
<!--##					PHYSICAL ADDRESS				   ##-->
<!--##													   ##-->
<!--#########################################################-->
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
		width: 400,
		height:300,
		modal: true,
		show: "blind",
		hide: "blind",
		buttons: {
			"Submit": function() {
				var building_type = $( "#physical_building_type" ),
					building_number = $( "#physical_building_number" ),
					building_name = $( "#physical_building_name" ),
					street_number = $( "#physical_street_number" ),
					street_name = $( "#physical_street_name" ),
					street_type = $( "#physical_street_type" ),
					suburb = $( "#physical_suburb" ),
					city_town = $( "#physical_city_town" ),
					postcode = $( "#physical_postcode" );
					
				$.get("../source/tlGet.php?type=store", { building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val(), suburb: suburb.val(), city_town: city_town.val(), postcode: postcode.val() }, function(data) {
					$( "#physical" ).val(data);
					$.get("../source/tlGet.php?type=display", { id: data }, function(data2) {
						var n = data2.split("}");
						$( "#display_physical1" ).val(n[0]);
						$( "#display_physical2" ).val(n[1]);
						$( "#display_physical3" ).val(n[2]);
						$( "#display_physical4" ).val(n[3]);
						
						$( "#dialog-confirm_physical" ).dialog("close");
					});
				});
			},
			"Reset": function() {
				$( "#physical_input" ).val("");
				$( ".validateTipsPhysical" ).text("Enter the customer's postcode below.");
				$( "#physical_building_type_tr" ).attr("style","display:none;");
				$( "#physical_building_number_tr" ).attr("style","display:none;");
				$( "#physical_building_name_tr" ).attr("style","display:none;");
				$( "#physical_street_number_tr" ).attr("style","display:none;");
				$( "#physical_street_tr" ).attr("style","display:none;");
				$( "#physical_suburb_tr" ).attr("style","display:none;");
				$( "#physical_city_town_tr" ).attr("style","display:none;");
				$( "#physical_postcode_tr" ).attr("style","display:none;");
				$( "#physical_input_tr" ).removeAttr("style");
				$( "#physical_building_type" ).val("");
				$( "#physical_building_number" ).val("");
				$( "#physical_building_name" ).val("");
				$( "#physical_street_number" ).val("");
				$( "#physical_street_name" ).val("");
				$( "#physical_street_type" ).val("");
				$( "#physical_suburb" ).val("");
				$( "#physical_city_town" ).val("");
				$( "#physical_postcode" ).val("");
			}
		}
	});
});

$(function() {
	$( "#physical_input" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "../source/tlGet.php",
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
		minLength: 2,
		select: function (event, ui) {
			var data = ui.item.id.split(",");
			
			$( "#physical_suburb" ).val(data[0]);
			$( "#physical_city_town" ).val(data[1]);
			$( "#physical_postcode" ).val(data[2]);
			$( "#physical_input_tr" ).attr("style","display:none;");
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
			$( ".validateTipsPhysical" ).text("Enter the customer's address below.");
			$( "#physical_building_type_tr" ).removeAttr("style");
			$( "#physical_building_number_span" ).html("Building Number ");
			$( "#physical_building_number_tr" ).removeAttr("style");
			$( "#physical_building_name_tr" ).removeAttr("style");
			$( "#physical_street_number_tr" ).removeAttr("style");
			$( "#physical_street_tr" ).removeAttr("style");
			$( "#physical_suburb_tr" ).removeAttr("style");
			$( "#physical_city_town_tr" ).removeAttr("style");
			$( "#physical_postcode_tr" ).removeAttr("style");
		}
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
			"Submit": function() {
				var building_type = $( "#mb_building_type" ),
					building_number = $( "#mb_building_number" ),
					suburb = $( "#mb_suburb" ),
					city_town = $( "#mb_city_town" ),
					postcode = $( "#mb_postcode" );
					
				$.get("../source/tlGet.php?type=store", { building_type: building_type.val(), building_number: building_number.val(), suburb: suburb.val(), city_town: city_town.val(), postcode: postcode.val() }, function(data) {
					$( "#postal" ).val(data);
					$.get("../source/tlGet.php?type=display", { id: data }, function(data2) {
						var n = data2.split("}");
						$( "#display_postal1" ).val(n[0]);
						$( "#display_postal2" ).val(n[1]);
						$( "#display_postal3" ).val(n[2]);
						$( "#display_postal4" ).val(n[3]);
						$( "#dialog-confirm_postal2" ).dialog( "close" );
					});
				});
			},
			"Reset": function() {
				$( "#mb_input" ).val("");
				$( ".validateTipsMB" ).text("Enter the customer's postcode below.");
				$( "#mb_building_type_tr" ).attr("style","display:none;");
				$( "#mb_building_number_tr" ).attr("style","display:none;");
				$( "#mb_suburb_tr" ).attr("style","display:none;");
				$( "#mb_city_town_tr" ).attr("style","display:none;");
				$( "#mb_postcode_tr" ).attr("style","display:none;");
				$( "#mb_input_tr" ).removeAttr("style");
				$( "#mb_building_type" ).val("");
				$( "#mb_building_number" ).val("");
				$( "#mb_suburb" ).val("");
				$( "#mb_city_town" ).val("");
				$( "#mb_postcode" ).val("");
			}
		}
	});
});

$(function() {
	$( "#mb_input" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "../source/tlGet.php",
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
		minLength: 2,
		select: function (event, ui) {
			var data = ui.item.id.split(",");
			$( "#mb_suburb" ).val(data[0]);
			$( "#mb_city_town" ).val(data[1]);
			$( "#mb_postcode" ).val(data[2]);
			$( ".validateTipsMB" ).text("Enter the customer's address below.");
			$( "#mb_input_tr" ).attr("style","display:none;");
			$( "#mb_suburb_tr" ).removeAttr("style");
			$( "#mb_city_town_tr" ).removeAttr("style");
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
			"Submit": function() {
				var building_type = $( "#postal_building_type" ),
					building_number = $( "#postal_building_number" ),
					building_name = $( "#postal_building_name" ),
					street_number = $( "#postal_street_number" ),
					street_name = $( "#postal_street_name" ),
					street_type = $( "#postal_street_type" ),
					suburb = $( "#postal_suburb" ),
					city_town = $( "#postal_city_town" ),
					postcode = $( "#postal_postcode" );
					
				$.get("../source/tlGet.php?type=store", { building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val(), suburb: suburb.val(), city_town: city_town.val(), postcode: postcode.val() }, function(data) {
					$( "#postal" ).val(data);
					$.get("../source/tlGet.php?type=display", { id: data }, function(data2) {
						var n = data2.split("}");
						$( "#display_postal1" ).val(n[0]);
						$( "#display_postal2" ).val(n[1]);
						$( "#display_postal3" ).val(n[2]);
						$( "#display_postal4" ).val(n[3]);
						
						$( "#dialog-confirm_postal" ).dialog("close");
					});
				});
			},
			"Reset": function() {
				$( "#postal_input" ).val("");
				$( ".validateTipsPostal" ).text("Enter the customer's postcode below.");
				$( "#postal_building_type_tr" ).attr("style","display:none;");
				$( "#postal_building_number_tr" ).attr("style","display:none;");
				$( "#postal_building_name_tr" ).attr("style","display:none;");
				$( "#postal_street_number_tr" ).attr("style","display:none;");
				$( "#postal_street_tr" ).attr("style","display:none;");
				$( "#postal_suburb_tr" ).attr("style","display:none;");
				$( "#postal_city_town_tr" ).attr("style","display:none;");
				$( "#postal_postcode_tr" ).attr("style","display:none;");
				$( "#postal_input_tr" ).removeAttr("style");
				$( "#postal_building_type" ).val("");
				$( "#postal_building_number" ).val("");
				$( "#postal_building_name" ).val("");
				$( "#postal_street_number" ).val("");
				$( "#postal_street_name" ).val("");
				$( "#postal_street_type" ).val("");
				$( "#postal_suburb" ).val("");
				$( "#postal_city_town" ).val("");
				$( "#postal_postcode" ).val("");
			}
		}
	});
});

$(function() {
	$( "#postal_input" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "../source/tlGet.php",
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
		minLength: 2,
		select: function (event, ui) {
			var data = ui.item.id.split(",");
			
			$( "#postal_suburb" ).val(data[0]);
			$( "#postal_city_town" ).val(data[1]);
			$( "#postal_postcode" ).val(data[2]);
			$( "#postal_input_tr" ).attr("style","display:none;");
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
			$( ".validateTipsPhysical" ).text("Enter the customer's address below.");
			$( "#postal_building_type_tr" ).removeAttr("style");
			$( "#postal_building_number_span" ).html("Building Number ");
			$( "#postal_building_number_tr" ).removeAttr("style");
			$( "#postal_building_name_tr" ).removeAttr("style");
			$( "#postal_street_number_tr" ).removeAttr("style");
			$( "#postal_street_tr" ).removeAttr("style");
			$( "#postal_suburb_tr" ).removeAttr("style");
			$( "#postal_city_town_tr" ).removeAttr("style");
			$( "#postal_postcode_tr" ).removeAttr("style");
		}
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
		$( "#postal_link" ).removeAttr("onclick");
		$( "#postal_link" ).attr("disabled","disabled");
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
		$( "#postal_link" ).attr("onclick", "Postal()");
		$( "#postal_link" ).removeAttr("disabled");
	}
}
</script>

<div id="dialog-form2" title="Add a Package">
<p class="validateTips2">All fields are required</p><br />
<table>
<tr>
<td width="125px">CLI </td>
<td><input type="text" id="cli" onchange="Plan_Dropdown()" style="width:125px;" /></td>
</tr>
<tr>
<td>Plan </td>
<td><input type="hidden" id="plan_type" value="" />
<select id="plan" style="width:210px;">
<option></option>
</select></td>
</tr>
<tr>
<td>Provider </td>
<td><select id="provider" style="width:210px;">
<option></option>
<?php
$q = mysql_query("SELECT value,name FROM vericon.providers ORDER BY name ASC") or die(mysql_error());
while ($providers = mysql_fetch_row($q))
{
	echo "<option value='" . $providers[0] . "'>" . $providers[1] . "</option>";
}
?>
<option>Other</option>
</select></td>
</tr>
<tr>
<td>Account Number </td>
<td><input type="text" size="15" id="ac_number" style="margin-top:0px;" /></td>
</tr>
<tr id="adsl_provider_tr" style="display:none;">
<td>ADSL Provider </td>
<td><select id="adsl_provider" style="width:210px;">
<option></option>
<?php
$q = mysql_query("SELECT value,name FROM vericon.providers ORDER BY name ASC") or die(mysql_error());
while ($providers = mysql_fetch_row($q))
{
	echo "<option value='" . $providers[0] . "'>" . $providers[1] . "</option>";
}
?>
<option>Other</option>
</select></td>
</tr>
<tr id="adsl_ac_number_tr" style="display:none;">
<td>ADSL Account Number </td>
<td><input type="text" size="15" id="adsl_ac_number" style="margin-top:0px;" /></td>
</tr>
</table>
</div>

<div id="dialog-form3" title="Edit Package">
<p class="validateTips3">All fields are required</p><br />
<input type="hidden" id="original_edit_cli" value="" />
<table>
<tr>
<td width="125px">CLI </td>
<td><input type="text" size="15" id="edit_cli" onchange="Plan_Dropdown_Edit()" style="margin-top:0px;" /></td>
</tr>
<tr>
<td>Plan </td>
<td><input type="hidden" id="edit_plan_type" value="" />
<select id="edit_plan" style="width:210px;">
<option></option>
</select></td>
</tr>
<tr>
<td>Provider </td>
<td><select id="edit_provider" style="width:210px;">
<option></option>
<?php
$q = mysql_query("SELECT value,name FROM vericon.providers ORDER BY name ASC") or die(mysql_error());
while ($providers = mysql_fetch_row($q))
{
	echo "<option value='" . $providers[0] . "'>" . $providers[1] . "</option>";
}
?>
<option>Other</option>
</select></td>
</tr>
<tr>
<td>Account Number </td>
<td><input type="text" size="15" id="edit_ac_number" style="margin-top:0px;" /></td>
</tr>
<tr id="edit_adsl_provider_tr" style="display:none;">
<td>ADSL Provider </td>
<td><select id="edit_adsl_provider" style="width:210px;">
<option></option>
<?php
$q = mysql_query("SELECT value,name FROM vericon.providers ORDER BY name ASC") or die(mysql_error());
while ($providers = mysql_fetch_row($q))
{
	echo "<option value='" . $providers[0] . "'>" . $providers[1] . "</option>";
}
?>
<option>Other</option>
</select></td>
</tr>
<tr id="edit_adsl_ac_number_tr" style="display:none;">
<td>ADSL Account Number </td>
<td><input type="text" size="15" id="edit_adsl_ac_number" style="margin-top:0px;" /></td>
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

<div id="dialog-confirm_physical" title="Physical Address">
<p class="validateTipsPhysical">Enter the customer's postcode below.</p><br />
<table width="100%">
<tr id="physical_input_tr">
<td width="50px">Postcode </td>
<td><input type="text" id="physical_input" value="" style="width:250px; height:auto; padding-left:3px;" /></td>
</tr>
</table>
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
<tr id="physical_city_town_tr" style="display:none;">
<td width="80px">City/Town </td>
<td><input type="text" id="physical_city_town" disabled="disabled" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="physical_postcode_tr" style="display:none;">
<td width="80px">Postcode </td>
<td><input type="text" id="physical_postcode" disabled="disabled" value="" style="width:30px; height:auto; padding-left:3px;" /></td>
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

<div id="dialog-confirm_postal2" title="Postal Mail Box">
<p class="validateTipsMB">Enter the customer's postcode below.</p><br />
<table width="100%">
<tr id="mb_input_tr">
<td width="50px">Postcode </td>
<td><input type="text" id="mb_input" value="" style="width:250px; height:auto; padding-left:3px;" /></td>
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
<tr id="mb_city_town_tr" style="display:none;">
<td width="80px">City/Town </td>
<td><input type="text" id="mb_city_town" disabled="disabled" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="mb_postcode_tr" style="display:none;">
<td width="80px">Postcode </td>
<td><input type="text" id="mb_postcode" disabled="disabled" value="" style="width:30px; height:auto; padding-left:3px;" /></td>
</tr>
</table>
</div>

<div id="dialog-confirm_postal" title="Postal Address">
<p class="validateTipsPostal">Enter the customer's postcode below.</p><br />
<table width="100%">
<tr id="postal_input_tr">
<td width="50px">Postcode </td>
<td><input type="text" id="postal_input" value="" style="width:250px; height:auto; padding-left:3px;" /></td>
</tr>
</table>
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
<tr id="postal_city_town_tr" style="display:none;">
<td width="80px">City/Town </td>
<td><input type="text" id="postal_city_town" disabled="disabled" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="postal_postcode_tr" style="display:none;">
<td width="80px">Postcode </td>
<td><input type="text" id="postal_postcode" disabled="disabled" value="" style="width:30px; height:auto; padding-left:3px;" /></td>
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