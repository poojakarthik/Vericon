<?php

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$id = $_GET["id"];
$action = $_GET["action"];

if ($action == "bus_info")
{
	$abn = $_GET["abn"];
	$abn_status = $_GET["abn_status"];
	$position = $_GET["position"];
	
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
elseif ($action == "name")
{
	$title = $_GET["title"];
	$first = $_GET["first"];
	$middle = $_GET["middle"];
	$last = $_GET["last"];
	
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
	
	if ($dob == "0000-00-00" || $dob == "")
	{
		echo "Please enter the customer's date of birth";
	}
	else
	{
		mysql_query("UPDATE sales_customers SET dob = '" . mysql_escape_string($dob) . "' WHERE id = '$id'") or die(mysql_error());
		echo "submitted";
	}
}
elseif ($action == "id_info")
{
	$id_type = $_GET["id_type"];
	$id_num = $_GET["id_num"];
	
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
elseif ($action == "postal")
{
	$postal = $_GET["postal"];
	
	mysql_query("UPDATE sales_customers SET postal = '" . mysql_escape_string($postal) . "' WHERE id = '$id'") or die(mysql_error());
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
elseif ($action == "email")
{
	$email = $_GET["email"];
	$billing = $_GET["billing"];
	$welcome = $_GET["welcome"];
	
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
	elseif ($email == "N/A" && $billing == "email")
	{
		echo 'Please enter a valid email address';
	}
	elseif ($email != "N/A" && !check_email_address($email))
	{
		echo 'Please enter a valid email address';
	}
	elseif ($welcome == "")
	{
		echo 'Please choose a welcome letter method';
	}
	else
	{
		mysql_query("UPDATE sales_customers SET email = '" . mysql_escape_string($email) . "', billing = '" . mysql_escape_string($billing) . "', welcome = '" . mysql_escape_string($welcome) . "' WHERE id = '$id'") or die(mysql_error());
		echo "submitted";
	}
}

?>