<?php
mysql_connect('localhost','vericon','18450be');

$date = $_GET["date"];
$li = 0;
$ta = 0;
?>
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th colspan="9">Logged In Today</th>
</tr>
<tr class="ui-widget-header ">
<th style="text-align:left;">Agent Name</th>
<th>Start Time</th>
<th>End Time</th>
<th>Hours</th>
<th>Approved</th>
<th>Cancelled</th>
<th>Upgrades</th>
<th>DD</th>
<th>Status</th>
</tr>
</thead>
<tbody>
<?php
	$q = mysql_query("SELECT * FROM vericon.auth WHERE type LIKE '%Welcome%' AND status = 'Enabled' ORDER BY user ASC") or die(mysql_error());

	while ($user = mysql_fetch_assoc($q))
	{
		$approved = 0;
		$cancelled = 0;
		$upgrade = 0;
		
		$ta++;
		$q0 = mysql_query("SELECT * FROM vericon.log_login WHERE user = '$user[user]' AND DATE(timestamp) = '$date'") or die(mysql_error());
		if (mysql_fetch_row($q0) != 0)
		{
			$li++;
			$q1 = mysql_query("SELECT * FROM vericon.timesheet WHERE user = '$user[user]' AND date = '$date'") or die(mysql_error());
			$data = mysql_fetch_assoc($q1);
			
			$q2 = mysql_query("SELECT status, COUNT(id) FROM vericon.welcome WHERE user = '$user[user]' AND DATE(timestamp) = '$date' GROUP BY status") or die(mysql_error());
			
			while($data2 = mysql_fetch_row($q2))
			{		
				if ($data2[0] == "Approve") { $approved = $data2[1]; }
				elseif ($data2[0] == "Cancel") { $cancelled = $data2[1]; }
				elseif ($data2[0] == "Upgrade") { $upgrade = $data2[1]; }
			}
			
			$q3 = mysql_query("SELECT COUNT(id) FROM vericon.welcome WHERE user = '$user[user]' AND DATE(timestamp) = '$date' AND dd = '1'") or die(mysql_error());
			$dd = mysql_fetch_row($q3);
			
			$icon = "<img src='../images/question_mark_icon.png'>";
			
			if ($data["start"] == "") { $start = ""; } else { $start = date("H:i", strtotime($data["start"])); }
			if ($data["end"] == "") { $end = ""; } else { $end = date("H:i", strtotime($data["end"])); }
			
			if ($data["start"] != "" && $data["end"] != "" && $data["hours"] != "")
			{
				$icon = "<img src='../images/check_icon.png'>";
			}
			
			echo "<tr>";
			echo "<td style='text-align:left;'><input type='hidden' id='save_user' value='$user[user]'>" . $user["first"] . " " . $user["last"] ."</td>";
			echo "<td><input type='text' id='$user[user]_start' value='$start' onChange='Edit(\"start\",\"$user[user]\")' style='height:15px; width:40px;'></td>";
			echo "<td><input type='text' id='$user[user]_end' value='$end' onChange='Edit(\"end\",\"$user[user]\")' style='height:15px; width:40px;'></td>";
			echo "<td><input type='text' id='$user[user]_hours' value='$data[hours]' onChange='Edit(\"hours\",\"$user[user]\")' style='height:15px; width:40px;'></td>";
			echo "<td>" . $approved . "</td>";
			echo "<td>" . $cancelled . "</td>";
			echo "<td>" . $upgrade . "</td>";
			echo "<td>" . $dd[0] . "</td>";
			echo "<td><span id='$user[user]_icon'>$icon</span></td>";
			echo "</tr>";
		}
	}
	
	if ($li == 0)
	{
		echo "<tr>";
		echo "<td colspan='9'>No Agents Logged in Today!</td>";
		echo "</tr>";
	}
?>
</tbody>
<?php
if ($ta >= $li || $li == 0)
{
?>
<thead>
<tr class="ui-widget-header ">
<th colspan="9">Not Logged In Today</th>
</tr>
<tr class="ui-widget-header ">
<th style="text-align:left;">Agent Name</th>
<th>Start Time</th>
<th>End Time</th>
<th>Hours</th>
<th>Approved</th>
<th>Cancelled</th>
<th>Upgrades</th>
<th>DD</th>
<th>Status</th>
</tr>
</thead>
<tbody>
<?php
	$q = mysql_query("SELECT * FROM vericon.auth WHERE type LIKE '%Welcome%' AND status = 'Enabled' ORDER BY user ASC") or die(mysql_error());

	while ($user = mysql_fetch_assoc($q))
	{
		$approved = 0;
		$cancelled = 0;
		$upgrade = 0;
		
		$q0 = mysql_query("SELECT * FROM vericon.log_login WHERE user = '$user[user]' AND DATE(timestamp) = '$date'") or die(mysql_error());
		if (mysql_fetch_row($q0) == 0)
		{
			$q1 = mysql_query("SELECT * FROM vericon.timesheet WHERE user = '$user[user]' AND date = '$date'") or die(mysql_error());
			$data = mysql_fetch_row($q1);
			
			$q2 = mysql_query("SELECT status, COUNT(id) FROM vericon.welcome WHERE user = '$user[user]' AND DATE(timestamp) = '$date' GROUP BY status") or die(mysql_error());
			
			while($data2 = mysql_fetch_row($q2))
			{		
				if ($data2[0] == "Approve") { $approved = $data2[1]; }
				elseif ($data2[0] == "Cancel") { $cancelled = $data2[1]; }
				elseif ($data2[0] == "Upgrade") { $upgrade = $data2[1]; }
			}
			
			$q3 = mysql_query("SELECT COUNT(id) FROM vericon.welcome WHERE user = '$user[user]' AND DATE(timestamp) = '$date' AND dd = '1'") or die(mysql_error());
			$dd = mysql_fetch_row($q3);
			
			$icon = "<img src='../images/question_mark_icon.png'>";
			
			if ($data["start"] == "") { $start = ""; } else { $start = date("H:i", strtotime($data["start"])); }
			if ($data["end"] == "") { $end = ""; } else { $end = date("H:i", strtotime($data["end"])); }
			
			if ($data["start"] != "" && $data["end"] != "" && $data["hours"] != "")
			{
				$icon = "<img src='../images/check_icon.png'>";
			}
			
			echo "<tr>";
			echo "<td style='text-align:left;'><input type='hidden' id='save_user' value='$user[user]'>" . $user["first"] . " " . $user["last"] ."</td>";
			echo "<td><input type='text' id='$user[user]_start' value='$start' onChange='Edit(\"start\",\"$user[user]\")' style='height:15px; width:40px;'></td>";
			echo "<td><input type='text' id='$user[user]_end' value='$end' onChange='Edit(\"end\",\"$user[user]\")' style='height:15px; width:40px;'></td>";
			echo "<td><input type='text' id='$user[user]_hours' value='$data[hours]' onChange='Edit(\"hours\",\"$user[user]\")' style='height:15px; width:40px;'></td>";
			echo "<td>" . $approved . "</td>";
			echo "<td>" . $cancelled . "</td>";
			echo "<td>" . $upgrade . "</td>";
			echo "<td>" . $dd[0] . "</td>";
			echo "<td><span id='$user[user]_icon'>$icon</span></td>";
			echo "</tr>";
		}
	}
?>
</tbody>
</table>
</div></center>
<?php
}
?>

<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><button onClick="Cancel()" class="btn">Cancel</button></td>
<td align="right" style="padding-right:5px;"><button id="save_btn" onClick="Save()" class="btn">Save</button></td>
</tr>
</table>