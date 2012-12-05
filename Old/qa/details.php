<?php
mysql_connect('localhost','vericon','18450be');

function CheckAccess()
{
	$q = mysql_query("SELECT * FROM vericon.allowedip") or die(mysql_error());
	
	while ($iplist = mysql_fetch_assoc($q))
	{
		$allowedip[$iplist['IP']] = $iplist['status'];
	}
  	$ip = $_SERVER['REMOTE_ADDR'];
	return ($allowedip[$ip]);
}

if (!CheckAccess())
{
	header("Location: ../index.php");
	exit;
}

$q1 = mysql_query("SELECT user FROM vericon.currentuser WHERE hash = '" . $_COOKIE["hash"] . "'") 
  or die(mysql_error());

$user = mysql_fetch_row($q1);

$q2 = mysql_query("SELECT * FROM vericon.auth WHERE user = '$user[0]'") or die(mysql_error());

$ac = mysql_fetch_assoc($q2);

$p = strtolower($ac["type"]);

$p1 = explode(",",$p);

for ($i = 0;$i < count($p1);$i++)
{
	foreach ($p1 as &$value)
	{
    	$acc[$p1[$i]] = true;
	}
}

$d = explode("/",$_SERVER['PHP_SELF']);

if (mysql_num_rows($q1) != 1)
{
	header("Location: ../index.php");
	exit;
}
elseif (preg_match("/admin/",$p) || $_SERVER[PHP_SELF] == "/main.php" || $d[1] == "ma")
{
	
}
elseif ($acc[$d[1]] != true)
{
	header("Location: ../index.php");
	exit;
}

if ($ac["status"] == "Disabled")
{
	setcookie("hash", "", time()-86400);
	header("Location: ../index.php?attempt=banned");
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />
<title><?php echo "VeriCon :: Quality Assurance :: Customer Details"; ?></title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<link rel="stylesheet" href="../jquery/css/custom-theme/jquery-ui-1.8.22.custom.css">
<script src="../jquery/js/jquery-1.7.2.min.js"></script>
<script src="../jquery/js/jquery-ui-1.8.22.custom.min.js"></script>
<script type="text/javascript" src="../js/ddsmoothmenu.js"></script>
<script type="text/javascript">
ddsmoothmenu.init({
	mainmenuid: "smoothmenu1", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})
</script>
</head>

<body>
<div id="main_wrapper">
<div id="innerpage_logo">
<img src="../images/logo.png"  width="252" height="65" alt="logo" style="border-style:none;" />
</div>
<div id="menu">
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><span style="padding:8px 25px; cursor:pointer; display:block; margin:inherit;">Validate the Customer's Details Below</span></li>
</ul>
</div>
</div>

<div id="text">

<div id="display">
<script>
$.get('details_submit.php?method=country', { id: "<?php echo $_GET["id"]; ?>" }, function(data) {
	$( "#display" ).load("details_display_" + data + ".php?id=<?php echo $_GET["id"]; ?>");
});
</script>
</div>

<?php
include "../source/footer.php";
?>