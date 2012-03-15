<?php
include "../auth/iprestrict.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: TPV :: Rates</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<link rel="stylesheet" href="../jquery/development-bundle/themes/custom-theme/jquery.ui.all.css">
<script src="../jquery/development-bundle/jquery-1.6.2.js"></script>
<script src="../jquery/development-bundle/external/jquery.bgiframe-2.1.2.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.core.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.mouse.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.button.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.draggable.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.position.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.resizable.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.dialog.js"></script>
<link rel="stylesheet" href="../jquery/development-bundle/demos/demos.css">
</head>

<body>
<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/tpv_menu.php";
?>

<div id="text" style="overflow:hidden;" class="demo">

<?php
include "../source/rates.php";
?>

<p><img src="../images/international_rates_header.png" width="210" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p><br />

<?php
include "../source/international.php";
?>

</div>

</div> 
<?php
include "../source/footer.php";
?>
</body>
</html>