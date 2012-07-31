<?php
mysql_connect('localhost','vericon','18450be');
?>
<p><img src="../images/current_users_header.png" width="150" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p>

<center><div id="users-contain" class="ui-widget" style="max-height:550px; width:98%; overflow:auto;">
<table id="users" class="ui-widget ui-widget-content" width="100%" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th style='text-align:left;'>Username</th>
<th style='text-align:left;'>Name</th>
<th>Account Access</th>
<th>Current Page</th>
<th>Logout</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT * FROM vericon.currentuser ORDER BY user ASC") or die(mysql_error());

while($current = mysql_fetch_assoc($q))
{
	$q2 = mysql_query("SELECT * FROM vericon.auth WHERE user = '$current[user]'") or die(mysql_error());
	$details = mysql_fetch_assoc($q2);
	
	if ($details["type"] == "Sales")
	{
		$type = $details["type"] . " - " . $details["centre"];
	}
	else
	{
		$type = $details["type"];
	}
	
	echo "<tr>";
	echo "<td style='text-align:left;'>" . $details["user"] . "</td>";
	echo "<td style='text-align:left;'>" . $details["first"] . " " . $details["last"] . "</td>";
	echo "<td>" . $type . "</td>";
	echo "<td>" . $current["current_page"] . "</td>";
	echo "<td><button onclick='Log_out(\"$current[hash]\",\"$current[user]\")' class='icon_logout' title='Logout'></button></td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div></center>