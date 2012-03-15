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
	elseif (!file_exists($filename))
	{
		echo "Please upload the voice file";
	}
	else
	{
		$q = mysql_query("SELECT * FROM sales_customers WHERE id = '$id'") or die(mysql_error());
		$data = mysql_fetch_assoc($q);
		
		mysql_query("INSERT INTO qa_customers (id,status,lead_id,timestamp,verifier,sale_timestamp,agent,centre,campaign,type,lead_check,recording_check,details_check) VALUES ('$id', 'Approved', '$lead_id', '$timestamp', '$verifier', '$data[approved_timestamp]', '$data[agent]', '$data[centre]', '$data[campaign]', '$data[type]', '$lead', '$recording', '$details')") or die(mysql_error());
		
		$q1 = mysql_query("SELECT * FROM customers WHERE sale_id = '$id'") or die(mysql_error());
		if (mysql_num_rows($q1) == 0)
		{
			$pre_id = date("y") . str_pad(date("z"),3,"0",STR_PAD_LEFT);
			$q2 = mysql_query("SELECT COUNT(id) FROM customers WHERE id LIKE '$pre_id%'");
			$num = mysql_fetch_row($q2);
			
			$account_number = $pre_id . str_pad(($num[0]+1),3,"0",STR_PAD_LEFT);
			
			mysql_query("INSERT INTO customers (id, industry, lead_id, sale_id, timestamp, agent, centre, campaign, type, title, firstname, middlename, lastname, dob, email, mobile, billing, welcome, promotions, physical, postal, id_type, id_num, abn, position) VALUES ('$account_number', '$data[industry]', '$data[lead_id]', '$data[id]', '$timestamp', '$data[agent]', '$data[centre]', '$data[campaign]', '$data[type]', '" . mysql_escape_string($data["title"]) . "', '" . mysql_escape_string($data["firstname"]) . "', '" . mysql_escape_string($data["middlename"]) . "', '" . mysql_escape_string($data["lastname"]) . "', '" . mysql_escape_string($data["dob"]) . "', '" . mysql_escape_string($data["email"]) . "', '" . mysql_escape_string($data["mobile"]) . "', '$data[billing]', '$data[welcome]', '$data[promotions]', '$data[physical]', '$data[postal]', '" . mysql_escape_string($data["id_type"]) . "', '" . mysql_escape_string($data["id_num"]) . "', '" . mysql_escape_string($data["abn"]) . "', '" . mysql_escape_string($data["position"]) . "')") or die(mysql_error());
			
			echo "done";
		}
		else
		{
			echo "Already Submitted!";
		}
	}
}
if ($method == "reject")
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
		
		mysql_query("INSERT INTO qa_customers (id,status,lead_id,timestamp,verifier,sale_timestamp,agent,centre,campaign,type,rejection_reason) VALUES ('$id', 'Rejected', '$data[lead_id]', '$timestamp', '$verifier', '$data[approved_timestamp]', '$data[agent]', '$data[centre]', '$data[campaign]', '$data[type]', '" . mysql_escape_string($reason) . "')") or die(mysql_error());
		echo "done";
	}
	elseif ($status == "Rework")
	{
		$q = mysql_query("SELECT * FROM sales_customers WHERE id = '$id'") or die(mysql_error());
		$data = mysql_fetch_assoc($q);
		
		mysql_query("INSERT INTO reworks (id,timestamp,centre,agent,reason) VALUES ('$id', '$timestamp', '$data[centre]',  '$data[agent]', '" . mysql_escape_string($reason) . "')") or die(mysql_error());
		mysql_query("UPDATE sales_customers SET status = 'Rework' WHERE id = '$id' LIMIT 1") or die(mysql_error());
		echo "done";
	}
}
?>

