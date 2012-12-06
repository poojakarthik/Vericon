<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>
    </title>
    <link rel="stylesheet" href="https://ajax.aspnetcdn.com/ajax/jquery.mobile/1.2.0/jquery.mobile-1.2.0.min.css" />
    <style>
	.ui-page { -webkit-backface-visibility: hidden; }
	table { margin: 1em 0; border-collapse: collapse; width: 100%; }
	table td { border:1px solid #999; padding: .3em 5px; text-align: left; }
	table th { padding: .3em 5px; text-align: center; border:1px solid #456f9a;background:#5e87b0;color:#fff;font-weight:bold;text-shadow:0 1px 1px #254f7a;background-image:-webkit-gradient(linear,left top,left bottom,from(#81a8ce),to(#5e87b0));background-image:-webkit-linear-gradient(#6facd5,#497bae);background-image:-moz-linear-gradient(#81a8ce,#5e87b0);background-image:-ms-linear-gradient(#81a8ce,#5e87b0);background-image:-o-linear-gradient(#81a8ce,#5e87b0);background-image:linear-gradient(#81a8ce,#5e87b0); }
    </style>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
	<script src="https://ajax.aspnetcdn.com/ajax/jquery.mobile/1.2.0/jquery.mobile-1.2.0.min.js"></script>
	<script type="text/javascript" src="../js/blockUI.js"></script>
    <script>
	function V_Loading_Start()
	{
		$.blockUI({ 
			message: '<img src="../images/v_loading.gif" width="64px" height="64px">',
			overlayCSS: {
				cursor: 'default'
			},
			css: { 
				border: 'none',
				padding: '5px',
				backgroundColor: '#000',
				'border-radius': '10px',
				'-webkit-border-radius': '10px',
				'-moz-border-radius': '10px',
				opacity: .75,
				color: '#fff',
				cursor: 'default'
			}
		});
	}
	
	function V_Loading_End()
	{
		$.unblockUI();
	}
	
	function Refresh_Sale_Stats()
	{
		V_Loading_Start();
		for (var i = 0; i < 10; i++)
		{
			$( "#display_sale_stats" ).append("Bla Bla Bla<br>");
		}
		V_Loading_End();
	}
	</script>
</head>
<body>

<div data-role="page" data-theme="b" id="sale_stats">
	<div data-theme="b" data-role="header" data-position="fixed">
		<h1>
			VeriCon
		</h1>
        <a data-role="button" data-inline="true" onClick="Refresh_Sale_Stats()" data-icon="refresh" data-iconpos="notext" class="show-page-loading-msg ui-btn-right"></a>
		<div data-role="navbar" data-iconpos="left">
			<ul>
				<li>
					<a href="#sale_stats" data-transition="flow" data-theme="" data-icon="" class="ui-btn-active ui-state-persist">
						Sale Stats
					</a>
				</li>
				<li>
					<a href="#server_status" data-transition="flow" data-theme="" data-icon="">
						Server Status
					</a>
				</li>
			</ul>
		</div>
	</div>
	<div data-role="content">
    	<fieldset data-role="controlgroup" data-mini="true">
    		<center><input name="date" id="date" value="" onChange="Refresh_Sale_Stats()" type="date" style="width:100px; text-align:center; margin-top:0;"></center>
        </fieldset>
    	<div id="display_sale_stats">
        	<center>
<table width="100%">
<?php //captive
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$centres = array();
$q = mysql_query("SELECT centre FROM vericon.centres WHERE status = 'Enabled' ORDER BY centre ASC") or die(mysql_error());
while ($centre = mysql_fetch_row($q))
{
	array_push($centres, $centre[0]);
}
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
    </div>
	<div data-theme="b" data-role="footer" data-position="fixed">
		<h3>
			<span style="font-size:10px;">Copyrights 2011-2012 | All Rights Reserved @ VeriCon</span>
		</h3>
	</div>
</div>

<div data-role="page" data-theme="b" id="server_status">
	<div data-theme="b" data-role="header" data-position="fixed">
		<h1>
			VeriCon
		</h1>
        <a data-role="button" data-inline="true" onClick="" data-icon="refresh" data-iconpos="notext" class="ui-btn-right">
			
		</a>
		<div data-role="navbar" data-iconpos="left">
			<ul>
				<li>
					<a href="#sale_stats" data-transition="flow" data-theme="" data-icon="">
						Sale Stats
					</a>
				</li>
				<li>
					<a href="#server_status" data-transition="flow" data-theme="" data-icon="" class="ui-btn-active ui-state-persist">
						Server Status
					</a>
				</li>
			</ul>
		</div>
	</div>
	<div data-role="content">
    	Coming Soon Also
    </div>
	<div data-theme="b" data-role="footer" data-position="fixed">
		<h3>
			<span style="font-size:10px;">Copyrights 2011-2012 | All Rights Reserved @ VeriCon</span>
		</h3>
	</div>
</div>