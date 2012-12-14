<?php
include("../auth/restrict.php");

$approved = array();
$declined = array();
$line_issue = array();
$q = mysql_query("SELECT DAYNAME(`approved_timestamp`), `status`, COUNT(`id`) FROM `vericon`.`sales_customers` WHERE `centre` = 'CC53' AND WEEK(`approved_timestamp`,3) = '" . mysql_real_escape_string(date("W")) . "' GROUP BY DAYOFWEEK(`approved_timestamp`), `status`") or die(mysql_error());
while ($data = mysql_fetch_row($q))
{
	if ($data[1] == "Approved") {
		$approved[$data[0]] = $data[2];
	} elseif ($data[1] == "Declined") {
		$declined[$data[0]] = $data[2];
	} elseif ($data[1] == "Line Issue") {
		$line_issue[$data[0]] = $data[2];
	}
}
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; background:none; }
div#users-contain table th { border: 1px solid rgba(41,171,226,0.25); padding: .6em 10px; text-align: center; }
div#users-contain table td { border: 1px solid rgba(41,171,226,0.25); padding: .6em 5px; text-align: center; }
div#users-contain table tbody tr:hover { background:rgba(255,255,255,0.25); }
</style>

<script>
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'sales_chart_container',
                type: 'column'
            },
            title: {
                text: ' '
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday'
                ]
            },
            yAxis: {
                min: 0,
                title: {
                    text: ''
                }
            },
            tooltip: {
                formatter: function() {
                    return ''+
                        this.x + ': ' + this.y + ' ' + this.point.extra;
                }
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
                series: [{
					name: 'Approved',
					data: [{
						y: parseInt("<?php echo $approved["Monday"]; ?>") || 0,
						extra: 'Approved'
					}, {
						y: parseInt("<?php echo $approved["Tuesday"]; ?>") || 0,
						extra: 'Approved'
					}, { 
						y: parseInt("<?php echo $approved["Wednesday"]; ?>") || 0,
						extra: 'Approved'
					}, {
						y: parseInt("<?php echo $approved["Thursday"]; ?>") || 0,
						extra: 'Approved'
					}, {
						y: parseInt("<?php echo $approved["Friday"]; ?>") || 0,
						extra: 'Approved'
					}, {
						y: parseInt("<?php echo $approved["Saturday"]; ?>") || 0,
						extra: 'Approved'
					}],
					color: 'rgba(0, 128, 0, .81)'
            }, {
					name: 'Declined',
					data: [{
						y: parseInt("<?php echo $declined["Monday"]; ?>") || 0,
						extra: 'Declined'
					}, {
						y: parseInt("<?php echo $declined["Tuesday"]; ?>") || 0,
						extra: 'Declined'
					}, { 
						y: parseInt("<?php echo $declined["Wednesday"]; ?>") || 0,
						extra: 'Declined'
					}, {
						y: parseInt("<?php echo $declined["Thursday"]; ?>") || 0,
						extra: 'Declined'
					}, {
						y: parseInt("<?php echo $declined["Friday"]; ?>") || 0,
						extra: 'Declined'
					}, {
						y: parseInt("<?php echo $declined["Saturday"]; ?>") || 0,
						extra: 'Declined'
					}],
					color: 'rgba(255, 0, 0, .81)'
            }, {
					name: 'Line Issue',
					data: [{
						y: parseInt("<?php echo $line_issue["Monday"]; ?>") || 0,
						extra: 'Line Issue'
					}, {
						y: parseInt("<?php echo $line_issue["Tuesday"]; ?>") || 0,
						extra: 'Line Issue'
					}, { 
						y: parseInt("<?php echo $line_issue["Wednesday"]; ?>") || 0,
						extra: 'Line Issue'
					}, {
						y: parseInt("<?php echo $line_issue["Thursday"]; ?>") || 0,
						extra: 'Line Issue'
					}, {
						y: parseInt("<?php echo $line_issue["Friday"]; ?>") || 0,
						extra: 'Line Issue'
					}, {
						y: parseInt("<?php echo $line_issue["Saturday"]; ?>") || 0,
						extra: 'Line Issue'
					}],
					color: 'rgba(255, 255, 0, .81)'
            }]
        });
    });
    
});
</script>

<div class="head">
<table>
<tr>
<td style="width:90px;"><div class="dotted"></div></td>
<td valign="middle" nowrap="nowrap" width="1px"><h1>Sales Dashboard</h1></td>
<td><div class="dotted"></div></td>
</tr>
</table>
</div>

