<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$mysqli = new mysqli('localhost','vericon','18450be');

function CheckAccess()
{
	$mysqli = new mysqli('localhost','vericon','18450be');
	
	$q = $mysqli->query("SELECT * FROM `vericon`.`allowedip` WHERE '" . $mysqli->real_escape_string(ip2long($_SERVER['REMOTE_ADDR'])) . "' BETWEEN `ip_start` AND `ip_end` AND `status` = 'Enabled'") or die($mysqli->error);
	
	if ($q->num_rows == 0) {
		return false;
	} else {
		return true;
	}
	
	$q->free();
	$mysqli->close();
}

function browser($ua)
{
	if (preg_match('/firefox/i', $ua)) {
		preg_match('/Firefox\/([0-9\.]+)(\+)?/i', $ua, $b);
		$return['name'] = 'Firefox';
		unset($b[0]);
		$return['version'] = implode('', $b);
	} elseif (preg_match('/chrome/i', $ua)) {
		preg_match('/Chrome\/([0-9\.]+)(\+)?/i', $ua, $b);
		$return['name'] = 'Chrome';
		unset($b[0]);
		$return['version'] = implode('', $b);
	} else {
		$return['name'] = 'Other';
		$return['version'] = 'Other';
	}
	return $return;
}

$token = $_COOKIE['vc_token'];

$q = $mysqli->query("SELECT `auth`.`user`, `auth`.`type`, `auth`.`centre`, `auth`.`status`, `auth`.`first`, `auth`.`last`, `auth`.`alias`, `auth`.`pass_reset` FROM `vericon`.`auth`, `vericon`.`current_users` WHERE `current_users`.`token` = '" . $mysqli->real_escape_string($token) . "' AND `current_users`.`user` = `auth`.`user`") or die($mysqli->error);
$ac = $q->fetch_assoc();

$browser = browser($_SERVER['HTTP_USER_AGENT']);
$referer = $_SERVER['SERVER_NAME'] . "/login/";
$referer_check = split("//", $_SERVER['HTTP_REFERER']);

if (!CheckAccess() || $browser["name"] != "Firefox" || $browser["version"] < 17 || $q->num_rows == 0 || $ac["status"] != "Enabled" || $referer_check[1] != $referer)
{
	header('Location: /');
	exit;
}

$q->free();

$dateTimeKolkata = new DateTime("now", new DateTimeZone("Asia/Kolkata"));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>VeriCon :: Main</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../jquery/css/vc-theme/jquery-ui.custom.min.css">
<script type="text/javascript" src="../jquery/js/jquery.js"></script>
<script type="text/javascript" src="../jquery/js/jquery-ui.custom.min.js"></script>
<script type="text/javascript" src="../js/blockUI.js"></script>
<script type="text/javascript" src="../js/jgrowl.js"></script>
<script type="text/javascript" src="../js/highcharts.js"></script>
<link type="text/css" rel="stylesheet" href="../css/main.css" />
<link type="text/css" rel="stylesheet" href="../css/jgrowl.css" />
<script>
function pad(number, length) {
	var str = '' + number;
	while (str.length < length) {
		str = '0' + str;
	}
	return str;
}

var TS_EST = "<?php echo time(); ?>";
function Header_Time_EST()
{
	var a = new Date(TS_EST*1000);
	var hour = a.getHours();
	var min = a.getMinutes();
	var period = hour >= 12 ? 'PM' : 'AM';
	hour = hour % 12;
	hour = hour ? hour : 12;
	var time = pad(hour,2) + ':' + pad(min,2) + ' ' + period;
	$( "#header_time_est" ).html(time);
	TS_EST++;
}

var TS_IST = "<?php echo strtotime($dateTimeKolkata->format("Y-m-d H:i:s")); ?>";
function Header_Time_IST()
{
	var a = new Date(TS_IST*1000);
	var hour = a.getHours();
	var min = a.getMinutes();
	var period = hour >= 12 ? 'PM' : 'AM';
	hour = hour % 12;
	hour = hour ? hour : 12;
	var time = pad(hour,2) + ':' + pad(min,2) + ' ' + period;
	$( "#header_time_ist" ).html(time);
	TS_IST++;
}

