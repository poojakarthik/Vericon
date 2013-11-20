<?php
mysql_connect('localhost','vericon','18450be');

$date = $_GET["date"];
?>

<script>
$(function() {
	$( "#datepicker" ).datepicker( {
		showOn: "button",
		buttonImage: "../images/calendar.png",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		altField: "#datepicker2",
		altFormat: "dd/mm/yy",
		firstDay: 1,
		showOtherMonths: true,
		selectOtherMonths: true,
		changeMonth: true,
		changeYear: true,
		maxDate: "0d",
		minDate: "<?php echo "2012-03-01"; ?>",
		onSelect: function(dateText, inst) {
			$( "#display" ).hide('blind', '' , 'slow', function() {
				$( "#display" ).load('cancellations_display.php?date=' + dateText,
				function() {
					$( "#display" ).show('blind', '' , 'slow');
				});
			});
		}});
});
</script>

<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/sale_cancellations_header.png" width="195" height="25"></td>
<td align="right" style="padding-right:10px;"><input type='text' size='11' id='datepicker2' readonly='readonly' value='<?php echo date("d/m/Y", strtotime($date)); ?>' /><input type='hidden' id='datepicker' value='<?php echo $date; ?>' /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="740" height="9"></td>
</tr>
</table>

<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="98%" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th></th>
<th>Contract</th>
<th>Fraud</th>
<th>Rates</th>
<th>Telstra</th>
<th>Other</th>
<th>Total</th>
</tr>
</thead>
<tbody>
<?php
$c_contract = 0;
$c_fraud = 0;
$c_rates = 0;
$c_telstra = 0;
$c_other = 0;
$m_contract = 0;
$m_fraud = 0;
$m_rates = 0;
$m_telstra = 0;
$m_other = 0;
$o_contract = 0;
$o_fraud = 0;
$o_rates = 0;
$o_telstra = 0;
$o_other = 0;

$q = mysql_query("SELECT centre, cancellation_reason, COUNT(id) FROM vericon.welcome WHERE DATE(timestamp) = '$date' AND status = 'Cancel' GROUP BY centre, cancellation_reason") or die(mysql_error());
while ($data = mysql_fetch_row($q))
{
	$q1 = mysql_query("SELECT type FROM vericon.centres WHERE centre = '$data[0]'") or die(mysql_error());
	$da = mysql_fetch_row($q1);
	
	if ($da[0] == "Captive")
	{
		if ($data[1] == "Contract") { $c_contract = $data[2]; }
		elseif ($data[1] == "Fraud") { $c_fraud = $data[2]; }
		elseif ($data[1] == "Rates") { $c_rates = $data[2]; }
		elseif ($data[1] == "Telstra") { $c_telstra = $data[2]; }
		elseif ($data[1] == "Other") { $c_other = $data[2]; }
	}
	elseif ($da[0] == "Self")
	{
		if ($data[1] == "Contract") { $m_contract = $data[2]; }
		elseif ($data[1] == "Fraud") { $m_fraud = $data[2]; }
		elseif ($data[1] == "Rates") { $m_rates = $data[2]; }
		elseif ($data[1] == "Telstra") { $m_telstra = $data[2]; }
		elseif ($data[1] == "Other") { $m_other = $data[2]; }
	}
	elseif ($da[0] == "Outsourced")
	{
		if ($data[1] == "Contract") { $o_contract = $data[2]; }
		elseif ($data[1] == "Fraud") { $o_fraud = $data[2]; }
		elseif ($data[1] == "Rates") { $o_rates = $data[2]; }
		elseif ($data[1] == "Telstra") { $o_telstra = $data[2]; }
		elseif ($data[1] == "Other") { $o_other = $data[2]; }
	}
}

$c_total = $c_contract + $c_fraud + $c_rates + $c_telstra + $c_other;
echo "<tr>";
echo "<td style='text-align:left;'>Captive</td>";
echo "<td>" . $c_contract . "</td>";
echo "<td>" . $c_fraud . "</td>";
echo "<td>" . $c_rates . "</td>";
echo "<td>" . $c_telstra . "</td>";
echo "<td>" . $c_other . "</td>";
echo "<td><b>" . $c_total . "</b></td>";
echo "</tr>";

$m_total = $m_contract + $m_fraud + $m_rates + $m_telstra + $m_other;
echo "<tr>";
echo "<td style='text-align:left;'>Melbourne</td>";
echo "<td>" . $m_contract . "</td>";
echo "<td>" . $m_fraud . "</td>";
echo "<td>" . $m_rates . "</td>";
echo "<td>" . $m_telstra . "</td>";
echo "<td>" . $m_other . "</td>";
echo "<td><b>" . $m_total . "</b></td>";
echo "</tr>";

$o_total = $o_contract + $o_fraud + $o_rates + $o_telstra + $o_other;
echo "<tr>";
echo "<td style='text-align:left;'>Outsourced</td>";
echo "<td>" . $o_contract . "</td>";
echo "<td>" . $o_fraud . "</td>";
echo "<td>" . $o_rates . "</td>";
echo "<td>" . $o_telstra . "</td>";
echo "<td>" . $o_other . "</td>";
echo "<td><b>" . $o_total . "</b></td>";
echo "</tr>";

$t_contract = $c_contract + $m_contract + $o_contract;
$t_fraud = $c_fraud + $m_fraud + $o_fraud;
$t_rates = $c_rates + $m_rates + $o_rates;
$t_telstra = $c_telstra + $m_telstra + $o_telstra;
$t_other = $c_other + $m_other + $o_other;
$t_total = $t_contract + $t_fraud + $t_rates + $t_telstra + $t_other;
echo "<tr>";
echo "<td style='text-align:left;'><b>Total</b></td>";
echo "<td><b>" . $t_contract . "</b></td>";
echo "<td><b>" . $t_fraud . "</b></td>";
echo "<td><b>" . $t_rates . "</b></td>";
echo "<td><b>" . $t_telstra . "</b></td>";
echo "<td><b>" . $t_other . "</b></td>";
echo "<td><b>" . $t_total . "</b></td>";
echo "</tr>";
?>
</tbody>
</table>
</div></center>

<table width="100%">
<tr>
<td align="right" style="padding-right:10px;"><button onClick="Export()" class="btn">Export</button></td>
</tr>
</table>