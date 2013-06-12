<?php
$method = $_GET["method"];
$date = date('d\/m\/Y');
$time = date('g:i A');
$staff_name = $_GET["staff_name"];
$campaign = $_GET["campaign"];
$customer_name = $_GET["customer_name"];
$customer_contact = $_GET["customer_contact"];
$acc_number = $_GET["acc_number"];
$service_type = $_GET["service_type"];
$call_type = $_GET["call_type"];
$comments = $_GET["comments"];

mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

if ($method == "create")
{	
	if ($date == "" || $time == "" || $staff_name == "")
	{
		echo "Error!!! Contact Your Administrator!";
		exit;
	}
	elseif ($campaign == "")
	{
		echo "Please select a campaign!";
		exit;
	}
	elseif ($customer_name == "")
	{
		echo "Please enter the customer's name!";
		exit;
	}
	elseif ($acc_number == "")
	{
		echo "Please enter the account number!";
		exit;
	}
	elseif ($service_type == "")
	{
		echo "Please select the service type!";
		exit;
	}
	elseif ($call_type == "")
	{
		echo "Please select the call type!";
		exit;
	}
	elseif ($comments == "")
	{
		echo "Please enter a detailed comment!";
		exit;
	}
	else
	{
		mysql_query("INSERT INTO `csform` (`status`, `date`, `time`, `staff_name`, `campaign`, `customer_name`, `customer_contact`, `account_number`, `type`, `call`, `comments`) VALUES ('Queue', '$date', '$time', '$staff_name', '$campaign', '$customer_name', '$customer_contact', '$acc_number', '$service_type', '$call_type', '$comments');");
		echo "created";
		exit;
	}
}
?>