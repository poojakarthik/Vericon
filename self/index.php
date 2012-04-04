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

<table width="100%" border="0">
<tr valign="top">
<td width="65%">
<p><img src="../images/centre_sales_header.png" width="130" height="25" style="margin-left:3px;" /></p>
<p><img src="../images/line.png" width="95%" height="9" alt="line" /></p>
<div id="users-contain" class="ui-widget" style="margin-left:3px;">
<table id="users" class="ui-widget ui-widget-content sortable" width="90%" style="margin-top:3px;">
<thead>
<tr class="ui-widget-header ">
<th width="40%" align="left">Name</th>
<th width="20%">Today</th>
<th width="20%">Week</th>
<th width="20%">Overall</th>
</tr>
</thead>
<tbody align="center">
<?php
$q = mysql_query("SELECT * FROM auth WHERE centre = '$ac[centre]' AND status = 'Enabled' ORDER BY user ASC") or die(mysql_error());
while ($agent = mysql_fetch_assoc($q))
{
	$today = date("Y-m-d");
	$week = date("W");
	$q1 = mysql_query("SELECT (SELECT COUNT(*) FROM sales_customers WHERE agent = '$agent[user]' AND DATE(approved_timestamp) = '$today'),(SELECT COUNT(*) FROM sales_customers WHERE agent = '$agent[user]' AND WEEK(approved_timestamp) = '$week'),(SELECT COUNT(*) FROM sales_customers WHERE agent = '$agent[user]')") or die(mysql_error());
	$stats = mysql_fetch_row($q1);
	echo "<tr>";
	echo "<td align='left'>" . $agent["first"] . " " . $agent["last"] . "</td>";
	echo "<td>" . $stats[0] . "</td>";
	echo "<td>" . $stats[1] . "</td>";
	echo "<td>" . $stats[2] . "</td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div>
</td>
<td width="35%">
<p><img src="../images/top_ten_header.png" width="80" height="25" /></p>
<p><img src="../images/line.png" width="95%" height="9" alt="line" /></p>
<div id="users-contain" class="ui-widget" style="margin-left:3px;">
<table id="users" class="ui-widget ui-widget-content" width="90%" style="margin-top:3px;">
<thead>
<tr class="ui-widget-header ">
<th align="left">Name</th>
<th width="20%"><select id="method" style="border:0; height:auto; margin:0; padding:0; width:75px; background:none; color:#EAF5F7; font-weight:bold;" onchange="Top_Ten()">
<option>Today</option>
<option>Week</option>
<option>Overall</option>
</select></th>
</tr>
</thead>
<tbody id="top_ten" align="center">
<script>
var method = $( "#method" );
$( "#top_ten" ).load('top_ten.php?method=' + method.val());
</script>
</tbody>
</table>
</div>
</td>
</tr>
</table>

</div>

</div>

<?php
include "../source/footer.php";
?>

</body>
</html>