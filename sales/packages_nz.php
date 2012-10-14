<?php
mysql_connect('localhost','vericon','18450be');

$id = $_GET["id"];
$q0 = mysql_query("SELECT campaign FROM vericon.sales_customers_temp WHERE lead_id = '" . mysql_real_escape_string($id) . "'") or die(mysql_error());
$c = mysql_fetch_row($q0);
$q = mysql_query("SELECT id FROM vericon.campaigns WHERE campaign = '" . mysql_real_escape_string($c[0]) . "'") or die(mysql_error());
$c2 = mysql_fetch_row($q);
$c_id = $c2[0];

$q = mysql_query("SELECT * FROM vericon.sales_packages_temp WHERE lead_id = '" . mysql_real_escape_string($id) . "' ORDER BY timestamp ASC");

if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td colspan='6' style='text-align:center;'>Customer has no packages!</td>";
	echo "</tr>";
}
else
{
	while ($package = mysql_fetch_assoc($q))
	{
		$q1 = mysql_query("SELECT * FROM vericon.plan_matrix WHERE id = '$package[plan]' AND campaign = '" . mysql_real_escape_string($c_id) . "'") or die(mysql_error());
		$package_name = mysql_fetch_assoc($q1);
		
		echo "<tr>";
		echo "<td>" . $package["cli"] . "</td>";
		echo "<td>" . $package_name["name"] . "</td>";
		if ($package["adsl_provider"] == "")
		{
			echo "<td>" . $package["provider"] . "</td>";
		}
		else
		{
			echo "<td>PSTN - " . $package["provider"] . "<br>ADSL - " . $package["adsl_provider"] . "</td>";
		}
		if ($package["adsl_ac_number"] == "")
		{
			echo "<td>" . $package["ac_number"] . "</td>";
		}
		else
		{
			echo "<td>PSTN - " . $package["ac_number"] . "<br>ADSL - " . $package["adsl_ac_number"] . "</td>";
		}
		echo "<td style='text-align:center;'><input type='button' onclick='Edit_Package(\"$package[cli]\",\"$package[plan]\")' class='icon_edit' title='Edit'></td>";
		echo "<td style='text-align:center;'><input type='button' onclick='Delete_Package(\"$package[cli]\")' class='icon_delete' title='Delete'></td>";
		echo "</tr>";
	}
}
?>