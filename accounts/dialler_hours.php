<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<link rel="stylesheet" type="text/css" href="upload/uploadify.css" />
<script type="text/javascript" src="upload/jquery.uploadify-3.1.min.js"></script>
<style>
div#users-contain table { margin: 1em 0; margin-bottom:0; border-collapse: collapse; width:98% }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid #eee; padding: .1em 10px; text-align: left; }

.import
{
	background-image:url('../images/import_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
	float:right;
	margin-right:5px;
}

.import:hover
{
	background-image:url('../images/import_btn_hover.png');
	cursor:pointer;
}

.export
{
	background-image:url('../images/export_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
	margin-left:5px;
}

.export:hover
{
	background-image:url('../images/export_btn_hover.png');
	cursor:pointer;
}
</style>

<script>
function Centre()
{
	var centre = $( "#centre" ),
		date = $( "#datepicker" );

	$( "#display" ).load('dialler_hours_display.php?centre=' + centre.val() + '&date=' + date.val());
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
		firstDay: 1,
		showOtherMonths: true,
		selectOtherMonths: true,
		changeMonth: true,
		changeYear: true,
		maxDate: "0d",
		onSelect: function(dateText, inst) {
			var centre = $( "#centre" );
			
			$( "#display" ).load('dialler_hours_display.php?centre=' + centre.val() + '&date=' + dateText);
		}});
});
</script>
<script>
function Export_Hours()
{
	var centre = $( "#centre" ),
		date = $( "#datepicker" );
		
	window.location = "dialler_hours_process.php?method=export_hours&centre=" + centre.val() + '&date=' + date.val();
}
</script>
<script> // import hours
$(function() {
    $('#file_upload').uploadify({
		'fileTypeDesc' : 'CSV',
        'fileTypeExts' : '*.csv',
		'multi'    : false,
		'progressData' : 'speed',
		'buttonCursor' : 'hand',
		'buttonText' : 'BROWSE...',
        'swf'      : 'upload/uploadify.swf',
        'uploader' : 'upload/uploadify.php',
		'onUploadSuccess' : function(file, data, response) {
			var centre = $( "#centre" ),
				date = $( "#datepicker" );
				
			$.get("dialler_hours_process.php", { method: "import_hours", date: date.val() }, function (data) {
				if (data == "done")
				{
					$( "#dialog-confirm" ).dialog( "close" );
					$( "#display" ).load('upload_hours.php?centre=' + centre.val() + '&date=' + date.val());
				}
				else
				{
					alert("Error!");
				}
			});
		}
    });
});

$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		height:210,
		width: 300,
		modal: true,
		buttons: {
			"Cancel": function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

function Import_Hours()
{
	$( "#dialog-confirm" ).dialog( "open" );
}
</script>

<div id="dialog-confirm" title="Import Dialler Hours">
<input type="file" name="file_upload" id="file_upload" />
</div>

<table width="100%">
<tr>
<td align="left"><img src="../images/dialler_hours_header.png" width="140" height="25" style="margin-left:5px;" /></td>
<td align="right"><select id="centre" style="width:75px;" onchange="Centre()">
<option>Centre</option>
<?php
$q = mysql_query("SELECT centre FROM centres WHERE type = 'Self' AND status = 'Active' ORDER BY centre ASC") or die(mysql_error());
while ($centres = mysql_fetch_row($q))
{
	echo "<option>" . $centres[0] . "</option>";
}
?>
</select>
<input type='text' size='9' id='datepicker2' readonly='readonly' style="height:20px;" value='<?php echo date("d/m/Y"); ?>' /><input type='hidden' id='datepicker' value='<?php echo date("Y-m-d"); ?>' />
</td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<div id="display">
<script>
var centre = $( "#centre" ),
	date = $( "#datepicker" );

$( "#display" ).load('dialler_hours_display.php?centre=' + centre.val() + '&date=' + date.val());
</script>
</div>

<?php
include "../source/footer.php";
?>