<?php
include "../auth/iprestrict.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Quality Assurance</title>
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
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
</style>
</head>

<body>
<div style="display:none;">

</div>
<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/qa_menu.php";
?>

<div id="text">

<p><img src="../images/sale_stats_header.png" width="110" height="25" style="margin-left:3px;" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p>

<p>Text</p>

</div>

</div> 
<?php
include "../source/footer.php";
?>
</body>
</html>