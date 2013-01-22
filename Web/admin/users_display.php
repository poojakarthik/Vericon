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
<th width="10%">Username</th>
<th width="18%">Full Name</th>
<th width="18%" style='text-align:center;'>Department</th>
<th width="8%" style='text-align:center;'>Centre</th>
<th width="13%" style='text-align:center;'>Joining Date</th>
<th width="13%" style='text-align:center;'>Last Login</th>
<th width="10%" style='text-align:center;'>Status</th>
<th width="10%" style='text-align:center;' colspan="2">Edit User</th>
</tr>
</thead>
<tbody>
<?php
if ($method == "display")
{
	$check = $mysqli->query("SELECT * FROM `vericon`.`auth`") or die($mysqli->error);
	$rows = $check->num_rows;
	$check->free();
	
	if($rows == 0)
	{
		$st = 0;
		echo "<tr>";
		echo "<td colspan='9' style='text-align:center;'>No Users?!?!?!</td>";
		echo "</tr>";
	}
	else
	{
		$st = $page * 13;
		$q = $mysqli->query("SELECT * FROM `vericon`.`auth` ORDER BY `user` ASC LIMIT $st , 13") or die($mysqli->error);
		
		while($r = $q->fetch_assoc())
		{
			$q1 = $mysqli->query("SELECT MAX(`timestamp`) FROM `logs`.`login` WHERE `user` = '" . $mysqli->real_escape_string($r["user"]) . "'") or die($mysqli->error);
			$l = $q1->fetch_row();
			if ($l[0] == null) {
				$last_login = "Never";
			} else {
				$last_login = date("d/m/Y H:i:s", strtotime($l[0]));
			}
			$q1->free();
			echo "<tr>";
			echo "<td>" . $r["user"] . "</td>";
			echo "<td>" . $r["first"] . " " . $r["last"] . "</td>";
			echo "<td style='text-align:center;'>" . $r["type"] . "</td>";
			echo "<td style='text-align:center;'>" . $r["centre"] . "</td>";
			echo "<td style='text-align:center;'>" . date("d/m/Y H:i:s", strtotime($r["timestamp"])) . "</td>";
			echo "<td style='text-align:center;'>" . $last_login . "</td>";
			echo "<td style='text-align:center;'>" . $r["status"] . "</td>";
			echo "<td style='text-align:center;'><button onclick='Admin03_Edit_User(\"$r[user]\")' class='icon_edit' title='Edit'></button></td>";
			if($r["status"] == "Enabled") {
				echo "<td style='text-align:center;'><button onclick='Admin03_Toggle_Status(\"$r[user]\",\"disable\")' class='icon_disable' title='Disable'></button></td>";
			} else {
				echo "<td style='text-align:center;'><button onclick='Admin03_Toggle_Status(\"$r[user]\",\"enable\")' class='icon_enable' title='Enable'></button></td>";
			}
			echo "</tr>";
		}
		$q->free();
	}
}
elseif ($method == "search_Users")
{
	$q = $mysqli->query("SELECT * FROM `vericon`.`auth` WHERE `user` = '" . $mysqli->real_escape_string($query) . "'") or die($mysqli->error);
	$r = $q->fetch_assoc();
	$q->free();
	$rows = 1;
	$st = 0;
	$q = $mysqli->query("SELECT MAX(`timestamp`) FROM `logs`.`login` WHERE `user` = '" . $mysqli->real_escape_string($r["user"]) . "'") or die($mysqli->error);
	$l = $q->fetch_row();
	if ($l[0] == null) {
		$last_login = "Never";
	} else {
		$last_login = date("d/m/Y H:i:s", strtotime($l[0]));
	}
	$q->free();
	echo "<tr>";
	echo "<td>" . $r["user"] . "</td>";
	echo "<td>" . $r["first"] . " " . $r["last"] . "</td>";
	echo "<td style='text-align:center;'>" . $r["type"] . "</td>";
	echo "<td style='text-align:center;'>" . $r["centre"] . "</td>";
	echo "<td style='text-align:center;'>" . date("d/m/Y H:i:s", strtotime($r["timestamp"])) . "</td>";
	echo "<td style='text-align:center;'>" . $last_login . "</td>";
	echo "<td style='text-align:center;'>" . $r["status"] . "</td>";
	echo "<td style='text-align:center;'><button onclick='Admin03_Edit_User(\"$r[user]\")' class='icon_edit' title='Edit'></button></td>";
	if($r["status"] == "Enabled") {
		echo "<td style='text-align:center;'><button onclick='Admin03_Toggle_Status(\"$r[user]\",\"disable\")' class='icon_disable' title='Disable'></button></td>";
	} else {
		echo "<td style='text-align:center;'><button onclick='Admin03_Toggle_Status(\"$r[user]\",\"enable\")' class='icon_enable' title='Enable'></button></td>";
	}
	echo "</tr>";
}
elseif ($method == "search_Departments")
{
	$check = $mysqli->query("SELECT * FROM `vericon`.`auth` WHERE `type` = '" . $mysqli->real_escape_string($query) . "'") or die($mysqli->error);
	$rows = $check->num_rows;
	$check->free();
	
	if($rows == 0)
	{
		$st = 0;
		echo "<tr>";
		echo "<td colspan='9' style='text-align:center;'>No Users</td>";
		echo "</tr>";
	}
	else
	{
		$st = $page * 13;
		$q = $mysqli->query("SELECT * FROM `vericon`.`auth` WHERE `type` = '" . $mysqli->real_escape_string($query) . "' ORDER BY `user` ASC LIMIT $st , 13") or die($mysqli->error);
		
		while($r = $q->fetch_assoc())
		{
			$q1 = $mysqli->query("SELECT MAX(`timestamp`) FROM `logs`.`login` WHERE `user` = '" . $mysqli->real_escape_string($r["user"]) . "'") or die($mysqli->error);
			$l = $q1->fetch_row();
			if ($l[0] == null) {
				$last_login = "Never";
			} else {
				$last_login = date("d/m/Y H:i:s", strtotime($l[0]));
			}
			$q1->free();
			echo "<tr>";
			echo "<td>" . $r["user"] . "</td>";
			echo "<td>" . $r["first"] . " " . $r["last"] . "</td>";
			echo "<td style='text-align:center;'>" . $r["type"] . "</td>";
			echo "<td style='text-align:center;'>" . $r["centre"] . "</td>";
			echo "<td style='text-align:center;'>" . date("d/m/Y H:i:s", strtotime($r["timestamp"])) . "</td>";
			echo "<td style='text-align:center;'>" . $last_login . "</td>";
			echo "<td style='text-align:center;'>" . $r["status"] . "</td>";
			echo "<td style='text-align:center;'><button onclick='Admin03_Edit_User(\"$r[user]\")' class='icon_edit' title='Edit'></button></td>";
			if($r["status"] == "Enabled") {
				echo "<td style='text-align:center;'><button onclick='Admin03_Toggle_Status(\"$r[user]\",\"disable\")' class='icon_disable' title='Disable'></button></td>";
			} else {
				echo "<td style='text-align:center;'><button onclick='Admin03_Toggle_Status(\"$r[user]\",\"enable\")' class='icon_enable' title='Enable'></button></td>";
			}
			echo "</tr>";
		}
		$q->free();
	}
}
elseif ($method == "search_Centres")
{
	$check = $mysqli->query("SELECT * FROM `vericon`.`auth` WHERE `centre` = '" . $mysqli->real_escape_string($query) . "'") or die($mysqli->error);
	$rows = $check->num_rows;
	$check->free();
	
	if($rows == 0)
	{
		$st = 0;
		echo "<tr>";
		echo "<td colspan='9' style='text-align:center;'>No Users</td>";
		echo "</tr>";
	}
	else
	{
		$st = $page * 13;
		$q = $mysqli->query("SELECT * FROM `vericon`.`auth` WHERE `centre` = '" . $mysqli->real_escape_string($query) . "' ORDER BY `user` ASC LIMIT $st , 13") or die($mysqli->error);
		
		while($r = $q->fetch_assoc())
		{
			$q1 = $mysqli->query("SELECT MAX(`timestamp`) FROM `logs`.`login` WHERE `user` = '" . $mysqli->real_escape_string($r["user"]) . "'") or die($mysqli->error);
			$l = $q1->fetch_row();
			if ($l[0] == null) {
				$last_login = "Never";
			} else {
				$last_login = date("d/m/Y H:i:s", strtotime($l[0]));
			}
			$q1->free();
			echo "<tr>";
			echo "<td>" . $r["user"] . "</td>";
			echo "<td>" . $r["first"] . " " . $r["last"] . "</td>";
			echo "<td style='text-align:center;'>" . $r["type"] . "</td>";
			echo "<td style='text-align:center;'>" . $r["centre"] . "</td>";
			echo "<td style='text-align:center;'>" . date("d/m/Y H:i:s", strtotime($r["timestamp"])) . "</td>";
			echo "<td style='text-align:center;'>" . $last_login . "</td>";
			echo "<td style='text-align:center;'>" . $r["status"] . "</td>";
			echo "<td style='text-align:center;'><button onclick='Admin03_Edit_User(\"$r[user]\")' class='icon_edit' title='Edit'></button></td>";
			if($r["status"] == "Enabled") {
				echo "<td style='text-align:center;'><button onclick='Admin03_Toggle_Status(\"$r[user]\",\"disable\")' class='icon_disable' title='Disable'></button></td>";
			} else {
				echo "<td style='text-align:center;'><button onclick='Admin03_Toggle_Status(\"$r[user]\",\"enable\")' class='icon_enable' title='Enable'></button></td>";
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
    echo "<button onClick='Admin03_More_Users(\"$page_back\")' class='back'></button>";
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
	echo "<button onClick='Admin03_More_Users(\"$page_next\")' class='next'></button>";
}
?>
</td>
</tr>
</table>
</td>
</tr>
</table></center>
<input type="hidden" id="Admin03_method" value="<?php echo $method; ?>" />
<input type="hidden" id="Admin03_page" value="<?php echo $page; ?>" />
<input type="hidden" id="Admin03_query" value="<?php echo $query; ?>" />