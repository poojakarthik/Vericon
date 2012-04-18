<?php
include "../auth/iprestrict.php";
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
<style>
div#users-contain table { margin: 1em 0; margin-bottom:0; border-collapse: collapse; }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid #eee; padding: .1em 10px; text-align: left; }

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
}

function Change_Display()
{
	var centre = $( "#centre" ),
		method = $( "#display_type" );

	$( "#display" ).load('timesheet_display.php?method=' + method.val() + '&centre=' + centre.val());
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
<?php
if ($_GET["user"] == "")
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
<?php
$q = mysql_query("SELECT centres FROM operations WHERE user = '$ac[user]'") or die(mysql_error());
$cen = mysql_fetch_row($q);
$centres = explode(",",$cen[0]);
?>
<center>
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;">
<?php //captive
$total_hours = 0;
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
}
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
</center>
<?php
}
else
{
?>

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