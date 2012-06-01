<?php
include "../auth/iprestrict.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Operations :: OP Payable</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<?php
include "../source/jquery.php";
?>
<style>
.edit
{
	background-image:url('../images/edit_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
}

.edit:hover
{
	background-image:url('../images/edit_btn_hover.png');
	cursor:pointer;
}

.done
{
	background-image:url('../images/done_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
}

.done:hover
{
	background-image:url('../images/done_btn_hover.png');
	cursor:pointer;
}

div#users-contain table { margin: 1em 0; margin-bottom:0; border-collapse: collapse; width:98% }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: center; }
div#users-contain table td { border: 1px solid #eee; padding: .1em 10px; text-align: center; }
</style>
<script>
function Centre()
{
	var centre = $( "#centre" ),
		date = $( "#datepicker" );

	$( "#display" ).load('payable_display.php?centre=' + centre.val() + '&date=' + date.val());
}
</script>
<script>
$(function() {
	$( "#datepicker" ).datepicker( {
		showOn: "button",
		buttonImage: "../images/calendar.gif",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		firstDay: 1,
		showOtherMonths: true,
		selectOtherMonths: true,
		changeMonth: true,
		changeYear: true,
		maxDate: "<?php echo date("Y-m-d", strtotime(date("Y")."W".(date("W") - 1)."7")); ?>",
		onSelect: function(dateText, inst) {
			var centre = $( "#centre" );
			
			$.get("payable_process.php", { method: "from", date: dateText }, function (data) { $( "#from" ).val(data); });
			$.get("payable_process.php", { method: "to", date: dateText }, function (data) { $( "#to" ).val(data); });
			$( "#display" ).load('payable_display.php?centre=' + centre.val() + '&date=' + dateText);
		}});
});
</script>
<script>
function Edit_View()
{
	var centre = $( "#centre" ),
		date = $( "#datepicker" );
	$.get("payable_process.php", {method: "check", date: date.val() }, function (data) {
		if (data == "valid")
		{
			$( "#display" ).load('payable_edit.php?centre=' + centre.val() + '&date=' + date.val());
		}
		else
		{
			alert(data);
		}
	});
}
</script>
<script>
function Done()
{
	var centre = $( "#centre" ),
		date = $( "#datepicker" );

	$( "#display" ).load('payable_display.php?centre=' + centre.val() + '&date=' + date.val());
}
</script>
<script>
function Hours(user)
{
	var field = "#" + user + "_hours",
		field2 = "#" + user + "_cps",
		hours = $( field ),
		date = $( "#datepicker" );

	function checkRegexp( o, regexp, n ) {
		if ( !( regexp.test( o.val() ) ) ) {
			alert( n );
			o.val("");
			return false;
		}
		else {
			return true;
		}
	}

	bValid = checkRegexp( hours, /^([0-9.])+$/, "'" + hours.val() + "' is not a valid input" );
	
	if (bValid)
	{
		$.get("payable_process.php", { method: "hours", date: date.val(), user: user, hours: hours.val() }, function (data) { 
			$( field2 ).html(data);
		});
	}
}
</script>
<script>
function Bonus(user)
{
	var field = "#" + user + "_bonus",
		field2 = "#" + user + "_cps",
		bonus = $( field ),
		date = $( "#datepicker" );

	function checkRegexp( o, regexp, n ) {
		if ( !( regexp.test( o.val() ) ) ) {
			alert( n );
			o.val("");
			return false;
		}
		else {
			return true;
		}
	}

	bValid = checkRegexp( bonus, /^([0-9.])+$/, "'" + bonus.val() + "' is not a valid input" );
	
	if (bValid)
	{
		$.get("payable_process.php", { method: "bonus", date: date.val(), user: user, bonus: bonus.val() }, function (data) { 
			$( field2 ).html(data);
		});
	}
}
</script>
</head>

<body>
<div style="display:none;">

</div>
<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/operations_menu.php";
?>

<div id="text">

<table width="100%">
<tr>
<td align="left"><img src="../images/centre_timesheet_header.png" width="175" height="25" style="margin-left:3px;" /></td>
<td align="right" style="padding-right:10px;"><select id="centre" style="margin:0px; padding:0px; height:22px; width:75px;" onchange="Centre()">
<option>Centre</option>
<?php
$q = mysql_query("SELECT centres FROM operations WHERE user = '$ac[user]'") or die(mysql_error());
$cen = mysql_fetch_row($q);
$centres = explode(",",$cen[0]);
for ($i = 0; $i < count($centres); $i++)
{
	$q1 = mysql_query("SELECT * FROM centres WHERE centre = '$centres[$i]'") or die(mysql_error());
	$c_check = mysql_fetch_assoc($q1);
	
	if ($c_check["type"] == "Self" && $c_check["status"] == "Active")
	{
		echo "<option>" . $centres[$i] . "</option>";
	}
}
?>
</select>
<input type='text' size='9' id='from' readonly='readonly' style="height:20px;" value="" /> to <input type='text' size='9' id='to' readonly='readonly' style="height:20px;" value="" /><input type='hidden' id='datepicker' value="<?php echo date("Y-m-d", strtotime(date("Y")."W".(date("W") - 2)."7")); ?>" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<script>
var date = $( "#datepicker" );

$.get("payable_process.php", { method: "from", date: date.val() }, function (data) { $( "#from" ).val(data); });
$.get("payable_process.php", { method: "to", date: date.val() }, function (data) { $( "#to" ).val(data); });
</script>

<div id="display">
<script>
var centre = $( "#centre" ),
	date = $( "#datepicker" );

$( "#display" ).load('payable_display.php?centre=' + centre.val() + '&date=' + date.val());
</script>
</div>

</div>

</div> 
<?php
include "../source/footer.php";
?>
</body>
</html>