<table width="100%">
<tr>
<td style="padding-left:5px;"><img src="../images/upload_leads_header.png" width="135" height="25"></td>
</tr>
<tr>
<td><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<?php
if (!file_exists("/var/vtmp/leads_report.txt"))
{
?>
<script>
$(function() {
    $('#file_upload').uploadify({
		'checkExisting' : 'upload/check-exists.php',
		'fileSizeLimit' : '20MB',
		'fileTypeDesc' : 'CSV',
        'fileTypeExts' : '*.csv',
		'multi'    : false,
		'progressData' : 'speed',
		'swf'      : 'upload/uploadify.swf',
        'uploader' : 'upload/uploadify.php',
		'buttonText' : 'BROWSE...',
		'buttonClass' : 'btn',
		'width'    : 102,
		'onFallback' : function() {
			alert('Flash was not detected');
		},
		'onUploadSuccess' : function(file, data, response) {
			$.get("upload_submit.php?method=begin", function (data) {
				if (data == 1)
				{
					Upload_View();
				}
			});
		}
    });
});
</script>
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
<input type="file" name="file_upload" id="file_upload" />
<?php
}
else
{
	header("Cache-Control: no-store, must-revalidate");
	header("Expires: 0");
	
	$progress = count(file("/var/vtmp/leads_report.txt"));
	
	$init_lines = count(file("/var/vtmp/leads.csv"));
	$total_lines = count(file("/var/vtmp/leads_tmp.csv"));
	$tmp_lines = count(file("/var/vtmp/leads_tmp_count.txt"));
	if ($tmp_lines != 0)
	{
		$tmp_lines--;
	}
	$leads_lines = count(file("/var/vtmp/leads_count.txt"));
	if ($leads_lines != 0)
	{
		$leads_lines--;
	}
?>
<script>
var progressbar = $('#progressbar1').progressbar({
	value: <?php echo number_format(($tmp_lines/$init_lines)*100,2); ?>,
	change: function(event, ui) {    
		var newVal = $(this).progressbar('option', 'value');
		var label = $('.pblabel', this).text(newVal + '%');
	}
});

var label = progressbar.find('.pblabel').clone().width(progressbar.width());
progressbar.find('.ui-progressbar-value').append(label);
</script>
<script>
var progressbar = $('#progressbar2').progressbar({
	value: <?php echo number_format(($leads_lines/$total_lines)*100,2); ?>,
	change: function(event, ui) {    
		var newVal = $(this).progressbar('option', 'value');
		var label = $('.pblabel', this).text(newVal + '%');
	}
});

var label = progressbar.find('.pblabel').clone().width(progressbar.width());
progressbar.find('.ui-progressbar-value').append(label);
</script>
<center><div id="users-contain" class="ui-widget" style="width:98%">
<table id="users" class="ui-widget ui-widget-content">
<thead>
<tr class="ui-widget-header">
<th width="5%" style="text-align:center">Status</th>
<th width="30%">Process</th>
<th width="65%">Progress</th>
</tr>
</thead>
<tbody>
<?php
echo "<tr>";
if ($progress == 2)
{
	echo "<td style='text-align:center;'><img src='../images/ajax-loader.gif'></td>";
}
elseif ($progress >= 3)
{
	echo "<td style='text-align:center;'><img src='../images/check_icon.png'></td>";
}
else
{
	echo "<td style='text-align:center;'><img src='../images/delete_icon.png'></td>";
}
echo "<td>Create Temporary Leads CSV</td>";
echo "<td><div id='progressbar1'>";
echo "<span class='pblabel'>" . number_format(($tmp_lines/$init_lines)*100,2) . "%</span>";
echo "</div></td>";
echo "</tr>";
echo "<tr>";
if ($progress == 3)
{
	echo "<td style='text-align:center;'><img src='../images/ajax-loader.gif'></td>";
}
elseif ($progress >= 4)
{
	echo "<td style='text-align:center;'><img src='../images/check_icon.png'></td>";
}
else
{
	echo "<td style='text-align:center;'><img src='../images/delete_icon.png'></td>";
}
echo "<td>Upload Leads</td>";
echo "<td><div id='progressbar2'>";
echo "<span class='pblabel'>" . $leads_lines . "/" . $total_lines . " (" . number_format(($leads_lines/$total_lines)*100,2) . "%)</span>";
echo "</div></td>";
echo "</tr>";
?>
</tbody>
</table>
</div>
<table width="98%">
<tr>
<td align="center"><button onclick="Cancel_Upload()" class="btn_red">Cancel</button></td>
</tr>
</table>
</center>
<?php
}
?>