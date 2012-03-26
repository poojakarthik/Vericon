<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$method = $_GET["method"];

if ($method == "submit")
{
	$id = $_GET["id"];
	$title = $_GET["title"];
	$first = strtoupper(substr($_GET["first"],0,1)) . strtolower(substr($_GET["first"],1));
	$middle = strtoupper(substr($_GET["middle"],0,1)) . strtolower(substr($_GET["middle"],1));
	$last = strtoupper(substr($_GET["last"],0,1)) . strtolower(substr($_GET["last"],1));
	$dob = $_GET["dob"];
	$email = $_GET["email"];
	$mobile = $_GET["mobile"];
	$billing = $_GET["billing"];
	$physical = $_GET["physical"];
	$postal = $_GET["postal"];
	$id_type = $_GET["id_type"];
	$id_num = $_GET["id_num"];
	$abn = preg_replace("/\s/","",$_GET["abn"]);
	$abn_status = $_GET["abn_status"];
	$position = $_GET["position"];
	
	$q = mysql_query("SELECT * FROM sales_customers WHERE id = '$id'");
	$data2 = mysql_fetch_assoc($q);
	
	$type = $data2["type"];
	
	$q1 = mysql_query("SELECT * FROM sales_packages WHERE sid = '$id'");
	
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
	} //end email check function 
	
	if ($id == "")
	{
		echo "Error! Contact your administrator!";
	}
	elseif ($title == "")
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
	elseif ($dob == "0000-00-00" || $dob == "")
	{
		echo "Please enter the customer's date of birth";
	}
	elseif ($email == "")
	{
		echo "Please enter the customer's email address";
	}
	elseif ($email != "N/A" && !check_email_address($email))
	{
		echo 'Please enter a valid email address';
	}
	elseif ($mobile == "")
	{
		echo "Please enter the customer's mobile number";
	}
	elseif ($mobile != "N/A" && !preg_match("/^04[0-9]{8}$/",$mobile))
	{
		echo "Please enter a valid mobile number";
	}
	elseif ($mobile != "N/A" && $mobile == "0400000000")
	{
		echo "Please enter a valid mobile number";
	}
	elseif ($physical == "")
	{
		echo "Please enter the customer's physical address";
	}
	elseif ($postal == "")
	{
		echo "Please enter the customer's postal address";
	}
	elseif ($type == "Residential" && $id_type == "")
	{
		echo "Please select an ID type";
	}
	elseif ($type == "Residential" && $id_num == "")
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
	elseif ($type == "Business" && $abn == "")
	{
		echo "Please enter the customer's ABN";
	}
	elseif ($type == "Business" && $abn_status != "Active")
	{
		echo "Please enter a valid ABN";
	}
	elseif ($type == "Business" && $position == "")
	{
		echo "Please enter the customer's position in the business";
	}
	elseif (mysql_num_rows($q1) == 0)
	{
		echo "Please enter a package for the customer";
	}
	else
	{
		mysql_query("UPDATE sales_customers SET title = '$title', firstname = '" . mysql_escape_string($first) . "', middlename = '" . mysql_escape_string($middle) . "', lastname = '" . mysql_escape_string($last) . "', dob = '" . mysql_escape_string($dob) . "', email = '" . mysql_escape_string($email) . "', mobile = '" . mysql_escape_string($mobile) . "', billing = '$billing', welcome = '$billing', physical = '$physical', postal = '$postal', id_type = '" . mysql_escape_string($id_type) . "', id_num = '" . mysql_escape_string($id_num) . "', abn = '" . mysql_escape_string($abn) . "', position = '" . mysql_escape_string($position) . "' WHERE id = '$id' LIMIT 1") or die(mysql_error());
		
		echo "submitted";
	}
}
?>