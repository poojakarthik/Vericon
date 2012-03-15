<?php
include "../auth/iprestrict.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Operations</title>
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
include "../source/operations_menu.php";
?>

<div id="text">

<?php
$q = mysql_query("SELECT centres FROM operations WHERE user = '$ac[user]'") or die(mysql_error());
$cen = mysql_fetch_row($q);
$centres = explode(",",$cen[0]);
$date = date("Y-m-d");
?>
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content">
<thead>
<tr class="ui-widget-header ">
<th>Centre</th>
<th>Campaign</th>
<th>Agents</th>
<th>Sales</th>
<th>Sales Per Agent</th>
</tr>
</thead>
<tbody>
<?php
for ($i = 0; $i < count($centres); $i++)
{
	$total_agents = 0;
	$q1 = mysql_query("SELECT campaign FROM centres WHERE centre = '$centres[$i]'") or die(mysql_error());
	$campaign = mysql_fetch_row($q1);
	
	$q2a = mysql_query("SELECT user FROM auth WHERE centre = '$centres[$i]' AND status = 'Enabled'") or die(mysql_error());
	while ($agent = mysql_fetch_row($q2a))
	{
		$q2 = mysql_query("SELECT * FROM log_login WHERE user = '$agent[0]' AND DATE(timestamp) = '$date' GROUP BY user") or die(mysql_error());
		$total_agents += mysql_num_rows($q2);
	}
	
	$q3 = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND centre = '$centres[$i]' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
	$sales = mysql_num_rows($q3);
	
	$spa = $sales / $total_agents;
	
	echo "<tr>";
	echo "<td>" . $centres[$i] . "</td>";
	echo "<td>" . $campaign[0] . "</td>";
	echo "<td style='text-align:center;'>" . $total_agents . "</td>";
	echo "<td style='text-align:center;'>" . $sales . "</td>";
	echo "<td style='text-align:center;'>" . round($spa,2) . "</td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div></center>

</div>

</div> 
<?php
include "../source/footer.php";
?>
</body>
</html>