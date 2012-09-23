<?php

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$id = $_GET["id"];
$action = $_GET["action"];

if ($action == "current_provider")
{
	$provider = $_GET["provider"];
	$c_ac_number = trim($_GET["c_ac_number"]);
	
	if ($provider == "")
	{
		echo "Please select a valid provider";
	}
	elseif ($c_ac_number == "")
	{
		echo "Please enter the customer's current account number";
	}
	else
	{
		//mysql_query("UPDATE sales_customers SET provider = '" . mysql_real_escape_string($provider) . "', c_ac_number = '" . mysql_real_escape_string($c_ac_number) . "' WHERE id = '$id'") or die(mysql_error());
		echo "submitted";
	}

}
elseif ($action == "bus_info")
{
	$abn = preg_replace("/\s/","",$_GET["abn"]);
	$abn_status = $_GET["abn_status"];
	$position = trim($_GET["position"]);
	
	if ($abn_status != "Active")
	{
		echo "Please enter a valid ABN";
	}
	elseif ($position == "")
	{
		echo "Please enter the customer's position in the business";
	}
	else
	{
		mysql_query("UPDATE sales_customers SET abn = '" . mysql_escape_string($abn) . "', position = '" . mysql_escape_string($position) . "' WHERE id = '$id'") or die(mysql_error());
		echo "submitted";
	}

}
elseif ($action == "bus_info2")
{
	$bus_name = trim(strtoupper($_GET["bus_name"]));
	$position = trim($_GET["position"]);
	
	if ($bus_name == "")
	{
		echo "Please enter a valid business name";
	}
	elseif ($position == "")
	{
		echo "Please enter the customer's position in the business";
	}
	else
	{
		//mysql_query("UPDATE sales_customers SET bus_name = '" . mysql_real_escape_string($bus_name) . "', position = '" . mysql_real_escape_string($position) . "' WHERE id = '$id'") or die(mysql_error());
		echo "submitted";
	}

}
elseif ($action == "name")
{
	$title = $_GET["title"];
	$first = trim(strtoupper(substr($_GET["first"],0,1)) . strtolower(substr($_GET["first"],1)));
	$middle = trim(strtoupper(substr($_GET["middle"],0,1)) . strtolower(substr($_GET["middle"],1)));
	$last = trim(strtoupper(substr($_GET["last"],0,1)) . strtolower(substr($_GET["last"],1)));
	
	if ($title == "")
	{
		echo "Please select a title";
	}
	elseif ($first == "")
	{
		echo "Please enter the customer's first name";
	}
	elseif ($last == "")
	{
		echo "Please enter the customer's last name";
	}
	else
	{
		mysql_query("UPDATE sales_customers SET title = '" . mysql_escape_string($title) . "', firstname = '" . mysql_escape_string($first) . "', middlename = '" . mysql_escape_string($middle) . "', lastname = '" . mysql_escape_string($last) . "' WHERE id = '$id'") or die(mysql_error());
		echo "submitted";
	}
}
elseif ($action == "dob")
{
	$dob = $_GET["dob"];
	
	mysql_query("UPDATE sales_customers SET dob = '" . mysql_escape_string($dob) . "' WHERE id = '$id'") or die(mysql_error());
	echo "submitted";
}
elseif ($action == "id_info")
{
	$id_type = $_GET["id_type"];
	$id_num = trim($_GET["id_num"]);
	
	if ($id_type == "")
	{
		echo "Please select an ID type";
	}
	elseif ($id_num == "")
	{
		echo "Please enter the customer's ID number";
	}
	elseif ($id_type == "Medicare Card" && !preg_match("/^[0-9]{10}$/",$id_num))
	{
		echo "Please enter a valid Medicare Card number";
	}
	elseif ($id_type == "Healthcare Card" && !preg_match("/^[0-9]{9}[a-zA-Z]$/",$id_num))
	{
		echo "Please enter a valid Healthcare Card number";
	}
	elseif ($id_type == "Pension Card" && !preg_match("/^[0-9]{9}[a-zA-Z]$/",$id_num))
	{
		echo "Please enter a valid Pension Card number";
	}
	else
	{
		mysql_query("UPDATE sales_customers SET id_type = '" . mysql_escape_string($id_type) . "', id_num = '" . mysql_escape_string($id_num) . "' WHERE id = '$id'") or die(mysql_error());
		echo "submitted";
	}
}
elseif ($action == "physical")
{
	$physical = $_GET["physical"];
	
	mysql_query("UPDATE sales_customers SET physical = '" . mysql_escape_string($physical) . "' WHERE id = '$id'") or die(mysql_error());
	echo "submitted";
}
elseif ($action == "physical2")
{
	$physical = $_GET["physical"];
	
	mysql_query("UPDATE sales_customers SET physical = '" . mysql_escape_string($physical) . "' WHERE id = '$id'") or die(mysql_error());
	echo "submitted";
}
elseif ($action == "postal")
{
	$postal = $_GET["postal"];
	
	if ($postal == "same")
	{
		$q = mysql_query("SELECT physical FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
		$da = mysql_fetch_row($q);
		
		mysql_query("UPDATE sales_customers SET postal = '" . mysql_real_escape_string($da[0]) . "' WHERE id = '$id'") or die(mysql_error());
	}
	else
	{
		mysql_query("UPDATE sales_customers SET postal = '" . mysql_real_escape_string($postal) . "' WHERE id = '$id'") or die(mysql_error());
	}
	
	echo "submitted";
}
elseif ($action == "postal2")
{
	$postal = $_GET["postal"];
	
	if ($postal == "same")
	{
		$q = mysql_query("SELECT physical FROM vericon.sales_customers WHERE id = '$id'") or die(mysql_error());
		$da = mysql_fetch_row($q);
		
		mysql_query("UPDATE sales_customers SET postal = '" . mysql_real_escape_string($da[0]) . "' WHERE id = '$id'") or die(mysql_error());
	}
	else
	{
		mysql_query("UPDATE sales_customers SET postal = '" . mysql_real_escape_string($postal) . "' WHERE id = '$id'") or die(mysql_error());
	}
	
	echo "submitted";
}
elseif ($action == "mobile")
{
	$mobile = $_GET["mobile"];
	
	if ($mobile == "")
	{
		echo "Please enter the customer's mobile number";
	}
	elseif ($mobile != "N/A" && !preg_match("/^04[0-9]{8}$/",$mobile))
	{
		echo "Please enter a valid mobile number";
	}
	else
	{
		mysql_query("UPDATE sales_customers SET mobile = '" . mysql_escape_string($mobile) . "' WHERE id = '$id'") or die(mysql_error());
		echo "submitted";
	}
}
elseif ($action == "mobile2")
{
	$mobile = $_GET["mobile"];
	
	if ($mobile == "")
	{
		echo "Please enter the customer's mobile number";
	}
	elseif ($mobile != "N/A" && !preg_match("/^02[0-9]{8}$/",$mobile))
	{
		echo "Please enter a valid mobile number";
	}
	else
	{
		mysql_query("UPDATE sales_customers SET mobile = '" . mysql_escape_string($mobile) . "' WHERE id = '$id'") or die(mysql_error());
		echo "submitted";
	}
}
elseif ($action == "email")
{
	$email = $_GET["email"];
	$promotions = $_GET["promotions"];
	
	function check_email_address($email) //email validation function
	{
		if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) 
		{
			return false;
		}
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++) 
		{
			if(!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",$local_array[$i]))
			{
				return false;
			}
		}
		if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1]))
		{
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2)
			{
				return false;
			}
			for ($i = 0; $i < sizeof($domain_array); $i++)
			{
				if(!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$",$domain_array[$i]))
				{
					return false;
				}
			}
		}
		if(!checkdnsrr($email_array[1],'MX'))
		{
			return false;
		}
		return true;
	}
	
	if ($email == "")
	{
		echo "Please enter the customer's email address";
	}
	elseif ($email != "N/A" && !check_email_address($email))
	{
		echo 'Please enter a valid email address';
	}
	elseif ($promotions == "")
	{
		echo 'Please select if the customer allows promotions';
	}
	else
	{
		if ($email == "N/A") { $billing = "post"; } else { $billing = "email"; }
		
		mysql_query("UPDATE sales_customers SET email = '" . mysql_real_escape_string($email) . "', billing = '" . mysql_real_escape_string($billing) . "', welcome = '" . mysql_real_escape_string($billing) . "', promotions = '" . mysql_real_escape_string($promotions) . "' WHERE id = '$id'") or die(mysql_error());
		echo "submitted";
	}
}
elseif ($action == "email2")
{
	$email = $_GET["email"];
	$promotions = $_GET["promotions"];
	
	function check_email_address($email) //email validation function
	{
		if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) 
		{
			return false;
		}
		$email_array = explode("@", $email);
		$local_array = explode(".", $email_array[0]);
		for ($i = 0; $i < sizeof($local_array); $i++) 
		{
			if(!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$",$local_array[$i]))
			{
				return false;
			}
		}
		if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1]))
		{
			$domain_array = explode(".", $email_array[1]);
			if (sizeof($domain_array) < 2)
			{
				return false;
			}
			for ($i = 0; $i < sizeof($domain_array); $i++)
			{
				if(!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$",$domain_array[$i]))
				{
					return false;
				}
			}
		}
		if(!checkdnsrr($email_array[1],'MX'))
		{
			return false;
		}
		return true;
	}
	
	if ($email == "")
	{
		echo "Please enter the customer's email address";
	}
	elseif (!check_email_address($email))
	{
		echo 'Please enter a valid email address';
	}
	elseif ($promotions == "")
	{
		echo 'Please select if the customer allows promotions';
	}
	else
	{
		if ($email == "N/A") { $billing = "post"; } else { $billing = "email"; }
		
		mysql_query("UPDATE sales_customers SET email = '" . mysql_real_escape_string($email) . "', billing = '" . mysql_real_escape_string($billing) . "', welcome = '" . mysql_real_escape_string($billing) . "', promotions = '" . mysql_real_escape_string($promotions) . "' WHERE id = '$id'") or die(mysql_error());
		echo "submitted";
	}
}
elseif ($action == "best_buddy")
{
	$best_buddy = $_GET["best_buddy"];
	
	if ($best_buddy == "")
	{
		echo "Please enter the customer's nominated best buddy";
	}
	elseif ($best_buddy != "N/A" && !preg_match("/^04[0-9]{8}$/",$best_buddy))
	{
		echo "Please enter a valid mobile number";
	}
	else
	{
		mysql_query("UPDATE sales_customers SET best_buddy = '" . mysql_real_escape_string($best_buddy) . "' WHERE id = '$id'") or die(mysql_error());
		echo "submitted";
	}
}
?>