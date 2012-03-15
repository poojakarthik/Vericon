<?php
include "../auth/iprestrict.php";
include "../auth/admin_access.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: TPV :: Admin</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<link rel="stylesheet" href="../css/jqtransform.css" type="text/css" media="all" />
<link rel="stylesheet" href="../jquery/development-bundle/themes/custom-theme/jquery.ui.all.css">
<script src="../jquery/development-bundle/jquery-1.6.2.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.core.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.datepicker.js"></script>
<script src="../jquery/development-bundle/external/jquery.bgiframe-2.1.2.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.mouse.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.button.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.draggable.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.position.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.resizable.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.dialog.js"></script>
<script src="../jquery/development-bundle/ui/jquery.effects.core.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.autocomplete.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.accordion.js"></script>
<script type="text/javascript" src="../js/jquery.jqtransform.js"></script>
<script type="text/javascript" src="../js/admin.js"></script>
<link rel="stylesheet" href="../jquery/development-bundle/demos/demos.css">
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
	.search
	{
		background-image:url('../images/search_btn.png');
		background-repeat:no-repeat;
		height:30px;
		width:102px;
		border:none;
		background-color:transparent;
	}
	
	.search:hover
	{
		background-image:url('../images/search_btn_hover.png');
		cursor:pointer;
	}
</style>
</head>

<body>
<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/tpv_menu.php";
?>

<div id="text" class="demo">
<?php
if ($_GET["p"] == "announcements")
{
	include "../tpv/admin_announcements.php";
}
elseif ($_GET["p"] == "users")
{
	include "../tpv/admin_users.php";
}
elseif ($_GET["p"] == "stats")
{
	include "../tpv/admin_stats.php";
}
elseif ($_GET["p"] == "details")
{
	include "../tpv/admin_details.php";
}
elseif ($_GET["p"] == "edit")
{
	include "../tpv/admin_edit.php";
}
elseif ($_GET["p"] == "scripts")
{
	include "../tpv/admin_scripts.php";
}
elseif ($_GET["p"] == "search")
{
	include "../tpv/admin_search.php";
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