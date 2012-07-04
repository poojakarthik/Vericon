<?php
$method = $_GET["method"];

if ($method == "load")
{
	$load = exec("cat /proc/loadavg");
	$load = split(" ",$load);
?>
<div style="margin:5px 10px 0px 15px;"><span style="font-size:32px;"><?php echo $load[0]; ?></span><span style="font-size:20px;"> / 12</span></div>
<center><div style="margin-top:10px; margin-left:15px; float:left"><?php echo $load[1] . "<br>5 Mins"; ?></div><div style="margin-top:10px; margin-right:15px; float:right"><?php echo $load[2] . "<br>15 Mins"; ?></div></center>
<?php
}
elseif ($method == "mem")
{
	$mem = exec("cat /proc/meminfo | grep MemTotal");
	$mem = str_replace(" ","",$mem);
	$mem = split(":",$mem);
	$mem_t = substr($mem[1],0,-2);
	$mem_total = number_format((substr($mem[1],0,-2) / 1048576),2) . " GB";
	$mem = exec("cat /proc/meminfo | grep MemFree");
	$mem = str_replace(" ","",$mem);
	$mem = split(":",$mem);
	$mem_used = number_format((($mem_t - substr($mem[1],0,-2)) / 1048576),2) . " GB";
?>
<div style="margin:5px 10px 0px 15px;"><span style="font-size:32px;"><?php echo $mem_used; ?></span></div><br>
<div style="margin:5px 10px 0px 15px;"><span style="font-size:20px;"> / <?php echo $mem_total; ?></span></div>
<?php
}
?>