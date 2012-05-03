<?php
$p = $_GET["p"];
$method = $_GET["method"];

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

if ($p == "users")
{
	$username = $_GET["username"];
	$password = $_GET["password"];
	$password2 = $_GET["password2"];
	$type = "Self";
	$access = "Agent";
	$status = $_GET["status"];
	$first = strtoupper(substr($_GET["first"],0,1)) . strtolower(substr($_GET["first"],1));
	$last = strtoupper(substr($_GET["last"],0,1)) . strtolower(substr($_GET["last"],1));
	$centre = $_GET["centre"];
	$alias = strtoupper(substr($_GET["alias"],0,1)) . strtolower(substr($_GET["alias"],1));
		
	if ($method == "create")
	{
		if ($first == "" || $last == "")
		{
			echo "Please enter a first and last name!";
			exit;
		}
		elseif ($alias == "")
		{
			echo "Please enter an alias!";
			exit;
		}
		elseif ($password != $password2)
		{
			echo "Passwords do not match!";
			exit;
		}
		else
		{
			if (strlen($last) == 2)
			{
				$user1 = strtolower(substr($first,0,2) . substr($last,0,2));
			}
			else
			{
				$user1 = strtolower(substr($first,0,1) . substr($last,0,3));
			}
			
			$q = mysql_query("SELECT COUNT(user) FROM `auth` WHERE `user` LIKE '$user1%'");
			$num = mysql_fetch_row($q);
			
			$username = $user1 . str_pad(($num[0]+1),3,"0",STR_PAD_LEFT);

			mysql_query("INSERT INTO `auth` (`user`,`pass`,`type`,`access`,`status`,`first`,`last`,`centre`,`alias`) VALUES ('$username','" . md5($password) . "','$type','$access','Enabled','" . mysql_escape_string($first) . "','" . mysql_escape_string($last) . "','$centre','" . mysql_escape_string($alias) . "')");
			echo "createdYou have successfully created the user!<br><br>Username: <b>$username</b>";
			exit;
		}
	}
	elseif ($method == "modify")
	{
		if ($alias == "")
		{
			echo "Please enter an alias!";
			exit;
		}
		elseif ($password != $password2)
		{
			echo "Passwords do not match!";
			exit;
		}
		else
		{
			mysql_query("UPDATE auth SET pass = '" .  md5($password) . "', alias = '" . mysql_escape_string($alias) . "' WHERE user = '$username' LIMIT 1");
			echo "modified";
			exit;
		}
	}
	elseif ($method == "disable")
	{
		mysql_query("UPDATE auth SET status = 'Disabled' WHERE user = '$username' LIMIT 1");
		exit;
	}
	elseif ($method == "enable")
	{
		mysql_query("UPDATE auth SET status = 'Enabled' WHERE user = '$username' LIMIT 1");
		exit;
	}
}
elseif ($p == "roster")
{
	$user = $_GET["user"];
	$centre = $_GET["centre"];
	for ($day = 1; $day <= 5; $day++)
	{
		$start[$day] = $_GET["start_hour$day"] . ":" . $_GET["start_minute$day"] . ":00";
		$end[$day] = $_GET["end_hour$day"] . ":" . $_GET["end_minute$day"] . ":00";
		$na[$day] = $_GET["na$day"];
	}
	
	if ($na[1] == 1) {$start[1] = ""; $end[1] = "";}
	if ($na[2] == 1) {$start[2] = ""; $end[2] = "";}
	if ($na[3] == 1) {$start[3] = ""; $end[3] = "";}
	if ($na[4] == 1) {$start[4] = ""; $end[4] = "";}
	if ($na[5] == 1) {$start[5] = ""; $end[5] = "";}
	
	if ($user == "" || $centre == "")
	{
		echo "Error! Please contact your administrator";
		exit;
	}
	for ($day = 1; $day <= 5; $day++)
	{
		if (strtotime($start[$day]) == "" && $na[$day] == 0 || strtotime($end[$day]) == "" && $na[$day] == 0)
		{
			echo "Please enter both the start and end time otherwise click on 'N/A'";
			exit;
		}
		elseif (strtotime($start[$day]) >= strtotime($end[$day]) && $na[$day] == 0)
		{
			echo "The start time must be less than the end time";
			exit;
		}
		else
		{
			$check[$day] = 1;
		}
	}
	if ($check[1] == 1 && $check[2] == 1 && $check[2] == 1 && $check[4] == 1 && $check[5] == 1)
	{
		for ($day=1; $day <= 5; $day++)
		{
			$date = date('Y-m-d', strtotime(date("Y") . "W" . date("W", strtotime("+1 week")) . $day));
			$q = mysql_query("SELECT * FROM roster WHERE agent = '$user' AND date = '$date'") or die(mysql_error());
			if (mysql_num_rows($q) == 0)
			{
				mysql_query("INSERT INTO roster (date,agent,centre,start,end,na) VALUES ('$date','$user','$centre','$start[$day]','$end[$day]','$na[$day]')") or die(mysql_error());
			}
			else
			{
				mysql_query("UPDATE roster SET start = '$start[$day]', end = '$end[$day]', na = '$na[$day]' WHERE date = '$date' AND agent = '$user'") or die(mysql_error());
			}
		}
		echo "submitted";
	}
}
elseif ($method == "get") //get sale
{
	$id = $_GET["id"];
	$centre = $_GET["centre"];
	
	$q = mysql_query("SELECT * FROM sales_customers WHERE id = '" . mysql_escape_string($id) . "'") or die(mysql_error());
	$check = mysql_fetch_assoc($q);
	
	if ($id == "" || mysql_num_rows($q) == 0)
	{
		echo "Invalid ID!";
	}
	elseif ($centre != $check["centre"])
	{
		echo "Sale Not for " . $centre;
	}
	else
	{
		echo "valid";
	}
}
elseif ($p == "edit")
{
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
}
?>