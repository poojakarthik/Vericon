<?php
if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/",$_GET["date"]))
{
	$date = date("Y-m-d");
}
else
{
	$date = $_GET["date"];
}
?>
<script>
$(function() {
	$( "#datepicker" ).datepicker( {
		showOn: "button",
		buttonImage: "../images/calendar.gif",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		altField: "#datepicker2",
		altFormat: "dd/mm/yy",
		changeMonth: true,
		changeYear: true,
		maxDate: "0d",
		onSelect: function(dateText, inst) {
			window.location = "?p=process&date=" + dateText;
		}});
});
</script>
<script> //Generate DSR
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var date = "<?php echo $date; ?>";
		
	$( "#dialog-form" ).dialog({
		autoOpen: false,
		width: 300,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Generate": function() {
				$.get("admin_process_submit.php", { date: date },
				function(data) {
					window.location.reload();
				});
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
		}
	});
});

function Generate_DSR()
{
	var date = "<?php echo $date; ?>",
		pending = $( "#pending" );
	
	if (pending.val() == 1)
	{
		$( ".dsr_check" ).html('<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>There is <b>' + pending.val() + '</b> sale still pending.<br><br>Are you sure you would like to generate the DSR?');
		$( "#dialog-form" ).dialog( "open" );
	}
	else if (pending.val() > 1)
	{
		$( ".dsr_check" ).html('<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>There are <b>' + pending.val() + '</b> sales still pending.<br><br>Are you sure you would like to generate the DSR?');
		$( "#dialog-form" ).dialog( "open" );
	}
	else
	{
		$.get("admin_process_submit.php", { date: date },
		function(data) {
			window.location.reload();
		});
	}
}
</script>

<div id="dialog-form" title="Generate DSR - Warning">
<p class="dsr_check"></p>
</div>

<table width="99%">
<tr>
<td align="left">
<img src="../images/sale_details_header2.png" width="125" height="25" style="margin-left:3px;" />
</td>
<td align="right">
<input type='text' size='9' id='datepicker2' readonly='readonly' style="height:20px;" value='<?php echo date("d/m/Y", strtotime($date)); ?>' /><input type='hidden' id='datepicker' value='<?php echo $date; ?>' />
</td>
</tr>
<tr>
<td colspan="2">
<img src="../images/line.png" width="740" height="9" />
</td>
</tr>
</table>

<center>
<div id="users-contain" class="ui-widget" style="width:80%;">
<table id="users" class="ui-widget ui-widget-content">
<thead>
<tr class="ui-widget-header ">
<th>Campaign</th>
<th style="text-align:center;">Total Sales</th>
<th style="text-align:center;">Reworks</th>
<th style="text-align:center;">Pending</th>
<th style="text-align:center;">Approved</th>
<th style="text-align:center;">Rejected</th>
</tr>
</thead>
<tbody id="display">
<?php
$q = mysql_query("SELECT campaign FROM campaigns ORDER BY campaign ASC") or die(mysql_error());

while ($campaign = mysql_fetch_row($q))
{
	$q1 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND campaign = '$campaign[0]' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
	$total_sales = mysql_num_rows($q1);
	
	$q2 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Rework' AND campaign = '$campaign[0]' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
	$rework = mysql_num_rows($q2);
	
	$q3e = mysql_query("SELECT id FROM qa_customers WHERE status = 'Approved' AND campaign = '$campaign[0]' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
	if (mysql_num_rows($q3e) != 0)
	{
		while ($ex = mysql_fetch_row($q3e))
		{
			$exclude .= " AND id != '$ex[0]' ";
		}
	}
	$q3 = mysql_query("SELECT COUNT(id) FROM sales_customers WHERE status = 'Approved' AND campaign = '$campaign[0]'" . $exclude . "AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
	$pending = mysql_fetch_row($q3);
	$total_pending += $pending[0];
	
	$q4 = mysql_query("SELECT COUNT(id) FROM qa_customers WHERE status = 'Approved' AND campaign = '$campaign[0]' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
	$approved = mysql_fetch_row($q4);
	$total_approved += $approved[0];
	
	$q5 = mysql_query("SELECT COUNT(id) FROM qa_customers WHERE status = 'Rejected' AND campaign = '$campaign[0]' AND DATE(sale_timestamp) = '$date'") or die(mysql_error());
	$rejected = mysql_fetch_row($q5);
	
	echo "<tr>";
	echo "<td>" . $campaign[0] . "</td>";
	echo "<td style='text-align:center;'>" . $total_sales . "</td>";
	echo "<td style='text-align:center;'>" . $rework . "</td>";
	echo "<td style='text-align:center;'>" . $pending[0] . "</td>";
	echo "<td style='text-align:center;'>" . $approved[0] . "</td>";
	echo "<td style='text-align:center;'>" . $rejected[0] . "</td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div>
</center>
<input type="hidden" id="pending" value="<?php echo $total_pending; ?>" />

<?php
$dir = "/home/dsr/" . date("Y/F", strtotime($date)) . "/DSR_" . date("d.m.Y", strtotime($date)) . "_Report.txt";
if (file_exists($dir))
{
?>
<table width="99%">
<tr>
<td>
<img src="../images/dsr_report_header.png" width="120" height="25" style="margin-left:3px;" />
</td>
</tr>
<tr>
<td>
<img src="../images/line.png" width="740" height="9" />
</td>
</tr>
</table>
<?php
	echo "<pre>";
	readfile("/home/dsr/" . date("Y/F", strtotime($date)) . "/DSR_" . date("d.m.Y", strtotime($date)) . "_Report.txt");
	echo "</pre>";
	echo "<a href='download_dsr.php?date=$date' style='color:inherit;'>Download - DSR_" . date("d.m.Y", strtotime($date)) . "_" . "_QA.csv</a>";
}
elseif ($total_approved == 0)
{
	echo "No Processed Sales to Generate DSR";
}
else
{
?>
	<input type="button" onclick="Generate_DSR()" value="Generate DSR" />
<?php
}
?>