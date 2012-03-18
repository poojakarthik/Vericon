<?php
include "../auth/iprestrict.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: Admin :: Logged In Users</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<?php
include "../source/jquery.php";
?>
<style>
	div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
	div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
</style>
<script>
function Log_out(hash,user)
{
	var hash = hash,
		user = user;
	
	$.get("users_submit.php?method=logout", { hash: hash, username: user},
	function(data) {
		location.reload();
	});
}
</script>
</head>

<body>
<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/admin_menu.php";
?>

<div id="text" class="demo">

<p><img src="../images/current_users_header.png" width="150" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p>

<div id="users-contain" class="ui-widget" style="height:550px; overflow:auto;">
<table id="users" class="ui-widget ui-widget-content" width="100%">
<thead>
<tr class="ui-widget-header ">
<th>Username</th>
<th>Name</th>
<th>Account Type</th>
<th colspan="2">Log In Time</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT * FROM currentuser ORDER BY timestamp DESC") or die(mysql_error());

while($current = mysql_fetch_assoc($q))
{
	$q2 = mysql_query("SELECT * FROM auth WHERE user = '$current[user]'") or die(mysql_error());
	$details = mysql_fetch_assoc($q2);
	
	echo "<tr>";
	echo "<td>" . $current["user"] . "</td>";
	echo "<td>" . $details["first"] . " " . $details["last"] . "</td>";
	echo "<td>" . $details["type"] . "</td>";
	echo "<td>" . date("d/m/Y h:iA", strtotime($current["timestamp"])) . "</td>";
	echo "<td><a onclick='Log_out(\"$current[hash]\",\"$current[user]\")' style='cursor:pointer; text-decoration:underline;'>Logout</a></td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div>

</div>
</div> 
<?php
include "../source/footer.php";
?>
</body>
</html>