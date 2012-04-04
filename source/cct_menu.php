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
<li><a href="../cct/index.php">HOME</a></li><li style="padding-top:8px;">|</li>
<li><a href="../cct/customers.php">CUSTOMERS</a></li><li style="padding-top:8px;">|</li>
<li><a href="../cct/rates.php">RATES</a></li></li><li style="padding-top:8px;">|</li>
<li><a href="../cct/extensions.php">EXTENSIONS</a></li>
<?php
if($access_level == "Admin")
{
?>
<li style="padding-top:8px;">|</li><li><a style="cursor:pointer;">ADMIN</a>
  <ul>
  <li><a href="../cct/admin.php?p=announcements">ANNOUNCEMENTS</a></li>
  <li><a href="../cct/admin.php?p=users">USERS</a></li>
  </ul>
</li>
<?php
}
?>
</ul>
</div>
</div>