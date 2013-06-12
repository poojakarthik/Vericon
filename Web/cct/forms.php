<?php
include "../auth/iprestrict.php";

$q = mysql_query("SELECT * FROM auth WHERE user = '$user[0]'") 
  or die(mysql_error());
  
$r = mysql_fetch_assoc($q);

$date = date('d\/m\/Y');
$time = date('g:i A');
$name = $r["first"] . " " . $r["last"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Customer Care :: Forms</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<?php
include "../source/jquery.php";
?>
<style>
	label { margin-right:3px; }
	input { height:auto; padding-left:10px; }
	input.text { margin-bottom:12px; width:95%; padding: .4em; font-family:Tahoma, Geneva, sans-serif;
font-size:13px; }
	fieldset { padding:0; border:0; margin-top:25px; }
	h1 { font-size: 1.2em; margin: .6em 0; }
	div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
	div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
	.ui-dialog .ui-state-error { padding: .3em; }
	.validateTips { border: 1px solid transparent; padding: 0.3em; }
</style>
<script>
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var staff_name = "<?php echo $name; ?>",
		campaign = $( "#campaign" ),
		customer_name = $( "#customer_name" ),
		customer_contact = $( "#customer_contact" ),
		acc_number = $( "#acc_number" ),
		service_type = $( "#service_type" ),
		call_type = $( "#call_type" ),
		comments = $( "#comments" ),
		allFields = $( [] ).add( staff_name ).add( campaign ).add( customer_name ).add( customer_contact ).add( acc_number ).add( service_type ).add( call_type ).add( comments ),
		tips = $( ".validateTips" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-error" );
	}

	$( "#dialog-form" ).dialog({
		autoOpen: false,
		height: 485,
		width: 400,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Submit Form": function() {
				var bValid = true;
				if ( bValid ) {
					$.get("form_submit.php?method=create", { staff_name: staff_name, campaign: campaign.val(), customer_name: customer_name.val(), customer_contact: customer_contact.val(), acc_number: acc_number.val(), service_type: service_type.val(), call_type: call_type.val(), comments: comments.val()},
function(data) {
   
   if (data == "created")
   {
	   $( "#dialog-form" ).dialog( "close" );
	   location.reload();
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
			allFields.val( "" ).removeClass( "ui-state-error" );
		}
	});

	$( "#create-form" )
		.button()
		.click(function() {
			$( "#dialog-form" ).dialog( "open" );
		});
});
</script>
<script> //display
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-message" ).dialog({
		modal: true,
		autoOpen: false,
		resizable: false,
		draggable: false,
		height: 435,
		width: 400,
		
		buttons: {
			Ok: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
function Display(id,status,date,time,staff_name,campaign,customer_name,customer_contact,account_number,type,call,comments)
{
	$( "#d_id" ).text(id);
	$( "#d_status" ).text(status);
	$( "#d_date" ).text(date);
	$( "#d_time" ).text(time);
	$( "#d_staff_name" ).text(staff_name);
	$( "#d_campaign" ).text(campaign);
	$( "#d_customer_name" ).text(customer_name);
	$( "#d_customer_contact" ).text(customer_contact);
	$( "#d_account_number" ).text(account_number);
	$( "#d_type" ).text(type);
	$( "#d_call_type" ).text(call);
	$( "#d_comments" ).val(comments);
	$( "#dialog-message" ).dialog( "open" );
}
</script>
</head>

<body>
<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/cct_menu.php";
?>

<div id="text" class="demo">

<div id="dialog-message" title="Submitted Form Details">
<table>
<tr><td>ID: </td>
<td><p id="d_id" style="font-weight:bold;"></p></td></tr>
<tr><td>Status: </td>
<td><p id="d_status" style="font-weight:bold;"></p></td></tr>
<tr><td>Date: </td>
<td><p id="d_date" style="font-weight:bold;"></p></td></tr>
<tr><td>Time of Call: </td>
<td><p id="d_time" style="font-weight:bold;"></p></td></tr>
<tr><td>Staff Name: </td>
<td><p id="d_staff_name" style="font-weight:bold;"></p></td></tr>
<tr><td>Campaign: </td>
<td><p id="d_campaign" style="font-weight:bold;"></p></td></tr>
<tr><td>Customer Name: </td>
<td><p id="d_customer_name" style="font-weight:bold;"></p></td></tr>
<tr><td>Customer Contact: </td>
<td><p id="d_customer_contact" style="font-weight:bold;"></p></td></tr>
<tr><td>Account Number: </td>
<td><p id="d_account_number" style="font-weight:bold;"></p></td></tr>
<tr><td>Type of Service: </td>
<td><p id="d_type" style="font-weight:bold;"></p></td></tr>
<tr><td>Type of Call: </td>
<td><p id="d_call_type" style="font-weight:bold;"></p></td></tr>
</table><br />
<h2><u>Comments</u></h2><br />
<textarea id="d_comments" rows="5" style="width:350px; resize:none;" disabled="disabled"></textarea>
</div>

<p><img src="../images/cs_forms_header.png" width="100" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p><br />

<div id="dialog-form" title="Customer Solutions Form">
	<p class="validateTips"></p>

<table>
<form>
<tr><td>Date: </td>
<td><b><?php echo $date; ?></b></td></tr>
<tr><td>Time of Call: </td>
<td><b><?php echo $time; ?></b></td></tr>
<tr><td>Staff Name: </td>
<td><b><?php echo $name; ?></b></td></tr>
<tr><td>Campaign: </td>
<td><select id="campaign" name="campaign" style="margin:0; width:175px;">
<option></option>
<option>Complete Telecom</option>
<option>Fairtalk</option>
<option>Ourtel</option>
<option>Simplytel</option>
<option>Time Telecom</option>
<option>Touchtel</option>
<option>Valuetel</option>
<option>Yourtel</option>
<option>Telko</option>
</select></td></tr>
<tr><td>Customer Name: </td>
<td><input id="customer_name" name="customer_name" type="text" size="25" style="height:25px;"></td></tr>
<tr><td>Customer Contact: </td>
<td><input id="customer_contact" name="customer_contact" type="text" size="25" style="height:25px;"></td></tr>
<tr><td>Account Number: </td>
<td><input id="acc_number" name="acc_number" type="text" size="25" style="height:25px;"></td></tr>
<tr><td>Type of Service: </td>
<td><select id="service_type" name="service_type" style="margin:0; width:175px;">
<option></option>
<option>Phone</option>
<option>Internet</option>
<option>Mobile</option>
<option>Bundle</option>
</select></td></tr>
<tr><td>Type of Call: </td>
<td><select id="call_type" name="call_type" style="margin:0; width:175px;">
<option></option>
<option>Winback</option>
<option>Change Plan</option>
<option>Add Service</option>
<option>New Customer</option>
</select></td></tr>
</table>
<h2><u>Comments</u></h2><br />
<textarea id="comments" name="comments" rows="5" style="width:350px; resize:none;"></textarea>
</form>
</div>

<div id="users-contain" class="ui-widget">
	<table id="users" class="ui-widget ui-widget-content">
		<thead>
			<tr class="ui-widget-header ">
				<th>ID</th>
				<th>Date Submitted</th>
				<th>Status</th>
                <th>Customer Name</th>
                <th>Account Number</th>
                <th>Service Type</th>
                <th>Call Type</th>
			</tr>
		</thead>
		<tbody>
        <?php
		$q = mysql_query("SELECT * FROM csform WHERE staff_name = '$name' AND status = 'Queue'")
			  or die(mysql_error());

		if (mysql_num_rows($q) == 0)
		{
			echo "<tr>";
			echo "<td colspan='7' style=\"text-align:center\">No Forms Submitted</td>";
			echo "</tr>";
		}
		else
		{	
			while($r = mysql_fetch_assoc($q))
			{
				echo "<tr>";
				echo "<td><a onclick='Display(\"$r[id]\",\"$r[status]\",\"$r[date]\",\"$r[time]\",\"$r[staff_name]\",\"$r[campaign]\",\"$r[customer_name]\",\"$r[customer_contact]\",\"$r[account_number]\",\"$r[type]\",\"$r[call]\",\"" . str_replace("\n",'\n',$r[comments]) . "\")' style='cursor:pointer; text-decoration:underline;'>$r[id]</a></td>";
				echo "<td>" . $r["date"] . " " . $r["time"] . "</td>";
				echo "<td>" . $r["status"] . "</td>";
				echo "<td>" . $r["customer_name"] . "</td>";
				echo "<td>" . $r["account_number"] . "</td>";
				echo "<td>" . $r["type"] . "</td>";
				echo "<td>" . $r["call"] . "</td>";
				echo "</tr>";
			}
		}
		?>
		</tbody>
	</table>
</div>

<button id="create-form">Submit Form</button>

</div>

</div> 
<?php
include "../source/footer.php";
?>
</body>
</html>