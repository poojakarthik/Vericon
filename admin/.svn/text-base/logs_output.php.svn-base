<?php
$method = $_GET["q"];
$start = $_GET["start"];
$end = $_GET["end"];

if ($method == "")
{
	
}
else
{
	if ($start == "")
	{
		$start = date("d/m/Y");
	}
	
	if ($end == "")
	{
		$end = date("d/m/Y");
	}
	
	preg_match("/([0-9]{2})\/([0-9]{2})\/(20[0-9]{2})/",$start,$s);
	preg_match("/([0-9]{2})\/([0-9]{2})\/(20[0-9]{2})/",$end,$e);
	
	$start_date = $s[3] . "-" . $s[2] . "-" . $s[1] . " 00:00:00";
	$end_date = $e[3] . "-" . $e[2] . "-" . $e[1] . " 23:59:59";
	
	mysql_connect('localhost','vericon','18450be');
	mysql_select_db('vericon');	
	$q = mysql_query("SELECT * FROM log_" . $method . " WHERE timestamp BETWEEN '$start_date' AND '$end_date'") or die(mysql_error());
	$num = mysql_num_rows($q);
	
	if ($method == "unauthorised")
	{
?>
		<table id="users" class="ui-widget ui-widget-content" width="100%">
		<thead>
		<tr class="ui-widget-header ">
		<th>Timestamp</th>
		<th>IP</th>
		<th>Username</th>
		</tr>
		</thead>
		<tbody>
<?php
		if ($num == 0)
		{
			echo "<tr>";
			echo "<td colspan='3'><center>No logs for that date range!</center></td>";
			echo "</tr>";
		}
		else
		{
			while ($logs = mysql_fetch_assoc($q))
			{
				echo "<tr>";
				echo "<td>" . $logs["timestamp"] . "</td>";
				echo "<td>" . $logs["ip"] . "</td>";
				echo "<td>" . $logs["user"] . "</td>";
				echo "</tr>";
			}
		}
?>
		</tbody>
		</table>
<?php
	}
	elseif ($method == "sales")
	{
?>
		<table id="users" class="ui-widget ui-widget-content" width="100%">
		<thead>
		<tr class="ui-widget-header ">
		<th>Timestamp</th>
		<th>Username</th>
		<th>Lead ID</th>
		<th>Reason</th>
		</tr>
		</thead>
		<tbody>
<?php
		if ($num == 0)
		{
			echo "<tr>";
			echo "<td colspan='4'><center>No logs for that date range!</center></td>";
			echo "</tr>";
		}
		else
		{
			while ($logs = mysql_fetch_assoc($q))
			{
				echo "<tr>";
				echo "<td>" . $logs["timestamp"] . "</td>";
				echo "<td>" . $logs["user"] . "</td>";
				echo "<td>" . $logs["lead_id"] . "</td>";
				echo "<td>" . $logs["reason"] . "</td>";
				echo "</tr>";
			}
		}
?>
		</tbody>
		</table>
<?php
	}
	elseif ($method == "login")
	{
?>
		<table id="users" class="ui-widget ui-widget-content" width="100%">
		<thead>
		<tr class="ui-widget-header ">
		<th>Timestamp</th>
		<th>IP</th>
		<th>Username</th>
		</tr>
		</thead>
		<tbody>
<?php
		if ($num == 0)
		{
			echo "<tr>";
			echo "<td colspan='3'><center>No logs for that date range!</center></td>";
			echo "</tr>";
		}
		else
		{
			while ($logs = mysql_fetch_assoc($q))
			{
				echo "<tr>";
				echo "<td>" . $logs["timestamp"] . "</td>";
				echo "<td>" . $logs["ip"] . "</td>";
				echo "<td>" . $logs["user"] . "</td>";
				echo "</tr>";
			}
		}
?>
		</tbody>
		</table>
<?php
	}
	elseif ($method == "al")
	{
?>
		<table id="users" class="ui-widget ui-widget-content" width="100%">
		<thead>
		<tr class="ui-widget-header ">
		<th>Timestamp</th>
		<th>IP</th>
		<th>Username</th>
		</tr>
		</thead>
		<tbody>
<?php
		if ($num == 0)
		{
			echo "<tr>";
			echo "<td colspan='3'><center>No logs for that date range!</center></td>";
			echo "</tr>";
		}
		else
		{
			while ($logs = mysql_fetch_assoc($q))
			{
				echo "<tr>";
				echo "<td>" . $logs["timestamp"] . "</td>";
				echo "<td>" . $logs["ip"] . "</td>";
				echo "<td>" . $logs["user"] . "</td>";
				echo "</tr>";
			}
		}
?>
		</tbody>
		</table>
<?php
	}
}

?>