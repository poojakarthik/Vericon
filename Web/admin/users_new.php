<?php
include("../auth/restrict_inner.php");
?>

<table>
<tr>
<td width="105px">Username </td>
<td><input type="text" value="Automatically Generated" disabled="disabled"></td>
</tr>
<tr>
<td>First Name<span style="color:#ff0000;">*</span> </td>
<td><input id="first" type="text"></td>
</tr>
<tr>
<td>Last Name<span style="color:#ff0000;">*</span> </td>
<td><input id="last" type="text"></td>
</tr>
<tr>
<td>Password<span style="color:#ff0000;">*</span> </td>
<td><input id="password" type="password"></td>
</tr>
<tr>
<td>Re-Type Password<span style="color:#ff0000;">*</span> </td>
<td><input id="password2" type="password"></td>
</tr>
<tr>
<td>Departments<span style="color:#ff0000;">*</span> </td>
<td><select id="access" multiple="multiple">
<?php
$q = mysql_query("SELECT `id`, `name` FROM `vericon`.`portals` WHERE `id` != 'MA' AND `status` = 'Enabled' ORDER BY `name` ASC") or die(mysql_error());
while($portals = mysql_fetch_row($q))
{
	echo "<option value='$portals[0]'>" . $portals[1] . "</option>";
}
?>
</select></td>
</tr>
<tr id="Admin03_cu_centre_tr">
<td>Centre<span style="color:#ff0000;">*</span> </td>
<td><select id="centre">
<option></option>
<option>CC01</option>
<?php
/*$q = mysql_query("SELECT centre FROM vericon.centres WHERE status = 'Enabled' ORDER BY centre ASC") or die(mysql_error());
while($centres = mysql_fetch_row($q))
{
	echo "<option>" . $centres[0] . "</option>";
}
*/?>
</select></td>
</tr>
<tr>
<td>Designation<span style="color:#ff0000;">*</span> </td>
<td><select id="designation">
<option></option>
<option>Team Leader</option>
<option>Closer</option>
<option>Agent</option>
<option>Probation</option>
</select></td>
</tr>
<tr>
<td>Alias<span style="color:#ff0000;">*</span> </td>
<td><input id="alias" type="text"></td>
</tr>
</table>