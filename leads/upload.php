<?php
include "../auth/iprestrict.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Leads :: Upload Leads</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<link rel="stylesheet" type="text/css" href="upload/uploadify.css" />
<?php
include "../source/jquery.php";
if ($_POST["m"] == "done")
{
?>
<script>
$.get("upload_view.php?method=complete", function (data) {
	window.location = "upload.php";
});
</script>
<?
}
?>
<script type="text/javascript" src="upload/jquery.uploadify-3.1.min.js"></script>
<script>
$(function() {
    $('#file_upload').uploadify({
		'checkExisting' : 'upload/check-exists.php',
		'fileSizeLimit' : '20MB',
		'fileTypeDesc' : 'CSV',
        'fileTypeExts' : '*.csv',
		'multi'    : false,
		'progressData' : 'speed',
		'removeCompleted' : false,
        'swf'      : 'upload/uploadify.swf',
        'uploader' : 'upload/uploadify.php',
        'buttonText' : 'BROWSE...',
		'uploadLimit' : 1,
		'onUploadSuccess' : function(file, data, response) {
			var refreshIntervalId = setInterval(function Check() {
				$.get("upload_view.php?method=check", function (data) {
					if (data == 0)
					{
						clearInterval(refreshIntervalId);
						$( "#complete" ).submit();
					}
					else
					{
						$( "#upload" ).load("upload_view.php?method=view");
					}
				});
			},1000);
			
			$.get("upload_view.php?method=begin", function (data) {
				if (data == 1)
				{
					refreshIntervalId;
				}
			});
		}
    });
});
</script>
</head>

<body>
<div style="display:none;">
<img src="../images/done_btn_hover.png" />
</div>

<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/leads_menu.php";
?>

<div id="text" style="margin-top:0px;">

<form id="complete" action="upload.php" method="post">
<input type="hidden" name="m" value="done" />
</form>

<p><img src="../images/upload_leads_header.png" width="135" height="25" style="margin-left:3px;" /></p>
<p style="margin-bottom:5px;"><img src="../images/line.png" width="740" height="9" /></p>

<div id="upload" style="width:98%; height:150px;">
<br /><input type="file" name="file_upload" id="file_upload" />
</div>

<p><img src="../images/last_upload_header.png" width="125" height="25" style="margin-left:3px;" /></p>
<p style="margin-bottom:5px;"><img src="../images/line.png" width="740" height="9" /></p>

<div id="display">
<script>
$( "#display" ).load("upload_view.php?method=last");
</script>
</div>

</div>

</div> 
<?php
include "../source/footer.php";
?>
</body>
</html>