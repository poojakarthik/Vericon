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
<li><a href="../qa/index.php">HOME</a></li><li style="padding-top:8px;">|</li>
<li><a href="../qa/sales.php">FRESH SALES</a></li>
<?php
if($access_level == "Admin")
{
?>
<li style="padding-top:8px;">|</li><li><a style="cursor:pointer;">ADMIN</a>
  <ul>
  <li><a href="../qa/admin.php?p=users">USERS</a></li>
  <li><a href="../qa/admin.php?p=current">LOGGED IN</a></li>
  <li><a href="../qa/admin.php?p=rejections">REJECTIONS</a></li>
  <li><a href="../qa/admin.php?p=process">DSR</a></li>
  </ul>
</li>
<?php
}
?>
</ul>
</div>
</div>