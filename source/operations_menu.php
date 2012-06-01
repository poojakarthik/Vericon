<script type="text/javascript" src="../js/ddsmoothmenu.js"></script>
<script type="text/javascript">
ddsmoothmenu.init({
	mainmenuid: "smoothmenu1", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})
</script>
<div id="menu">
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="../operations/index.php">HOME</a></li><li style="padding-top:8px;">|</li>
<li><a href="../operations/sales.php">SALES</a></li><li style="padding-top:8px;">|</li>
<li><a href="../operations/rejections.php">REJECTIONS</a></li>
<?php if ($ac["user"] == "vkoc001" || $ac["user"] == "nshe001" || $ac["user"] == "kdar001" || $ac["user"] == "nrat001" || $ac["user"] == "onaj001" || $ac["user"] == "bsha001") 
{
?>
<li style="padding-top:8px;">|</li><li><a style="cursor:pointer;">TIMESHEET</a>
	<ul>
	<li><a href="../operations/payable.php">OP PAYABLE</a></li>
    <li><a href="../operations/centre.php">CENTRE REPORT</a></li>
    <li><a href="../operations/agent.php">AGENT REPORT</a></li>
	</ul>
</li>
<?php
}
?>
</ul>
</div>
</div>