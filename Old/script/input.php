<?php
$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
$data = mysql_fetch_assoc($q);

$q1 = mysql_query("SELECT country FROM vericon.campaigns WHERE campaign = '" . mysql_real_escape_string($data["campaign"]) . "'") or die(mysql_error());
$country = mysql_fetch_row($q1);

//input variables
$abn = $data["abn"];
$position = $data["position"];
$title = $data["title"];
$firstname = $data["firstname"];
$middlename = $data["middlename"];
$lastname = $data["lastname"];
$dob = $data["dob"];
$dob1 = date("d/m/Y", strtotime($data["dob"]));
$id_type = $data["id_type"];
$id_num = $data["id_num"];
$physical = $data["physical"];
$postal = $data["postal"];
$mobile = $data["mobile"];
$email = $data["email"];
$best_buddy = $data["best_buddy"];
$bus_name = $data["bus_name"];
$modem_address = $data["modem_address"];

switch ($title)
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
	case "Community Services Card":
	$csc="selected";
	break;
	case "Driver's Licence (NZ)":
	$dsl="selected";
	break;
	case "Gold Card":
	$gdc="selected";
	break;
}

if ($data["billing"] == "post") { $dd_discount = "\$3"; } elseif ($data["billing"] == "email") { $dd_discount = "\$5"; }
?>
<script>
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

if ( $( "#mobile" ) != null && $( "#mobile" ).val() == "N/A" )
{
	$( "#no_mobile" ).prop("checked", true);
	$( "#mobile" ).attr("disabled", true);
}

if ( $( "#email" ) != null && $( "#email" ).val() == "N/A" )
{
	$( "#no_email" ).prop("checked", true);
	$( "#email" ).attr("disabled", true);
}

if ( "<?php echo $data["promotions"]; ?>" == "N" )
{
	$( "#promotions_n" ).prop("checked", true);
}
else if ( "<?php echo $data["promotions"]; ?>" == "Y" )
{
	$( "#promotions_y" ).prop("checked", true);
}

<?php if ($country[0] == "AU") { ?>
if ( $( '#physical' ).val() != undefined )
{
	$.get("../source/gnafGet.php?type=display", { id: "<?php echo $data["physical"]; ?>" }, function(data) {
		var n = data.split("}");
		$( "#display_physical1" ).val(n[0]);
		$( "#display_physical2" ).val(n[1]);
		$( "#display_physical3" ).val(n[2]);
		$( "#display_physical4" ).val(n[3]);
	});
}

if ( $( '#postal' ).val() != undefined )
{
	if ("<?php echo $data["physical"]; ?>" == "<?php echo $data["postal"]; ?>")
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
}
<?php } elseif ($country[0] == "NZ") { ?>
if ( $( '#physical' ).val() != undefined )
{
	$.get("../source/tlGet.php?type=display", { id: "<?php echo $data["physical"]; ?>" }, function(data) {
		var n = data.split("}");
		$( "#display_physical1" ).val(n[0]);
		$( "#display_physical2" ).val(n[1]);
		$( "#display_physical3" ).val(n[2]);
		$( "#display_physical4" ).val(n[3]);
	});
}

if ( $( '#postal' ).val() != undefined )
{
	if ("<?php echo $data["physical"]; ?>" == "<?php echo $data["postal"]; ?>")
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
		$( "#postal_same" ).prop("checked", true);
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
}
<?php
}
?>

function Modem_Physical()
{
	if ($('#modem_physical').attr('checked'))
	{
		$( "#modem_postal" ).prop("checked", false);
		$( "#modem_address" ).val("Same as Physical");
		$( "#modem_address" ).attr("disabled", true);
	}
	else
	{
		$( "#modem_address" ).val("");
		$( "#modem_address" ).removeAttr("disabled");
	}
}

function Modem_Postal()
{
	if ($('#modem_postal').attr('checked'))
	{
		$( "#modem_physical" ).prop("checked", false);
		$( "#modem_address" ).val("Same as Postal");
		$( "#modem_address" ).attr("disabled", true);
	}
	else
	{
		$( "#modem_address" ).val("");
		$( "#modem_address" ).removeAttr("disabled");
	}
}

if ( $( "#modem_address" ) != null && $( "#modem_address" ).val() == "Same as Physical" )
{
	$( "#modem_physical" ).prop("checked", true);
	$( "#modem_address" ).attr("disabled", true);
}
else if ( $( "#modem_address" ) != null && $( "#modem_address" ).val() == "Same as Postal" )
{
	$( "#modem_postal" ).prop("checked", true);
	$( "#modem_address" ).attr("disabled", true);
}

if ( $( '#packages' ) != null )
{
	var id = '<?php echo $id; ?>';
	$( '#packages' ).load('../../tpv/packages_<?php echo strtolower($country[0]); ?>.php?id=' + id);
}

