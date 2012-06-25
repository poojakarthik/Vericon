<?php
include "../auth/iprestrict.php";
include "../source/header.php";

$q = mysql_query("SELECT campaign FROM centres WHERE centre = '$ac[centre]'");
$cam = mysql_fetch_assoc($q);
?>

<p><img src="../images/agent_details_header.png" width="145" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" alt="line" /></p><br />

<table width="100%" border="0">
<tr>
<td width="85px">Agent Name: </td>
<td><b><?php echo $ac["first"] . " " . $ac["last"] . " (" . $ac["alias"] . ")"; ?></b></td>
</tr>
<tr>
<td width="85px">Access Level: </td>
<td><b><?php echo $ac["access"]; ?></b></td>
</tr>
<tr>
<td>Centre: </td>
<td><b><?php echo $ac["centre"]; ?></b></td>
</tr>
<tr>
<td>Campaign: </td>
<td><b><?php echo $cam["campaign"]; ?></b></td>
</tr>
</table><br />

<p><img src="../images/sale_stats_header.png" width="110" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" alt="line" /></p><br />

<p><center><img src="../sales/chart.php?method=user&user=<?php echo $ac["user"]; ?>" /></center></p>

<?php
include "../source/footer.php";
?>