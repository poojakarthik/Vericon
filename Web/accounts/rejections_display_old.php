<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$method = $_GET["method"];
$date1 = $_GET["date1"];
$date2 = $_GET["date2"];

if ($method == "display")
{
	$q = mysql_query("SELECT centre FROM centres WHERE centre != 'TPV' ORDER BY centre ASC") or die(mysql_error());
	while($centre = mysql_fetch_row($q))
	{
		$cen .= $centre[0] . ",";
	}
	$cen = substr($cen,0,-1);
	$centres = explode(",",$cen);
	?>
	<center>
	<div id="users-contain" class="ui-widget">
	<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;">
	<?php //captive
	$total_vero_approved = 0;
	$total_rework = 0;
	$total_processed = 0;
	$total_rejected = 0;
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
		echo '<th colspan="6" style="text-align:center;">Captive</th>';
		echo '</tr>';
		echo '<tr class="ui-widget-header ">';
		echo '<th>Centre</th>';
		echo '<th>Campaign</th>';
		echo '<th style="text-align:center;">Total Sales</th>';
		echo '<th style="text-align:center;">Reworks</th>';
		echo '<th style="text-align:center;">Rejected</th>';
		echo '<th style="text-align:center;">Processed</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
	}
	
	for ($i = 0; $i < count($centres); $i++)
	{
		if ($captive[$centres[$i]] == 1)
		{
			$q1 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND centre = '$centres[$i]' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
			$vero_approved = mysql_num_rows($q1);
			$total_vero_approved += $vero_approved;
			
			$q2 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Rework' AND centre = '$centres[$i]' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
			$rework = mysql_num_rows($q2);
			$total_rework += $rework;
			
			$q3 = mysql_query("SELECT COUNT(id) FROM qa_customers WHERE status = 'Approved' AND centre = '$centres[$i]' AND DATE(sale_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
			$approved = mysql_fetch_row($q3);
			$total_processed += $approved[0];
			
			$q4 = mysql_query("SELECT COUNT(id) FROM qa_customers WHERE status = 'Rejected' AND centre = '$centres[$i]' AND DATE(sale_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
			$rejected = mysql_fetch_row($q4);
			$total_rejected += $rejected[0];
	
			$q5 = mysql_query("SELECT campaign from centres WHERE centre = '$centres[$i]'") or die(mysql_error());
			$campaign = mysql_fetch_row($q5);
			
			if (($vero_approved + $rework) != 0)
			{
				echo "<tr>";
				echo "<td><a onclick='View(\"$centres[$i]\")' style='text-decoration:underline;cursor:pointer;'>" . $centres[$i] . "</a></td>";
				echo "<td>" . $campaign[0] . "</td>";
				echo "<td style='text-align:center;'>" . ($vero_approved + $rework) . "</td>";
				echo "<td style='text-align:center;'>" . $rework . "</td>";
				echo "<td style='text-align:center;'>" . $rejected[0] . "</td>";
				echo "<td style='text-align:center;'>" . $approved[0] . "</td>";
				echo "</tr>";
			}
		}
	}
	
	if (array_sum($captive) > 0)
	{
		echo "<tr>";
		echo "<td colspan='2' style='text-align:right;'><b>Total</b></td>";
		echo "<td style='text-align:center;'><b>" . ($total_vero_approved + $total_rework) . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_rework . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_rejected . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_processed . "</b></td>";
		echo "</tr>";
		echo "</tbody>";
	}
	?>
	<?php //outsourced
	$total_vero_approved = 0;
	$total_rework = 0;
	$total_processed = 0;
	$total_rejected = 0;
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
		echo '<th colspan="6" style="text-align:center;">Outsourced</th>';
		echo '</tr>';
		echo '<tr class="ui-widget-header ">';
		echo '<th>Centre</th>';
		echo '<th>Campaign</th>';
		echo '<th style="text-align:center;">Total Sales</th>';
		echo '<th style="text-align:center;">Reworks</th>';
		echo '<th style="text-align:center;">Rejected</th>';
		echo '<th style="text-align:center;">Processed</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
	}
	
	for ($i = 0; $i < count($centres); $i++)
	{
		if ($outsourced[$centres[$i]] == 1)
		{
			$q1 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND centre = '$centres[$i]' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
			$vero_approved = mysql_num_rows($q1);
			$total_vero_approved += $vero_approved;
			
			$q2 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Rework' AND centre = '$centres[$i]' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
			$rework = mysql_num_rows($q2);
			$total_rework += $rework;
			
			$q3 = mysql_query("SELECT COUNT(id) FROM qa_customers WHERE status = 'Approved' AND centre = '$centres[$i]' AND DATE(sale_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
			$approved = mysql_fetch_row($q3);
			$total_processed += $approved[0];
			
			$q4 = mysql_query("SELECT COUNT(id) FROM qa_customers WHERE status = 'Rejected' AND centre = '$centres[$i]' AND DATE(sale_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
			$rejected = mysql_fetch_row($q4);
			$total_rejected += $rejected[0];
	
			$q5 = mysql_query("SELECT campaign from centres WHERE centre = '$centres[$i]'") or die(mysql_error());
			$campaign = mysql_fetch_row($q5);		
			
			if (($vero_approved + $rework) != 0)
			{
				echo "<tr>";
				echo "<td><a onclick='View(\"$centres[$i]\")' style='text-decoration:underline;cursor:pointer;'>" . $centres[$i] . "</a></td>";
				echo "<td>" . $campaign[0] . "</td>";
				echo "<td style='text-align:center;'>" . ($vero_approved + $rework) . "</td>";
				echo "<td style='text-align:center;'>" . $rework . "</td>";
				echo "<td style='text-align:center;'>" . $rejected[0] . "</td>";
				echo "<td style='text-align:center;'>" . $approved[0] . "</td>";
				echo "</tr>";
			}
		}
	}
	
	if (array_sum($outsourced) > 0)
	{
		echo "<tr>";
		echo "<td colspan='2' style='text-align:right;'><b>Total</b></td>";
		echo "<td style='text-align:center;'><b>" . ($total_vero_approved + $total_rework) . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_rework . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_rejected . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_processed . "</b></td>";
		echo "</tr>";
		echo "</tbody>";
	}
	?>
	<?php //self
	$total_vero_approved = 0;
	$total_rework = 0;
	$total_processed = 0;
	$total_rejected = 0;
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
		echo '<th colspan="6" style="text-align:center;">Melbourne</th>';
		echo '</tr>';
		echo '<tr class="ui-widget-header ">';
		echo '<th>Centre</th>';
		echo '<th>Campaign</th>';
		echo '<th style="text-align:center;">Total Sales</th>';
		echo '<th style="text-align:center;">Reworks</th>';
		echo '<th style="text-align:center;">Rejected</th>';
		echo '<th style="text-align:center;">Processed</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
	}
	
	for ($i = 0; $i < count($centres); $i++)
	{
		if ($self[$centres[$i]] == 1)
		{
			$q1 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND centre = '$centres[$i]' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
			$vero_approved = mysql_num_rows($q1);
			$total_vero_approved += $vero_approved;
			
			$q2 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Rework' AND centre = '$centres[$i]' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
			$rework = mysql_num_rows($q2);
			$total_rework += $rework;
			
			$q3 = mysql_query("SELECT COUNT(id) FROM qa_customers WHERE status = 'Approved' AND centre = '$centres[$i]' AND DATE(sale_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
			$approved = mysql_fetch_row($q3);
			$total_processed += $approved[0];
			
			$q4 = mysql_query("SELECT COUNT(id) FROM qa_customers WHERE status = 'Rejected' AND centre = '$centres[$i]' AND DATE(sale_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
			$rejected = mysql_fetch_row($q4);
			$total_rejected += $rejected[0];
	
			$q5 = mysql_query("SELECT campaign from centres WHERE centre = '$centres[$i]'") or die(mysql_error());
			$campaign = mysql_fetch_row($q5);		
			
			if (($vero_approved + $rework) != 0)
			{
				echo "<tr>";
				echo "<td><a onclick='View(\"$centres[$i]\")' style='text-decoration:underline;cursor:pointer;'>" . $centres[$i] . "</a></td>";
				echo "<td>" . $campaign[0] . "</td>";
				echo "<td style='text-align:center;'>" . ($vero_approved + $rework) . "</td>";
				echo "<td style='text-align:center;'>" . $rework . "</td>";
				echo "<td style='text-align:center;'>" . $rejected[0] . "</td>";
				echo "<td style='text-align:center;'>" . $approved[0] . "</td>";
				echo "</tr>";
			}
		}
	}
	
	if (array_sum($self) > 0)
	{
		echo "<tr>";
		echo "<td colspan='2' style='text-align:right;'><b>Total</b></td>";
		echo "<td style='text-align:center;'><b>" . ($total_vero_approved + $total_rework) . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_rework . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_rejected . "</b></td>";
		echo "<td style='text-align:center;'><b>" . $total_processed . "</b></td>";
		echo "</tr>";
		echo "</tbody>";
	}
	?>
	</table>
	</div>
	</center>
<?php
}
?>