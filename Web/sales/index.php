<?php
include("../auth/restrict.php");
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
                text: ''
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
						y: 1,
						extra: 'Approved'
					}, {
						y: 2,
						extra: 'Approved'
					}, { 
						y: 3,
						extra: 'Approved'
					}, {
						y: 4,
						extra: 'Approved'
					}, {
						y: 5,
						extra: 'Approved'
					}, {
						y: 6,
						extra: 'Approved'
					}],
					color: 'rgba(0, 128, 0, .81)'
            }, {
					name: 'Declined',
					data: [{
						y: 1,
						extra: 'Declined'
					}, {
						y: 2,
						extra: 'Declined'
					}, { 
						y: 3,
						extra: 'Declined'
					}, {
						y: 4,
						extra: 'Declined'
					}, {
						y: 5,
						extra: 'Declined'
					}, {
						y: 6,
						extra: 'Declined'
					}],
					color: 'rgba(255, 0, 0, .81)'
            }, {
					name: 'Line Issue',
					data: [{
						y: 1,
						extra: 'Line Issue'
					}, {
						y: 2,
						extra: 'Line Issue'
					}, { 
						y: 3,
						extra: 'Line Issue'
					}, {
						y: 4,
						extra: 'Line Issue'
					}, {
						y: 5,
						extra: 'Line Issue'
					}, {
						y: 6,
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
for ($i = 0; $i < 5; $i++)
{
	echo "<tr>";
	echo "<td style='text-align:left;'>Agent " . $i . "</td>";
	echo "<td>" . rand(0,5) . "</td>";
	echo "</tr>";
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
for ($i = 0; $i < 5; $i++)
{
	echo "<tr>";
	echo "<td style='text-align:left;'>Agent " . $i . "</td>";
	echo "<td>" . rand(4,20) . "</td>";
	echo "</tr>";
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
for ($i = 0; $i < 5; $i++)
{
	echo "<tr>";
	echo "<td style='text-align:left;'>Agent " . $i . "</td>";
	echo "<td>" . rand(30,150) . "</td>";
	echo "</tr>";
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