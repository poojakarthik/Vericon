<p><img src="../images/centre_stats_header.png" width="135" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p><br />

<p><center><img src="../sales/chart.php?method=centre&centre=<?php echo $ac["centre"]; ?>" /></center></p><br />

<p><img src="../images/sales_export_header.png" width="135" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p><br />

<a style="color:#666;" href="sales_export.php?user=<?php echo $ac["user"] ?>" target="_blank"><?php echo $ac["centre"] . "_Sales_" . date("d_m_Y") . ".xls" ?></a>