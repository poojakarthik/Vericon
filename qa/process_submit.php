<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$method = $_GET["method"];

if ($method == "approve")
{
	$id = $_GET["id"];
	$verifier = $_GET["verifier"];
	$lead_id = $_GET["lead_id"];
	$lead = $_GET["lead"];
	$recording = $_GET["recording"];
	$details = $_GET["details"];
	$filename = "/var/vericon/upload/tmp/" . $lead_id . ".gsm";
	$timestamp = date("Y-m-d H:i:s");
	
	$qc = mysql_query("SELECT * FROM qa_customers WHERE id = '$id'") or die(mysql_error());
	
	if ($id == "" || $verifier == "" || $lead_id == "")
	{
		echo "Error! Please contact your administrator!";
	}
	elseif ($lead == 0)
	{
		echo "Please check if the lead is valid";
	}
	elseif ($recording == 0)
	{
		echo "Please check if the recording is complete and fine for processing";
	}
	elseif ($details == 0)
	{
		echo "Please check if the customer's details are correct";
	}
	elseif (!file_exists($filename) && mysql_num_rows($qc) == 0)
	{
		echo "Please upload the voice file";
	}
	else
	{
		$q = mysql_query("SELECT * FROM sales_customers WHERE id = '$id'") or die(mysql_error());
		$data = mysql_fetch_assoc($q);
		
		if (mysql_fetch_row($qc) == 0)
		{
			mysql_query("INSERT INTO qa_customers (id, status, lead_id, timestamp, verifier, sale_timestamp, agent, centre, campaign, type, lead_check, recording_check, details_check) VALUES ('$id', 'Approved', '$lead_id', '$timestamp', '$verifier', '$data[approved_timestamp]', '$data[agent]', '$data[centre]', '$data[campaign]', '$data[type]', '$lead', '$recording', '$details')") or die(mysql_error());
		}
		else
		{
			mysql_query("UPDATE qa_customers SET status = 'Approved', timestamp = '$timestamp', verifier = '$verifier', lead_check = '$lead', recording_check = '$recording', details_check = '$details' WHERE id = '$id' LIMIT 1") or die(mysql_error());
		}
		
		$q1 = mysql_query("SELECT * FROM customers WHERE sale_id = '$id'") or die(mysql_error());
		if (mysql_num_rows($q1) == 0)
		{
			$pre_id = date("y", strtotime($data["approved_timestamp"])) . str_pad(date("z", strtotime($data["approved_timestamp"])),3,"0",STR_PAD_LEFT);
			$q2 = mysql_query("SELECT COUNT(id) FROM customers WHERE id LIKE '$pre_id%'");
			$num = mysql_fetch_row($q2);
			
			$account_number = $pre_id . str_pad(($num[0]+1),3,"0",STR_PAD_LEFT);
			
			mysql_query("INSERT INTO customers (id, industry, lead_id, sale_id, timestamp, agent, centre, campaign, type, title, firstname, middlename, lastname, dob, email, mobile, billing, welcome, promotions, physical, postal, id_type, id_num, abn, position) VALUES ('$account_number', '$data[industry]', '$data[lead_id]', '$data[id]', '$timestamp', '$data[agent]', '$data[centre]', '$data[campaign]', '$data[type]', '" . mysql_escape_string($data["title"]) . "', '" . mysql_escape_string($data["firstname"]) . "', '" . mysql_escape_string($data["middlename"]) . "', '" . mysql_escape_string($data["lastname"]) . "', '" . mysql_escape_string($data["dob"]) . "', '" . mysql_escape_string($data["email"]) . "', '" . mysql_escape_string($data["mobile"]) . "', '$data[billing]', '$data[welcome]', '$data[promotions]', '$data[physical]', '$data[postal]', '" . mysql_escape_string($data["id_type"]) . "', '" . mysql_escape_string($data["id_num"]) . "', '" . mysql_escape_string($data["abn"]) . "', '" . mysql_escape_string($data["position"]) . "')") or die(mysql_error());
			
			$q3 = mysql_query("SELECT * FROM sales_packages WHERE sid = '$id'") or die(mysql_error());
			while ($packages = mysql_fetch_row($q3))
			{
				mysql_query("INSERT INTO packages (id, cli, plan) VALUES ('$account_number', '$packages[1]', '$packages[2]')") or die(mysql_error());
			}
			
			$command = "mv /var/vericon/upload/tmp/" . $data["lead_id"] . ".gsm /var/rec/" . md5($account_number) . sha1($account_number) . ".gsm";
			exec($command);
			
			mysql_query("INSERT INTO recordings (id, name) VALUES ('$account_number', '" . mysql_escape_string(md5($account_number) . sha1($account_number) . ".gsm") . "')") or die(mysql_error());
			
			echo 1;
		}
		else
		{
			echo 1;
		}
	}
}
elseif ($method == "reject")
{
	$id = $_GET["id"];
	$verifier = $_GET["verifier"];
	$status = $_GET["status"];
	$reason = $_GET["reason"];
	$timestamp = date("Y-m-d H:i:s");
	
	if ($id == "" || $verifier == "")
	{
		echo "Error! Please contact your administrator!";
	}
	elseif ($status == "In-House Rejection")
	{
		$q = mysql_query("SELECT * FROM sales_customers WHERE id = '$id'") or die(mysql_error());
		$data = mysql_fetch_assoc($q);
		
		$qc = mysql_query("SELECT * FROM qa_customers WHERE id = '$id'") or die(mysql_error());
		$check = mysql_fetch_assoc($qc);
		
		if (mysql_num_rows($qc) == 0)
		{
			mysql_query("INSERT INTO qa_customers (id, status, lead_id, timestamp, verifier, sale_timestamp, agent, centre, campaign, type, rejection_reason) VALUES ('$id', 'Rejected', '$data[lead_id]', '$timestamp', '$verifier', '$data[approved_timestamp]', '$data[agent]', '$data[centre]', '$data[campaign]', '$data[type]', '" . mysql_escape_string($reason) . "')") or die(mysql_error());
			echo "submitted";
		}
		else
		{
			if ($check["status"] == "Approved")
			{
				$q4 = mysql_query("SELECT id FROM customers WHERE sale_id = '$id'") or die(mysql_error());
				$aid = mysql_fetch_row($q4);
				
				mysql_query("UPDATE tmp_dsr SET Account_Status = 'Cancelled' WHERE Sale_ID = '$aid[0]'") or die(mysql_error());
				
				mysql_query("UPDATE qa_customers SET status = 'Rejected', timestamp = '$timestamp', verifier = '$verifier', rejection_reason = '" . mysql_escape_string($reason) . "' WHERE id = '$id' LIMIT 1") or die(mysql_error());
				echo "submitted";
			}
			else
			{
				mysql_query("UPDATE qa_customers SET status = 'Rejected', timestamp = '$timestamp', verifier = '$verifier', rejection_reason = '" . mysql_escape_string($reason) . "' WHERE id = '$id' LIMIT 1") or die(mysql_error());
				
				echo "submitted";
			}
		}
		
		$command = "rm /var/vericon/upload/tmp/" . $data["lead_id"] . ".gsm";
		exec($command);
	}
	elseif ($status == "Rework")
	{
		$q = mysql_query("SELECT * FROM sales_customers WHERE id = '$id'") or die(mysql_error());
		$data = mysql_fetch_assoc($q);
		
		mysql_query("INSERT INTO reworks (id,timestamp,centre,agent,reason) VALUES ('$id', '$timestamp', '$data[centre]',  '$data[agent]', '" . mysql_escape_string($reason) . "')") or die(mysql_error());
		mysql_query("UPDATE sales_customers SET status = 'Rework' WHERE id = '$id' LIMIT 1") or die(mysql_error());
		
		$path = "/var/vericon/upload/tmp/" . $data["lead_id"] . ".gsm";
		$command = "rm /var/vericon/upload/tmp/" . $data["lead_id"] . ".gsm";
		
		if (file_exists($path))
		{
			exec($command);
		}
		
		echo "submitted";
	}
}
?>

