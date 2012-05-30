<?php
include "../auth/iprestrict.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Accounts :: Timesheet</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<?php
include "../source/jquery.php";
?>
<style>
.more {
	background-image:url('../images/more_icon.png');
	background-repeat:no-repeat;
	height:16px;
	width:16px;
	border:none;
	background-color:transparent;
	cursor:pointer;
}

.export
{
	background-image:url('../images/export_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
}

.export:hover
{
	background-image:url('../images/export_btn_hover.png');
	cursor:pointer;
}

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

.ui-dialog2 .ui-state-highlight { padding: .3em; }
.validateTips { border: 1px solid transparent; padding: 0.3em; }
div#users-contain table { margin: 1em 0; margin-bottom:0; border-collapse: collapse; width:98% }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: center; }
div#users-contain table td { border: 1px solid #eee; padding: .1em 10px; text-align: center; }
</style>
<script>
function Centre()
{
	var centre = $( "#centre" ),
		date = $( "#datepicker" );

	$( "#display" ).load('timesheet_display.php?centre=' + centre.val() + '&date=' + date.val());
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
			
			$.get("timesheet_process.php", { method: "from", date: dateText }, function (data) { $( "#from" ).val(data); });
			$.get("timesheet_process.php", { method: "to", date: dateText }, function (data) { $( "#to" ).val(data); });
			$( "#display" ).load('timesheet_display.php?centre=' + centre.val() + '&date=' + dateText);
		}});
});
</script>
<script>
function Export()
{
	var centre = $( "#centre" ),
		date = $( "#datepicker" );
		
	window.location = "timesheet_export.php?centre=" + centre.val() + "&date=" + date.val();
}
</script>
<script>
function Edit_View()
{
	var centre = $( "#centre" ),
		date = $( "#datepicker" );

	$( "#display" ).load('timesheet_edit.php?centre=' + centre.val() + '&date=' + date.val());
}
</script>
<script>
function Done()
{
	var centre = $( "#centre" ),
		date = $( "#datepicker" );

	$( "#display" ).load('timesheet_display.php?centre=' + centre.val() + '&date=' + date.val());
}
</script>
<script>
function PAYG(user)
{
	var field = "#" + user + "_payg",
		field2 = "#" + user + "_net",
		field3 = "#" + user + "_rate",
		payg = $( field ),
		rate = $( field3 ).html().substr(1),
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

	bValid = checkRegexp( payg, /^([0-9.])+$/, "'" + payg.val() + "' is not a valid PAYG amount" );
	
	if (bValid)
	{
		$.get("timesheet_process.php", { method: "payg", date: date.val(), user: user, payg: payg.val(), rate: rate }, function (data) {
			$( field2 ).html(data);
		});
	}
}
</script>
<script>
function M_Cost()
{
	var m_cost = $( "#m_cost" ),
		centre = $( "#centre" ),
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

	bValid = checkRegexp( m_cost, /^([0-9.])+$/, "'" + m_cost.val() + "' is not a valid Management Cost" );
	
	if (bValid)
	{
		$.get("timesheet_process.php", { method: "m_cost", date: date.val(), centre: centre.val(), m_cost: m_cost.val() }, function(data){});
	}
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		height: 270,
		width: 350,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Close": function() {
				$( "#dialog-confirm" ).dialog( "close" );
			}
		},
		close: function() {
		}
	});
});

function More_Display(user,name)
{
	var date = $( "#datepicker" );
	$( "#name_d" ).val(name);
	$.get("timesheet_process.php", {method: "annual", user: user, date: date.val()}, function (data) { $( "#annual_d" ).val(data); });
	$.get("timesheet_process.php", {method: "sick", user: user, date: date.val()}, function (data) { $( "#sick_d" ).val(data); });
	$.get("timesheet_process.php", {method: "comments", user: user, date: date.val()}, function(data) {$( "#comments_d" ).val(data);});
	$( "#dialog-confirm" ).dialog( "open" );
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog2" ).dialog( "destroy" );
	
	var user = $( "#user" ),
		date = $( "#datepicker" ),
		centre = $( "#centre" ),
		annual = $( "#annual" ),
		sick = $( "#sick" ),
		comments = $( "#comments" ),
		tips = $( ".validateTips" );
	
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	
	$( "#dialog-form" ).dialog({
		autoOpen: false,
		height: 280,
		width: 350,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Submit": function() {
				$.get("timesheet_process.php?method=other", { user: user.val(), date: date.val(), annual: annual.val(), sick: sick.val(), comments: comments.val() },
				function(data) {
					if (data == "submitted")
					{
						$( "#dialog-form" ).dialog( "close" );
						$( "#display" ).load('timesheet_edit.php?centre=' + centre.val() + '&date=' + date.val());
					}
					else
					{
						updateTips(data);
					}
				});
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
		}
	});
});

