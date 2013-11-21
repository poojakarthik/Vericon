<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 98%; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: center; }
ui-dialog { padding: .3em; }
.validateTips { border: 1px solid transparent; padding: 0.3em; }
</style>
<script>
function View_Edit()
{
	var date = $( "#datepicker" ),
		centre = "<?php echo $ac["centre"] ?>";

	$( "#display2" ).hide('blind', '' , 'slow', function() {
		$( "#display2" ).load('timesheet_edit.php?centre=' + centre + '&date=' + date.val(),
		function() {
			$( "#display2" ).show('blind', '' , 'slow');
		});
	});
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:250,
		height:100,
		modal: true,
		show: "blind",
		hide: "blind"
	});
});
</script>
<script>
function Edit(method,user)
{
	var start = $( "#" + user + "_start" ),
		end = $( "#" + user + "_end" ),
		hours = $( "#" + user + "_hours" ),
		bonus = $( "#" + user + "_bonus" ),
		icon = $( "#" + user + "_icon" );
		
	function checkRegexp( o, regexp, n ) {
		if ( !( regexp.test( o.val() ) ) ) {
			$( ".validateTips" ).html( n );
			$( "#dialog-confirm" ).dialog( "open" );
			o.val("");
			return false;
		}
		else {
			return true;
		}
	}
	
	bValid = true;
		
	if (method == "start")
	{
		if (start.val() != "")
		{
			bValid = checkRegexp( start, /^([0-9]{2}:[0-9]{2})+$/, "'" + start.val() + "' is not a valid input" );
			var n = start.val().split(':');
			if (n[0] > 23 || (n[1] != 00 && n[1] != 15 && n[1] != 30 && n[1] != 45))
			{
				$( ".validateTips" ).html("Start time must be in 24 hour format and in 15 minute intervals.<br>Example: 09:00 for 9am or 20:00 for 8pm");
				$( "#dialog-confirm" ).dialog( "open" );
				start.val("");
				bValid = false;
			}
		}
	}
	else if (method == "end")
	{
		if (end.val() != "")
		{
			bValid = checkRegexp( end, /^([0-9]{2}:[0-9]{2})+$/, "'" + end.val() + "' is not a valid input" );
			var n = end.val().split(':');
			if (n[0] > 23 || (n[1] != 00 && n[1] != 15 && n[1] != 30 && n[1] != 45))
			{
				$( ".validateTips" ).html("End time must be in 24 hour format and in 15 minute intervals.<br>Example: 09:00 for 9am or 20:00 for 8pm");
				$( "#dialog-confirm" ).dialog( "open" );
				end.val("");
				bValid = false;
			}
		}
	}
	else if (method == "hours")
	{
		if (hours.val() != "")
		{
			bValid = checkRegexp( hours, /^([0-9.])+$/, "'" + hours.val() + "' is not a valid input" );
			var n = hours.val().split('.');
			if (n[1] != null && (n[1] != 0 && n[1] != 00 && n[1] != 25 && n[1] != 5 && n[1] != 50 && n[1] != 75))
			{
				$( ".validateTips" ).html("Hours must be quarter intervals.<br><br>Example: 7.5 for 7 hours and 30 minutes");
				$( "#dialog-confirm" ).dialog( "open" );
				hours.val("");
				bValid = false;
			}
		}
	}
	else if (method == "bonus")
	{
		if (bonus.val() != "")
		{
			bValid = checkRegexp( bonus, /^([0-9.])+$/, "'" + bonus.val() + "' is not a valid input" );
		}
	}
	
	if (bValid)
	{
		if (start.val() != "" && end.val() != "" && hours.val() != "")
		{
			icon.html("<img src='../images/check_icon.png'>");
		}
		else if (start.val() == "" && end.val() == "" && hours.val() == "" && bonus.val() == "")
		{
			icon.html("<img src='../images/question_mark_icon.png'>");
		}
		else
		{
			icon.html("<img src='../images/delete_icon.png'>");
		}
	}
}
</script>
<script>
function Save()
{
	$( "#save_button" ).attr("disabled", "disabled");
	
	var date = $( "#datepicker" ),
		centre = "<?php echo $ac["centre"] ?>",
		users = new Array(),
		start = new Array(),
		end = new Array(),
		hours = new Array(),
		bonus = new Array();
	
	$('#users input[type=hidden]').each(function() {
		user = $(this).val();
		users.push(user);
		start.push($( "#" + user + "_start" ).val());
		end.push($( "#" + user + "_end" ).val());
		hours.push($( "#" + user + "_hours" ).val());
		bonus.push($( "#" + user + "_bonus" ).val());
	});
	
	var t = users.length,
		i = 0;
		
	function Save_Get() {
		$.get("timesheet_process.php", { method: "save", date: date.val(), centre: centre, users: users[i], start: start[i], end: end[i], hours: hours[i], bonus: bonus[i] }, function() {
			if (i < t)
			{
				i++;
				Save_Get();
			}
			else
			{
				$( "#display2" ).hide('blind', '' , 'slow', function() {
					$( "#display2" ).load('timesheet_view.php?centre=' + centre + '&date=' + date.val(),
					function() {
						$( "#display2" ).show('blind', '' , 'slow');
					});
				});
			}
		});
	}
	
	Save_Get();
}
</script>
<script>
function Cancel()
{
	var date = $( "#datepicker" ),
		centre = "<?php echo $ac["centre"] ?>";

	$( "#display2" ).hide('blind', '' , 'slow', function() {
		$( "#display2" ).load('timesheet_view.php?centre=' + centre + '&date=' + date.val(),
		function() {
			$( "#display2" ).show('blind', '' , 'slow');
		});
	});
}
</script>
<script>
function Export()
{
	var date = $( "#datepicker" ),
		centre = "<?php echo $ac["centre"] ?>";

	window.location = 'timesheet_export.php?centre=' + centre + '&date=' + date.val();
}
</script>

<div id="dialog-confirm" title="Error">
<span class='ui-icon ui-icon-alert' style='float:left; margin-right:.3em; margin-top:4px'></span><p class="validateTips"></p>
</div>

<div id="display">
<script>
var centre = "<?php echo $ac["centre"]; ?>",
	date = "<?php echo date("Y-m-d"); ?>";

$( "#display" ).load("timesheet_display.php?centre=" + centre + "&date=" + date,
function() {
	$( "#display" ).show('blind', '' , 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>