<?php
mysql_connect('localhost','vericon','18450be');

$query = $_GET["query"];
?>
<center><table width="98%" height="575px">
<tr valign="top" height="95%">
<td>
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th width="15%">IP</th>
<th width="40%">Description</th>
<th width="15%" style='text-align:center;'>Date Added</th>
<th width="15%" style='text-align:center;'>Added By</th>
<th colspan="2" style='text-align:center;' width="15%">Status</th>
</tr>
</thead>
<tbody>
<?php
mysql_connect('localhost','vericon','18450be');

if ($query == "")
{
	$page_link = "?page=" . $_GET["page"];
	
	$check = mysql_query("SELECT * FROM vericon.allowedip") or die(mysql_error());
	$rows = mysql_num_rows($check);
	
	if($rows == 0)
	{
		echo "<tr>";
		echo "<td colspan='4'>No IPs?!?!?!</td>";
		echo "</tr>";
	}
	else
	{
		$st = $_GET["page"]*15;
		$q = mysql_query("SELECT * FROM vericon.allowedip ORDER BY Description, IP ASC LIMIT $st , 15") or die(mysql_error());
		
		while($r = mysql_fetch_assoc($q))
		{
			if ($r["status"] == 1) { $status = "Enabled"; } else { $status = "Disabled"; }
			
			$q1 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$r[added_by]'") or die(mysql_error());
			$user = mysql_fetch_row($q1);
			
			echo "<tr>";
			echo "<td>" . $r["IP"] . "</td>";
			echo "<td>" . $r["Description"] . "</td>";
			echo "<td style='text-align:center;'>" . date("d/m/Y", strtotime($r["timestamp"])) . "</td>";
			echo "<td style='text-align:center;'>" . $user[0] . " " . $user[1] . "</td>";
			echo "<td style='text-align:center;'>" . $status . "</td>";
			if($status == "Enabled")
			{
				echo "<td style='text-align:center;'><button onclick='Disable(\"$r[IP]\")' class='icon_cancel' title='Disable'></button></td>";
			}
			else
			{
				echo "<td style='text-align:center;'><button onclick='Enable(\"$r[IP]\")' class='icon_check' title='Enable'></button></td>";
			}
			echo "</tr>";
		}
	}
}
else
{
	$page_link = "?query=" . $query;
	$q = mysql_query("SELECT * FROM vericon.allowedip WHERE ip = '" . mysql_real_escape_string($query) . "'") or die(mysql_error());
	$r = mysql_fetch_assoc($q);
	$rows = 1;
	
	if ($r["status"] == 1) { $status = "Enabled"; } else { $status = "Disabled"; }
			
	echo "<tr>";
	echo "<td>" . $r["IP"] . "</td>";
	echo "<td>" . $r["Description"] . "</td>";
	echo "<td style='text-align:center;' style='text-align:center;'>" .$status . "</td>";
	if($status == "Enabled")
	{
		echo "<td style='text-align:center;'><button onclick='Disable(\"$r[IP]\")' class='icon_cancel' title='Disable'></button></td>";
	}
	else
	{
		echo "<td style='text-align:center;'><button onclick='Enable(\"$r[IP]\")' class='icon_check' title='Enable'></button></td>";
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