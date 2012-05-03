<?php
include "../auth/iprestrict.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: User Manual</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<?php
include "../source/jquery.php";
?>
<script>
function Play(id)
{
	var l = "play.php?id=" + id;
	window.open(l,'Video','menubar=no,scrollbars=no,width=1050px,height=825px,left=10px,top=10px');
}
</script>
</head>

<body>
<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/manuals_menu.php";
?>

<div id="text">

<div id="dialog-confirm" title="Sale Details">
</div>

<p><img src="../images/video_tutorials_header.png" width="160" height="25" style="margin-left:3px;" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p>

<center><table width="98%">
<?php
$portals = explode(",",$ac["type"]);

for ($i = 0;$i < count($portals);$i++)
{
	$q = mysql_query("SELECT * FROM tutorials WHERE portal = '$portals[$i]'") or die(mysql_error());
	echo "<tr>";
	echo "<td colspan='" . (mysql_num_rows($q) + 1) . "'><h1><u>" . $portals[$i] . "</u></h1></td>";
	echo "</tr>";
	echo "<tr>";
	if (mysql_num_rows($q) == 0)
	{
		echo "<td>Coming Soon</td>";
	}
	else
	{
		while ($data = mysql_fetch_assoc($q))
		{
			
			echo "<td>";
			echo "<table>";
			echo "<tr>";
			echo "<td align='center'><a onclick='Play(\"$data[id]\")' style='cursor:pointer;'><img src='thumbnail.php?id=$data[id]' style='border:1px solid black'></a></td>";
			echo "</tr>";
			echo "<tr>";
			echo "<td align='center'>" . $data["name"] . "</td>";
			echo "</tr>";
			echo "</table>";
			echo "</td>";
		}
	}
	echo "</tr>";
}
?>
</table></center>

</div>

</div> 
<?php
include "../source/footer.php";
?>
</body>
</html>