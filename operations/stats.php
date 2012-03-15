<?php
include "../auth/iprestrict.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Operations :: Stats</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<link rel="stylesheet" href="../jquery/development-bundle/demos/demos.css">
<link rel="stylesheet" href="../jquery/development-bundle/themes/custom-theme/jquery.ui.all.css">
<script src="../jquery/development-bundle/jquery-1.6.2.js"></script>
<script src="../jquery/development-bundle/external/jquery.bgiframe-2.1.2.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.core.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.mouse.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.button.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.dialog.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.position.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.datepicker.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.accordion.js"></script>
<style>
div#users-contain table { margin: 1em 0; margin-bottom:0; border-collapse: collapse; }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid #eee; padding: .1em 10px; text-align: left; }
</style>
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
			var user = "<?php echo $ac["user"] ?>";
			
			$( "#display" ).load('display.php?user=' + user + '&date=' + dateText);
		}});
});
</script>
</head>

<body>
<div style="display:none;">

</div>
<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/operations_menu.php";
?>

<div id="text">

<table width="99%">
<tr>
<td align="right">
<input type='text' size='9' id='datepicker2' readonly='readonly' value='<?php echo date("d/m/Y"); ?>' /><input type='hidden' id='datepicker' value='<?php echo date("Y-m-d"); ?>' />
</td>
</tr>
</table>
<div id="display">
<script>
var user = "<?php echo $ac["user"] ?>",
	date = $( "#datepicker" );

$( "#display" ).load('display.php?user=' + user + '&date=' + date.val());
</script>
</div>

</div>

</div> 
<?php
include "../source/footer.php";
?>
</body>
</html>