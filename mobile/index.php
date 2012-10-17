<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>VeriCon :: Login</title>
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.1.0-rc.1/jquery.mobile-1.1.0-rc.1.min.css" />
<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
<script src="http://code.jquery.com/mobile/1.1.0-rc.1/jquery.mobile-1.1.0-rc.1.min.js"></script>
<script>
$("#login-form").live("submit", function(event) {
	event.preventDefault();
	
	var user = $( "#user" ),
		pass = $( "#pass" );
	
	$.get("auth/check.php", { user: user.val(), pass: pass.val() },
	function(data) {
	   if (data == "valid") {
		  $.get("auth/hash.php", { user: user.val() },
		  function(data) {
			 window.location = "main.php?token=" + data;
		  });
	   } else {
		  alert(data);
	   }
	});
});
</script>
</head>
<body>
<div data-role="page" data-theme="b">

<div data-role="header" data-position="inline" data-theme="b">
<h1>Vericon :: Login</h1>
</div>

<div data-role="content" data-theme="b">
<center>
<form id="login-form" data-ajax="false">
<table style="margin-top:20px; padding:10px;">
<tr>
<td>Username: </td>
<td><input type="text" id="user" value=""></td>
</tr>
<tr>
<td>Password: </td>
<td><input type="password" id="pass" value=""></td>
</tr>
<tr>
<td></td>
<td align="right"><button type="submit" id="login">Login</button></td>
</tr>
</table>
</form>
</center>
</div>

<div data-role="footer" data-position="inline" data-theme="b">
<h4 style="font-size:9px;">Copyright &copy; VeriCon | All Rights Reserved 2011-<?php echo date("Y"); ?><br/>
Designed & Developed by Team VeriCon</h4>
</div>

</div>
</body>
</html>
