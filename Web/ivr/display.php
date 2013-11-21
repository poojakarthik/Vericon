<?php
mysql_connect('localhost','vericon','18450be');

if (strlen($_GET["id"]) == 9)
{
	$id = $_GET["id"];
	
	$q = mysql_query("SELECT `sales_customers`.`id`, `sales_customers`.`centre`,`sales_customers`.`campaign`,`sales_customers`.`type`, CONCAT(`sales_customers`.`title`,' ',`sales_customers`.`firstname`,' ',`sales_customers`.`middlename`,' ',`sales_customers`.`lastname`) as name, CONCAT(`auth`.`first`,' ',`auth`.`last`) as agent, `auth`.`alias` FROM `vericon`.`sales_customers`,`vericon`.`auth` WHERE `sales_customers`.`id` = '" . mysql_real_escape_string($id) . "' AND `sales_customers`.`agent` = `auth`.`user`") or die(mysql_error());
	$data = mysql_fetch_assoc($q);
	
	$q = mysql_query("SELECT `id` FROM `vericon`.`campaigns` WHERE `campaign` = '" . mysql_real_escape_string($data["campaign"]) . "'") or die(mysql_error());
	$c_id = mysql_fetch_row($q);
}
else
{
	$ext = $_GET["id"];
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: IVR :: Sale Details</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/style.css" type="text/css"/>
</head>
<body style="min-height:400px; background-image:none; background-color:rgb(255,245,236);">
<div id="wrapper" style="width:600px;">
<div id="logo" style="margin-top:10px;">
<img src="../images/logo.png"  width="252" height="65" alt="logo" />
</div>
<?php
if (mysql_num_rows($q) != 0)
{
?>
<div id="login_form">  
<table>
<tr>
<td><img src="../images/details_bg.png" width="194" height="24" style="margin:40px 0 5px 30px; font-size:10px;font-family:Tahoma;" /><td>
</tr>
</table>
<table class="table_style" style="margin-left:50px;">
<tr>
<td width="100px"><b>Sale Code</b></td>
<td><?php echo $data["id"]; ?></td>
</tr>
<tr>
<td><b>Agent</b></td>
<td><?php echo $data["agent"] . " (" . $data["alias"] . ")"; ?></td>
</tr>
<tr>
<td><b>Centre</b></td>
<td><?php echo $data["centre"]; ?></td>
</tr>
<tr>
<td><b>Campaign</b></td>
<td><?php echo $data["campaign"] . " " . $data["type"]; ?></td>
</tr>
<tr>
<td><b>Customer Name</b></td>
<td><?php echo str_replace("  ", " ", $data["name"]); ?></td>
</tr>
<tr>
<td><b>Main Line</b></td>
<?php
$q = mysql_query("SELECT `sales_packages`.`cli`,`plan_matrix`.`name` FROM `vericon`.`sales_packages`,`vericon`.`plan_matrix` WHERE `sales_packages`.`sid` = '" . $data["id"] . "' AND `plan_matrix`.`id` = `sales_packages`.`plan` AND `plan_matrix`.`campaign` = '" . mysql_real_escape_string($c_id[0]) . "' ORDER BY `sales_packages`.`timestamp` ASC LIMIT 1") or die(mysql_error());
$lines = mysql_fetch_assoc($q);

echo "<td>" . $lines["cli"] . " -- " . $lines["name"] . "</td>";
?>
</tr>
</td>
</tr>   
</table>           
<table class="table_style2">
<tr>
<td><img src="../images/line_bg.png" height="6" width="300" style="margin-top:10px; margin-bottom:5px;"/></td>
</tr>
</table>
</div>	
<?php
}
elseif (substr($ext,0,2) == "CC")
{
?>
<div id="login_form">  
<table>
<tr>
<td><img src="../images/details_bg.png" width="194" height="24" style="margin:40px 0 5px 30px; font-size:10px;font-family:Tahoma;" /><td>
</tr>
</table>
<table class="table_style" style="margin-left:50px;">
<tr>
<td width="100px"><b>Sale Code</b></td>
<td>N/A</td>
</tr>
<tr>
<td><b>Agent</b></td>
<td>N/A</td>
</tr>
<tr>
<td><b>Centre</b></td>
<td><?php echo $ext; ?></td>
</tr>
<tr>
<td><b>Campaign</b></td>
<td>N/A</td>
</tr>
<tr>
<td><b>Customer Name</b></td>
<td>N/A</td>
</tr>
</td>
</tr>   
</table>           
<table class="table_style2">
<tr>
<td><img src="../images/line_bg.png" height="6" width="300" style="margin-top:10px; margin-bottom:5px;"/></td>
</tr>
</table>
</div>
<?php
}
else
{
	echo "<br><br><center><h2>Incorrect Sale ID!</h2>";
}
?>
</div>
</body>
</html>