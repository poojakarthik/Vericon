<?php
mysql_connect('localhost','vericon','18450be');

$centres = explode(",", $_GET["centres"]);
?>

<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/agent_report_header.png" width="140" height="25" /></td>
<td align="right" style="padding-right:10px;"><button onClick="Search()" class="btn2">Search</button></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px; width:98%">
<?php //melbourne
for ($i = 0; $i < count($centres); $i++)
{
    $q0 = mysql_query("SELECT centre FROM vericon.centres WHERE centre = '$centres[$i]' AND type = 'Self'") or die(mysql_error());
    if (mysql_num_rows($q0) != 0)
    {
        $self[$centres[$i]] = 1;
    }
}

if (array_sum($self) > 0)
{
    echo '<thead>';
    echo '<tr class="ui-widget-header ">';
    echo '<th colspan="4" style="text-align:center;">Melbourne</th>';
    echo '</tr>';
    echo '<tr class="ui-widget-header ">';
    echo '<th>Centre</th>';
	echo '<th>Team Leader</th>';
    echo '<th style="text-align:center;">Active Agents</th>';
	echo '<th></th>';
    echo '</tr>';
    echo '</thead>';
    echo '<tbody>';
}

for ($i = 0; $i < count($centres); $i++)
{
    if ($self[$centres[$i]] == 1)
    {
		$q0 = mysql_query("SELECT auth.first, auth.last FROM vericon.timesheet_designation,vericon.auth WHERE timesheet_designation.designation = 'Team Leader' AND auth.centre = '$centres[$i]' AND auth.status = 'Enabled' AND auth.user = timesheet_designation.user") or die(mysql_error());
		$t = mysql_fetch_row($q0);
		$tl = $t[0] . " " . $t[1];
		
		$q1 = mysql_query("SELECT COUNT(user) FROM vericon.auth WHERE centre = '$centres[$i]' AND status = 'Enabled'") or die(mysql_error());
		$active = mysql_fetch_row($q1);
		
		echo "<tr>";
		echo "<td>" . $centres[$i] . "</td>";
		echo "<td>" . $tl . "</td>";
		echo "<td style='text-align:center;'>" . $active[0] . "</td>";
		echo "<td style='text-align:center;'><button onclick='View_Agents(\"$centres[$i]\")' class='icon_view' title='View Agents'></button></td>";
		echo "</tr>";
    }
}
?>
</table>
</div></center>
<br>
<div id="display2">
</div>