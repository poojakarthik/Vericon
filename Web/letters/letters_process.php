<?php
$mysqli = new mysqli("localhost","vericon","18450be");

$type = $_GET["type"];
$file = $_GET["file"];

exec("dos2unix /var/vericon/temp/" . $file["name"]);

$csv = array_map("str_getcsv", file("/var/vericon/temp/" . $file["name"], FILE_SKIP_EMPTY_LINES));
$keys = array_shift($csv);

foreach ($csv as $i=>$row) {
    $csv[$i] = array_combine($keys, $row);
}

$row_number = 2;

if ($type == "Customers")
{
	foreach ($csv as $row) {
		$group = $mysqli->query("SELECT `group` FROM `letters`.`campaigns` WHERE `group` = '" . $mysqli->real_escape_string($row["group"]) . "'") or die($mysqli->error);
		$campaign = $mysqli->query("SELECT `id` FROM `letters`.`campaigns` WHERE `id` = '" . $mysqli->real_escape_string($row["campaign"]) . "'") or die($mysqli->error);
		
		if ($row["id"] == "")
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 2, "0", STR_PAD_LEFT) . " - Missing ID</p>";
		}
		elseif ($row["letter_type"] != "N" && $row["letter_type"] != "U" && $row["letter_type"] != "W")
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 2, "0", STR_PAD_LEFT) . " - Invalid Letter Type</p>";
		}
		elseif ($row["sale_date"] == "")
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 2, "0", STR_PAD_LEFT) . " - Missing Sale Date</p>";
		}
		elseif ($group->num_rows <= 0)
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 2, "0", STR_PAD_LEFT) . " - Invalid Group</p>";
		}
		elseif ($campaign->num_rows <= 0)
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 2, "0", STR_PAD_LEFT) . " - Invalid Campaign</p>";
		}
		elseif ($row["type"] != "Business" && $row["type"] != "Residential")
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 2, "0", STR_PAD_LEFT) . " - Invalid Type</p>";
		}
		elseif ($row["title"] == "" || $row["firstname"] == "" || $row["lastname"] == "")
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 2, "0", STR_PAD_LEFT) . " - Name is Incomplete</p>";
		}
		elseif ($row["type"] == "Business" && $row["bus_name"] == "")
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 2, "0", STR_PAD_LEFT) . " - Missing Business Name</p>";
		}
		elseif ($row["delivery"] == "E" && $row["email"] == "")
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 2, "0", STR_PAD_LEFT) . " - Missing Email</p>";
		}
		elseif ($row["address_line1"] == "" || $row["address_line2"] == "")
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 2, "0", STR_PAD_LEFT) . " - Incomplete Address</p>";
		}
		elseif ($row["delivery"] != "E" && $row["delivery"] != "P")
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 2, "0", STR_PAD_LEFT) . " - Invalid Delivery</p>";
		}
		elseif ($row["package_type"] != "Landline" && $row["package_type"] != "Broadband" && $row["package_type"] != "Bundle")
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 2, "0", STR_PAD_LEFT) . " - Invalid Package Type</p>";
		}
		else
		{
			$s_d = explode("/", trim($row["sale_date"]));
			$s_d = $s_d[2] . "-" . $s_d[1] . "-" . $s_d[0];
			$sale_date = date("Y-m-d", strtotime($s_d));
			
			$mysqli->query("INSERT IGNORE INTO `letters`.`customers` (`id`, `letter_type`, `sale_date`, `group`, `campaign`, `type`, `title`, `firstname`, `lastname`, `bus_name`, `email`, `address_line1`, `address_line2`, `delivery`, `package_type`) VALUES ('" . $mysqli->real_escape_string($row["id"]) . "', '" . $mysqli->real_escape_string($row["letter_type"]) . "', '" . $mysqli->real_escape_string($sale_date) . "', '" . $mysqli->real_escape_string($row["group"]) . "', '" . $mysqli->real_escape_string($row["campaign"]) . "', '" . $mysqli->real_escape_string($row["type"]) . "', '" . $mysqli->real_escape_string($row["title"]) . "', '" . $mysqli->real_escape_string($row["firstname"]) . "', '" . $mysqli->real_escape_string($row["lastname"]) . "', '" . $mysqli->real_escape_string($row["bus_name"]) . "', '" . $mysqli->real_escape_string($row["email"]) . "', '" . $mysqli->real_escape_string($row["address_line1"]) . "', '" . $mysqli->real_escape_string($row["address_line2"]) . "', '" . $mysqli->real_escape_string($row["delivery"]) . "', '" . $mysqli->real_escape_string($row["package_type"]) . "')") or die($mysqli->error);
			
			echo "<p class='good'>Row " . str_pad($row_number, 2, "0", STR_PAD_LEFT) . " - All Goods in Da Row</p>";
		}
		
		$group->free();
		$campaign->free();
		
		$row_number++;
	}
}

