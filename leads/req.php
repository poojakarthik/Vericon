<?php
include "../auth/iprestrict.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Leads</title>
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
include "../source/leads_menu.php";
?>

<div id="text">
<?PHP
  $q = mysql_query("SELECT * FROM leads_request WHERE status = 'OPEN'") || die(mysql_error());

while($d = mysql_fetch_row($q)){
  echo "$d[0]";
}

?>
</div>

</div> 
<?php
include "../source/footer.php";
?>
</body>
</html>