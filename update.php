<?php
include "auth/iprestrict.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<?php
include "source/jquery.php";
?>
<script> //error dialog
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog({
		autoOpen: true,
		resizable: false,
		draggable: false,
		height:140,
		modal: true,
		buttons: {
			"OK": function() {
				window.location = "/self";
			}
		}
	});
});
</script>
</head>

<body>
<div id="main_wrapper">

<div id="innerpage_logo">
<a href="../" style="border-style:none;"><img src="../images/logo.png"  width="252" height="65" alt="logo" /></a>
</div>

<div id="menu">
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a>Down for Updates</a></li>
</ul>
</div>
</div>

<div id="dialog-confirm" title="Portal Online">
	<p>Self Portal is online again</p>
   	<p>Thank you for your patience</p>
</div>

<div id="text" class="demo">

</div>

</div>

<?php
include "source/footer.php";
?>

</body>
</html>