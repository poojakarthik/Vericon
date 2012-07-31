<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
div#users-contain table { margin: 1em 0; margin-bottom:0; border-collapse: collapse; width:100% }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
.ui-progressbar { position:relative; width: 100%; }
.ui-progressbar-value { position: absolute; overflow: hidden; }
.pblabel { position: absolute; display: block; width: 100%; text-align: center; line-height: 1.9em; font-weight: bold; }
.ui-progressbar-value .pblabel { position: relative; font-weight: bold; color:#EAF5F7; }
</style>
<link rel="stylesheet" type="text/css" href="upload/uploadify.css" />
<script type="text/javascript" src="upload/jquery.uploadify-3.1.min.js"></script>
<script>
function Upload_View()
{
	var refreshIntervalId = setInterval(function Check() {
		$.get("upload_submit.php?method=check", function (data) {
			if (data == 0)
			{
				clearInterval(refreshIntervalId);
				$.get("upload_submit.php?method=complete", function (data) {
					$( "#upload" ).load("upload_view.php", function() {
						$( "#dialog-form" ).dialog( "close" );
					});
					$( "#display2" ).load("upload_display2.php");
				});
			}
			else
			{
				$( "#upload" ).load("upload_view.php");
			}
		});
	},1000);
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	$( "#dialog-form" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:250,
		height:100,
		modal: true
	});
});

function Cancel_Upload()
{
	$( "#dialog-form" ).dialog( "open" );
	$.get("upload_submit.php?method=cancel");
}
</script>

<div id="dialog-form" title="Cancelling Upload">
<center><br><p><img src='../images/ajax-loader.gif'>&nbsp;&nbsp;&nbsp;&nbsp;Please wait. Cancelling Upload...</p></center>
</div>

<div id="display">
<script>
$( "#display" ).hide();
$( "#display" ).load("upload_display.php", function() {
	$( "#upload" ).load("upload_view.php", function() {
		$( "#display2" ).load("upload_display2.php", function() {
			$( "#display" ).show('blind', '', 'slow');
		});
	});
});
</script>
</div>

<?php
include "../source/footer.php";
?>