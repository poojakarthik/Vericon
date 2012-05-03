<?php
include "../auth/iprestrict.php";
$q = mysql_query("SELECT centres FROM operations WHERE user = '$ac[user]'") or die(mysql_error());
$cen = mysql_fetch_row($q);
$centres = explode(",",$cen[0]);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Operations :: Timesheets</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<?php
include "../source/jquery.php";
?>
<script src="../js/date.js" type="text/javascript"></script>
<style>
div#users-contain table { margin: 1em 0; margin-bottom:0; border-collapse: collapse; }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid #eee; padding: .1em 10px; text-align: left; }
.ui-dialog .ui-state-error { padding: .3em; }
.error { border: 1px solid transparent; padding: 0.3em; }
.ui-autocomplete-loading { background: white url('../images/ajax-loader.gif') right center no-repeat; }

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
function Display(centre)
{
	var method = $( "#display_type" );

	$( ".centre" ).html(centre);
	$( "#centre" ).val(centre);
	$( "#display" ).load('timesheet_display.php?method=' + method.val() + '&centre=' + centre);
	$( "#centre_export_btn" ).removeAttr("style");
}

function Change_Display()
{
	var centre = $( "#centre" ),
		method = $( "#display_type" );

	$( "#display" ).load('timesheet_display.php?method=' + method.val() + '&centre=' + centre.val());
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var agent = $( "#search_agent" ),
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
		resizable: false,
		draggable: false,
		width:275,
		height:175,
		modal: true,
		buttons: {
			"Open": function() {
				var lead = $( "#lead" );
				
				$.get("timesheet_display.php?method=check", { agent: agent.val() },
				function(data) {
					if (data == "valid")
					{
						window.location = "timesheet.php?agent=" + agent.val();
					}
					else
					{
						updateTips(data);
					}
				});
			},
			
			"Close": function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

$(function() {
	$( "#search_box" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "timesheet_display.php",
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
			$( "#search_agent" ).val(ui.item.id);
		}
	});
});

function Search()
{
	$( "#dialog-form" ).dialog( "open" );
}
</script>
<script>
function Centre_Export()
{
	var type = $( "#display_type" ),
		centre = $( "#centre" );

	window.location = 'timesheet_export.php?method=centre&centre=' + centre.val() + '&type=' + type.val();
}
</script>
<script>
function Agent_Export()
{
	var type = $( "#export_type" ),
		user = "<?php echo $_GET["agent"]; ?>";

	window.location = 'timesheet_export.php?method=agent&user=' + user + '&type=' + type.val();
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog2" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:500,
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
	var agent = "<?php echo $_GET["agent"]; ?>",
		date = formatDate(new Date(getDateFromFormat(we,"y-MM-dd")),"dd/MM/y");
	
	$( ".we" ).html(date);
	$( "#week_breakdown" ).load('timesheet_display.php?method=Agent&agent=' + agent + '&we=' + we);
	$( "#dialog-confirm" ).dialog( "open" );
}
</script>
</head>

<body>
<div style="display:none;">
<img src="../images/search_btn_hover_2.png" />
</div>
<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/operations_menu.php";
?>

<div id="text">

<div id="dialog-form" title="Search">
<p class="error">Please Type the Agent's Name Below</p><br />
Agent: <input type="text" id="search_box" />
<input type="hidden" id="search_agent" value="" />
</div>

<div id="dialog-confirm" title="Week Breakdown for W.E. <span class='we'></span>">
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;" width="100%">
<thead>
<tr class="ui-widget-header ">
<th>Date</th>
<th style="text-align:center;">Start Time</th>
<th style="text-align:center;">End Time</th>
<th style="text-align:center;">Hours</th>
<th style="text-align:center;">Sales</th>
<th style="text-align:center;">SPH</th>
<th style="text-align:center;">Grade</th>
</tr>
</thead>
<tbody id="week_breakdown">
</tbody>
</table>
</div>
</div>

