<?php
// ==============================
// Server Uptime And Hardware Information |
// ==============================
?>

<html>
<head>
<title><?php echo $_SERVER['SERVER_NAME']; ?> - Server Information</title>
<STYLE type=text/css>
BODY { FONT-SIZE: 8pt; COLOR: black; FONT-FAMILY: Verdana,arial, helvetica, serif; margin : 0 0 0 0;}
</STYLE>
</head>
<body>
<pre>
<b>Uptime:</b>
<?php system("uptime"); ?>

<b>System Information:</b>
<?php system("uname -a"); ?>


<b>Memory Usage (MB):</b>
<?php system("free -m"); ?>


<b>Disk Usage:</b>
<?php system("df -h"); ?>


<b>CPU Information:</b>
<?php system("cat /proc/cpuinfo | grep \"model name\\|processor\""); ?>
</pre>

<br>
<br>
</body>
</html>