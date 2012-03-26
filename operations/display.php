<script>
$(function() {
	$( "#accordion" ).accordion({
		autoHeight: false,
		navigation: true
	});
});
</script>
<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$user = $_GET["user"];
$date = $_GET["date"];

$q = mysql_query("SELECT centres FROM operations WHERE user = '$user'") or die(mysql_error());
$cen = mysql_fetch_row($q);
$centres = explode(",",$cen[0]);
?>
<div id="accordion">
<h3><a href="#section1"><img src="../images/call_conversion_header.png" width="120" height="15" style="margin-left:3px;" /></a></h3>
<div>
<center><img src="chart2.php?centre=<?php echo $cen[0]; ?>&date=<?php echo $date; ?>" /></center>
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content">
<?php //captive
$total_approved = 0;
$total_declined = 0;
$total_line_issue = 0;
$total_total = 0;
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
	echo '<th colspan="5" style="text-align:center;">Captive</th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th>Centre</th>';
	echo '<th>Approved</th>';
	echo '<th>Declined</th>';
	echo '<th>Line Issue</th>';
	echo '<th>Total</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
}

for ($i = 0; $i < count($centres); $i++)
{
	if ($captive[$centres[$i]] == 1)
	{
		$q1 = mysql_query("SELECT * FROM tpv_notes WHERE status = 'Approved' AND centre = '$centres[$i]' AND DATE(timestamp) = '$date'") or die(mysql_error());
		$approved = mysql_num_rows($q1);
		$total_approved += mysql_num_rows($q1);
		$q2 = mysql_query("SELECT * FROM tpv_notes WHERE status = 'Declined' AND centre = '$centres[$i]' AND DATE(timestamp) = '$date'") or die(mysql_error());
		$declined = mysql_num_rows($q2);
		$total_declined += mysql_num_rows($q2);
		$q3 = mysql_query("SELECT * FROM tpv_notes WHERE status = 'Line Issue' AND centre = '$centres[$i]' AND DATE(timestamp) = '$date'") or die(mysql_error());
		$line_issue = mysql_num_rows($q3);
		$total_line_issue += mysql_num_rows($q3);
		$total = $approved + $declined + $line_issue;
		
		echo "<tr>";
		echo "<td>" . $centres[$i] . "</td>";
		echo "<td style='text-align:center;'>" . $approved . "</td>";
		echo "<td style='text-align:center;'>" . $declined . "</td>";
		echo "<td style='text-align:center;'>" . $line_issue . "</td>";
		echo "<td style='text-align:center;'><b>" . $total . "</b></td>";
		echo "</tr>";
	}
}

if (array_sum($captive) > 0)
{
	$total_total = $total_approved + $total_declined + $total_line_issue;
	echo "<tr>";
	echo "<td><b>Total</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_approved . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_declined . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_line_issue . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_total . "</b></td>";
	echo "</tr>";
	echo "</tbody>";
}
?>
<?php //outsourced
$total_approved = 0;
$total_declined = 0;
$total_line_issue = 0;
$total_total = 0;
for ($i = 0; $i < count($centres); $i++)
{
	$q0 = mysql_query("SELECT centre FROM centres WHERE centre = '$centres[$i]' AND type = 'Outsourced'") or die(mysql_error());
	if (mysql_num_rows($q0) != 0)
	{
		$outsourced[$centres[$i]] = 1;
	}
}

if (array_sum($outsourced) > 0)
{
	echo '<thead>';
	echo '<tr class="ui-widget-header ">';
	echo '<th colspan="5" style="text-align:center;">Outsourced</th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th>Centre</th>';
	echo '<th>Approved</th>';
	echo '<th>Declined</th>';
	echo '<th>Line Issue</th>';
	echo '<th>Total</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
}

for ($i = 0; $i < count($centres); $i++)
{
	if ($outsourced[$centres[$i]] == 1)
	{
		$q1 = mysql_query("SELECT * FROM tpv_notes WHERE status = 'Approved' AND centre = '$centres[$i]' AND DATE(timestamp) = '$date'") or die(mysql_error());
		$approved = mysql_num_rows($q1);
		$total_approved += mysql_num_rows($q1);
		$q2 = mysql_query("SELECT * FROM tpv_notes WHERE status = 'Declined' AND centre = '$centres[$i]' AND DATE(timestamp) = '$date'") or die(mysql_error());
		$declined = mysql_num_rows($q2);
		$total_declined += mysql_num_rows($q2);
		$q3 = mysql_query("SELECT * FROM tpv_notes WHERE status = 'Line Issue' AND centre = '$centres[$i]' AND DATE(timestamp) = '$date'") or die(mysql_error());
		$line_issue = mysql_num_rows($q3);
		$total_line_issue += mysql_num_rows($q3);
		$total = $approved + $declined + $line_issue;
		
		echo "<tr>";
		echo "<td>" . $centres[$i] . "</td>";
		echo "<td style='text-align:center;'>" . $approved . "</td>";
		echo "<td style='text-align:center;'>" . $declined . "</td>";
		echo "<td style='text-align:center;'>" . $line_issue . "</td>";
		echo "<td style='text-align:center;'><b>" . $total . "</b></td>";
		echo "</tr>";
	}
}

