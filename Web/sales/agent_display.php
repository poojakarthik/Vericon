<?php
mysql_connect('localhost','vericon','18450be');

$centre = $_GET["centre"];
?>

<table width="100%">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/agent_report_header.png" width="140" height="25" /></td>
<td align="right" style="padding-right:10px;"><button onClick="Search()" class="btn2">Search</button></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
</table>

<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;" width="98%">
<thead>
<tr class="ui-widget-header ">
<th colspan="6" style="text-align:center;">Closer</th>
</tr>
<tr class="ui-widget-header ">
<th width="35%">Full Name</th>
<th width="15%" style="text-align:center;">Sales</th>
<th width="15%" style="text-align:center;">SPH</th>
<th width="15%" style="text-align:center;">Estimated CPS</th>
<th width="15%" style="text-align:center;">Grade</th>
<th width="5%"></th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT auth.user,auth.first,auth.last,timesheet_designation.designation FROM vericon.auth, vericon.timesheet_designation WHERE auth.centre = '$centre' AND auth.status = 'Enabled' AND timesheet_designation.designation = 'Closer' AND timesheet_designation.user = auth.user ORDER BY auth.user ASC") or die(mysql_error());
if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td colspan='6' style='text-align:center;'>No Agents</td>";
	echo "</tr>";
}
else
{
	while ($agent = mysql_fetch_row($q))
	{
		$q0 = mysql_query("SELECT MIN(date),SUM(hours),SUM(bonus) FROM vericon.timesheet WHERE user = '$agent[0]'") or die(mysql_error());
		$data = mysql_fetch_row($q0);
		
		$q1 = mysql_query("SELECT COUNT(id) FROM vericon.sales_customers WHERE status = 'Approved' AND agent = '$agent[0]' AND DATE(approved_timestamp) >= '$data[0]'") or die(mysql_error());
		$sales = mysql_fetch_row($q1);
		
		$q2 = mysql_query("SELECT rate FROM vericon.timesheet_rate WHERE user = '$agent[0]'") or die(mysql_error());
		$rate = mysql_fetch_row($q2);
		
		$sph = $sales[0] / $data[1];
		
		if ($rate[0] == "")
		{
			$gross = (($rate * $data[1]) + $data[2]) * 1.09;
		}
		else
		{
			$gross = ((16.57 * $data[1]) + $data[2]) * 1.09;
		}
		
		if ($sales[0] > 0) { $cps = $gross / $sales[0]; } else { $cps = $gross; }
		
		if ($sph < 0.15) { $grade = "D"; } elseif ($sph < 0.2) { $grade = "C"; } elseif ($sph < 0.25) { $grade = "B"; } else { $grade = "A"; }
		
		echo "<tr>";
		echo "<td>" . $agent[1] . " " . $agent[2] . "</td>";
		echo "<td style='text-align:center;'>" . number_format($sales[0]) . "</td>";
		echo "<td style='text-align:center;'>" . number_format($sph,2) . "</td>";
		echo "<td style='text-align:center;'>\$" . number_format($cps,2) . "</td>";
		echo "<td style='text-align:center;'>" . $grade . "</td>";
		echo "<td style='text-align:center;'><button onClick='View_Agent(\"$agent[0]\")' class='icon_view' title='Details'></button></td>";
		echo "</tr>";
	}
}
?>
</tbody>
<thead>
<tr class="ui-widget-header ">
<th colspan="6" style="text-align:center;">Agent</th>
</tr>
<tr class="ui-widget-header ">
<th>Full Name</th>
<th style="text-align:center;">Sales</th>
<th style="text-align:center;">SPH</th>
<th style="text-align:center;">Estimated CPS</th>
<th style="text-align:center;">Grade</th>
<th></th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT auth.user,auth.first,auth.last,timesheet_designation.designation FROM vericon.auth, vericon.timesheet_designation WHERE auth.centre = '$centre' AND auth.status = 'Enabled' AND timesheet_designation.designation = 'Agent' AND timesheet_designation.user = auth.user ORDER BY auth.user ASC") or die(mysql_error());
if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td colspan='6' style='text-align:center;'>No Agents</td>";
	echo "</tr>";
}
else
{
	while ($agent = mysql_fetch_row($q))
	{
		$q0 = mysql_query("SELECT MIN(date),SUM(hours),SUM(bonus) FROM vericon.timesheet WHERE user = '$agent[0]'") or die(mysql_error());
		$data = mysql_fetch_row($q0);
		
		$q1 = mysql_query("SELECT COUNT(id) FROM vericon.sales_customers WHERE status = 'Approved' AND agent = '$agent[0]' AND DATE(approved_timestamp) >= '$data[0]'") or die(mysql_error());
		$sales = mysql_fetch_row($q1);
		
		$q2 = mysql_query("SELECT rate FROM vericon.timesheet_rate WHERE user = '$agent[0]'") or die(mysql_error());
		$rate = mysql_fetch_row($q2);
		
		$sph = $sales[0] / $data[1];
		
		if ($rate[0] == "")
		{
			$gross = (($rate * $data[1]) + $data[2]) * 1.09;
		}
		else
		{
			$gross = ((16.57 * $data[1]) + $data[2]) * 1.09;
		}
		
		if ($sales[0] > 0) { $cps = $gross / $sales[0]; } else { $cps = $gross; }
		
		if ($sph < 0.15) { $grade = "D"; } elseif ($sph < 0.2) { $grade = "C"; } elseif ($sph < 0.25) { $grade = "B"; } else { $grade = "A"; }
		
		echo "<tr>";
		echo "<td>" . $agent[1] . " " . $agent[2] . "</td>";
		echo "<td style='text-align:center;'>" . number_format($sales[0]) . "</td>";
		echo "<td style='text-align:center;'>" . number_format($sph,2) . "</td>";
		echo "<td style='text-align:center;'>\$" . number_format($cps,2) . "</td>";
		echo "<td style='text-align:center;'>" . $grade . "</td>";
		echo "<td style='text-align:center;'><button onClick='View_Agent(\"$agent[0]\")' class='icon_view' title='Details'></button></td>";
		echo "</tr>";
	}
}
?>
</tbody>
<thead>
<tr class="ui-widget-header ">
<th colspan="6" style="text-align:center;">Probation</th>
</tr>
<tr class="ui-widget-header ">
<th>Full Name</th>
<th style="text-align:center;">Sales</th>
<th style="text-align:center;">SPH</th>
<th style="text-align:center;">Estimated CPS</th>
<th style="text-align:center;">Grade</th>
<th></th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT auth.user,auth.first,auth.last,timesheet_designation.designation FROM vericon.auth, vericon.timesheet_designation WHERE auth.centre = '$centre' AND auth.status = 'Enabled' AND timesheet_designation.designation = 'Probation' AND timesheet_designation.user = auth.user ORDER BY auth.user ASC") or die(mysql_error());
if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td colspan='6' style='text-align:center;'>No Agents</td>";
	echo "</tr>";
}
else
{
	while ($agent = mysql_fetch_row($q))
	{
		$q0 = mysql_query("SELECT MIN(date),SUM(hours),SUM(bonus) FROM vericon.timesheet WHERE user = '$agent[0]'") or die(mysql_error());
		$data = mysql_fetch_row($q0);
		
		$q1 = mysql_query("SELECT COUNT(id) FROM vericon.sales_customers WHERE status = 'Approved' AND agent = '$agent[0]' AND DATE(approved_timestamp) >= '$data[0]'") or die(mysql_error());
		$sales = mysql_fetch_row($q1);
		
		$q2 = mysql_query("SELECT rate FROM vericon.timesheet_rate WHERE user = '$agent[0]'") or die(mysql_error());
		$rate = mysql_fetch_row($q2);
		
		$sph = $sales[0] / $data[1];
		
		if ($rate[0] == "")
		{
			$gross = (($rate * $data[1]) + $data[2]) * 1.09;
		}
		else
		{
			$gross = ((16.57 * $data[1]) + $data[2]) * 1.09;
		}
		
		if ($sales[0] > 0) { $cps = $gross / $sales[0]; } else { $cps = $gross; }
		
		if ($sph < 0.15) { $grade = "D"; } elseif ($sph < 0.2) { $grade = "C"; } elseif ($sph < 0.25) { $grade = "B"; } else { $grade = "A"; }
		
		echo "<tr>";
		echo "<td>" . $agent[1] . " " . $agent[2] . "</td>";
		echo "<td style='text-align:center;'>" . number_format($sales[0]) . "</td>";
		echo "<td style='text-align:center;'>" . number_format($sph,2) . "</td>";
		echo "<td style='text-align:center;'>\$" . number_format($cps,2) . "</td>";
		echo "<td style='text-align:center;'>" . $grade . "</td>";
		echo "<td style='text-align:center;'><button onClick='View_Agent(\"$agent[0]\")' class='icon_view' title='Details'></button></td>";
		echo "</tr>";
	}
}
?>
</tbody>
</table>
</div></center>