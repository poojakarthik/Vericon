<?php
mysql_connect('localhost','vericon','18450be');

$centre = $_GET["centre"];
$date = $_GET["date"];
$li = 0;
$ta = 0;
?>
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th colspan="8">Logged In Today</th>
</tr>
<tr class="ui-widget-header ">
<th style="text-align:left;">Agent Name</th>
<th style="text-align:left;">Designation</th>
<th>Start Time</th>
<th>End Time</th>
<th>Hours</th>
<th>Sales</th>
<th>Bonus</th>
<th>Status</th>
</tr>
</thead>
<tbody>
<?php
	$q = mysql_query("SELECT * FROM vericon.auth WHERE centre = '$centre' AND status = 'Enabled' ORDER BY user ASC") or die(mysql_error());

	while ($user = mysql_fetch_assoc($q))
	{
		$ta++;
		$q0 = mysql_query("SELECT * FROM vericon.log_login WHERE user = '$user[user]' AND DATE(timestamp) = '$date'") or die(mysql_error());
		if (mysql_fetch_row($q0) != 0)
		{
			$li++;
			$q1 = mysql_query("SELECT * FROM vericon.timesheet WHERE user = '$user[user]' AND date = '$date'") or die(mysql_error());
			$data = mysql_fetch_assoc($q1);
			
			$icon = "<img src='../images/question_mark_icon.png'>";
			
			if ($data["start"] == "") { $start = ""; } else { $start = date("H:i", strtotime($data["start"])); }
			if ($data["end"] == "") { $end = ""; } else { $end = date("H:i", strtotime($data["end"])); }
			
			if ($data["start"] != "" && $data["end"] != "" && $data["hours"] != "")
			{
				$icon = "<img src='../images/check_icon.png'>";
			}
			
			$q2 = mysql_query("SELECT * FROM vericon.sales_customers WHERE agent = '$user[user]' AND status = 'Approved' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
			$sales = mysql_num_rows($q2);
			
			$q3 = mysql_query("SELECT designation FROM vericon.timesheet_designation WHERE user = '$user[user]'")or die(mysql_error());
			$designation = mysql_fetch_row($q3);
			
			if (mysql_num_rows($q3) != 0)
			{
				echo "<tr>";
				echo "<td style='text-align:left;'><input type='hidden' id='save_user' value='$user[user]'>" . $user["first"] . " " . $user["last"] ."</td>";
				echo "<td style='text-align:left;'>" . $designation[0] ."</td>";
				echo "<td><input type='text' id='$user[user]_start' value='$start' onChange='Edit(\"start\",\"$user[user]\")' style='height:15px; width:40px;'></td>";
				echo "<td><input type='text' id='$user[user]_end' value='$end' onChange='Edit(\"end\",\"$user[user]\")' style='height:15px; width:40px;'></td>";
				echo "<td><input type='text' id='$user[user]_hours' value='$data[hours]' onChange='Edit(\"hours\",\"$user[user]\")' style='height:15px; width:40px;'></td>";
				echo "<td>" . $sales . "</td>";
				echo "<td>\$<input type='text' id='$user[user]_bonus' value='$data[bonus]' onChange='Edit(\"bonus\",\"$user[user]\")' style='height:15px; width:40px;'></td>";
				echo "<td><span id='$user[user]_icon'>$icon</span></td>";
				echo "</tr>";
			}
			else
			{
				echo "<tr class='ui-state-highlight' style='padding:0 .7em;'>";
				echo "<td style='text-align:left;'>" . $user["first"] . " " . $user["last"] ."</td>";
				echo "<td colspan='6'>Enter the Agent's Designation First!</td>";
				echo "<td><img src='../images/alert_icon.png'></td>";
				echo "</tr>";
			}
		}
	}
	
	if ($li == 0)
	{
		echo "<tr>";
		echo "<td colspan='8'>No Agents Logged in Today!</td>";
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
<th colspan="8">Not Logged In Today</th>
</tr>
<tr class="ui-widget-header ">
<th style="text-align:left;">Agent Name</th>
<th style="text-align:left;">Designation</th>
<th>Start Time</th>
<th>End Time</th>
<th>Hours</th>
<th>Sales</th>
<th>Bonus</th>
<th>Status</th>
</tr>
</thead>
<tbody>
<?php
	$q = mysql_query("SELECT * FROM vericon.auth WHERE centre = '$centre' AND status = 'Enabled' ORDER BY user ASC") or die(mysql_error());

	while ($user = mysql_fetch_assoc($q))
	{
		$q0 = mysql_query("SELECT * FROM vericon.log_login WHERE user = '$user[user]' AND DATE(timestamp) = '$date'") or die(mysql_error());
		if (mysql_fetch_row($q0) == 0)
		{
			$q1 = mysql_query("SELECT * FROM vericon.timesheet WHERE user = '$user[user]' AND date = '$date'") or die(mysql_error());
			$data = mysql_fetch_assoc($q1);
			
			$icon = "<img src='../images/question_mark_icon.png'>";
			
			if ($data["start"] == "") { $start = ""; } else { $start = date("H:i", strtotime($data["start"])); }
			if ($data["end"] == "") { $end = ""; } else { $end = date("H:i", strtotime($data["end"])); }
			
			if ($data["start"] != "" && $data["end"] != "" && $data["hours"] != "")
			{
				$icon = "<img src='../images/check_icon.png'>";
			}
			
			$q2 = mysql_query("SELECT * FROM vericon.sales_customers WHERE agent = '$user[user]' AND status = 'Approved' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
			$sales = mysql_num_rows($q2);
			
			$q3 = mysql_query("SELECT designation FROM vericon.timesheet_designation WHERE user = '$user[user]'")or die(mysql_error());
			$designation = mysql_fetch_row($q3);
			
			if (mysql_num_rows($q3) != 0)
			{
				echo "<tr>";
				echo "<td style='text-align:left;'><input type='hidden' id='save_user' value='$user[user]'>" . $user["first"] . " " . $user["last"] ."</td>";
				echo "<td style='text-align:left;'>" . $designation[0] ."</td>";
				echo "<td><input type='text' id='$user[user]_start' value='$start' onChange='Edit(\"start\",\"$user[user]\")' style='height:15px; width:40px;'></td>";
				echo "<td><input type='text' id='$user[user]_end' value='$end' onChange='Edit(\"end\",\"$user[user]\")' style='height:15px; width:40px;'></td>";
				echo "<td><input type='text' id='$user[user]_hours' value='$data[hours]' onChange='Edit(\"hours\",\"$user[user]\")' style='height:15px; width:40px;'></td>";
				echo "<td>" . $sales . "</td>";
				echo "<td>\$<input type='text' id='$user[user]_bonus' value='$data[bonus]' onChange='Edit(\"bonus\",\"$user[user]\")' style='height:15px; width:40px;'></td>";
				echo "<td><span id='$user[user]_icon'>$icon</span></td>";
				echo "</tr>";
			}
			else
			{
				echo "<tr class='ui-state-highlight' style='padding:0 .7em;'>";
				echo "<td style='text-align:left;'>" . $user["first"] . " " . $user["last"] ."</td>";
				echo "<td colspan='6'>Enter the Agent's Designation First!</td>";
				echo "<td><img src='../images/alert_icon.png'></td>";
				echo "</tr>";
			}
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