if (array_sum($outsourced) > 0)
{
	$total_total = $total_approved + $total_declined + $total_line_issue;
	echo "<tr>";
	echo "<td><b>Total</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_approved . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_declined . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_line_issue . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_total . "</b></td>";
	echo "</tr>";
	echo "</tbody>";
}
?>
<?php //self
$total_approved = 0;
$total_declined = 0;
$total_line_issue = 0;
$total_total = 0;
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
	echo '<th colspan="5" style="text-align:center;">Self</th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th>Centre</th>';
	echo '<th>Approved</th>';
	echo '<th>Declined</th>';
	echo '<th>Line Issue</th>';
	echo '<th>Total</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
}

for ($i = 0; $i < count($centres); $i++)
{
	if ($self[$centres[$i]] == 1)
	{
		$q1 = mysql_query("SELECT * FROM tpv_notes WHERE status = 'Approved' AND centre = '$centres[$i]' AND DATE(timestamp) = '$date'") or die(mysql_error());
		$approved = mysql_num_rows($q1);
		$total_approved += mysql_num_rows($q1);
		$q2 = mysql_query("SELECT * FROM tpv_notes WHERE status = 'Declined' AND centre = '$centres[$i]' AND DATE(timestamp) = '$date'") or die(mysql_error());
		$declined = mysql_num_rows($q2);
		$total_declined += mysql_num_rows($q2);
		$q3 = mysql_query("SELECT * FROM tpv_notes WHERE status = 'Line Issue' AND centre = '$centres[$i]' AND DATE(timestamp) = '$date'") or die(mysql_error());
		$line_issue = mysql_num_rows($q3);
		$total_line_issue += mysql_num_rows($q3);
		$total = $approved + $declined + $line_issue;
		
		echo "<tr>";
		echo "<td>" . $centres[$i] . "</td>";
		echo "<td style='text-align:center;'>" . $approved . "</td>";
		echo "<td style='text-align:center;'>" . $declined . "</td>";
		echo "<td style='text-align:center;'>" . $line_issue . "</td>";
		echo "<td style='text-align:center;'><b>" . $total . "</b></td>";
		echo "</tr>";
	}
}

if (array_sum($self) > 0)
{
	$total_total = $total_approved + $total_declined + $total_line_issue;
	echo "<tr>";
	echo "<td><b>Total</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_approved . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_declined . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_line_issue . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_total . "</b></td>";
	echo "</tr>";
	echo "</tbody>";
}
?>
</table>
</div></center>
</div>
<h3><a href="#section2"><img src="../images/customer_conversion_header.png" width="160" height="15" /></a></h3>
<div>
<center><img src="chart.php?centre=<?php echo $cen[0]; ?>&date=<?php echo $date; ?>" /></center>
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content">
<?php //captive
$total_approved = 0;
$total_declined = 0;
$total_line_issue = 0;
$total_total = 0;
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
	echo '<th colspan="5" style="text-align:center;">Captive</th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th>Centre</th>';
	echo '<th>Approved</th>';
	echo '<th>Declined</th>';
	echo '<th>Line Issue</th>';
	echo '<th>Total</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
}

for ($i = 0; $i < count($centres); $i++)
{
	if ($captive[$centres[$i]] == 1)
	{
		$q1 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND centre = '$centres[$i]' AND DATE(timestamp) = '$date'") or die(mysql_error());
		$approved = mysql_num_rows($q1);
		$total_approved += mysql_num_rows($q1);
		$q2 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Declined' AND centre = '$centres[$i]' AND DATE(timestamp) = '$date'") or die(mysql_error());
		$declined = mysql_num_rows($q2);
		$total_declined += mysql_num_rows($q2);
		$q3 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Line Issue' AND centre = '$centres[$i]' AND DATE(timestamp) = '$date'") or die(mysql_error());
		$line_issue = mysql_num_rows($q3);
		$total_line_issue += mysql_num_rows($q3);
		$total = $approved + $declined + $line_issue;
		
		echo "<tr>";
		echo "<td>" . $centres[$i] . "</td>";
		echo "<td style='text-align:center;'>" . $approved . "</td>";
		echo "<td style='text-align:center;'>" . $declined . "</td>";
		echo "<td style='text-align:center;'>" . $line_issue . "</td>";
		echo "<td style='text-align:center;'><b>" . $total . "</b></td>";
		echo "</tr>";
	}
}

