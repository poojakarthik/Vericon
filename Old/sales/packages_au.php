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
	echo "<td colspan='4' style='text-align:center;'>Customer has no packages!</td>";
	echo "</tr>";
}
else
{
	while ($package = mysql_fetch_assoc($q))
	{
		$class1 = "";
		$class2 = "";
		$warning = "";
		$q1 = mysql_query("SELECT * FROM vericon.plan_matrix WHERE id = '$package[plan]' AND campaign = '" . mysql_real_escape_string($c_id) . "'") or die(mysql_error());
		$package_name = mysql_fetch_assoc($q1);
		if (substr($package_name["type"],0,4) == "ADSL" || $package_name["type"] == "Bundle")
		{
			preg_match('/0([2378])([0-9]{8})/',$package["cli"],$d);
			$q2 = mysql_query("SELECT * FROM adsl.Enabled_Exchanges WHERE Range_From <= $d[2] AND Range_To >= $d[2] AND AC = '$d[1]'") or die(mysql_error());
			
			if(mysql_num_rows($q2) == 0)
			{
				$class1 = "class='ui-state-highlight' style='padding:0 .7em;'";
				$class2 = "<span class='ui-icon ui-icon-alert' style='float:left; margin-right:.3em;'></span>";
				$warning = " -- ADSL may not be available";
			}
		}
		echo "<tr $class1>";
		echo "<td>" . $package["cli"] . "</td>";
		echo "<td>$class2" . $package_name["name"] . "$warning</td>";
		echo "<td style='text-align:center;'><input type='button' onclick='Edit_Package(\"$package[cli]\",\"$package[plan]\")' class='icon_edit' title='Edit'></td>";
		echo "<td style='text-align:center;'><input type='button' onclick='Delete_Package(\"$package[cli]\")' class='icon_delete' title='Delete'></td>";
		echo "</tr>";
	}
}
?>