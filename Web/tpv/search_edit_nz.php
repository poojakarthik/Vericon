<?php
mysql_connect('localhost','vericon','18450be');

$q = mysql_query("SELECT user FROM vericon.currentuser WHERE hash = '" . $_COOKIE["hash"] . "'") or die(mysql_error());
$user = mysql_fetch_row($q);

$q1 = mysql_query("SELECT * FROM vericon.auth WHERE user = '$user[0]'") or die(mysql_error());
$ac = mysql_fetch_assoc($q1);

$id = $_GET["id"];
$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
$data = mysql_fetch_assoc($q);

$q2 = mysql_query("SELECT * FROM vericon.auth WHERE user = '$data[agent]'") or die(mysql_error());
$data2 = mysql_fetch_assoc($q2);

$q3 = mysql_query("SELECT id FROM vericon.campaigns WHERE campaign = '" . mysql_real_escape_string($data["campaign"]) . "'") or die(mysql_error());
$c = mysql_fetch_row($q3);
$c_id = $c[0];

if ($data["status"] == "Line Issue")
{
	$status_text = "line_issue";
}
else
{
	$status_text = strtolower($data["status"]);
}
?>

<input type="hidden" id="id" value="<?php echo $data["id"]; ?>" />
<input type="hidden" id="sale_type" value="<?php echo $data["type"]; ?>" />

<style>
.ui-dialog_submit { padding: .3em; }
.ui-dialog2 { padding: .3em; }
.ui-dialog3 { padding: .3em; }
.ui-dialog4 { padding: .3em; }
.validateTips2 { border: 1px solid transparent; padding: 0.3em; }
.validateTips3 { border: 1px solid transparent; padding: 0.3em; }
.validateTips4 { border: 1px solid transparent; padding: 0.3em; }
.ui-dialog_physical { padding: .3em; }
.ui-dialog_postal { padding: .3em; }
.ui-dialog_postal_mailbox { padding: .3em; }
.ui-dialog_postal_confirm_switch { padding: .3em; }
.validateTipsPhysical { border: 1px solid transparent; padding: 0.3em; }
.validateTipsPostal { border: 1px solid transparent; padding: 0.3em; }
.validateTipsMB { border: 1px solid transparent; padding: 0.3em; }
.ui-autocomplete { max-height: 300px; overflow-y: auto; overflow-x: hidden; padding-right: 20px; }
.ui-autocomplete-loading { background: white url('../images/ajax-loader.gif') right center no-repeat; }
</style>
<script> //notes button
$(function() {
	$( "#dialog:ui-dialog_notes" ).dialog( "destroy" );
	
	$( "#dialog-form_notes" ).dialog({
		autoOpen: false,
		height: 200,
		width: 425,
		modal: true,
		resizable: false,
		draggable: false,
		show: "blind",
		hide: "blind"
	});
});

function Notes()
{
	var id = "<?php echo $id; ?>";
	
	$.get("search_submit.php", { method: "notes", id: id }, function(data) {
		$( "#notes_display" ).val(data);
	});
	$( "#dialog-form_notes" ).dialog( "open" );
}
</script>
<script> //dob datepicker
$(function() {
	$( "#datepicker" ).datepicker( {
		showOn: "button",
		buttonImage: "../images/calendar.png",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		firstDay: 1,
		showOtherMonths: true,
		selectOtherMonths: true,
		altField: "#datepicker2",
		altFormat: "dd/mm/yy",
		changeMonth: true,
		changeYear: true,
		maxDate: "-216M",
		yearRange: "-100Y:-18Y"
	});
});
</script>
<script>
if ( $( "#mobile" ).val() == "N/A" )
{
	$( "#no_mobile" ).prop("checked", true);
	$( "#mobile" ).attr("disabled", "disabled");
}

if ( $( "#email" ).val() == "N/A" )
{
	$( "#no_email" ).prop("checked", true);
	$( "#email" ).attr("disabled", "disabled");
}

$.get("../source/tlGet.php?type=display", { id: "<?php echo $data["physical"]; ?>" }, function(data) {
	var n = data.split("}");
	$( "#display_physical1" ).val(n[0]);
	$( "#display_physical2" ).val(n[1]);
	$( "#display_physical3" ).val(n[2]);
	$( "#display_physical4" ).val(n[3]);
});

