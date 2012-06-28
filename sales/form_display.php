<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];
$user = $_GET["user"];
$id = $_GET["id"];

if ($method == "init")
{
?>
<div id="get_sale_table" style="margin-top:15px; margin-bottom:15px;">
<form onsubmit="event.preventDefault()">
<table>
<tr>
<td><p>Enter the Customer's Lead ID</p></td>
<td><input type="text" id="id" size="25" autocomplete="off" /></td>
<td><button type="submit" onclick="Get_Sale()" class="get_sale_btn"></button></td>
</tr>
</table>
</form>
<center><p class="error" style="color:#C00;"></p></center>
</div>
<br />
<p><img src="../images/submitted_sales_header.png" width="165" height="25" style="margin-left:3px;" /></p>
<p><img src="../images/line.png" width="715" height="9" /></p>
<div id="users-contain" class="ui-widget" style="width:100%; margin-left:5px; margin-top:-5px;">
<table id="users" class="ui-widget ui-widget-content" width="95%">
<thead>
<tr class="ui-widget-header ">
<th>Sale ID</th>
<th>Status</th>
<th>Date/Time</th>
<th>Customer Name</th>
</tr>
</thead>
<tbody>
<?php
$weekago = date("Y-m-d", strtotime("-1 week"));
$q = mysql_query("SELECT id,status,timestamp,firstname,lastname FROM vericon.sales_customers WHERE agent = '$user' AND DATE(timestamp) >= '$weekago' ORDER BY timestamp DESC") or die(mysql_error());
if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td colspan='4'><center>No Sales Submitted!</center></td>";
	echo "</tr>";
}
else
{
	while ($sales_data = mysql_fetch_assoc($q))
	{
		echo "<tr>";
		echo "<td>" . $sales_data["id"] . "</td>";
		echo "<td>" . $sales_data["status"] . "</td>";
		echo "<td>" . date("d/m/Y H:i", strtotime($sales_data["timestamp"])) . "</td>";
		echo "<td>" . $sales_data["firstname"] . " " . $sales_data["lastname"] . "</td>";
		echo "</tr>";
	}
}
?>
</tbody>
</table>
</div>
<?php
}
elseif ($method == "form")
{
	$q = mysql_query("SELECT * FROM vericon.auth WHERE user = '$user'") or die(mysql_error());
	$ac = mysql_fetch_assoc($q);
	
	$q1 = mysql_query("SELECT * FROM vericon.sales_customers_temp WHERE lead_id = '$id'") or die(mysql_error());
	$data = mysql_fetch_assoc($q1);
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
<td width="85px">Lead ID </td>
<td><b><?php echo $id; ?></b></td>
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
<td><b class="campaign"><?php echo $data["campaign"]; ?></b></td>
</tr>
<tr>
<td>Type </td>
<td><b><?php echo $data["type"]; ?></b></td>
</tr>
</table>
</td>
<td width="50%" valign="top">
<table border="0" width="100%" style="margin-bottom:10px;">
<?php
if ($data["type"] == "Business")
{
?>
<input type="hidden" id="sale_type" value="Business" />
<tr>
<td colspan="2"><img src="../images/business_identification_header.png" width="164" height="15" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="90%" height="9" alt="line" /></td>
</tr>
<tr>
<td width="85px">ABN<span style="color:#ff0000;">*</span> </td>
<td><input type="text" id="abn" onchange="getABN()" style="width:225px;" /></td>
</tr>
<tr>
<td>Position<span style="color:#ff0000;">*</span> </td>
<td><input type="text" id="position" style="width:225px;" /></td>
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
<input type="hidden" id="sale_type" value="Residential" />
<tr>
<td colspan="2"><img src="../images/customer_identification_header.png" width="172" height="15" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="90%" height="9" alt="line" /></td>
</tr>
<tr>
<td width="85px">ID Type<span style="color:#ff0000;">*</span> </td>
<td><select id="id_type" style="width:192px;">
<option></option>
<option>Driver's Licence (AUS)</option>
<option>Healthcare Card</option>
<option>Medicare Card</option>
<option>Passport</option>
<option>Pension Card</option>
</select></td>
</tr>
<tr>
<td>ID Number<span style="color:#ff0000;">*</span> </td>
<td><input type="text" id="id_num" style="width:190px;" /></td>
</tr>
<?php
}
?>
</table>
</td>
</tr>
<tr>
<td width="50%" height="100%" valign="top">
<table border="0" width="100%" style="margin-bottom:10px;">
<tr>
<td colspan="2"><img src="../images/customer_details_header.png" width="128" height="15" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="80%" height="9" alt="line" /></td>
</tr>
<tr>
<td width="85px">Title<span style="color:#ff0000;">*</span> </td>
<td><select id="title" style="width:50px;">
<option></option>
<option>Mr</option>
<option>Mrs</option>
<option>Miss</option>
<option>Ms</option>
<option>Dr</option>
</select></td>
</tr>
<tr>
<td>First Name<span style="color:#ff0000;">*</span> </td>
<td><input type="text" id="first" value="" style="width:150px;" /></td>
</tr>
<tr>
<td>Middle Name </td>
<td><input type="text" id="middle" value="" style="width:150px;" /></td>
</tr>
<tr>
<td>Last Name<span style="color:#ff0000;">*</span> </td>
<td><input type="text" id="last" value="" style="width:150px;" /></td>
</tr>
<tr>
<td>D.O.B<span style="color:#ff0000;">*</span> </td>
<td><input type="text" id="datepicker2" readonly style="width:80px;" /> <input type="hidden" id="datepicker" /></td>
</tr>
<tr>
<td>E-Mail<span style="color:#ff0000;">*</span> </td>
<td><input type="text" id="email" style="width:150px;" /> <input type="checkbox" id="no_email" onclick="Email()" style="height:auto;" /> N/A</td>
</tr>
<tr>
<td>Mobile<span style="color:#ff0000;">*</span> </td>
<td><input type="text" id="mobile" style="width:150px;" /> <input type="checkbox" id="no_mobile" onclick="Mobile()" style="height:auto;" /> N/A</td>
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
<td width="85px"><a onclick="Physical()" style="cursor:pointer; text-decoration:underline;">Physical</a><span style="color:#ff0000;">*</span> </td>
<td><input type="text" id="display_physical1" readonly style="width:225px;" /></td>
</tr>
<tr>
<td><input type="hidden" id="physical" value="" /></td>
<td><input type="text" id="display_physical2" readonly style="width:225px;" /></td>
</tr>
<tr>
<td></td>
<td><input type="text" id="display_physical3" readonly style="width:45px;" /> <input type="text" id="display_physical4" readonly style="width:55px;" /></td>
</tr>
<tr>
<td width="85px"><a id="postal_link" onclick="Postal()" style="cursor:pointer; text-decoration:underline;">Postal</a><span style="color:#ff0000;">*</span> </td>
<td><input type="text" id="display_postal1" readonly style="width:225px;" /></td>
</tr>
<tr>
<td><input type="hidden" id="postal" value="" /></td>
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
<table id="users" class="ui-widget ui-widget-content" width="99%">
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
<td align="left"><button onclick="Add_Package()" class="btn">Add Package</button></td>
<td align="right"><button onclick="Submit()" class="btn" style="margin-right:10px;">Submit</button><button onclick="Cancel()" class="btn" style="margin-right:10px;">Cancel</button></td>
</tr>
</table>
</td>
</tr>
</table>
<?php
}
?>