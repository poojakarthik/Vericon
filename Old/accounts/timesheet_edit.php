<?php
mysql_connect('localhost','vericon','18450be');

$centre = $_GET["centre"];
$date = $_GET["date"];
$week = date("W", strtotime($date));
$year = date("Y", strtotime($date));
$date1 = date("Y-m-d", strtotime($year . "W" . $week . "1"));
$week1 = date("W", strtotime($date1));
$date2 = date("Y-m-d", strtotime($year . "W" . $week . "7"));
$week2 = date("W", strtotime($date2));
?>

<script>
$(function() {
	$( "#datepicker" ).datepicker( {
		showOn: "button",
		buttonImage: "../images/calendar.png",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		firstDay: 1,
		showOtherMonths: true,
		selectOtherMonths: true,
		changeMonth: true,
		changeYear: true,
		maxDate: "<?php echo date("Y-m-d", strtotime("-1 week")); ?>",
		onSelect: function(dateText, inst) {
			var centre = "<?php echo $centre; ?>";
			
			$( "#display" ).hide( 'blind', '', 'slow', function() {
				$( "#display" ).load('timesheet_display.php?centre=' + centre + '&date=' + dateText, function(){
					$( "#display" ).show( 'blind', '', 'slow');
				});
			});
		}});
});
</script>

<table width="100%">
<tr>
<td align="left"><img src="../images/centre_timesheet_header.png" width="175" height="25" style="margin-left:3px;" /></td>
<td align="right" style="padding-right:10px;"><select id="centre" style="width:75px;" onchange="Centre()">
<option>Centre</option>
<?php
$q = mysql_query("SELECT centre FROM vericon.centres WHERE status = 'Enabled' ORDER BY centre ASC") or die(mysql_error());
while ($centres = mysql_fetch_row($q))
{
	echo "<option>" . $centres[0] . "</option>";
}
?>
</select>
<input type='text' size='9' id='from' readonly='readonly' style="height:20px;" value="<?php echo date("d/m/Y", strtotime($date1)); ?>" /> to <input type='text' size='9' id='to' readonly='readonly' style="height:20px;" value="<?php echo date("d/m/Y", strtotime($date2)); ?>" /><input type='hidden' id='datepicker' value="<?php echo $date; ?>" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<script>
$( "#centre" ).val("<?php echo $centre; ?>");
</script>

<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header">
<th style="text-align:left">Full Name</th>
<th>Hours</th>
<th>Bonus</th>
<th>Rate</th>
<th>Rate Bonus</th>
<th>Gross Pay</th>
<th>PAYG</th>
<th>Net Pay</th>
<th>Other</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT * FROM vericon.timesheet WHERE centre = '$centre' AND date BETWEEN '$date1' AND '$date2' GROUP BY user ORDER BY user ASC") or die(mysql_error());

