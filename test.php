<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Admin</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<?php
include "source/jquery.php";
?>
<script>
window.setInterval("" ,1000);
</script>
</head>

<body>
<div id="main_wrapper">

<?php
include "source/header.php";
include "source/admin_menu.php";

function linuxUptime() {
  $ut = strtok( exec( "cat /proc/uptime" ), "." );
  $days = sprintf( "%2d", ($ut/(3600*24)) );
  $hours = sprintf( "%2d", ( ($ut % (3600*24)) / 3600) );
  $min = sprintf( "%2d", ($ut % (3600*24) % 3600)/60  );
  $sec = sprintf( "%2d", ($ut % (3600*24) % 3600)%60  );
  return array( $days, $hours, $min, $sec );
}
?>

<div id="text" class="demo">

<p><img src="../images/server_dashboard_header.png" width="190" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p><br />

<table width="100%" style="border:1px solid black;">
<tr>
<th style="text-align:left;">Server</th>
<th>HTTP</th>
<th>HTTPS</th>
<th>SFTP</th>
<th>MYSQL</th>
<th>Uptime</th>
</tr>
<tr style="text-align:center;">
<td style="text-align:left;">Main</td>
<td><?php $checkport = fsockopen("localhost", "80", $errnum, $errstr, 2); if(!$checkport) { echo "<img src='../images/down.png'>"; } else { echo "<img src='../images/up.png'>"; } ?></td>
<td><?php $checkport = fsockopen("localhost", "443", $errnum, $errstr, 2); if(!$checkport) { echo "<img src='../images/down.png'>"; } else { echo "<img src='../images/up.png'>"; } ?></td>
<td><?php $checkport = fsockopen("localhost", "22", $errnum, $errstr, 2); if(!$checkport) { echo "<img src='../images/down.png'>"; } else { echo "<img src='../images/up.png'>"; } ?></td>
<td><?php $checkport = fsockopen("localhost", "3306", $errnum, $errstr, 2); if(!$checkport) { echo "<img src='../images/down.png'>"; } else { echo "<img src='../images/up.png'>"; } ?></td>
<td><?php $ut = linuxUptime(); echo "$ut[0] days, $ut[1]:$ut[2]"; ?></td>
</tr>
<tr style="text-align:center;">
<td style="text-align:left;">Redundancy</td>
<td><?php $checkport = fsockopen("", "81", $errnum, $errstr, 2); if(!$checkport) { echo "<img src='../images/down.png'>"; } else { echo "<img src='../images/up.png'>"; } ?></td>
<td><?php $checkport = fsockopen("", "443", $errnum, $errstr, 2); if(!$checkport) { echo "<img src='../images/down.png'>"; } else { echo "<img src='../images/up.png'>"; } ?></td>
<td><?php $checkport = fsockopen("", "22", $errnum, $errstr, 2); if(!$checkport) { echo "<img src='../images/down.png'>"; } else { echo "<img src='../images/up.png'>"; } ?></td>
<td><?php $checkport = fsockopen("", "3306", $errnum, $errstr, 0); if(!$checkport) { echo "<img src='../images/down.png'>"; } else { echo "<img src='../images/up.png'>"; } ?></td>
<td></td>
</tr>
<tr style="text-align:center;">
<td style="text-align:left;">Test</td>
</tr>
</table>

</div>

</div> 
<?php
include "source/footer.php";
?>
</body>
</html>