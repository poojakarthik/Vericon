<?php
include("../auth/restrict_inner.php");

$id = $_POST["lead"];
$q = $mysqli->query("SELECT `sales_customers_temp`.`id`, `sales_customers_temp`.`user`, `sales_customers_temp`.`centre`, `sales_customers_temp`.`type`, CONCAT(`auth`.`first`,' ',`auth`.`last`) AS name, `campaigns`.`campaign` FROM `vericon`.`sales_customers_temp`, `vericon`.`auth`, `vericon`.`campaigns` WHERE `sales_customers_temp`.`id` = '" . $mysqli->real_escape_string($id) . "' AND `sales_customers_temp`.`user` = `auth`.`user` AND `sales_customers_temp`.`campaign` = `campaigns`.`id`") or die($mysqli->error);
$data = $q->fetch_assoc();
$q->free();
?>
<style>
div#users-contain table { border-collapse: collapse; background:none; }
div#users-contain table th { border: 1px solid rgba(41,171,226,0.25); padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid rgba(41,171,226,0.25); padding: .6em 5px; text-align: left; }
div#users-contain table tbody tr:hover { background:rgba(255,255,255,0.25); }
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
		$.post( "/source/plans_au.php", { campaign: "<?php echo $data["campaign"]; ?>", type: "<?php echo $data["type"]; ?>", cli: cli.val() }, function(data) {
			plan.html(data);
			plan.removeAttr("disabled");
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

<div class="head">
<table>
<tr>
<td style="width:90px;"><div class="dotted"></div></td>
<td valign="middle" nowrap="nowrap" width="1px"><h1>Sales Form</h1></td>
<td><div class="dotted"></div></td>
</tr>
</table>
</div>

<center><table width="98%">
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
<td><input type="text" autocomplete="off" id="first" /></td>
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
<td><input type="text" autocomplete="off" id="email" /><input type="checkbox" id="no_email" onclick="Email()" /><label for="no_email"></label> N/A</td>
</tr>
<tr>
<td><b>Mobile <span style="color:#ff0000;">*</span></b></td>
<td><input type="text" autocomplete="off" id="mobile" /><input type="checkbox" id="no_mobile" onclick="Mobile()" /><label for="no_mobile"></label> N/A</td>
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
<th width="3%" style="text-align:center;">#</th>
<th width="25%">CLI</th>
<th width="72%">Plan</th>
</tr>
</thead>
<tbody>
<tr>
<td style="text-align:center;">01</td>
<td><input type="text" autocomplete="off" id="cli_1" onchange="Plan_Load(1)" style="width:200px;" /></td>
<td><select id="plan_1" disabled="disabled" style="width:400px;">
<option></option>
</select></td>
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
<td align="left"><button onclick="" class="btn">Add Package</button></td>
</tr>
</table>
</td>
</tr>
</table></center>