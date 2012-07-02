<?php
mysql_connect('localhost','vericon','18450be');

$date = $_GET["date"];

$q0 = mysql_query("SELECT * FROM vericon.centres WHERE status = 'Active'") or die(mysql_error());
$num = mysql_num_rows($q0);

$t1 = ceil($num / 2);
$t2 = $num - $t1;
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
				$( "#display" ).load('sales_display.php?date=' + dateText,
				function() {
					$( "#display" ).show('blind', '' , 'slow');
				});
			});
			$( "#store_date" ).val(dateText);
		}});
});
</script>

<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/fresh_sales_header.png" width="120" height="25"></td>
<td align="right" style="padding-right:10px;"><input type='text' size='11' id='datepicker2' readonly='readonly' value='<?php echo date("d/m/Y", strtotime($date)); ?>' /><input type='hidden' id='datepicker' value='<?php echo $date; ?>' /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<table width="100%">
<tr>
<td width="50%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="98%" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th style="text-align:center;" rowspan="2">Centre</th>
<th style="text-align:center;" colspan="2">Pending</th>
<th style="text-align:center;" colspan="2">Approved</th>
<th style="text-align:center;" colspan="2">Rejected</th>
<th style="text-align:center;" rowspan="2"></th>
</tr>
<tr class="ui-widget-header ">
<th style="text-align:center;">B</th>
<th style="text-align:center;">R</th>
<th style="text-align:center;">B</th>
<th style="text-align:center;">R</th>
<th style="text-align:center;">B</th>
<th style="text-align:center;">R</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT centre FROM vericon.centres WHERE status = 'Active' ORDER BY centre ASC LIMIT 0,$t1") or die(mysql_error());
while ($centre = mysql_fetch_row($q))
{
	$exclude_b = "";
	$exclude_r = "";
	
	$q1e = mysql_query("SELECT id FROM vericon.qa_customers WHERE centre = '$centre[0]' AND type = 'Business' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
	if (mysql_num_rows($q1e) != 0)
	{
		while ($ex = mysql_fetch_row($q1e))
		{
			$exclude_b .= " AND id != '$ex[0]' ";
		}
	}
	$q1 = mysql_query("SELECT id FROM vericon.sales_customers WHERE status = 'Approved' AND centre = '$centre[0]' AND type = 'Business'" . $exclude_b . "AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
	$pending_b = mysql_num_rows($q1);
	
	$q2e = mysql_query("SELECT id FROM vericon.qa_customers WHERE centre = '$centre[0]' AND type = 'Residential' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
	if (mysql_num_rows($q2e) != 0)
	{
		while ($ex = mysql_fetch_row($q2e))
		{
			$exclude_r .= " AND id != '$ex[0]' ";
		}
	}
	$q2 = mysql_query("SELECT id FROM vericon.sales_customers WHERE status = 'Approved' AND centre = '$centre[0]' AND type = 'Residential'" . $exclude_r . "AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
	$pending_r = mysql_num_rows($q2);
	
	$q3 = mysql_query("SELECT id FROM vericon.qa_customers WHERE status = 'Approved' AND centre = '$centre[0]' AND type = 'Business' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
	$approved_b = mysql_num_rows($q3);
	
	$q4 = mysql_query("SELECT id FROM vericon.qa_customers WHERE status = 'Approved' AND centre = '$centre[0]' AND type = 'Residential' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
	$approved_r = mysql_num_rows($q4);
	
	$q5 = mysql_query("SELECT id FROM vericon.qa_customers WHERE status = 'Rejected' AND centre = '$centre[0]' AND type = 'Business' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
	$rejected_b = mysql_num_rows($q5);
	
	$q6 = mysql_query("SELECT id FROM vericon.qa_customers WHERE status = 'Rejected' AND centre = '$centre[0]' AND type = 'Residential' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
	$rejected_r = mysql_num_rows($q6);
	
	echo "<tr>";
	echo "<td style='text-align:center'>" . $centre[0] . "</td>";
	echo "<td style='text-align:center'>" . $pending_b . "</td>";
	echo "<td style='text-align:center'>" . $pending_r . "</td>";
	echo "<td style='text-align:center'>" . $approved_b . "</td>";
	echo "<td style='text-align:center'>" . $approved_r . "</td>";
	echo "<td style='text-align:center'>" . $rejected_b . "</td>";
	echo "<td style='text-align:center'>" . $rejected_r . "</td>";
	echo "<td style='text-align:center'><button onclick='View_Switch(\"$centre[0]\")' class='icon_view' title='View'></button></td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div></center>
</td>
<td width="50%" valign="top">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="98%" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th style="text-align:center;" rowspan="2">Centre</th>
<th style="text-align:center;" colspan="2">Pending</th>
<th style="text-align:center;" colspan="2">Approved</th>
<th style="text-align:center;" colspan="2">Rejected</th>
<th style="text-align:center;" rowspan="2"></th>
</tr>
<tr class="ui-widget-header ">
<th style="text-align:center;">B</th>
<th style="text-align:center;">R</th>
<th style="text-align:center;">B</th>
<th style="text-align:center;">R</th>
<th style="text-align:center;">B</th>
<th style="text-align:center;">R</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT centre FROM vericon.centres WHERE status = 'Active' ORDER BY centre ASC LIMIT $t1,$t2") or die(mysql_error());
while ($centre = mysql_fetch_row($q))
{
	$exclude_b = "";
	$exclude_r = "";
	
	$q1e = mysql_query("SELECT id FROM vericon.qa_customers WHERE centre = '$centre[0]' AND type = 'Business' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
	if (mysql_num_rows($q1e) != 0)
	{
		while ($ex = mysql_fetch_row($q1e))
		{
			$exclude_b .= " AND id != '$ex[0]' ";
		}
	}
	$q1 = mysql_query("SELECT id FROM vericon.sales_customers WHERE status = 'Approved' AND centre = '$centre[0]' AND type = 'Business'" . $exclude_b . "AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
	$pending_b = mysql_num_rows($q1);
	
	$q2e = mysql_query("SELECT id FROM vericon.qa_customers WHERE centre = '$centre[0]' AND type = 'Residential' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
	if (mysql_num_rows($q2e) != 0)
	{
		while ($ex = mysql_fetch_row($q2e))
		{
			$exclude_r .= " AND id != '$ex[0]' ";
		}
	}
	$q2 = mysql_query("SELECT id FROM vericon.sales_customers WHERE status = 'Approved' AND centre = '$centre[0]' AND type = 'Residential'" . $exclude_r . "AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
	$pending_r = mysql_num_rows($q2);
	
	$q3 = mysql_query("SELECT id FROM vericon.qa_customers WHERE status = 'Approved' AND centre = '$centre[0]' AND type = 'Business' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
	$approved_b = mysql_num_rows($q3);
	
	$q4 = mysql_query("SELECT id FROM vericon.qa_customers WHERE status = 'Approved' AND centre = '$centre[0]' AND type = 'Residential' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
	$approved_r = mysql_num_rows($q4);
	
	$q5 = mysql_query("SELECT id FROM vericon.qa_customers WHERE status = 'Rejected' AND centre = '$centre[0]' AND type = 'Business' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
	$rejected_b = mysql_num_rows($q5);
	
	$q6 = mysql_query("SELECT id FROM vericon.qa_customers WHERE status = 'Rejected' AND centre = '$centre[0]' AND type = 'Residential' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
	$rejected_r = mysql_num_rows($q6);
	
	echo "<tr>";
	echo "<td style='text-align:center'>" . $centre[0] . "</td>";
	echo "<td style='text-align:center'>" . $pending_b . "</td>";
	echo "<td style='text-align:center'>" . $pending_r . "</td>";
	echo "<td style='text-align:center'>" . $approved_b . "</td>";
	echo "<td style='text-align:center'>" . $approved_r . "</td>";
	echo "<td style='text-align:center'>" . $rejected_b . "</td>";
	echo "<td style='text-align:center'>" . $rejected_r . "</td>";
	echo "<td style='text-align:center'><button onclick='View_Switch(\"$centre[0]\")' class='icon_view' title='View'></button></td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div></center>
</td>
</tr>
</table>

<div id="display2">
</div>