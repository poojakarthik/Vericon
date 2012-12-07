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
</thead>
<tbody>
<?php
if ($method == "display")
{
	$check = mysql_query("SELECT * FROM `vericon`.`auth`") or die(mysql_error());
	$rows = mysql_num_rows($check);
	
	if($rows == 0)
	{
		echo "<tr>";
		echo "<td colspan='9' style='text-align:center;'>No Users?!?!?!</td>";
		echo "</tr>";
	}
	else
	{
		$st = $page * 13;
		$q = mysql_query("SELECT * FROM `vericon`.`auth` ORDER BY `user` ASC LIMIT $st , 13") or die(mysql_error());
		
		while($r = mysql_fetch_assoc($q))
		{
			$q1 = mysql_query("SELECT MAX(`timestamp`) FROM `logs`.`login` WHERE `user` = '" . mysql_real_escape_string($r["user"]) . "'") or die(mysql_error());
			$l = mysql_fetch_row($q1);
			if ($l[0] == null) {
				$last_login = "Never";
			} else {
				$last_login = date("d/m/Y H:i:s", strtotime($l[0]));
			}
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
	}
}
elseif ($method == "search_Users")
{
	$q = mysql_query("SELECT * FROM `vericon`.`auth` WHERE `user` = '" . mysql_real_escape_string($query) . "'") or die(mysql_error());
	$r = mysql_fetch_assoc($q);
	$rows = 1;
	$q1 = mysql_query("SELECT MAX(`timestamp`) FROM `logs`.`login` WHERE `user` = '" . mysql_real_escape_string($r["user"]) . "'") or die(mysql_error());
	$l = mysql_fetch_row($q1);
	if ($l[0] == null) {
		$last_login = "Never";
	} else {
		$last_login = date("d/m/Y H:i:s", strtotime($l[0]));
	}
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
	$check = mysql_query("SELECT * FROM `vericon`.`auth` WHERE `type` LIKE '%" . mysql_real_escape_string($query) . "%'") or die(mysql_error());
	$rows = mysql_num_rows($check);
	
	if($rows == 0)
	{
		echo "<tr>";
		echo "<td colspan='9' style='text-align:center;'>No Users</td>";
		echo "</tr>";
	}
	else
	{
		$st = $page * 13;
		$q = mysql_query("SELECT * FROM `vericon`.`auth` WHERE `type` LIKE '%" . mysql_real_escape_string($query) . "%' ORDER BY `user` ASC LIMIT $st , 13") or die(mysql_error());
		
		while($r = mysql_fetch_assoc($q))
		{
			$q1 = mysql_query("SELECT MAX(`timestamp`) FROM `logs`.`login` WHERE `user` = '" . mysql_real_escape_string($r["user"]) . "'") or die(mysql_error());
			$l = mysql_fetch_row($q1);
			if ($l[0] == null) {
				$last_login = "Never";
			} else {
				$last_login = date("d/m/Y H:i:s", strtotime($l[0]));
			}
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
	}
}
elseif ($method == "search_Centres")
{
	$check = mysql_query("SELECT * FROM `vericon`.`auth` WHERE `centre` LIKE '%" . mysql_real_escape_string($query) . "%'") or die(mysql_error());
	$rows = mysql_num_rows($check);
	
	if($rows == 0)
	{
		echo "<tr>";
		echo "<td colspan='9' style='text-align:center;'>No Users</td>";
		echo "</tr>";
	}
	else
	{
		$st = $page * 13;
		$q = mysql_query("SELECT * FROM `vericon`.`auth` WHERE `centre` LIKE '%" . mysql_real_escape_string($query) . "%' ORDER BY `user` ASC LIMIT $st , 13") or die(mysql_error());
		
		while($r = mysql_fetch_assoc($q))
		{
			$q1 = mysql_query("SELECT MAX(`timestamp`) FROM `logs`.`login` WHERE `user` = '" . mysql_real_escape_string($r["user"]) . "'") or die(mysql_error());
			$l = mysql_fetch_row($q1);
			if ($l[0] == null) {
				$last_login = "Never";
			} else {
				$last_login = date("d/m/Y H:i:s", strtotime($l[0]));
			}
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
	}
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
if (($st - 13) < $rows && $page > 0)
{
    $page_back = $page - 1;
    echo "<button onClick='Admin03_More_Users(\"$page_back\")' class='back'>Back</button>";
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
	echo "<button onClick='Admin03_More_Users(\"$page_next\")' class='next'>Next</button>";
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