if ($type == "Packages")
{
	foreach ($csv as $row) {
		$id = $mysqli->query("SELECT `campaign` FROM `letters`.`customers` WHERE `id` = '" . $mysqli->real_escape_string($row["id"]) . "'") or die($mysqli->error);
		$campaign = $id->fetch_row();
		
		$plan = $mysqli->query("SELECT `id` FROM `letters`.`plan_matrix` WHERE `s_id` = '" . $mysqli->real_escape_string($row["plan"]) . "' AND `campaign` = '" . $mysqli->real_escape_string($campaign[0]) . "'") or die($mysqli->error);
		
		if ($id->num_rows <= 0)
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 3, "0", STR_PAD_LEFT) . " - ID Doesn't Exist</p>";
		}
		elseif (!preg_match("/^[2378][0-9]{8}$/", $row["cli"]) && $row["cli"] != "New Connection")
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 2, "0", STR_PAD_LEFT) . " - Invalid CLI</p>";
		}
		elseif ($plan->num_rows <= 0)
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 2, "0", STR_PAD_LEFT) . " - Invalid Plan Code</p>";
		}
		else
		{
			$plan_code = $plan->fetch_row();
			
			if (preg_match("/^[2378][0-9]{8}$/", $row["cli"])) {
				$cli = $row["cli"];
			} else {
				$cli = mt_rand(1, 99999999);
			}
			
			$mysqli->query("INSERT IGNORE INTO `letters`.`packages` (`id`, `cli`, `plan`) VALUES ('" . $mysqli->real_escape_string($row["id"]) . "', '" . $mysqli->real_escape_string($cli) . "', '" . $mysqli->real_escape_string($plan_code[0]) . "')") or die($mysqli->error);
			
			echo "<p class='good'>Row " . str_pad($row_number, 2, "0", STR_PAD_LEFT) . " - All Goods in Da Row</p>";
		}
		
		$id->free();
		$plan->free();
		
		$row_number++;
	}
}

if ($type == "ADSL")
{
	$err = 0;
	
	foreach ($csv as $row) {		
		if ($row["Unique User Name"] != "") {
			$q = $mysqli->query("SELECT `id`, `group` FROM `letters`.`campaigns` WHERE `id` LIKE '" . substr($row["Unique User Name"], 1, 2) . "_'") or die($mysqli->error);
			$da = $q->fetch_row();
			$q->free();
		}
		
		if (!preg_match("/^[0-9]{8}$/", $row["Order Number"]))
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 3, "0", STR_PAD_LEFT) . " - Invalid Order Number</p>";
			$err++;
		}
		elseif ($row["Subscriber Name"] == "")
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 3, "0", STR_PAD_LEFT) . " - Please enter a Subscriber Name</p>";
			$err++;
		}
		elseif ($row["Unique User Name"] == "")
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 3, "0", STR_PAD_LEFT) . " - Please enter a Unique User Name</p>";
			$err++;
		}
		elseif ($da[0] == "")
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 3, "0", STR_PAD_LEFT) . " - Invalid Campaign. Please Check the Unique User Name</p>";
			$err++;
		}
		elseif ($row["Unique Password"] == "")
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 3, "0", STR_PAD_LEFT) . " - Please enter a Unique Password</p>";
			$err++;
		}
		elseif (!preg_match("/^[2378][0-9]{8}$/", substr($row["Subscriber Ph No"], 1)))
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 2, "0", STR_PAD_LEFT) . " - Invalid Subscriber Ph No</p>";
			$err++;
		}
		elseif ($row["Street Number & Name"] == "")
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 3, "0", STR_PAD_LEFT) . " - Please enter a Street Number & Name</p>";
			$err++;
		}
		elseif ($row["Suburb/City"] == "")
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 3, "0", STR_PAD_LEFT) . " - Please enter a Suburb/City</p>";
			$err++;
		}
		elseif ($row["State"] == "")
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 3, "0", STR_PAD_LEFT) . " - Please enter a State</p>";
			$err++;
		}
		elseif ($row["PostCode"] == "")
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 3, "0", STR_PAD_LEFT) . " - Please enter a PostCode</p>";
			$err++;
		}
		elseif ($row["SSID"] == "")
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 3, "0", STR_PAD_LEFT) . " - Please enter a SSID</p>";
			$err++;
		}
		elseif ($row["SSID Password"] == "")
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 3, "0", STR_PAD_LEFT) . " - Please enter a SSID Password</p>";
			$err++;
		}
		elseif ($row["ADSL Plan"] == "")
		{
			echo "<p class='error'><b>Error: </b> Row " . str_pad($row_number, 3, "0", STR_PAD_LEFT) . " - Please enter a ADSL Plan</p>";
			$err++;
		}
		else
		{
			$address_line2 = $row["Suburb/City"] . " " . $row["State"] . " " . $row["PostCode"];
			
			$mysqli->query("INSERT IGNORE INTO `letters`.`adsl` (`id`, `group`, `campaign`, `name`, `bus_name`, `address_line1`, `address_line2`, `cli`, `plan`, `user`, `pass`, `ssid`, `w_pass`) VALUES ('" . $mysqli->real_escape_string($row["Order Number"]) . "', '" . $mysqli->real_escape_string($da[1]) . "', '" . $mysqli->real_escape_string($da[0]) . "', '" . $mysqli->real_escape_string($row["Subscriber Name"]) . "', '" . $mysqli->real_escape_string($row["Company Name"]) . "', '" . $mysqli->real_escape_string($row["Street Number & Name"]) . "', '" . $mysqli->real_escape_string($address_line2) . "', '" . $mysqli->real_escape_string(substr($row["Subscriber Ph No"], 1)) . "', '" . $mysqli->real_escape_string($row["ADSL Plan"]) . "', '" . $mysqli->real_escape_string($row["Unique User Name"]) . "', '" . $mysqli->real_escape_string($row["Unique Password"]) . "', '" . $mysqli->real_escape_string($row["SSID"]) . "', '" . $mysqli->real_escape_string($row["SSID Password"]) . "')") or die($mysqli->error);
		}
		
		$row_number++;
	}
	
	if ($err <= 0) {
		echo "<p class='good'>All Rows Uploaded :)</p>";
	}
}

unlink("/var/vericon/temp/" . $file["name"]);

$mysqli->close();
?>