<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
ui-dialog { padding: .3em; }
.ui-dialog_view_switch { padding: .3em; }
.ui-dialog_form { padding: .3em; }
.ui-dialog_script { padding: .3em; }
.ui-dialog_lead { padding: .3em; }
.ui-dialog_reject { padding: .3em; }
.validateTips { border: 1px solid transparent; padding: 0.3em; }
.validateTips2 { border: 1px solid transparent; padding: 0.3em; }
.validateTips3 { border: 1px solid transparent; padding: 0.3em; }
.ui-state-highlight { padding: .3em; }
div#users-contain table { margin: 1em 0; border-collapse: collapse; }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
div#users-contain table td { border: 1px solid #eee; padding: .3em 5px; text-align: left; }
</style>
<script>
$(function() {
	$( "#dialog:ui-dialog_view_switch" ).dialog( "destroy" );
	
	$( "#dialog-confirm_view_switch" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width: 375,
		height: 100,
		modal: true,
		show: "blind",
		hide: "blind"
	});
});

function View(type)
{
	var date = $( "#store_date" ),
		centre = $( "#store_centre" );
	
	$( "#store_type" ).val(type);
	$( "#dialog-confirm_view_switch" ).dialog( "close" );
	$( "#display2" ).hide('blind', '' , 'slow', function() {
		$( "#display2" ).load('sales_display2.php?date=' + date.val() + '&centre=' + centre.val() + '&type=' + type,
		function() {
			$( "#display2" ).show('blind', '' , 'slow');
		});
	});
}

function View_Switch(centre)
{
	$( "#store_centre" ).val(centre);
	$( "#dialog-confirm_view_switch" ).dialog( "open" );
}
</script>
<script>
function View_Sale(id)
{
	$( "#display" ).hide('blind', '' , 'slow', function() {
		$( "#display" ).load('sales_display_sale.php?id=' + id,
		function() {
			$( "#display" ).show('blind', '' , 'slow');
		});
	});
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	$( "#dialog-form" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:250,
		height:100,
		modal: true,
		show: "blind",
		hide: "blind"
	});
});
</script>
<script>
function Approve()
{
	var id = $( "#sale_id" ),
		verifier = "<?php echo $ac["user"]; ?>",
		lead = $( "#lead_check" ),
		recording = $( "#recording_check" ),
		details = $( "#details_check" );
	
	$.get("sales_process.php?method=approve", { id: id.val(), verifier: verifier, lead: lead.val(), recording: recording.val(), details: details.val() }, function (data) {
		if (data == 1)
		{
			var date = $( "#store_date" ),
				centre = $( "#store_centre" ),
				type = $( "#store_type" );
			
			$( "#display" ).hide('blind', '' , 'slow', function() {
				$( "#display" ).load('sales_display.php?date=' + date.val(),
				function() {
					$( "#display2" ).load('sales_display2.php?date=' + date.val() + '&centre=' + centre.val() + '&type=' + type.val(),
					function() {
						$( "#display" ).show('blind', '' , 'slow');
					});
				});
			});
		}
		else
		{
			$( ".validateTips" ).html(data);
			$( "#dialog-form" ).dialog( "open" );
		}
	});
}
</script>
<script> //reject sale
$(function() {
	$( "#dialog:ui-dialog_reject" ).dialog( "destroy" );
	
	var tips = $( ".validateTips3" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#dialog-form_reject" ).dialog({
		autoOpen: false,
		height: 225,
		width: 425,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Reject Sale": function() {
				var id = $( "#sale_id" ),
					verifier = "<?php echo $ac["user"]; ?>",
					reason = $( "#reason" ),
					lead = $( "#lead_check" ),
					recording = $( "#recording_check" ),
					details = $( "#details_check" );
				
				if (reason.val() == "")
				{
					updateTips("Please Write a Reason for Rejecting the Sale Below");
				}
				else
				{
					$.get("sales_process.php?method=reject",{id: id.val(), verifier: verifier, reason: reason.val(), lead: lead.val(), recording: recording.val(), details: details.val() },
					function(data) {
						if (data == "submitted")
						{
							$( "#dialog-form_reject" ).dialog( "close" );
							var date = $( "#store_date" ),
								centre = $( "#store_centre" ),
								type = $( "#store_type" );
							
							$( "#display" ).hide('blind', '' , 'slow', function() {
								$( "#display" ).load('sales_display.php?date=' + date.val(),
								function() {
									$( "#display2" ).load('sales_display2.php?date=' + date.val() + '&centre=' + centre.val() + '&type=' + type.val(),
									function() {
										$( "#display" ).show('blind', '' , 'slow');
									});
								});
							});
						}
						else
						{
							updateTips(data);
						}
					});
				}
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

function Reject()
{
	$( "#dialog-form_reject" ).dialog( "open" );
}
</script>

<div id="dialog-confirm_view_switch" title="Sale Status Type">
<table width="100%" height="55px">
<tr height="100%">
<td valign="middle" align="center"><button onclick="View('Pending')" class="btn">Pending</button></td>
<td valign="middle" align="center"><button onclick="View('Approved')" class="btn">Approved</button></td>
<td valign="middle" align="center"><button onclick="View('Rejected')" class="btn">Rejected</button></td>
</tr>
</table>
</div>

<div id="dialog-form" title="Error">
<span class='ui-icon ui-icon-alert' style='float:left; margin-right:.3em; margin-top:4px'></span><p class="validateTips"></p>
</div>

<div id="dialog-form_reject" title="Reject Sale">
<p class="validateTips3">Please Write a Reason for Rejecting the Sale Below</p>
<table width="100%">
<tr>
<td width="50px">Reason </td>
<td><textarea id="reason" style="width:100%; height:100px; resize:none;"></textarea></td>
</tr>
</table>
</div>

<input type="hidden" id="store_date" value="<?php echo date("Y-m-d"); ?>" />
<input type="hidden" id="store_centre" value="" />
<input type="hidden" id="store_type" value="" />

<div id="display">
<script>
$( "#display" ).load('sales_display.php?date=<?php echo date("Y-m-d"); ?>',
function() {
	$( "#display" ).show('blind', '' , 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>