<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];

if ($method == "get") //get sale
{
	$id = $_GET["id"];
	$user = $_GET["user"];
	
	mysql_query("DELETE FROM vericon.verification_lock WHERE user = '$user'") or die(mysql_error());
	
	$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE id = '" . mysql_real_escape_string($id) . "'") or die(mysql_error());
	$check = mysql_fetch_assoc($q);
	
	$q1 = mysql_query("SELECT user FROM vericon.verification_lock WHERE id = '" . mysql_real_escape_string($id) . "'") or die(mysql_error());
	$lock = mysql_fetch_assoc($q1);
	
	if ($id == "" || mysql_num_rows($q) == 0)
	{
		echo "Invalid ID";
	}
	elseif ($check["status"] == "Approved")
	{
		echo "Sale is already approved";
	}
	elseif (mysql_num_rows($q1) != 0)
	{
		$q2 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$lock[user]'") or die(mysql_error());
		$lv = mysql_fetch_row($q2);
		
		echo "Sale is locked by " . $lv[0] . " " . $lv[1];
	}
	else
	{
		mysql_query("INSERT INTO vericon.verification_lock (id, user) VALUES ('$id', '$user') ON DUPLICATE KEY UPDATE id = '$id'") or die(mysql_error());
		
		echo "valid";
	}
}
elseif ($method == "change_type") //change type
{
	$id = $_GET["id"];
	$verifier = $_GET["verifier"];
	$type = $_GET["type"];
	$now = date("Y-m-d H:i:s");
	
	$q = mysql_query("SELECT centre,lead_id FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	if ($id == "" || $verifier == "" || $type == "")
	{
		echo "Error! Please contact your administrator";
	}
	else
	{
		mysql_query("INSERT INTO vericon.tpv_notes (id, status, lead_id, centre, verifier, note) VALUES ('$id', 'Declined', '$data[1]', '$data[0]', '$verifier', 'Changed sale to " . $type . "')") or die(mysql_error());
		
		mysql_query("UPDATE vericon.sales_customers SET status = 'Declined', approved_timestamp = '$now', type = '$type' WHERE id = '$id'") or die(mysql_error());
		
		$q1 = mysql_query("SELECT * FROM vericon.sales_packages WHERE sid = '$id'") or die(mysql_error());
		while ($pack = mysql_fetch_assoc($q1))
		{
			if ($type == "Business")
			{
				$new_plan = substr($pack["plan"],0,3) . "B" . substr($pack["plan"],4);
			}
			elseif ($type == "Residential")
			{
				$new_plan = substr($pack["plan"],0,3) . "R" . substr($pack["plan"],4);
			}
			
			mysql_query("UPDATE vericon.sales_packages SET plan = '$new_plan' WHERE sid = '$id' AND cli = '$pack[cli]'") or die(mysql_error());
		}
		
		echo "done";
	}
}
elseif ($method == "add") //add package
{
	$sid = $_GET["id"];
	$cli = $_GET["cli"];
	$plan = $_GET["plan"];
	$week = date("W");
	
	$ch2 = mysql_query("SELECT COUNT(cli) FROM vericon.sales_packages WHERE cli = '$cli' AND WEEK(timestamp) = '$week'");
	$check2 = mysql_fetch_row($ch2);
	
	$ch3 = mysql_query("SELECT COUNT(cli) FROM vericon.sct_dnc WHERE cli = '" . mysql_real_escape_string($cli) . "'");
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
		mysql_query("INSERT INTO vericon.sales_packages (sid, cli, plan) VALUES ('$sid', '" . mysql_real_escape_string($cli) . "', '$plan')") or die(mysql_error());
		echo "added";
	}
}
elseif ($method == "edit") //edit package
{
	$sid = $_GET["id"];
	$cli = $_GET["cli"];
	$plan = $_GET["plan"];
	$cli2 = $_GET["cli2"];
	
	$ch2 = mysql_query("SELECT COUNT(cli) FROM vericon.sales_packages WHERE cli = '$cli'");
	$check2 = mysql_fetch_row($ch2);
	
	$ch3 = mysql_query("SELECT COUNT(cli) FROM vericon.sct_dnc WHERE cli = '" . mysql_real_escape_string($cli) . "'");
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
		mysql_query("DELETE FROM vericon.sales_packages WHERE sid = '$sid' AND cli = '$cli2' LIMIT 1") or die(mysql_error());
		mysql_query("INSERT INTO vericon.sales_packages (sid, cli, plan) VALUES ('$sid', '" . mysql_real_escape_string($cli) . "', '$plan')") or die(mysql_error());
		echo "editted";
	}
}
elseif ($method == "delete") //delete package
{
	$sid = $_GET["id"];
	$cli = $_GET["cli"];
	
	mysql_query("DELETE FROM vericon.sales_packages WHERE sid = '$sid' AND cli = '$cli' LIMIT 1") or die(mysql_error());
	
	echo "deleted";
}
elseif ($method == "cancel") //cancel sale
{
	$id = $_GET["id"];
	$status = $_GET["status"];
	$lead_id = $_GET["lead_id"];
	$verifier = $_GET["verifier"];
	$note = $_GET["note"];
	$now = date("Y-m-d H:i:s");
	
	$q = mysql_query("SELECT centre,lead_id FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	if ($id == "" || $verifier == "")
	{
		echo "Error! Please contact your administrator";
	}
	elseif ($status == "")
	{
		echo "Please select a status";
	}
	elseif ($note == "")
	{
		echo "Please enter a note";
	}
	else
	{
		mysql_query("INSERT INTO vericon.tpv_notes (id, status, lead_id, centre, verifier, note) VALUES ('$id', '$status', '$data[1]', '$data[0]', '$verifier', '". mysql_real_escape_string($note) . "')") or die(mysql_error());
		
		mysql_query("UPDATE vericon.sales_customers SET status = '$status', approved_timestamp = '$now' WHERE id = '$id'") or die(mysql_error());
		
		mysql_query("DELETE FROM vericon.verification_lock WHERE id = '$id'") or die(mysql_error());
		
		echo "done";
	}
}
elseif ($method == "submit") //submit sale
{
	$id = $_GET["id"];
	$status = "Approved";
	$industry = "TPV";
	$verifier = $_GET["verifier"];
	$note = $_GET["note"];
	$now = date("Y-m-d H:i:s");
	
	$q = mysql_query("SELECT centre,lead_id FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	mysql_query("INSERT INTO vericon.tpv_notes (id, status, lead_id, centre, verifier, note) VALUES ('$id', '$status', '$data[1]', '$data[0]', '$verifier', '". mysql_real_escape_string($note) . "')") or die(mysql_error());
	
	mysql_query("UPDATE vericon.sales_customers SET status = '$status', industry = '$industry', approved_timestamp = '$now' WHERE id = '$id'") or die(mysql_error());
	
	mysql_query("DELETE FROM vericon.verification_lock WHERE id = '$id'") or die(mysql_error());
	
	echo "done";
}
?>