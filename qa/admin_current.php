<script>
function Log_out(hash,user)
{
	var hash = hash,
		user = user;
	
	$.get("../admin/users_submit.php?method=logout", { hash: hash, username: user},
	function(data) {
		location.reload();
	});
}
</script>
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
	$q2 = mysql_query("SELECT * FROM auth WHERE user = '$current[user]' AND type = 'QA'") or die(mysql_error());
	$details = mysql_fetch_assoc($q2);
	
	if(mysql_num_rows($q2) != 0)
	{
		echo "<tr>";
		echo "<td>" . $details["user"] . "</td>";
		echo "<td>" . $details["first"] . " " . $details["last"] . "</td>";
		echo "<td>" . $details["type"] . "</td>";
		echo "<td>" . date("d/m/Y h:iA", strtotime($current["timestamp"])) . "</td>";
		echo "<td><a onclick='Log_out(\"$current[hash]\",\"$current[user]\")' style='cursor:pointer; text-decoration:underline;'>Logout</a></td>";
		echo "</tr>";
	}
}
?>
</tbody>
</table>
</div>