function Update_Clock()
{
	$.post("/source/clock.php", function(data)
	{
		clock = data.split(";");
		$( "#header_date_est" ).html(clock[0]);
		TS_EST = clock[1];
		$( "#header_date_ist" ).html(clock[2]);
		TS_IST = clock[3];
	});
}
</script>
<script>
function V_Loading_Start()
{
	$.blockUI({ 
		message: '<div class="loading_message"><p><img src="../images/v_loading.gif"></p><b>Loading...</b></div>',
		overlayCSS: {
			cursor: 'default'
		},
		css: { 
			border: 'none',
			padding: '15px',
			backgroundColor: '#000',
			'border-radius': '10px',
			'-webkit-border-radius': '10px',
			'-moz-border-radius': '10px',
			opacity: .5,
			color: '#fff',
			cursor: 'default'
		}
	});
}

function V_Loading_End()
{
	$.unblockUI();
}

var v_current_page_link = "";
var v_current_page_id = "";
var v_current_page_sub_id = "";

function V_Page_Load(id, sub_id, page_link)
{
	if (v_current_page_link != page_link)
	{
		if ($( ".blockUI" ).val() != "")
		{
			V_Loading_Start();
		}
		$( "#display" ).load(page_link, function(data, status, xhr){
			if (status == "error")
			{
				$(".loading_message").html("<p><b>An error occurred while loading the page.</b></p><p><b>Error: " + xhr.status + " " + xhr.statusText + "</b></p>");
				setTimeout(function() {
					V_Loading_End();
					if (xhr.status == 420 || xhr.status == 421)
					{
						V_Logout();
					}
				}, 2500);
			}
			else
			{
				if (v_current_page_id != "")
				{
					$( "#" + v_current_page_id ).removeClass("active");
				}
				if (v_current_page_sub_id != "")
				{
					$( "#" + v_current_page_sub_id ).removeClass("active");
				}
				$( "#" + id ).addClass("active");
				if (sub_id != "")
				{
					$( "#" + sub_id ).addClass("active");
				}
				v_current_page_id = id;
				v_current_page_sub_id = sub_id;
				v_current_page_link = page_link;
				V_Loading_End();
			}
		});
	}
}

function V_Page_Reload()
{
	if ($( ".blockUI" ).val() != "")
	{
		V_Loading_Start();
	}
	$( "#display" ).load(v_current_page_link, function(data, status, xhr){
		if (status == "error")
		{
			$(".loading_message").html("<p><b>An error occurred while loading the page.</b></p><p><b>Error: " + xhr.status + " " + xhr.statusText + "</b></p>");
			setTimeout(function() {
				V_Loading_End();
				if (xhr.status == 420 || xhr.status == 421)
				{
					V_Logout();
				}
			}, 2500);
		}
		else
		{
			V_Loading_End();
		}
	});
}

var v_current_portal = "";

function V_Menu_Load(portal)
{
	if (v_current_portal != portal)
	{
		$( "#ncv" ).load("/source/menu.php", { p: portal });
		v_current_portal = portal;
		
		if (portal == "ma") {
			$( "#header_logo" ).removeAttr("style");
			$( "#header_logo" ).removeAttr("onclick");
		} else {
			$( "#header_logo" ).attr("style","cursor:pointer");
			$( "#header_logo" ).attr("onclick","V_Page_Load('MA01','','/ma/index.php'); V_Menu_Load('ma');");
		}
	}
}

function V_Pass_Reset()
{
	if ($( ".blockUI" ).val() != "")
	{
		V_Loading_Start();
	}
	$( "#header_logo" ).removeAttr("style");
	$( "#header_logo" ).removeAttr("onclick");
	v_current_page_link = "/ma/pass_reset.php";
	$( "#display" ).load("/ma/pass_reset.php", function(data, status, xhr){
		if (status == "error")
		{
			$(".loading_message").html("<p><b>An error occurred while loading the page.</b></p><p><b>Error: " + xhr.status + " " + xhr.statusText + "</b></p>");
			setTimeout(function() {
				V_Loading_End();
				if (xhr.status == 420 || xhr.status == 421)
				{
					V_Logout();
				}
			}, 2500);
		}
		else
		{
			V_Loading_End();
		}
	});
	
}

