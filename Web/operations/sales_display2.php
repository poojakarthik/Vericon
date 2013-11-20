<?php
mysql_connect('localhost','vericon','18450be');

$centres = explode(",", $_GET["centres"]);
$date = $_GET["date"];
?>

<center><table width="99%">
<tr>
<td valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; margin-top:0px;">
<?php //captive
$total_approved = 0;
$total_declined = 0;
$total_line_issue = 0;
$total_total = 0;
for ($i = 0; $i < count($centres); $i++)
{
	$q0 = mysql_query("SELECT centre FROM vericon.centres WHERE centre = '$centres[$i]' AND type = 'Captive'") or die(mysql_error());
	if (mysql_num_rows($q0) != 0)
	{
		$captive[$centres[$i]] = 1;
	}
}

if (array_sum($captive) > 0)
{
	echo '<thead>';
	echo '<tr class="ui-widget-header ">';
	echo '<th colspan="6" style="text-align:center;">Captive</th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th>Centre</th>';
	echo '<th style="text-align:center;">Approved</th>';
	echo '<th style="text-align:center;">Declined</th>';
	echo '<th style="text-align:center;">Line</th>';
	echo '<th style="text-align:center;">Total</th>';
	echo '<th></th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
}

for ($i = 0; $i < count($centres); $i++)
{
	if ($captive[$centres[$i]] == 1)
	{
		$q1 = mysql_query("SELECT * FROM vericon.sales_customers WHERE status = 'Approved' AND centre = '$centres[$i]' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
		$approved = mysql_num_rows($q1);
		$total_approved += mysql_num_rows($q1);
		
		$q2 = mysql_query("SELECT * FROM vericon.sales_customers WHERE status = 'Declined' AND centre = '$centres[$i]' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
		$declined = mysql_num_rows($q2);
		$total_declined += mysql_num_rows($q2);
		
		$q3 = mysql_query("SELECT * FROM vericon.sales_customers WHERE status = 'Line Issue' AND centre = '$centres[$i]' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
		$line_issue = mysql_num_rows($q3);
		$total_line_issue += mysql_num_rows($q3);
		
		$total = $approved + $declined + $line_issue;
		
		echo "<tr>";
		echo "<td>" . $centres[$i] . "</td>";
		echo "<td style='text-align:center;'>" . $approved . "</td>";
		echo "<td style='text-align:center;'>" . $declined . "</td>";
		echo "<td style='text-align:center;'>" . $line_issue . "</td>";
		echo "<td style='text-align:center;'><b>" . $total . "</b></td>";
		echo "<td style='text-align:center;'><button onclick='View(\"$centres[$i]\")' class='icon_view'></button></td>";
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
	echo "<td></td>";
	echo "</tr>";
	echo "</tbody>";
}
?>
</table>
</div></center>
</td>
<td valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; margin-top:0px;">
<?php //self
$total_approved = 0;
$total_declined = 0;
$total_line_issue = 0;
$total_total = 0;
for ($i = 0; $i < count($centres); $i++)
{
	$q0 = mysql_query("SELECT centre FROM vericon.centres WHERE centre = '$centres[$i]' AND type = 'Self'") or die(mysql_error());
	if (mysql_num_rows($q0) != 0)
	{
		$self[$centres[$i]] = 1;
	}
}

if (array_sum($self) > 0)
{
	echo '<thead>';
	echo '<tr class="ui-widget-header ">';
	echo '<th colspan="6" style="text-align:center;">Melbourne</th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th>Centre</th>';
	echo '<th style="text-align:center;">Approved</th>';
	echo '<th style="text-align:center;">Declined</th>';
	echo '<th style="text-align:center;">Line</th>';
	echo '<th style="text-align:center;">Total</th>';
	echo '<th></th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
}

for ($i = 0; $i < count($centres); $i++)
{
	if ($self[$centres[$i]] == 1)
	{
		$q1 = mysql_query("SELECT * FROM vericon.sales_customers WHERE status = 'Approved' AND centre = '$centres[$i]' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
		$approved = mysql_num_rows($q1);
		$total_approved += mysql_num_rows($q1);
		
		$q2 = mysql_query("SELECT * FROM vericon.sales_customers WHERE status = 'Declined' AND centre = '$centres[$i]' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
		$declined = mysql_num_rows($q2);
		$total_declined += mysql_num_rows($q2);
		
		$q3 = mysql_query("SELECT * FROM vericon.sales_customers WHERE status = 'Line Issue' AND centre = '$centres[$i]' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
		$line_issue = mysql_num_rows($q3);
		$total_line_issue += mysql_num_rows($q3);
		
		$total = $approved + $declined + $line_issue;
		
		echo "<tr>";
		echo "<td>" . $centres[$i] . "</td>";
		echo "<td style='text-align:center;'>" . $approved . "</td>";
		echo "<td style='text-align:center;'>" . $declined . "</td>";
		echo "<td style='text-align:center;'>" . $line_issue . "</td>";
		echo "<td style='text-align:center;'><b>" . $total . "</b></td>";
		echo "<td style='text-align:center;'><button onclick='View(\"$centres[$i]\")' class='icon_view'></button></td>";
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
	echo "<td></td>";
	echo "</tr>";
	echo "</tbody>";
}
?>
</table>
</div></center>
</td>
</tr>
<tr>
<td colspan="2">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; margin-top:0px;">
<?php //outsourced
$total_approved = 0;
$total_declined = 0;
$total_line_issue = 0;
$total_total = 0;
for ($i = 0; $i < count($centres); $i++)
{
	$q0 = mysql_query("SELECT centre FROM vericon.centres WHERE centre = '$centres[$i]' AND type = 'Outsourced'") or die(mysql_error());
	if (mysql_num_rows($q0) != 0)
	{
		$outsourced[$centres[$i]] = 1;
	}
}

if (array_sum($outsourced) > 0)
{
	echo '<thead>';
	echo '<tr class="ui-widget-header ">';
	echo '<th colspan="6" style="text-align:center;">Outsourced</th>';
	echo '</tr>';
	echo '<tr class="ui-widget-header ">';
	echo '<th>Centre</th>';
	echo '<th style="text-align:center;">Approved</th>';
	echo '<th style="text-align:center;">Declined</th>';
	echo '<th style="text-align:center;">Line</th>';
	echo '<th style="text-align:center;">Total</th>';
	echo '<th></th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
}

for ($i = 0; $i < count($centres); $i++)
{
	if ($outsourced[$centres[$i]] == 1)
	{
		$q1 = mysql_query("SELECT * FROM vericon.sales_customers WHERE status = 'Approved' AND centre = '$centres[$i]' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
		$approved = mysql_num_rows($q1);
		$total_approved += mysql_num_rows($q1);
		
		$q2 = mysql_query("SELECT * FROM vericon.sales_customers WHERE status = 'Declined' AND centre = '$centres[$i]' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
		$declined = mysql_num_rows($q2);
		$total_declined += mysql_num_rows($q2);
		
		$q3 = mysql_query("SELECT * FROM vericon.sales_customers WHERE status = 'Line Issue' AND centre = '$centres[$i]' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
		$line_issue = mysql_num_rows($q3);
		$total_line_issue += mysql_num_rows($q3);
		
		$total = $approved + $declined + $line_issue;
		
		echo "<tr>";
		echo "<td>" . $centres[$i] . "</td>";
		echo "<td style='text-align:center;'>" . $approved . "</td>";
		echo "<td style='text-align:center;'>" . $declined . "</td>";
		echo "<td style='text-align:center;'>" . $line_issue . "</td>";
		echo "<td style='text-align:center;'><b>" . $total . "</b></td>";
		echo "<td style='text-align:center;'><button onclick='View(\"$centres[$i]\")' class='icon_view'></button></td>";
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
	echo "<td></td>";
	echo "</tr>";
	echo "</tbody>";
}
?>
</table>
</div></center>
</td>
</tr>
</table>
</center>