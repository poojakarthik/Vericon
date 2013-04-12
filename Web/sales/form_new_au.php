<?php
include("../auth/restrict_inner.php");

$id = $_POST["lead"];
$q = $mysqli->query("SELECT `sales_customers_temp`.`id`, `sales_customers_temp`.`user`, `sales_customers_temp`.`centre`, `sales_customers_temp`.`type`, `sales_customers_temp`.`campaign` AS campaign_id, CONCAT(`auth`.`first`,' ',`auth`.`last`) AS name, `campaigns`.`campaign` FROM `vericon`.`sales_customers_temp`, `vericon`.`auth`, `vericon`.`campaigns` WHERE `sales_customers_temp`.`id` = '" . $mysqli->real_escape_string($id) . "' AND `sales_customers_temp`.`user` = `auth`.`user` AND `sales_customers_temp`.`campaign` = `campaigns`.`id`") or die($mysqli->error);
$data = $q->fetch_assoc();
$q->free();
?>
<style>
div#users-contain table { border-collapse: collapse; background:none; }
div#users-contain table th { border: 1px solid rgba(41,171,226,0.25); padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid rgba(41,171,226,0.25); padding: .6em 5px; text-align: left; }
div#users-contain table tbody tr:hover { background:rgba(255,255,255,0.25); }
div#address_edit table td { padding:3px 0; }
.ui-autocomplete-loading { background:white url('/images/loading_icon.gif') right center no-repeat; }
</style>
<script> //get ABN
function getABN()
{
	if ($( "#abn" ).val() == "")
	{
		$(".bus_name").html( "" );
		$(".abn_status").html( "" );
		$(".bus_type").html( "" );
	}
	else
	{
		V_Loading_Start();
		$.getJSON("/source/abrGet.php", { abn: $("#abn").val() },
		function(data){
			if (data['error'] == "true")
			{
				$(".bus_name").html( "" );
				$(".abn_status").html( "Invalid ABN" );
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
			V_Loading_End();
		});
	}
}
</script>
<script>
function Email()
{
	if ( $( "#no_email:checked" ).length == 1 )
	{
		$( "#email" ).val("N/A");
		$( "#email" ).attr("readonly", "readonly" );
	}
	else
	{
		$( "#email" ).val("");
		$( "#email" ).removeAttr("readonly");
	}
}

function Mobile()
{
	if ( $( "#no_mobile:checked" ).length == 1 )
	{
		$( "#mobile" ).val("N/A");
		$( "#mobile" ).attr("readonly", "readonly" );
	}
	else
	{
		$( "#mobile" ).val("");
		$( "#mobile" ).removeAttr("readonly");
	}
}
</script>
<script>
function Plan_Load(number)
{
	V_Loading_Start();
	
	var cli = $( "#cli_" + number ),
		plan = $( "#plan_" + number );
	
	if (cli.val() == "")
	{
		plan.attr("disabled", "disabled");
		plan.html("<option></option>");
		plan.val("");
		V_Loading_End();
	}
	else
	{
		$.post( "/source/plans_au.php", { campaign: "<?php echo $data["campaign_id"]; ?>", type: "<?php echo $data["type"]; ?>", cli: cli.val() }, function(data) {
			data = data.split(":=");
			if (data[0] == "error")
			{
				plan.attr("disabled", "disabled");
				plan.html(data[1]);
			}
			else
			{
				plan.html(data);
				plan.removeAttr("disabled");
			}
			V_Loading_End();
		}).error( function(xhr, text, err) {
			$(".loading_message").html("<p><b>An error occurred while performing this action.</b></p><p><b>Error: " + xhr.status + " " + xhr.statusText + "</b></p>");
			setTimeout(function() {
				V_Loading_End();
			}, 2500);
		});
	}
}
</script>
<script>
function Postal_Same()
{
	if ( $( "#postal_same:checked" ).length == 1 )
	{
		$( "#postal_line1" ).val("Same as Physical");
		$( "#postal_line2" ).val("");
		$( "#postal_dpid " ).val("");
		$( "#postal_barcode" ).val("");
		$( "#postal_formattedAddress" ).val("");
		$( "#postal_building_name" ).val("");
		$( "#postal_sub_premise" ).val("");
		$( "#postal_street_number" ).val("");
		$( "#postal_street_name" ).val("");
		$( "#postal_street_type" ).val("");
		$( "#postal_street_type_suffix" ).val("");
		$( "#postal_suburb_town" ).val("");
		$( "#postal_state" ).val("");
		$( "#postal_postcode" ).val("");
		$( "#postal_edit" ).attr("disabled", "disabled");
	}
	else
	{
		$( "#postal_line1" ).val("");
		$( "#postal_line2" ).val("");
		$( "#postal_dpid " ).val("");
		$( "#postal_barcode" ).val("");
		$( "#postal_formattedAddress" ).val("");
		$( "#postal_building_name" ).val("");
		$( "#postal_sub_premise" ).val("");
		$( "#postal_street_number" ).val("");
		$( "#postal_street_name" ).val("");
		$( "#postal_street_type" ).val("");
		$( "#postal_street_type_suffix" ).val("");
		$( "#postal_suburb_town" ).val("");
		$( "#postal_state" ).val("");
		$( "#postal_postcode" ).val("");
		$( "#postal_edit" ).removeAttr("disabled");
	}
}

function Edit_Address(type)
{
	V_Loading_Start();
	$( "#address_edit" ).load("/source/address_au.php", { method: "auto", type: type }, function(data, status, xhr){
		if (status == "error")
		{
			if (xhr.status == 403 || xhr.status == 0)
			{
				$(".loading_message").html("<p><b>Your session has expired.</b></p><p><b>You will be logged out shortly.</b></p>");
				setTimeout(function() {
					V_Logout();
				}, 2500);
			}
			else
			{
				$(".loading_message").html("<p><b>An error occurred while performing this action.</b></p><p><b>Error: " + xhr.status + " " + xhr.statusText + "</b></p>");
				setTimeout(function() {
					V_Loading_End();
				}, 2500);
			}
		}
		else
		{
			$( "#main_form" ).attr("style", "display:none;");
			$( "#address_edit" ).removeAttr("style");
			$( "#address_edit" ).attr("style", "width:98%; text-align:left;");
			V_Loading_End();
		}
	});
}

function Edit_Address_Manual(type)
{
	V_Loading_Start();
	$( "#address_edit" ).load("/source/address_au.php", { method: "manual", type: type }, function(data, status, xhr){
		if (status == "error")
		{
			if (xhr.status == 403 || xhr.status == 0)
			{
				$(".loading_message").html("<p><b>Your session has expired.</b></p><p><b>You will be logged out shortly.</b></p>");
				setTimeout(function() {
					V_Logout();
				}, 2500);
			}
			else
			{
				$(".loading_message").html("<p><b>An error occurred while performing this action.</b></p><p><b>Error: " + xhr.status + " " + xhr.statusText + "</b></p>");
				setTimeout(function() {
					V_Loading_End();
				}, 2500);
			}
		}
		else
		{
			$( "#main_form" ).attr("style", "display:none;");
			$( "#address_edit" ).removeAttr("style");
			$( "#address_edit" ).attr("style", "width:98%; text-align:left;");
			V_Loading_End();
		}
	});
}

function Edit_Address_Submit()
{
	V_Loading_Start();
	
	if ( $( "#suburb_town" ).val() == "" && $( "#suburb_town" ).html() == "" )
	{
		var text = "<b>Error: </b>Please enter a suburb / town.";
		
		$( "#address_edit_error" ).html('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p style="padding: 9px 0;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>' + text + '</p></div>');
	}
	else if ( $( "#state" ).val() == "" && $( "#state" ).html() == "" )
	{
		var text = "<b>Error: </b>Please enter a state.";
		
		$( "#address_edit_error" ).html('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p style="padding: 9px 0;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>' + text + '</p></div>');
	}
	else if ( $( "#postcode" ).val() == "" && $( "#postcode" ).html() == "" )
	{
		var text = "<b>Error: </b>Please enter a postcode.";
		
		$( "#address_edit_error" ).html('<div class="ui-state-error ui-corner-all" style="padding: 0 .7em;"><p style="padding: 9px 0;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>' + text + '</p></div>');
	}
	else
	{
		if ( $( "#address_method" ).val() == "manual" )
		{
			var line_1 = "",
				line_2 = "",
				building_name = $( "#building_name" ).val(),
				sub_premise = $( "#sub_premise" ).val(),
				street_number = $( "#street_number" ).val(),
				street_name = $( "#street_name" ).val(),
				street_type = $( "#street_type" ).val(),
				street_type_suffix = $( "#street_type_suffix" ).val(),
				suburb_town = $( "#suburb_town" ).val(),
				state = $( "#state" ).val(),
				postcode = $( "#postcode" ).val();
		
			if (building_name != "") {
				line_1 += building_name.trim() + ", ";
			}
			
			line_1 += sub_premise.trim() + " " + street_number.trim() + " " + street_name.trim() + " " + street_type.trim() + " " + street_type_suffix.trim();
			line_1 = line_1.replace(/\w\S*/g, function(txt){
				return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();
			});
			line_2 += suburb_town.trim() + " " + state.trim() + " " + postcode.trim();
			line_2 = line_2.toUpperCase();
			
			var formattedAddress = line_1.trim() + ", " + line_2.trim();
			formattedAddress = formattedAddress.replace(/\s{2,}/g, ' ');
			formattedAddress = formattedAddress.trim();
		}
		else
		{
			var formattedAddress = $( "#formattedAddress" ).val();
		}
		
		var type = $( "#address_type" ).val(),
			n = formattedAddress.lastIndexOf(',');
		
		$( "#" + type + "_line1" ).val(formattedAddress.substring(0, n));
		$( "#" + type + "_line2" ).val(formattedAddress.substring(n + 2));
		$( "#" + type + "_dpid " ).val($( "#dpid" ).val());
		$( "#" + type + "_barcode" ).val($( "#barcode" ).val());
		$( "#" + type + "_formattedAddress" ).val($( "#formattedAddress" ).val());
		$( "#" + type + "_building_name" ).val($( "#building_name" ).html());
		$( "#" + type + "_sub_premise" ).val($( "#sub_premise" ).html());
		$( "#" + type + "_street_number" ).val($( "#street_number" ).html());
		$( "#" + type + "_street_name" ).val($( "#street_name" ).html());
		$( "#" + type + "_street_type" ).val($( "#street_type" ).html());
		$( "#" + type + "_street_type_suffix" ).val($( "#street_type_suffix" ).html());
		$( "#" + type + "_suburb_town" ).val($( "#suburb_town" ).html());
		$( "#" + type + "_state" ).val($( "#state" ).html());
		$( "#" + type + "_postcode" ).val($( "#postcode" ).html());
		$( "#main_form" ).removeAttr("style");
		$( "#address_edit" ).attr("style", "width:98%; text-align:left; display:none;");
	}
	V_Loading_End();
}

function Edit_Address_Cancel()
{
	V_Loading_Start();
	$( "#main_form" ).removeAttr("style");
	$( "#address_edit" ).attr("style", "width:98%; text-align:left; display:none;");
	V_Loading_End();
}
</script>
<script>
package_count = 1;
function Add_Package()
{
	package_count++;
	$( "#packages" ).append('<tr id="package_' + package_count + '"><td><input type="text" autocomplete="off" id="cli_' + package_count + '" onchange="Plan_Load(' + package_count + ')" style="width:91%;" /></td><td><select id="plan_' + package_count + '" disabled="disabled" style="width:100%;"><option></option></select></td><td style="text-align:center;"><button onclick="Delete_Package(' + package_count + ')" class="icon_delete" title="Remove Package"></button></th></tr>');
}
function Delete_Package(number)
{
	$( "#package_" + number ).remove();
}
</script>

<div class="head">
<table>
<tr>
<td style="width:90px;"><div class="dotted"></div></td>
<td valign="middle" nowrap="nowrap" width="1px"><h1>Sales Form</h1></td>
<td><div class="dotted"></div></td>
</tr>
</table>
</div>

<center><table id="main_form" width="98%">
<tr>
<td width="50%" valign="top">
<center><h2>Sale Details</h2></center>
<table width="100%" style="margin-bottom:5px;">
<tr>
<td width="269px" height="2px"><div class="line_left"></div></td>
<td height="2px"><div class="line_repeat"></div></td>
<td width="269px" height="2px"><div class="line_right"></div></td>
</tr>
</table>
<table width="100%" style="margin-bottom:5px;">
<tr>
<td width="100px"><b>Lead ID </b></td>
<td><?php echo "0" . $data["id"]; ?></td>
</tr>
<tr>
<td><b>Agent </b></td>
<td><?php echo $data["name"] . " (" . $data["user"] . ")"; ?></td>
</tr>
<tr>
<td><b>Centre </b></td>
<td><?php echo $data["centre"]; ?></td>
</tr>
<tr>
<td><b>Campaign </b></td>
<td><?php echo $data["campaign"]; ?></td>
</tr>
<tr>
<td><b>Type </b></td>
<td><?php echo $data["type"]; ?></td>
</tr>
</table>
</td>
<td width="50%" valign="top">
<?php
if ($data["type"] == "Business")
{
?>
<center><h2>Business Identification</h2></center>
<table width="100%" style="margin-bottom:5px;">
<tr>
<td width="269px" height="2px"><div class="line_left"></div></td>
<td height="2px"><div class="line_repeat"></div></td>
<td width="269px" height="2px"><div class="line_right"></div></td>
</tr>
</table>
<table width="100%" style="margin-bottom:5px;">
<tr>
<td width="100px"><b>ABN <span style="color:#ff0000;">*</span></b></td>
<td><input type="text" id="abn" onchange="getABN()" /></td>
</tr>
<tr>
<td><b>Position <span style="color:#ff0000;">*</span></b></td>
<td><input type="text" id="position" /></td>
</tr>
<tr>
<td><b>Business Name </b></td>
<td><span class="bus_name"></span></td>
</tr>
<tr>
<td><b>Status </b></td>
<td><span class="abn_status"></span></td>
</tr>
<tr>
<td><b>Business Type </b></td>
<td><span class="bus_type"></span></td>
</tr>
</table>
<?php
}
elseif ($data["type"] == "Residential")
{
?>
<center><h2>Customer Identification</h2></center>
<table width="100%" style="margin-bottom:5px;">
<tr>
<td width="269px" height="2px"><div class="line_left"></div></td>
<td height="2px"><div class="line_repeat"></div></td>
<td width="269px" height="2px"><div class="line_right"></div></td>
</tr>
</table>
<table width="100%" style="margin-bottom:5px;">
<tr>
<td width="100px"><b>ID Type <span style="color:#ff0000;">*</span></b></td>
<td><select id="id_type">
<option></option>
<option>Driver's Licence (AUS)</option>
<option>Healthcare Card</option>
<option>Medicare Card</option>
<option>Passport</option>
<option>Pension Card</option>
</select></td>
</tr>
<tr>
<td><b>ID Number <span style="color:#ff0000;">*</span></b></td>
<td><input type="text" id="id_num" /></td>
</tr>
</table>
<?php
}
?>
</td>
</tr>
<tr>
<td width="50%" valign="top">
<center><h2>Customer Details</h2></center>
<table width="100%" style="margin-bottom:5px;">
<tr>
<td width="269px" height="2px"><div class="line_left"></div></td>
<td height="2px"><div class="line_repeat"></div></td>
<td width="269px" height="2px"><div class="line_right"></div></td>
</tr>
</table>
<table width="100%" style="margin-bottom:5px;">
<tr>
<td width="100px"><b>Title <span style="color:#ff0000;">*</span></b></td>
<td><select id="title" style="width:100px;">
<option></option>
<option>Mr</option>
<option>Mrs</option>
<option>Miss</option>
<option>Ms</option>
<option>Dr</option>
</select></td>
</tr>
<tr>
<td><b>First Name <span style="color:#ff0000;">*</span></b></td>
<td><input type="text" autocomplete="off" id="first" title="test" /></td>
</tr>
<tr>
<td><b>Middle Name </b></td>
<td><input type="text" autocomplete="off" id="middle" /></td>
</tr>
<tr>
<td><b>Last Name <span style="color:#ff0000;">*</span></b></td>
<td><input type="text" autocomplete="off" id="last" /></td>
</tr>
<tr>
<td><b>D.O.B <span style="color:#ff0000;">*</span></b></td>
<td><input type="text" autocomplete="off" id="dob_d" placeholder="DD" style="width:35px; text-align:center;" /> / <input type="text" autocomplete="off" id="dob_m" placeholder="MM" style="width:35px; text-align:center;" /> / <input type="text" autocomplete="off" id="dob_y" placeholder="YYYY" style="width:50px; text-align:center;" /></td>
</tr>
<tr>
<td><b>E-Mail <span style="color:#ff0000;">*</span></b></td>
<td><input type="text" autocomplete="off" id="email" /><input type="checkbox" id="no_email" onclick="Email()" /><label for="no_email"></label> <span style="vertical-align:middle;">N/A</span></td>
</tr>
<tr>
<td><b>Mobile <span style="color:#ff0000;">*</span></b></td>
<td><input type="text" autocomplete="off" id="mobile" /><input type="checkbox" id="no_mobile" onclick="Mobile()" /><label for="no_mobile"></label> <span style="vertical-align:middle;">N/A</span></td>
</tr>
</table>
</td>
<td width="50%" valign="top">
<center><h2>Address Details</h2></center>
<table width="100%" style="margin-bottom:5px;">
<tr>
<td width="269px" height="2px"><div class="line_left"></div></td>
<td height="2px"><div class="line_repeat"></div></td>
<td width="269px" height="2px"><div class="line_right"></div></td>
</tr>
</table>
<table width="100%" style="margin-bottom:5px;">
<tr>
<td width="100px"><b>Physical <span style="color:#ff0000;">*</span></b></td>
<td><input type="text" readonly="readonly" id="physical_line1" /></td>
</tr>
<tr>
<td></td>
<td><input type="text" id="physical_line2" readonly="readonly" /></td>
</tr>
<tr>
<td></td>
<td>
<table width="272px">
<tr>
<td align="left"><button onclick="Edit_Address('physical')" class="btn">Edit</button></td>
</tr>
</table>
</td>
</tr>
<tr>
<td><b>Postal <span style="color:#ff0000;">*</span></b></td>
<td><input type="text" id="postal_line1" readonly="readonly" /></td>
</tr>
<tr>
<td></td>
<td><input type="text" id="postal_line2" readonly="readonly" /></td>
</tr>
<tr>
<td></td>
<td>
<table width="272px">
<tr>
<td align="left"><button id="postal_edit" onclick="Edit_Address('postal')"  class="btn">Edit</button></td>
<td align="right"><input type="checkbox" id="postal_same" onclick="Postal_Same()" /><label for="postal_same" style="margin-left: 0px;"></label> <span style="vertical-align:middle;">Same as Physical</span></td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan="2" width="100%" valign="top">
<center><h2>Sale Packages</h2></center>
<table width="100%" style="margin-bottom:5px;">
<tr>
<td width="269px" height="2px"><div class="line_left"></div></td>
<td height="2px"><div class="line_repeat"></div></td>
<td width="269px" height="2px"><div class="line_right"></div></td>
</tr>
</table>
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="100%">
<thead>
<tr class="ui-widget-header ">
<th width="25%">CLI</th>
<th width="72%">Plan</th>
<th width="3%" style="text-align:center;">Remove</th>
</tr>
</thead>
<tbody id="packages">
<tr id="package_1">
<td><input type="text" autocomplete="off" id="cli_1" onchange="Plan_Load(1)" style="width:91%;" /></td>
<td><select id="plan_1" disabled="disabled" style="width:100%;">
<option></option>
</select></td>
<td style="text-align:center;"><button disabled="disabled" class="icon_delete" title="Can't Remove this Package"></button></td>
</tr>
</tbody>
</table>
</div></center>
</td>
</tr>
<tr>
<td colspan="2" width="100%" valign="top">
<table width="100%">
<tr>
<td align="left"><button onclick="Add_Package()" class="btn">Add Package</button></td>
</tr>
</table>
</td>
</tr>
</table></center>

<input type="hidden" id="physical_dpid" value="" />
<input type="hidden" id="physical_barcode" value="" />
<input type="hidden" id="physical_formattedAddress" value="" />
<input type="hidden" id="physical_building_name" value="" />
<input type="hidden" id="physical_sub_premise" value="" />
<input type="hidden" id="physical_street_number" value="" />
<input type="hidden" id="physical_street_name" value="" />
<input type="hidden" id="physical_street_type" value="" />
<input type="hidden" id="physical_street_type_suffix" value="" />
<input type="hidden" id="physical_suburb_town" value="" />
<input type="hidden" id="physical_state" value="" />
<input type="hidden" id="physical_postcode" value="" />
<input type="hidden" id="postal_dpid" value="" />
<input type="hidden" id="postal_barcode" value="" />
<input type="hidden" id="postal_formattedAddress" value="" />
<input type="hidden" id="postal_building_name" value="" />
<input type="hidden" id="postal_sub_premise" value="" />
<input type="hidden" id="postal_street_number" value="" />
<input type="hidden" id="postal_street_name" value="" />
<input type="hidden" id="postal_street_type" value="" />
<input type="hidden" id="postal_street_type_suffix" value="" />
<input type="hidden" id="postal_suburb_town" value="" />
<input type="hidden" id="postal_state" value="" />
<input type="hidden" id="postal_postcode" value="" />

<center><div id="address_edit" style="width:98%; text-align:left; display:none;">
<table>
<tr>
<td></td>
</tr>
</table>
</div></center>