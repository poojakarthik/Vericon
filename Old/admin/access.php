<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
.ui-dialog .ui-state-highlight { padding: .3em; }
.validateTips { border: 1px solid transparent; padding: 0.3em; }
</style>
<script>
function Display(page)
{
	$( "#display2" ).load("access_display2.php?page=" + page);
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var ip = $( "#ip" ),
		description = $( "#description" ),
		user = "<?php echo $ac["user"]; ?>",
		tips = $( ".validateTips" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	
	$( "#dialog-form" ).dialog({
		autoOpen: false,
		height: 225,
		width: 300,
		modal: true,
		resizable: false,
		draggable: false,
		show: 'blind',
		hide: 'blind',
		buttons: {
			"Submit": function() {
				$.get("access_process.php?method=add", { ip: ip.val(), description: description.val(), user: user },
				function(data) {
					if (data == "added")
					{
						var page_link = $( "#page_link" );
						$( "#display2" ).load("access_display2.php" + page_link.val());
						$( "#dialog-form" ).dialog( "close" );
					}
					else
					{
						updateTips(data);
					}
				});
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

function Add_IP()
{
	$( "#ip" ).val("");
	$( "#description" ).val("");
	$( ".validateTips" ).html("All fields are required.");
	$( "#dialog-form" ).dialog( "open" );
}
</script>
<script> // disable confirmation
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		height:140,
		modal: true,
		show: 'blind',
		hide: 'blind',
		buttons: {
			"Disable IP": function() {
				var disable_ip = $( "#disable_ip" ).val();
				$.get("access_process.php?method=disable", { ip: disable_ip }, function (data) {
					var page_link = $( "#page_link" );
					$( "#display2" ).load("access_display2.php" + page_link.val());
					$( "#dialog-confirm" ).dialog( "close" );
				});
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

function Disable(ip)
{
	$( "#disable_ip" ).val(ip);
	$( "#dialog-confirm" ).dialog( "open" );
}
</script>
<script> // enable confirmation
$(function() {
	$( "#dialog:ui-dialog2" ).dialog( "destroy" );

	$( "#dialog-confirm2" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		height:140,
		modal: true,
		show: 'blind',
		hide: 'blind',
		buttons: {
			"Enable IP": function() {
				var enable_ip = $( "#enable_ip" ).val();
				$.get("access_process.php?method=enable", { ip: enable_ip }, function (data) {
					var page_link = $( "#page_link" );
					$( "#display2" ).load("access_display2.php" + page_link.val());
					$( "#dialog-confirm2" ).dialog( "close" );
				});
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

function Enable(ip)
{
	$( "#enable_ip" ).val(ip);
	$( "#dialog-confirm2" ).dialog( "open" );
}
</script>

<div id="dialog-confirm" title="Disable IP?">
<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you would like to disable this IP?</p>
<input type="hidden" id="disable_ip" value="" />
</div>

<div id="dialog-confirm2" title="Enable IP?">
<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you would like to enable this IP?</p>
<input type="hidden" id="enable_ip" value="" />
</div>

<div id="dialog-form" title="Add Allowed IP">
<p class="validateTips">All fields are required.</p>
<table>
<tr><td width="85px">IP<span style="color:#ff0000;">*</span> </td>
<td><input id="ip" name="ip" type="text" style="width:150px;"></td></tr>
<tr><td>Description<span style="color:#ff0000;">*</span> </td>
<td><textarea id="description" name="description" rows="4" style="resize:none; width:200px; display:block;"></textarea></td></tr>
</table>
</div>

<div id="display">
<script>
$( "#display" ).hide();
$( "#display" ).load('access_display.php',
function() {
	$( "#display2" ).load('access_display2.php?page=0',
	function() {
		$( "#display" ).show('blind', '' , 'slow');
	});
});
</script>
</div>

<?php
include "../source/footer.php";
?>