<?php
if ($_GET["agent"] == "")
{
?>
<table width="100%">
<tr>
<td align="left"><img src="../images/centre_timesheet_header.png" width="175" height="25" style="margin-left:3px;" /></td>
<td align="right"><input type="button" onclick="Search()" class="search" />
</td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>
<center>
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;">
<?php //captive
/*$total_hours = 0;
$total_sales = 0;
for ($i = 0; $i < count($centres); $i++)
{
	$q0 = mysql_query("SELECT centre FROM centres WHERE centre = '$centres[$i]' AND type = 'Captive'") or die(mysql_error());
	if (mysql_num_rows($q0) != 0)
	{
		$captive[$centres[$i]] = 1;
	}
}

if (array_sum($captive) > 0)
{
	echo '<thead>';
	echo '<tr class="ui-widget-header ">';
	echo '<th colspan="5" style="text-align:center;">India</th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th>Centre</th>';
	echo '<th style="text-align:center;">Total Hours</th>';
	echo '<th style="text-align:center;">Total Sales</th>';
	echo '<th style="text-align:center;">Average SPH</th>';
	echo '<th style="text-align:center;">Centre Grade</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
}

for ($i = 0; $i < count($centres); $i++)
{
	if ($captive[$centres[$i]] == 1)
	{
		$hours = 0;
		
		$q1 = mysql_query("SELECT timesheet.hours FROM timesheet,auth WHERE auth.centre = '$centres[$i]' AND auth.user = timesheet.user") or die(mysql_error());
		while ($h = mysql_fetch_row($q1))
		{
			$hours += $h[0];
		}
		$total_hours += $hours;
		
		$q2 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND centre = '$centres[$i]'") or die(mysql_error());
		$sales = mysql_num_rows($q2);
		$total_sales += $sales;
		
		$sph = $sales / $hours;
		
		if ($sph < 0.15) { $grade = "D"; } elseif ($sph < 0.2) { $grade = "C"; } elseif ($sph < 0.25) { $grade = "B"; } else { $grade = "A"; }

		echo "<tr>";
		echo "<td><a onclick='Display(\"$centres[$i]\")' style='text-decoration:underline; cursor:pointer;'>" . $centres[$i] . "</a></td>";
		echo "<td style='text-align:center;'>" . $hours . "</td>";
		echo "<td style='text-align:center;'>" . $sales . "</td>";
		echo "<td style='text-align:center;'>" . number_format($sph,2) . "</td>";
		echo "<td style='text-align:center;'>" . $grade . "</td>";
		echo "</tr>";
	}
}

if (array_sum($captive) > 0)
{
	$total_sph = $total_sales / $total_hours;
	
	if ($total_sph < 0.15) { $total_grade = "D"; } elseif ($total_sph < 0.2) { $total_grade = "C"; } elseif ($total_sph < 0.25) { $total_grade = "B"; } else { $total_grade = "A"; }
	
	echo "<tr>";
	echo "<td><b>Total</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_hours . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_sales . "</b></td>";
	echo "<td style='text-align:center;'><b>" . number_format($total_sph,2) . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_grade . "</b></td>";
	echo "</tr>";
	echo "</tbody>";
}*/
?>
<?php //self
$total_hours = 0;
$total_sales = 0;
for ($i = 0; $i < count($centres); $i++)
{
	$q0 = mysql_query("SELECT centre FROM centres WHERE centre = '$centres[$i]' AND type = 'Self'") or die(mysql_error());
	if (mysql_num_rows($q0) != 0)
	{
		$self[$centres[$i]] = 1;
	}
}

if (array_sum($self) > 0)
{
	echo '<thead>';
	echo '<tr class="ui-widget-header ">';
	echo '<th colspan="5" style="text-align:center;">Melbourne</th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th>Centre</th>';
	echo '<th style="text-align:center;">Total Hours</th>';
	echo '<th style="text-align:center;">Total Sales</th>';
	echo '<th style="text-align:center;">Average SPH</th>';
	echo '<th style="text-align:center;">Centre Grade</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
}

for ($i = 0; $i < count($centres); $i++)
{
	if ($self[$centres[$i]] == 1)
	{
		$hours = 0;
		
		$q1 = mysql_query("SELECT timesheet.hours FROM timesheet,auth WHERE auth.centre = '$centres[$i]' AND auth.user = timesheet.user") or die(mysql_error());
		while ($h = mysql_fetch_row($q1))
		{
			$hours += $h[0];
		}
		$total_hours += $hours;
		
		$q2 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND centre = '$centres[$i]'") or die(mysql_error());
		$sales = mysql_num_rows($q2);
		$total_sales += $sales;
		
		$sph = $sales / $hours;
		
		if ($sph < 0.15) { $grade = "D"; } elseif ($sph < 0.2) { $grade = "C"; } elseif ($sph < 0.25) { $grade = "B"; } else { $grade = "A"; }

		echo "<tr>";
		echo "<td><a onclick='Display(\"$centres[$i]\")' style='text-decoration:underline; cursor:pointer;'>" . $centres[$i] . "</a></td>";
		echo "<td style='text-align:center;'>" . $hours . "</td>";
		echo "<td style='text-align:center;'>" . $sales . "</td>";
		echo "<td style='text-align:center;'>" . number_format($sph,2) . "</td>";
		echo "<td style='text-align:center;'>" . $grade . "</td>";
		echo "</tr>";
	}
}

if (array_sum($self) > 0)
{
	$total_sph = $total_sales / $total_hours;
	
	if ($total_sph < 0.15) { $total_grade = "D"; } elseif ($total_sph < 0.2) { $total_grade = "C"; } elseif ($total_sph < 0.25) { $total_grade = "B"; } else { $total_grade = "A"; }
	
	echo "<tr>";
	echo "<td><b>Total</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_hours . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_sales . "</b></td>";
	echo "<td style='text-align:center;'><b>" . number_format($total_sph,2) . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_grade . "</b></td>";
	echo "</tr>";
	echo "</tbody>";
}
?>
</table>
</div>
</center>
<br />
<table width="100%">
<tr>
<td align="left"><img src="../images/centre_breakdown_header.png" width="190" height="25" style="margin-left:3px;" /></td>
<td align="right" style="padding-right:10px;">
<b><span class="centre"></span></b>&nbsp;&nbsp;&nbsp;&nbsp; <select id="display_type" onchange="Change_Display()" style="padding:0px; margin:0px; height:auto; width:60px;">
<option>Active</option>
<option>All</option>
</select>
</td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<input type="hidden" id="centre" value="" />
<center>
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;" width="98%">
<thead>
<tr class="ui-widget-header ">
<th>#</th>
<th>Agent Name</th>
<th style="text-align:center;">Total Hours</th>
<th style="text-align:center;">Total Sales</th>
<th style="text-align:center;">Average SPH</th>
<th style="text-align:center;">Grade</th>
</tr>
</thead>
<tbody id="display">
<script>
var centre = $( "#centre" ),
	method = $( "#display_type" );

$( "#display" ).load('timesheet_display.php?method=' + method.val() + '&centre=' + centre.val());
</script>
</tbody>
</table>
</div>
</center><br />
<input type="button" id="centre_export_btn" onClick="Centre_Export()" class="export" style="display:none;">
<?php
}
else
{
?>
<table width="100%">
<tr>
<td align="left"><img src="../images/agent_breakdown_header.png" width="185" height="25" style="margin-left:3px;" /></td>
<td align="right"><input type="button" onclick="Search()" class="search" />
</td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>
<?php
$ag = $_GET["agent"];
$q = mysql_query("SELECT * FROM auth WHERE user = '$ag'") or die(mysql_error());
$agent = mysql_fetch_assoc($q);

$q1 = mysql_query("SELECT date FROM timesheet WHERE user = '$ag' ORDER BY date ASC") or die(mysql_error());
$start = mysql_fetch_row($q1);

$q2 = mysql_query("SELECT date FROM timesheet WHERE user = '$ag' ORDER BY date DESC") or die(mysql_error());
$end = mysql_fetch_row($q2);
?>
<table width="100%" border="0">
<tr>
<td width="85px">Agent Name: </td>
<td><b><?php echo $agent["first"] . " " . $agent["last"] . " (" . $agent["alias"] . ")"; ?></b></td>
</tr>
<tr>
<td>Centre: </td>
<td><b><?php echo $agent["centre"]; ?></b></td>
</tr>
<tr>
<td>Start Date: </td>
<td><b><?php echo date("d/m/Y", strtotime($start[0])); ?></b></td>
</tr>
<tr>
<td>End Date: </td>
<td><b><?php echo date("d/m/Y", strtotime($end[0])); ?></b></td>
</tr>
</table><br />

<center>
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;" width="98%">
<thead>
<tr class="ui-widget-header ">
<th>Week Ending</th>
<th style="text-align:center;">Total Hours</th>
<th style="text-align:center;">Total Sales</th>
<th style="text-align:center;">Average SPH</th>
<th style="text-align:center;">Grade</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT * FROM timesheet WHERE user = '$ag' GROUP BY WEEK(date) ORDER BY date ASC") or die(mysql_error());

while ($data = mysql_fetch_assoc($q))
{
	$week = date("W", strtotime($data["date"]));
	$year = date("Y", strtotime($data["date"]));
	$we = date("Y-m-d", strtotime($year . "W" . $week . "7"));
	
	$hours = 0;
	$q0 = mysql_query("SELECT hours FROM timesheet WHERE user = '$ag' AND WEEK(date) = '$week'") or die(mysql_error());
	while ($h = mysql_fetch_row($q0))
	{
		$hours += $h[0];
	}
	$total_hours += $hours;
	
	$q1 = mysql_query("SELECT * FROM sales_customers WHERE agent = '$ag' AND WEEK(approved_timestamp) = '$week' AND status = 'Approved'") or die(mysql_error());
	$sales = mysql_num_rows($q1);
	$total_sales += $sales;
	
	$sph = $sales / $data["hours"];
	
	if ($sph < 0.15) { $grade = "D"; } elseif ($sph < 0.2) { $grade = "C"; } elseif ($sph < 0.25) { $grade = "B"; } else { $grade = "A"; }
	
	echo "<tr>";
	echo "<td><a onclick='View(\"$we\")' style='text-decoration:underline; cursor:pointer;'>W.E. " . date("d/m/Y", strtotime($we)) . "</a></td>";
	echo "<td style='text-align:center;'>" . $hours  . "</td>";
	echo "<td style='text-align:center;'>" . $sales . "</td>";
	echo "<td style='text-align:center;'>" . number_format($sph,2) . "</td>";
	echo "<td style='text-align:center;'>" . $grade . "</td>";
	echo "</tr>";
}

$total_sph = $total_sales / $total_hours;

if ($total_sph < 0.15) { $total_grade = "D"; } elseif ($total_sph < 0.2) { $total_grade = "C"; } elseif ($total_sph < 0.25) { $total_grade = "B"; } else { $total_grade = "A"; }

echo "<tr>";
echo "<td><b>Total</b></td>";
echo "<td style='text-align:center;'><b>" . $total_hours  . "</b></td>";
echo "<td style='text-align:center;'><b>" . $total_sales . "</b></td>";
echo "<td style='text-align:center;'><b>" . number_format($total_sph,2) . "</b></td>";
echo "<td style='text-align:center;'><b>" . $total_grade . "</b></td>";
echo "</tr>";
?>
</tbody>
</table>
</div>
</center><br />
<input type="button" onClick="Agent_Export()" class="export">
<select id="export_type" style="margin:0px; padding:0px; height:20px; margin-left:10px; width:80px;">
<option value="weekly">Weekly</option>
<option value="daily">Daily</option>
</select>
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