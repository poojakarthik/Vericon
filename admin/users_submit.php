<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];
$username = $_GET["username"];
$first = trim($_GET["first"]);
$last = trim($_GET["last"]);
$password = $_GET["password"];
$password2 = $_GET["password2"];
$t = $_GET["access"];
$type = implode(",",$t);
$centre = $_GET["centre"];
$designation = $_GET["designation"];
$alias = trim($_GET["alias"]);
$status = $_GET["status"];
$timestamp = date("Y-m-d H:i:s");

if ($method == "create")
{
	if (strlen(preg_replace("/[^A-Za-z]/", "", $last)) == 2)
	{
		$user1 = strtolower(substr(preg_replace("/[^A-Za-z]/", "", $first),0,2) . substr(preg_replace("/[^A-Za-z]/", "", $last),0,2));
	}
	else
	{
		$user1 = strtolower(substr(preg_replace("/[^A-Za-z]/", "", $first),0,1) . substr(preg_replace("/[^A-Za-z]/", "", $last),0,3));
	}
	
	if ($first == "" || $last == "")
	{
		echo "Please enter a first and last name";
	}
	elseif (strlen($user1) != 4)
	{
		echo "Invalid first and last name. Must contain at least 2 letters in each";
	}
	elseif ($password != $password2)
	{
		echo "Passwords do not match";
	}
	elseif ($type == "")
	{
		echo "Please select an account type";
	}
	elseif (in_array("Sales", $t) && $centre == "")
	{
		echo "Please select a centre";
	}
	elseif (in_array("Sales", $t) && $designation == "")
	{
		echo "Please select a designation";
	}
	elseif ((in_array("Sales", $t) || in_array("CS", $t) || in_array("TPV", $t) || in_array("Welcome", $t)) && $alias == "")
	{
		echo "Please enter an alias";
	}
	else
	{
		if (!in_array("Sales", $t))
		{
			$centre = "";
			$designation = "";
		}
		
		if (!in_array("Sales", $t) && !in_array("CS", $t) && !in_array("TPV", $t) && !in_array("Welcome", $t))
		{
			$alias = "";
		}
		
		$q = mysql_query("SELECT COUNT(user) FROM vericon.auth WHERE user LIKE '$user1%'");
		$num = mysql_fetch_row($q);
		
		$username = $user1 . str_pad(($num[0]+1),3,"0",STR_PAD_LEFT);
		
		mysql_query("INSERT INTO vericon.auth (user, pass, type, centre, status, first, last, alias, timestamp) VALUES ('$username' ,'" . md5($password) . "', '$type', '$centre', 'Enabled', '" . mysql_real_escape_string($first) . "', '" . mysql_real_escape_string($last) . "', '" . mysql_real_escape_string($alias) . "', '$timestamp')");
		
		if (in_array("Sales", $t))
		{
			mysql_query("INSERT INTO vericon.timesheet_designation (user,designation) VALUES ('$username','$designation')") or die(mysql_error());
		}
		
		$pages = array( );
		
		for ($i = 0; $i < count($t); $i++)
		{
			if ($t[$i] == "Sales")
			{
				$q1 = mysql_query("SELECT type FROM vericon.centres WHERE centre = '$centre'") or die(mysql_error());
				$centre_type = mysql_fetch_row($q1);
				
				$q2 = mysql_query("SELECT pages FROM vericon.portals_template WHERE user = '$t[$i]' AND type = '$centre_type[0]'") or die(mysql_error());
				$p = mysql_fetch_row($q2);
				
				$pa = explode(",", $p[0]);
				for ($pi = 0; $pi < count($pa); $pi++)
				{
					array_push($pages ,$pa[$pi]);
				}
			}
			else
			{
				$q1 = mysql_query("SELECT pages FROM vericon.portals_template WHERE user = '$t[$i]'") or die(mysql_error());
				$p = mysql_fetch_row($q1);
				
				$pa = explode(",", $p[0]);
				for ($pi = 0; $pi < count($pa); $pi++)
				{
					array_push($pages ,$pa[$pi]);
				}
			}
		}
		
		$pages = implode(",", array_unique($pages));
		
		mysql_query("INSERT INTO vericon.portals_access (user, pages) VALUES ('$username', '" . mysql_real_escape_string($pages) . "')") or die(mysql_error());
		
		echo "createdYou have successfully created the user!<br><br>Username: <b>$username</b>";
	}
}
elseif ($method == "modify")
{
	if ($type == "")
	{
		echo "Please select an account type";
	}
	elseif (in_array("Sales", $t) && $centre == "")
	{
		echo "Please select a centre";
	}
	elseif (in_array("Sales", $t) && $designation == "")
	{
		echo "Please select a designation";
	}
	elseif ((in_array("Sales", $t) || in_array("CS", $t) || in_array("TPV", $t) || in_array("Welcome", $t)) && $alias == "")
	{
		echo "Please enter an alias";
	}
	else
	{
		if (!in_array("Sales", $t))
		{
			$centre = "";
			$designation = "";
		}
		
		if (!in_array("Sales", $t) && !in_array("CS", $t) && !in_array("TPV", $t) && !in_array("Welcome", $t))
		{
			$alias = "";
		}
		
		mysql_query("UPDATE vericon.auth SET type = '$type', centre = '$centre', alias = '" . mysql_real_escape_string($alias) . "' WHERE user = '$username' LIMIT 1");
		
		if (in_array("Sales", $t))
		{
			mysql_query("INSERT INTO vericon.timesheet_designation (user, designation) VALUES ('$username', '$designation') ON DUPLICATE KEY UPDATE designation = '$designation'") or die(mysql_error());
		}
		else
		{
			mysql_query("DELETE FROM vericon.timesheet_designation WHERE user = '$username' LIMIT 1") or die(mysql_error());
		}
		
		$pages = array( );
		
		for ($i = 0; $i < count($t); $i++)
		{
			if ($t[$i] == "Sales")
			{
				$q1 = mysql_query("SELECT type FROM vericon.centres WHERE centre = '$centre'") or die(mysql_error());
				$centre_type = mysql_fetch_row($q1);
				
				$q2 = mysql_query("SELECT pages FROM vericon.portals_template WHERE user = '$t[$i]' AND type = '$centre_type[0]'") or die(mysql_error());
				$p = mysql_fetch_row($q2);
				
				$pa = explode(",", $p[0]);
				for ($pi = 0; $pi < count($pa); $pi++)
				{
					array_push($pages ,$pa[$pi]);
				}
			}
			else
			{
				$q1 = mysql_query("SELECT pages FROM vericon.portals_template WHERE user = '$t[$i]'") or die(mysql_error());
				$p = mysql_fetch_row($q1);
				
				$pa = explode(",", $p[0]);
				for ($pi = 0; $pi < count($pa); $pi++)
				{
					array_push($pages ,$pa[$pi]);
				}
			}
		}
		
		$pages = implode(",", array_unique($pages));
		
		mysql_query("INSERT INTO vericon.portals_access (user, pages) VALUES ('$username', '" . mysql_real_escape_string($pages) . "') ON DUPLICATE KEY UPDATE pages = '" . mysql_real_escape_string($pages) . "'") or die(mysql_error());
		
		echo "modified";
	}
}
elseif ($method == "modify_pw")
{
	if ($password != $password2)
	{
		echo "Passwords do not match";
	}
	else
	{
		mysql_query("UPDATE vericon.auth SET pass = '" . md5($password) . "' WHERE user = '$username' LIMIT 1");
		
		echo "modified";
	}
}
elseif ($method == "modify_access")
{
	$p = $_GET["pages"];
	$pages = implode(",", $p);
	
	mysql_query("INSERT INTO vericon.portals_access (user, pages) VALUES ('$username', '" . mysql_real_escape_string($pages) . "') ON DUPLICATE KEY UPDATE pages = '" . mysql_real_escape_string($pages) . "'") or die(mysql_error());
	
	echo "modified";
}
elseif ($method == "disable")
{
	mysql_query("UPDATE vericon.auth SET status = 'Disabled' WHERE user = '$username' LIMIT 1") or die(mysql_error());
}
elseif ($method == "enable")
{
	mysql_query("UPDATE vericon.auth SET status = 'Enabled' WHERE user = '$username' LIMIT 1") or die(mysql_error());
}
elseif ($method == "search")
{
	$term = explode(" ",$_GET["term"]);
	
	$q = mysql_query("SELECT * FROM vericon.auth WHERE first LIKE '" . mysql_real_escape_string($term[0]) . "%' AND last LIKE '" . mysql_real_escape_string($term[1]) . "%'") or die(mysql_error());
	echo '[';
	while ($data = mysql_fetch_assoc($q))
	{
		$d[] = "{ \"id\": \"" . $data["user"] . "\", \"value\": \"" . $data["first"] . " " . $data["last"] . " (" . $data["user"] . ")\" }";
	}
	echo implode(", ",$d);
	echo ']';
}
elseif ($method == "first")
{
	$user = $_GET["user"];
	$q = mysql_query("SELECT first FROM vericon.auth WHERE user = '$user'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo $data[0];
}
elseif ($method == "last")
{
	$user = $_GET["user"];
	$q = mysql_query("SELECT last FROM vericon.auth WHERE user = '$user'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo $data[0];
}
elseif ($method == "access")
{
	$user = $_GET["user"];
	$q = mysql_query("SELECT type FROM vericon.auth WHERE user = '$user'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo $data[0];
}
elseif ($method == "centre")
{
	$user = $_GET["user"];
	$q = mysql_query("SELECT centre FROM vericon.auth WHERE user = '$user'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo $data[0];
}
elseif ($method == "designation")
{
	$user = $_GET["user"];
	$q = mysql_query("SELECT designation FROM vericon.timesheet_designation WHERE user = '$user'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo $data[0];
}
elseif ($method == "alias")
{
	$user = $_GET["user"];
	$q = mysql_query("SELECT alias FROM vericon.auth WHERE user = '$user'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo $data[0];
}
elseif ($method == "pages")
{
	$q = mysql_query("SELECT * FROM vericon.auth WHERE user = '$username'") or die(mysql_error());
	$t = mysql_fetch_assoc($q);
	$type = explode(",", $t["type"]);
	$name = $t["first"] . " " . $t["last"] . " (" . $t["user"] . ")";
	
	$q = mysql_query("SELECT pages FROM vericon.portals_access WHERE user = '$username'") or die(mysql_error());
	$p = mysql_fetch_row($q);
	$pages = explode(",", $p[0]);
?>
<table>
<tr>
<td width="70px"><b>Username: </b></td>
<td><?php echo $name; ?></td>
</tr>
</table>

<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%;">
<thead>
<tr class="ui-widget-header ">
<th style='text-align:center;' colspan="4">My Account</th>
</tr>
<tr class="ui-widget-header ">
<th width="10%"></th>
<th style='text-align:center;' width="20%">ID</th>
<th style='text-align:center;' width="10%">Level</th>
<th style='text-align:center;' width="60%">Page</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT * FROM vericon.portals_pages WHERE portal = 'MA' ORDER BY id ASC") or die(mysql_error());
while ($da = mysql_fetch_assoc($q))
{
	if ($da["sub_level"] != 0) { $level = $da["level"] . " - " . $da["sub_level"]; } else { $level = $da["level"]; }
	
	$checkbox = "<input type='checkbox' checked='checked' disabled='disabled' style='height: auto;' value='$da[id]'>";
	
	echo "<tr>";
	echo "<td style='text-align:center; padding: .3em 5px;'>" . $checkbox . "</td>";
	echo "<td style='text-align:center; padding: .3em 5px;'>" . $da["id"] . "</td>";
	echo "<td style='text-align:center; padding: .3em 5px;'>" . $level . "</td>";
	echo "<td style='text-align:center; padding: .3em 5px;'>" . $da["name"] . "</td>";
	echo "</tr>";
}

for ($i = 0; $i < count($type); $i++)
{
	$q0 = mysql_query("SELECT name FROM vericon.portals WHERE id = '$type[$i]'") or die(mysql_error());
	$p_name = mysql_fetch_row($q0);
?>
<thead>
<tr class="ui-widget-header ">
<th style='text-align:center;' colspan="4"><?php echo $p_name[0]; ?></th>
</tr>
<tr class="ui-widget-header ">
<th width="10%"></th>
<th style='text-align:center;' width="20%">ID</th>
<th style='text-align:center;' width="10%">Level</th>
<th style='text-align:center;' width="60%">Page</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT * FROM vericon.portals_pages WHERE portal = '$type[$i]' ORDER BY id ASC") or die(mysql_error());
while ($data = mysql_fetch_assoc($q))
{
	if ($data["sub_level"] != 0) { $level = $data["level"] . " - " . $data["sub_level"]; } else { $level = $data["level"]; }
	
	if ($data["level"] == 1)
	{
		$checkbox = "<input type='checkbox' checked='checked' disabled='disabled' style='height: auto;' value='$data[id]'>";
	}
	elseif (in_array($data["id"], $pages))
	{
		$checkbox = "<input type='checkbox' checked='checked' style='height: auto;' value='$data[id]'>";
	}
	else
	{
		$checkbox = "<input type='checkbox' style='height: auto;' value='$data[id]'>";
	}
	
	echo "<tr>";
	echo "<td style='text-align:center; padding: .3em 5px;'>" . $checkbox . "</td>";
	echo "<td style='text-align:center; padding: .3em 5px;'>" . $data["id"] . "</td>";
	echo "<td style='text-align:center; padding: .3em 5px;'>" . $level . "</td>";
	echo "<td style='text-align:center; padding: .3em 5px;'>" . $data["name"] . "</td>";
	echo "</tr>";
}
?>
</tbody>
<?php
}
?>
</table>
</div>
<?php
}
?>