if (array_sum($captive) > 0)
{
	$total_total = $total_approved + $total_declined + $total_line_issue;
	echo "<tr>";
	echo "<td><b>Total</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_approved . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_declined . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_line_issue . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_total . "</b></td>";
	echo "</tr>";
	echo "</tbody>";
}
?>
<?php //outsourced
$total_approved = 0;
$total_declined = 0;
$total_line_issue = 0;
$total_total = 0;
for ($i = 0; $i < count($centres); $i++)
{
	$q0 = mysql_query("SELECT centre FROM centres WHERE centre = '$centres[$i]' AND type = 'Outsourced'") or die(mysql_error());
	if (mysql_num_rows($q0) != 0)
	{
		$outsourced[$centres[$i]] = 1;
	}
}

if (array_sum($outsourced) > 0)
{
	echo '<thead>';
	echo '<tr class="ui-widget-header ">';
	echo '<th colspan="5" style="text-align:center;">Outsourced</th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th>Centre</th>';
	echo '<th>Approved</th>';
	echo '<th>Declined</th>';
	echo '<th>Line Issue</th>';
	echo '<th>Total</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
}

for ($i = 0; $i < count($centres); $i++)
{
	if ($outsourced[$centres[$i]] == 1)
	{
		$q1 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND centre = '$centres[$i]' AND DATE(timestamp) = '$date'") or die(mysql_error());
		$approved = mysql_num_rows($q1);
		$total_approved += mysql_num_rows($q1);
		$q2 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Declined' AND centre = '$centres[$i]' AND DATE(timestamp) = '$date'") or die(mysql_error());
		$declined = mysql_num_rows($q2);
		$total_declined += mysql_num_rows($q2);
		$q3 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Line Issue' AND centre = '$centres[$i]' AND DATE(timestamp) = '$date'") or die(mysql_error());
		$line_issue = mysql_num_rows($q3);
		$total_line_issue += mysql_num_rows($q3);
		$total = $approved + $declined + $line_issue;
		
		echo "<tr>";
		echo "<td>" . $centres[$i] . "</td>";
		echo "<td style='text-align:center;'>" . $approved . "</td>";
		echo "<td style='text-align:center;'>" . $declined . "</td>";
		echo "<td style='text-align:center;'>" . $line_issue . "</td>";
		echo "<td style='text-align:center;'><b>" . $total . "</b></td>";
		echo "</tr>";
	}
}

if (array_sum($outsourced) > 0)
{
	$total_total = $total_approved + $total_declined + $total_line_issue;
	echo "<tr>";
	echo "<td><b>Total</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_approved . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_declined . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_line_issue . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_total . "</b></td>";
	echo "</tr>";
	echo "</tbody>";
}
?>
<?php //self
$total_approved = 0;
$total_declined = 0;
$total_line_issue = 0;
$total_total = 0;
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
	echo '<th colspan="5" style="text-align:center;">Self</th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th>Centre</th>';
	echo '<th>Approved</th>';
	echo '<th>Declined</th>';
	echo '<th>Line Issue</th>';
	echo '<th>Total</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
}

for ($i = 0; $i < count($centres); $i++)
{
	if ($self[$centres[$i]] == 1)
	{
		$q1 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND centre = '$centres[$i]' AND DATE(timestamp) = '$date'") or die(mysql_error());
		$approved = mysql_num_rows($q1);
		$total_approved += mysql_num_rows($q1);
		$q2 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Declined' AND centre = '$centres[$i]' AND DATE(timestamp) = '$date'") or die(mysql_error());
		$declined = mysql_num_rows($q2);
		$total_declined += mysql_num_rows($q2);
		$q3 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Line Issue' AND centre = '$centres[$i]' AND DATE(timestamp) = '$date'") or die(mysql_error());
		$line_issue = mysql_num_rows($q3);
		$total_line_issue += mysql_num_rows($q3);
		$total = $approved + $declined + $line_issue;
		
		echo "<tr>";
		echo "<td>" . $centres[$i] . "</td>";
		echo "<td style='text-align:center;'>" . $approved . "</td>";
		echo "<td style='text-align:center;'>" . $declined . "</td>";
		echo "<td style='text-align:center;'>" . $line_issue . "</td>";
		echo "<td style='text-align:center;'><b>" . $total . "</b></td>";
		echo "</tr>";
	}
}

if (array_sum($self) > 0)
{
	$total_total = $total_approved + $total_declined + $total_line_issue;
	echo "<tr>";
	echo "<td><b>Total</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_approved . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_declined . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_line_issue . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_total . "</b></td>";
	echo "</tr>";
	echo "</tbody>";
}
?>
</table>
</div></center>
</div>
</div>