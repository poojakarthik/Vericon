<?php
$date = $_GET["date"];
if ($date == "") { $date = date("Y-m-d"); }
?>
<script>
$(function() {
	$( "#accordion" ).accordion({
		autoHeight: false
	});
});
</script>
<center><div id="accordion" style="width:98%;">
<?php
$path = '/var/vericon/leads/log';
$t = 0;
$f = 0;
$file_arr['name'] = array();
$file_arr['time'] = array();
if (@$handle = opendir($path))
{
	while (false!== ($file = readdir($handle)))
	{
		if($file!= "." && $file!= "..")
		{
			$fName = $file;
			$file = $path.'/'.$file;
			if(is_file($file))
			{
				$file_arr['time'][$t++] = filemtime($file);
				$file_arr['name'][$f++] = $file;
			}
		}
	}
	closedir($handle);
	arsort( $file_arr['time'] );
	arsort( $file_arr['name'] );
}

foreach ($file_arr['time'] as $key=>$ftime)
{
	if (date("Y-m-d", $ftime) == $date)
	{
		$fname = $file_arr['name'][$key];
		echo '<h3 style="font-weight:bold;"><a href="">' . date("h:i:s A", $ftime) . '</a></h3>';
		echo '<div>';
		echo '<pre style="text-align:left">';
		readfile($fname);
		echo '</pre>';
		echo '</div>';
	}
}
?>
</div></center>