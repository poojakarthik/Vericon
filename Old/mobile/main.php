<?php
include "auth/restrict.php";

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$centres = array();
$q = mysql_query("SELECT centre FROM vericon.centres WHERE status = 'Enabled' ORDER BY centre ASC") or die(mysql_error());
while ($centre = mysql_fetch_row($q))
{
	array_push($centres, $centre[0]);
}

if ($_GET["date"] == "")
{
	$date1 = date("Y-m-d");
	$date2 = date("Y-m-d");
}
else
{
	$date1 = $_GET["date"];
	$date2 = $_GET["date"];
}

$currentday = strtotime($date1);
$lessday = strtotime(date("Y-m-d", $currentday) . " -1 day");
$addday = strtotime(date("Y-m-d", $currentday) . " +1 day");
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>VeriCon :: Sale Stats</title>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0-rc.1/jquery.mobile-1.1.0-rc.1.min.css" />
<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script src="http://code.jquery.com/mobile/1.1.0-rc.1/jquery.mobile-1.1.0-rc.1.min.js"></script>
<style>
table { margin: 1em 0; border-collapse: collapse; width: 100%; }
table td { border:1px solid #999; padding: .3em 5px; text-align: left; }
table th { padding: .3em 5px; text-align: center; border:1px solid #456f9a;background:#5e87b0;color:#fff;font-weight:bold;text-shadow:0 1px 1px #254f7a;background-image:-webkit-gradient(linear,left top,left bottom,from(#81a8ce),to(#5e87b0));background-image:-webkit-linear-gradient(#6facd5,#497bae);background-image:-moz-linear-gradient(#81a8ce,#5e87b0);background-image:-ms-linear-gradient(#81a8ce,#5e87b0);background-image:-o-linear-gradient(#81a8ce,#5e87b0);background-image:linear-gradient(#81a8ce,#5e87b0); }
</style>
</head>

<body>
<div data-role="page" data-theme="b">

<div data-role="header" data-position="inline" data-theme="b">
<h1>Sale Stats</h1>
</div>
<div data-role="content" data-theme="b">
<table style="margin:0; margin-top:-15px;">
<tr>
<td style="border:none;"><a href="?token=<?php echo $_GET["token"]; ?>&date=<?php echo date("Y-m-d", $lessday); ?>" data-role="button" data-icon="arrow-l" data-iconpos="notext" data-ajax="false" style="float:left;"></a></td>
<td style="border:none; text-align:center;"><b><?php echo date("l - d/m/Y", $currentday); ?></b></td>
<td style="border:none;"><?php if ($currentday >= strtotime(date("Y-m-d"))) { ?> <a data-role="button" data-icon="delete" data-iconpos="notext" data-ajax="false" style="float:right;"></a> <?php } else { ?> <a href="?token=<?php echo $_GET["token"]; ?>&date=<?php echo date("Y-m-d", $addday); ?>" data-role="button" data-icon="arrow-r" data-iconpos="notext" data-ajax="false" style="float:right;"></a> <?php } ?></td>
</tr>
</table>
<center>
<table width="100%">
<?php //captive
$total_approved = 0;
$total_declined = 0;
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
	echo '<tr>';
	echo '<th colspan="3" style="text-align:center;">Captive</th>';
	echo '</tr>';
	echo '<tr>';
	echo '<th style="text-align:left;">Centre</th>';
	echo '<th>Approved</th>';
	echo '<th>Declined</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
}

for ($i = 0; $i < count($centres); $i++)
{
	if ($captive[$centres[$i]] == 1)
	{
		$q1 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND centre = '$centres[$i]' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
		$approved = mysql_num_rows($q1);
		$total_approved += $approved;
		
		$q2 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Declined' AND centre = '$centres[$i]' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
		$declined = mysql_num_rows($q2);
		$total_declined += $declined;
		
		echo "<tr>";
		echo "<td>" . $centres[$i] . "</td>";
		echo "<td style='text-align:center;'>" . $approved . "</td>";
		echo "<td style='text-align:center;'>" . $declined . "</td>";
		echo "</tr>";
	}
}

if (array_sum($captive) > 0)
{
	echo "<tr>";
	echo "<td><b>Total</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_approved . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_declined . "</b></td>";
	echo "</tr>";
	echo "</tbody>";
}
?>
<?php //outsourced
$total_approved = 0;
$total_declined = 0;
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
	echo '<tr>';
	echo '<th colspan="3" style="text-align:center;">Outsourced</th>';
	echo '</tr>';
	echo '<tr>';
	echo '<th style="text-align:left;">Centre</th>';
	echo '<th>Approved</th>';
	echo '<th>Declined</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
}

for ($i = 0; $i < count($centres); $i++)
{
	if ($outsourced[$centres[$i]] == 1)
	{
		$q1 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND centre = '$centres[$i]' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
		$approved = mysql_num_rows($q1);
		$total_approved += $approved;
		
		$q2 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Declined' AND centre = '$centres[$i]' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
		$declined = mysql_num_rows($q2);
		$total_declined += $declined;
		
		echo "<tr>";
		echo "<td>" . $centres[$i] . "</td>";
		echo "<td style='text-align:center;'>" . $approved . "</td>";
		echo "<td style='text-align:center;'>" . $declined . "</td>";
		echo "</tr>";
	}
}

if (array_sum($captive) > 0)
{
	echo "<tr>";
	echo "<td><b>Total</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_approved . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_declined . "</b></td>";
	echo "</tr>";
	echo "</tbody>";
}
?>
<?php //self
$total_approved = 0;
$total_declined = 0;
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
	echo '<tr>';
	echo '<th colspan="3" style="text-align:center;">Melbourne</th>';
	echo '</tr>';
	echo '<tr>';
	echo '<th style="text-align:left;">Centre</th>';
	echo '<th>Approved</th>';
	echo '<th>Declined</th>';
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
}

for ($i = 0; $i < count($centres); $i++)
{
	if ($self[$centres[$i]] == 1)
	{
		$q1 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND centre = '$centres[$i]' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
		$approved = mysql_num_rows($q1);
		$total_approved += $approved;
		
		$q2 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Declined' AND centre = '$centres[$i]' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
		$declined = mysql_num_rows($q2);
		$total_declined += $declined;
		
		echo "<tr>";
		echo "<td>" . $centres[$i] . "</td>";
		echo "<td style='text-align:center;'>" . $approved . "</td>";
		echo "<td style='text-align:center;'>" . $declined . "</td>";
		echo "</tr>";
	}
}

if (array_sum($captive) > 0)
{
	echo "<tr>";
	echo "<td><b>Total</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_approved . "</b></td>";
	echo "<td style='text-align:center;'><b>" . $total_declined . "</b></td>";
	echo "</tr>";
	echo "</tbody>";
}
?>
</table>
</center>
</div>
<div data-role="footer" data-position="inline" data-theme="b">
<h4><p style="font-size:9px;">Copyright &copy; VeriCon | All Rights Reserved 2011-<?php echo date("Y"); ?><br/>
Designed & Developed by Team VeriCon</p></h4>
</div>
</div>
</body>
</html>
