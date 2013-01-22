<?php
include("../auth/restrict_inner.php");

$id = $_POST["lead"];
$q = $mysqli->query("SELECT * FROM `vericon`.`sales_customers_temp` WHERE `id` = '" . $mysqli->real_escape_string($id) . "'") or die($mysqli->error);
$data = $q->fetch_assoc();
$q->free();
?>

<div class="head">
<table>
<tr>
<td style="width:90px;"><div class="dotted"></div></td>
<td valign="middle" nowrap="nowrap" width="1px"><h1>Sales Form</h1></td>
<td><div class="dotted"></div></td>
</tr>
</table>
</div>

