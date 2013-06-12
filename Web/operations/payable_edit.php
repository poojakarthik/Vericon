<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$centre = $_GET["centre"];
$centres = explode(",",$_GET["centres"]);
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
			var centre = $( "#centre" ),
				centres = "<?php echo implode(",", $centres); ?>";
			
			$( "#display" ).hide( 'blind', '', 'slow', function() {
				$( "#display" ).load('payable_display.php?centre=' + centre.val() + '&centres=' + centres + '&date=' + dateText, function(){
					$( "#display" ).show( 'blind', '', 'slow');
				});
			});
		}
	});
});
</script>

<table width="100%">
<tr>
<td align="left"><img src="../images/centre_timesheet_header.png" width="175" height="25" style="margin-left:3px;" /></td>
<td align="right" style="padding-right:10px;"><select id="centre" style="margin:0px; padding:0px; height:22px; width:75px;" onchange="Centre()">
<option>Centre</option>
<?php
for ($i = 0; $i < count($centres); $i++)
{
	echo "<option>" . $centres[$i] . "</option>";
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
<th colspan="2">Details</th>
<th colspan="3">Timesheet</th>
<th colspan="4">Operations</th>
</tr>
<tr class="ui-widget-header">
<th style="text-align:left">Agent ID</th>
<th style="text-align:left">Full Name</th>
<th>Hours</th>
<th>Bonus</th>
<th>Sales</th>
<th>Hours</th>
<th>Bonus</th>
<th>Net Sales</th>
<th>CPS</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT * FROM timesheet WHERE centre = '$centre' AND date BETWEEN '$date1' AND '$date2' GROUP BY user ORDER BY user ASC") or die(mysql_error());

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
		$q0 = mysql_query("SELECT first,last FROM auth WHERE user = '$data[user]'") or die(mysql_error());
		$user = mysql_fetch_row($q0);
		
		$q1 = mysql_query("SELECT SUM(hours),SUM(bonus),SUM(dialler_hours) FROM timesheet WHERE user = '$data[user]' AND date BETWEEN '$date1' AND '$date2'") or die(mysql_error());
		$da = mysql_fetch_row($q1);
		
		$q2 = mysql_query("SELECT SUM(op_hours),SUM(op_bonus),SUM(cancellations) FROM timesheet_other WHERE user = '$data[user]' AND week BETWEEN '$week1' AND '$week2'") or die(mysql_error());
		$da2 = mysql_fetch_row($q2);
		
		$q3 = mysql_query("SELECT * FROM sales_customers WHERE agent = '$data[user]' AND status = 'Approved' AND DATE(approved_timestamp) BETWEEN '$date1' AND '$date2'") or die(mysql_error());
		$da3 = mysql_num_rows($q3);
		
		$q4 = mysql_query("SELECT rate,type FROM timesheet_rate WHERE user = '$data[user]'") or die(mysql_error());
		$da4 = mysql_fetch_row($q4);
		
		if ($da4[0] == "")
		{
			$rate = 17.0458;
		}
		else
		{
			if ($da4[1] == "F")
			{
				$rate = $da4[0];
			}
			elseif ($da4[1] == "T")
			{
				$q3 = mysql_query("SELECT designation FROM vericon.timesheet_designation WHERE user = '$data[user]'") or die(mysql_error());
				$desig = mysql_fetch_row($q3);
				
				$q3 = mysql_query("SELECT rate FROM vericon.timesheet_tiered WHERE designation = '$desig[0]' AND '$da3' BETWEEN `from` AND `to`") or die(mysql_error());
				$t_rate = mysql_fetch_row($q3);
				
				$rate = $t_rate[0];
			}
		}
		
		if ($da2[0] != "")
		{
			$n_sales_d = $da3 - $da2[2];
		}
		else
		{
			$n_sales_d = "-";
		}

		if (($da3 - $da2[2]) > 0)
		{
			$gross = (($rate * $da2[0]) + $da2[1]) * 1.09;
			$net = $da3 - $da2[2];
			$cps = $gross / $net;
		}
		else
		{
			$cps = (($rate * $da2[0]) + $da2[1]) * 1.09;
		}
		
		if ($da2[0] != 0)
		{
			$o_hours = number_format($da2[0],2);
			$o_bonus = number_format($da2[1],2);
			$cps_d = "\$" . number_format($cps,2);

		}
		else
		{
			$o_hours = "";
			$o_bonus = "";
			$cps_d = "-";
		}
		
		if ($da[0] == "")
		{
			$t_hours_d = "-";
			$t_bonus_d = "-";
			$t_sales_d = "-";
			$o_hours_d = "-";
			$o_bonus_d = "-";
		}
		else
		{
			$t_hours_d = number_format($da[0],2);
			$t_bonus_d = "\$" . number_format($da[1],2);
			$t_sales_d = $da3;
			$o_hours_d = "<input type='text' id='$data[user]_hours' value='$o_hours' onChange='Hours(\"$data[user]\")' style='height:15px; width:30px;'>";
			$o_bonus_d = "$<input type='text' id='$data[user]_bonus' value='$o_bonus' onChange='Bonus(\"$data[user]\")' style='height:15px; width:35px;'>";
		}
		
		echo "<tr>";
		echo "<td style='text-align:left;'>" . $data["user"] . "</td>";
		echo "<td style='text-align:left;'>" . $user[0] . " " . $user[1] . "</td>";
		echo "<td>" . $t_hours_d . "</td>";
		echo "<td>" . $t_bonus_d . "</td>";
		echo "<td>" . $t_sales_d . "</td>";
		echo "<td>" . $o_hours_d . "</td>";
		echo "<td>" . $o_bonus_d . "</td>";
		echo "<td>" . $n_sales_d . "</td>";
		echo "<td><span id='$data[user]_cps'>" . $cps_d . "</span></td>";
		echo "</tr>";
	}
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