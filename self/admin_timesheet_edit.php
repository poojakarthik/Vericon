<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$method = $_GET["method"];
$centre = $_GET["centre"];
$date = $_GET["date"];
$user = $_GET["user"];

if ($method == "check")
{
	if (date("W") == date("W", strtotime($date)))
	{
		echo "valid";
	}
	else
	{
		echo "You can only edit this week's timesheets";
	}
}
elseif ($method == "check_rows")
{
	$q = mysql_query("SELECT * FROM auth,timesheet WHERE auth.centre = '$centre' AND timesheet.date = '$date' AND auth.user = timesheet.user ORDER BY timesheet.user ASC") or die(mysql_error());
	echo mysql_num_rows($q);
}
elseif ($method == "view")
{
	$q = mysql_query("SELECT * FROM auth WHERE centre = '$centre' AND status = 'Enabled' ORDER BY user ASC") or die(mysql_error());

	while ($user = mysql_fetch_assoc($q))
	{
		$q1 = mysql_query("SELECT * FROM timesheet WHERE user = '$user[user]' AND date = '$date'") or die(mysql_error());
		$data = mysql_fetch_assoc($q1);
		
		if ($data["start"] == "") { $start = "-"; } else { $start = date("H:i", strtotime($data["start"])); }
		if ($data["end"] == "") { $end = "-"; } else { $end = date("H:i", strtotime($data["end"])); }
		if ($data["hours"] == "") { $hours = "-"; } else { $hours = $data["hours"]; }
		if ($data["bonus"] == "") { $bonus = "-"; } else { $bonus = "\$" . $data["bonus"]; }
		
		$q2 = mysql_query("SELECT * FROM sales_customers WHERE agent = '$user[user]' AND status = 'Approved' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
		$sales = mysql_num_rows($q2);
		
		echo "<tr>";
		echo "<td style='text-align:left;'><a onClick='Edit(\"$user[user]\")' style='text-decoration:underline; cursor:pointer;'>" . $user["first"] . " " . $user["last"] ."</a></td>";
		echo "<td>" . $start . "</td>";
		echo "<td>" . $end . "</td>";
		echo "<td>" . $hours . "</td>";
		echo "<td>" . $sales . "</td>";
		echo "<td>" . $bonus . "</td>";
		echo "</tr>";
	}
}
elseif ($method == "name")
{
	$q = mysql_query("SELECT * FROM auth WHERE user = '$user'") or die(mysql_error());
	$user = mysql_fetch_assoc($q);
	
	echo $user["first"] . " " . $user["last"];
}
elseif ($method == "start_h")
{
	$q = mysql_query("SELECT * FROM timesheet WHERE user = '$user' AND date = '$date'") or die(mysql_error());
	$data = mysql_fetch_assoc($q);
	if ($data["start"] != "")
	{
		$result = date("H", strtotime($data["start"]));
	}
	
	echo $result;
}
elseif ($method == "start_m")
{
	$q = mysql_query("SELECT * FROM timesheet WHERE user = '$user' AND date = '$date'") or die(mysql_error());
	$data = mysql_fetch_assoc($q);
	if ($data["start"] != "")
	{
		$result = date("i", strtotime($data["start"]));
	}
	
	echo $result;
}
elseif ($method == "end_h")
{
	$q = mysql_query("SELECT * FROM timesheet WHERE user = '$user' AND date = '$date'") or die(mysql_error());
	$data = mysql_fetch_assoc($q);
	if ($data["end"] != "")
	{
		$result = date("H", strtotime($data["end"]));
	}
	
	echo $result;
}
elseif ($method == "end_m")
{
	$q = mysql_query("SELECT * FROM timesheet WHERE user = '$user' AND date = '$date'") or die(mysql_error());
	$data = mysql_fetch_assoc($q);
	if ($data["end"] != "")
	{
		$result = date("i", strtotime($data["end"]));
	}
	
	echo $result;
}
elseif ($method == "hours")
{
	$q = mysql_query("SELECT * FROM timesheet WHERE user = '$user' AND date = '$date'") or die(mysql_error());
	$data = mysql_fetch_assoc($q);
	
	echo $data["hours"];
}
elseif ($method == "sales")
{
	$q = mysql_query("SELECT * FROM sales_customers WHERE agent = '$user' AND status = 'Approved' AND DATE(approved_timestamp) = '$date'") or die(mysql_error());
	$sales = mysql_num_rows($q);
	
	echo $sales . " Sales";
}
elseif ($method == "bonus")
{
	$q = mysql_query("SELECT * FROM timesheet WHERE user = '$user' AND date = '$date'") or die(mysql_error());
	$data = mysql_fetch_assoc($q);
	
	echo $data["bonus"];
}
elseif ($method == "edit")
{
	$start = $_GET["start_h"] . ":" . $_GET["start_m"] . ":00";
	$end = $_GET["end_h"] . ":" . $_GET["end_m"] . ":00";
	$hours = $_GET["hours"];
	$bonus = $_GET["bonus"];
	
	if (preg_match("/^[0-9]{1}$/",$hours) || preg_match("/^[0-9]{1}.[0-9]{1}$/",$hours) || preg_match("/^[0-9]{1}.[0-9]{2}$/",$hours) || preg_match("/^[0-9]{2}$/",$hours) || preg_match("/^[0-9]{2}.[0-9]{1}$/",$hours) || preg_match("/^[0-9]{2}.[0-9]{2}$/",$hours))
	{
		$hours_check = "valid";
	}
	else
	{
		$hours_check = "invalid";
	}
	
	if (!preg_match("/^[0-9]{2}:[0-9]{2}:00$/",$start))
	{
		echo "Please enter a start time";
	}
	elseif (!preg_match("/^[0-9]{2}:[0-9]{2}:00$/",$end))
	{
		echo "Please enter an end time";
	}
	elseif ($hours_check == "invalid")
	{
		echo "Please enter a valid number for the hours (7 or 7.25)";
	}
	elseif (!is_numeric($bonus) && $bonus != "")
	{
		echo "Please enter a valid numeric value as a bonus";
	}
	else
	{
		
		$q = mysql_query("SELECT * FROM timesheet WHERE user = '$user' AND date = '$date'") or die(mysql_error());
		if (mysql_num_rows($q) == 0)
		{
			mysql_query("INSERT INTO timesheet (user, date, start, end, hours, bonus) VALUES ('$user', '$date', '" . mysql_escape_string($start) . "', '" . mysql_escape_string($end) . "', '" . mysql_escape_string($hours) . "', '$bonus')") or die(mysql_error());
			
			echo "submitted";
		}
		else
		{
			mysql_query("UPDATE timesheet SET start = '" . mysql_escape_string($start) . "', end = '" . mysql_escape_string($end) . "', hours = '" . mysql_escape_string($hours) . "', bonus = '$bonus' WHERE user = '$user' AND date = '$date' LIMIT 1") or die(mysql_error());
			
			echo "submitted";
		}
	}
}
?>