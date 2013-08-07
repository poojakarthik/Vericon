<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

function CheckAccess()
{
	$q = mysql_query("SELECT * FROM allowedip") or die(mysql_error());
	
	while ($iplist = mysql_fetch_assoc($q))
	{
		$allowedip[$iplist['IP']] = $iplist['status'];
	}
  	$ip = $_SERVER['REMOTE_ADDR'];
	return ($allowedip[$ip]);
}

if (!CheckAccess())
{
	header("Location: ../index.php");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VeriCon :: Welcome Letter Upload</title>
<script src="../jquery/js/jquery-1.7.2.min.js"></script>
<script type="text/javascript" src="upload/jquery.uploadify-3.1.min.js"></script>
<script>
$(function() {
    $('#file_upload').uploadify({
		'checkExisting' : 'upload/check-exists.php',
		'fileSizeLimit' : '20MB',
		'fileTypeDesc' : 'CSV',
        'fileTypeExts' : '*.csv',
		'multi' : false,
		'progressData' : 'speed',
		'swf' : 'upload/uploadify.swf',
        'uploader' : 'upload/uploadify.php',
		'buttonText' : 'UPLOAD',
		'buttonClass' : 'btn',
		'width' : 102,
		'onFallback' : function() {
			alert('Flash was not detected');
		},
		'onUploadSuccess' : function(file, data, response) {
			$.get("letters_process.php", { type: $( "#type" ).val(), file: file }, function (data) {
				$( "#output" ).html(data);
			});
		}
    });
});

function Type_Check() {
	if ( $( "#type" ).val() == "") {
		$( "#file_tr" ).attr( "style", "display: none;" );
	} else {
		$( "#file_tr" ).removeAttr( "style" );
	}
}
</script>
<style>
* {
	margin: 0;
	padding: 0;
}
body {
	font-family:Tahoma, Geneva, sans-serif;
	font-size:12px;
	color:#666666;
	background: #007CC5;
}
#wrapper {
	width: 900px;
	height: 500px;
	margin: 100px auto 0;
	padding: 0;
	background: white;
	box-shadow: 0 0 5px 2px;
}
h1 {
	margin: 10px 0;
	text-align: center;
}
#output {
	width: 440px;
	height: 447px;
	overflow-y: scroll;
	border-left: 1px dotted black;
	padding-left: 10px;
}
.error {
	color: red;
}
.good {
	color: green;
}
.uploadify-button {
	background-image: url('../images/btn.png');
	background-color: transparent;
	border: none;
	text-transform: uppercase;
	padding: 0;
	font-weight: bold;
	font-family: Tahoma, Geneva, sans-serif;
	font-size: 11px;
	color: white;
	text-shadow: none;
	text-align: center;
	margin-top: 10px;
}
.uploadify:hover .uploadify-button {
	background-color: transparent;
	background-image: url('../images/btn_hover.png');
}
</style>
</head>

<body>
<div id="wrapper">
	<table width="900px">
		<tr>
			<td width="450px" valign="top">
				<h1>Fill Meh Up Mang</h1>
				<table width="100%">
					<tr>
						<td width="100px">
							Type
						</td>
						<td>
							<select id="type" onchange="Type_Check()">
							<option></option>
							<option>Customers</option>
							<option>Packages</option>
							</select>
						</td>
					</tr>
					<tr>
					<td colspan="2" id="file_tr" style="display: none;">
						<input type="file" name="file_upload" id="file_upload" />
					</td>
					</tr>
				</table>
			</td>
			<td width="450px" valign="top">
				<h1>Output</h1>
				<div id="output">
				</div>
			</td>
		</tr>
	</table>
	
</div>
</body>
</html>