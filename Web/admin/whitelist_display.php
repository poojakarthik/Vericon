<?php
include("../auth/restrict_inner.php");

$method = $_POST["m"];
$page = $_POST["page"];
$query = $_POST["query"];
?>
<center><table width="98%" height="500px">
<tr valign="top" height="95%">
<td>
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th width="20%">IP</th>
<th width="35%">Description</th>
<th width="15%" style='text-align:center;'>Date Added</th>
<th width="15%" style='text-align:center;'>Added By</th>
<th width="10%" style='text-align:center;'>Status</th>
<th width="5%" style='text-align:center;'></th>
</tr>
</thead>
<tbody>
<?php
if ($method == "display")
{
	$check = $mysqli->query("SELECT * FROM `vericon`.`allowedip`") or die($mysqli->error);
	$rows = $check->num_rows;
	$check->free();
	
	if($rows == 0)
	{
		$st = 0;
		echo "<tr>";
		echo "<td colspan='6'>No IPs?!?!?!</td>";
		echo "</tr>";
	}
	else
	{
		$st = $page * 13;
		$q = $mysqli->query("SELECT INET_NTOA(`allowedip`.`ip_start`) AS ip_start, INET_NTOA(`allowedip`.`ip_end`) AS ip_end, `allowedip`.`description`, `allowedip`.`status`, `allowedip`.`timestamp`, CONCAT(`auth`.`first`, ' ', `auth`.`last`) AS name FROM `vericon`.`allowedip`, `vericon`.`auth` WHERE `allowedip`.`added_by` = `auth`.`user` ORDER BY `ip_start` ASC LIMIT $st , 13") or die($mysqli->error);
		while($r = $q->fetch_assoc())
		{
			if ($r["ip_start"] != $r["ip_end"]) {
				$ip = $r["ip_start"] . " - " . $r["ip_end"];
			} else {
				$ip = $r["ip_start"];
			}
			
			echo "<tr>";
			echo "<td>" . $ip . "</td>";
			echo "<td>" . $r["description"] . "</td>";
			echo "<td style='text-align:center;'>" . date("d/m/Y H:i:s", strtotime($r["timestamp"])) . "</td>";
			echo "<td style='text-align:center;'>" . $r["name"] . "</td>";
			echo "<td style='text-align:center;'>" . $r["status"] . "</td>";
			if($r["status"] == "Enabled") {
				echo "<td style='text-align:center;'><button onclick='Admin04_Toggle_Status(\"$r[ip_start]\",\"$r[ip_end]\",\"disable\")' class='icon_disable' title='Disable'></button></td>";
			} else {
				echo "<td style='text-align:center;'><button onclick='Admin04_Toggle_Status(\"$r[ip_start]\",\"$r[ip_end]\",\"enable\")' class='icon_enable' title='Enable'></button></td>";
			}
			echo "</tr>";
		}
		$q->free();
	}
}
elseif ($method == "search_IPs")
{
	$query = explode(",",$query);
	$st = 0;
	$q = $mysqli->query("SELECT INET_NTOA(`allowedip`.`ip_start`) AS ip_start, INET_NTOA(`allowedip`.`ip_end`) AS ip_end, `allowedip`.`description`, `allowedip`.`status`, `allowedip`.`timestamp`, CONCAT(`auth`.`first`, ' ', `auth`.`last`) AS name FROM `vericon`.`allowedip`, `vericon`.`auth` WHERE (`allowedip`.`ip_start` = '" . $mysqli->real_escape_string(ip2long($query[0])) . "' AND `allowedip`.`ip_end` = '" . $mysqli->real_escape_string(ip2long($query[1])) . "') AND `allowedip`.`added_by` = `auth`.`user`") or die($mysqli->error);
	$r = $q->fetch_assoc();
	$q->free();
	$rows = 1;
	
	if ($r["ip_start"] != $r["ip_end"]) {
		$ip = $r["ip_start"] . " - " . $r["ip_end"];
	} else {
		$ip = $r["ip_start"];
	}
	
	echo "<tr>";
	echo "<td>" . $ip . "</td>";
	echo "<td>" . $r["description"] . "</td>";
	echo "<td style='text-align:center;'>" . date("d/m/Y H:i:s", strtotime($r["timestamp"])) . "</td>";
	echo "<td style='text-align:center;'>" . $r["name"] . "</td>";
	echo "<td style='text-align:center;'>" . $r["status"] . "</td>";
	if($r["status"] == "Enabled") {
		echo "<td style='text-align:center;'><button onclick='Admin04_Toggle_Status(\"$r[ip_start]\",\"$r[ip_end]\",\"disable\")' class='icon_disable' title='Disable'></button></td>";
	} else {
		echo "<td style='text-align:center;'><button onclick='Admin04_Toggle_Status(\"$r[ip_start]\",\"$r[ip_end]\",\"enable\")' class='icon_enable' title='Enable'></button></td>";
	}
	echo "</tr>";
	
	$query = implode(",",$query);
}
elseif ($method == "search_Description")
{
	$check = $mysqli->query("SELECT * FROM `vericon`.`allowedip` WHERE `description` = '" . $mysqli->real_escape_string($query) . "'") or die($mysqli->error);
	$rows = $check->num_rows;
	
	if($rows == 0)
	{
		$st = 0;
		echo "<tr>";
		echo "<td colspan='6'>No IPs?!?!?!</td>";
		echo "</tr>";
	}
	else
	{
		$st = $page * 13;
		$q = $mysqli->query("SELECT INET_NTOA(`allowedip`.`ip_start`) AS ip_start, INET_NTOA(`allowedip`.`ip_end`) AS ip_end, `allowedip`.`description`, `allowedip`.`status`, `allowedip`.`timestamp`, CONCAT(`auth`.`first`, ' ', `auth`.`last`) AS name FROM `vericon`.`allowedip`, `vericon`.`auth` WHERE `allowedip`.`description` = '" . $mysqli->real_escape_string($query) . "' AND `allowedip`.`added_by` = `auth`.`user`") or die($mysqli->error);
		while ($r = $q->fetch_assoc())
		{
			if ($r["ip_start"] != $r["ip_end"]) {
				$ip = $r["ip_start"] . " - " . $r["ip_end"];
			} else {
				$ip = $r["ip_start"];
			}
			
			echo "<tr>";
			echo "<td>" . $ip . "</td>";
			echo "<td>" . $r["description"] . "</td>";
			echo "<td style='text-align:center;'>" . date("d/m/Y H:i:s", strtotime($r["timestamp"])) . "</td>";
			echo "<td style='text-align:center;'>" . $r["name"] . "</td>";
			echo "<td style='text-align:center;'>" . $r["status"] . "</td>";
			if($r["status"] == "Enabled") {
				echo "<td style='text-align:center;'><button onclick='Admin04_Toggle_Status(\"$r[ip_start]\",\"$r[ip_end]\",\"disable\")' class='icon_disable' title='Disable'></button></td>";
			} else {
				echo "<td style='text-align:center;'><button onclick='Admin04_Toggle_Status(\"$r[ip_start]\",\"$r[ip_end]\",\"enable\")' class='icon_enable' title='Enable'></button></td>";
			}
			echo "</tr>";
		}
		$q->free();
	}
}
$mysqli->close();
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
if (($st - 13) < $rows && $page > 0)
{
    $page_back = $page - 1;
    echo "<button onClick='Admin04_More_IPs(\"$page_back\")' class='back'></button>";
}
?>
</td>
<td align="center" width="20%">
<?php
$p = $page + 1;
$p_t = ceil($rows / 13);
echo $p . " of " . $p_t;
?>
</td>
<td align="right" width="40%">
<?php
if (($st + 13) < $rows)
{
	$page_next = $page + 1;
	echo "<button onClick='Admin04_More_IPs(\"$page_next\")' class='next'></button>";
}
?>
</td>
</tr>
</table>
</td>
</tr>
</table></center>
<input type="hidden" id="Admin04_method" value="<?php echo $method; ?>" />
<input type="hidden" id="Admin04_page" value="<?php echo $page; ?>" />
<input type="hidden" id="Admin04_query" value="<?php echo $query; ?>" />