if ($centre == "Centre")
{
	echo "<tr><td colspan='9'>Please Select a Centre From Above</td></tr>";
}
elseif (mysql_num_rows($q) == 0)
{
	echo "<tr><td colspan='9'>No Records Found!</td></tr>";
}
else
{
	while ($data = mysql_fetch_assoc($q))
	{
		$q0 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$data[user]'") or die(mysql_error());
		$user = mysql_fetch_row($q0);
		
		$q1 = mysql_query("SELECT SUM(op_hours),SUM(op_bonus),AVG(rate),SUM(payg),SUM(annual),SUM(sick),pay_type,AVG(base_rate) FROM vericon.timesheet_other WHERE user = '$data[user]' AND week BETWEEN '$week1' AND '$week2'") or die(mysql_error());
		$da = mysql_fetch_row($q1);
		
		$q2 = mysql_query("SELECT rate,type FROM vericon.timesheet_rate WHERE user = '$data[user]'") or die(mysql_error());
		$r = mysql_fetch_row($q2);
		
		$hours = number_format($da[0],2);
		$bonus = number_format($da[1],2);
		$hours_d = "<input type='text' id='$data[user]_hours' value='$hours' onChange='Hours(\"$data[user]\")' style='height:15px; width:35px;'>";
		$bonus_d = "\$<input type='text' id='$data[user]_bonus' value='$bonus' onChange='Bonus(\"$data[user]\")' style='height:15px; width:35px;'>";
		if ($da[2] <= 0)
		{
			$r_type = $r[1];
			
			if ($r[1] == "F")
			{
				$rate = $r[0];
				$r_bonus = 0;
				$a_rate = 0;
			}
			elseif ($r[1] == "T")
			{
				$q3 = mysql_query("SELECT designation FROM vericon.timesheet_designation WHERE user = '$data[user]'") or die(mysql_error());
				$desig = mysql_fetch_row($q3);
				
				$q3 = mysql_query("SELECT rate FROM vericon.timesheet_tiered WHERE designation = '$desig[0]' AND `from` = '0'");
				$b_rate = mysql_fetch_row($q3);
				$rate = $b_rate[0];
				
				$q3 = mysql_query("SELECT id FROM vericon.sales_customers WHERE agent = '$data[user]' AND status = 'Approved' AND WEEK(approved_timestamp,3) BETWEEN '$week1' AND '$week2'");
				$sales = mysql_num_rows($q3);
				
				$q3 = mysql_query("SELECT rate FROM vericon.timesheet_tiered WHERE designation = '$desig[0]' AND '$sales' BETWEEN `from` AND `to`") or die(mysql_error());
				$t_rate = mysql_fetch_row($q3);
				
				$r_bonus = ($t_rate[0] - $rate) * ($da[0] + $da[4] + $da[5]);
				$a_rate = $t_rate[0];
			}
		}
		else
		{
			$r_type = $da[6];
			
			if ($da[6] == "F")
			{
				$rate = $da[2];
				$r_bonus = 0;
				$a_rate = 0;
			}
			elseif ($da[6] == "T")
			{
				$rate = $da[7];
				$r_bonus = ($da[2] - $rate) * ($da[0] + $da[4] + $da[5]);
				$a_rate = $da[2];
			}
		}
		$rate_d = "\$" . number_format($rate,4);
		$r_bonus_d = "\$" . number_format($r_bonus,2);
		$gross = ($rate * ($da[0] + $da[4] + $da[5])) + $da[1] + $r_bonus;
		$gross_d = "\$" . number_format($gross,2);
		$payg = $da[3];
		$payg_d = "\$<input type='text' id='$data[user]_payg' value='$payg' onChange='PAYG(\"$data[user]\")' style='height:15px; width:35px;'>";
		$net = $gross - $payg;
		$net_d = "\$" . number_format($net,2);
		$other_d = "<button onclick='More_Edit(\"$data[user]\")' class='icon_notes' title='More'></button>";
		
		if ($da[0] == "" || $da[0] == 0)
		{
			$hours_d = "-";
			$bonus_d = "-";
			$rate_d = "-";
			$r_bonus_d = "-";
			$gross_d = "-";	
			$payg_d = "-";
			$net_d = "-";
			$other_d = "-";
		}
		elseif ($rate_d == "$0.00")
		{
			$hours_d = $hours;
			$bonus_d = "\$" . $bonus;
			$rate_d = "-";
			$r_bonus_d = "-";
			$gross_d = "-";	
			$payg_d = "-";
			$net_d = "-";
			$other_d = "-";
		}
		elseif ($da[2] <= 0)
		{
			$payg = "";
			$payg_d = "$<input type='text' id='$data[user]_payg' value='$payg' onChange='PAYG(\"$data[user]\")' style='height:15px; width:35px;'>";
			$net_d = "-";
		}
		
		echo "<tr>";
		echo "<input type='hidden' id='$data[user]_type' value='" . $r_type . "'>";
		echo "<input type='hidden' id='$data[user]_actual' value='" . $a_rate . "'>";
		echo "<td style='text-align:left;'>" . $user[0] . " " . $user[1] . "</td>";
		echo "<td>" . $hours_d . "</td>";
		echo "<td>" . $bonus_d . "</td>";
		echo "<td><span id='$data[user]_rate'>" . $rate_d . "</span></td>";
		echo "<td>" . $r_bonus_d . "</td>";
		echo "<td><span id='$data[user]_gross'>" . $gross_d . "</span></td>";
		echo "<td>" . $payg_d . "</td>";
		echo "<td><span id='$data[user]_net'>" . $net_d . "</span></td>";
		echo "<td>" . $other_d . "</td>";
		echo "</tr>";
	}
	
	$q1 = mysql_query("SELECT m_cost FROM vericon.timesheet_mcost WHERE centre = '$centre' AND week = '$week'") or die(mysql_error());
	$da = mysql_fetch_row($q1);
	
	if ($da[0] == "")
	{
		$m_cost = "";
	}
	else
	{
		$m_cost = number_format($da[0],2);
	}
	
	echo "<tr>";
	echo "<td colspan='7' style='text-align:right;'><b>Management Cost</b></td>";
	echo "<td><b>$<input type='text' id='m_cost' value='$m_cost' onChange='M_Cost()' style='height:15px; width:40px;'></b></td>";
	echo "<td></td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div></center><br>

<center><table width="98%">
<tr>
<td align="right"><button onclick="Done()" class="btn">Done</button></td>
</tr>
</table></center>