openWins = new Array();

function V_Mail_Open()
{
	openWins[1]= window.open("/auth/mail_login.php", "V_Mail");
}
</script>
<script>
function V_Logout()
{
	$.blockUI({ 
		message: '<div class="loading_message"><p><img src="../images/v_loading.gif"></p><b>Logging Out...</b></div>',
		overlayCSS: {
			cursor: 'default'
		},
		css: { 
			border: 'none',
			padding: '15px',
			backgroundColor: '#000',
			'border-radius': '10px',
			'-webkit-border-radius': '10px',
			'-moz-border-radius': '10px',
			opacity: .5,
			color: '#fff',
			cursor: 'default'
		}
	});
	
	$.post("/auth/logout.php", function(data) {
		if (openWins[1] && !openWins[1].closed) {
			openWins[1].close();
		}
		setTimeout(function() {
			V_Loading_End();
			$(window).unbind("beforeunload");
			window.location = "/logout";
		}, 500);
	}).error( function(xhr, text, err) {
		$(".loading_message").html("<p><b>An error occurred while performing this action.</b></p><p><b>Error: " + xhr.status + " " + xhr.statusText + "</b></p>");
		setTimeout(function() {
			V_Loading_End();
		}, 2500);
	});
}
</script>
<script>
function V_Broadcast()
{
	$( "#broadcast" ).load("/source/broadcast.php", function(data, status, xhr) {
		if (status == "error")
		{
			if (xhr.status == 403 || xhr.status == 420 || xhr.status == 421)
			{
				V_Logout();
			}
		}
	});
}

function V_Mail_Check()
{
	$( "#inbox" ).load("/source/mail.php", function(data, status, xhr) {
		if (status == "error")
		{
			if (xhr.status == 403 || xhr.status == 420 || xhr.status == 421)
			{
				V_Logout();
			}
		}
	});
}

var v_notification_count = 0;

function V_Notification_Open()
{
	v_notification_count++;
	if (v_notification_count == 0) {
		window.document.title = "VeriCon :: Main";
	} else {
		window.document.title = "VeriCon :: Main (" + v_notification_count + ")";
		$( "#broadcast" ).html('<audio autoplay src="/audio/notify.ogg"></audio>');
	}
}

function V_Notification_Close()
{
	v_notification_count--;
	if (v_notification_count == 0) {
		window.document.title = "VeriCon :: Main";
	} else {
		window.document.title = "VeriCon :: Main (" + v_notification_count + ")";
	}
}
</script>
<script>
Header_Time_EST();
Header_Time_IST();
setInterval("Header_Time_EST()", 1000);
setInterval("Header_Time_IST()", 1000);
setInterval("Update_Clock()", 900000);
setInterval("V_Broadcast()", 120000);
setInterval("V_Mail_Check()", 180000);
$.jGrowl.defaults.closer = false;
$.jGrowl.defaults.closeTemplate = '<img src="/images/close_icon.png" width="16px" height="16px">';

$(window).bind('beforeunload', function() {
	return 'Are you sure you want to leave?';
});

$(window).keydown(function(event) {
	if((event.ctrlKey && event.keyCode == 82) || (event.ctrlKey && event.keyCode == 116) || event.keyCode == 116) {
		V_Page_Reload();
		event.preventDefault();
	}
});
</script>
</head>

