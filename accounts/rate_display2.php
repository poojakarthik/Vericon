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
<th width="10%">Username</th>
<th width="35%">Full Name</th>
<th style='text-align:center;' width="10%">Centre</th>
<th style='text-align:center;' width="15%">Designation</th>
<th style='text-align:center;' width="10%">Rate</th>
<th style='text-align:center;' width="10%">Edit</th>
</tr>
</thead>
<tbody>
<?php
if ($query == "")
{
	$page_link = "?page=" . $_GET["page"] . "&user=" . $user;
	$q = mysql_query("SELECT centre FROM vericon.centres WHERE status = 'Enabled' ORDER BY centre ASC") or die(mysql_error());
	while($centres = mysql_fetch_row($q))
	{
		$c_q .= "centre = '$centres[0]' OR ";
	}
	$c_q = substr($c_q,0,-4);
	
	$check = mysql_query("SELECT * FROM vericon.auth WHERE (" . $c_q . ") AND status = 'Enabled'") or die(mysql_error());
	$rows = mysql_num_rows($check);
	
	if($rows == 0)
	{
		echo "<tr>";
		echo "<td colspan='6'>No Users?!?!?!</td>";
		echo "</tr>";
	}
	else
	{
		$st = $_GET["page"]*15;
		$q = mysql_query("SELECT * FROM vericon.auth WHERE (" . $c_q . ") AND status = 'Enabled' ORDER BY user ASC LIMIT $st , 15") or die(mysql_error());
		
		while($r = mysql_fetch_assoc($q))
		{
			$q1 = mysql_query("SELECT * FROM vericon.timesheet_rate WHERE user = '$r[user]'") or die(mysql_error());
			$d = mysql_fetch_assoc($q1);
			if ($d["rate"] != "")
			{
				$rate = "\$" . $d["rate"];
			}
			else
			{
				$rate = "-";
			}
			
			$q2 = mysql_query("SELECT designation FROM vericon.timesheet_designation WHERE user = '$r[user]'") or die(mysql_error());
			$des = mysql_fetch_row($q2);
			
			echo "<tr>";
			echo "<td>" . $r["user"] . "</td>";
			echo "<td>" . $r["first"] . " " . $r["last"] . "</td>";
			echo "<td style='text-align:center;'>" . $r["centre"] . "</td>";
			echo "<td style='text-align:center;'>" . $des[0] . "</td>";
			echo "<td style='text-align:center;'>" . $rate . "</td>";
			echo "<td style='text-align:center;'><button onclick='Edit(\"$r[user]\",\"$d[rate]\")' class='icon_edit' title='Edit'></button></td>";
			echo "</tr>";
		}
	}
}
else
{
	$page_link = "?query=" . $query;
	$rows = 1;
	$q = mysql_query("SELECT * FROM vericon.auth WHERE user = '$query'") or die(mysql_error());
	$r = mysql_fetch_assoc($q);
	
	$q1 = mysql_query("SELECT * FROM vericon.timesheet_rate WHERE user = '$r[user]'") or die(mysql_error());
	$d = mysql_fetch_assoc($q1);
	if ($d["rate"] != "")
	{
		$rate = "\$" . $d["rate"];
	}
	else
	{
		$rate = "";
	}
	
	$q2 = mysql_query("SELECT designation FROM vericon.timesheet_designation WHERE user = '$r[user]'") or die(mysql_error());
	$des = mysql_fetch_row($q2);
	
	echo "<tr>";
	echo "<td>" . $r["user"] . "</td>";
	echo "<td>" . $r["first"] . " " . $r["last"] . "</td>";
	echo "<td style='text-align:center;'>" . $r["centre"] . "</td>";
	echo "<td style='text-align:center;'>" . $des[0] . "</td>";
	echo "<td style='text-align:center;'>" . $rate . "</td>";
	echo "<td style='text-align:center;'><button onclick='Edit(\"$r[user]\",\"$d[rate]\")' class='icon_edit' title='Edit'></button></td>";
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