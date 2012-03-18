<?php
include "../auth/iprestrict.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Admin :: Logs</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<?php
include "../source/jquery.php";
?>
<style>
div#log table { margin: 1em 0; border-collapse: collapse; }
div#log table td, div#log table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
</style>
<script>
$(function() {
	var dates = $( "#from, #to" ).datepicker({
		numberOfMonths: 1,
		dateFormat: "dd/mm/yy",
		maxDate: "0",
		onSelect: function( selectedDate ) {
			var option = this.id == "from" ? "minDate" : "maxDate",
				instance = $( this ).data( "datepicker" ),
				date = $.datepicker.parseDate(
					instance.settings.dateFormat ||
					$.datepicker._defaults.dateFormat,
					selectedDate, instance.settings );
			dates.not( this ).datepicker( "option", option, date );
		}
	});
});
</script>
<script> //search button
$(function() {
	$( "input:button", ".demo" ).button();
});
</script>
<script>
function Search()
{
	var q = "<?php echo $_GET["q"]; ?>",
		start = $( "#from" ),
		end = $( "#to" );
		
		$( "#log" ).load('logs_output.php?q=' + q + '&start=' + start.val() + '&end=' + end.val());
}
</script>
</head>

<body>
<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/admin_menu.php";
?>

<div id="text" class="demo">

<?php
if ($_GET["q"] == "unauthorised" || $_GET["q"] == "sales" || $_GET["q"] == "login" || $_GET["q"] == "al")
{
?>
	<p><img src="<?php echo "../images/logs_" . $_GET[q] . "_header.png"; ?>" /></p>
	<p><img src="../images/line.png" width="740" height="9" /></p><br />
    <label for="from">From</label>
    <input type="text" id="from" name="from" size="9" readonly="readonly" style="padding-left:3px;" value="<?php echo date("d/m/Y"); ?>" />
    <label for="to">to</label>
    <input type="text" id="to" name="to" size="9" readonly="readonly" style="padding-left:3px;" value="<?php echo date("d/m/Y"); ?>" />
    <input type="button" value="Search" onclick="Search()" style="height:30px; padding-bottom:5px; padding: 0em 1em 3px;" /><br />
    
    <div id="log" style="overflow:auto; width:100%; height:500px;">
    </div>

	<script>
	var q = "<?php echo $_GET["q"]; ?>";
	$( "#log" ).load('logs_output.php?q=' + q);
	</script>
<?php
}
else
{
	echo "Error!";
}
?>

</div>

</div> 
<?php
include "../source/footer.php";
?>
</body>
</html>