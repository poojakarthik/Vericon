<?php
mysql_connect('localhost','vericon','18450be');
?>

<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/telco_groups_header.png" width="135" height="25"></td>
<td align="right" style="padding-right:10px;"><button onclick="Add_Group()" class="btn2">Add Group</button></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" alt="line" /></td>
</tr>
</table>

<center><div id="users-contain" class="ui-widget" style="width:98%;">
<table id="users" class="ui-widget ui-widget-content sortable" width="100%" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th style="text-align:center;">ID</th>
<th style="text-align:center;">Name</th>
<th style="text-align:center;">Campaigns</th>
<th style="text-align:center;"></th>
</tr>
</thead>
<tbody align="center">
<?php
$q = mysql_query("SELECT * FROM vericon.groups ORDER BY id ASC") or die(mysql_error());
while ($groups = mysql_fetch_assoc($q))
{
	$q1 = mysql_query("SELECT COUNT(id) FROM vericon.campaigns WHERE `group` = '$groups[id]'") or die(mysql_error());
	$campaigns = mysql_fetch_row($q1);
	
	echo "<tr>";
	echo "<td style='text-align:center;'>" . $groups["id"] . "</td>";
	echo "<td style='text-align:center;'>" . $groups["name"] . "</td>";
	echo "<td style='text-align:center;'>" . $campaigns[0] . "</td>";
	echo "<td style='text-align:center'><button onclick='View(\"$groups[id]\")' class='icon_view' title='View'></button></td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div></center>
<br />
<div id="display2">
</div>