if ("<?php echo $data["physical"]; ?>" == "<?php echo $data["postal"]; ?>")
{
	$( "#display_postal1" ).val("SAME AS ABOVE");
	$( "#postal_same" ).prop("checked", true);
	$( "#display_postal1" ).attr("disabled", "disabled");
	$( "#display_postal2" ).attr("disabled", "disabled");
	$( "#display_postal3" ).attr("disabled", "disabled");
	$( "#display_postal4" ).attr("disabled", "disabled");
	$( "#postal_link" ).removeAttr("onclick");
	$( "#postal_link" ).removeAttr("style");
}
else
{
	$.get("../source/tlGet.php?type=display", { id: "<?php echo $data["postal"]; ?>" }, function(data) {
		var n = data.split("}");
		$( "#display_postal1" ).val(n[0]);
		$( "#display_postal2" ).val(n[1]);
		$( "#display_postal3" ).val(n[2]);
		$( "#display_postal4" ).val(n[3]);
	});
}
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
		height: 240,
		width: 350,
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
					provider = $( "#provider" ),
					ac_number = $( "#ac_number" );
				
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
					$.get("search_submit.php?method=add_nz", { id: id.val(), cli: cli.val(), plan: plan.val(), provider: provider.val(), ac_number: ac_number.val() },
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
	$( ".validateTips2" ).text("All fields are required");
	$( "#dialog-form2" ).dialog( "open" );
}

