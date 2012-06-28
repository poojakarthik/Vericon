<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
#physical_address_code { height:120px; margin:0px; overflow-y:auto; border:1px solid black; padding:3px; }
#postal_address_code  { height:120px; margin:0px; overflow-y:auto; border:1px solid black; padding:3px; }
ui-dialog { padding: .3em; }
.ui-dialog_physical { padding: .3em; }
.ui-dialog_physical_confirm { padding: .3em; }
.ui-dialog_postal { padding: .3em; }
.ui-dialog_postal_mailbox { padding: .3em; }
.ui-dialog_postal_confirm { padding: .3em; }
.ui-dialog_postal_confirm_switch { padding: .3em; }
.ui-state-highlight { padding: .3em; }
.validateTips { border: 1px solid transparent; padding: 0.3em; }
.validateTips2 { border: 1px solid transparent; padding: 0.3em; }
.validateTips3 { border: 1px solid transparent; padding: 0.3em; }
.validateTips4 { border: 1px solid transparent; padding: 0.3em; }
.sale_submitted { border: 1px solid transparent; padding: 0.3em; }
.validateTipsPhysical { border: 1px solid transparent; padding: 0.3em; }
.validateTipsPostal { border: 1px solid transparent; padding: 0.3em; }
.validateTipsMB { border: 1px solid transparent; padding: 0.3em; }
.ui-autocomplete { max-height: 300px; overflow-y: auto; overflow-x: hidden; padding-right: 20px; }
.ui-autocomplete-loading { background: white url('../images/ajax-loader.gif') right center no-repeat; }
.ui-widget-overlay {z-index: 3999; }
div#users-contain table { margin: 1em 0; border-collapse: collapse; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
</style>
<script> //init form
function Get_Sale()
{
	var id = $( "#id" ),
	centre = "<?php echo $ac["centre"]; ?>";
	
	$( ".error" ).html('<img src="../images/ajax-loader.gif" />');
	
	$.get("form_submit.php?method=get", { id: id.val(), centre: centre}, function(data) {
		if (data == "valid")
		{
			$( ".id2" ).html(id.val());
			$( "#dialog-form" ).dialog( "open" );
			$( ".error" ).html('');
		}
		else
		{
			$( ".error" ).html(data);
		}
	});
}
</script>
<script> //load form
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var tips = $( ".validateTips" );

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
		height: 225,
		width: 300,
		modal: true,
		resizable: false,
		draggable: false,
		show: "blind",
		hide: "blind",
		buttons: {
			"Load Form": function() {
				var id = $( "#id" ),
					agent = "<?php echo $ac["user"]; ?>",
					centre = "<?php echo $ac["centre"]; ?>",
					campaign = $( "#campaign" ),
					type = $( "#type" );

				if (campaign.val() == "")
				{
					updateTips("Select a campaign!");
				}
				else if (type.val() == "")
				{
					updateTips("Select a type!");
				}
				else
				{
					$.get("form_submit.php?method=load", { id: id.val(), agent: agent, centre: centre, campaign: campaign.val(), type: type.val() },
					function(data) {
						if (data == "valid")
						{
							var options = {};
							$( "#lead_id" ).val(id.val());
							$( "#dialog-form" ).dialog( "close" );
							$( "#display" ).hide('blind', options , 'slow', function() {
								$( "#display" ).load("form_display.php?method=form&user=<?php echo $ac["user"]; ?>&id=" + id.val(),
								function() {
									$( "#display" ).show('blind', options , 'slow');
								});
							});
						}
						else
						{
							updateTips("Error! Please contact your Administrator!");
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
				var id = $( "#lead_id" ),
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
					$.get("form_submit.php?method=add", { id: id.val(), cli: cli.val(), plan: plan.val() },
					function(data) {
						if (data == "added")
						{
							$( "#packages" ).load('packages.php?id=' + id.val());
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
		},
		close: function() {
		}
	});
});

function Add_Package()
{
	$( "#cli" ).val("");
	$( "#plan" ).val("");
	$( "#validateTips2" ).text("All fields are required");
	$( "#dialog-form2" ).dialog( "open" );
}

function Plan_Dropdown()
{
	$( "#plan" ).val("");
	$( "#plan" ).load("plans.php?type=" + $( "#sale_type" ).val() + "&cli=" + $('#cli').val());
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
				var id = $( "#lead_id" ),
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
					$.get("form_submit.php?method=edit", { id: id.val(), cli: cli.val(), plan: plan.val(), cli2: cli2.val() },
					function(data) {
						if (data == "editted")
						{
							$( "#packages" ).load('packages.php?id=' + id.val());
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
		},
		close: function() {
		}
	});
});

function Edit_Package(cli,plan)
{
	$( "#edit_cli" ).val(cli);
	$( "#edit_plan" ).load("plans.php?type=" + $( "#sale_type" ).val() + "&cli=" + $('#edit_cli').val(), function() {
		$( "#edit_plan" ).val(plan);
	});
	$( "#original_edit_cli" ).val(cli);
	$( "#dialog-form3" ).dialog( "open" );
}

function Plan_Dropdown_Edit()
{
	$( "#edit_plan" ).load("plans.php?type=" + $( "#sale_type" ).val() + "&cli=" + $('#edit_cli').val());
}
</script>
<script> //delete packages
function Delete_Package(cli)
{
	var id = $( "#lead_id" ),
		cli = cli;
	
	$.get("form_submit.php?method=delete", { id: id.val(), cli: cli},
	function(data) {
		if (data == "deleted")
		{
			$( "#packages" ).load('packages.php?id=' + id.val());
		}
	});
}
</script>
<script> //cancel form
function Cancel()
{
	var id = $( "#lead_id" );
	var centre = "<?php echo $ac["centre"]; ?>";
	
	$.get("form_submit.php?method=cancel", { id: id.val(), centre: centre},
	function(data) {
		$( "#display" ).hide('blind', '' , 'slow', function() {
			window.location = "form.php";
		});
	});
}
</script>
<script> //submit form
function Submit()
{
	var id = $( "#lead_id" ),
		agent = "<?php echo $ac["user"]; ?>",
		centre = "<?php echo $ac["centre"]; ?>",
		campaign = $( ".campaign" ),
		type = $( "#sale_type" ),
		title = $( "#title" ),
		first = $( "#first" ),
		middle = $( "#middle" ),
		last = $( "#last" ),
		dob = $( "#datepicker" ),
		email = $( "#email" ),
		mobile = $( "#mobile" ),
		physical = $( "#physical" ),
		postal = $( "#postal" ),
		id_type = $( "#id_type" ),
		id_num = $( "#id_num" ),
		abn = $( "#abn" ),
		abn_status = $( ".abn_status" ),
		position = $( "#position" );
		
		if ($('#postal_same').attr('checked'))
		{
			postal = $( "#physical" );
		}
	
	$.get("form_submit.php?method=submit", { id: id.val(), agent: agent, centre: centre, campaign: campaign.html(), type: type.val(), title: title.val(), first: first.val(), middle: middle.val(), last: last.val(), dob: dob.val(), email: email.val(), mobile: mobile.val(), physical: physical.val(), postal: postal.val(), id_type: id_type.val(), id_num: id_num.val(), abn: abn.val(), abn_status: abn_status.html(), position: position.val()},
	function(data) {
		if (data.substring(0,9) == "submitted")
		{
			$( ".sale_submitted" ).html(data.substring(9));
			$( "#dialog-form5" ).dialog( "open" );
		}
		else
		{
			$( ".validateTips4" ).html(data);
			$( "#dialog-form4" ).dialog( "open" );
		}
	});
}
</script>
<script> //error dialog
$(function() {
	$( "#dialog:ui-dialog4" ).dialog( "destroy" );
	
	$( "#dialog-form4" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:250,
		height:100,
		modal: true,
		show: "blind",
		hide: "blind"
	});
});
</script>
<script> //sale submitted confirm
$(function() {
	$( "#dialog:ui-dialog5" ).dialog( "destroy" );
	
	$( "#dialog-form5" ).dialog({
	autoOpen: false,
	resizable: false,
	draggable: false,
	width:250,
	height:100,
	modal: true,
	show: "blind",
	hide: "blind",
	close: function() {
			$( "#display" ).hide('blind', '' , 'slow', function() {
				window.location = "form.php";
			});
		}
	});
});
</script>
<script> //get ABN
function getABN(){
	$.getJSON("../source/abrGet.php", {abn: $("#abn").val() },
	function(data){
		if (data['error'] == "true")
		{
			$(".bus_name").html( "" );
			$(".abn_status").html( "" );
			$(".bus_type").html( "" );
		}
		else 
		{
			var abn = $("#abn").val();
			$("#abn").val( abn.replace(/\s/g,""));
			if( data['organisationName'] != null) {
				$(".bus_name").html( data['organisationName'] );
			}
			else if (data['tradingName'] != null) {
				$(".bus_name").html( data['tradingName'] );
			}
			else {
				$(".bus_name").html( data['entityName'] );
			}
			$(".abn_status").html( data['entityStatus'] );
			$(".bus_type").html( data['entityDescription'] );
		}
	});
}
</script>
<script>
function Email()
{
	if ($('#no_email').attr('checked'))
	{
		$( "#email" ).val("N/A");
		$( "#email" ).attr("disabled", true);
	}
	else
	{
		$( "#email" ).val("");
		$( "#email" ).removeAttr("disabled");
	}
}

function Mobile()
{
	if ($('#no_mobile').attr('checked'))
	{
		$( "#mobile" ).val("N/A");
		$( "#mobile" ).attr("disabled", true);
	}
	else
	{
		$( "#mobile" ).val("");
		$( "#mobile" ).removeAttr("disabled");
	}
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
										$.get("../source/gnafGet.php?type=test", { input: data0 }, function(data2) {
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

function OTH_Physical()
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
	$( "#physical_building_name_tr" ).removeAttr("style");
	$( "#physical_building_type_tr" ).removeAttr("style");
	$( "#physical_building_number_span" ).html("Building Number ");
	$( "#physical_building_number_tr" ).removeAttr("style");
	$( "#physical_street_number_tr" ).removeAttr("style");
	$( "#physical_street_tr" ).removeAttr("style");
	$( "#physical_suburb_tr" ).removeAttr("style");
	$( "#physical_state_tr" ).removeAttr("style");
	$( "#physical_postcode_tr" ).removeAttr("style");
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
			  l_pid : $('#physical_locality_pid').val(),
			  street_name : $( "#physical_street_name" ).val(),
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
	var building_type = $( "#physical_building_type" ),
		building_number = $( "#physical_building_number" ),
		building_name = $( "#physical_building_name" ),
		street_number = $( "#physical_street_number" ),
		street_name = $( "#physical_street_name" ),
		street_type = $( "#physical_street_type" ),
		l_pid = $('#physical_locality_pid');
	
	$( "#physical_address_code" ).attr("style","display:none;");
	$( "#physical_manual_store" ).attr('style','display:none;');
	$( "#physical_search_div" ).html("<center><br><br><br><br><p><img src='../images/ajax-loader.gif'>&nbsp;&nbsp;&nbsp;&nbsp;Please wait. Saving your address...</p></center>");
	$( "#physical_search_div" ).removeAttr('style');
		

	$.get("../source/gnafGet.php?type=manual", { l_pid: l_pid.val(), building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data) {
		$( "#physical" ).val(data);
		$.get("../source/gnafGet.php?type=display", { id: data }, function(data2) {
			var n = data2.split("}");
			$( "#display_physical1" ).val(n[0]);
			$( "#display_physical2" ).val(n[1]);
			$( "#display_physical3" ).val(n[2]);
			$( "#display_physical4" ).val(n[3]);
			$( "#dialog-confirm_physical2" ).dialog( "close" );
			$( "#dialog-confirm_physical" ).dialog( "close" );
		});
	});
}

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
										$.get("../source/gnafGet.php?type=test", { input: data0 }, function(data2) {
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

function OTH_Postal()
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
	$( "#postal_building_name_tr" ).removeAttr("style");
	$( "#postal_building_type_tr" ).removeAttr("style");
	$( "#postal_building_number_span" ).html("Building Number ");
	$( "#postal_building_number_tr" ).removeAttr("style");
	$( "#postal_street_number_tr" ).removeAttr("style");
	$( "#postal_street_tr" ).removeAttr("style");
	$( "#postal_suburb_tr" ).removeAttr("style");
	$( "#postal_state_tr" ).removeAttr("style");
	$( "#postal_postcode_tr" ).removeAttr("style");
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
			  l_pid : $('#postal_locality_pid').val(),
			  street_name : $( "#postal_street_name" ).val(),
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
	var building_type = $( "#postal_building_type" ),
		building_number = $( "#postal_building_number" ),
		building_name = $( "#postal_building_name" ),
		street_number = $( "#postal_street_number" ),
		street_name = $( "#postal_street_name" ),
		street_type = $( "#postal_street_type" ),
		l_pid = $('#postal_locality_pid');
	
	$( "#postal_address_code" ).attr("style","display:none;");
	$( "#postal_manual_store" ).attr('style','display:none;');
	$( "#postal_search_div" ).html("<center><br><br><br><br><p><img src='../images/ajax-loader.gif'>&nbsp;&nbsp;&nbsp;&nbsp;Please wait. Saving your address...</p></center>");
	$( "#postal_search_div" ).removeAttr('style');
		

	$.get("../source/gnafGet.php?type=manual", { l_pid: l_pid.val(), building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data) {
		$( "#postal" ).val(data);
		$.get("../source/gnafGet.php?type=display", { id: data }, function(data2) {
			var n = data2.split("}");
			$( "#display_postal1" ).val(n[0]);
			$( "#display_postal2" ).val(n[1]);
			$( "#display_postal3" ).val(n[2]);
			$( "#display_postal4" ).val(n[3]);
			$( "#dialog-confirm_postal3" ).dialog( "close" );
			$( "#dialog-confirm_postal" ).dialog( "close" );
		});
	});
}
</script>
<script>
function Postal_Same()
{
	if ($('#postal_same').attr('checked'))
	{
		$( "#display_postal1" ).val("SAME AS ABOVE");
		$( "#display_postal2" ).val("");
		$( "#display_postal3" ).val("");
		$( "#display_postal4" ).val("");
		$( "#display_postal1" ).attr("disabled","disabled");
		$( "#display_postal2" ).attr("disabled","disabled");
		$( "#display_postal3" ).attr("disabled","disabled");
		$( "#display_postal4" ).attr("disabled","disabled");
		$( "#postal_link" ).removeAttr("onclick");
		$( "#postal_link" ).removeAttr("style");
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
		$( "#postal_link" ).attr("style", "cursor:pointer; text-decoration:underline;");
	}
}
</script>

<div id="dialog-form" title="Sale Details">
<p class="validateTips">Please select a campaign and sale type</p><br />
<table>
<tr>
<td width="60px">Lead ID </td>
<td><b><p class="id2"></p></b></td>
</tr>
<tr>
<td>Agent </td>
<td><b><?php echo $ac["user"] . " (" . $ac["alias"] . ")"; ?></b></td>
</tr>
<tr>
<td>Centre </td>
<td><b><?php echo $ac["centre"]; ?></b></td>
</tr>
<tr>
<td>Campaign </td>
<td><select id="campaign" style="width:120px; height:auto; padding:0px; margin:0px;">
<option></option>
<?php
$q = mysql_query("SELECT campaign FROM vericon.centres WHERE centre = '$ac[centre]'") or die(mysql_error());
$cam = mysql_fetch_row($q);
$campaign = explode(",",$cam[0]);
for ($i = 0; $i < count($campaign); $i++)
{
	echo "<option>" . $campaign[$i] . "</option>";
}
?>
</select></td>
</tr>
<tr>
<td>Type </td>
<td><select id="type" style="width:120px; height:auto; padding:0px; margin:0;">
<option></option>
<option>Business</option>
<option>Residential</option>
</select></td>
</tr>
</table>
</div>

<div id="dialog-form2" title="Add a Package">
<p class="validateTips2">All fields are required</p><br />
<table>
<tr>
<td width="50px">CLI </td>
<td><input type="text" size="15" id="cli" onchange="Plan_Dropdown()" style="margin-top:0px;" /></td>
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
<td><select id="edit_plan" style="margin-left:0px; width:210px; height:25px; padding:1px 0 0;">
<option></option>
</select></td>
</tr>
</table>
</div>

<div id="dialog-form4" title="Error">
<span class='ui-icon ui-icon-alert' style='float:left; margin-right:.3em; margin-top:4px'></span><p class="validateTips4"></p>
</div>

<div id="dialog-form5" title="Sale Submitted">
<p class="sale_submitted"></p>
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
<center><table>
<tr id="physical_type_tr" style="display:none;">
<td><input type="radio" name="physical_type" value="FS" onclick="FS_Physical()" style="height:auto; margin:0 3px;" />Freestanding Premises</td>
<td><input type="radio" name="physical_type" value="OB" onclick="OB_Physical()" style="height:auto; margin:0 3px;" />Office Building</td>
<td><input type="radio" name="physical_type" value="BU" onclick="BU_Physical()" style="height:auto; margin:0 3px;" />Flat, Unit or Apartment</td>
<td><input type="radio" name="physical_type" value="LOT" onclick="LOT_Physical()" style="height:auto; margin:0 3px;" />Lot</td>
<td><input type="radio" name="physical_type" value="OTH" onclick="OTH_Physical()" style="height:auto; margin:0 3px;" />Other</td>
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
<td><select id="mb_building_type" style="width:95px; height:auto; padding:0px; margin:0px;">
<option></option>
<option>GPO BOX</option>
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
<center><table>
<tr id="postal_type_tr" style="display:none;">
<td><input type="radio" name="postal_type" value="FS" onclick="FS_Postal()" style="height:auto; margin:0 3px;" />Freestanding Premises</td>
<td><input type="radio" name="postal_type" value="OB" onclick="OB_Postal()" style="height:auto; margin:0 3px;" />Office Building</td>
<td><input type="radio" name="postal_type" value="BU" onclick="BU_Postal()" style="height:auto; margin:0 3px;" />Flat, Unit or Apartment</td>
<td><input type="radio" name="postal_type" value="LOT" onclick="LOT_Postal()" style="height:auto; margin:0 3px;" />Lot</td>
<td><input type="radio" name="postal_type" value="OTH" onclick="OTH_Postal()" style="height:auto; margin:0 3px;" />Other</td>
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

<input type="hidden" id="lead_id" value="" />
<div id="display">
<script>
$( "#display" ).load("form_display.php?method=init&user=<?php echo $ac["user"]; ?>",
function() {
	$( "#display" ).show('blind', '' , 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>