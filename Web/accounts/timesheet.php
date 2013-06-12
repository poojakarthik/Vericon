<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
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
	
	$( "#display" ).hide( 'blind', '', 'slow', function() {
		$( "#display" ).load('timesheet_display.php?centre=' + centre.val() + '&date=' + date.val(), function(){
			$( "#display" ).show( 'blind', '', 'slow');
		});
	});
}
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
	
	$( "#display" ).hide( 'blind', '', 'slow', function() {
		$( "#display" ).load('timesheet_edit.php?centre=' + centre.val() + '&date=' + date.val(), function(){
			$( "#display" ).show( 'blind', '', 'slow');
		});
	});
}
</script>
<script>
function Done()
{
	var centre = $( "#centre" ),
		date = $( "#datepicker" );
	
	$( "#display" ).hide( 'blind', '', 'slow', function() {
		$( "#display" ).load('timesheet_display.php?centre=' + centre.val() + '&date=' + date.val(), function(){
			$( "#display" ).show( 'blind', '', 'slow');
		});
	});
}
</script>
<script>
function Hours(user)
{
	var field = "#" + user + "_hours",
		hours = $( field ),
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

	bValid = checkRegexp( hours, /^([0-9.])+$/, "'" + hours.val() + "' is not a valid Hour input" );
	
	if (bValid)
	{
		$.get("timesheet_process.php", { method: "hours", date: date.val(), user: user, hours: hours.val() }, function (data) {
			$( "#display" ).load('timesheet_edit.php?centre=' + centre.val() + '&date=' + date.val());
		});
	}
}
</script>
<script>
function Bonus(user)
{
	var field = "#" + user + "_bonus",
		bonus = $( field ),
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

	bValid = checkRegexp( bonus, /^([0-9.])+$/, "'" + bonus.val() + "' is not a valid Bonus input" );
	
	if (bValid)
	{
		$.get("timesheet_process.php", { method: "bonus", date: date.val(), user: user, bonus: bonus.val() }, function (data) {
			$( "#display" ).load('timesheet_edit.php?centre=' + centre.val() + '&date=' + date.val());
		});
	}
}
</script>
<script>
function PAYG(user)
{
	var field = "#" + user + "_payg",
		field2 = "#" + user + "_net",
		field3 = "#" + user + "_rate",
		field4 = "#" + user + "_type",
		field5 = "#" + user + "_actual",
		payg = $( field ),
		rate = $( field3 ).html().substr(1),
		type = $( field4 ),
		a_rate = $( field5 ),
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
		$.get("timesheet_process.php", { method: "payg", date: date.val(), user: user, payg: payg.val(), rate: rate, type: type.val(), a_rate: a_rate.val() }, function (data) {
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
		$.get("timesheet_process.php", { method: "m_cost", date: date.val(), centre: centre.val(), m_cost: m_cost.val() });
	}
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		height: 200,
		width: 350,
		modal: true,
		resizable: false,
		draggable: false,
		show: 'blind',
		hide: 'blind'
	});
});

function More_Display(user)
{
	var date = $( "#datepicker" );
	$.get("timesheet_process.php", {method: "name", user: user}, function (data) { $( "#name_d" ).val(data); });
	$.get("timesheet_process.php", {method: "annual", user: user, date: date.val()}, function (data) { $( "#annual_d" ).val(data); });
	$.get("timesheet_process.php", {method: "sick", user: user, date: date.val()}, function (data) { $( "#sick_d" ).val(data); });
	$.get("timesheet_process.php", {method: "comments", user: user, date: date.val()}, function(data) {$( "#comments_d" ).val(data);});
	$( "#dialog-confirm" ).dialog( "open" );
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog2" ).dialog( "destroy" );
	
	var tips = $( ".validateTips" );
	
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
				var user = $( "#user" ),
					date = $( "#datepicker" ),
					centre = $( "#centre" ),
					annual = $( "#annual" ),
					sick = $( "#sick" ),
					comments = $( "#comments" );
				
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
	$.get("timesheet_process.php", {method: "name", user: user}, function (data) { $( "#name" ).val(data); });
	$.get("timesheet_process.php", {method: "annual", user: user, date: date.val()}, function (data) { $( "#annual" ).val(data); });
	$.get("timesheet_process.php", {method: "sick", user: user, date: date.val()}, function (data) { $( "#sick" ).val(data); });
	$.get("timesheet_process.php", {method: "comments", user: user, date: date.val()}, function(data) {$( "#comments" ).val(data);});
	$( "#dialog-form" ).dialog( "open" );
}
</script>

<div id="dialog-confirm" title="Other Details">
<table>
<tr>
<td width='80px'>Agent Name </td>
<td><input type="text" id="name_d" disabled="disabled" style='width:150px;'></td>
</tr>
<tr>
<td width='80px'>Annual Leave </td>
<td><input type="text" id="annual_d" disabled="disabled" style='width:40px;'></td>
</tr>
<tr>
<td width='80px'>Sick Leave </td>
<td><input type="text" id="sick_d" disabled="disabled" style='width:40px;'></td>
</tr>
<tr>
<td width='80px'>Comments </td>
<td><textarea id="comments_d" disabled="disabled" style="width:240px; height:75px; resize:none;"></textarea></td>
</tr>
</table>
</div>

<div id="dialog-form" title="Other Details">
<input type="hidden" id="user" value="" />
<p class="validateTips">Enter the Additional Hours and Comments Below</p>
<table>
<tr>
<td width='80px'>Agent Name </td>
<td><input type="text" id="name" disabled="disabled" style='width:150px;'></td>
</tr>
<tr>
<td width='80px'>Annual Leave </td>
<td><input type="text" id="annual" style='width:40px;'></td>
</tr>
<tr>
<td width='80px'>Sick Leave </td>
<td><input type="text" id="sick" style='width:40px;'></td>
</tr>
<tr>
<td width='80px'>Comments </td>
<td><textarea id="comments" style="width:240px; height:75px; resize:none;"></textarea></td>
</tr>
</table>
</div>

<div id="display">
<script>
var centre = $( "#centre" ),
	date = $( "#datepicker" );

$( "#display" ).load('timesheet_display.php?centre=Centre&date=<?php echo date("Y-m-d", strtotime("-2 weeks")); ?>',
function() {
	$( "#display" ).show('blind', '', 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>