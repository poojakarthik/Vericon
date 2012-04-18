<style>
.new_roster
{
	background-image:url('../images/new_roster_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
}

.new_roster:hover
{
	background-image:url('../images/new_roster_btn_hover.png');
	cursor:pointer;
}

.roster_done
{
	background-image:url('../images/done_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
}

.roster_done:hover
{
	background-image:url('../images/done_btn_hover.png');
	cursor:pointer;
}
</style>
<script>
function Roster_View()
{
	var date = $( "#datepicker" ),
		centre = "<?php echo $ac["centre"] ?>";
	$( "#roster_view" ).load('admin_roster_get.php?centre=' + centre + '&date=' + date.val());
}
</script>
<script>
function Roster_Edit()
{
	var centre = "<?php echo $ac["centre"] ?>";
	$( "#roster_edit" ).load('admin_roster_edit.php?method=display&centre=' + centre);
}
</script>
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
		firstDay: 1,
		minDate: new Date(2012,1,27),
		maxDate: "W",
		onSelect: function(){
			Roster_View();
		}});
});
</script>
<script>
function Edit(agent,centre)
{
	$( "#roster_agent" ).load('admin_roster_edit.php?method=edit&agent=' + agent + "&centre=" + centre);
	$( "#dialog-form" ).dialog( "open" );
}
</script>
<script>
function New_Roster()
{
	window.location = "admin.php?p=roster&m=e";
}
</script>
<div style="display:none;">
<img src="../images/new_roster_btn_hover.png"><img src="../images/done_btn_hover.png">
</div>

<div id="dialog-form" title="Enter Availability">
<div id="roster_agent">
<script>
$( "#roster_agent" ).load('admin_roster_edit.php?method=edit');
</script>
</div>
</div>

<p><img src="../images/centre_roster_header.png" width="145" height="25" style="margin-left:3px;" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p>
<?php
if ($_GET["m"] == "d") {
?>
Search by Date: <input type="text" size="11" id="datepicker2" readonly="readonly" value="<?php echo date("d/m/Y"); ?>" /><input type="hidden" id="datepicker" value="<?php echo date("Y-m-d"); ?>" /> <input type="button" onClick="New_Roster()" class="new_roster" style="float:right;">
<div id="roster_view">
<script>
	var date = $( "#datepicker" ),
		centre = "<?php echo $ac["centre"] ?>";

	$( "#roster_view" ).load('admin_roster_get.php?centre=' + centre + '&date=' + date.val());
</script>
</div>
<?php
} elseif ($_GET["m"] == "e") {
?>
<div id="roster_edit">
<script>
	var centre = "<?php echo $ac["centre"] ?>";
	$( "#roster_edit" ).load('admin_roster_edit.php?method=display&centre=' + centre);
</script>
</div>
<?php
}
?>