function Plan_Dropdown()
{
	$( "#plan" ).attr("disabled","disabled");
	$( "#plan" ).html("<option value=''>Loading...</option>");
	$( "#plan" ).load("plans_nz.php?id=" + $( "#id" ).val() + "&type=" + $( "#sale_type" ).val() + "&cli=" + $('#cli').val(), function() {
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
		height: 240,
		width: 350,
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
					provider = $( "#edit_provider" ),
					ac_number = $( "#edit_ac_number" ),
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
					$.get("search_submit.php?method=edit_nz", { id: id.val(), cli: cli.val(), plan: plan.val(), provider: provider.val(), ac_number: ac_number.val(), cli2: cli2.val() },
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
	$( "#edit_cli" ).attr("disabled","disabled");
	$( "#edit_plan" ).attr("disabled","disabled");
	$( "#edit_plan" ).html("<option value=''>Loading...</option>");
	$( "#edit_plan" ).load("plans_nz.php?id=" + $( "#id" ).val() + "&type=" + $( "#sale_type" ).val() + "&cli=" + $('#edit_cli').val(),
	function() {
		$( "#edit_cli" ).removeAttr("disabled");
		$( "#edit_plan" ).removeAttr("disabled");
		$( "#edit_plan" ).val(plan);
	});
	$.get("search_submit.php?method=nz_provider", { id: id.val(), cli: cli }, function(data) { $( "#edit_provider" ).val(data); });
	$.get("search_submit.php?method=nz_ac_number", { id: id.val(), cli: cli }, function(data) { $( "#edit_ac_number" ).val(data); });
	$( "#original_edit_cli" ).val(cli);
	$( ".validateTips3" ).text("All fields are required");
	$( "#dialog-form3" ).dialog( "open" );
}

function Plan_Dropdown_Edit()
{
	$( "#edit_plan" ).attr("disabled","disabled");
	$( "#edit_plan" ).html("<option value=''>Loading...</option>");
	$( "#edit_plan" ).load("plans_nz.php?id=" + $( "#id" ).val() + "&type=" + $( "#sale_type" ).val() + "&cli=" + $('#edit_cli').val(), function() {
		$( "#edit_plan" ).removeAttr("disabled");
	});
}
</script>
<script> //delete packages
function Delete_Package(cli)
{
	var id = $( "#id" );
	
	$.get("search_submit.php?method=delete", { id: id.val(), cli: cli},
	function(data) {
		if (data == "deleted")
		{
			$( "#packages" ).load('packages_nz.php?id=' + id.val());
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
<script>
function Submit_Details()
{
	var id = $( "#id" ),
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
		bus_name = $( "#bus_name" ),
		position = $( "#position" ),
		user = "<?php echo $ac["user"]; ?>",
		method = $( "#method" ),
		query = $( "#query" );
				
		if ($('#postal_same').attr('checked'))
		{
			postal = $( "#physical" );
		}
	
	$.get("search_submit.php?method=submit_nz", { id: id.val(), title: title.val(), first: first.val(), middle: middle.val(), last: last.val(), dob: dob.val(), email: email.val(), mobile: mobile.val(), physical: physical.val(), postal: postal.val(), id_type: id_type.val(), id_num: id_num.val(), bus_name: bus_name.val(), position: position.val(), user: user },
	function(data) {
		if (data == "submitted")
		{
			$( "#display" ).hide('blind', '', 'slow', function() {
				$( "#display" ).load('search_display.php', function() {
					$( "#results" ).load("search_results.php?method=" +  method.val() + "&query=" + query.val(), function() {
						$( "#display" ).show('blind', '', 'slow');
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

<div id="dialog-form_notes" title="Notes">
<textarea disabled="disabled" id="notes_display" style="width:400px; height:150px; resize:none;">
</textarea>
</div>

<div id="dialog-form2" title="Add a Package">
<p class="validateTips2">All fields are required</p><br />
<table>
<tr>
<td width="95px">CLI </td>
<td><input type="text" size="15" id="cli" onchange="Plan_Dropdown()" style="margin-top:0px;" /></td>
</tr>
<tr>
<td>Plan </td>
<td><select id="plan" style="width:210px;">
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
</table>
</div>

<div id="dialog-form3" title="Edit Package">
<p class="validateTips3">All fields are required</p><br />
<input type="hidden" id="original_edit_cli" value="" />
<table>
<tr>
<td width="95px">CLI </td>
<td><input type="text" size="15" id="edit_cli" onchange="Plan_Dropdown_Edit()" style="margin-top:0px;" /></td>
</tr>
<tr>
<td>Plan </td>
<td><select id="edit_plan" style="margin-left:0px; width:210px;">
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
</table>
</div>

<div id="dialog-form_submit" title="Error!">
<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><span class="submit_error"></span></p>
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

<table border="0" width="100%">
<tr>
<td width="50%" valign="top">
<table border="0" width="100%" style="margin-bottom:10px;">
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
<td width="85px">Lead ID </td>
<td><b><?php echo $data["lead_id"]; ?></b></td>
</tr>
<tr>
<td>Agent </td>
<td><b><?php echo $data2["first"] . " " . $data2["last"] . " (" . $data2["user"] . ")"; ?></b></td>
</tr>
<tr>
<td>Centre </td>
<td><b><?php echo $data["centre"]; ?></b></td>
</tr>
<tr>
<td>Campaign </td>
<td><b><?php echo $data["campaign"]; ?></b></td>
</tr>
<tr>
<td>Type </td>
<td><b><?php echo $data["type"]; ?></b></td>
</tr>
</table>
</td>
<td width="50%" height="100%" valign="top">
<table border="0" width="100%" style="margin-bottom:10px;">
<?php
if ($data["type"] == "Business")
{
?>
<tr>
<td colspan="2"><img src="../images/business_identification_header.png" width="164" height="15" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="90%" height="9" alt="line" /></td>
</tr>
<tr>
<td width="85px">Business Name </td>
<td><input type="text" id="bus_name" style="width:225px;" value="<?php echo $data["bus_name"]; ?>" /></td>
</tr>
<tr>
<td>Position </td>
<td><input type="text" id="position" style="width:225px;" value="<?php echo $data["position"]; ?>" /></td>
</tr>
<?php
}
elseif ($data["type"] == "Residential")
{
	switch ($data["id_type"])
	{
		case "Driver's Licence (NZ)":
		$dsl="selected";
		break;
		case "Gold Card":
		$gdc="selected";
		break;
		case "Community Services Card":
		$csc="selected";
		break;
		case "Passport":
		$ppt="selected";
		break;
	}
?>
<tr>
<td colspan="2"><img src="../images/customer_identification_header.png" width="172" height="15" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="90%" height="9" alt="line" /></td>
</tr>
<tr>
<td width="85px">ID Type </td>
<td><select id="id_type" style="width:192px;">
<option <?php echo $csc; ?>>Community Services Card</option>
<option <?php echo $dsl; ?>>Driver's Licence (NZ)</option>
<option <?php echo $gdc; ?>>Gold Card</option>
<option <?php echo $ppt; ?>>Passport</option>
</select></td>
</tr>
<tr>
<td>ID Number </td>
<td><input type="text" id="id_num" style="width:190px;" value="<?php echo $data["id_num"]; ?>" /></td>
</tr>
<?php
}

switch ($data["title"])
{
	case "Mr":
	$mr="selected";
	break;
	case "Mrs":
	$mrs="selected";
	break;
	case "Miss":
	$miss="selected";
	break;
	case "Ms":
	$ms="selected";
	break;
	case "Dr":
	$dr="selected";
	break;
}
?>
</table>
</td>
</tr>
<tr>
<td width="50%" valign="top">
<table border="0" width="100%" style="margin-bottom:10px;">
<tr>
<td colspan="2"><img src="../images/customer_details_header.png" width="128" height="15" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="80%" height="9" alt="line" /></td>
</tr>
<tr>
<td width="85px">Title </td>
<td><select id="title" style="width:50px;">
<option <?php echo $mr; ?>>Mr</option>
<option <?php echo $mrs; ?>>Mrs</option>
<option <?php echo $miss; ?>>Miss</option>
<option <?php echo $ms; ?>>Ms</option>
<option <?php echo $dr; ?>>Dr</option>
</select></td>
</tr>
<tr>
<td>First Name </td>
<td><input type="text" id="first" style="width:150px;" value="<?php echo $data["firstname"]; ?>" /></td>
</tr>
<tr>
<td>Middle Name </td>
<td><input type="text" id="middle" style="width:150px;" value="<?php echo $data["middlename"]; ?>" /></td>
</tr>
<tr>
<td>Last Name </td>
<td><input type="text" id="last" style="width:150px;" value="<?php echo $data["lastname"]; ?>" /></td>
</tr>
<tr>
<td>D.O.B </td>
<td><input type="text" id="datepicker2" readonly style="width:80px;" value="<?php echo date("d/m/Y", strtotime($data["dob"])); ?>" /> <input type="hidden" id="datepicker" value="<?php echo date("Y-m-d", strtotime($data["dob"])); ?>" /></td>
</tr>
<tr>
<td>E-Mail </td>
<td><input type="text" id="email" style="width:150px;" value="<?php echo $data["email"]; ?>" /> <input type="checkbox" id="no_email" onclick="Email()" style="height:auto;" /> N/A</td>
</tr>
<tr>
<td>Mobile </td>
<td><input type="text" id="mobile" style="width:150px;" value="<?php echo $data["mobile"]; ?>" /> <input type="checkbox" id="no_mobile" onclick="Mobile()" style="height:auto;" /> N/A</td>
</tr>
</table>
</td>
<td width="50%" height="100%" valign="top">
<table border="0" width="100%" height="100%" style="margin-bottom:10px;">
<tr valign="top">
<td>
<table border="0" width="100%">
<tr>
<td colspan="2"><img src="../images/customer_address_header.png" width="136" height="15" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="90%" height="9" alt="line" /></td>
</tr>
<tr>
<td width="85px"><a onclick="Physical()" style="cursor:pointer; text-decoration:underline;">Physical</a> </td>
<td><input type="text" id="display_physical1" style="width:225px;" readonly /></td>
</tr>
<tr>
<td><input type="hidden" id="physical" value="<?php echo $data["physical"]; ?>" /></td>
<td><input type="text" id="display_physical2" style="width:225px;" readonly /></td>
</tr>
<tr>
<td></td>
<td><input type="text" id="display_physical3" style="width:164px;" readonly /> <input type="text" id="display_physical4" style="width:55px;" readonly /></td>
</tr>
<tr>
<td width="85px"><a id="postal_link" onclick="Postal()" style="cursor:pointer; text-decoration:underline;">Postal</a> </td>
<td><input type="text" id="display_postal1" readonly style="width:225px;" /></td>
</tr>
<tr>
<td><input type="hidden" id="postal" value="<?php echo $data["postal"]; ?>" /></td>
<td><input type="text" id="display_postal2" style="width:225px;" readonly /></td>
</tr>
<tr>
<td></td>
<td><input type="text" id="display_postal3" style="width:164px;" readonly /> <input type="text" id="display_postal4" style="width:55px; margin-right:12px;" readonly /></td>
<tr>
<td></td>
<td><input type="checkbox" id="postal_same" onclick="Postal_Same()" style="height:auto;" /> Same as Above</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<tr>
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
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="99%" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th width="12%">CLI</th>
<th width="30%">Plan</th>
<th width="28%">Provider</th>
<th width="20%">Account Number</th>
<th width="10%" colspan="2" style="text-align:center;">Edit</th>
</tr>
</thead>
<tbody id="packages">
</tbody>
</table>
</div></center>
</td>
</tr>
<tr valign="bottom">
<td align="left" style="padding-left:5px;"><button onclick="Add_Package()" class="btn">Add Package</button> <button onclick="Notes()" class="btn">Notes</button></td>
<td align="right" style="padding-right:5px;"><button onclick="Submit_Details()" class="btn">Submit</button> <button onclick="Cancel_Search()" class="btn_red">Cancel</button></td>
</tr>
</table>
</td>
</tr>
</table>