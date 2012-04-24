<style>
.submitform
{
	background-image:url('../images/submit_form_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
	margin-right:10px;
}

.submitform:hover
{
	background-image:url('../images/submit_form_btn_hover.png');
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
}

.addpackage:hover
{
	background-image:url('../images/add_package_btn_hover.png');
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
	$( "#dialog:ui-dialog2" ).dialog( "destroy" );
	
	var id = "<?php echo $_GET["id"]; ?>",
		cli = $( "#cli" ),
		plan = $( "#plan" ),
		tips = $( ".error3" );

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
					$.get("../tpv/verification_submit.php?method=add", { id: id, cli: cli.val(), plan: plan.val() },
					function(data) {
						if (data == "added")
						{
							$( "#packages" ).load('../tpv/packages.php?id=' + id);
							$( "#cli" ).val("");
							$( "#plan" ).val("");
							tips.text("All fields are required");
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
	$( "#dialog-form2" ).dialog( "open" );
}
</script>
<script> //edit packages
$(function() {
	$( "#dialog:ui-dialog4" ).dialog( "destroy" );
	
	var id = "<?php echo $_GET["id"]; ?>",
		cli = $( "#edit_cli" ),
		plan = $( "#edit_plan" ),
		cli2 = $( "#original_edit_cli" ),
		tips = $( ".error2" );

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
					$.get("../tpv/verification_submit.php?method=edit", { id: id, cli: cli.val(), plan: plan.val(), cli2: cli2.val() },
					function(data) {
						if (data == "editted")
						{
							$( "#dialog-form4" ).dialog( "close" );
							var id = '<?php echo $_GET["id"]; ?>';
							$( '#packages' ).load('../tpv/packages.php?id=' + id);
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
	$( "#edit_plan" ).load("plans.php?type=" + $( "#type" ).val() + "&cli=" + $('#edit_cli').val());
	$( "#edit_plan" ).val(plan);
	$( "#original_edit_cli" ).val(cli);
	$( "#dialog-form4" ).dialog( "open" );
}
</script>
<script> //delete packages
function Delete_Package(cli)
{
	var id = "<?php echo $_GET["id"]; ?>",
		cli = cli;
	
	$.get("../tpv/verification_submit.php?method=delete", { id: id, cli: cli},
	function(data) {
		if (data == "deleted")
		{
			$( "#packages" ).load('../tpv/packages.php?id=' + id);
		}
	});
}
</script>
<script> //cancel form
function Cancel()
{
	var id = "<?php echo $_GET["id"]; ?>";
	window.location = "admin.php?p=details&id=" + id;
}
</script>
<script> //submit button
function Submit()
{
	var id = "<?php echo $_GET["id"]; ?>",
		verifier = "<?php echo $ac["user"]; ?>",
		lead_id = $( "#lead_id" ),
		status = $( "#status" ),
		note = $( "#notes" ),
		title = $( "#title" ),
		first = $( "#first" ),
		middle = $( "#middle" ),
		last = $( "#last" ),
		dob = $( "#datepicker" ),
		email = $( "#email" ),
		mobile = $( "#mobile" ),
		billing = $('input[name=billing]:checked'),
		physical = $( "#physical" ),
		postal = $( "#postal" ),
		id_type = $( "#id_type" ),
		id_num = $( "#id_num" ),
		abn = $( "#abn" ),
		abn_status = $( ".abn_status" ),
		position = $( "#position" ),
		tips = $( ".error" );
			
		if ($('#postal_same').attr('checked'))
		{
			postal = $( "#physical" );
		}
					
	$.get("admin_submit.php?p=edit&method=submit", { id: id, verifier: verifier, lead_id: lead_id.val(), status: status.val(), note: note.val(), title: title.val(), first: first.val(), middle: middle.val(), last: last.val(), dob: dob.val(), email: email.val(), mobile: mobile.val(), billing: billing.val(), physical: physical.val(), postal: postal.val(), id_type: id_type.val(), id_num: id_num.val(), abn: abn.val(), abn_status: abn_status.html(), position: position.val()},
	function(data) {
		if (data == "submitted")
		{
			$( "#dialog-form" ).dialog( "close" );
			window.location = "admin.php?p=details&id=" + id;
		}
		else
		{
			$( ".error4" ).html(data);
			$( "#dialog-confirm3" ).dialog( "open" );
		}
	});
}
</script>
<script> //error dialog
	$(function() {
		$( "#dialog:ui-dialog3" ).dialog( "destroy" );
	
		$( "#dialog-confirm3" ).dialog({
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
<script> //physical address
$(function() {
	$( "#dialog:ui-dialog5" ).dialog( "destroy" );
	
	var tips = $( ".error5" );
	
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#dialog-confirm5" ).dialog({
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
								$( "#display_physical" ).val(data);
							});
						$( "#physical" ).val($( "#address_code" ).val());
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
								$( "#physical" ).val(data);
								$.get("../source/gnafGet.php?type=manualdisplay&id=" + data, {  },
									function(data2) {
										$( "#display_physical" ).val(data2);
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
		$('input#physical_address_method').removeAttr('disabled');
}

function Physical()
{
	$( "#dialog-confirm5" ).dialog( "open" );
}
</script>
<script> //postal address
$(function() {
	$( "#dialog:ui-dialog6" ).dialog( "destroy" );
	
	var tips = $( ".error6" );
	
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
								$( "#display_postal" ).val(data);
							});
						$( "#postal" ).val($( "#address_code_p" ).val());
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
								$( "#postal" ).val(data);
								$.get("../source/gnafGet.php?type=manualdisplay&id=" + data, {  },
									function(data2) {
										$( "#display_postal" ).val(data2);
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
	$( "#dialog-confirm6" ).dialog( "open" );
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
<script>
function Plan_Dropdown()
{
	$( "#plan" ).val("");
	$( "#plan" ).load("plans.php?type=" + $( "#type" ).val() + "&cli=" + $('#cli').val());
}
</script>
<script>
function Plan_Dropdown_Edit()
{
	$( "#edit_plan" ).load("plans.php?type=" + $( "#type" ).val() + "&cli=" + $('#edit_cli').val());
}
</script>


<div style="display:none;">
<img src="../images/getsale_btn_hover.png" /><img src="../images/submit_form_btn_hover.png" /><img src="../images/cancel_form_btn_hover.png" /><img src="../images/add_package_btn_hover.png" /><img src="../images/search_btn_2.png" /><img src="../images/search_btn_hover_2.png" />
</div>

<div id="dialog-form2" title="Add a Package">
<p class="error3">All fields are required</p><br />
<table>
<tr>
<td width="50px">CLI </td>
<td><input type="text" size="15" id="cli" onchange="Plan_Dropdown()" style="margin-top:0px;" /></td>
</tr>
<td>Plan </td>
<td><select id="plan" style="margin-left:0px; width:210px; height:25px; padding:1px 0 0;">
<option></option>
</select></td>
</tr>
</table>
</div>

<div id="dialog-confirm3" title="Error">
	<p class="error4"></p>
</div>

<div id="dialog-form4" title="Edit Package">
<p class="error2">All fields are required</p><br />
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

<div id="dialog-confirm5" title="Physical Address">
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
<td rowspan="2" align="right" valign="bottom"><input type="button" onclick="Check_Address_Physical()" id="check_btn" value="" class="search" style="display:none;" /></td>
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

<div id="dialog-confirm6" title="Postal Address">
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
<td rowspan="2" align="right" valign="bottom"><input type="button" onclick="Check_Address_Postal()" id="check_btn_p" value="" class="search" style="display:none;" /></td>
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

<?php
if ($_GET["id"] == "")
{
	echo "<script>window.location = '../self/admin.php?p=details';</script>";
}
else
{
	$id = $_GET["id"];
	$date1 = date("Y-m-d");
	$date2 = date("Y-m-d", strtotime("+1 week"));
	
	$q = mysql_query("SELECT * FROM sales_customers WHERE id = '$id'") or die(mysql_error());
	$data = mysql_fetch_assoc($q);
	
	$q2 = mysql_query("SELECT * FROM auth WHERE user = '$data[agent]'") or die(mysql_error());
	$data2 = mysql_fetch_assoc($q2);
	
	if (mysql_num_rows($q) == 0)
	{
		echo "<script>window.location = '../self/admin.php?p=details';</script>";
	}
	
	if ($data["centre"] != $ac["centre"])
	{
		echo '<script>';
		echo 'window.location = "admin.php?p=details";';
		echo '</script>';
	}
	
	if (strtotime(date("Y-m-d", strtotime($data["approved_timestamp"]))) < strtotime(date("Y-m-d", strtotime("-2days"))))
	{
		echo '<script>';
		echo 'window.location = "admin.php?p=details";';
		echo '</script>';
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
	
	switch ($data["id_type"])
	{
		case "Driver's Licence (AUS)":
		$drl="selected";
		break;
		case "Medicare Card":
		$mcc="selected";
		break;
		case "Healthcare Card":
		$hcc="selected";
		break;
		case "Passport":
		$ppt="selected";
		break;
		case "Pension Card":
		$pnc="selected";
		break;
	}
?>

<script>
$(function() {
	$( "#datepicker" ).datepicker( {
		showOn: "button",
		buttonImage: "../images/calendar.gif",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		altField: "#datepicker2",
		altFormat: "dd/mm/yy",
		changeMonth: true,
		changeYear: true,
		maxDate: "-216M",
		yearRange: "-100Y:-18Y" });
});
</script>
<script>
function Email()
{
	$( "#email" ).val("");
	$( "#email" ).removeAttr("disabled");
}

function Post()
{
	$( "#email" ).val("N/A");
	$( "#email" ).attr("disabled", true);
}

function Same_Address()
{
	if ($('#postal_same').attr('checked'))
	{
		$( "#display_postal" ).val("SAME");
		$( "#display_postal" ).attr("disabled", true);
		$( ".postal_open" ).removeAttr("onclick");
		$( ".postal_open" ).removeAttr("style");
	}
	else
	{
		$( "#display_postal" ).val("");
		$( "#postal" ).val("");
		$( "#display_postal" ).removeAttr("disabled");
		$( ".postal_open" ).attr("onclick", "Postal()");
		$( ".postal_open" ).attr("style", "cursor:pointer; text-decoration:underline;");
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
window.onload=function()
{
	if ( $( "#mobile" ).val() == "N/A" )
	{
		$( "#no_mobile" ).prop("checked", true);
		$( "#mobile" ).attr("disabled", true);
	}
	
	if ( $( "#email" ).val() == "N/A" )
	{
		$( "#billing_p" ).prop("checked", true);
		$( "#email" ).attr("disabled", true);
	}
	else
	{
		$( "#billing_e" ).prop("checked", true);
	}
	
	if ( "<?php echo $data["welcome"]; ?>" == "post" )
	{
		$( "#welcome_p" ).prop("checked", true);
	}
	else if ( "<?php echo $data["welcome"]; ?>" == "email" )
	{
		$( "#welcome_e" ).prop("checked", true);
	}
	
	var id = $( "#physical" );
	$( '#physical_address' ).load('../tpv/address.php?id=' + id.val());
	if ( id.val().substring(0,2) == "GA" )
	{
		$.get("../source/gnafGet.php?type=display&gnaf_id=" + id.val(), {  },
			function(data) {
				$( "#display_physical" ).val(data);
		});
	}
	else
	{
		$.get("../source/gnafGet.php?type=manualdisplay&id=" + id.val(), {  },
			function(data) {
				$( "#display_physical" ).val(data);
		});
	}
	
	if ( $( "#physical" ).val() == $( "#postal" ).val())
	{
		$('#postal_same').attr('checked', true);
		$( "#display_postal" ).val("SAME");
		$( "#display_postal" ).attr("disabled", true);
		$( ".postal_open" ).removeAttr("onclick");
		$( ".postal_open" ).removeAttr("style");
	}
	else
	{
		var id = $( "#postal" );
		$( '#postal_address' ).load('../tpv/address.php?id=' + id.val());
		if ( id.val().substring(0,2) == "GA" )
		{
			$.get("../source/gnafGet.php?type=display&gnaf_id=" + id.val(), {  },
				function(data) {
					$( "#display_postal" ).val(data);
			});
		}
		else
		{
			$.get("../source/gnafGet.php?type=manualdisplay&id=" + id.val(), {  },
				function(data) {
					$( "#display_postal" ).val(data);
			});
		}
	}

	if ( $( "#abn" ).val() != undefined )
	{
		$.getJSON("../source/abrGet.php", {abn: $("#abn").val() },
			function(data){
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
		});
	}
}
</script>
<input type="hidden" id="lead_id" value="<?php echo $data["lead_id"]; ?>" />
<input type="hidden" id="type" value="<?php echo $data["type"]; ?>" />
<table border="0" width="100%">
<tr>
<td width="50%" valign="top">
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
<td>Agent </td>
<td><b><?php echo $data["agent"] . " (" . $data2["alias"] . ")"; ?></b></td>
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
<td><b><?php echo $data["type"]; ?></b></td>
</tr>
</table>
</td>
<td width="50%" valign="top">
<table border="0" width="100%">
<tr>
<td colspan="2"><img src="../images/customer_address_header.png" width="136" height="15" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="90%" height="9" alt="line" /></td>
</tr>
<tr>
<td width="85px"><a onclick="Physical()" style="cursor:pointer; text-decoration:underline;">Physical</a><span style="color:#ff0000;">*</span> </td>
<td><input type="text" size="35" id="display_physical" readonly /><input type="hidden" id="physical" value="<?php echo $data["physical"]; ?>" /></td>
</tr>
<tr>
<td><a onclick="Postal()" class="postal_open" style="cursor:pointer; text-decoration:underline;">Postal</a><span style="color:#ff0000;">*</span> </td>
<td><input type="text" size="35" id="display_postal" readonly /><input type="hidden" id="postal" value="<?php echo $data["postal"]; ?>" /></td>
</tr>
<tr>
<td></td>
<td><input type="checkbox" id="postal_same" onclick="Same_Address()" style="height:auto;" /> Same as Above</td>
</tr>
</table>
</td>
</tr>
<tr>
<td width="50%" valign="top">
<table border="0" width="100%">
<tr>
<td colspan="2"><br /><img src="../images/customer_details_header.png" width="128" height="15" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="80%" height="9" alt="line" /></td>
</tr>
<tr>
<td width="85px">Title<span style="color:#ff0000;">*</span> </td>
<td><select id="title" style="margin-left:0px; width:75px; height:25px; padding: 1px 0 0;">
<option <?php echo $mr; ?>>Mr</option>
<option <?php echo $mrs; ?>>Mrs</option>
<option <?php echo $miss; ?>>Miss</option>
<option <?php echo $ms; ?>>Ms</option>
<option <?php echo $dr; ?>>Dr</option>
</select></td>
</tr>
<tr>
<td>First Name<span style="color:#ff0000;">*</span> </td>
<td><input type="text" size="25" id="first" value="<?php echo $data["firstname"]; ?>" /></td>
</tr>
<tr>
<td>Middle Name </td>
<td><input type="text" size="25" id="middle" value="<?php echo $data["middlename"]; ?>" /></td>
</tr>
<tr>
<td>Last Name<span style="color:#ff0000;">*</span> </td>
<td><input type="text" size="25" id="last" value="<?php echo $data["lastname"]; ?>" /></td>
</tr>
<tr>
<td>D.O.B<span style="color:#ff0000;">*</span> </td>
<td><input type="text" size="11" id="datepicker2" value="<?php echo date("d/m/Y", strtotime($data["dob"])); ?>" readonly /><input type="hidden" id="datepicker" value="<?php echo $data["dob"]; ?>" /></td>
</tr>
<tr>
<td>Billing<span style="color:#ff0000;">*</span> </td>
<td><input type="radio" name="billing" value="email" id="billing_e" onclick="Email()" checked="checked" style="height:auto;" /> E-Bill &nbsp; <input type="radio" name="billing" id="billing_p" onclick="Post()" value="post" style="height:auto;" /> Post</td>
</tr>
<tr>
<td>E-Mail<span style="color:#ff0000;">*</span> </td>
<td><input type="text" size="25" id="email" value="<?php echo $data["email"]; ?>" /></td>
</tr>
<tr>
<td>Mobile<span style="color:#ff0000;">*</span> </td>
<td><input type="text" size="25" id="mobile" value="<?php echo $data["mobile"]; ?>" /> <input type="checkbox" id="no_mobile" onclick="Mobile()" style="height:auto;" /> <span class="mobile_text">N/A</span></td>
</tr>
</table>
</td>
<td width="50%" height="100%" valign="top">
<table border="0" width="100%" height="100%">
<tr valign="top">
<td>
<table border="0" width="100%">
<?php
if ($data["type"] == "Business")
{
?>
<input type="hidden" id="sale_type" value="Business" />
<tr>
<td colspan="2"><br /><img src="../images/business_identification_header.png" width="164" height="15" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="90%" height="9" alt="line" /></td>
</tr>
<tr>
<td width="85px">ABN<span style="color:#ff0000;">*</span> </td>
<td><input type="text" size="25" id="abn" value="<?php echo $data["abn"]; ?>" onchange="getABN()" /></td>
</tr>
<tr>
<td>Position<span style="color:#ff0000;">*</span> </td>
<td><input type="text" size="25" id="position" value="<?php echo $data["position"]; ?>" /></td>
</tr>
<tr>
<td>Business Name </td>
<td><b class="bus_name" style="font-size:9px;"></b></td>
</tr>
<tr>
<td>ABN Status </td>
<td><b class="abn_status" style="font-size:9px;"></b></td>
</tr>
<tr>
<td>Business Type </td>
<td><b class="bus_type" style="font-size:9px;"></b></td>
</tr>
<tr>
<td colspan="2" height="109px" valign="bottom">
<img src="../images/bus_fill_bg.png" width="352" height="109" />
</td>
</tr>
<?php
}
elseif ($data["type"] == "Residential")
{
?>
<input type="hidden" id="sale_type" value="Residential" />
<tr>
<td colspan="2"><br /><img src="../images/customer_identification_header.png" width="172" height="15" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="90%" height="9" alt="line" /></td>
</tr>
<tr>
<td width="85px">ID Type<span style="color:#ff0000;">*</span> </td>
<td><select id="id_type" style="margin-left:0px; width:168px; height:25px; padding: 1px 0 0;">
<option <?php echo $drl; ?>>Driver's Licence (AUS)</option>
<option <?php echo $mcc; ?>>Medicare Card</option>
<option <?php echo $hcc; ?>>Healthcare Card</option>
<option <?php echo $ppt; ?>>Passport</option>
<option <?php echo $pnc; ?>>Pension Card</option>
</select></td>
</tr>
<tr>
<td>ID Number<span style="color:#ff0000;">*</span> </td>
<td><input type="text" size="25" id="id_num" value="<?php echo $data["id_num"]; ?>" /></td>
</tr>
<tr>
<td colspan="2" height="159px" valign="bottom">
<img src="../images/resi_fill_bg.png" width="352" height="147" />
</td>
</tr>
<?php
}
?>
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
<td colspan="2"><br /><img src="../images/selected_packages_header.png" width="134" height="15" style="padding-left:3px;"/></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" alt="line" /></td>
</tr>
<tr>
<td colspan="2">
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="99%">
<thead>
<tr class="ui-widget-header ">
<th>CLI</th>
<th>Plan</th>
<th colspan="2">Edit</th>
</tr>
</thead>
<tbody id="packages">
<script>
var id = "<?php echo $_GET['id']; ?>";
$( "#packages" ).load('../tpv/packages.php?id=' + id);
</script>
</tbody>
</table>
</div>
</td>
</tr>
<tr valign="bottom">
<td align="left"><input type="button" onclick="Add_Package()" class="addpackage" /></td>
<td align="right"><input type="button" onclick="Submit()" class="submitform" /><input type="button" onclick="Cancel()" class="cancelform" /></td>
</tr>
</table>
</td>
</tr>
</table>

<?php
}
?>