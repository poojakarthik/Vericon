<?php
include "../auth/iprestrict.php";
include "../js/self-js.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Self Verification :: Roster</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<?php
include "../source/jquery.php";
?>
<style>
.availability
{
	background-image:url('../images/availability_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
	margin-top:5px;
	margin-left:10px;
}

.availability:hover
{
	background-image:url('../images/availability_btn_hover.png');
	cursor:pointer;
}

div#users-contain table { margin: 1em 0; border-collapse: collapse; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .4em 10px; }
</style>
<script>
var na = new Array();
na[1] = 0;
na[2] = 0;
na[3] = 0;
na[4] = 0;
na[5] = 0;
</script>
<script> //availability
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var user = "<?php echo $ac["user"]; ?>",
		centre = "<?php echo $ac["centre"]; ?>";
		start_hour1 = $( "#start_hour1" ),
		start_minute1 = $( "#start_minute1" ),
		end_hour1 = $( "#end_hour1" ),
		end_minute1 = $( "#end_minute1" ),
		start_hour2 = $( "#start_hour2" ),
		start_minute2 = $( "#start_minute2" ),
		end_hour2 = $( "#end_hour2" ),
		end_minute2 = $( "#end_minute2" ),
		start_hour3 = $( "#start_hour3" ),
		start_minute3 = $( "#start_minute3" ),
		end_hour3 = $( "#end_hour3" ),
		end_minute3 = $( "#end_minute3" ),
		start_hour4 = $( "#start_hour4" ),
		start_minute4 = $( "#start_minute4" ),
		end_hour4 = $( "#end_hour4" ),
		end_minute4 = $( "#end_minute4" ),
		start_hour5 = $( "#start_hour5" ),
		start_minute5 = $( "#start_minute5" ),
		end_hour5 = $( "#end_hour5" ),
		end_minute5 = $( "#end_minute5" ),
		tips = $( ".error" );

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
		height: 300,
		width: 500,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Submit Availability": function() {
				$.get("availability.php", { user: user, centre: centre, 
				start_hour1: start_hour1.val(), start_minute1: start_minute1.val(), end_hour1: end_hour1.val(), end_minute1: end_minute1.val(), na1: na[1], start_hour2: start_hour2.val(), start_minute2: start_minute2.val(), end_hour2: end_hour2.val(), end_minute2: end_minute2.val(), na2: na[2], start_hour3: start_hour3.val(), start_minute3: start_minute3.val(), end_hour3: end_hour3.val(), end_minute3: end_minute3.val(), na3: na[3], start_hour4: start_hour4.val(), start_minute4: start_minute4.val(), end_hour4: end_hour4.val(), end_minute4: end_minute4.val(), na4: na[4], start_hour5: start_hour5.val(), start_minute5: start_minute5.val(), end_hour5: end_hour5.val(), end_minute5: end_minute5.val(), na5: na[5] },
				function(data) {
					if (data == "submitted")
					{
						$( "#dialog-form" ).dialog( "close" );
						location.reload();
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

function Availability()
{
	$( "#dialog-form" ).dialog( "open" );
}
</script>
<script>
function NA(day)
{
	var check = "#not_available" + day,
		start_hour = "#start_hour" + day,
		start_minute = "#start_minute" + day,
		end_hour = "#end_hour" + day,
		end_minute = "#end_minute" + day;
	
	if ($( check ).attr('checked'))
	{
		$( start_hour ).attr("disabled", true);
		$( start_minute ).attr("disabled", true);
		$( end_hour ).attr("disabled", true);
		$( end_minute ).attr("disabled", true);
		na[day] = 1;
	}
	else
	{
		$( start_hour ).removeAttr("disabled");
		$( start_minute ).removeAttr("disabled");
		$( end_hour ).removeAttr("disabled");
		$( end_minute ).removeAttr("disabled");
		na[day] = 0;
	}
}
</script>
</head>

<body>
<div style="display:none;">
<img src="../images/availability_btn_hover.png" />
</div>
<div id="main_wrapper">
<?php
include "../source/header.php";
include "../source/self_menu.php";
?>

<div id="text" class="demo">

<div id="dialog-form" title="Enter Availability">
<p class="error">All fields are required</p>
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="100%">
<thead>
<tr class="ui-widget-header ">
<th>Day</th>
<th>Date</th>
<th>Available Start</th>
<th>Available End</th>
<th></th>
</tr>
</thead>
<tbody align="center">
<?php
for ($day=1; $day <= 5; $day++)
{
	echo "<tr>";
	echo "<td>" . date('l', strtotime(date("Y") . "W" . date("W", strtotime("+1 week")) . $day)) . "</td>";
	echo "<td>" . date('d/m/Y', strtotime(date("Y") . "W" . date("W", strtotime("+1 week")) . $day)) . "</td>";
    echo "<td><select id='start_hour$day' style='width:40px; height:auto; padding:0px; margin:0px;'><option></option><option>09</option><option>10</option><option>11</option><option>12</option><option>13</option><option>14</option><option>15</option><option>16</option><option>17</option><option>18</option><option>19</option><option>20</option></select> : <select id='start_minute$day' style='width:40px; height:auto; padding:0px; margin:0px;'><option></option><option>00</option><option>15</option><option>30</option><option>45</option></select></td>";
    echo "<td><select id='end_hour$day' style='width:40px; height:auto; padding:0px; margin:0px;'><option></option><option>09</option><option>10</option><option>11</option><option>12</option><option>13</option><option>14</option><option>15</option><option>16</option><option>17</option><option>18</option><option>19</option><option>20</option></select> : <select id='end_minute$day' style='width:40px; height:auto; padding:0px; margin:0px;'><option></option><option>00</option><option>15</option><option>30</option><option>45</option></select></td>";
	echo "<td><input type='checkbox' id='not_available$day' onclick='NA($day)' style='width:auto; height:auto; padding:0px; margin:0px;' /> N/A</td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div>
</div>

<p><img src="../images/this_week_header.png" width="100" height="25" style="margin-left:3px;" /></p>
<p><img src="../images/line.png" width="740" height="9" alt="line" /></p>
<div id="users-contain" class="ui-widget" style="margin-left:3px;">
<table id="users" class="ui-widget ui-widget-content" width="60%" style="margin-top:3px;">
<thead>
<tr class="ui-widget-header ">
<th>Day</th>
<th>Date</th>
<th>Start</th>
<th>End</th>
</tr>
</thead>
<tbody align="center">
<?php
$week_number = date("W");
$q = mysql_query("SELECT * FROM roster WHERE agent = '$ac[user]' AND WEEK(date) = '$week_number' ORDER BY date ASC") or die(mysql_error());
if (mysql_num_rows($q) == 0)
{
	for ($day=1; $day <= 5; $day++)
	{
		echo "<tr>";
		echo "<td>" . date('l', strtotime(date("Y") . "W" . $week_number . $day)) . "</td>";
		echo "<td>" . date('d/m/Y', strtotime(date("Y") . "W" . $week_number . $day)) . "</td>";
		echo "<td>N/A</td>";
		echo "<td>N/A</td>";
		echo "</tr>";
	}
}
else
{
	while ($roster = mysql_fetch_assoc($q))
	{
		if ($roster["na"] == 1) {$start = "N/A"; $end = "N/A";} else {$start = date("h:i A", strtotime($roster["start"])); $end = date("h:i A", strtotime($roster["end"]));}
		echo "<tr>";
		echo "<td>" . date("l", strtotime($roster["date"]))."</td>";
		echo "<td>" . date("d/m/Y", strtotime($roster["date"]))."</td>";
		echo "<td>" . $start . "</td>";
		echo "<td>" . $end . "</td>";
		echo "</tr>";
	}
}
?>
</tbody>
</table>
</div>

<p><img src="../images/next_week_header.png" width="105" height="25" style="margin-left:3px;" /></p>
<p><img src="../images/line.png" width="740" height="9" alt="line" /></p>
<?php
$week_number2 = date("W", strtotime("+1 week"));
$q2 = mysql_query("SELECT * FROM roster WHERE agent = '$ac[user]' AND WEEK(date) = '$week_number2' ORDER BY date ASC") or die(mysql_error());
if (mysql_num_rows($q2) == 0)
{
?>
<div id="users-contain" class="ui-widget" style="margin-left:3px;">
<table id="users" class="ui-widget ui-widget-content" width="80%" style="margin-top:3px;">
<thead>
<tr class="ui-widget-header ">
<th>Day</th>
<th>Date</th>
<th>Available Start</th>
<th>Available End</th>
<th>Start</th>
<th>End</th>
</tr>
</thead>
<tbody align="center">
<?php
for ($day=1; $day <= 5; $day++)
{
	echo "<tr>";
	echo "<td>" . date('l', strtotime(date("Y") . "W" . $week_number2 . $day)) . "</td>";
	echo "<td>" . date('d/m/Y', strtotime(date("Y") . "W" . $week_number2 . $day)) . "</td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
    echo "<td></td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div>
<input type="button" onclick="Availability()" class="availability" />
<?php
}
else
{
?>
<div id="users-contain" class="ui-widget" style="margin-left:3px;">
<table id="users" class="ui-widget ui-widget-content" width="80%" style="margin-top:3px;">
<thead>
<tr class="ui-widget-header ">
<th>Day</th>
<th>Date</th>
<th>Available Start</th>
<th>Available End</th>
<th>Start</th>
<th>End</th>
</tr>
</thead>
<tbody align="center">
<?php
while ($roster2 = mysql_fetch_assoc($q2))
{
	if ($roster2["na"] == 1)
	{
		$start2 = "N/A";
		$end2 = "N/A";
		$av_start2 = "N/A";
		$av_end2 = "N/A";
	}
	else
	{
		$start2 = date("h:i A", strtotime($roster2["start"]));
		$end2 = date("h:i A", strtotime($roster2["end"]));
		$av_start2 = date("h:i A", strtotime($roster2["av_start"]));
		$av_end2 = date("h:i A", strtotime($roster2["av_end"]));
		
		if ($roster2["start"] == "00:00:00" || $roster2["end"] == "00:00:00")
		{
			$start2 = "";
			$end2 = "";
		}
	}
	echo "<tr>";
	echo "<td>" . date("l", strtotime($roster2["date"]))."</td>";
    echo "<td>" . date("d/m/Y", strtotime($roster2["date"]))."</td>";
	echo "<td>" . $av_start2 . "</td>";
	echo "<td>" . $av_end2 . "</td>";
	echo "<td>" . $start2 . "</td>";
	echo "<td>" . $end2 . "</td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div>
<?php
}
?>

</div>

</div>

<?php
include "../source/footer.php";
?>

</body>
</html>