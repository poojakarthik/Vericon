<?php
mysql_connect('localhost','vericon','18450be');

$method = $_GET["method"];

if ($method == "country")
{
	$id = $_GET["id"];
	
	$q = mysql_query("SELECT campaigns.country FROM vericon.sales_customers,vericon.campaigns WHERE sales_customers.id = '$id' AND sales_customers.campaign = campaigns.campaign") or die(mysql_error());
	$data = mysql_fetch_row($q);
	
	echo strtolower($data[0]);
}
elseif ($method == "rename_rec")
{
	$lead_id = $_GET["lead_id"];
	$file = $_GET["file"];
	
	if (file_exists("/var/vtmp/" . $file["name"]))
	{
		if ($file["size"] > 102400)
		{
			exec("mv /var/vtmp/" . $file["name"] . " /var/vtmp/ns_" . $lead_id . ".gsm");
			
			if (file_exists("/var/vtmp/ns_" . $lead_id . ".gsm"))
			{
				echo 1;
			}
			else
			{
				echo 0;
			}
		}
		else
		{
			echo 0;
		}
	}
	else
	{
		echo 0;
	}
}
elseif ($method == "approve_au")
{
	$id = $_GET["id"];
	$verifier = $_GET["verifier"];
	$lead = $_GET["lead"];
	$recording = $_GET["recording"];
	$details = $_GET["details"];
	$billing_comments = $_GET["billing"];
	$other_comments = $_GET["other"];
	$timestamp = date("Y-m-d H:i:s");
	
	$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
	$data = mysql_fetch_assoc($q);
	
	$lead_id = $data["lead_id"];
	$filename = "/var/vtmp/ns_" . $lead_id . ".gsm";
	
	$q1 = mysql_query("SELECT * FROM vericon.recordings WHERE sale_id = '$id'") or die(mysql_error());
	
	if ($id == "" || $verifier == "")
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
	elseif (!file_exists($filename) && mysql_num_rows($q1) == 0)
	{
		echo "Please upload the voice file";
	}
	else
	{
		$q2 = mysql_query("SELECT * FROM vericon.qa_customers WHERE id = '$id'") or die(mysql_error());
		
		if (mysql_fetch_row($q2) == 0)
		{
			mysql_query("INSERT INTO vericon.qa_customers (id, status, lead_id, timestamp, verifier, sale_timestamp, agent, centre, campaign, type, lead_check, recording_check, details_check) VALUES ('$id', 'Approved', '$lead_id', '$timestamp', '$verifier', '$data[approved_timestamp]', '$data[agent]', '$data[centre]', '$data[campaign]', '$data[type]', '$lead', '$recording', '$details')") or die(mysql_error());
		}
		else
		{
			mysql_query("UPDATE vericon.qa_customers SET status = 'Approved', timestamp = '$timestamp', verifier = '$verifier', lead_check = '$lead', recording_check = '$recording', details_check = '$details' WHERE id = '$id' LIMIT 1") or die(mysql_error());
		}
		
		$pre_id = date("y", strtotime($data["approved_timestamp"])) . str_pad(date("z", strtotime($data["approved_timestamp"])),3,"0",STR_PAD_LEFT);
		$q3 = mysql_query("SELECT COUNT(id) FROM vericon.customers WHERE id LIKE '$pre_id%'");
		$num = mysql_fetch_row($q3);
		
		$account_number = $pre_id . str_pad(($num[0]+1),3,"0",STR_PAD_LEFT);
		
		mysql_query("INSERT INTO vericon.customers (id, status, last_edit_by, industry, lead_id, sale_id, timestamp, agent, centre, campaign, type, title, firstname, middlename, lastname, dob, email, mobile, billing, welcome, promotions, physical, postal, id_type, id_num, abn, position, best_buddy, credit, ongoing_credit, onceoff_credit, payway, dd_type, billing_comments, other_comments) VALUES ('$account_number', 'Waiting Welcome Call', '$verifier', '$data[industry]', '$data[lead_id]', '$data[id]', '$timestamp', '$data[agent]', '$data[centre]', '$data[campaign]', '$data[type]', '" . mysql_real_escape_string($data["title"]) . "', '" . mysql_real_escape_string($data["firstname"]) . "', '" . mysql_real_escape_string($data["middlename"]) . "', '" . mysql_real_escape_string($data["lastname"]) . "', '" . mysql_real_escape_string($data["dob"]) . "', '" . mysql_real_escape_string($data["email"]) . "', '" . mysql_real_escape_string($data["mobile"]) . "', '$data[billing]', '$data[welcome]', '$data[promotions]', '$data[physical]', '$data[postal]', '" . mysql_real_escape_string($data["id_type"]) . "', '" . mysql_real_escape_string($data["id_num"]) . "', '" . mysql_real_escape_string($data["abn"]) . "', '" . mysql_real_escape_string($data["position"]) . "', '" . mysql_real_escape_string($data["best_buddy"]) . "', '" . mysql_real_escape_string($data["credit"]) . "', '" . mysql_real_escape_string($data["ongoing_credit"]) . "', '" . mysql_real_escape_string($data["onceoff_credit"]) . "', '" . mysql_real_escape_string($data["payway"]) . "', '" . mysql_real_escape_string($data["dd_type"]) . "', '" . mysql_real_escape_string($billing_comments) . "', '" . mysql_real_escape_string($other_comments) . "')") or die(mysql_error());
		
		mysql_query("INSERT INTO vericon.customers_log (id, status, last_edit_by, industry, lead_id, sale_id, timestamp, agent, centre, campaign, type, title, firstname, middlename, lastname, dob, email, mobile, billing, welcome, promotions, physical, postal, id_type, id_num, abn, position, best_buddy, credit, ongoing_credit, onceoff_credit, payway, dd_type, billing_comments, other_comments) VALUES ('$account_number', 'Waiting Welcome Call', '$verifier', '$data[industry]', '$data[lead_id]', '$data[id]', '$timestamp', '$data[agent]', '$data[centre]', '$data[campaign]', '$data[type]', '" . mysql_real_escape_string($data["title"]) . "', '" . mysql_real_escape_string($data["firstname"]) . "', '" . mysql_real_escape_string($data["middlename"]) . "', '" . mysql_real_escape_string($data["lastname"]) . "', '" . mysql_real_escape_string($data["dob"]) . "', '" . mysql_real_escape_string($data["email"]) . "', '" . mysql_real_escape_string($data["mobile"]) . "', '$data[billing]', '$data[welcome]', '$data[promotions]', '$data[physical]', '$data[postal]', '" . mysql_real_escape_string($data["id_type"]) . "', '" . mysql_real_escape_string($data["id_num"]) . "', '" . mysql_real_escape_string($data["abn"]) . "', '" . mysql_real_escape_string($data["position"]) . "', '" . mysql_real_escape_string($data["best_buddy"]) . "', '" . mysql_real_escape_string($data["credit"]) . "', '" . mysql_real_escape_string($data["ongoing_credit"]) . "', '" . mysql_real_escape_string($data["onceoff_credit"]) . "', '" . mysql_real_escape_string($data["payway"]) . "', '" . mysql_real_escape_string($data["dd_type"]) . "', '" . mysql_real_escape_string($billing_comments) . "', '" . mysql_real_escape_string($other_comments) . "')") or die(mysql_error());
		
		$q4 = mysql_query("SELECT * FROM vericon.sales_packages WHERE sid = '$id'") or die(mysql_error());
		while ($packages = mysql_fetch_assoc($q4))
		{
			mysql_query("INSERT INTO vericon.packages (id, cli, plan, status, edit_by) VALUES ('$account_number', '$packages[cli]', '$packages[plan]', 'P', '$verifier')") or die(mysql_error());
			
			mysql_query("INSERT INTO vericon.packages_log (id, cli, plan, status, edit_by) VALUES ('$account_number', '$packages[cli]', '$packages[plan]', 'P', '$verifier')") or die(mysql_error());
		}
		
		$command = "mv " . $filename . " /var/rec/" . md5($account_number . date("Y-m-d H:i:s")) . ".gsm";
		exec($command);
		
		mysql_query("INSERT INTO vericon.recordings (id, sale_id, type, name) VALUES ('$account_number', '$data[id]', 'New Sale',  '" . mysql_real_escape_string(md5($account_number . date("Y-m-d H:i:s")) . ".gsm") . "')") or die(mysql_error());
		
		echo 1;
	}
}
elseif ($method == "nz_address_check")
{
	$cli = $_GET["cli"];
	
	$data = exec("perl /var/vericon/source/nz_address.pl " . $cli);
	
	echo $data;
}
elseif ($method == "approve_nz")
{
	$id = $_GET["id"];
	$verifier = $_GET["verifier"];
	$lead = $_GET["lead"];
	$recording = $_GET["recording"];
	$details = $_GET["details"];
	$billing_comments = $_GET["billing"];
	$other_comments = $_GET["other"];
	$timestamp = date("Y-m-d H:i:s");
	
	$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
	$data = mysql_fetch_assoc($q);
	
	$lead_id = $data["lead_id"];
	$filename = "/var/vtmp/ns_" . $lead_id . ".gsm";
	
	$q1 = mysql_query("SELECT * FROM vericon.recordings WHERE sale_id = '$id'") or die(mysql_error());
	
	if ($id == "" || $verifier == "")
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
	elseif (!file_exists($filename) && mysql_num_rows($q1) == 0)
	{
		echo "Please upload the voice file";
	}
	else
	{
		$q2 = mysql_query("SELECT * FROM vericon.qa_customers WHERE id = '$id'") or die(mysql_error());
		
		if (mysql_fetch_row($q2) == 0)
		{
			mysql_query("INSERT INTO vericon.qa_customers (id, status, lead_id, timestamp, verifier, sale_timestamp, agent, centre, campaign, type, lead_check, recording_check, details_check) VALUES ('$id', 'Approved', '$lead_id', '$timestamp', '$verifier', '$data[approved_timestamp]', '$data[agent]', '$data[centre]', '$data[campaign]', '$data[type]', '$lead', '$recording', '$details')") or die(mysql_error());
		}
		else
		{
			mysql_query("UPDATE vericon.qa_customers SET status = 'Approved', timestamp = '$timestamp', verifier = '$verifier', lead_check = '$lead', recording_check = '$recording', details_check = '$details' WHERE id = '$id' LIMIT 1") or die(mysql_error());
		}
		
		$pre_id = date("y", strtotime($data["approved_timestamp"])) . str_pad(date("z", strtotime($data["approved_timestamp"])),3,"0",STR_PAD_LEFT);
		$q3 = mysql_query("SELECT COUNT(id) FROM vericon.customers WHERE id LIKE '$pre_id%'");
		$num = mysql_fetch_row($q3);
		
		$account_number = $pre_id . str_pad(($num[0]+1),3,"0",STR_PAD_LEFT);
		
		mysql_query("INSERT INTO vericon.customers (id, status, last_edit_by, industry, lead_id, sale_id, timestamp, agent, centre, campaign, type, title, firstname, middlename, lastname, dob, email, mobile, billing, welcome, promotions, physical, postal, id_type, id_num, bus_name, position, best_buddy, modem_address, credit, ongoing_credit, onceoff_credit, payway, dd_type, billing_comments, other_comments) VALUES ('$account_number', 'Waiting Welcome Call', '$verifier', '$data[industry]', '$data[lead_id]', '$data[id]', '$timestamp', '$data[agent]', '$data[centre]', '$data[campaign]', '$data[type]', '" . mysql_real_escape_string($data["title"]) . "', '" . mysql_real_escape_string($data["firstname"]) . "', '" . mysql_real_escape_string($data["middlename"]) . "', '" . mysql_real_escape_string($data["lastname"]) . "', '" . mysql_real_escape_string($data["dob"]) . "', '" . mysql_real_escape_string($data["email"]) . "', '" . mysql_real_escape_string($data["mobile"]) . "', '$data[billing]', '$data[welcome]', '$data[promotions]', '$data[physical]', '$data[postal]', '" . mysql_real_escape_string($data["id_type"]) . "', '" . mysql_real_escape_string($data["id_num"]) . "', '" . mysql_real_escape_string($data["bus_name"]) . "', '" . mysql_real_escape_string($data["position"]) . "', '" . mysql_real_escape_string($data["best_buddy"]) . "', '" . mysql_real_escape_string($data["modem_address"]) . "', '" . mysql_real_escape_string($data["credit"]) . "', '" . mysql_real_escape_string($data["ongoing_credit"]) . "', '" . mysql_real_escape_string($data["onceoff_credit"]) . "', '" . mysql_real_escape_string($data["payway"]) . "', '" . mysql_real_escape_string($data["dd_type"]) . "', '" . mysql_real_escape_string($billing_comments) . "', '" . mysql_real_escape_string($other_comments) . "')") or die(mysql_error());
		
		mysql_query("INSERT INTO vericon.customers_log (id, status, last_edit_by, industry, lead_id, sale_id, timestamp, agent, centre, campaign, type, title, firstname, middlename, lastname, dob, email, mobile, billing, welcome, promotions, physical, postal, id_type, id_num, bus_name, position, best_buddy, modem_address, credit, ongoing_credit, onceoff_credit, payway, dd_type, billing_comments, other_comments) VALUES ('$account_number', 'Waiting Welcome Call', '$verifier', '$data[industry]', '$data[lead_id]', '$data[id]', '$timestamp', '$data[agent]', '$data[centre]', '$data[campaign]', '$data[type]', '" . mysql_real_escape_string($data["title"]) . "', '" . mysql_real_escape_string($data["firstname"]) . "', '" . mysql_real_escape_string($data["middlename"]) . "', '" . mysql_real_escape_string($data["lastname"]) . "', '" . mysql_real_escape_string($data["dob"]) . "', '" . mysql_real_escape_string($data["email"]) . "', '" . mysql_real_escape_string($data["mobile"]) . "', '$data[billing]', '$data[welcome]', '$data[promotions]', '$data[physical]', '$data[postal]', '" . mysql_real_escape_string($data["id_type"]) . "', '" . mysql_real_escape_string($data["id_num"]) . "', '" . mysql_real_escape_string($data["bus_name"]) . "', '" . mysql_real_escape_string($data["position"]) . "', '" . mysql_real_escape_string($data["best_buddy"]) . "', '" . mysql_real_escape_string($data["modem_address"]) . "', '" . mysql_real_escape_string($data["credit"]) . "', '" . mysql_real_escape_string($data["ongoing_credit"]) . "', '" . mysql_real_escape_string($data["onceoff_credit"]) . "', '" . mysql_real_escape_string($data["payway"]) . "', '" . mysql_real_escape_string($data["dd_type"]) . "', '" . mysql_real_escape_string($billing_comments) . "', '" . mysql_real_escape_string($other_comments) . "')") or die(mysql_error());
		
		$q4 = mysql_query("SELECT * FROM vericon.sales_packages WHERE sid = '$id'") or die(mysql_error());
		while ($packages = mysql_fetch_assoc($q4))
		{
			mysql_query("INSERT INTO vericon.packages (id, cli, plan, provider, ac_number, adsl_provider, adsl_ac_number, status, edit_by) VALUES ('$account_number', '$packages[cli]', '$packages[plan]', '$packages[provider]', '$packages[ac_number]', '$packages[adsl_provider]', '$packages[adsl_ac_number]', 'P', '$verifier')") or die(mysql_error());
			
			mysql_query("INSERT INTO vericon.packages_log (id, cli, plan, provider, ac_number, adsl_provider, adsl_ac_number, status, edit_by) VALUES ('$account_number', '$packages[cli]', '$packages[plan]', '$packages[provider]', '$packages[ac_number]', '$packages[adsl_provider]', '$packages[adsl_ac_number]', 'P', '$verifier')") or die(mysql_error());
		}
		
		$command = "mv " . $filename . " /var/rec/" . md5($account_number . date("Y-m-d H:i:s")) . ".gsm";
		exec($command);
		
		mysql_query("INSERT INTO vericon.recordings (id, sale_id, type, name) VALUES ('$account_number', '$data[id]', 'New Sale',  '" . mysql_real_escape_string(md5($account_number . date("Y-m-d H:i:s")) . ".gsm") . "')") or die(mysql_error());
		
		echo 1;
	}
}
elseif ($method == "reject")
{
	$id = $_GET["id"];
	$verifier = $_GET["verifier"];
	$reason = $_GET["reason"];
	$lead = $_GET["lead"];
	$recording = $_GET["recording"];
	$details = $_GET["details"];
	$timestamp = date("Y-m-d H:i:s");
	
	if ($id == "" || $verifier == "")
	{
		echo "Error! Please contact your administrator!";
	}
	else
	{
		$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
		$data = mysql_fetch_assoc($q);
		
		$q1 = mysql_query("SELECT * FROM vericon.qa_customers WHERE id = '$id'") or die(mysql_error());
		$check = mysql_fetch_assoc($q1);
		
		if (mysql_num_rows($q1) == 0)
		{
			mysql_query("INSERT INTO vericon.qa_customers (id, status, lead_id, timestamp, verifier, sale_timestamp, agent, centre, campaign, type, lead_check, recording_check, details_check, rejection_reason) VALUES ('$id', 'Rejected', '$data[lead_id]', '$timestamp', '$verifier', '$data[approved_timestamp]', '$data[agent]', '$data[centre]', '$data[campaign]', '$data[type]', '$lead', '$recording', '$details', '" . mysql_escape_string($reason) . "')") or die(mysql_error());
		}
		else
		{
			mysql_query("UPDATE vericon.qa_customers SET status = 'Rejected', timestamp = '$timestamp', verifier = '$verifier', lead_check = '$lead', recording_check = '$recording', details_check = '$details', rejection_reason = '" . mysql_escape_string($reason) . "' WHERE id = '$id' LIMIT 1") or die(mysql_error());
		}
					
		$command = "rm " . $filename;
		exec($command);
		
		echo "submitted";
	}
}
?>