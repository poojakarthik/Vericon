<?php
$id = $_GET["sale_id"];

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$q = mysql_query("SELECT * FROM sales_customers WHERE id = '$id'") or die(mysql_error());
$data = mysql_fetch_assoc($q);

$q1 = mysql_query("SELECT alias FROM auth WHERE user = '$data[agent]'") or die (mysql_error());
$salias = mysql_fetch_row($q1);

$sale_id = $data["id"];
$centre = $data["centre"];
$campaign = $data["campaign"] . " " . $data["type"];
$name = $data["firstname"] . " " . $data["middlename"] . " " . $data["lastname"];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: IVR :: Sale Details</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/style.css" type="text/css"/>
<style>
.get_sale_btn
{
	background-image:url('../images/get_sale_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
	display:block;
}

.get_sale_btn:hover
{
	background-image:url('../images/get_sale_btn_hover.png');
	cursor:pointer;
}
</style>
<!--<script>
function Init()
{
	var id = "<?php echo $id; ?>";

	$.get("init.php",{sale_id: id});
}

function closeEditorWarning(){
	var id = "<?php echo $id; ?>";
	$.get("end.php", {sale_id: id}, function(data){});
	return "Click Leave This Page";
}

window.onbeforeunload = closeEditorWarning;
</script>-->
</head>
<!--<body onload="Init()">-->
<body style="min-height:400px; background-image:url(../images/ivr_body_bg.jpg);">
<div id="wrapper" style="width:600px;">
<div id="logo" style="margin-top:10px;">
<img src="../images/logo.png"  width="252" height="65" alt="logo" />
</div>
<?php
if (mysql_num_rows($q) == 0)
{
	echo "<br><br><center><h2>Incorrect Sale ID!</h2>";
	exit;
}
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
<td><?php echo $sale_id; ?></td>
</tr>
<tr>
<td><b>Agent</b></td>
<td><?php echo $data["agent"] . " (" . $salias[0] . ")"; ?></td>
</tr>
<tr>
<td><b>Centre</b></td>
<td><?php echo $centre; ?></td>
</tr>
<tr>
<td><b>Campaign</b></td>
<td><?php echo $campaign; ?></td>
</tr>
<tr>
<td><b>Customer Name</b></td>
<td><?php echo $name; ?></td>
</tr>
<tr>
<td><b>Main Line</b></td>
<?php
$q2 = mysql_query("SELECT * FROM sales_packages WHERE sid = '$id' LIMIT 1") or die(mysql_error());
$lines = mysql_fetch_assoc($q2);
echo "<td>" . $lines["cli"] . " -- " . $lines["plan"] . "</td>";
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
</div>
</body>
</html>