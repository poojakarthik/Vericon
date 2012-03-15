<?php
include "../auth/iprestrict.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Customer Solutions</title>
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
include "../source/cs_menu.php";
?>

<div id="text">

<p><img src="../images/cs_announcements_header.png" width="200" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p><br />

<?php
$check = mysql_query("SELECT * FROM announcements WHERE (department = 'cs' OR department = 'all') AND display = 'Yes'") or die(mysql_error());
$rows = mysql_num_rows($check);

if ($rows == 0)
{
	echo "<p>No Announcements!</p>";
}
else
{
	$st = $_GET["page"]*4;
	
	$q = mysql_query("SELECT * FROM announcements WHERE (department = 'cs' OR department = 'all') AND display = 'Yes' ORDER BY id DESC LIMIT $st , 4") or die(mysql_error());
	
	if ($rows <= 4)
	{
		while($r = mysql_fetch_assoc($q))
		{
			$icon = "";
			if ($r["department"] == "all")
			{
				$icon = "&nbsp;<img src='../images/star.gif'>";
			}
			echo "<p><b>" . $r["subject"] . $icon . "</b></p>";
			echo "<p>" . nl2br($r["message"]) . "</p>";
			echo "<img src='../images/line.png' width='200' height='9'>";
			echo "<p style='font-size:9px;'>Posted by " . $r["poster"] . " | " . $r["date"] . "</p><br>";
		}
	}
	else
	{
		while($r = mysql_fetch_assoc($q))
		{
			$icon = "";
			if ($r["department"] == "all")
			{
				$icon = "&nbsp;<img src='../images/star.gif'>";
			}
			echo "<p><b>" . $r["subject"] . $icon . "</b></p>";
			echo "<p>" . nl2br($r["message"]) . "</p>";
			echo "<img src='../images/line.png' width='200' height='9'>";
			echo "<p style='font-size:9px;'>Posted by " . $r["poster"] . " | " . $r["date"] . "</p><br>";
		}
		?>
        <table width="100%">
        <tr>
        <td align="left">
        <?php
		if (($st - 4) < $rows && $_GET["page"] > 0)
		{
			$page = $_GET["page"]-1;
			echo "<a href='?page=$page' class='newer'></a>";
		}
		?>
        </td>
        <td align="right">
        <?php
		if (($st + 4) < $rows)
		{
			$page = $_GET["page"]+1;
			echo "<a href='?page=$page' class='older'></a>";
		}
		?>
        </td>
        </tr>
        </table>
        <?php
	}
}

?>

</div>

</div> 
<?php
include "../source/footer.php";
?>
</body>
</html>