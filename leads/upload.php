<?php
include "../auth/iprestrict.php";
include "../source/header.php";

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
<link rel="stylesheet" type="text/css" href="upload/uploadify.css" />
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

<?php
include "../source/footer.php";
?>