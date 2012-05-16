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
<li><a href="../accounts/index.php">HOME</a></li><li style="padding-top:8px;">|</li>
<li><a href="../accounts/upload_stats.php">UPLOAD STATS</a></li><?php if ($ac["user"] != "dsin002") { ?><li style="padding-top:8px;">|</li>
<li><a href="../accounts/rejections.php">REJECTIONS</a></li><li style="padding-top:8px;">|</li>
<li><a href="../accounts/timesheet.php">TIMESHEET</a></li><?php } ?>
</ul>
</div>
</div>