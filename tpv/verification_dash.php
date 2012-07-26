<?php
mysql_connect('localhost','vericon','18450be');

$id = $_GET["id"];

$q = mysql_query("SELECT * FROM vericon.sales_customers WHERE id = '" . mysql_escape_string($id) . "'") or die (mysql_error());
$data = mysql_fetch_assoc($q);

$status_text = strtolower(str_replace(" ", "_", $data["status"]));

$q1 = mysql_query("SELECT first,last,alias FROM vericon.auth WHERE user = '$data[agent]'") or die(mysql_error());
$agent = mysql_fetch_assoc($q1);

$q2 = mysql_query("SELECT state_name FROM gnaf.STATE WHERE state_abbreviation = '" . mysql_real_escape_string(str_replace("_", "", substr($data["physical"],2,3))) . "'") or die(mysql_error());
$state = mysql_fetch_row($q2);
?>

<input type="hidden" id="id" value="<?php echo $data["id"]; ?>" />
<input type="hidden" id="sale_type" value="<?php echo $data["type"]; ?>" />
<table width="100%">
<tr>
<td width="50%" valign="top" style="padding-right:20px;">
<table width="100%" style="margin-bottom:10px;">
<tr>
<td align="left" style="padding-left:5px;"><img src="../images/sale_details_header.png" width="90" height="15" /></td>
<td align="right" style="padding-right:10px;"><img src="../images/<?php echo $status_text; ?>_header.png" width="100" height="15"></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
<tr>
<td width="100px" style="padding-left:5px;">Sale ID </td>
<td><b><?php echo $data["id"]; ?></b></td>
</tr>
<tr>
<td style="padding-left:5px;">Agent Name </td>
<td><b><?php echo $agent["first"] . " " . $agent["last"] . " (" . $agent["alias"] . ")"; ?></b></td>
</tr>
<tr>
<td style="padding-left:5px;">Centre </td>
<td><b><?php echo $data["centre"]; ?></b></td>
</tr>
<tr>
<td style="padding-left:5px;">Campaign </td>
<td><b><?php echo $data["campaign"]; ?></b></td>
</tr>
<tr>
<td style="padding-left:5px;">Type </td>
<td><b><?php echo $data["type"]; ?></b></td>
</tr>
</table>
</td>
<td width="50%" valign="top" style="padding-left:20px;">
<table width="100%" style="margin-bottom:10px;">
<tr>
<td colspan="2" style="padding-left:5px;"><img src="../images/customer_details_header.png" width="128" height="15" /></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
<tr>
<td width="100px" style="padding-left:5px;">Customer Name </td>
<td><b><?php echo $data["title"] . " " . $data["firstname"] . " " . $data["lastname"]; ?></b></td>
</tr>
<tr>
<td style="padding-left:5px;">D.O.B </td>
<td><b><?php echo date("d/m/Y", strtotime($data["dob"])); ?></b></td>
</tr>
<tr>
<td style="padding-left:5px;">State </td>
<td><b><?php echo ucwords(strtolower($state[0])); ?></b></td>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan="2" valign="top">
<table width="100%">
<tr>
<td style="padding-left:5px;"><img src="../images/tpv_notes_header.png" width="80" height="15"></td>
</tr>
<tr>
<td><img src="../images/line.png" width="100%" height="9"></td>
</tr>
<tr>
<td>
<center><div style="height:125px; width:97%; padding:0 3px; overflow:auto; border: 1px solid #eee;">
<?php
$q3 = mysql_query("SELECT * FROM vericon.tpv_notes WHERE id = '$id' ORDER BY timestamp DESC") or die (mysql_error());

echo "<table width='100%'>";
if (mysql_num_rows($q3) == 0)
{
	echo "<tr>";
	echo "<td>No Notes</td>";
	echo "</tr>";
}
else
{
	while ($tpv_notes = mysql_fetch_assoc($q3))
	{
		$q4 = mysql_query("SELECT first,last FROM vericon.auth WHERE user = '$tpv_notes[verifier]'") or die(mysql_error());
		$vname = mysql_fetch_assoc($q4);
		
		echo "<tr>";
		echo "<td>----- " . date("d/m/Y H:i:s", strtotime($tpv_notes["timestamp"])) . " - " . $vname["first"] . " " . $vname["last"] . " -----" . " (" . $tpv_notes["status"] . ")</td>";
		echo "</tr>";
		echo "<tr>";
		echo "<td>" . $tpv_notes["note"] . "</td>";
		echo "</tr>";
	}
}
echo "</table>";
?>
</div></center>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan="2" valign="top">
<table width="100%" border="0">
<tr>
<td width="56%" valign="top">
<table width="100%">
<tr>
<td style="padding-left:5px;"><img src="../images/previous_attempt_header.png" width="140" height="15"></td>
</tr>
<tr>
<td><img src="../images/line.png" width="100%" height="9"></td>
</tr>
<tr>
<td>
<center><div style="height:100px; width:97%; padding:0 3px; overflow:auto; border: 1px solid #eee;">
<?php
echo "<table width='100%'>";
echo "<tr>";
echo "<td>Temporarily Unavailable</td>";
echo "</tr>";
echo "</table>";
?>
</div></center>
</td>
</tr>
</table>
</td>
<td width="44%" valign="bottom">
<table border="0" width="100%">
<tr>
<td align="right" style="padding-right:5px;">
<img src="../images/verification_fill_bg.png" width="315" height="104" />
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan="2" valign="top">
<table border="0" width="100%">
<tr>
<td colspan="2" style="padding-left:5px;"><img src="../images/selected_packages_header.png" width="134" height="15"></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="100%" height="9" alt="line" /></td>
</tr>
<tr>
<td colspan="2">
<center><div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="99%" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th width="20%">CLI</th>
<th width="70%">Plan</th>
<th width="10%" colspan="2" style="text-align:center;">Edit</th>
</tr>
</thead>
<tbody id="packages">
</tbody>
</table>
</div></center>
</td>
</tr>
<tr valign="bottom">
<td align="left" style="padding-left:10px;"><button onclick="Add_Package()" class="btn">Add Package</button></td>
<td align="right" style="padding-right:10px;"><button onclick="LoadScript()" class="btn">Load Script</button> <button onclick="Cancel()" class="btn">Cancel</button></td>
</tr>
</table>
</td>
</tr>
</table>