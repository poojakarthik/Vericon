<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo "VeriCon :: " . $portal_name["name"] . " :: " . $page_id["name"]; ?></title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<link rel="stylesheet" href="../jquery/css/custom-theme/jquery-ui-1.8.20.custom.css">
<script src="../jquery/js/jquery-1.7.2.min.js"></script>
<script src="../jquery/js/jquery-ui-1.8.20.custom.min.js"></script>
<script type="text/javascript" src="../js/ddsmoothmenu.js"></script>
<script type="text/javascript">
ddsmoothmenu.init({
	mainmenuid: "smoothmenu1", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})
</script>
<script type="text/javascript">
function Logout()
{
	window.location = "../auth/logout.php";
}
</script>
<?php
$time = date("H:i:s");
$time = explode(":", $time);

$t = 0;
$t += ($time[0] * 60 * 60);
$t += ($time[1] * 60);
$t += ($time[2]);
?>
<script>
function pad(number, length) {
	var str = '' + number;
	
	while (str.length < length) {
		str = '0' + str;
	}
	
	return str;
}

var Seconds=<?php echo $t; ?>;
setInterval( function() {
	var timeString = "";
	var secs = parseInt(Seconds % 60);
	var mins = parseInt(Seconds / 60 % 60);
	var hours = parseInt(Seconds / 3600 % 24);
	var days = parseInt(Seconds / 86400);
	period = ((hours > 11) ? " PM" : " AM");
	if (hours > 12)
	{
		hours = hours - 12;
	}
	hours = pad(hours,2);
	mins = pad(mins,2);
	secs = pad(secs,2);
	
	timeString = hours + ":" + mins + ":" + secs + " " + period;
	var span_el = document.getElementById("time");
	var replaceWith = document.createTextNode(timeString);
	span_el.replaceChild(replaceWith, span_el.childNodes[0]);
	Seconds++;
},1000);

setInterval(function() {
$.get("../source/clock.php", function(time) { Seconds = time; });
}, 300000);
</script>
</head>

<body>
<div id="main_wrapper">
<div id="innerpage_logo">
<a href="../"><img src="../images/logo.png"  width="252" height="65" alt="logo" style="border-style:none;" /></a>
</div>
<div id="logout">
<table width="100%" height="17px" border="0" style="padding-right:22px; margin-top:-2px; margin-bottom:-2px;">
<tr valign="bottom">
<td align="right"><span class="clock" style="font-family:Arial, Helvetica, sans-serif; font-size:11px; color:#0066cc; font-weight:bolder; margin-right:-10px;"><?php echo date("d/m/Y"); ?> <span id="time"><?php echo date("h:i:s A"); ?></span></span></td>
</tr>
</table>
<table width="100%" height="24px" border="0" style="padding-right:20px;">
<tr valign="bottom">
<td align="right"><span style="font-family:'Trebuchet MS', Arial, Helvetica, sans-serif; font-weight:lighter; font-size:11px; color:#666;"><?php echo $ac["user"]; ?></span></td>
</tr>
</table>
<table width="100%" height="24px" border="0" style="padding-right:23px;">
<tr valign="bottom">
<td align="right" valign="middle"><img src="../images/logout_icon.png" /> <a onclick="Logout()">Logout</a></td>
</tr>
</table>
</div>
<div id="menu">
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<?php
$q = mysql_query("SELECT * FROM vericon.portals_pages WHERE portal = '$d[1]' AND status = '1' AND sub_level = '0' ORDER BY level") or die(mysql_error());
while ($pages = mysql_fetch_assoc($q))
{
	if (in_array($pages["id"], $access_pages) || $acc["admin"] == true)
	{
		if ($pages["link"] != "")
		{
			$pages_link[$pages["level"]] .= '<li><a href="../' . $d[1] . '/' . $pages["link"] . '">' . strtoupper($pages["name"]) . '</a></li>';
		}
		else
		{
			$pages_link[$pages["level"]] .= '<li><span style="padding:8px 25px; cursor:pointer; display:block; margin:inherit;">' . strtoupper($pages["name"]) . '</span><ul>';
			$q2 = mysql_query("SELECT * FROM vericon.portals_pages WHERE portal = '$d[1]' AND status = '1' AND level = '$pages[level]' AND sub_level > '0' ORDER BY sub_level") or die(mysql_error());
			while ($sub_pages = mysql_fetch_assoc($q2))
			{
				if (in_array($sub_pages["id"], $access_pages) || $acc["admin"] == true)
				{
					$pages_link[$pages["level"]] .= '<li><a href="../' . $d[1] . '/' . $sub_pages["link"] . '">' . strtoupper($sub_pages["name"]) . '</a></li>';
				}
			}
			$pages_link[$pages["level"]] .= '</ul></li>';
		}
	}
}
$links = implode('<li style="padding-top:8px;">|</li>',$pages_link);

echo $links;
?>
</ul>
</div>
</div>

<script>
keypressed = null;

$(window).keydown(function(event) {
    keypressed = event.keyCode;
});

$(window).keyup(function(event) {
    keypressed = null;
});

$("a" , "#menu").click(function(event) {
    // Don't work magic if command keys or ctrl keys held down
    if (keypressed == 91 || keypressed == 92 || keypressed == 17) {
        return true;
    }
	var transition_link = $(this).attr("href");
    event.preventDefault();
    $( "#display" ).hide('blind', '' , 'slow', function() {
        location.href = transition_link;
    });
});
</script>

<div id="text">