<?php
include "source/browser.php";

if ($browser["name"] == "Firefox" && $browser["version"] >= 11)
{
	
}
else
{
	echo "<h1>Sorry VeriCon is not supported by your web browser<br>Please use Firefox version 11 or above</h1><br>";
	echo '<h1><a href="http://www.mozilla.org/en-US/firefox/new/">Click Here to Download the Latest Version of Firefox</a></h1>';
	exit;
}

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$q1 = mysql_query("SELECT user FROM currentuser WHERE hash = '" . $_COOKIE["hash"] . "'") 
  or die(mysql_error());

$user = mysql_fetch_row($q1);

$q2 = mysql_query("SELECT * FROM auth WHERE user = '$user[0]'") or die(mysql_error());

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

if ($_SERVER[PHP_SELF] == "/index.php")
{
	if ($p != "")
	{
		header("Location: ../main.php");
	}
}
elseif (preg_match("/admin/",$p) || $_SERVER[PHP_SELF] == "/main.php")
{
	
}
elseif (mysql_num_rows($q1) != 1)
{
	header("Location: ../index.php");
	exit;
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
}

if($_GET["attempt"] == "badip")
{
	echo "<h1>Access Denied!</h1>";
	echo "The IP " . $_SERVER['REMOTE_ADDR'] . " does not have the ability to view this website!";
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VeriCon :: Login</title>
<link rel="shortcut icon" href="./images/vericon.ico">
<link rel="stylesheet" href="css/style.css" type="text/css"/>
</head>

<body>
<div style="display:none;">
<img src="./images/login_btn_hover.png" />
</div>
<div id="wrapper">
<div id="logo">
<img src="./images/logo.png"  width="252" height="65" alt="logo" />
</div>
<div id="login_form">  
<form action="auth/check.php" method="post" class="form_style" autocomplete="off">
<table>
<tr>
<td><img src="./images/welcome_bg.png" width="156" height="24" align="welcome" style="margin-top:80px; margin-left:50px; font-size:10px;font-family:Tahoma;" /><td>
</tr>
<tr><td><p class="form_para">Enter your username and password in the below fields</p></td></tr>
</table>
<table class="table_style">
<tr>
<td><img src="./images/users_bg.png" width="23" height="24" align="users"></td>
<td>USERNAME</td>
<td><input name="username" type="text" size="25"></td>
</tr>
<tr>
<td><img src="./images/password_bg.png" width="23" height="20" align="password"></td>
<td>PASSWORD</td>
<td><input name="password" type="password" size="25"></td>
</tr>     
<td colspan="3" align="left"><input name="" type="submit" class="submit_btn" value=""></td> 
</table>           
<table class="table_style2">
<tr>
<td><img src="./images/line_bg.png" height="6" width="300" align="line"/></td>
</tr>
<tr><td><p class="form_para2">
<?php
if($_GET["attempt"] == "fail")
{
	echo "Incorrect Username or Password!";
}
elseif($_GET["attempt"] == "banned")
{
	echo "Your Account has been Disabled!";
}
?>
</p></td></tr>
</table>
</form>
</div>
</div>
</body>
</html>
