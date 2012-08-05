<?php
mysql_connect('localhost','vericon','18450be');

$id = $_GET["id"];

$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
$data = mysql_fetch_assoc($q);

$q2 = mysql_query("SELECT * FROM vericon.auth WHERE user = '$data[agent]'") or die(mysql_error());
$data2 = mysql_fetch_assoc($q2);
?>
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
	$( "#display_postal1" ).attr("disabled", "disabled");
	$( "#display_postal2" ).attr("disabled", "disabled");
	$( "#display_postal3" ).attr("disabled", "disabled");
	$( "#display_postal4" ).attr("disabled", "disabled");
	$( "#postal_link" ).removeAttr("onclick");
	$( "#postal_link" ).removeAttr("style");
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

<table border="0" width="100%">
<tr>
<td width="50%" valign="top">
<table border="0" width="100%" style="margin-bottom:10px;">
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
<input type="hidden" id="sale_type" value="Business">
<tr>
<td colspan="2"><img src="../images/business_identification_header.png" width="164" height="15" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="90%" height="9" alt="line" /></td>
</tr>
<tr>
<td width="85px">ABN </td>
<td><input type="text" id="abn" onchange="getABN()" style="width:225px;" value="<?php echo $data["abn"]; ?>" /></td>
</tr>
<tr>
<td>Position </td>
<td><input type="text" id="position" style="width:225px;" value="<?php echo $data["position"]; ?>" /></td>
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
<input type="hidden" id="sale_type" value="Residential">
<tr>
<td colspan="2"><img src="../images/customer_identification_header.png" width="172" height="15" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="90%" height="9" alt="line" /></td>
</tr>
<tr>
<td width="85px">ID Type </td>
<td><select id="id_type" style="width:192px;">
<option <?php echo $drl; ?>>Driver's Licence (AUS)</option>
<option <?php echo $hcc; ?>>Healthcare Card</option>
<option <?php echo $mcc; ?>>Medicare Card</option>
<option <?php echo $ppt; ?>>Passport</option>
<option <?php echo $pnc; ?>>Pension Card</option>
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
<td><input type="text" id="display_physical1" readonly style="width:225px;" /></td>
</tr>
<tr>
<td><input type="hidden" id="physical" value="<?php echo $data["physical"]; ?>" /></td>
<td><input type="text" id="display_physical2" readonly style="width:225px;" /></td>
</tr>
<tr>
<td></td>
<td><input type="text" id="display_physical3" readonly style="width:45px;" /> <input type="text" id="display_physical4" readonly style="width:55px;" /></td>
</tr>
<tr>
<td width="85px"><a id="postal_link" onclick="Postal()" style="cursor:pointer; text-decoration:underline;">Postal</a> </td>
<td><input type="text" id="display_postal1" readonly style="width:225px;" /></td>
</tr>
<tr>
<td><input type="hidden" id="postal" value="<?php echo $data["postal"]; ?>" /></td>
<td><input type="text" id="display_postal2" readonly style="width:225px;" /></td>
</tr>
<tr>
<td></td>
<td><input type="text" id="display_postal3" readonly style="width:45px;" /> <input type="text" id="display_postal4" readonly style="width:55px; margin-right:12px;" /> <input type="checkbox" id="postal_same" onclick="Postal_Same()" style="height:auto;" /> Same as Above</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<?php
switch ($data["dd_type"])
{
	case "AMEX":
	$amex="selected";
	break;
	case "DINERS":
	$diners="selected";
	break;
	case "MASTERCARD":
	$mastercard="selected";
	break;
	case "VISA":
	$visa="selected";
	break;
}
?>
<tr>
<td colspan="2">
<table border="0" width="100%" style="margin-bottom:10px;">
<tr>
<td colspan="2"><img src="../images/other_details_header.png" width="105" height="15" style="padding-left:3px;"/></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" alt="line" /></td>
</tr>
<tr>
<td width="50%" height="100%" valign="top">
<table width="100%">
<tr>
<td width="115px">Ongoing Credit ($) </td>
<td><input type="text" id="ongoing_credit" style="width:50px;" value="<?php echo $data["ongoing_credit"]; ?>" /></td>
</tr>
<tr>
<td>Once Off Credit ($) </td>
<td><input type="text" id="onceoff_credit" style="width:50px;" value="<?php echo $data["onceoff_credit"]; ?>" /></td>
</tr>
</table>
</td>
<td width="50%" height="100%" valign="top">
<table width="100%">
<tr>
<td width="85px">Payway ID </td>
<td><input type="text" id="payway" style="width:150px;" value="<?php echo $data["payway"]; ?>" /></td>
</tr>
<tr>
<td>DD Type </td>
<td><select id="dd_type" style="width:150px;">
<option></option>
<option <?php echo $amex; ?>>AMEX</option>
<option <?php echo $diners; ?>>DINERS</option>
<option <?php echo $mastercard; ?>>MASTERCARD</option>
<option <?php echo $visa; ?>>VISA</option>
</select></td>
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
<th width="20%">CLI</th>
<th width="70%">Plan</th>
<th width="10%" colspan="2">Edit</th>
</tr>
</thead>
<tbody id="packages">
<script>
var id = "<?php echo $id; ?>";
$( "#packages" ).load('packages.php?id=' + id);
</script>
</tbody>
</table>
</div>
</td>
</tr>
<tr valign="bottom">
<td align="left" style="padding-left:5px;"><button onclick="Add_Package()" class="btn">Add Package</button></td>
<td align="right" style="padding-right:5px;"><button onclick="Notes()" class="btn" style="margin-right:10px;">Notes</button> <button onclick="Done()" class="btn">Done</button></td>
</tr>
</table>
</td>
</tr>
</table>