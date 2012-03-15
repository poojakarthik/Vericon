<?php

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$q = mysql_query("SELECT * FROM sales_customers WHERE id = '$id'") or die(mysql_error());
$data = mysql_fetch_assoc($q);

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
	$( "#welcome_p" ).prop("checked", true);
	$( "#email" ).attr("disabled", true);
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

window.onload=function()
{
	setTimeout(enableIt,3000)
	if ( $( "#mobile" ) != null && $( "#mobile" ).val() == "N/A" )
	{
		$( "#no_mobile" ).prop("checked", true);
		$( "#mobile" ).attr("disabled", true);
	}
	
	if ( $( "#email" ) != null && $( "#email" ).val() == "N/A" )
	{
		$( "#billing_p" ).prop("checked", true);
		$( "#welcome_p" ).prop("checked", true);
		$( "#email" ).attr("disabled", true);
	}
	else
	{
		$( "#billing_e" ).prop("checked", true);
		$( "#welcome_e" ).prop("checked", true);
	}
	
	if ( "<?php echo $data["welcome"]; ?>" == "post" )
	{
		$( "#welcome_p" ).prop("checked", true);
	}
	else if ( "<?php echo $data["welcome"]; ?>" == "email" )
	{
		$( "#welcome_e" ).prop("checked", true);
	}
	
	if ( $( '#physical_address' ) != null )
	{
		var id = $( "#physical" );
		$( '#physical_address' ).load('../../tpv/address.php?id=' + id.val());
	}
	
	if ( $( '#postal_address' ) != null )
	{
		var id = $( "#postal" );
		$( '#postal_address' ).load('../../tpv/address.php?id=' + id.val());
	}

	if ( $( '#packages' ) != null )
	{
		var id = '<?php echo $id; ?>';
		$( '#packages' ).load('../../tpv/packages.php?id=' + id);
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

$input["name"] = $line . "<br><br><table border='0' width='100%'>
<tr><td width='95px'>Title<span style='color:#ff0000;'>*</span> </td><td><select id='title' style='margin-left:0px; margin-bottom: 0px; width:75px; height:25px; padding: 1px 0 0;'>
<option $mr>Mr</option>
<option $mrs>Mrs</option>
<option $miss>Miss</option>
<option $ms>Ms</option>
<option $dr>Dr</option>
</select></td></tr>
<tr><td width='95px'>First Name<span style='color:#ff0000;'>*</span> </td><td><input type='text' size='25' id='first' value='$firstname'></td></tr>
<tr><td width='95px'>Middle Name </td><td><input type='text' size='25' id='middle' value='$middlename'></td></tr>
<tr><td width='95px'>Last Name<span style='color:#ff0000;'>*</span> </td><td><input type='text' size='25' id='last' value='$lastname'></td></tr>
</table>";

$input["dob"] = $line . "<br><br><table border='0' width='100%'>
<tr><td width='95px'>D.O.B<span style='color:#ff0000;'>*</span> </td><td><input type='text' size='11' id='datepicker2' readonly='readonly' value='$dob1' /><input type='hidden' id='datepicker' value='$dob' /></td></tr>
</table>";

$input["id_info"] = $line . "<br><br><table border='0' width='100%'>
<tr><td width='95px'>ID Type<span style='color:#ff0000;'>*</span> </td><td><select id='id_type' style='margin-left:0px; margin-bottom: 0px; width:168px; height:25px; padding: 1px 0 0;'>
<option $drl>Driver's Licence (AUS)</option>
<option $mcc>Medicare Card</option>
<option $hcc>Healthcare Card</option>
<option $ppt>Passport</option>
<option $pnc>Pension Card</option>
</select></td></tr>
<tr><td width='95px'>ID Number<span style='color:#ff0000;'>*</span> </td><td><input type='text' size='25' id='id_num' value='$id_num'></td></tr>
</table>";

$input["physical"] = $line . "<input type='hidden' id='physical' value='$physical'><br><br>
<div id='physical_address'></div><br><input type='button' onclick='parent.Physical()' class='search' value=''>";

$input["postal"] = $line . "<input type='hidden' id='postal' value='$postal'><br><br>
<div id='postal_address'></div><br><input type='button' onclick='parent.Postal()' class='search' value=''>";

$input["mobile"] = $line . "<br><br><table border='0' width='100%'>
<tr><td width='95px'>Mobile<span style='color:#ff0000;'>*</span> </td><td><input type='text' size='25' id='mobile' value='$mobile' /> <input type='checkbox' id='no_mobile' onclick='Mobile()' style='height:auto;' /> <span>N/A</span></td></tr>
</table>";

$input["email"] = $line . "<br><br><table border='0' width='100%'>
<tr><td width='105px'>Billing<span style='color:#ff0000;'>*</span> </td><td><input type='radio' name='billing' id='billing_e' value='email' onclick='Email()' style='height:auto;' /> E-Bill &nbsp; <input type='radio' name='billing' id='billing_p' onclick='Post()' value='post' style='height:auto;' /> Post</td></tr>
<tr><td width='105px'>Welcome Letter<span style='color:#ff0000;'>*</span> </td><td><input type='radio' name='welcome' id='welcome_e' value='email' style='height:auto;' /> E-Mail &nbsp; <input type='radio' name='welcome' id='welcome_p' value='post' style='height:auto;' /> Post</td></tr>
<tr><td width='105px'>E-Mail<span style='color:#ff0000;'>*</span> </td><td><input type='text' size='25' id='email' value='$email' /></td></tr>
</table>";

$input["email2"] = $line . "<br><br><table border='0' width='100%'>
<tr><td width='95px'>E-Mail<span style='color:#ff0000;'>*</span> </td><td><input type='text' size='25' id='email2' value='$email' /></td></tr>
</table>";

$input["lines"] = $line . "<div class='demo'><div id='users-contain' class='ui-widget'>
<table id='users' class='ui-widget ui-widget-content' width='65%' style='margin-top:0px;'>
<thead>
<tr class='ui-widget-header'>
<th>CLI</th>
<th colspan='3'>Plan</th>
</tr>
</thead>
<tbody id='packages'>
</tbody>
</table>
<input type='button' onclick='Add_Package()' class='addpackage' />
</div></div>";

?>