if ( $( "#abn" ).val() != undefined )
{
	$.getJSON("../../source/abrGet.php", {abn: $("#abn").val() },
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

<?php
$line = "<img src='../../images/line.png' width='50%' height='9' style='margin-top:3px;' />";

$input["bus_info"] = $line . "<br><br><table border='0' width='100%'>
<tr><td width='95px'>ABN<span style='color:#ff0000;'>*</span> </td><td><input type='text' size='25' id='abn' onchange='getABN()' value='$abn'></td></tr>
<tr><td width='95px'>Position<span style='color:#ff0000;'>*</span> </td><td><input type='text' size='25' id='position' value='$position'></td></tr>
<tr><td width='95px'>Business Name</td><td><b class='bus_name' style='font-size:9px;'></b></td></tr>
<tr><td width='95px'>ABN Status</td><td><b class='abn_status' style='font-size:9px;'></b></td></tr>
<tr><td width='95px'>Business Type</td><td><b class='bus_type' style='font-size:9px;'></b></td></tr>
</table>";

$input["bus_info2"] = $line . "<br><br><table border='0' width='100%'>
<tr><td width='95px'>Business Name<span style='color:#ff0000;'>*</span> </td><td><input type='text' id='bus_name' size='25' value='$bus_name'></td></tr>
<tr><td width='95px'>Position<span style='color:#ff0000;'>*</span> </td><td><input type='text' size='25' id='position' value='$position'></td></tr>
</table>";

$input["name"] = $line . "<br><br><table border='0' width='100%'>
<tr><td width='95px'>Title<span style='color:#ff0000;'>*</span> </td><td><select id='title' style='width:50px;'>
<option $mr>Mr</option>
<option $mrs>Mrs</option>
<option $miss>Miss</option>
<option $ms>Ms</option>
<option $dr>Dr</option>
</select></td></tr>
<tr><td width='95px'>First Name<span style='color:#ff0000;'>*</span> </td><td><input type='text' id='first' style='width:150px;' value='$firstname'></td></tr>
<tr><td width='95px'>Middle Name </td><td><input type='text' id='middle' style='width:150px;' value='$middlename'></td></tr>
<tr><td width='95px'>Last Name<span style='color:#ff0000;'>*</span> </td><td><input type='text' id='last' style='width:150px;' value='$lastname'></td></tr>
</table>";

$input["dob"] = $line . "<br><br><table border='0' width='100%'>
<tr><td width='95px'>D.O.B<span style='color:#ff0000;'>*</span> </td><td><input type='text' size='11' id='datepicker2' readonly='readonly' value='$dob1' /><input type='hidden' id='datepicker' value='$dob' /></td></tr>
</table>";

$input["id_info"] = $line . "<br><br><table border='0' width='100%'>
<tr><td width='95px'>ID Type<span style='color:#ff0000;'>*</span> </td><td><select id='id_type' style='width:192px;'>
<option $drl>Driver's Licence (AUS)</option>
<option $mcc>Medicare Card</option>
<option $hcc>Healthcare Card</option>
<option $ppt>Passport</option>
<option $pnc>Pension Card</option>
</select></td></tr>
<tr><td width='95px'>ID Number<span style='color:#ff0000;'>*</span> </td><td><input type='text' id='id_num' value='$id_num' style='width: 190px;'></td></tr>
</table>";

$input["id_info2"] = $line . "<br><br><table border='0' width='100%'>
<tr><td width='95px'>ID Type<span style='color:#ff0000;'>*</span> </td><td><select id='id_type' style='width:192px;'>
<option $csc>Community Services Card</option>
<option $dsl>Driver's Licence (NZ)</option>
<option $gdc>Gold Card</option>
<option $ppt>Passport</option>
</select></td></tr>
<tr><td width='95px'>ID Number<span style='color:#ff0000;'>*</span> </td><td><input type='text' id='id_num' value='$id_num' style='width: 190px;'></td></tr>
</table>";

$input["physical"] = $line . "<input type='hidden' id='physical' value='$physical'><br><br>
<table width='100%'>
<tr><td><input type='text' id='display_physical1' readonly style='width:225px;' /></td></tr>
<tr><td><input type='text' id='display_physical2' readonly style='width:225px;' /></td></tr>
<tr><td><input type='text' id='display_physical3' readonly style='width:45px;' /> <input type='text' id='display_physical4' readonly style='width:55px;' /></td></tr>
</table><br><button onclick='Physical()' class='btn'>Search</button>";

$input["physical2"] = $line . "<input type='hidden' id='physical' value='$physical'><br><br>
<table width='100%'>
<tr><td><input type='text' id='display_physical1' readonly style='width:225px;' /></td></tr>
<tr><td><input type='text' id='display_physical2' readonly style='width:225px;' /></td></tr>
<tr><td><input type='text' id='display_physical3' readonly style='width:164px;' /> <input type='text' id='display_physical4' readonly style='width:55px;' /></td></tr>
</table><br><button onclick='Physical()' class='btn'>Search</button>";

$input["postal"] = $line . "<input type='hidden' id='postal' value='$postal'><br><br>
<table width='100%'>
<tr><td><input type='text' id='display_postal1' readonly style='width:225px;' /></td></tr>
<tr><td><input type='text' id='display_postal2' readonly style='width:225px;' /></td></tr>
<tr><td><input type='text' id='display_postal3' readonly style='width:45px;' /> <input type='text' id='display_postal4' readonly style='width:55px;' /> <input type='checkbox' id='postal_same' onclick='Postal_Same()' style='height:auto;' /> Same as Physical</td></tr>
</table><br><button onclick='Postal()' id='postal_link' class='btn'>Search</button>";

$input["postal2"] = $line . "<input type='hidden' id='postal' value='$postal'><br><br>
<table width='100%'>
<tr><td><input type='text' id='display_postal1' readonly style='width:225px;' /></td></tr>
<tr><td><input type='text' id='display_postal2' readonly style='width:225px;' /></td></tr>
<tr><td><input type='text' id='display_postal3' readonly style='width:164px;' /> <input type='text' id='display_postal4' readonly style='width:55px;' /></td></tr>
</table><br><button onclick='Postal()' id='postal_link' class='btn'>Search</button> <input type='checkbox' id='postal_same' onclick='Postal_Same()' style='height:auto;' /> Same as Physical";

$input["mobile"] = $line . "<br><br><table border='0' width='100%'>
<tr><td width='95px'>Mobile<span style='color:#ff0000;'>*</span> </td><td><input type='text' size='25' id='mobile' value='$mobile' /> <input type='checkbox' id='no_mobile' onclick='Mobile()' style='height:auto;' /> <span>N/A</span></td></tr>
</table>";

$input["mobile2"] = $line . "<br><br><table border='0' width='100%'>
<tr><td width='95px'>Mobile<span style='color:#ff0000;'>*</span> </td><td><input type='text' size='25' id='mobile' value='$mobile' /> <input type='checkbox' id='no_mobile' onclick='Mobile()' style='height:auto;' /> <span>N/A</span></td></tr>
</table>";

$input["email"] = $line . "<br><br><table border='0' width='100%'>
<tr><td width='105px'>E-Mail<span style='color:#ff0000;'>*</span> </td><td><input type='text' size='25' id='email' value='$email' /> <input type='checkbox' id='no_email' onclick='Email()' style='height:auto;' /> <span>N/A</span></td></tr>
<tr><td width='105px'>Promotions<span style='color:#ff0000;'>*</span> </td><td><input type='radio' id='promotions_y' name='promotions' value='Y' style='height:auto;' /> Yes &nbsp; <input type='radio' id='promotions_n' name='promotions' value='N' style='height:auto;' /> No</td></tr>
</table>";

$input["email2"] = $line . "<br><br><table border='0' width='100%'>
<tr><td width='95px'>E-Mail<span style='color:#ff0000;'>*</span> </td><td><input type='text' size='25' id='email2' value='$email' /></td></tr>
<tr><td width='105px'>Promotions<span style='color:#ff0000;'>*</span> </td><td><input type='radio' id='promotions_y' name='promotions' value='Y' style='height:auto;' /> Yes &nbsp; <input type='radio' id='promotions_n' name='promotions' value='N' style='height:auto;' /> No</td></tr>
</table>";

$input["lines"] = $line . "<div id='users-contain' class='ui-widget'>
<table id='users' class='ui-widget ui-widget-content' width='100%' style='margin-top:0px;'>
<thead>
<tr class='ui-widget-header'>
<th width='20%'>CLI</th>
<th width='70%'>Plan</th>
<th width='10%' colspan='2'>Edit</th>
</tr>
</thead>
<tbody id='packages'>
</tbody>
</table>
<button onclick='Add_Package()' class='btn'>Add Package</button>
</div>";

$input["lines2"] = $line . "<div id='users-contain' class='ui-widget'>
<table id='users' class='ui-widget ui-widget-content' width='100%' style='margin-top:0px;'>
<thead>
<tr class='ui-widget-header'>
<th width='12%'>CLI</th>
<th width='30%'>Plan</th>
<th width='28%'>Provider</th>
<th width='20%'>Account Number</th>
<th width='10%' colspan='2' style='text-align:center;'>Edit</th>
</tr>
</thead>
<tbody id='packages'>
</tbody>
</table>
<button onclick='Add_Package()' class='btn'>Add Package</button>
</div>";

$input["best_buddy"] = $line . "<br><br><table border='0' width='100%'>
<tr><td width='95px'>Best Buddy<span style='color:#ff0000;'>*</span> </td><td><input type='text' size='25' id='best_buddy' value='$best_buddy' /></td></tr>
</table>";

$input["modem_delivery"] = $line . "<br><br><table border='0' width='100%'>
<tr><td width='105px'>Modem Address<span style='color:#ff0000;'>*</span> </td><td><input type='text' size='50' id='modem_address' value='$modem_address' /></tr>
<tr><td></td><td><input type='checkbox' id='modem_physical' onclick='Modem_Physical()' style='height:auto;' /> <span>Same as Physical</span></td></tr>
<tr><td></td><td><input type='checkbox' id='modem_postal' onclick='Modem_Postal()' style='height:auto;' /> <span>Same as Postal</span></td></tr>
</table>";
?>