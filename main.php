<?php
include "auth/iprestrict.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Home</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<link rel="stylesheet" href="../css/main.css" type="text/css"/>
<?php
include "source/jquery.php";
?>
</head>

<body>
<div style="display:none;">
<img src="../images/qa_portal_btn_hover.png" /><img src="../images/self_portal_btn_hover.png" /><img src="../images/cs_portal_btn_hover.png" /><img src="../images/sales_portal_btn_hover.png" /><img src="../images/admin_portal_btn_hover.png" /><img src="../images/tpv_portal_btn_hover.png" /><img src="../images/cct_portal_btn_hover.png" /><img src="../images/user_manual_btn_hover.png" /><img src="../images/operations_portal_btn_hover.png" /><img src="../images/melbourne_portal_btn_hover.png" /><img src="../images/leads_portal_btn_hover.png" />
</div>
<div id="main_wrapper">

<?php
include "source/header.php";
include "source/browser.php";
?>

<div id="menu">
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a>Welcome to VeriCon. Please choose your respective portal.</a></li>
</ul>
</div>
</div>

<?php
if ($browser["name"] == "Firefox" && $browser["version"] >= 11)
{

}
else
{
?>
<script>
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog({
		autoOpen: true,
		resizable: false,
		draggable: false,
		width: 410,
		height:260,
		modal: true,
		buttons: {
			"OK": function() {
				$( "#dialog-confirm" ).dialog( "close" );
			}
		}
	});
});
</script>

<script>
function Firefox()
{
	window.open("http://mozilla.mirror.aarnet.edu.au/pub/mozilla/firefox/releases/12.0/win32/en-US/Firefox%20Setup%2012.0.exe");
}
</script>

<div id="dialog-confirm" title="Please Upgrade Browser">
	<p>Please note that from Monday, 7th May 2012, VeriCon will only be available on Firefox Ver 11+</p><br />
    <p>Be sure to update your browser otherwise you will not be able to access VeriCon</p><br />
   	<center><p><a onclick="Firefox()" target="_blank"><img src="../images/firefox_download.png" /></a></p></center>
</div>
<?php
}
?>

<div id="vericon_portals">
<table class="script_table" style="padding: 15px 15px 7px;">
<tr><td><img src="../images/vericon_portals_header.png" width="170" height="25" /></td></tr>
<tr><td><img src="images/line.png" width="715" height="9" alt="line" /></td></tr>
</table>
<table width="100%">
<tr>
<?php
for ($i = 0;$i < count($p1);$i++)
{
	echo "<td align='center' valign='middle'>";
	echo "<a href=\"$p1[$i]\" class=\"" . $p1[$i] . "_portal\"></a>";
	echo "</td>";
}
?>
<td align='center' valign='middle'><a href="manuals/" class="user_manual"></a></td>
</tr>
</table>
</div>

<div id="text">

<p><img src="../images/vericon_updates_header.png" width="175" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p><br />

<?php
$check = mysql_query("SELECT * FROM updates") or die(mysql_error());
$rows = mysql_num_rows($check);

if ($rows == 0)
{
	echo "<p>No Updates?</p>";
}
else
{
	$st = $_GET["page"]*1;
	
	$q = mysql_query("SELECT * FROM updates ORDER BY id DESC LIMIT $st , 1") or die(mysql_error());

	while($r = mysql_fetch_assoc($q))
	{
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
	if (($st - 1) < $rows && $_GET["page"] > 0)
	{
		$page = $_GET["page"]-1;
		echo "<a href='?page=$page' class='newer'></a>";
	}
	?>
	</td>
	<td align="right">
	<?php
	if (($st + 1) < $rows)
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
?>

</div>

</div> 
<?php
include "source/footer.php";
?>
</body>
</html>