<?php
mysql_connect('localhost','vericon','18450be');
?>
<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/call_centres_header.png" width="130" height="25" /></td>
<td align="right" style="padding-right:10px;"><button onclick="Add_Centre()" class="btn2">Add Centre</button></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="98%" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th style="text-align:center;">Centre</th>
<th style="text-align:center;">Campaign</th>
<th style="text-align:center;">Type</th>
<th style="text-align:center;">Lead Validation</th>
<th style="text-align:center;">Status</th>
<th style="text-align:center;" colspan="2">Edit</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT * FROM vericon.centres ORDER BY centre ASC") or die(mysql_error());
while ($centre = mysql_fetch_assoc($q))
{
	if ($centre["leads"] == 1) { $lead = "Active"; } else { $lead = "Inactive"; }
	
	echo "<tr>";
	echo "<td style='text-align:center'>" . $centre["centre"] . "</td>";
	echo "<td style='text-align:center'>" . $centre["campaign"] . "</td>";
	echo "<td style='text-align:center'>" . $centre["type"] . "</td>";
	echo "<td style='text-align:center'>" . $lead . "</td>";
	echo "<td style='text-align:center'>" . $centre["status"] . "</td>";
	echo "<td style='text-align:center'><button onclick='Edit(\"$centre[centre]\")' class='icon_edit' title='Edit'></button></td>";
	if($centre["status"] == "Enabled")
	{
		echo "<td style='text-align:center;'><button onclick='Disable(\"$centre[centre]\")' class='icon_cancel' title='Disable'></button></td>";
	}
	else
	{
		echo "<td style='text-align:center;'><button onclick='Enable(\"$centre[centre]\")' class='icon_check' title='Enable'></button></td>";
	}
	echo "</tr>";
}
?>
</tbody>
</table>
</div></center>