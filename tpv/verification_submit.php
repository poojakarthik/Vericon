<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$method = $_GET["method"];

if ($method == "get") //get sale
{
	$id = $_GET["id"];
	$user = $_GET["user"];
	
	$q = mysql_query("SELECT * FROM sales_customers WHERE id = '" . mysql_escape_string($id) . "'") or die(mysql_error());
	$check = mysql_fetch_assoc($q);
	
	$q1 = mysql_query("SELECT * FROM tpv_lock WHERE id = '$id'") or die(mysql_error());
	$check2 = mysql_fetch_assoc($q1);
	
	$vq = mysql_query("SELECT first FROM auth WHERE user = '$check2[user]'") or die(mysql_error());
	$veri = mysql_fetch_row($vq);
	
	if ($id == "" || mysql_num_rows($q) == 0)
	{
		echo "Invalid ID!";
	}
	elseif ($check["status"] == "Approved")
	{
		echo "Sale already Approved!";
	}
	elseif (mysql_num_rows($q1) != 0)
	{
		echo "Sale Currently Open by " . $veri[0] . "!";
	}
	else
	{
		$q2 = mysql_query("SELECT * FROM tpv_lock WHERE user = '$user'") or die(mysql_error());
		if (mysql_num_rows($q2) == 0)
		{
			mysql_query("INSERT INTO tpv_lock (user, id) VALUES ('$user', '$id')") or die(mysql_error());
		}
		else
		{
			mysql_query("UPDATE tpv_lock SET id = '$id' WHERE user = '$user'") or die(mysql_error());
		}
		echo "valid";
	}
}
elseif ($method == "add") //add package
{
	$sid = $_GET["id"];
	$cli = $_GET["cli"];
	$plan = $_GET["plan"];
	$week = date("W");
	
	$ch2 = mysql_query("SELECT COUNT(cli) FROM sales_packages WHERE cli = '$cli' AND WEEK(timestamp) = '$week'");
	$check2 = mysql_fetch_row($ch2);
	
	$ch3 = mysql_query("SELECT COUNT(cli) FROM sct_dnc WHERE cli = '" . mysql_escape_string($cli) . "'");
	$check3 = mysql_fetch_row($ch3);
	
	if (!preg_match("/^0[2378][0-9]{8}$/",$cli))
	{
		echo "Invalid CLI";
	}
	elseif ($check3[0] != 0)
	{
		echo "CLI is on the SCT DNC list";
	}
	elseif ($check2[0] != 0)
	{
		echo "CLI already added";
	}
	else
	{
		mysql_query("INSERT INTO sales_packages (sid, cli, plan) VALUES ('$sid', '" . mysql_escape_string($cli) . "', '$plan')") or die(mysql_error());
		echo "added";
	}
}
elseif ($method == "edit") //edit package
{
	$sid = $_GET["id"];
	$cli = $_GET["cli"];
	$plan = $_GET["plan"];
	$cli2 = $_GET["cli2"];
	
	$ch2 = mysql_query("SELECT COUNT(cli) FROM sales_packages WHERE cli = '$cli'");
	$check2 = mysql_fetch_row($ch2);
	
	$ch3 = mysql_query("SELECT COUNT(cli) FROM sct_dnc WHERE cli = '" . mysql_escape_string($cli) . "'");
	$check3 = mysql_fetch_row($ch3);
	
	if (!preg_match("/^0[2378][0-9]{8}$/",$cli))
	{
		echo "Invalid CLI";
	}
	elseif ($check3[0] != 0)
	{
		echo "CLI is on the SCT DNC list";
	}
	elseif ($check2[0] != 0 && $cli != $cli2)
	{
		echo "CLI already added";
	}
	else
	{
		mysql_query("DELETE FROM sales_packages WHERE sid = '$sid' AND cli = '$cli2' LIMIT 1") or die(mysql_error());
		mysql_query("INSERT INTO sales_packages (sid, cli, plan) VALUES ('$sid', '" . mysql_escape_string($cli) . "', '$plan')") or die(mysql_error());
		echo "editted";
	}
}
elseif ($method == "delete") //delete package
{
	$sid = $_GET["id"];
	$cli = $_GET["cli"];
	
	mysql_query("DELETE FROM sales_packages WHERE sid = '$sid' AND cli = '$cli' LIMIT 1") or die(mysql_error());
	
	echo "deleted";
}
elseif ($method == "update_type") //update type
{
	$id = $_GET["id"];
	$type = $_GET["type"];
	
	mysql_query("UPDATE sales_customers SET type = '$type' WHERE id = '$id'") or die(mysql_error());
}
elseif ($method == "cancel") //cancel sale
{
	$id = $_GET["id"];
	$status = $_GET["status"];
	$lead_id = $_GET["lead_id"];
	$verifier = $_GET["verifier"];
	$note = $_GET["note"];
	$now = date("Y-m-d H:i:s");
	
	$q = mysql_query("SELECT centre FROM sales_customers WHERE id = '$id'") or die(mysql_error());
	$centre = mysql_fetch_row($q);
	
	mysql_query("INSERT INTO tpv_notes (id,status,lead_id,centre,verifier,note) VALUES ('$id','$status','$lead_id','$centre[0]','$verifier','". mysql_escape_string($note) . "')") or die(mysql_error());
	
	mysql_query("UPDATE sales_customers SET status = '$status', approved_timestamp = '$now' WHERE id = '$id'") or die(mysql_error());
	
	mysql_query("DELETE FROM tpv_lock WHERE user = '$verifier' LIMIT 1") or die(mysql_error());
}
elseif ($method == "submit") //submit sale
{
	$id = $_GET["id"];
	$status = "Approved";
	$industry = "TPV";
	$lead_id = $_GET["lead_id"];
	$verifier = $_GET["verifier"];
	$note = $_GET["note"];
	$now = date("Y-m-d H:i:s");
	
	$q = mysql_query("SELECT centre FROM sales_customers WHERE id = '$id'") or die(mysql_error());
	$centre = mysql_fetch_row($q);
	
	mysql_query("INSERT INTO tpv_notes (id,status,lead_id,centre,verifier,note) VALUES ('$id','$status','$lead_id','$centre[0]','$verifier','". mysql_escape_string($note) . "')") or die(mysql_error());
	
	mysql_query("UPDATE sales_customers SET status = '$status', industry = '$industry', approved_timestamp = '$now' WHERE id = '$id'") or die(mysql_error());
	
	mysql_query("DELETE FROM tpv_lock WHERE user = '$verifier' LIMIT 1") or die(mysql_error());
}
elseif ($method == "details")
{
	$id = $_GET["id"];
	
	$q = mysql_query("SELECT * FROM sales_customers WHERE id = '$id'") or die(mysql_error());
	$data = mysql_fetch_assoc($q);
	
	$q1 = mysql_query("SELECT * FROM auth WHERE user = '$data[agent]'") or die(mysql_error());
	$agent = mysql_fetch_assoc($q1);
	
	$q2 = mysql_query("SELECT * FROM qa_customers WHERE id = '$id'") or die(mysql_error());
	$data2 = mysql_fetch_assoc($q2);
	
	if ($data2["status"] == "")
	{
		$qa_status = "Not Processed";
	}
	else
	{
		$qa_status = $data2["status"];
	}
?>
<table width="100%">
<tr>
<td width="70px"><b>Sale ID</b></td>
<td colspan="3"><?php echo $data["id"]; ?></td>
</tr>
<tr>
<td><b>TPV Status</b></td>
<td><?php echo $data["status"]; ?></td>
<td width="70px"><b>QA Status</b></td>
<td><?php echo $qa_status; ?></td>
</tr>
<tr>
<td><b>Agent</b></td>
<td colspan="3"><?php echo $agent["first"] . " " . $agent["last"]; ?></td>
</tr>
<tr>
<td><b>Campaign</b></td>
<td colspan="3"><?php echo $data["campaign"]; ?></td>
</tr>
<tr>
<td><b>Type</b></td>
<td colspan="3"><?php echo $data["type"]; ?></td>
</tr>
<tr>
<td><b>Notes</b></td>
<td colspan="3"><div style="height:75px; width:100%; overflow:auto; border: 1px solid #eee;">
<?php
$q2 = mysql_query("SELECT * FROM tpv_notes WHERE id = '$id' ORDER BY timestamp DESC") or die (mysql_error());

echo "<table border='0' width='100%'>";
if (mysql_num_rows($q2) == 0)
{
	echo "<tr>";
	echo "<td>No Notes</td>";
	echo "</tr>";
}
else
{
	while ($tpv_notes = mysql_fetch_assoc($q2))
	{
		$q3 = mysql_query("SELECT * FROM auth WHERE user = '$tpv_notes[verifier]'") or die(mysql_error());
		$vname = mysql_fetch_assoc($q3);
		
		echo "<tr>";
		echo "<td>----- " . date("d/m/Y H:i:s", strtotime($tpv_notes["timestamp"])) . " - " . $vname["first"] . " " . $vname["last"] . " -----" . " (" . $tpv_notes["status"] . ")</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>" . $tpv_notes["note"] . "</td>";
		echo "</tr>";
	}
}
echo "</table>";
?>
</div></td>
</tr>
</table>
<?php
}
?>