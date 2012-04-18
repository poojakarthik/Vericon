<script type="text/javascript" src="../js/ddsmoothmenu.js"></script>
<script type="text/javascript">
ddsmoothmenu.init({
	mainmenuid: "smoothmenu1", //menu DIV id
	orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
	contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
})
</script>
<script> //Coverage Check
$(function() {
	$( "#dialog:ui-dialog_co" ).dialog( "destroy" );

	$( "#dialog-confirm_co" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:275,
		height:200,
		modal: true,
		buttons: {
			"Check": function() {
				var phone = $( "#phone" );
				
				$.get("../source/broadband.php", { phone: phone.val() },
				function(data) {
					$( ".result" ).html(data);
				});
			},
			
			"Close": function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

function Check()
{
	$( "#dialog-confirm_co" ).dialog( "open" );
}
</script>

<div id="dialog-confirm_co" title="Broadband Coverage">
	Telephone Number: &nbsp;&nbsp;<input type="text" size="15" id="phone" />
    <br /><br />
    <p class="result"></p>
</div>

<div id="menu">
<div id="smoothmenu1" class="ddsmoothmenu">
<ul>
<li><a href="../self/index.php">HOME</a></li><li style="padding-top:8px;">|</li>
<li><a href="../self/roster.php">ROSTER</a></li><li style="padding-top:8px;">|</li>
<li><a href="../self/form.php">SALES FORM</a></li><li style="padding-top:8px;">|</li>
<li><a href="../self/scripts.php">VERIFICATION</a></li><li style="padding-top:8px;">|</li>
<li><a href="../self/rates.php">RATES</a></li><li style="padding-top:8px;">|</li>
<li><a onclick="Check()" style="cursor:pointer">ADSL CHECKER</a></li>
<?php
if($access_level == "Admin")
{
?>
<li style="padding-top:8px;">|</li><li><a style="cursor:pointer;">ADMIN</a>
  <ul>
  <li><a href="../self/admin.php?p=users">USERS</a></li>
  <?php if ($ac["centre"] == "CC12") { ?>
  <li><a href="../self/admin.php?p=teams">TEAMS</a></li>
  <?php } ?>
  <?php if ($ac["centre"] == "CC33") { ?>
  <li><a href="../self/admin.php?p=timesheet">TIMESHEET</a></li>
  <?php } ?>
  <li><a href="../self/admin.php?p=current">LOGGED IN</a></li>
  <li><a href="../self/admin.php?p=details">SALE DETAILS</a></li>
  <li><a href="../self/admin.php?p=roster&m=d">ROSTER</a></li>
  </ul>
</li>
<?php
}
?>
</ul>
</div>
</div>