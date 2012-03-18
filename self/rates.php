<?php
include "../auth/iprestrict.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Self Verification :: Rates</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<?php
include "../source/jquery.php";
?>
</head>

<body>
<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/self_menu.php";
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