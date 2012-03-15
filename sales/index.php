<?php
include "../auth/iprestrict.php";
$q = mysql_query("SELECT campaign FROM centres WHERE centre = '$ac[centre]'");
$cam = mysql_fetch_assoc($q);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Sales</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<link rel="stylesheet" href="../jquery/development-bundle/demos/demos.css">
<link rel="stylesheet" href="../jquery/development-bundle/themes/custom-theme/jquery.ui.all.css">
<script src="../jquery/development-bundle/jquery-1.6.2.js"></script>
<script src="../jquery/development-bundle/external/jquery.bgiframe-2.1.2.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.core.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.mouse.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.button.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.dialog.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.position.js"></script>
</head>

<body>
<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/sales_menu.php";
?>

<div id="text">

<p><img src="../images/agent_details_header.png" width="145" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" alt="line" /></p><br />

<table width="100%" border="0">
<tr>
<td width="85px">Agent Name: </td>
<td><b><?php echo $ac["first"] . " " . $ac["last"] . " (" . $ac["alias"] . ")"; ?></b></td>
</tr>
<tr>
<td width="85px">Access Level: </td>
<td><b><?php echo $ac["access"]; ?></b></td>
</tr>
<tr>
<td>Centre: </td>
<td><b><?php echo $ac["centre"]; ?></b></td>
</tr>
<tr>
<td>Campaign: </td>
<td><b><?php echo $cam["campaign"]; ?></b></td>
</tr>
</table><br />

<p><img src="../images/sale_stats_header.png" width="110" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" alt="line" /></p><br />

<p><center><img src="../sales/chart.php?method=user&user=<?php echo $ac["user"]; ?>" /></center></p>

</div>

</div>

<?php
include "../source/footer.php";
?>

</body>
</html>