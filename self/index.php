<?php
include "../auth/iprestrict.php";
include "../js/self-js.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Self Verification</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<?php
include "../source/jquery.php";
?>
<script src="../js/sorttable.js"></script>
<script>
function Top_Ten()
{
	var method = $( "#method" );
	$( "#top_ten" ).load('top_ten.php?method=' + method.val());
}
</script>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .4em 10px; }
</style>
</head>

<body>
<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/self_menu.php";
?>

<div id="text" class="demo">
<p>Down until further notice</p>
</div>

</div>

<?php
include "../source/footer.php";
?>

</body>
</html>