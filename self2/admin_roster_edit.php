<?php
$centre = $_GET["centre"];
$method = $_GET["method"];

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

if ($method == "display")
{
?>
<script>
function Roster_Done()
{
	window.location = "admin.php?p=roster&m=d";
}
</script>
<div id="users-contain" class="ui-widget" style="margin-left:3px;">
<table id="users" class="ui-widget ui-widget-content" width="100%" style="margin-top:5px; font-size:10px;">
<thead>
<tr class="ui-widget-header ">
<th><p>Name</p></th>
<?php
$week_number = date("W", strtotime("+1 week"));
for ($day=1; $day <= 5; $day++)
{
	echo "<th style='text-align:center;'><p>" . date('l', strtotime(date("Y") . "W" . $week_number . $day)) . "</p>";
	echo "<p>" . date('d/m/Y', strtotime(date("Y") . "W" . $week_number . $day)) . "</p></th>";
}
?>
</tr>
</thead>
<tbody align="center">
<?php
$q = mysql_query("SELECT user,first,last FROM auth WHERE centre = '$centre' AND status = 'Enabled' ORDER BY user ASC") or die(mysql_error());
while ($agent = mysql_fetch_row($q))
{
	$q2 = mysql_query("SELECT * FROM roster WHERE agent = '$agent[0]' AND WEEK(date) = '$week_number' ORDER BY date ASC") or die(mysql_error());
	echo "<tr>";
	echo "<td><a onClick='Edit(\"$agent[0]\",\"$centre\")' style='text-decoration:underline; cursor:pointer;'>" . $agent[1] . " " . $agent[2] ."</a></td>";
	if (mysql_num_rows($q2) == 0)
	{
		echo "<td style='text-align:center;' colspan='5'>Availability Not Entered!</td>";
	}
	else
	{
		while ($roster = mysql_fetch_assoc($q2))
		{
			if ($roster["na"] == 1) {$start = "N/A"; $end = "N/A";} else {$start = date("h:i A", strtotime($roster["start"])); $end = date("h:i A", strtotime($roster["end"]));}
			if ($start == "12:00 AM") { $display = "-"; }
			elseif ($start == "N/A") { $display = "N/A"; }
			else { $display = $start . " - " . $end; }
			echo "<td style='text-align:center;'>" . $display . "</td>";
		}
	}
	echo "</tr>";
}
?>
</tbody>
</table>
<input type="button" onClick="Roster_Done()" class="roster_done" style="float:right;">
</div>
<?php
}
elseif ($method == "edit")
{
$agent = $_GET["agent"];
$centre = $_GET["centre"];
?>
<script>
var na = new Array();
na[1] = 0;
na[2] = 0;
na[3] = 0;
na[4] = 0;
na[5] = 0;
var roster_user = $( "#agent_hidden" ),
	roster_centre = $( "#centre_hidden" ),
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
	end_minute5 = $( "#end_minute5" );
</script>
<script> //make roster
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var tips = $( ".error" );

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
		height: 330,
		width: 725,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Submit Availability": function() {
				$.get("admin_submit.php?p=roster", { user: roster_user.val(), centre: roster_centre.val(), 
				start_hour1: start_hour1.val(), start_minute1: start_minute1.val(), end_hour1: end_hour1.val(), end_minute1: end_minute1.val(), na1: na[1], start_hour2: start_hour2.val(), start_minute2: start_minute2.val(), end_hour2: end_hour2.val(), end_minute2: end_minute2.val(), na2: na[2], start_hour3: start_hour3.val(), start_minute3: start_minute3.val(), end_hour3: end_hour3.val(), end_minute3: end_minute3.val(), na3: na[3], start_hour4: start_hour4.val(), start_minute4: start_minute4.val(), end_hour4: end_hour4.val(), end_minute4: end_minute4.val(), na4: na[4], start_hour5: start_hour5.val(), start_minute5: start_minute5.val(), end_hour5: end_hour5.val(), end_minute5: end_minute5.val(), na5: na[5] },
				function(data) {
					if (data == "submitted")
					{
						$( "#dialog-form" ).dialog( "close" );
						$( "#roster_edit" ).load('admin_roster_edit.php?method=display&centre=' + centre);
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
<p class="error">All fields are required</p>
<input type="hidden" id="agent_hidden" value="<?php echo $agent; ?>">
<input type="hidden" id="centre_hidden" value="<?php echo $centre; ?>">
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="100%">
<thead>
<tr class="ui-widget-header ">
<th style="text-align:center;">Day</th>
<th style="text-align:center;">Date</th>
<th style="text-align:center;">Available Start</th>
<th style="text-align:center;">Available End</th>
<th style="text-align:center;">Start</th>
<th style="text-align:center;">End</th>
<th></th>
</tr>
</thead>
<tbody>
<?php
for ($day=1; $day <= 5; $day++)
{
	$date = date('Y-m-d', strtotime(date("Y") . "W" . date("W", strtotime("+1 week")) . $day));
	echo "<tr>";
	echo "<td style='text-align:center;'>" . date('l', strtotime(date("Y") . "W" . date("W", strtotime("+1 week")) . $day)) . "</td>";
	echo "<td style='text-align:center;'>" . date('d/m/Y', strtotime(date("Y") . "W" . date("W", strtotime("+1 week")) . $day)) . "</td>";
	$q2 = mysql_query("SELECT * FROM roster WHERE agent = '$agent' AND date = '$date'") or die(mysql_error());
	if (mysql_num_rows($q2) == 0)
	{
		echo "<td style='text-align:center;'>???</td>";
		echo "<td style='text-align:center;'>???</td>";
	}
	else
	{
		while ($availability = mysql_fetch_assoc($q2))
		{
			if ($availability["na"] == 1)
			{
				$av_start = "N/A";
				$av_end = "N/A";
			}
			else
			{
				$av_start = date("h:i A", strtotime($availability["av_start"]));
				$av_end = date("h:i A", strtotime($availability["av_end"]));
			}
			
			echo "<td style='text-align:center;'>$av_start</td>";
			echo "<td style='text-align:center;'>$av_end</td>";
		}
	}
    echo "<td style='text-align:center;'><select id='start_hour$day' style='width:40px; height:auto; padding:0px; margin:0px;'><option></option><option>09</option><option>10</option><option>11</option><option>12</option><option>13</option><option>14</option><option>15</option><option>16</option><option>17</option><option>18</option><option>19</option><option>20</option></select> : <select id='start_minute$day' style='width:40px; height:auto; padding:0px; margin:0px;'><option></option><option>00</option><option>15</option><option>30</option><option>45</option></select></td>";
    echo "<td style='text-align:center;'><select id='end_hour$day' style='width:40px; height:auto; padding:0px; margin:0px;'><option></option><option>09</option><option>10</option><option>11</option><option>12</option><option>13</option><option>14</option><option>15</option><option>16</option><option>17</option><option>18</option><option>19</option><option>20</option></select> : <select id='end_minute$day' style='width:40px; height:auto; padding:0px; margin:0px;'><option></option><option>00</option><option>15</option><option>30</option><option>45</option></select></td>";
	echo "<td><input type='checkbox' id='not_available$day' onclick='NA($day)' style='width:auto; height:auto; padding:0px; margin:0px;' /> N/A</td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div>
<?php
}
?>