function More_Edit(user,name)
{
	var date = $( "#datepicker" );
	$( ".validateTips" ).html("Enter the Additional Hours and Comments Below");
	$( "#user" ).val(user);
	$( "#name" ).val(name);
	$.get("timesheet_process.php", {method: "annual", user: user, date: date.val()}, function (data) { $( "#annual" ).val(data); });
	$.get("timesheet_process.php", {method: "sick", user: user, date: date.val()}, function (data) { $( "#sick" ).val(data); });
	$.get("timesheet_process.php", {method: "comments", user: user, date: date.val()}, function(data) {$( "#comments" ).val(data);});
	$( "#dialog-form" ).dialog( "open" );
}
</script>
</head>

<body>
<div style="display:none;">
<img src="../images/export_btn_hover.png" /><img src="../images/edit_btn_hover.png" /><img src="../images/done_btn_hover.png" />
</div>
<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/accounts_menu.php";
?>

<div id="text">

<div id="dialog-confirm" title="Other Details">
<table>
<tr>
<td width='80px'>Agent Name </td>
<td><input type="text" id="name_d" disabled="disabled" size="20" style='padding:0px; margin:0px;'></td>
</tr>
<tr>
<td width='80px'>Annual Leave </td>
<td><input type="text" id="annual_d" disabled="disabled" size="20" style='padding:0px; margin:0px;'></td>
</tr>
<tr>
<td width='80px'>Sick Leave </td>
<td><input type="text" id="sick_d" disabled="disabled" size="20" style='padding:0px; margin:0px;'></td>
</tr>
<tr>
<td width='80px'>Comments </td>
<td><textarea id="comments_d" readonly="readonly" style="width:240px; height:75px; resize:none;"></textarea></td>
</tr>
</table>
</div>

<div id="dialog-form" title="Other Details">
<input type="hidden" id="user" value="" />
<p class="validateTips">Enter the Additional Hours and Comments Below</p>
<table>
<tr>
<td width='80px'>Agent Name </td>
<td><input type="text" id="name" disabled="disabled" size="20" style='padding:0px; margin:0px;'></td>
</tr>
<tr>
<td width='80px'>Annual Leave </td>
<td><input type="text" id="annual" size="20" style='padding:0px; margin:0px;'></td>
</tr>
<tr>
<td width='80px'>Sick Leave </td>
<td><input type="text" id="sick" size="20" style='padding:0px; margin:0px;'></td>
</tr>
<tr>
<td width='80px'>Comments </td>
<td><textarea id="comments" style="width:240px; height:75px; resize:none;"></textarea></td>
</tr>
</table>
</div>

<table width="100%">
<tr>
<td align="left"><img src="../images/centre_timesheet_header.png" width="175" height="25" style="margin-left:3px;" /></td>
<td align="right" style="padding-right:10px;"><select id="centre" style="margin:0px; padding:0px; height:22px; width:75px;" onchange="Centre()">
<option>Centre</option>
<?php
$q = mysql_query("SELECT centre FROM centres WHERE type = 'Self' AND status = 'Active' ORDER BY centre ASC") or die(mysql_error());
while ($centres = mysql_fetch_row($q))
{
	echo "<option>" . $centres[0] . "</option>";
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
var centre = $( "#centre" ),
	date = $( "#datepicker" );

$.get("timesheet_process.php", { method: "from", date: date.val() }, function (data) { $( "#from" ).val(data); });
$.get("timesheet_process.php", { method: "to", date: date.val() }, function (data) { $( "#to" ).val(data); });
</script>

<div id="display">
<script>
var centre = $( "#centre" ),
	date = $( "#datepicker" );

$( "#display" ).load('timesheet_display.php?centre=' + centre.val() + '&date=' + date.val());
</script>
</div>

</div>

</div> 
<?php
include "../source/footer.php";
?>
</body>
</html>