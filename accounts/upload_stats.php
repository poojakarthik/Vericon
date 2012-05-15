<?php
include "../auth/iprestrict.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Accounts :: Upload Stats</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<link rel="stylesheet" type="text/css" href="upload/uploadify.css" />
<?php
include "../source/jquery.php";
?>
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
<script type="text/javascript" src="upload/jquery.uploadify-3.1.min.js"></script>
<script>
function Centre()
{
	var centre = $( "#centre" ),
		date = $( "#datepicker" );

	$( "#display" ).load('upload_display.php?centre=' + centre.val() + '&date=' + date.val());
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
		maxDate: "0d",
		onSelect: function(dateText, inst) {
			var centre = $( "#centre" );
			
			$( "#display" ).load('upload_display.php?centre=' + centre.val() + '&date=' + dateText);
		}});
});
</script>
<script>
function Export_Hours()
{
	var centre = $( "#centre" ),
		date = $( "#datepicker" );
		
	window.open("upload_process.php?method=export_hours&centre=" + centre.val() + '&date=' + date.val()); 
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
				
			$.get("upload_process.php", { method: "import_hours", date: date.val() }, function (data) {
				if (data == "done")
				{
					$( "#dialog-confirm" ).dialog( "close" );
					$( "#display" ).load('upload_display.php?centre=' + centre.val() + '&date=' + date.val());
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
<script>
function Export_Cancellations()
{
	var centre = $( "#centre" ),
		date = $( "#datepicker" );
		
	window.open("upload_process.php?method=export_cancellations&centre=" + centre.val() + '&date=' + date.val()); 
}
</script>
<script> // import cancellations
$(function() {
    $('#file_upload2').uploadify({
		'fileTypeDesc' : 'CSV',
        'fileTypeExts' : '*.csv',
		'multi'    : false,
		'progressData' : 'speed',
		'buttonCursor' : 'hand',
		'buttonText' : 'BROWSE...',
        'swf'      : 'upload/uploadify.swf',
        'uploader' : 'upload/uploadify2.php',
		'onUploadSuccess' : function(file, data, response) {
			var centre = $( "#centre" ),
				date = $( "#datepicker" );
				
			$.get("upload_process.php", { method: "import_cancellations", date: date.val() }, function (data) {
				if (data == "done")
				{
					$( "#dialog-confirm2" ).dialog( "close" );
					$( "#display" ).load('upload_display.php?centre=' + centre.val() + '&date=' + date.val());
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
	$( "#dialog:ui-dialog2" ).dialog( "destroy" );

	$( "#dialog-confirm2" ).dialog({
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

function Import_Cancellations()
{
	$( "#dialog-confirm2" ).dialog( "open" );
}
</script>
</head>

<body>
<div style="display:none;">
<img src="../images/export_btn_hover.png" /> <img src="../images/import_btn_hover.png" />
</div>
<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/accounts_menu.php";
?>

<div id="text">

<div id="dialog-confirm" title="Import Dialler Hours">
<input type="file" name="file_upload" id="file_upload" />
</div>

<div id="dialog-confirm2" title="Import Sale Cancellations">
<input type="file" name="file_upload2" id="file_upload2" />
</div>

<table width="99%">
<tr>
<td align="right"><select id="centre" style="margin:0px; padding:0px; height:20px; width:75px;" onchange="Centre()">
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
</table>

<div id="display">
<script>
var centre = $( "#centre" ),
	date = $( "#datepicker" );

$( "#display" ).load('upload_display.php?centre=' + centre.val() + '&date=' + date.val());
</script>
</div>

</div>

</div> 
<?php
include "../source/footer.php";
?>
</body>
</html>