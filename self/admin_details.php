<style>
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

div#users-contain table { margin: 1em 0; border-collapse: collapse; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
</style>
<script> //init form
function Get_Sale()
{
	var id = $( "#id" );
	var centre = "<?php echo $ac["centre"]; ?>";
	
	$.get("admin_submit.php?method=get", { id: id.val(), centre: centre },
	function(data) {
	   
	   if (data == "valid")
	   {
			var link = "admin.php?p=details&id=" + id.val();
			window.location = link;
	   }
	   else
	   {
			$( ".error" ).html(data);
	   }
	});
}
</script>
<script> //cancel form
function Cancel()
{
	window.location = "admin.php?p=details";
}
</script>

<div style="display:none;">
<img src="../images/getsale_btn_hover.png" /><img src="../images/cancel_form_btn_hover.png" />
</div>

<?php
if ($_GET["id"] == "")
{
?>
<div id="get_sale_table" style="margin-top:75px; margin-bottom:75px;">
<form onsubmit="event.preventDefault()">
    <table>
        <tr>
            <td><p>Enter the Customer's Sale ID</p></td>
            <td><input type="text" name="id" id="id" size="25"/></td>
            <td><input type="submit" class="get_sale_btn" onclick="Get_Sale()" value=""/></td>
        </tr>
    </table>
</form>
    <center><p class="error" style="color:#C00;"></p></center>
</div>
<p><img src="../images/sales_export_header.png" width="135" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p><br />

<a style="color:#666;" href="sales_export.php?user=<?php echo $ac["user"] ?>" target="_blank"><?php echo $ac["centre"] . "_Sales_" . date("d_m_Y") . ".xls" ?></a>
<?php
}
else
{
	$id = $_GET["id"];
	
	$q = mysql_query("SELECT * FROM sales_customers WHERE id = '$id'") or die(mysql_error());
	$data = mysql_fetch_assoc($q);
	
	$q2 = mysql_query("SELECT * FROM auth WHERE user = '$data[agent]'") or die(mysql_error());
	$data2 = mysql_fetch_assoc($q2);
	
	if ($data["status"] == "Line Issue")
	{
		$status_text = "line_issue";
	}
	else
	{
		$status_text = strtolower($data["status"]);
	}
?>
<script> //get ABN
window.onload=function()
{
	$.getJSON("../../source/abrGet.php", {abn: $(".abn").html() },
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

<table border="0" width="100%">
<tr>
<td width="50%" valign="top">
<table border="0" width="100%">
<tr>
<td colspan="2"><img src="../images/sale_details_header.png" width="90" height="15" /><img src="../images/<?php echo $status_text; ?>_header.png" width="100" height="15" style="float:right; margin-right:80px;" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="80%" height="9" alt="line" /></td>
</tr>
<tr>
<td width="85px">Lead ID </td>
<td><b><?php echo $data["lead_id"]; ?></b></td>
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
<td><b><?php echo $data["campaign"]; ?></b></td>
</tr>
<tr>
<td>Type </td>
<td><b><?php echo $data["type"]; ?></b></td>
</tr>
</table>
</td>
<td width="50%" height="100%" valign="top">
<?php
$physical = $data["physical"];
$postal = $data["postal"];

if (substr($physical,0,2) == "GA")
{
	$q3 = mysql_query("SELECT * FROM gnaf WHERE address_detail_pid = '$physical'") or die(mysql_error());
	$data3 = mysql_fetch_assoc($q3);
	
	if ($data3["FLAT_NUMBER"] != 0) { $d_number = $data3["FLAT_TYPE_CODE"] . " " . $data3["FLAT_NUMBER"] . "/"; } elseif ($data3["LEVEL_NUMBER"] != 0) { $d_number = "LVL " . $data3["LEVEL_NUMBER"] . "/"; }
	if ($data3["NUMBER_LAST"] != 0) { $d_number = $d_number . $data3["NUMBER_FIRST"] . "-" . $data3["NUMBER_LAST"]; } else { $d_number = $d_number . $data3["NUMBER_FIRST"] . $data3["NUMBER_FIRST_SUFFIX"]; }
	if ($data3["STREET_SUFFIX_CODE"] != "") { $d_street = $data3["STREET_NAME"] . " " . $data3["STREET_TYPE_CODE"] . " " . $data3["STREET_SUFFIX_CODE"]; } else{ $d_street = $data3["STREET_NAME"] . " " . $data3["STREET_TYPE_CODE"]; }
	
	$street = $d_number . " " . $d_street;
	$suburb = $data3["LOCALITY_NAME"];
	$state = $data3["STATE"];
	$postcode = $data3["POSTCODE"];
}
elseif (substr($physical,0,2) == "MA")
{
	$q3 = mysql_query("SELECT * FROM address WHERE id = '$physical'") or die(mysql_error());
	$data3 = mysql_fetch_assoc($q3);
	
	$street = $data3["street"];
	$suburb = $data3["suburb"];
	$state = $data3["state"];
	$postcode = $data3["postcode"];
}

if (substr($postal,0,2) == "GA")
{
	$q3 = mysql_query("SELECT * FROM gnaf WHERE address_detail_pid = '$postal'") or die(mysql_error());
	$data3 = mysql_fetch_assoc($q3);
	
	if ($data3["FLAT_NUMBER"] != 0) { $d_number_p = $data3["FLAT_TYPE_CODE"] . " " . $data3["FLAT_NUMBER"] . "/"; } elseif ($data3["LEVEL_NUMBER"] != 0) { $d_number_p = "LVL " . $data3["LEVEL_NUMBER"] . "/"; }
	if ($data3["NUMBER_LAST"] != 0) { $d_number_p = $d_number_p . $data3["NUMBER_FIRST"] . "-" . $data3["NUMBER_LAST"]; } else { $d_number_p = $d_number_p . $data3["NUMBER_FIRST"] . $data3["NUMBER_FIRST_SUFFIX"]; }
	if ($data3["STREET_SUFFIX_CODE"] != "") { $d_street_p = $data3["STREET_NAME"] . " " . $data3["STREET_TYPE_CODE"] . " " . $data3["STREET_SUFFIX_CODE"]; } else{ $d_street_p = $data3["STREET_NAME"] . " " . $data3["STREET_TYPE_CODE"]; }
	
	$street_p = $d_number_p . " " . $d_street_p;
	$suburb_p = $data3["LOCALITY_NAME"];
	$state_p = $data3["STATE"];
	$postcode_p = $data3["POSTCODE"];
}
elseif (substr($postal,0,2) == "MA")
{
	$q3 = mysql_query("SELECT * FROM address WHERE id = '$postal'") or die(mysql_error());
	$data3 = mysql_fetch_assoc($q3);
	
	$street_p = $data3["street"];
	$suburb_p = $data3["suburb"];
	$state_p = $data3["state"];
	$postcode_p = $data3["postcode"];
}
?>
<table border="0" width="100%">
<tr>
<td colspan="2"><img src="../images/customer_address_header.png" width="136" height="15" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="90%" height="9" alt="line" /></td>
</tr>
<tr>
<td width="85px">Physical</td>
<td><b><?php echo $street; ?></b></td>
</tr>
<tr>
<td></td>
<td><b><?php echo $suburb . ", " . $state . ", " . $postcode; ?></b></td>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
</tr>
<tr>
<td>Postal </td>
<td><b><?php echo $street_p; ?></b></td>
</tr>
<tr>
<td></td>
<td><b><?php echo $suburb_p . ", " . $state_p . ", " . $postcode_p; ?></b></td>
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
<td width="85px">Title </td>
<td><b><?php echo $data["title"] ?></b></td>
</tr>
<tr>
<td>First Name </td>
<td><b><?php echo $data["firstname"] ?></b></td>
</tr>
<tr>
<td>Middle Name </td>
<td><b><?php echo $data["middlename"] ?></b></td>
</tr>
<tr>
<td>Last Name </td>
<td><b><?php echo $data["lastname"] ?></b></td>
</tr>
<tr>
<td>D.O.B </td>
<td><b><?php echo date("d/m/Y", strtotime($data["dob"])) ?></b></td>
</tr>
<tr>
<td>Billing </td>
<td><b><?php echo strtoupper(substr($data["billing"],0,1)) . substr($data["billing"],1) ?></b></td>
</tr>
<tr>
<td>Welcome </td>
<td><b><?php echo strtoupper(substr($data["welcome"],0,1)) . substr($data["welcome"],1) ?></b></td>
</tr>
<tr>
<td>E-Mail </td>
<td><b><?php echo $data["email"] ?></b></td>
</tr>
<tr>
<td>Mobile </td>
<td><b><?php echo $data["mobile"] ?></b></td>
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
<tr>
<td colspan="2"><br /><img src="../images/business_identification_header.png" width="164" height="15" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="90%" height="9" alt="line" /></td>
</tr>
<tr>
<td width="85px">ABN </td>
<td><b class="abn"><?php echo $data["abn"]; ?></b></td>
</tr>
<tr>
<td>Position </td>
<td><b><?php echo $data["position"]; ?></b></td>
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
?>
<tr>
<td colspan="2"><br /><img src="../images/customer_identification_header.png" width="172" height="15" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="90%" height="9" alt="line" /></td>
</tr>
<tr>
<td width="85px">ID Type </td>
<td><b><?php echo $data["id_type"] ?></b></td>
</tr>
<tr>
<td>ID Number </td>
<td><b><?php echo $data["id_num"] ?></b></td>
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
</tr>
</thead>
<tbody id="packages">
<script>
var id = "<?php echo $id; ?>";
$( "#packages" ).load('../qa/packages.php?id=' + id);
</script>
</tbody>
</table>
</div>
</td>
</tr>
<tr valign="bottom">
<td align="right"><input type="button" onclick="Cancel()" class="cancelform" /></td>
</tr>
</table>
</td>
</tr>
</table>

<?php
}
?>