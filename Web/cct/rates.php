<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<script>
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:450,
		height:345,
		modal: true,
		buttons: {
			"OK": function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
</script>
<style>
.ui-autocomplete { max-height: 100px; overflow-y: auto; overflow-x: hidden; padding-right: 20px; }
</style>
<script> //autocomplete
$(function() {
	var availablePlans = new Array();
	
	availablePlans = [
	<?php
	$q3 = mysql_query("SELECT plan_code FROM plan_rates") or die(mysql_error());
	while ($plan_name = mysql_fetch_row($q3))
	{
		echo '"' . $plan_name[0] . '",';
	}
	?>
	];
	$( "#plan" ).autocomplete({
		source: availablePlans,
		minLength: 2
	});
});
</script>
<script> //Display plan rates
function Display()
{
	var plan = $( "#plan" ).val();
	
	$.get("plan_rates.php", { plan: plan }, function(data) {
	$('.rates').html(data)});
	$( "#dialog-confirm" ).dialog( "open" );
}
</script>
<script> //search button
$(function() {
	$( "input:submit", ".demo" ).button();
});
</script>

<div id="dialog-confirm" title="Plan Rates">
	<p class="rates"></p>
</div>

<p><img src="../images/plan_rates_header.png" width="115" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p><br />

<form onsubmit="event.preventDefault()">
<label>T-Bill Plan Code: </label>
<input type="text" id="plan" size="25" />
<input type="submit" onclick="Display()" id="search" value="Search" style="height:30px; padding-bottom:5px; padding: 0em 1em 3px;" />
</form><br />

<p><img src="../images/international_rates_header.png" width="210" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p><br />

<?php
include "../source/international.php";
include "../source/footer.php";
?>