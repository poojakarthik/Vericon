<?php
include "../auth/iprestrict.php";
include "../auth/admin_access.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Melbourne Sales :: Admin</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<?php
include "../source/jquery.php";
?>
<style>
	label { margin-right:3px; }
	input.text { margin-bottom:12px; width:95%; padding: .4em; font-family:Tahoma, Geneva, sans-serif;
font-size:13px; }
	fieldset { padding:0; border:0; margin-top:25px; }
	h1 { font-size: 1.2em; margin: .6em 0; }
	div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
	div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
	.ui-dialog .ui-state-error { padding: .3em; }
	.validateTips { border: 1px solid transparent; padding: 0.3em; }
</style>
</head>

<body>
<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/melbourne_menu.php";
?>

<div id="text" class="demo">
<?php
if ($_GET["p"] == "users")
{
	include "../melbourne/admin_users.php";
}
elseif ($_GET["p"] == "teams")
{
	include "../melbourne/admin_teams.php";
}
elseif ($_GET["p"] == "current")
{
	include "../melbourne/admin_current.php";
}
elseif ($_GET["p"] == "details")
{
	include "../melbourne/admin_details.php";
}
elseif ($_GET["p"] == "roster")
{
	include "../melbourne/admin_roster.php";
}
else
{
	echo "<p>Error!</p>";
}
?>
</div>

</div> 
<?php
include "../source/footer.php";
?>
</body>
</html>