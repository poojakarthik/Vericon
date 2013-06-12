<script> // import update
$(function() {
    $('#file_upload').uploadify({
		'fileTypeDesc' : 'CSV',
        'fileTypeExts' : '*.csv',
		'multi'    : false,
		'progressData' : 'speed',
		'swf'      : 'upload/uploadify.swf',
        'uploader' : 'upload/uploadify.php',
		'buttonText' : 'IMPORT',
		'buttonClass' : 'btn',
		'width'    : 102,
		'onUploadSuccess' : function(file, data, response) {
			$.get("update_process.php", { file: file }, function (data) {
				if (data == "done")
				{
					alert("Done!");
				}
				else
				{
					alert("Error!");
				}
			});
		}
    });
});
</script>

<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/update_dsr_header.png" width="120" height="25" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<table width="100%">
<tr>
<td align="center" width="50%"><button onClick="Business()" class="portal_btn">Business</button></td>
<td align="center" width="50%"><button onClick="Residential()" class="portal_btn">Residential</button></td>
</tr>
</table><br />

<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/customers_update_header.png" width="190" height="25" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<style type="text/css">
.uploadify-button {
	background-image:url('../images/btn.png');
	background-color: transparent;
	border: none;
	text-transform:uppercase;
	padding: 0;
	font-weight: bold;
	font-family: Tahoma,Geneva,sans-serif;
	font-size: 11px;
	text-shadow:none;
}
.uploadify:hover .uploadify-button {
	background-color: transparent;
	background-image:url('../images/btn_hover.png');
}
</style>

<table width="100%">
<tr>
<td align="left" style="padding-left:10px;"><input type="file" name="file_upload" id="file_upload" /></td>
</tr>
</table>