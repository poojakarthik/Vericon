<?php
mysql_connect('localhost','vericon','18450be');

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
<script>
if ( $( "#mobile" ).val() == "N/A" )
{
	$( "#no_mobile" ).prop("checked", true);
}

if ( $( "#email" ).val() == "N/A" )
{
	$( "#no_email" ).prop("checked", true);
}

$.get("../source/gnafGet.php?type=display", { id: "<?php echo $data["physical"]; ?>" }, function(data) {
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
}
else
{
	$.get("../source/gnafGet.php?type=display", { id: "<?php echo $data["postal"]; ?>" }, function(data) {
		var n = data.split("}");
		$( "#display_postal1" ).val(n[0]);
		$( "#display_postal2" ).val(n[1]);
		$( "#display_postal3" ).val(n[2]);
		$( "#display_postal4" ).val(n[3]);
	});
}
</script>

<div id="dialog-form_notes" title="Notes">
<textarea disabled="disabled" id="display_notes" style="width:400px; height:150px; resize:none;">
</textarea>
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
<script> //get ABN
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
</script>
<tr>
<td colspan="2"><img src="../images/business_identification_header.png" width="164" height="15" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="90%" height="9" alt="line" /></td>
</tr>
<tr>
<td width="85px">ABN </td>
<td><input type="text" id="abn" onchange="getABN()" style="width:225px;" disabled="disabled" value="<?php echo $data["abn"]; ?>" /></td>
</tr>
<tr>
<td>Position </td>
<td><input type="text" id="position" style="width:225px;" disabled="disabled" value="<?php echo $data["position"]; ?>" /></td>
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
<?php
}
elseif ($data["type"] == "Residential")
{
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
<tr>
<td colspan="2"><img src="../images/customer_identification_header.png" width="172" height="15" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="90%" height="9" alt="line" /></td>
</tr>
<tr>
<td width="85px">ID Type </td>
<td><select id="id_type" disabled="disabled" style="width:192px;">
<option <?php echo $drl; ?>>Driver's Licence (AUS)</option>
<option <?php echo $hcc; ?>>Healthcare Card</option>
<option <?php echo $mcc; ?>>Medicare Card</option>
<option <?php echo $ppt; ?>>Passport</option>
<option <?php echo $pnc; ?>>Pension Card</option>
</select></td>
</tr>
<tr>
<td>ID Number </td>
<td><input type="text" id="id_num" style="width:190px;" disabled="disabled" value="<?php echo $data["id_num"]; ?>" /></td>
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
<td><select id="title" style="width:50px;" disabled="disabled">
<option <?php echo $mr; ?>>Mr</option>
<option <?php echo $mrs; ?>>Mrs</option>
<option <?php echo $miss; ?>>Miss</option>
<option <?php echo $ms; ?>>Ms</option>
<option <?php echo $dr; ?>>Dr</option>
</select></td>
</tr>
<tr>
<td>First Name </td>
<td><input type="text" id="first" style="width:150px;" disabled="disabled" value="<?php echo $data["firstname"]; ?>" /></td>
</tr>
<tr>
<td>Middle Name </td>
<td><input type="text" id="middle" style="width:150px;" disabled="disabled" value="<?php echo $data["middlename"]; ?>" /></td>
</tr>
<tr>
<td>Last Name </td>
<td><input type="text" id="last" style="width:150px;" disabled="disabled" value="<?php echo $data["lastname"]; ?>" /></td>
</tr>
<tr>
<td>D.O.B </td>
<td><input type="text" id="datepicker2" readonly style="width:80px;" disabled="disabled" value="<?php echo date("d/m/Y", strtotime($data["dob"])); ?>" /> <input type="hidden" id="datepicker" value="<?php echo date("Y-m-d", strtotime($data["dob"])); ?>" /></td>
</tr>
<tr>
<td>E-Mail </td>
<td><input type="text" id="email" style="width:150px;" disabled="disabled" value="<?php echo $data["email"]; ?>" /> <input type="checkbox" id="no_email" onclick="Email()" style="height:auto;" disabled="disabled" /> N/A</td>
</tr>
<tr>
<td>Mobile </td>
<td><input type="text" id="mobile" style="width:150px;" value="<?php echo $data["mobile"]; ?>" disabled="disabled" /> <input type="checkbox" id="no_mobile" onclick="Mobile()" style="height:auto;" disabled="disabled" /> N/A</td>
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
<td width="85px">Physical </td>
<td><input type="text" id="display_physical1" style="width:225px;" disabled="disabled" /></td>
</tr>
<tr>
<td><input type="hidden" id="physical" value="" /></td>
<td><input type="text" id="display_physical2" style="width:225px;" disabled="disabled" /></td>
</tr>
<tr>
<td></td>
<td><input type="text" id="display_physical3" style="width:45px;" disabled="disabled" /> <input type="text" id="display_physical4" style="width:55px;" disabled="disabled" /></td>
</tr>
<tr>
<td width="85px">Postal </td>
<td><input type="text" id="display_postal1" readonly style="width:225px;" disabled="disabled" /></td>
</tr>
<tr>
<td><input type="hidden" id="postal" value="" /></td>
<td><input type="text" id="display_postal2" style="width:225px;" disabled="disabled" /></td>
</tr>
<tr>
<td></td>
<td><input type="text" id="display_postal3" style="width:45px;" disabled="disabled" /> <input type="text" id="display_postal4" style="width:55px; margin-right:12px;" disabled="disabled" /> <input type="checkbox" id="postal_same" onclick="Postal_Same()" style="height:auto;" disabled="disabled" /> Same as Above</td>
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
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="99%" style="margin-top:0px; margin-left:3px;">
<thead>
<tr class="ui-widget-header ">
<th>CLI</th>
<th>Plan</th>
</tr>
</thead>
<tbody>
<?php
$q3 = mysql_query("SELECT * FROM vericon.sales_packages WHERE sid = '" . mysql_real_escape_string($id) . "' ORDER BY timestamp ASC");

if (mysql_num_rows($q3) == 0)
{
	echo "<tr>";
	echo "<td colspan='2' style='text-align:center;'>Customer has no packages!</td>";
	echo "</tr>";
}
else
{
	while ($package = mysql_fetch_assoc($q3))
	{
		$class1 = "";
		$class2 = "";
		$warning = "";
		$q4 = mysql_query("SELECT * FROM vericon.plan_matrix WHERE id = '$package[plan]' AND campaign = '" . mysql_real_escape_string($c_id) . "'") or die(mysql_error());
		$package_name = mysql_fetch_assoc($q4);
		if (substr($package_name["type"],0,4) == "ADSL" || $package_name["type"] == "Bundle")
		{
			preg_match('/0([2378])([0-9]{8})/',$package["cli"],$d);
			$q5 = mysql_query("SELECT * FROM adsl.Enabled_Exchanges WHERE Range_From <= $d[2] AND Range_To >= $d[2] AND AC = '$d[1]'") or die(mysql_error());
			
			if(mysql_num_rows($q5) == 0)
			{
				$class1 = "class='ui-state-highlight' style='padding:0 .7em;'";
				$class2 = "<span class='ui-icon ui-icon-alert' style='float:left; margin-right:.3em;'></span>";
				$warning = " -- ADSL may not be available";
			}
		}
		echo "<tr $class1>";
		echo "<td>" . $package["cli"] . "</td>";
		echo "<td>$class2" . $package_name["name"] . "$warning</td>";
		echo "</tr>";
	}
}
?>
</tbody>
</table>
</div>
</td>
</tr>
<tr valign="bottom">
<td align="left" style="padding-left:5px;"><button onclick="Notes()" class="btn">Notes</button></td>
<td align="right" style="padding-right:5px;"><button onclick="Cancel_Search()" class="btn">Cancel</button></td>
</tr>
</table>
</td>
</tr>
</table>