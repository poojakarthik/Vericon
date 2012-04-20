<script type="text/javascript">
$(function() {
    $('form').jqTransform({imgPath:'../images/'});
});
</script>
<div style="display:none;">
<img src="../images/load_script_btn_hover.png" />
</div>
<p><img src="../images/export_scripts_header.png" width="150" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" alt="line" /></p><br />

<table width="100%" style="margin-bottom:30px; margin-top:10;">
<tr>
<td>Campaign: </td>
<td>
<form>
<select id="campaign" style="min-width:210px;">
<option>--- Campaign ---</option>
<?php
$q = mysql_query("SELECT campaign FROM campaigns ORDER BY campaign ASC");
while ($campaign = mysql_fetch_row($q))
{
	echo "<option>" . $campaign[0] . " Business</option>";
	echo "<option>" . $campaign[0] . " Residential</option>";
}
?>
</select>
</form>
</td>
<td>Plan: </td>
<td><form><select id="plan" style="min-width:230px;">
<option>--- Plan ---</option>
<option disabled="disabled">--- Landline ---</option>
<?php
$q0 = mysql_query("SELECT * FROM plan_matrix WHERE status = 'Active' AND rating = 'Business' AND type = 'PSTN' AND name != 'Addon' ORDER BY id ASC");

while ($l_plan = mysql_fetch_assoc($q0))
{
	echo "<option>" . $l_plan["name"] . "</option>";
}
?>
<option disabled="disabled">--- Internet ---</option>
<option>ADSL 15GB 24 Month Contract</option>
<option>ADSL Unlimited 24 Month Contract</option>
<option disabled="disabled">--- Bundle ---</option>
<?php
$q0 = mysql_query("SELECT * FROM plan_matrix WHERE status = 'Active' AND rating = 'Business' AND type = 'Bundle' ORDER BY id ASC");

while ($b_plan = mysql_fetch_assoc($q0))
{
	echo "<option>" . $b_plan["name"] . "</option>";
}
?>
</select>
</form>
</td>
<td><input type="button" onclick="LoadScript()" class="load" /></td>
</tr>
</table>

<p><img src="../images/edit_scripts_header.png" width="120" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" alt="line" /></p><br />

<p>Coming Soon!</p>