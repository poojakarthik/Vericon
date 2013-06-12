<?php
mysql_connect('localhost','vericon','18450be');

$centre = $_GET["centre"];
$date = $_GET["date"];
$week = date("W", strtotime($date));
$year = date("Y", strtotime($date));
$date1 = date("Y-m-d", strtotime($year . "W" . $week . "1"));
$date2 = date("Y-m-d", strtotime($year . "W" . $week . "7"));
?>

<script> // import cancellations
$(function() {
    $('#file_upload').uploadify({
		'fileTypeDesc' : 'CSV',
        'fileTypeExts' : '*.csv',
		'multi'    : false,
		'progressData' : 'speed',
		'removeTimeout' : 0,
		'swf'      : 'upload/uploadify.swf',
        'uploader' : 'upload/uploadify2.php',
		'buttonText' : 'IMPORT',
		'buttonClass' : 'btn',
		'width'    : 102,
		'onUploadSuccess' : function(file, data, response) {
			var centre = $( "#centre" ),
				date = $( "#datepicker" );
				
			$.get("cancellations_process.php", { method: "import_cancellations", date: date.val() }, function (data) {
				if (data == "done")
				{
					$( "#display" ).hide('blind', '', 'slow', function() {
						$( "#display" ).load('cancellations_display.php?centre=' + centre.val() + '&date=' + date.val(), function() {
							$( "#display" ).show('blind', '', 'slow');
						});
					});
				}
				else
				{
					alert("Error!");
				}
			});
		}
    });
});
</script>
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
		minDate: "<?php echo "2012-03-01"; ?>",
		onSelect: function(dateText, inst) {
			var centre = "<?php echo $centre; ?>";
			
			$( "#display" ).hide('blind', '', 'slow', function() {
				$( "#display" ).load('cancellations_display.php?centre=' + centre + '&date=' + dateText, function() {
					$( "#display" ).show('blind', '', 'slow');
				});
			});
		}});
});
</script>

<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/sale_cancellations_header.png" width="195" height="25" /></td>
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
<input type='text' size='9' id='from' readonly='readonly' style="height:20px;" value="<?php echo date("d/m/Y", strtotime($date1)); ?>" /> to <input type='text' size='9' id='to' readonly='readonly' style="height:20px;" value="<?php echo date("d/m/Y", strtotime($date2)); ?>" /><input type='hidden' id='datepicker' value='<?php echo $date; ?>' />
</td>
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
<tr class="ui-widget-header ">
<th width="15%">Username</th>
<th width="65%">Full Name</th>
<th width="20%" style="text-align:center;">Cancellations</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT user FROM vericon.timesheet WHERE WEEK(date) = '$week' AND centre = '$centre' GROUP BY user ORDER BY user ASC") or die(mysql_error());

if ($centre == "Centre")
{
	echo "<tr><td colspan='3' style='text-align:center;'>Please Select a Centre From Above</td></tr>";
}
elseif (mysql_num_rows($q) == 0)
{
	echo "<tr><td colspan='3' style='text-align:center;'>No Records Found!</td></tr>";
}
else
{
	while ($data = mysql_fetch_row($q))
	{
		$q0 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$data[0]'") or die(mysql_error());
		$user = mysql_fetch_row($q0);
		
		$q1 = mysql_query("SELECT cancellations FROM vericon.timesheet_other WHERE user = '$data[0]' AND week = '$week'") or die(mysql_error());
		$c = mysql_fetch_row($q1);
		
		if ($c[0] == "") { $cancellations = "-"; } else { $cancellations = $c[0]; }
		
		echo "<tr>";
		echo "<td>" . $data[0] . "</td>";
		echo "<td>" . $user[0] . " " . $user[1] . "</td>";
		echo "<td style='text-align:center;'>" . $cancellations . "</td>";
		echo "</tr>";
	}
}
?>
</tbody>
</table>
</div></center><br>
<?php
if (mysql_num_rows($q) != 0)
{
?>
<style type="text/css">
.uploadify-button {

	background-image:url('../images/btn.png');
	background-color: transparent;
	border: none;
	text-transform:uppercase;
	padding: 0;
	font-weight: bold;
	font-family: Tahoma,Geneva,sans-serif;
	font-size: 11px;
	text-shadow:none;
}
.uploadify:hover .uploadify-button {
	background-color: transparent;
	background-image:url('../images/btn_hover.png');
}
</style>

<table width="100%">
<tr>
<td align="left" valign="top" style="padding-left:10px;"><input type="file" name="file_upload" id="file_upload" /></td>
<td align="right" valign="top" style="padding-right:10px;"><button onClick="Export_Cancellations()" class="btn">Export</button></td>
</tr>
</table>
<?php
}
?>