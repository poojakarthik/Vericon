<?php
mysql_connect('localhost','vericon','18450be');

$user = $_GET["user"];

$q0 = mysql_query("SELECT * FROM vericon.auth WHERE user = '$user'") or die(mysql_error());
$ac = mysql_fetch_assoc($q0);

$q = mysql_query("SELECT campaign FROM vericon.centres WHERE centre = '$ac[centre]'");
$cam = mysql_fetch_assoc($q);

$q1 = mysql_query("SELECT designation FROM vericon.timesheet_designation WHERE user = '$ac[user]'") or die(mysql_error());
$designation = mysql_fetch_row($q1);
?>

<p><img src="../images/agent_details_header.png" width="145" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" alt="line" /></p>

<table width="100%" border="0">
<tr>
<td width="85px">Agent Name: </td>
<td><b><?php echo $ac["first"] . " " . $ac["last"] . " (" . $ac["alias"] . ")"; ?></b></td>
</tr>
<tr>
<td>Centre: </td>
<td><b><?php echo $ac["centre"]; ?></b></td>
</tr>
<tr>
<td width="85px">Designation: </td>
<td><b><?php echo $designation[0]; ?></b></td>
</tr>
<tr>
<td>Campaign: </td>
<td><b><?php echo $cam["campaign"]; ?></b></td>
</tr>
</table><br />

<table width="100%" border="0">
<tr valign="top">
<td width="65%">
<p><img src="../images/sale_stats_header.png" width="110" height="25" /></p>
<p><img src="../images/line.png" width="95%" height="9" alt="line" /></p>
<p><img src="../sales/chart.php?user=<?php echo $ac["user"]; ?>" style="margin-left:5px;" /></p>
</td>
<td width="35%">
<p><img src="../images/top_ten_header.png" width="80" height="25" /></p>
<p><img src="../images/line.png" width="95%" height="9" alt="line" /></p>
<div id="users-contain" class="ui-widget" style="margin-left:3px;">
<table id="users" class="ui-widget ui-widget-content" width="90%" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th align="left">Name</th>
<th width="20%"><select id="method" style="border:0; height:auto; margin:0; padding:0; width:75px; background:none; -webkit-appearance: none; color:#EAF5F7; font-weight:bold;" onchange="Top_Ten()">
<option style="background-color:#2191c0;">Today</option>
<option style="background-color:#2191c0;">Week</option>
<option style="background-color:#2191c0;" selected="selected">Overall</option>
</select></th>
</tr>
</thead>
<tbody id="top_ten" align="center">
<script>
var method = $( "#method" );
$( "#top_ten" ).load('top_ten.php?method=' + method.val() + '&centre=<?php echo $ac["centre"]; ?>');
</script>
</tbody>
</table>
</div>
</td>
</tr>
</table>