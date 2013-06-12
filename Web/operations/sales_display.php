<?php
mysql_connect('localhost','vericon','18450be');

$centres = explode(",", $_GET["centres"]);
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
			var centres = "<?php echo $_GET["centres"]; ?>";
			
			$( "#date_store" ).val(dateText);
			$( "#display2" ).hide('blind', '' , 'slow', function() {
				$( "#display_loading2" ).show();
				$( "#display2" ).load('sales_display2.php?centres=' + centres + '&date=' + dateText,
				function() {
					$( "#display_loading2" ).hide();
					$( "#display2" ).show('blind', '' , 'slow');
				});
			});
		}});
});
</script>

<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/vericon_search_header.png" width="160" height="25" /></td>
<td align="right" style="padding-right:10px;"><button onClick="Search()" class="btn2">Search</button></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<div id="results" style="min-height:75px;">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:98%; margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th width="12%">ID</th>
<th width="10%">Status</th>
<th width="12%">Centre</th>
<th width="26%">Agent</th>
<th width="20%">Campaign</th>
<th width="10%">Type</th>
<th style='text-align:center;' width="10%"></th>
</tr>
</thead>
<tbody>
<tr>
<td colspan='8' style='text-align:center;'>Click the search button above to search for sales</td>
</tr>
</tbody>
</table>
</div></center>
</div>
<br />
<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/centre_sales_header.png" width="130" height="25"></td>
<td align="right" style="padding-right:10px;"><input type='text' size='9' id='datepicker2' readonly='readonly' style="height:20px;" value='<?php echo date("d/m/Y", strtotime($date)); ?>' /><input type='hidden' id='datepicker' value='<?php echo $date; ?>' /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<div id="display_loading2">
<br />
<center><img src="../images/ajax-loader.gif" /><br /><br />
<p>Loading Sales. Please Wait...</p></center>
</div>

<div id="display2">
</div>

<div id="display3">
</div>