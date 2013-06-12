<?php
mysql_connect('localhost','vericon','18450be');

$query = $_GET["query"];
$user = $_GET["user"];
?>
<center><table width="98%" height="575px">
<tr valign="top" height="95%">
<td>
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th width="13%">Username</th>
<th width="35%">Full Name</th>
<th style='text-align:center;' width="13%">Centre</th>
<th style='text-align:center;' width="13%">Designation</th>
<th style='text-align:center;' width="13%">Status</th>
<th colspan="3" style='text-align:center;' width="13%">Edit User</th>
</tr>
</thead>
<tbody>
<?php
if ($query == "")
{
	$page_link = "?page=" . $_GET["page"] . "&user=" . $user;
	$q = mysql_query("SELECT centres FROM vericon.operations WHERE user = '$user'") or die(mysql_error());
	$cen = mysql_fetch_row($q);
	if ($cen[0] == "All")
	{
		$q1 = mysql_query("SELECT centre FROM vericon.centres WHERE status = 'Enabled' ORDER BY centre ASC") or die(mysql_error());
		while ($centres = mysql_fetch_row($q1))
		{
			$c_q .= "centre = '$centres[0]' OR ";
		}
		$c_q = substr($c_q,0,-4);
	}
	elseif ($cen[0] == "Captive" || $cen[0] == "Self")
	{
		$q1 = mysql_query("SELECT centre FROM vericon.centres WHERE status = 'Enabled' AND type = '$cen[0]' ORDER BY centre ASC") or die(mysql_error());
		while ($centres = mysql_fetch_row($q1))
		{
			$c_q .= "centre = '$centres[0]' OR ";
		}
		$c_q = substr($c_q,0,-4);
	}
	else
	{
		$centres = explode(",",$cen[0]);
		for ($i = 0; $i < count($centres); $i++)
		{
			$c_q .= "centre = '$centres[$i]' OR ";
		}
		$c_q = substr($c_q,0,-4);
	}
	
	$check = mysql_query("SELECT * FROM vericon.auth WHERE " . $c_q) or die(mysql_error());
	$rows = mysql_num_rows($check);
	
	if($rows == 0)
	{
		echo "<tr>";
		echo "<td colspan='8'>No Users?!?!?!</td>";
		echo "</tr>";
	}
	else
	{
		$st = $_GET["page"]*15;
		$q = mysql_query("SELECT * FROM vericon.auth WHERE " . $c_q . " ORDER BY user ASC LIMIT $st , 15") or die(mysql_error());
		
		while($r = mysql_fetch_assoc($q))
		{
			$q1 = mysql_query("SELECT designation FROM vericon.timesheet_designation WHERE user = '$r[user]'") or die(mysql_error());
			$d = mysql_fetch_row($q1);
			
			echo "<tr>";
			echo "<td>" . $r["user"] . "</td>";
			echo "<td>" . $r["first"] . " " . $r["last"] . "</td>";
			echo "<td style='text-align:center;'>" . $r["centre"] . "</td>";
			echo "<td style='text-align:center;'>" . $d[0] . "</td>";
			echo "<td style='text-align:center;'>" . $r["status"] . "</td>";
			echo "<td style='text-align:center;'><button onclick='Modify_PW(\"$r[user]\")' class='icon_change_password' title='Change Password'></button></td>";
			echo "<td style='text-align:center;'><button onclick='Modify(\"$r[user]\")' class='icon_edit' title='Edit'></button></td>";
			if($r["status"] == "Enabled")
			{
				echo "<td style='text-align:center;'><button onclick='Disable(\"$r[user]\")' class='icon_cancel' title='Disable'></button></td>";
			}
			else
			{
				echo "<td style='text-align:center;'><button onclick='Enable(\"$r[user]\")' class='icon_check' title='Enable'></button></td>";
			}
			echo "</tr>";
		}
	}
}
else
{
	$page_link = "?query=" . $query;
	$q = mysql_query("SELECT * FROM vericon.auth WHERE user = '$query'") or die(mysql_error());
	$r = mysql_fetch_assoc($q);
	$rows = 1;
	
	$q1 = mysql_query("SELECT designation FROM vericon.timesheet_designation WHERE user = '$r[user]'") or die(mysql_error());
	$d = mysql_fetch_row($q1);
	
	echo "<tr>";
	echo "<td>" . $r["user"] . "</td>";
	echo "<td>" . $r["first"] . " " . $r["last"] . "</td>";
	echo "<td style='text-align:center;'>" . $r["centre"] . "</td>";
	echo "<td style='text-align:center;'>" . $d[0] . "</td>";
	echo "<td style='text-align:center;'>" . $r["status"] . "</td>";
	echo "<td style='text-align:center;'><button onclick='Modify_PW(\"$r[user]\")' class='icon_change_password' title='Change Password'></button></td>";
	echo "<td style='text-align:center;'><button onclick='Modify(\"$r[user]\")' class='icon_edit' title='Edit'></button></td>";
	if($r["status"] == "Enabled")
	{
		echo "<td style='text-align:center;'><button onclick='Disable(\"$r[user]\")' class='icon_cancel' title='Disable'></button></td>";
	}
	else
	{
		echo "<td style='text-align:center;'><button onclick='Enable(\"$r[user]\")' class='icon_check' title='Enable'></button></td>";
	}
	echo "</tr>";
}
?>
</tbody>
</table>
</div>
</td>
</tr>
<tr valign="bottom">
<td>
<table width="100%">
<tr>
<td align="left" width="40%">
<?php
if (($st - 15) < $rows && $_GET["page"] > 0)
{
    $page = $_GET["page"]-1;
    echo "<input type='button' onClick='Display(\"$page\")' class='back'>";
}
?>
</td>
<td align="center" width="20%">
<?php
$p = $_GET["page"] + 1;
$p_t = ceil($rows / 15);
echo $p . " of " . $p_t;
?>
</td>
<td align="right" width="40%">
<?php
if (($st + 15) < $rows)
{
	$page = $_GET["page"]+1;
	echo "<input type='button' onClick='Display(\"$page\")' class='next'>";
}
?>
</td>
</tr>
</table>
</td>
</tr>
</table></center>
<input type="hidden" id="page" value="<?php echo $_GET["page"]; ?>">
<input type="hidden" id="page_link" value="<?php echo $page_link; ?>">