<body>
<div id="preload">
<img src="/images/v_loading.gif" /><img src="/images/next_btn.png" /><img src="/images/next_btn_hover.png" /><img src="/images/back_btn.png" /><img src="/images/back_btn_hover.png" /><img src="/images/older_btn.png" /><img src="/images/older_btn_hover.png" /><img src="/images/newer_btn.png" /><img src="/images/newer_btn_hover.png" /><img src="/images/change_password_icon.png" /><img src="/images/checkbox.png" /><img src="/images/checkbox_checked.png" /><img src="/images/close_icon.png" /><img src="/images/delete_icon.png" /><img src="/images/disable_icon.png" /><img src="/images/down.png" /><img src="/images/edit_icon.png" /><img src="/images/enable_icon.png" /><img src="/images/loading_icon.gif" /><img src="/images/logout_icon.png" /><img src="/images/notes_icon.png" /><img src="/images/radio.png" /><img src="/images/radio_checked.png" /><img src="/images/search_icon.png" /><img src="/images/up.png" /><img src="/images/logout.png" />
</div>
<div id="broadcast">
<script>
V_Broadcast();
</script>
</div>

<div id="top_repeat"></div>
<div id="wrapper">
<div id="header_inner">
<div class="logo"><img id="header_logo" src="../images/logo.png" border="0" onclick="V_Page_Load('MA01','','/ma/index.php'); V_Menu_Load('ma');" style="cursor:pointer;" /></div>
<div class="rightSide">
<ul style="padding-right:60px; padding-top:8px;">
<li><a onclick="V_Logout()">logout</a></li>
<li><img src="../images/next.jpg" style="margin-top:2px;" /></li>
<li><img src="../images/top.jpg" /></li>
<li><span style="color:#FFF;"><?php echo $ac["user"]; ?></span></li>
<li><img src="../images/agency.jpg" style="margin-top:2px;" /></li>
</ul>
<ul style="padding-top:25px;">
<li style="display:inline-block;">:&nbsp;&nbsp;<span id="header_date_ist"><?php echo $dateTimeKolkata->format("d/m/Y"); ?></span> - <span id="header_time_ist"><?php echo $dateTimeKolkata->format("h:i A"); ?></span></li>
<li style="width:56px;">Kolkata</li>
<li style="padding-top:1px;"><img src="../images/clock.png" /></li>
</ul>
<ul>
<li style="display:inline-block;">:&nbsp;&nbsp;<span id="header_date_est"><?php echo date("d/m/Y"); ?></span> - <span id="header_time_est"><?php echo date("h:i A"); ?></span></li>
<li style="width:56px;">Melbourne</li>
<li style="padding-top:1px;"><img src="../images/clock.png" /></li>
</ul>
</div>
</div>
<div id="ncv">
<script>
<?php
if (date("Y-m-d", strtotime($ac["pass_reset"])) > date("Y-m-d", strtotime("-90 days")))
{
	echo 'V_Menu_Load("ma");';
}
?>
</script>
</div>

<div id="contents_inner">
<div id="display">
<script>
<?php
if (date("Y-m-d", strtotime($ac["pass_reset"])) <= date("Y-m-d", strtotime("-90 days"))) {
	echo 'V_Pass_Reset()';
} else {
	echo 'V_Page_Load("MA01","","/ma/index.php");';
}
?>
</script>
</div>
</div>
<div id="footer">
<table width="100%">
<tr>
<td colspan="2" align="center">
<table width="100%">
<tr>
<td width="269px" height="2px"><div class="line_left"></div></td>
<td height="2px"><div class="line_repeat"></div></td>
<td width="269px" height="2px"><div class="line_right"></div></td>
</tr>
</table>
</td>
</tr>
<tr>
<td class="left_f" align="left" valign="top">Copyrights 2011-<?php echo date("Y"); ?> | All Rights Reserved @ VeriCon<br />Designed &amp; Developed by Team VeriCon</td>
<td class="right_f" align="right" valign="top">
<img src="../images/footer_logo.png" border="0" />
<?php
$q = $mysqli->query("SELECT `subject` FROM `vericon`.`updates` ORDER BY `id` DESC LIMIT 1") or die($mysqli->error);
$ver = $q->fetch_row();
$q->free();
$mysqli->close();
?>
<div style="width:133px; text-align:right; margin:-5px 30px 0 0;">
<?php echo $ver[0]; ?>
</div>
</td>
</tr>
</table>
</div>
</div>
</body>
</html>