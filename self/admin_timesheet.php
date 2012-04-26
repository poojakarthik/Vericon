<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 98%; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: center; }
.ui-dialog .ui-dialog2 .ui-state-error { padding: .3em; }
.error2 { border: 1px solid transparent; padding: 0.3em; }

.export
{
	background-image:url('../images/export_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
	margin-left:10px;
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
	margin-right:10px;
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
	margin-right:10px;
}

.done:hover
{
	background-image:url('../images/done_btn_hover.png');
	cursor:pointer;
}
</style>
<script> //datepicker
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
		firstDay: 1,
		minDate: new Date(2012,2,1),
		maxDate: "W",
		onSelect: function(dateText, inst){
			var centre = "<?php echo $ac["centre"] ?>";

			$( "#timesheet_view" ).load('admin_timesheet_get.php?centre=' + centre + '&date=' + dateText);
			$( "#edit_btn").removeAttr("style");
			$( "#export_btn").removeAttr("style");
			$( "#done_btn").attr("style","display:none;");
		}});
});
</script>
<script> //error
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		height:140,
		modal: true,
		buttons: {
			"Close": function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
</script>
<script>
function Done()
{
	date = $( "#datepicker" ),
		centre = "<?php echo $ac["centre"] ?>";

	$( "#timesheet_view" ).load('admin_timesheet_get.php?centre=' + centre + '&date=' + date.val());
	$( "#edit_btn").removeAttr("style");
	$( "#export_btn").removeAttr("style");
	$( "#done_btn").attr("style","display:none;");
}
</script>
<script>
function Export()
{
	var date = $( "#datepicker" ),
		user = "<?php echo $ac["user"] ?>",
		centre = "<?php echo $ac["centre"] ?>";

	$.get("admin_timesheet_edit.php", { method: "check_rows", date: date.val(), centre: centre }, function (data) {
		if (data >= 1)
		{
			window.location = 'admin_timesheet_export.php?centre=' + centre + '&user=' + user + '&date=' + date.val();
		}
		else
		{
			$( ".error" ).html("Nothing to Export!");
			$( "#dialog-confirm" ).dialog( "open" );
		}
	});
}
</script>
<script> //edit view
function Edit_View()
{
	var date = $( "#datepicker" ),
		centre = "<?php echo $ac["centre"] ?>";

	$.get("admin_timesheet_edit.php", { method: "check", date: date.val() }, function (data) {
		if (data == "valid")
		{
			$( "#timesheet_view" ).load('admin_timesheet_edit.php?method=view&centre=' + centre + '&date=' + date.val());
			$( "#edit_btn").attr("style","display:none;");
			$( "#export_btn").attr("style","display:none;");
			setTimeout( function () { $( "#done_btn").removeAttr("style"); },2000);
		}
		else
		{
			$( ".error" ).html(data);
			$( "#dialog-confirm" ).dialog( "open" );
		}
	});
}
</script>
<script> //edit
$(function() {
	$( "#dialog:ui-dialog2" ).dialog( "destroy" );
	
	var user = $( "#user" ),
		date = $( "#datepicker" ),
		start_h = $( "#start_h" ),
		start_m = $( "#start_m" ),
		end_h = $( "#end_h" ),
		end_m = $( "#end_m" ),
		hours = $( "#hours" ),
		bonus = $( "#bonus" ),
		tips = $( ".error2" ),
		centre = "<?php echo $ac["centre"] ?>";

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
		height: 250,
		width: 300,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Submit": function() {
				$.get("admin_timesheet_edit.php", { method: "edit", user: user.val(), date: date.val(), start_h: start_h.val(), start_m: start_m.val(), end_h: end_h.val(), end_m: end_m.val(), hours: hours.val(), bonus: bonus.val() },
				function(data) {
					if (data == "submitted")
					{
						$( "#dialog-form" ).dialog( "close" );
						$( "#timesheet_view" ).load('admin_timesheet_edit.php?method=view&centre=' + centre + '&date=' + date.val());
						$( "#done_btn").removeAttr("style");
						$( "#edit_btn").attr("style","display:none;");
						$( "#export_btn").attr("style","display:none;");
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

function Edit(user)
{
	var date = $( "#datepicker" );
	
	$( "#user" ).val(user);
	$.get("admin_timesheet_edit.php", {method: "name", user: user}, function(data) {$( "#name" ).val(data);});
	$.get("admin_timesheet_edit.php", {method: "start_h", user: user, date: date.val()}, function(data) {$( "#start_h" ).val(data);});
	$.get("admin_timesheet_edit.php", {method: "start_m", user: user, date: date.val()}, function(data) {$( "#start_m" ).val(data);});
	$.get("admin_timesheet_edit.php", {method: "end_h", user: user, date: date.val()}, function(data) {$( "#end_h" ).val(data);});
	$.get("admin_timesheet_edit.php", {method: "end_m", user: user, date: date.val()}, function(data) {$( "#end_m" ).val(data);});
	$.get("admin_timesheet_edit.php", {method: "hours", user: user, date: date.val()}, function(data) {$( "#hours" ).val(data);});
	$.get("admin_timesheet_edit.php", {method: "sales", user: user, date: date.val()}, function(data) {$( ".sales" ).html(data);});
	$.get("admin_timesheet_edit.php", {method: "bonus", user: user, date: date.val()}, function(data) {$( "#bonus" ).val(data);});
	$( "#dialog-form" ).dialog( "open" );
}
</script>

<div style="display:none;">
<img src="../images/export_btn_hover.png" /><img src="../images/edit_btn_hover.png" /><img src="../images/done_btn_hover.png" />
</div>

<div id="dialog-confirm" title="Error">
    <p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span><span class="error"></span></p>
</div>

<div id="dialog-form" title="Bla">
<p class="error2"><span style="color:#ff0000;">*</span> Required Fields</p>
<input type="hidden" id="user">
<table>
<tr>
<td width='80px'>Agent Name</td>
<td><input type="text" id="name" disabled="disabled" style='width:89px; height:20px; padding:0px; margin:0px;'></td>
</tr>
<tr>
<td>Start Time<span style="color:#ff0000;">*</span> </td>
<td><select id="start_h" style='width:40px; height:20px; padding:0px; margin:0px;'>
<option></option>
<option>09</option>
<option>10</option>
<option>11</option>
<option>12</option>
<option>13</option>
<option>14</option>
<option>15</option>
<option>16</option>
<option>17</option>
<option>18</option>
<option>19</option>
<option>20</option>
</select> : <select id="start_m" style='width:40px; height:20px; padding:0px; margin:0px;'>
<option></option>
<option>00</option>
<option>15</option>
<option>30</option>
<option>45</option>
</select></td>
</tr>
<tr>
<td>End Time<span style="color:#ff0000;">*</span> </td>
<td><select id="end_h" style='width:40px; height:20px; padding:0px; margin:0px;'>
<option></option>
<option>09</option>
<option>10</option>
<option>11</option>
<option>12</option>
<option>13</option>
<option>14</option>
<option>15</option>
<option>16</option>
<option>17</option>
<option>18</option>
<option>19</option>
<option>20</option>
</select> : <select id="end_m" style='width:40px; height:20px; padding:0px; margin:0px;'>
<option></option>
<option>00</option>
<option>15</option>
<option>30</option>
<option>45</option>
</select></td>
</tr>
<tr>
<td>Hours<span style="color:#ff0000;">*</span> </td>
<td><input type="text" id="hours" style='width:89px; height:20px; padding:0px; margin:0px;'></td>
</tr>
<tr>
<td>Sales</td>
<td><b class="sales"></b></td>
</tr>
<tr>
<td>Bonus ($)</td>
<td><input type="text" id="bonus" style='width:89px; height:20px; padding:0px; margin:0px;'></td>
</tr>
</table>
</div>

<table width="100%">
<tr>
<td align="left"><img src="../images/centre_timesheet_header.png" width="175" height="25" style="margin-left:3px;" /></td>
<td align="right"><input type="text" size="10" id="datepicker2" readonly value="<?php echo date("d/m/Y"); ?>" style="height:20px;" /><input type="hidden" id="datepicker" value="<?php echo date("Y-m-d"); ?>" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" alt="line" /></td>
</tr>
</table>

<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th style="text-align:left;">Agent Name</th>
<th>Start Time</th>
<th>End Time</th>
<th>Hours</th>
<th>Sales</th>
<th>Bonus</th>
</tr>
</thead>
<tbody id="timesheet_view">
<script>
var date = $( "#datepicker" ),
	centre = "<?php echo $ac["centre"] ?>";

$( "#timesheet_view" ).load('admin_timesheet_get.php?centre=' + centre + '&date=' + date.val());
</script>
</tbody>
</table>
</div></center>
<table width="100%">
<tr>
<td align="left"><input type="button" id="export_btn" onClick="Export()" class="export"></td>
<td align="right"><input type="button" id="edit_btn" onClick="Edit_View()" class="edit"><input type="button" id="done_btn" onClick="Done()" class="done" style="display:none;"></td>
</tr>
</table>