<center><table width="98%">
<tr>
<td width="50%" valign="top">
<center><h2>Sale Stats</h2></center>
<table width="100%" style="margin-bottom:5px;">
<tr>
<td width="269px" height="2px"><div class="line_left"></div></td>
<td height="2px"><div class="line_repeat"></div></td>
<td width="269px" height="2px"><div class="line_right"></div></td>
</tr>
</table>
<div id="sales_chart_container" style="width:95%; height:300px; margin:0 auto 10px;"></div>
</td>
<td width="50%" valign="top">
<center><h2>Agent Performance</h2></center>
<table width="100%" style="margin-bottom:5px;">
<tr>
<td width="269px" height="2px"><div class="line_left"></div></td>
<td height="2px"><div class="line_repeat"></div></td>
<td width="269px" height="2px"><div class="line_right"></div></td>
</tr>
</table>
<div id="performance_chart_container" style="width:95%; height:300px; margin:0 auto 10px;"></div>
</td>
</tr>
<tr>
<td colspan="2">
<center><h2>Top 5</h2></center>
<table width="100%" style="margin-bottom:5px;">
<tr>
<td width="269px" height="2px"><div class="line_left"></div></td>
<td height="2px"><div class="line_repeat"></div></td>
<td width="269px" height="2px"><div class="line_right"></div></td>
</tr>
</table>
<center>
<table width="95%">
<tr>
<td width="32%" align="center">
<center><h3>Today</h3></center>
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; margin-top:5px;">
<thead>
<tr class="ui-widget-header ">
<th width="80%" style="text-align:left;">Agent</th>
<th width="20%">Sales</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT CONCAT(`auth`.`first`, ' ', `auth`.`last`), COUNT(`sales_customers`.`id`) FROM `vericon`.`sales_customers`, `vericon`.`auth` WHERE `sales_customers`.`centre` = '" . mysql_real_escape_string($ac["centre"]) . "' AND `sales_customers`.`status` = 'Approved' AND DATE(`sales_customers`.`approved_timestamp`) = '" . mysql_real_escape_string(date("Y-m-d")) . "' AND `sales_customers`.`agent` = `auth`.`user` GROUP BY `sales_customers`.`agent` ORDER BY COUNT(`sales_customers`.`id`) DESC LIMIT 5") or die(mysql_error());
if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td colspan='2'>No sales made today</td>";
	echo "</tr>";
}
else
{
	while ($data = mysql_fetch_row($q))
	{
		echo "<tr>";
		echo "<td style='text-align:left;'>" . $data[0] . "</td>";
		echo "<td>" . $data[1] . "</td>";
		echo "</tr>";
	}
}
?>
</tbody>
</table>
</div>
</td>
<td width="2%"></td>
<td width="32%" align="center">
<center><h3>This Week</h3></center>
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; margin-top:5px;">
<thead>
<tr class="ui-widget-header ">
<th width="80%" style="text-align:left;">Agent</th>
<th width="20%">Sales</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT CONCAT(`auth`.`first`, ' ', `auth`.`last`), COUNT(`sales_customers`.`id`) FROM `vericon`.`sales_customers`, `vericon`.`auth` WHERE `sales_customers`.`centre` = '" . mysql_real_escape_string($ac["centre"]) . "' AND `sales_customers`.`status` = 'Approved' AND WEEK(`sales_customers`.`approved_timestamp`,3) = '" . mysql_real_escape_string(date("W")) . "' AND `sales_customers`.`agent` = `auth`.`user` GROUP BY `sales_customers`.`agent` ORDER BY COUNT(`sales_customers`.`id`) DESC LIMIT 5") or die(mysql_error());
if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td colspan='2'>No sales made this week</td>";
	echo "</tr>";
}
else
{
	while ($data = mysql_fetch_row($q))
	{
		echo "<tr>";
		echo "<td style='text-align:left;'>" . $data[0] . "</td>";
		echo "<td>" . $data[1] . "</td>";
		echo "</tr>";
	}
}
?>
</tbody>
</table>
</div>
</td>
<td width="2%"></td>
<td width="32%" align="center">
<center><h3>Overall</h3></center>
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:100%; margin-top:5px;">
<thead>
<tr class="ui-widget-header ">
<th width="80%" style="text-align:left;">Agent</th>
<th width="20%">Sales</th>
</tr>
</thead>
<tbody>
<?php
$q = mysql_query("SELECT CONCAT(`auth`.`first`, ' ', `auth`.`last`), COUNT(`sales_customers`.`id`) FROM `vericon`.`sales_customers`, `vericon`.`auth` WHERE `sales_customers`.`centre` = '" . mysql_real_escape_string($ac["centre"]) . "' AND `sales_customers`.`status` = 'Approved' AND `sales_customers`.`agent` = `auth`.`user` GROUP BY `sales_customers`.`agent` ORDER BY COUNT(`sales_customers`.`id`) DESC LIMIT 5") or die(mysql_error());
if (mysql_num_rows($q) == 0)
{
	echo "<tr>";
	echo "<td colspan='2'>No sales made ever</td>";
	echo "</tr>";
}
else
{
	while ($data = mysql_fetch_row($q))
	{
		echo "<tr>";
		echo "<td style='text-align:left;'>" . $data[0] . "</td>";
		echo "<td>" . $data[1] . "</td>";
		echo "</tr>";
	}
}
?>
</tbody>
</table>
</div>
</td>
</tr>
</table>
</center>
</td>
</tr>
</table></center>

<script>
$( "#sales_chart_container rect[fill='#FFFFFF']").attr("fill","rgba(255, 255, 255, .31)");
$( "#sales_chart_container g path[stroke='#C0D0E0']").attr("stroke","rgba(58, 101, 180, .31)");
$( "#sales_chart_container g path[stroke='#C0C0C0']").attr("stroke","rgba(58, 101, 180, .31)");
$( "#sales_chart_container g rect[stroke='#909090']").attr("stroke","rgba(58, 101, 180, .31)");
</script>