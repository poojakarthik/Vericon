<?php
$p = $_GET["p"];
$method = $_GET["method"];

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

if ($p == "announcements")
{
	if ($method == "post")
	{
		$poster = $_GET["poster"];
		$subject = $_GET["subject"];
		$message = $_GET["message"];
		$date = date("d F Y");
		if ($poster == "")
		{
			echo "Error!!! Contact Your Administrator!";
			exit;
		}
		elseif ($subject == "")
		{
			echo "Please enter a subject!";
			exit;
		}
		elseif ($message == "")
		{
			echo "Please enter a message!";
			exit;
		}
		else
		{
			mysql_query("INSERT INTO `announcements` (`date`, `poster`, `department`, `display`, `subject`, `message`) VALUES ('$date', '$poster', 'tpv', 'Yes', '" . mysql_escape_string($subject) . "', '" . mysql_escape_string($message) . "');");
			echo "posted";
			exit;
		}
	}
	elseif ($method == "edit")
	{
		$id = $_GET["id"];
		$subject = $_GET["subject"];
		$message = $_GET["message"];
		if ($subject == "")
		{
			echo "Please enter a subject!";
			exit;
		}
		elseif ($message == "")
		{
			echo "Please enter a message!";
			exit;
		}
		else
		{
			mysql_query("UPDATE announcements SET subject = '" . mysql_escape_string($subject) . "', message = '" . mysql_escape_string($message) . "' WHERE id = '$id' LIMIT 1");
			echo "edited";
			exit;
		}
	}
	elseif ($method == "hide")
	{
		$id = $_GET["id"];
		mysql_query("UPDATE announcements SET display = 'No' WHERE id = '$id' LIMIT 1");
		exit;
	}
	elseif ($method == "display")
	{
		$id = $_GET["id"];
		mysql_query("UPDATE announcements SET display = 'Yes' WHERE id = '$id' LIMIT 1");
		exit;
	}
	elseif ($method == "subject")
	{
		$id = $_GET["id"];
		$q = mysql_query("SELECT subject FROM announcements WHERE id = '$id'");
		$subject = mysql_fetch_row($q);
		echo $subject[0];
	}
	elseif ($method == "message")
	{
		$id = $_GET["id"];
		$q = mysql_query("SELECT message FROM announcements WHERE id = '$id'");
		$message = mysql_fetch_row($q);
		echo $message[0];
	}
}
elseif ($p == "users")
{
	$username = $_GET["username"];
	$password = $_GET["password"];
	$password2 = $_GET["password2"];
	$type = "TPV";
	$access = "Agent";
	$status = $_GET["status"];
	$first = strtoupper(substr($_GET["first"],0,1)) . strtolower(substr($_GET["first"],1));
	$last = strtoupper(substr($_GET["last"],0,1)) . strtolower(substr($_GET["last"],1));
		
	if ($method == "create")
	{
		if ($first == "" || $last == "")
		{
			echo "Please enter a first and last name!";
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

			mysql_query("INSERT INTO `auth` (`user`,`pass`,`type`,`access`,`status`,`first`,`last`) VALUES ('$username','" . md5($password) . "','$type','$access','Enabled','" . mysql_escape_string($first) . "','" . mysql_escape_string($last) . "')");
			echo "createdYou have successfully created the user!<br><br>Username: <b>$username</b>";
			exit;
		}
	}
	elseif ($method == "modify")
	{
		if ($password != $password2)
		{
			echo "Passwords do not match!";
			exit;
		}
		else
		{
			mysql_query("UPDATE auth SET pass = '" .  md5($password) . "' WHERE user = '$username' LIMIT 1");
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
elseif ($p == "search")
{
	?>
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="100%" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th>ID</th>
<th>Status</th>
<th>Campaign</th>
<th>Agent</th>
<th>Centre</th>
<th>Sale Date</th>
</tr>
</thead>
<tbody>
    <?php
	$query = $_GET["query"];
	
	if ($method == "line")
	{
		if (!preg_match("/^0[2378][0-9]{8}$/",$query))
		{
			echo "<tr>";
			echo "<td colspan='6' align='center'>Invalid Phone Number</td>";
			echo "</tr>";
			exit;
		}
		$q = mysql_query("SELECT * FROM sales_packages WHERE cli = '$query'") or die(mysql_error());
		
		if (mysql_num_rows($q) == 0)
		{
			echo "<tr>";
			echo "<td colspan='6' align='center'>No Results</td>";
			echo "</tr>";
			exit;
		}
		
		while ($id = mysql_fetch_row($q))
		{
			$q2 = mysql_query("SELECT * FROM sales_customers WHERE id = '$id[0]'") or die(mysql_error());
			$data = mysql_fetch_assoc($q2);
			
			echo "<tr>";
			echo "<td><a onclick='vericon_display(\"$data[id]\")' style='cursor:pointer; text-decoration:underline;'>" . $data["id"] . "</a></td>";
			echo "<td>" . $data["status"] . "</td>";
			echo "<td>" . $data["campaign"] . " " . $data["type"] . "</td>";
			echo "<td>" . $data["agent"] . "</td>";
			echo "<td>" . $data["centre"] . "</td>";
			echo "<td>" . date("d/m/Y", strtotime($data["timestamp"])) . "</td>";
			echo "</tr>";
		}
	}
	elseif ($method == "id")
	{
		$q = mysql_query("SELECT * FROM sales_customers WHERE id = '$query'") or die(mysql_error());
		$data = mysql_fetch_assoc($q);
		
		if (mysql_num_rows($q) == 0)
		{
			echo "<tr>";
			echo "<td colspan='6' align='center'>No Results</td>";
			echo "</tr>";
			exit;
		}
		
		echo "<tr>";
		echo "<td><a onclick='vericon_display(\"$data[id]\")' style='cursor:pointer; text-decoration:underline;'>" . $data["id"] . "</a></td>";
		echo "<td>" . $data["status"] . "</td>";
		echo "<td>" . $data["campaign"] . " " . $data["type"] . "</td>";
		echo "<td>" . $data["agent"] . "</td>";
		echo "<td>" . $data["centre"] . "</td>";
		echo "<td>" . date("d/m/Y", strtotime($data["timestamp"])) . "</td>";
		echo "</tr>";
	}
	elseif ($method == "lead_id")
	{
		$q = mysql_query("SELECT * FROM sales_customers WHERE lead_id = '$query'") or die(mysql_error());
		$data = mysql_fetch_assoc($q);
		
		if (mysql_num_rows($q) == 0)
		{
			echo "<tr>";
			echo "<td colspan='6' align='center'>No Results</td>";
			echo "</tr>";
			exit;
		}
		
		echo "<tr>";
		echo "<td><a onclick='vericon_display(\"$data[id]\")' style='cursor:pointer; text-decoration:underline;'>" . $data["id"] . "</a></td>";
		echo "<td>" . $data["status"] . "</td>";
		echo "<td>" . $data["campaign"] . " " . $data["type"] . "</td>";
		echo "<td>" . $data["agent"] . "</td>";
		echo "<td>" . $data["centre"] . "</td>";
		echo "<td>" . date("d/m/Y", strtotime($data["timestamp"])) . "</td>";
		echo "</tr>";
	}
	?>
</tbody>
</table>
</div>
	<?php
}
elseif ($p == "edit")
{
	if ($method == "submit")
	{
		$id = $_GET["id"];
		$verifier = $_GET["verifier"];
		$lead_id = $_GET["lead_id"];
		$status = $_GET["status"];
		$note = $_GET["note"];
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
		
		if ($id == "" || $lead_id == "" || $verifier == "")
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
			$timestamp = $data2["timestamp"];		
			
			if ($status == "Approved")
			{
				$approved_timestamp = date("Y-m-d H:i:s");
			}
			else
			{
				$approved_timestamp = "0000-00-00 00:00:00";
			}

			mysql_query("UPDATE sales_customers SET status = '$status', industry = 'TPV', approved_timestamp = '$approved_timestamp', title = '$title', firstname = '" . mysql_escape_string($first) . "', middlename = '" . mysql_escape_string($middle) . "', lastname = '" . mysql_escape_string($last) . "', dob = '" . mysql_escape_string($dob) . "', email = '" . mysql_escape_string($email) . "', mobile = '" . mysql_escape_string($mobile) . "', billing = '$billing', welcome = '$billing', physical = '$physical', postal = '$postal', id_type = '" . mysql_escape_string($id_type) . "', id_num = '" . mysql_escape_string($id_num) . "', abn = '" . mysql_escape_string($abn) . "', position = '" . mysql_escape_string($position) . "' WHERE id = '$id' LIMIT 1") or die(mysql_error());
			
			mysql_query("INSERT INTO tpv_notes (id,status,lead_id,centre,verifier,note) VALUES ('$id','$status','$lead_id','$centre[0]','$verifier','". mysql_escape_string($note) . "')") or die(mysql_error());
			
			echo "submitted";
		}
	}
}
?>