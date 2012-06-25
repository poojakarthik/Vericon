<?php
include "../auth/iprestrict.php";
include "../source/header.php";

$q = mysql_query("SELECT centres FROM operations WHERE user = '$ac[user]'") or die(mysql_error());
$cen = mysql_fetch_row($q);
$centres = explode(",",$cen[0]);
?>
<script src="../js/date.js" type="text/javascript"></script>
<style>
div#users-contain table { margin: 1em 0; margin-bottom:0; border-collapse: collapse; }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid #eee; padding: .1em 10px; text-align: left; }
.ui-dialog { padding: .3em; }
.ui-dialog2 { padding: .3em; }
.validateTips { border: 1px solid transparent; padding: 0.3em; }
.ui-autocomplete {
	max-height: 100px;
	overflow-y: auto;
	overflow-x: hidden;
	padding-right: 20px;
}
.ui-autocomplete-loading { background: white url('../images/ajax-loader.gif') right center no-repeat; }

.export
{
	background-image:url('../images/export_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
	float:right;
	margin-top:10px;
	margin-right:10px;
}

.export:hover
{
	background-image:url('../images/export_btn_hover.png');
	cursor:pointer;
}

.search
{
	background-image:url('../images/search_btn_2.png');
	background-repeat:no-repeat;
	height:25px;
	width:85px;
	border:none;
	background-color:transparent;
	float:right;
	margin-right:10px;
}

.search:hover
{
	background-image:url('../images/search_btn_hover_2.png');
	cursor:pointer;
}
</style>
<script>
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	$( "#dialog-form" ).dialog({
		autoOpen: true,
		resizable: false,
		draggable: false,
		width:225,
		height:125,
		modal: true
	});
});

$(function() {
	$( "#search_box" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "agent_process.php",
				dataType: "json",
				data: {
					method: "search",
					centres : "<?php echo str_replace(",", "_", $cen[0]); ?>",
					term : request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 2,
		select: function( event, ui ) {
			var date1 = $( "#datepicker" ),
				date2 = $( "#datepicker3" );
			
			$( "#details" ).load("agent_display.php?method=details&user=" + ui.item.id);
			$( "#week_header" ).removeAttr("style");
			$( "#week" ).load("agent_display.php?method=week&user=" + ui.item.id + '&date1=' + date1.val() + '&date2=' + date2.val());
			$( "#dialog-form" ).dialog( "close" );
		}
	});
});

function Search()
{
	$( "#search_box" ).val("");
	$( "#dialog-form" ).dialog( "open" );
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
		altField: "#datepicker2",
		altFormat: "dd/mm/yy",
		showOtherMonths: true,
		selectOtherMonths: true,
		changeMonth: true,
		changeYear: true,
		maxDate: "0d",
		onSelect: function(dateText, inst) {
			var date2 = $( "#datepicker3" ),
				user = $( "#user" );
			
			$( "#week" ).load("agent_display.php?method=week&user=" + user.val() + '&date1=' + dateText + '&date2=' + date2.val());
		}});
});
</script>
<script>
$(function() {
	$( "#datepicker3" ).datepicker( {
		showOn: "button",
		buttonImage: "../images/calendar.gif",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		firstDay: 1,
		altField: "#datepicker4",
		altFormat: "dd/mm/yy",
		showOtherMonths: true,
		selectOtherMonths: true,
		changeMonth: true,
		changeYear: true,
		maxDate: "0d",
		onSelect: function(dateText, inst) {
			var date1 = $( "#datepicker" ),
				user = $( "#user" );
			
			$( "#week" ).load("agent_display.php?method=week&user=" + user.val() + '&date1=' + date1.val() + '&date2=' + dateText);
		}});
});
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog2" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:700,
		height:275,
		modal: true,
		buttons: {
			"Close": function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

function View(we)
{
	var user = $( "#user" ),
		date = formatDate(new Date(getDateFromFormat(we,"y-MM-dd")),"dd/MM/y");
	
	$( ".we" ).html(date);
	$( "#daily" ).load('agent_display.php?method=daily&user=' + user.val() + '&we=' + we);
	$( "#dialog-confirm" ).dialog( "open" );
}
</script>
<script>
function Export()
{
	var user = $( "#user" ),
		date1 = $( "#datepicker" ),
		date2 = $( "#datepicker3" );
	
	window.location = "agent_export?user=" + user.val() + "&date1=" + date1.val() + "&date2=" + date2.val();
}
</script>

<div id="dialog-form" title="Search">
<p class="validateTips">Please Type the Agent's Name Below</p><br />
<input type="text" style="width:200px" id="search_box" value="" />
</div>

<div id="dialog-confirm" title="Daily Breakdown for W.E. <span class='we'></span>">
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;" width="100%">
<thead>
<tr class="ui-widget-header ">
<th>Day</th>
<th>Date</th>
<th style="text-align:center;">Start Time</th>
<th style="text-align:center;">End Time</th>
<th style="text-align:center;">Hours</th>
<th style="text-align:center;">Bonus</th>
<th style="text-align:center;">Sales</th>
<th style="text-align:center;">SPH</th>
<th style="text-align:center;">Estimated CPS</th>
</tr>
</thead>
<tbody id="daily">
</tbody>
</table>
</div>
</div>

<div id="details"></div>

<div id="week_header" style="display:none;">
<table width="100%">
<tr>
<td align="left"><img src="../images/weekly_breakdown_header.png" width="200" height="25" style="margin-left:3px;" /></td>
<td align="right" style="padding-right:10px;">
<input type='text' size='9' id='datepicker2' readonly='readonly' style="height:20px;" value='<?php echo "01/03/2012"; ?>' /><input type='hidden' id='datepicker' value='<?php echo "2012-03-01"; ?>' /> to <input type='text' size='9' id='datepicker4' readonly='readonly' style="height:20px;" value='<?php echo date("d/m/Y"); ?>' /><input type='hidden' id='datepicker3' value='<?php echo date("Y-m-d"); ?>' />
</td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>
</div>

<div id="week"></div>

<?php
include "../source/footer.php";
?>