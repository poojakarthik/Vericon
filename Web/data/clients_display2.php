<?php
mysql_connect('localhost','vericon','18450be');

$group = $_GET["group"];
?>

<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/telco_campaigns_header.png" width="170" height="25"></td>
<td align="right" style="padding-right:10px;"><b><?php echo $group; ?></b></td>
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
<th style="text-align:center;">Plan Matrix</th>
</tr>
</thead>
<tbody align="center">
<?php
$q = mysql_query("SELECT * FROM vericon.campaigns WHERE `group` = '" . mysql_real_escape_string($group) . "' ORDER BY id ASC") or die(mysql_error());
if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td style='text-align:center;' colspan='2'>No Campaigns!</td>";
	echo "</tr>";
}
while ($campaigns = mysql_fetch_assoc($q))
{	
	echo "<tr>";
	echo "<td style='text-align:center;'>" . $campaigns["id"] . "</td>";
	echo "<td style='text-align:center;'>" . $campaigns["campaign"] . "</td>";
	echo "<td style='text-align:center'><button onclick='View_Matrix(\"$campaigns[id]\")' class='icon_view' title='View'></button></td>";
	echo "</tr>";
}
?>
</tbody>
</table>
</div></center>
<table width="100%">
<tr>
<td align="right" style="padding-right:10px;"><button onclick="Add_Campaign()" class="btn">Add Campaign</button></td>
</tr>
</table>

<div id="display2">
</div>