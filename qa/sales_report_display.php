<?php
mysql_connect('localhost','vericon','18450be');

$centre = $_GET["centre"];
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
			var centre = $( "#centre" );
			$( "#display" ).hide('blind', '' , 'slow', function() {
				$( "#display" ).load('sales_report_display.php?centre=' + centre.val() + '&date=' + dateText,
				function() {
					$( "#display" ).show('blind', '' , 'slow');
				});
			});
		}});
});
</script>
<script>
function Centre()
{
	var date = $( "#datepicker" ),
		centre = $( "#centre" );
	$( "#display" ).hide('blind', '' , 'slow', function() {
		$( "#display" ).load('sales_report_display.php?centre=' + centre.val() + '&date=' + date.val(),
		function() {
			$( "#display" ).show('blind', '' , 'slow');
		});
	});
}
</script>

<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/sales_report_header.png" width="135" height="25"></td>
<td align="right" style="padding-right:10px;"><select id="centre" onchange="Centre()" style="min-width: 115px; display:none;">
<option>All</option>
<option>Captive</option>
<option>Melbourne</option>
<option>Outsourced</option>
</select> <input type='text' size='11' id='datepicker2' readonly='readonly' value='<?php echo date("d/m/Y", strtotime($date)); ?>' /><input type='hidden' id='datepicker' value='<?php echo $date; ?>' /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<script>
$( "#centre" ).val("<?php echo $centre; ?>");
</script>

<?php
$q0 = mysql_query("SELECT * FROM vericon.groups") or die(mysql_error());
while ($group = mysql_fetch_assoc($q0))
{
?>
<center><div id="users-contain" class="ui-widget" style="width:98%">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th colspan="13" style="text-align:center;"><?php echo $group["name"]; ?></th>
</tr>
<tr class="ui-widget-header ">
<th rowspan="2" width="20%">Campaign</th>
<th colspan="3" width="20%" style="text-align:center;">Total Sales</th>
<th colspan="3" width="20%" style="text-align:center;">Approved</th>
<th colspan="3" width="20%" style="text-align:center;">Rejected</th>
<th colspan="3" width="20%" style="text-align:center;">Pending</th>
</tr>
<tr class="ui-widget-header ">
<th style="text-align:center;">B</th>
<th style="text-align:center;">R</th>
<th style="text-align:center;">T</th>
<th style="text-align:center;">B</th>
<th style="text-align:center;">R</th>
<th style="text-align:center;">T</th>
<th style="text-align:center;">B</th>
<th style="text-align:center;">R</th>
<th style="text-align:center;">T</th>
<th style="text-align:center;">B</th>
<th style="text-align:center;">R</th>
<th style="text-align:center;">T</th>
</tr>
</thead>
<tbody>
<?php
if ($centre == "All")
{
	$q = mysql_query("SELECT campaign FROM vericon.campaigns WHERE `group` = '$group[id]' ORDER BY campaign ASC") or die(mysql_error());
	while ($campaign = mysql_fetch_row($q))
	{
		$ts_business = 0;
		$ts_residential = 0;
		$a_business = 0;
		$a_residential = 0;
		$r_business = 0;
		$r_residential = 0;
		
		$q1 = mysql_query("SELECT type,COUNT(id) FROM vericon.sales_customers WHERE campaign = '$campaign[0]' AND status = 'Approved' AND DATE(approved_timestamp) = '$date' GROUP BY type") or die(mysql_error());
		while ($data = mysql_fetch_row($q1))
		{
			if ($data[0] == "Business") { $ts_business = $data[1]; } elseif ($data[0] == "Residential") { $ts_residential = $data[1]; }
		}
		$ts_total = $ts_business + $ts_residential;
		
		$q1 = mysql_query("SELECT status,type,COUNT(id) FROM vericon.qa_customers WHERE campaign = '$campaign[0]' AND DATE(sale_timestamp) = '$date' GROUP BY status,type") or die(mysql_error());
		while ($data = mysql_fetch_row($q1))
		{
			if ($data[0] == "Approved") {
				if($data[1] == "Business") { $a_business = $data[2]; } elseif($data[1] == "Residential") { $a_residential = $data[2]; }
			} elseif ($data[0] == "Rejected") {
				if($data[1] == "Business") { $r_business = $data[2]; } elseif($data[1] == "Residential") { $r_residential = $data[2]; }
			}
		}
		$a_total = $a_business + $a_residential;
		$r_total = $r_business + $r_residential;
		
		$p_business = $ts_business - ($a_business + $r_business);
		$p_residential = $ts_residential - ($a_residential + $r_residential);
		$p_total = $p_business + $p_residential;
		
		echo "<tr>";
		echo "<td>" . $campaign[0] . "</td>";
		echo "<td style='text-align:center;'>" . $ts_business . "</td>";
		echo "<td style='text-align:center;'>" . $ts_residential . "</td>";
		echo "<td style='text-align:center;'><b>" . $ts_total . "</b></td>";
		echo "<td style='text-align:center;'>" . $a_business . "</td>";
		echo "<td style='text-align:center;'>" . $a_residential . "</td>";
		echo "<td style='text-align:center;'><b>" . $a_total . "</b></td>";
		echo "<td style='text-align:center;'>" . $r_business . "</td>";
		echo "<td style='text-align:center;'>" . $r_residential . "</td>";
		echo "<td style='text-align:center;'><b>" . $r_total . "</b></td>";
		echo "<td style='text-align:center;'>" . $p_business . "</td>";
		echo "<td style='text-align:center;'>" . $p_residential . "</td>";
		echo "<td style='text-align:center;'><b>" . $p_total . "</b></td>";
		echo "</tr>";
	}
}
else
{
	
}
?>
</tbody>
</table>
</div>
</center>
<?php
}
?>
<table width="100%">
<tr>
<td style="padding-left:5px;"><img src="../images/dsr_download_header.png" width="155" height="25" /></td>
</tr>
<tr>
<td><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<div id="display2" style="padding-left:10px;">
<?php
$date_path = date("Y/F/d.m.Y", strtotime($date));
$dir = "/var/dsr/" . $date_path . "/";
$dh = opendir($dir);
$count = 0;

while ($file = readdir($dh))
{
	if ($file != "." && $file != ".."  && $file != "Update")
	{
		$count++;
		if (filetype($dir . $file) == "dir")
		{
			echo "<b>" . $file . "</b><br>";
			
			$dh2 = opendir($dir . $file . "/");
			while ($file2 = readdir($dh2))
			{
				if ($file2 != "." && $file2 != "..")
				{
					echo "-- <a onClick='Export(\"$file\",\"$file2\")' style='text-decoration:underline; cursor: pointer;'>" . $file2 . "</a><br>";
				}
			}
			closedir($dh2);
		}
	}
}

closedir($dh);

if ($count < 1)
{
	if ($date != date("Y-m-d"))
	{
		echo "No DSR Found";
	}
	elseif (!file_exists("/var/vtmp/dsr_loading.txt"))
	{
		echo "<button onclick='Generate()' class='btn'>Generate</button>";
	}
	else
	{
		echo "The DSR is still being generated. Please check back soon.";
	}
}
?>
</div>