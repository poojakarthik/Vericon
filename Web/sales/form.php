<?php
include("../auth/restrict.php");
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; background:none; }
div#users-contain table th { border: 1px solid rgba(41,171,226,0.25); padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid rgba(41,171,226,0.25); padding: .6em 5px; text-align: left; }
div#users-contain table tbody tr:hover { background:rgba(255,255,255,0.25); }
</style>

<div class="head">
<table>
<tr>
<td style="width:90px;"><div class="dotted"></div></td>
<td valign="middle" nowrap="nowrap" width="1px"><h1>Sales Form</h1></td>
<td><div class="dotted"></div></td>
</tr>
</table>
</div>

<center><div style="width:98%;">
<table width="100%">
<tr>
<td width="45%" valign="top" rowspan="2">
<center><h2>Sale Details</h2></center>
<table width="100%">
<tr>
<td width="269px" height="2px"><div class="line_left"></div></td>
<td height="2px"><div class="line_repeat"></div></td>
<td width="269px" height="2px"><div class="line_right"></div></td>
</tr>
</table>
<table>
<tr>
<td colspan="2" id="Sales02_error"><p>Enter the lead details below to begin the form.</p></td>
<td></td>
</tr>
<tr>
<td width="105px">Lead ID<span style="color:#ff0000;">*</span> </td>
<td><input id="Sales02_lead_id" type="text"></td>
<td id="Sales02_lead_check" style="padding-left:5px;"></td>
</tr>
<tr>
<td>Campaign<span style="color:#ff0000;">*</span> </td>
<td><select id="Admin02_campaign" disabled="disabled">
<option></option>
</select></td>
<td></td>
</tr>
<tr>
<td>Type<span style="color:#ff0000;">*</span> </td>
<td><select id="Admin02_type" disabled="disabled">
<option></option>
</select></td>
<td></td>
</tr>
<tr>
<td colspan="2" align="right"><button class="btn" style="margin-top:5px;">Submit</button></td>
</tr>
</table>
</td>
<td width="55%" valign="top">
<center><h2>Last 5 Sales</h2></center>
<table width="100%">
<tr>
<td width="269px" height="2px"><div class="line_left"></div></td>
<td height="2px"><div class="line_repeat"></div></td>
<td width="269px" height="2px"><div class="line_right"></div></td>
</tr>
</table>
<center>
<table style="width:95%">
<tr>
<td><p>Below are your 5 most recent sales.</p></td>
</tr>
</table>
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" style="width:95%; margin-top:0;">
<thead>
<tr class="ui-widget-header ">
<th width="20%">ID</th>
<th width="20%">Status</th>
<th width="60%">Customer Name</th>
</tr>
</thead>
<tbody>
<tr>
<td colspan="3" style="text-align:center;">No Sales</td>
</tr>
</tbody>
</table>
</div></center>
</td>
</tr>
</table>
</div></center>