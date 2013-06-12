<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<link rel="stylesheet" type="text/css" href="upload/uploadify.css" />
<script type="text/javascript" src="upload/jquery.uploadify-3.1.min.js"></script>
<style>
#physical_address_code { height:120px; margin:0px; overflow-y:auto; border:1px solid black; padding:3px; }
#postal_address_code  { height:120px; margin:0px; overflow-y:auto; border:1px solid black; padding:3px; }
.ui-dialog { padding: .3em; }
.ui-dialog0 { padding: .3em; }
.ui-dialog2 { padding: .3em; }
.ui-dialog3 { padding: .3em; }
.ui-dialog4 { padding: .3em; }
.ui-dialog_dd { padding: .3em; }
.ui-dialog_dd_cc { padding: .3em; }
.ui-dialog_dd_bank { padding: .3em; }
.ui-dialog_form0 { padding: .3em; }
.ui-dialog_physical { padding: .3em; }
.ui-dialog_physical_confirm { padding: .3em; }
.ui-dialog_postal { padding: .3em; }
.ui-dialog_postal_mailbox { padding: .3em; }
.ui-dialog_postal_confirm { padding: .3em; }
.ui-dialog_postal_confirm_switch { padding: .3em; }
.ui-dialog_na_switch { padding: .3em; }
.ui-dialog_call_back { padding: .3em; }
.ui-dialog_complete { padding: .3em; }
.ui-dialog_reject { padding: .3em; }
.ui-dialog_upgrade { padding: .3em; }
.ui-state-highlight { padding: .3em; }
.validateTips { border: 1px solid transparent; padding: 0.3em; }
.validateTips2 { border: 1px solid transparent; padding: 0.3em; }
.validateTips3 { border: 1px solid transparent; padding: 0.3em; }
.validateTips4 { border: 1px solid transparent; padding: 0.3em; }
.validateTips5 { border: 1px solid transparent; padding: 0.3em; }
.validateTips6 { border: 1px solid transparent; padding: 0.3em; }
.validateTips7 { border: 1px solid transparent; padding: 0.3em; }
.validateTips8 { border: 1px solid transparent; padding: 0.3em; }
.validateTipsPhysical { border: 1px solid transparent; padding: 0.3em; }
.validateTipsPostal { border: 1px solid transparent; padding: 0.3em; }
.validateTipsMB { border: 1px solid transparent; padding: 0.3em; }
.ui-autocomplete { max-height: 300px; overflow-y: auto; overflow-x: hidden; padding-right: 20px; }
.ui-autocomplete-loading { background: white url('../images/ajax-loader.gif') right center no-repeat; }
.ui-widget-overlay {z-index: 3999; }
div#users-contain table { margin: 1em 0; border-collapse: collapse; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
</style>

<script>
$(function() {
	$( "#dialog:ui-dialog0" ).dialog( "destroy" );
	
	$( "#dialog-form0" ).dialog({
		autoOpen: false,
		height: 175,
		width: 300,
		modal: true,
		resizable: false,
		draggable: false,
		show: "blind",
		hide: "blind"
	});
});

function Rec(type)
{
	$( "#processing_type" ).val(type);
	$( "#dialog-form0" ).dialog( "open" );
}
</script>
<script>
$(function() {
    $('#file_upload').uploadify({
		'fileSizeLimit' : '5MB',
		'fileTypeDesc' : 'GSM,MP3',
        'fileTypeExts' : '*.gsm;*.mp3',
		'multi'    : false,
		'progressData' : 'speed',
		'removeTimeout' : 0,
        'swf'      : 'upload/uploadify.swf',
        'uploader' : 'upload/uploadify.php',
        'buttonText' : 'BROWSE...',
		'buttonClass' : 'btn',
		'width'    : 102,
		'onUploadSuccess' : function(file, data, response) {
			$( "#rec" ).attr("style", "display:none;");
			$( "#rec2" ).html('<br><img src="../images/ajax-loader.gif"> Processing Voice File...');
			$( "#rec2" ).removeAttr("style");
			$.get("customers_submit.php", { method: "rename_rec", file: file, id: $( "#account_id" ).val() },
			function(data2) {
				if (data2 == 1)
				{
					$( "#rec2" ).html("<br>Voice File Successfully Uploaded");
					setTimeout(function() {
						$( "#rec2" ).attr("style", "display:none;");
						$( "#dialog-form0" ).dialog( "close" );
						if ($( "#processing_type" ).val() == "Upgrade")
						{
							Upgrade();
							setTimeout("", 500);
						}
						else if ($( "#processing_type" ).val() == "Complete")
						{
							Complete();
						}
						$( "#rec" ).removeAttr("style");
					}, 2000);
				}
				else
				{
					$( "#rec2" ).html("<br>Voice File Didn't Uploaded Successfully. Please try again");
					setTimeout(function() {
						$( "#rec" ).removeAttr("style");
						$( "#rec2" ).attr("style", "display:none;");
					}, 2000);
				}
			});
		}
    });
});
</script>
<script>
function Check_Rec()
{
	$( "#rec" ).attr("style", "display:none;");
	$( "#rec2" ).html('<br><img src="../images/ajax-loader.gif"> Processing Voice File...');
	$( "#rec2" ).removeAttr("style");
	$.get("customers_submit.php", { method: "check_rec", id: $( "#account_id" ).val() },
	function(data) {
		if (data == 1)
		{
			$( "#rec2" ).html("<br>Voice File Successfully Uploaded");
			setTimeout(function() {
				$( "#rec2" ).attr("style", "display:none;");
				$( "#dialog-form0" ).dialog( "close" );
				if ($( "#processing_type" ).val() == "Upgrade")
				{
					Upgrade();
					setTimeout("", 500);
				}
				else if ($( "#processing_type" ).val() == "Complete")
				{
					Complete();
				}
				$( "#rec" ).removeAttr("style");
			}, 2000);
		}
		else
		{
			$( "#rec2" ).html("<br>Voice File Didn't Uploaded Successfully. Please try again");
			setTimeout(function() {
				$( "#rec" ).removeAttr("style");
				$( "#rec2" ).attr("style", "display:none;");
			}, 2000);
		}
	});
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	$( "#dialog-form" ).dialog({
		autoOpen: true,
		height: 100,
		width: 275,
		modal: true,
		resizable: false,
		draggable: false,
		show: "blind",
		hide: "blind"
	});
});

function Business_Customers()
{
	$( "#dialog-form" ).dialog( "close" );
	$( "#customer_type_store" ).val("Business");
	$( "#display" ).load('customers_display.php?type=Business&user=<?php echo $ac["user"]; ?>',
	function() {
		$( "#display" ).show('blind', '', 'slow');
	});
}

function Residential_Customers()
{
	$( "#dialog-form" ).dialog( "close" );
	$( "#customer_type_store" ).val("Residential");
	$( "#display" ).load('customers_display.php?type=Residential&user=<?php echo $ac["user"]; ?>',
	function() {
		$( "#display" ).show('blind', '', 'slow');
	});
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog_dd" ).dialog( "destroy" );
	
	$( "#dialog-confirm_dd" ).dialog({
		autoOpen: false,
		height: 100,
		width: 275,
		modal: true,
		resizable: false,
		draggable: false,
		show: "blind",
		hide: "blind"
	});
});

function DD_CC()
{
	$( "#dialog-confirm_dd" ).dialog( "close" );
	$( "#dialog-confirm_dd_cc" ).dialog( "open" );
	
}

function DD_Bank()
{
	$( "#dialog-confirm_dd" ).dialog( "close" );
	$( "#dialog-confirm_dd_bank" ).dialog( "open" );
}

function DD()
{
	$( "#dialog-confirm_dd" ).dialog( "open" );
}
</script>
<script> // DD Credit
$(function() {
	$( "#dialog:ui-dialog_dd_cc" ).dialog( "destroy" );
	
	var tips = $( ".validateTips7" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	
	$( "#dialog-confirm_dd_cc" ).dialog({
		autoOpen: false,
		height: 225,
		width: 275,
		modal: true,
		resizable: false,
		draggable: false,
		show: "blind",
		hide: "blind",
		buttons: {
			"Submit": function() {
				var id = $( "#account_id" ),
					user = "<?php echo $ac["user"]; ?>",
					cardholder = $( "#cardholder" ),
					cardtype = $( "#card_type" ),
					cardnumber = $( "#card_number" ),
					cardexpiry_m = $( "#card_expiry_m" ),
					cardexpiry_y = $( "#card_expiry_y" );
				
				$.get("customers_submit.php?method=dd", { id: id.val(), type: "CC", user: user, cardholder: cardholder.val(), cardtype: cardtype.val(), cardnumber: cardnumber.val(), cardexpiry_m: cardexpiry_m.val(), cardexpiry_y: cardexpiry_y.val() },
				function(data) {
					if (data.substring(0,4) == "done")
					{
						$( "#dd_btn" ).attr("disabled", true);
						$( "#done_dd" ).val("1");
						$( "#payway" ).val(data.substring(4));
						$( "#dd_type" ).val(cardtype.val());
						$( "#dialog-confirm_dd_cc" ).dialog( "close" );
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
</script>
<script> // DD Bank
$(function() {
	$( "#dialog:ui-dialog_dd_bank" ).dialog( "destroy" );
	
	var tips = $( ".validateTips8" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	
	$( "#dialog-confirm_dd_bank" ).dialog({
		autoOpen: false,
		height: 200,
		width: 275,
		modal: true,
		resizable: false,
		draggable: false,
		show: "blind",
		hide: "blind",
		buttons: {
			"Submit": function() {
				var id = $( "#account_id" ),
					accountname = $( "#accountname" ),
					bsb = $( "#bsb" ),
					accountnumber = $( "#accountnumber" ),
					user = "<?php echo $ac["user"]; ?>";
				
				$.get("customers_submit.php?method=dd", { id: id.val(), type: "Bank", accountname: accountname.val(), bsb: bsb.val(), accountnumber: accountnumber.val(), user: user },
				function(data) {
					if (data.substring(0,4) == "done")
					{
						$( "#dd_btn" ).attr("disabled", true);
						$( "#done_dd" ).val("1");
						$( "#payway" ).val(data.substring(4));
						$( "#dialog-confirm_dd_bank" ).dialog( "close" );
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
</script>
<script> //add packages
$(function() {
	$( "#dialog:ui-dialog2" ).dialog( "destroy" );
	
	var tips = $( ".validateTips2" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#dialog-form2" ).dialog({
		autoOpen: false,
		height: 200,
		width: 275,
		modal: true,
		resizable: false,
		draggable: false,
		hide: "blind",
		buttons: {
			"Add Package": function() {
				var id = $( "#account_id" ),
					cli = $( "#cli" ),
					plan = $( "#plan" ),
					user = "<?php echo $ac["user"]; ?>";
				
				if (cli.val() == "")
				{
					updateTips("Enter the CLI!");
				}
				else if (plan.val() == "")
				{
					updateTips("Select a plan!");
				}
				else
				{
					$.get("customers_submit.php?method=add", { id: id.val(), cli: cli.val(), plan: plan.val(), user: user },
					function(data) {
						if (data == "added")
						{
							$( "#upgrade_packages" ).load('packages2.php?id=' + id.val());
							$( "#dialog-form2" ).dialog( "close" );
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
		},
		close: function() {
		}
	});
});

function Add_Package()
{
	$( "#cli" ).val("");
	$( "#plan" ).val("");
	$( "#validateTips2" ).text("All fields are required");
	$( "#dialog-form2" ).dialog( "open" );
}

function Plan_Dropdown()
{
	$( "#plan" ).val("");
	$( "#plan" ).load("plans.php?id=" + $( "#account_id" ).val() + "&type=" + $( "#sale_type" ).val() + "&cli=" + $('#cli').val());
}
</script>
<script> //edit packages
$(function() {
	$( "#dialog:ui-dialog3" ).dialog( "destroy" );
	
	var tips = $( ".validateTips3" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#dialog-form3" ).dialog({
		autoOpen: false,
		height: 200,
		width: 275,
		modal: true,
		resizable: false,
		draggable: false,
		hide: "blind",
		buttons: {
			"Edit Package": function() {
				var id = $( "#account_id" ),
					cli = $( "#edit_cli" ),
					plan = $( "#edit_plan" ),
					user = "<?php echo $ac["user"]; ?>";
				
				if (plan.val() == "")
				{
					updateTips("Select a plan!");
				}
				else
				{
					$.get("customers_submit.php?method=edit", { id: id.val(), cli: cli.val(), plan: plan.val(), user: user },
					function(data) {
						if (data == "editted")
						{
							$( "#upgrade_packages" ).load('packages2.php?id=' + id.val());
							$( "#dialog-form3" ).dialog( "close" );
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
		},
		close: function() {
		}
	});
});

function Edit_Package(cli,plan)
{
	$( "#edit_cli" ).val(cli);
	$( "#edit_plan" ).load("plans.php?id=" + $( "#account_id" ).val() + "&type=" + $( "#sale_type" ).val() + "&cli=" + $('#edit_cli').val(), function() {
		$( "#edit_plan" ).val(plan);
	});
	$( "#dialog-form3" ).dialog( "open" );
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog_na_switch" ).dialog( "destroy" );
	
	$( "#dialog-confirm_na" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width: 275,
		height: 100,
		modal: true,
		show: "blind",
		hide: "blind"
	});
});

function No_Answer()
{
	$( "#dialog-confirm_na" ).dialog( "close" );
	$( "#display" ).hide('blind', '', 'slow', function() {
		$( "#display" ).load('customers_display.php?type=' + $( "#customer_type_store" ).val() + '&user=<?php echo $ac["user"]; ?>',
		function() {
			$( "#display" ).show('blind', '', 'slow');
		});
	});
}

function Call_Back()
{
	$( ".validateTips5" ).text("All fields are required");
	$( "#cb_time_h" ).val("");
	$( "#cb_time_m" ).val("");
	$( "#cb_time_p" ).val("");
	$( "#dialog-confirm_na" ).dialog( "close" );
	setTimeout(function() {$( "#dialog-confirm_call_back" ).dialog( "open" );},500);
}

function NA_Switch()
{
	$( "#dialog-confirm_na" ).dialog( "open" );
}
</script>
<script> //call back datepicker
$(function() {
	$( "#cb_datepicker" ).datepicker( {
		showOn: "button",
		buttonImage: "../images/calendar.png",
		buttonImageOnly: true,
		dateFormat: "yy-mm-dd",
		firstDay: 1,
		showOtherMonths: true,
		selectOtherMonths: true,
		altField: "#cb_datepicker2",
		altFormat: "dd/mm/yy",
		minDate: "0d",
		maxDate: "3d"
	});
});
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog_call_back" ).dialog( "destroy" );
	
	var tips = $( ".validateTips5" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	
	$( "#dialog-confirm_call_back" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width: 250,
		height: 170,
		modal: true,
		show: "blind",
		hide: "blind",
		buttons: {
			"Submit": function() {
				var id = $( "#account_id" ),
					date = $("#cb_datepicker" ),
					time_h = $( "#cb_time_h" ),
					time_m = $( "#cb_time_m" ),
					time_p = $( "#cb_time_p" );
				
				if (time_h.val() == "" || time_m.val() == "" || time_p.val() == "")
				{
					updateTips("Enter a valid call back time!");
				}
				else
				{
					var time = time_h.val() + ":" + time_m.val() + ":00 " + time_p.val();
					
					$.get("customers_submit.php?method=call_back", { id: id.val(), date: date.val(), time: time },
					function(data) {
						if (data == "done")
						{
							$( "#dialog-confirm_call_back" ).dialog( "close" );
							$( "#display" ).hide('blind', '', 'slow', function() {
								$( "#display" ).load('customers_display.php?type=' + $( "#customer_type_store" ).val() + '&user=<?php echo $ac["user"]; ?>',
								function() {
									$( "#display" ).show('blind', '', 'slow');
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
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog_complete" ).dialog( "destroy" );
	
	$( "#dialog-confirm_complete" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width: 275,
		height: 100,
		modal: true,
		show: "blind",
		hide: "blind"
	});
});

function Complete()
{
	$( "#dialog-confirm_complete" ).dialog( "open" );
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog_reject" ).dialog( "destroy" );
	
	var tips = $( ".validateTips6" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}
	
	$( "#dialog-confirm_reject" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width: 300,
		height: 225,
		modal: true,
		show: "blind",
		hide: "blind",
		buttons: {
			"Reject": function() {
				var id = $( "#account_id" ),
					user = "<?php echo $ac["user"]; ?>",
					reason = $( "#reject_reason" ),
					notes = $( "#reject_notes" ),
					rec = $( "#rec_store" );
				
				$.get("customers_submit.php?method=reject", { id: id.val(), user: user, reason: reason.val(), notes: notes.val(), rec: rec.val() },
				function(data) {
					if (data == "rejected")
					{
						$( "#dialog-confirm_reject" ).dialog( "close" );
						$( "#display" ).hide('blind', '', 'slow', function() {
							$( "#display" ).load('customers_display.php?type=' + $( "#customer_type_store" ).val() + '&user=<?php echo $ac["user"]; ?>',
							function() {
								$( "#display" ).show('blind', '', 'slow');
							});
						});
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
function Reject()
{
	$( ".validateTips6" ).text("All fields are required");
	$( "#reject_reason" ).val("");
	$( "#reject_notes" ).val("");
	$( "#dialog-confirm_complete" ).dialog( "close" );
	setTimeout(function() {$( "#dialog-confirm_reject" ).dialog( "open" );},500);
}
</script>
<script>
function Approve()
{
	var id = $( "#account_id" ),
		title = $( "#title" ),
		first = $( "#first" ),
		middle = $( "#middle" ),
		last = $( "#last" ),
		dob = $( "#datepicker" ),
		email = $( "#email" ),
		mobile = $( "#mobile" ),
		physical = $( "#physical" ),
		postal = $( "#postal" ),
		id_type = $( "#id_type" ),
		id_num = $( "#id_num" ),
		abn = $( "#abn" ),
		abn_status = $( ".abn_status" ),
		position = $( "#position" ),
		ongoing_credit = $( "#ongoing_credit" ),
		onceoff_credit = $( "#onceoff_credit" ),
		payway = $( "#payway" ),
		dd_type = $( "#dd_type" ),
		user = "<?php echo $ac["user"]; ?>",
		rec = $( "#rec_store" ),
		dd = $( "#done_dd" );
		
		if ($('#postal_same').attr('checked'))
		{
			postal = $( "#physical" );
		}
	
	$.get("customers_submit.php?method=approve", { id: id.val(), title: title.val(), first: first.val(), middle: middle.val(), last: last.val(), dob: dob.val(), email: email.val(), mobile: mobile.val(), physical: physical.val(), postal: postal.val(), id_type: id_type.val(), id_num: id_num.val(), abn: abn.val(), abn_status: abn_status.html(), position: position.val(), ongoing_credit: ongoing_credit.val(), onceoff_credit: onceoff_credit.val(), payway: payway.val(), dd_type: dd_type.val(), user: user, rec: rec.val(), dd: dd.val() },
	function(data) {
		if (data == "submitted")
		{
			$( "#dialog-confirm_complete" ).dialog( "close" );
			$( "#display" ).hide('blind', '', 'slow', function() {
				$( "#display" ).load('customers_display.php?type=' + $( "#customer_type_store" ).val() + '&user=<?php echo $ac["user"]; ?>',
				function() {
					$( "#display" ).show('blind', '', 'slow');
				});
			});
		}
		else
		{
			$( ".validateTips4" ).html(data);
			$( "#dialog-confirm_complete" ).dialog( "close" );
			setTimeout(function() {$( "#dialog-form4" ).dialog( "open" );},500);
		}
	});
}
</script>
<script> //error dialog
$(function() {
	$( "#dialog:ui-dialog4" ).dialog( "destroy" );
	
	$( "#dialog-form4" ).dialog({
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
$(function() {
	$( "#dialog:ui-dialog_upgrade" ).dialog( "destroy" );
	
	$( "#dialog-confirm_upgrade" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width: 550,
		height: 300,
		modal: true,
		show: "blind",
		hide: "blind",
		buttons: {
			"Submit": function() {
				var id = $( "#account_id" ),
					title = $( "#title" ),
					first = $( "#first" ),
					middle = $( "#middle" ),
					last = $( "#last" ),
					dob = $( "#datepicker" ),
					email = $( "#email" ),
					mobile = $( "#mobile" ),
					physical = $( "#physical" ),
					postal = $( "#postal" ),
					id_type = $( "#id_type" ),
					id_num = $( "#id_num" ),
					abn = $( "#abn" ),
					abn_status = $( ".abn_status" ),
					position = $( "#position" ),
					ongoing_credit = $( "#ongoing_credit" ),
					onceoff_credit = $( "#onceoff_credit" ),
					payway = $( "#payway" ),
					dd_type = $( "#dd_type" ),
					user = "<?php echo $ac["user"]; ?>",
					rec = $( "#rec_store" ),
					dd = $( "#done_dd" );
					
					if ($('#postal_same').attr('checked'))
					{
						postal = $( "#physical" );
					}
				
				$.get("customers_submit.php?method=upgrade", { id: id.val(), title: title.val(), first: first.val(), middle: middle.val(), last: last.val(), dob: dob.val(), email: email.val(), mobile: mobile.val(), physical: physical.val(), postal: postal.val(), id_type: id_type.val(), id_num: id_num.val(), abn: abn.val(), abn_status: abn_status.html(), position: position.val(), ongoing_credit: ongoing_credit.val(), onceoff_credit: onceoff_credit.val(), payway: payway.val(), dd_type: dd_type.val(), user: user, rec: rec.val(), dd: dd.val() },
				function(data) {
					if (data == "submitted")
					{
						$( "#dialog-confirm_upgrade" ).dialog( "close" );
						$( "#display" ).hide('blind', '', 'slow', function() {
							$( "#display" ).load('customers_display.php?type=' + $( "#customer_type_store" ).val() + '&user=<?php echo $ac["user"]; ?>',
							function() {
								$( "#display" ).show('blind', '', 'slow');
							});
						});
					}
					else
					{
						$( ".validateTips4" ).html(data);
						$( "#dialog-confirm_upgrade" ).dialog( "close" );
						setTimeout(function() {$( "#dialog-form4" ).dialog( "open" );},500);
					}
				});
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

function Upgrade()
{
	var id = $( "#account_id" );
	
	$( "#upgrade_packages" ).load("packages2.php?id=" + id.val());
	$( "#dialog-confirm_upgrade" ).dialog( "open" );
}
</script>
<script> //get ABN
function getABN(){
	$.getJSON("../source/abrGet.php", {abn: $("#abn").val() },
	function(data){
		if (data['error'] == "true")
		{
			$(".bus_name").html( "" );
			$(".abn_status").html( "" );
			$(".bus_type").html( "" );
		}
		else 
		{
			var abn = $("#abn").val();
			$("#abn").val( abn.replace(/\s/g,""));
			if( data['organisationName'] != null) {
				$(".bus_name").html( data['organisationName'] );
			}
			else if (data['tradingName'] != null) {
				$(".bus_name").html( data['tradingName'] );
			}
			else {
				$(".bus_name").html( data['entityName'] );
			}
			$(".abn_status").html( data['entityStatus'] );
			$(".bus_type").html( data['entityDescription'] );
		}
	});
}
</script>
<script>
function Email()
{
	if ($('#no_email').attr('checked'))
	{
		$( "#email" ).val("N/A");
		$( "#email" ).attr("disabled", true);
	}
	else
	{
		$( "#email" ).val("");
		$( "#email" ).removeAttr("disabled");
	}
}

function Mobile()
{
	if ($('#no_mobile').attr('checked'))
	{
		$( "#mobile" ).val("N/A");
		$( "#mobile" ).attr("disabled", true);
	}
	else
	{
		$( "#mobile" ).val("");
		$( "#mobile" ).removeAttr("disabled");
	}
}
</script>
<!--#########################################################-->
<!--##													   ##-->
<!--##					PHYSICAL ADDRESS				   ##-->
<!--##													   ##-->
<!--#########################################################-->
<script>
$(function() {
	$( "#dialog:ui-dialog_physical_confirm" ).dialog( "destroy" );

	$( "#dialog-confirm_physical2" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width: 400,
		height: 235,
		modal: true,
		buttons: {
			"Select": function() {
				if ($( "input[name=address_code]:checked" ).val() != undefined)
				{
					$.get("../source/gnafGet.php?type=display", { id: $( "input[name=address_code]:checked" ).val() },
						function(data) {
							var n = data.split("}");
							$( "#display_physical1" ).val(n[0]);
							$( "#display_physical2" ).val(n[1]);
							$( "#display_physical3" ).val(n[2]);
							$( "#display_physical4" ).val(n[3]);
						});
					$( "#physical" ).val($( "input[name=address_code]:checked" ).val());
					$( "#dialog-confirm_physical2" ).dialog( "close" );
					$( "#dialog-confirm_physical" ).dialog( "close" );
				}
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
</script>
<script>
$(function() {
	$( "#dialog:.ui-dialog_physical" ).dialog( "destroy" );
	
	var tips = $( ".validateTipsPhysical" );
	
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#dialog-confirm_physical" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width: 475,
		height:350,
		modal: true,
		show: "blind",
		hide: "blind",
		buttons: {
			"Verify Address": function() {
				var address_type = $('input[name=physical_type]:checked'),
					building_type = $( "#physical_building_type" ),
					building_number = $( "#physical_building_number" ),
					building_name = $( "#physical_building_name" ),
					street_number = $( "#physical_street_number" ),
					street_name = $( "#physical_street_name" ),
					street_type = $( "#physical_street_type" ),
					l_pid = $('#physical_locality_pid');
					
					$.get("../source/gnafGet.php?type=check", { address_type: address_type.val(), l_pid: l_pid.val(), building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data) {
						if (data == "valid")
						{
							$( "#physical_address_code" ).attr("style","display:none;");
							$( "#physical_manual_store" ).attr('style','display:none;');
							$( "#dialog-confirm_physical2" ).dialog( "open" );
							$( "#physical_search_div" ).html("<center><br><br><br><br><p><img src='../images/ajax-loader.gif'>&nbsp;&nbsp;&nbsp;&nbsp;Please wait. Verifying your address...</p></center>");
							$( "#physical_search_div" ).removeAttr('style');
							
							$.get("../source/gnafGet.php?type=search", { address_type: address_type.val(), l_pid: l_pid.val(), building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data) {
								if (data == 'No Results Found')
								{
									$( "#physical_search_div" ).html("<center><br><br><br><br><p><img src='../images/ajax-loader.gif'>&nbsp;&nbsp;&nbsp;&nbsp;Exact match not found. Looking up similar...</p></center>");
									
									$.get("../source/gnafGet.php?type=format", { l_pid: l_pid.val(), building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data0) {
										/// CLOUD GEOCODER API GET
										$.get("../source/gnafGet.php?type=test", { input: data0 }, function(data2) {
											$.get("../source/gnafGet.php?type=search2", { address_type: address_type.val(), a_pid: data2, building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data3) {
												$( "#physical_address_code" ).html(data3);
												$( "#physical_search_div" ).attr("style","display:none;");
												$( "#physical_address_code" ).removeAttr('style');
												$( "#physical_manual_store" ).removeAttr('style');
											});
										});
										///
									});
								}
								else
								{
									$( "#physical_address_code" ).html(data);
									$( "#physical_search_div" ).attr("style","display:none;");
									$( "#physical_address_code" ).removeAttr('style');
									$( "#physical_manual_store" ).removeAttr('style');
								}
							});
						}
						else
						{
							updateTips(data);
						}
					});
			},
			"Reset": function() {
				$( "#physical_input" ).val("");
				$( "#physical_input2" ).val("");
				$( "#physical_building_type_tr" ).attr("style","display:none;");
				$( "#physical_building_number_tr" ).attr("style","display:none;");
				$( "#physical_building_name_tr" ).attr("style","display:none;");
				$( "#physical_street_number_tr" ).attr("style","display:none;");
				$( "#physical_street_tr" ).attr("style","display:none;");
				$( "#physical_suburb_tr" ).attr("style","display:none;");
				$( "#physical_state_tr" ).attr("style","display:none;");
				$( "#physical_postcode_tr" ).attr("style","display:none;");
				$( "#physical_input_tr" ).attr("style","display:none;");
				$( "#physical_type_tr" ).attr("style","display:none;");
				$( "#physical_input_tr" ).removeAttr("style");
				$( "#physical_building_type option" ).remove();
				$( "#physical_building_number" ).val("");
				$( "#physical_building_name" ).val("");
				$( "#physical_street_number" ).val("");
				$( "#physical_street_name" ).val("");
				$( "#physical_street_type" ).val("");
				$( "#physical_suburb" ).val("");
				$( "#physical_state" ).val("");
				$( "#physical_postcode" ).val("");
				$('input[name=physical_type]:checked').removeAttr("checked");
			}
		}
	});
});

$(function() {
	$( "#physical_input" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "../source/gnafGet.php",
				dataType: "json",
				data: {
					type : "input",
					term : request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 3,
		select: function (event, ui) {
			$( "#physical_locality_pid" ).val(ui.item.id);
			$.get("../source/gnafGet.php?type=input_check", { l_pid: ui.item.id }, function(data) {
				var data2 = data.split(",");
				
				$( "#physical_suburb" ).val(data2[0]);
				$( "#physical_state" ).val(data2[1]);
				$( "#physical_postcode" ).val(data2[2]);
				$( "#physical_input_tr" ).attr("style","display:none;");
				$( "#physical_type_tr" ).removeAttr("style");
			});
		}
	});
});

$(function() {
	$( "#physical_input2" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "../source/gnafGet.php",
				dataType: "json",
				data: {
					type : "input2",
					term : request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 2,
		select: function (event, ui) {
			$( "#physical_locality_pid" ).val(ui.item.id);
			$.get("../source/gnafGet.php?type=input_check", { l_pid: ui.item.id }, function(data) {
				var data2 = data.split(",");
				
				$( "#physical_suburb" ).val(data2[0]);
				$( "#physical_state" ).val(data2[1]);
				$( "#physical_postcode" ).val(data2[2]);
				$( "#physical_input_tr" ).attr("style","display:none;");
				$( "#physical_type_tr" ).removeAttr("style");
			});
		}
	});
});

function FS_Physical()
{
	$( "#physical_building_type option" ).remove();
	$( "#physical_street_number_tr" ).removeAttr("style");
	$( "#physical_street_tr" ).removeAttr("style");
	$( "#physical_suburb_tr" ).removeAttr("style");
	$( "#physical_state_tr" ).removeAttr("style");
	$( "#physical_postcode_tr" ).removeAttr("style");
	$( "#physical_building_type_tr" ).attr("style","display:none;");
	$( "#physical_building_number_tr" ).attr("style","display:none;");
	$( "#physical_building_name_tr" ).attr("style","display:none;");
}

function OB_Physical()
{
	$( "#physical_building_type option" ).remove();
	$( "#physical_building_type" ).append(new Option('LEVEL', 'LEVEL', true, true));
	$( "#physical_building_number_span" ).html("Level Number ");
	$( "#physical_building_number_tr" ).removeAttr("style");
	$( "#physical_street_number_tr" ).removeAttr("style");
	$( "#physical_street_tr" ).removeAttr("style");
	$( "#physical_suburb_tr" ).removeAttr("style");
	$( "#physical_state_tr" ).removeAttr("style");
	$( "#physical_postcode_tr" ).removeAttr("style");
	$( "#physical_building_type_tr" ).attr("style","display:none;");
	$( "#physical_building_name_tr" ).attr("style","display:none;");
}

function BU_Physical()
{
	var newOptions = {
		'' : '',
		'APARTMENT' : 'APARTMENT',
		'DUPLEX' : 'DUPLEX',
		'FACTORY' : 'FACTORY',
		'FLAT' : 'FLAT',
		'HALL' : 'HALL',
		'OFFICE' : 'OFFICE',
		'PENTHOUSE' : 'PENTHOUSE',
		'ROOM' : 'ROOM',
		'SECTION' : 'SECTION',
		'SHOP' : 'SHOP',
		'SITE' : 'SITE',
		'STORE' : 'STORE',
		'STUDIO' : 'STUDIO',
		'SUITE' : 'SUITE',
		'TOWNHOUSE' : 'TOWNHOUSE',
		'UNIT' : 'UNIT',
		'VILLA' : 'VILLA'
	};
	var selectedOption = '';
	
	var select = $('#physical_building_type');
	if(select.prop) {
	  var options = select.prop('options');
	}
	else {
	  var options = select.attr('options');
	}
	$( "#physical_building_type option" ).remove();
	
	$.each(newOptions, function(val, text) {
		options[options.length] = new Option(text, val);
	});
	select.val(selectedOption);
	$( "#physical_building_type_tr" ).removeAttr("style");
	$( "#physical_building_number_span" ).html("Building Number ");
	$( "#physical_building_number_tr" ).removeAttr("style");
	$( "#physical_street_number_tr" ).removeAttr("style");
	$( "#physical_street_tr" ).removeAttr("style");
	$( "#physical_suburb_tr" ).removeAttr("style");
	$( "#physical_state_tr" ).removeAttr("style");
	$( "#physical_postcode_tr" ).removeAttr("style");
	$( "#physical_building_name_tr" ).attr("style","display:none;");
}

function LOT_Physical()
{
	$( "#physical_building_type option" ).remove();
	$( "#physical_building_type" ).append(new Option('LOT', 'LOT', true, true));
	$( "#physical_building_number_span" ).html("Lot Number ");
	$( "#physical_building_number_tr" ).removeAttr("style");
	$( "#physical_street_tr" ).removeAttr("style");
	$( "#physical_suburb_tr" ).removeAttr("style");
	$( "#physical_state_tr" ).removeAttr("style");
	$( "#physical_postcode_tr" ).removeAttr("style");
	$( "#physical_street_number_tr" ).attr("style","display:none;");
	$( "#physical_building_type_tr" ).attr("style","display:none;");
	$( "#physical_building_name_tr" ).attr("style","display:none;");
}

function OTH_Physical()
{
	var newOptions = {
		'' : '',
		'APARTMENT' : 'APARTMENT',
		'BLOCK' : 'BLOCK',
		'BUILDING' : 'BUILDING',
		'DUPLEX' : 'DUPLEX',
		'FACTORY' : 'FACTORY',
		'FLAT' : 'FLAT',
		'HALL' : 'HALL',
		'LOT' : 'LOT',
		'OFFICE' : 'OFFICE',
		'PENTHOUSE' : 'PENTHOUSE',
		'ROOM' : 'ROOM',
		'SECTION' : 'SECTION',
		'SHOP' : 'SHOP',
		'SITE' : 'SITE',
		'STORE' : 'STORE',
		'STUDIO' : 'STUDIO',
		'SUITE' : 'SUITE',
		'TOWNHOUSE' : 'TOWNHOUSE',
		'UNIT' : 'UNIT',
		'VILLA' : 'VILLA'
	};
	var selectedOption = '';
	
	var select = $('#physical_building_type');
	if(select.prop) {
	  var options = select.prop('options');
	}
	else {
	  var options = select.attr('options');
	}
	$( "#physical_building_type option" ).remove();
	
	$.each(newOptions, function(val, text) {
		options[options.length] = new Option(text, val);
	});
	select.val(selectedOption);
	$( "#physical_building_name_tr" ).removeAttr("style");
	$( "#physical_building_type_tr" ).removeAttr("style");
	$( "#physical_building_number_span" ).html("Building Number ");
	$( "#physical_building_number_tr" ).removeAttr("style");
	$( "#physical_street_number_tr" ).removeAttr("style");
	$( "#physical_street_tr" ).removeAttr("style");
	$( "#physical_suburb_tr" ).removeAttr("style");
	$( "#physical_state_tr" ).removeAttr("style");
	$( "#physical_postcode_tr" ).removeAttr("style");
}

$(function() {
	$( "#physical_street_name" ).autocomplete({
		source: function(request, response) {
        $.ajax({
          url: "../source/gnafGet.php",
               dataType: "json",
          data: {
			  type : "street_name",
			  l_pid : $('#physical_locality_pid').val(),
  			  term : request.term
          },
          success: function(data) {
            response(data);
          }
        });
      },
		minLength: 1
	});
});

$(function() {
	$( "#physical_street_type" ).autocomplete({
		source: function(request, response) {
        $.ajax({
          url: "../source/gnafGet.php",
               dataType: "json",
          data: {
			  type : "street_type",
			  l_pid : $('#physical_locality_pid').val(),
			  street_name : $( "#physical_street_name" ).val(),
  			  term : request.term
          },
          success: function(data) {
            response(data);
          }
        });
      },
		minLength: 1
	});
});

function Manual_Physical()
{
	var building_type = $( "#physical_building_type" ),
		building_number = $( "#physical_building_number" ),
		building_name = $( "#physical_building_name" ),
		street_number = $( "#physical_street_number" ),
		street_name = $( "#physical_street_name" ),
		street_type = $( "#physical_street_type" ),
		l_pid = $('#physical_locality_pid');
	
	$( "#physical_address_code" ).attr("style","display:none;");
	$( "#physical_manual_store" ).attr('style','display:none;');
	$( "#physical_search_div" ).html("<center><br><br><br><br><p><img src='../images/ajax-loader.gif'>&nbsp;&nbsp;&nbsp;&nbsp;Please wait. Saving your address...</p></center>");
	$( "#physical_search_div" ).removeAttr('style');
		

	$.get("../source/gnafGet.php?type=manual", { l_pid: l_pid.val(), building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data) {
		$( "#physical" ).val(data);
		$.get("../source/gnafGet.php?type=display", { id: data }, function(data2) {
			var n = data2.split("}");
			$( "#display_physical1" ).val(n[0]);
			$( "#display_physical2" ).val(n[1]);
			$( "#display_physical3" ).val(n[2]);
			$( "#display_physical4" ).val(n[3]);
			$( "#dialog-confirm_physical2" ).dialog( "close" );
			$( "#dialog-confirm_physical" ).dialog( "close" );
		});

	});
}

function Physical()
{
	$( "#dialog-confirm_physical" ).dialog( "open" );
}
</script>
<!--#########################################################-->
<!--##													   ##-->
<!--##					POSTAL ADDRESS					   ##-->
<!--##													   ##-->
<!--#########################################################-->
<script>
$(function() {
	$( "#dialog:ui-dialog_postal_confirm_switch" ).dialog( "destroy" );
	
	$( "#dialog-confirm_postal4" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width: 275,
		height: 100,
		modal: true,
		show: "blind",
	});
});

function MailBox()
{
	$( "#dialog-confirm_postal2" ).dialog( "open" );
	$( "#dialog-confirm_postal4" ).dialog( "close" );
}

function MailAddress()
{
	$( "#dialog-confirm_postal" ).dialog( "open" );
	$( "#dialog-confirm_postal4" ).dialog( "close" );
}

function Postal()
{
	$( "#dialog-confirm_postal4" ).dialog( "open" );
}
</script>
<script>
$(function() {
	$( "#dialog:ui-dialog_postal_confirm" ).dialog( "destroy" );

	$( "#dialog-confirm_postal3" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width: 400,
		height: 235,
		modal: true,
		buttons: {
			"Select": function() {
				if ($( "input[name=address_code]:checked" ).val() != undefined)
				{
					$.get("../source/gnafGet.php?type=display", { id: $( "input[name=address_code]:checked" ).val() },
						function(data) {
							var n = data.split("}");
							$( "#display_postal1" ).val(n[0]);
							$( "#display_postal2" ).val(n[1]);
							$( "#display_postal3" ).val(n[2]);
							$( "#display_postal4" ).val(n[3]);
						});
					$( "#postal" ).val($( "input[name=address_code]:checked" ).val());
					$( "#dialog-confirm_postal3" ).dialog( "close" );
					$( "#dialog-confirm_postal2" ).dialog( "close" );
					$( "#dialog-confirm_postal" ).dialog( "close" );
				}
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
</script>
<script> //Mail Box Address
$(function() {
	$( "#dialog:.ui-dialog_postal_mailbox" ).dialog( "destroy" );
	
	var tips = $( ".validateTipsMB" );
	
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#dialog-confirm_postal2" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width: 475,
		height:350,
		modal: true,
		show: "blind",
		hide: "blind",
		buttons: {
			"Verify Address": function() {
				var address_type = "MB",
					building_type = $( "#mb_building_type" ),
					building_number = $( "#mb_building_number" ),
					suburb = $( "#mb_suburb" ),
					state = $( "#mb_state" ),
					postcode = $( "#mb_postcode" );
				
				$.get("../source/gnafGet.php?type=check2", { address_type: address_type, building_type: building_type.val(), building_number: building_number.val(), suburb: suburb.val(), state: state.val(), postcode: postcode.val() }, function(data) {
					if (data == "valid")
					{
						$( "#postal_address_code" ).attr("style","display:none;");
						$( "#postal_manual_store" ).attr('style','display:none;');
						$( "#postal_search_div" ).html("<center><br><br><br><br><p><img src='../images/ajax-loader.gif'>&nbsp;&nbsp;&nbsp;&nbsp;Please wait. Saving your address...</p></center>");
						$( "#postal_search_div" ).removeAttr('style');
						$( "#dialog-confirm_postal3" ).dialog( "open" );
						
						$.get("../source/gnafGet.php?type=mailbox", { building_type: building_type.val(), building_number: building_number.val(), suburb: suburb.val(), state: state.val(), postcode: postcode.val() }, function(data2) {
							$( "#postal" ).val(data2);
							$.get("../source/gnafGet.php?type=display", { id: data2 }, function(data3) {
								var n = data3.split("}");
								$( "#display_postal1" ).val(n[0]);
								$( "#display_postal2" ).val(n[1]);
								$( "#display_postal3" ).val(n[2]);
								$( "#display_postal4" ).val(n[3]);
								$( "#dialog-confirm_postal3" ).dialog( "close" );
								$( "#dialog-confirm_postal2" ).dialog( "close" );
							});
						});
					}
					else
					{
						updateTips(data);
					}
				});
			},
			"Reset": function() {
				$( "#mb_input" ).val("");
				$( "#mb_input2" ).val("");
				$( "#mb_building_type_tr" ).attr("style","display:none;");
				$( "#mb_building_number_tr" ).attr("style","display:none;");
				$( "#mb_suburb_tr" ).attr("style","display:none;");
				$( "#mb_state_tr" ).attr("style","display:none;");
				$( "#mb_postcode_tr" ).attr("style","display:none;");
				$( "#mb_input_tr" ).removeAttr("style");
				$( "#mb_building_type" ).val("");
				$( "#mb_building_number" ).val("");
				$( "#mb_suburb" ).val("");
				$( "#mb_state" ).val("");
				$( "#mb_postcode" ).val("");
			}
		}
	});
});

$(function() {
	$( "#mb_input" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "../source/gnafGet.php",
				dataType: "json",
				data: {
					type : "input3",
					term : request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 3,
		select: function (event, ui) {
			var data2 = ui.item.id.split(",");
			$( "#mb_suburb" ).val(data2[0]);
			$( "#mb_state" ).val(data2[1]);
			$( "#mb_postcode" ).val(data2[2]);
			$( "#mb_input_tr" ).attr("style","display:none;");
			$( "#mb_suburb_tr" ).removeAttr("style");
			$( "#mb_state_tr" ).removeAttr("style");
			$( "#mb_postcode_tr" ).removeAttr("style");
			$( "#mb_building_type_tr" ).removeAttr("style");
			$( "#mb_building_number_tr" ).removeAttr("style");
		}
	});
});

$(function() {
	$( "#mb_input2" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "../source/gnafGet.php",
				dataType: "json",
				data: {
					type : "input4",
					term : request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 2,
		select: function (event, ui) {
			var data2 = ui.item.id.split(",");
			$( "#mb_suburb" ).val(data2[0]);
			$( "#mb_state" ).val(data2[1]);
			$( "#mb_postcode" ).val(data2[2]);
			$( "#mb_input_tr" ).attr("style","display:none;");
			$( "#mb_suburb_tr" ).removeAttr("style");
			$( "#mb_state_tr" ).removeAttr("style");
			$( "#mb_postcode_tr" ).removeAttr("style");
			$( "#mb_building_type_tr" ).removeAttr("style");
			$( "#mb_building_number_tr" ).removeAttr("style");
		}
	});
});
</script>
<script> // Postal Fixed Address
$(function() {
	$( "#dialog:.ui-dialog_postal" ).dialog( "destroy" );
	
	var tips = $( ".validateTipsPostal" );
	
	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	$( "#dialog-confirm_postal" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width: 475,
		height:350,
		modal: true,
		show: "blind",
		hide: "blind",
		buttons: {
			"Verify Address": function() {
				var address_type = $('input[name=postal_type]:checked'),
					building_type = $( "#postal_building_type" ),
					building_number = $( "#postal_building_number" ),
					building_name = $( "#postal_building_name" ),
					street_number = $( "#postal_street_number" ),
					street_name = $( "#postal_street_name" ),
					street_type = $( "#postal_street_type" ),
					l_pid = $('#postal_locality_pid');
					
					$.get("../source/gnafGet.php?type=check", { address_type: address_type.val(), l_pid: l_pid.val(), building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data) {
						if (data == "valid")
						{
							$( "#postal_address_code" ).attr("style","display:none;");
							$( "#postal_manual_store" ).attr('style','display:none;');
							$( "#dialog-confirm_postal3" ).dialog( "open" );
							$( "#postal_search_div" ).html("<center><br><br><br><br><p><img src='../images/ajax-loader.gif'>&nbsp;&nbsp;&nbsp;&nbsp;Please wait. Verifying your address...</p></center>");
							$( "#postal_search_div" ).removeAttr('style');
							
							$.get("../source/gnafGet.php?type=search", { address_type: address_type.val(), l_pid: l_pid.val(), building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data) {
								if (data == 'No Results Found')
								{
									$( "#postal_search_div" ).html("<center><br><br><br><br><p><img src='../images/ajax-loader.gif'>&nbsp;&nbsp;&nbsp;&nbsp;Exact match not found. Looking up similar...</p></center>");
									
									$.get("../source/gnafGet.php?type=format", { l_pid: l_pid.val(), building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data0) {
										/// CLOUD GEOCODER API GET
										$.get("../source/gnafGet.php?type=test", { input: data0 }, function(data2) {
											$.get("../source/gnafGet.php?type=search2", { address_type: address_type.val(), a_pid: data2, building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data3) {
												$( "#postal_address_code" ).html(data3);
												$( "#postal_search_div" ).attr("style","display:none;");
												$( "#postal_address_code" ).removeAttr('style');
												$( "#postal_manual_store" ).removeAttr('style');
											});
										});
										///
									});
								}
								else
								{
									$( "#postal_address_code" ).html(data);
									$( "#postal_search_div" ).attr("style","display:none;");
									$( "#postal_address_code" ).removeAttr('style');
									$( "#postal_manual_store" ).removeAttr('style');
								}
							});
						}
						else
						{
							updateTips(data);
						}
					});
			},
			"Reset": function() {
				$( "#postal_input" ).val("");
				$( "#postal_input2" ).val("");
				$( "#postal_building_type_tr" ).attr("style","display:none;");
				$( "#postal_building_number_tr" ).attr("style","display:none;");
				$( "#postal_building_name_tr" ).attr("style","display:none;");
				$( "#postal_street_number_tr" ).attr("style","display:none;");
				$( "#postal_street_tr" ).attr("style","display:none;");
				$( "#postal_suburb_tr" ).attr("style","display:none;");
				$( "#postal_state_tr" ).attr("style","display:none;");
				$( "#postal_postcode_tr" ).attr("style","display:none;");
				$( "#postal_input_tr" ).attr("style","display:none;");
				$( "#postal_type_tr" ).attr("style","display:none;");
				$( "#postal_input_tr" ).removeAttr("style");
				$( "#postal_building_type option" ).remove();
				$( "#postal_building_number" ).val("");
				$( "#postal_building_name" ).val("");
				$( "#postal_street_number" ).val("");
				$( "#postal_street_name" ).val("");
				$( "#postal_street_type" ).val("");
				$( "#postal_suburb" ).val("");
				$( "#postal_state" ).val("");
				$( "#postal_postcode" ).val("");
				$('input[name=postal_type]:checked').removeAttr("checked");
			}
		}
	});
});

$(function() {
	$( "#postal_input" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "../source/gnafGet.php",
				dataType: "json",
				data: {
					type : "input",
					term : request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 3,
		select: function (event, ui) {
			$( "#postal_locality_pid" ).val(ui.item.id);
			$.get("../source/gnafGet.php?type=input_check", { l_pid: ui.item.id }, function(data) {
				var data2 = data.split(",");
				
				$( "#postal_suburb" ).val(data2[0]);
				$( "#postal_state" ).val(data2[1]);
				$( "#postal_postcode" ).val(data2[2]);
				$( "#postal_input_tr" ).attr("style","display:none;");
				$( "#postal_type_tr" ).removeAttr("style");
			});
		}
	});
});

$(function() {
	$( "#postal_input2" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "../source/gnafGet.php",
				dataType: "json",
				data: {
					type : "input2",
					term : request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 2,
		select: function (event, ui) {
			$( "#postal_locality_pid" ).val(ui.item.id);
			$.get("../source/gnafGet.php?type=input_check", { l_pid: ui.item.id }, function(data) {
				var data2 = data.split(",");
				
				$( "#postal_suburb" ).val(data2[0]);
				$( "#postal_state" ).val(data2[1]);
				$( "#postal_postcode" ).val(data2[2]);
				$( "#postal_input_tr" ).attr("style","display:none;");
				$( "#postal_type_tr" ).removeAttr("style");
			});
		}
	});
});

function FS_Postal()
{
	$( "#postal_building_type option" ).remove();
	$( "#postal_street_number_tr" ).removeAttr("style");
	$( "#postal_street_tr" ).removeAttr("style");
	$( "#postal_suburb_tr" ).removeAttr("style");
	$( "#postal_state_tr" ).removeAttr("style");
	$( "#postal_postcode_tr" ).removeAttr("style");
	$( "#postal_building_type_tr" ).attr("style","display:none;");
	$( "#postal_building_number_tr" ).attr("style","display:none;");
	$( "#postal_building_name_tr" ).attr("style","display:none;");
}

function OB_Postal()
{
	$( "#postal_building_type option" ).remove();
	$( "#postal_building_type" ).append(new Option('LEVEL', 'LEVEL', true, true));
	$( "#postal_building_number_span" ).html("Level Number ");
	$( "#postal_building_number_tr" ).removeAttr("style");
	$( "#postal_street_number_tr" ).removeAttr("style");
	$( "#postal_street_tr" ).removeAttr("style");
	$( "#postal_suburb_tr" ).removeAttr("style");
	$( "#postal_state_tr" ).removeAttr("style");
	$( "#postal_postcode_tr" ).removeAttr("style");
	$( "#postal_building_type_tr" ).attr("style","display:none;");
	$( "#postal_building_name_tr" ).attr("style","display:none;");
}

function BU_Postal()
{
	var newOptions = {
		'' : '',
		'APARTMENT' : 'APARTMENT',
		'DUPLEX' : 'DUPLEX',
		'FACTORY' : 'FACTORY',
		'FLAT' : 'FLAT',
		'HALL' : 'HALL',
		'OFFICE' : 'OFFICE',
		'PENTHOUSE' : 'PENTHOUSE',
		'ROOM' : 'ROOM',
		'SECTION' : 'SECTION',
		'SHOP' : 'SHOP',
		'SITE' : 'SITE',
		'STORE' : 'STORE',
		'STUDIO' : 'STUDIO',
		'SUITE' : 'SUITE',
		'TOWNHOUSE' : 'TOWNHOUSE',
		'UNIT' : 'UNIT',
		'VILLA' : 'VILLA'
	};
	var selectedOption = '';
	
	var select = $('#postal_building_type');
	if(select.prop) {
	  var options = select.prop('options');
	}
	else {
	  var options = select.attr('options');
	}
	$( "#postal_building_type option" ).remove();
	
	$.each(newOptions, function(val, text) {
		options[options.length] = new Option(text, val);
	});
	select.val(selectedOption);
	$( "#postal_building_type_tr" ).removeAttr("style");
	$( "#postal_building_number_span" ).html("Building Number ");
	$( "#postal_building_number_tr" ).removeAttr("style");
	$( "#postal_street_number_tr" ).removeAttr("style");
	$( "#postal_street_tr" ).removeAttr("style");
	$( "#postal_suburb_tr" ).removeAttr("style");
	$( "#postal_state_tr" ).removeAttr("style");
	$( "#postal_postcode_tr" ).removeAttr("style");
	$( "#postal_building_name_tr" ).attr("style","display:none;");
}

function LOT_Postal()
{
	$( "#postal_building_type option" ).remove();
	$( "#postal_building_type" ).append(new Option('LOT', 'LOT', true, true));
	$( "#postal_building_number_span" ).html("Lot Number ");
	$( "#postal_building_number_tr" ).removeAttr("style");
	$( "#postal_street_tr" ).removeAttr("style");
	$( "#postal_suburb_tr" ).removeAttr("style");
	$( "#postal_state_tr" ).removeAttr("style");
	$( "#postal_postcode_tr" ).removeAttr("style");
	$( "#postal_street_number_tr" ).attr("style","display:none;");
	$( "#postal_building_type_tr" ).attr("style","display:none;");
	$( "#postal_building_name_tr" ).attr("style","display:none;");
}

function OTH_Postal()
{
	var newOptions = {
		'' : '',
		'APARTMENT' : 'APARTMENT',
		'BLOCK' : 'BLOCK',
		'BUILDING' : 'BUILDING',
		'DUPLEX' : 'DUPLEX',
		'FACTORY' : 'FACTORY',
		'FLAT' : 'FLAT',
		'HALL' : 'HALL',
		'LOT' : 'LOT',
		'OFFICE' : 'OFFICE',
		'PENTHOUSE' : 'PENTHOUSE',
		'ROOM' : 'ROOM',
		'SECTION' : 'SECTION',
		'SHOP' : 'SHOP',
		'SITE' : 'SITE',
		'STORE' : 'STORE',
		'STUDIO' : 'STUDIO',
		'SUITE' : 'SUITE',
		'TOWNHOUSE' : 'TOWNHOUSE',
		'UNIT' : 'UNIT',
		'VILLA' : 'VILLA'
	};
	var selectedOption = '';
	
	var select = $('#postal_building_type');
	if(select.prop) {
	  var options = select.prop('options');
	}
	else {
	  var options = select.attr('options');
	}
	$( "#postal_building_type option" ).remove();
	
	$.each(newOptions, function(val, text) {
		options[options.length] = new Option(text, val);
	});
	select.val(selectedOption);
	$( "#postal_building_name_tr" ).removeAttr("style");
	$( "#postal_building_type_tr" ).removeAttr("style");
	$( "#postal_building_number_span" ).html("Building Number ");
	$( "#postal_building_number_tr" ).removeAttr("style");
	$( "#postal_street_number_tr" ).removeAttr("style");
	$( "#postal_street_tr" ).removeAttr("style");
	$( "#postal_suburb_tr" ).removeAttr("style");
	$( "#postal_state_tr" ).removeAttr("style");
	$( "#postal_postcode_tr" ).removeAttr("style");
}

$(function() {
	$( "#postal_street_name" ).autocomplete({
		source: function(request, response) {
        $.ajax({
          url: "../source/gnafGet.php",
               dataType: "json",
          data: {
			  type : "street_name",
			  l_pid : $('#postal_locality_pid').val(),
  			  term : request.term
          },
          success: function(data) {
            response(data);
          }
        });
      },
		minLength: 1
	});
});

$(function() {
	$( "#postal_street_type" ).autocomplete({
		source: function(request, response) {
        $.ajax({
          url: "../source/gnafGet.php",
               dataType: "json",
          data: {
			  type : "street_type",
			  l_pid : $('#postal_locality_pid').val(),
			  street_name : $( "#postal_street_name" ).val(),
  			  term : request.term
          },
          success: function(data) {
            response(data);
          }
        });
      },
		minLength: 1
	});
});

function Manual_Postal()
{
	var building_type = $( "#postal_building_type" ),
		building_number = $( "#postal_building_number" ),
		building_name = $( "#postal_building_name" ),
		street_number = $( "#postal_street_number" ),
		street_name = $( "#postal_street_name" ),
		street_type = $( "#postal_street_type" ),
		l_pid = $('#postal_locality_pid');
	
	$( "#postal_address_code" ).attr("style","display:none;");
	$( "#postal_manual_store" ).attr('style','display:none;');
	$( "#postal_search_div" ).html("<center><br><br><br><br><p><img src='../images/ajax-loader.gif'>&nbsp;&nbsp;&nbsp;&nbsp;Please wait. Saving your address...</p></center>");
	$( "#postal_search_div" ).removeAttr('style');
		

	$.get("../source/gnafGet.php?type=manual", { l_pid: l_pid.val(), building_type: building_type.val(), building_number: building_number.val(), building_name: building_name.val(), street_number: street_number.val(), street_name: street_name.val(), street_type: street_type.val() }, function(data) {
		$( "#postal" ).val(data);
		$.get("../source/gnafGet.php?type=display", { id: data }, function(data2) {
			var n = data2.split("}");
			$( "#display_postal1" ).val(n[0]);
			$( "#display_postal2" ).val(n[1]);
			$( "#display_postal3" ).val(n[2]);
			$( "#display_postal4" ).val(n[3]);
			$( "#dialog-confirm_postal3" ).dialog( "close" );
			$( "#dialog-confirm_postal" ).dialog( "close" );
		});
	});
}
</script>
<script>
function Postal_Same()
{
	if ($('#postal_same').attr('checked'))
	{
		$( "#display_postal1" ).val("SAME AS ABOVE");
		$( "#display_postal2" ).val("");
		$( "#display_postal3" ).val("");
		$( "#display_postal4" ).val("");
		$( "#display_postal1" ).attr("disabled","disabled");
		$( "#display_postal2" ).attr("disabled","disabled");
		$( "#display_postal3" ).attr("disabled","disabled");
		$( "#display_postal4" ).attr("disabled","disabled");
		$( "#postal_link" ).removeAttr("onclick");
		$( "#postal_link" ).removeAttr("style");
	}
	else
	{
		$( "#display_postal1" ).val("");
		$( "#display_postal2" ).val("");
		$( "#display_postal3" ).val("");
		$( "#display_postal4" ).val("");
		$( "#display_postal1" ).removeAttr("disabled");
		$( "#display_postal2" ).removeAttr("disabled");
		$( "#display_postal3" ).removeAttr("disabled");
		$( "#display_postal4" ).removeAttr("disabled");
		$( "#postal" ).val("");
		$( "#postal_link" ).attr("onclick", "Postal()");
		$( "#postal_link" ).attr("style", "cursor:pointer; text-decoration:underline;");
	}
}
</script>

<div id="dialog-form0" title="Voice File">
<style type="text/css">
.uploadify-button {
	background-image:url('../images/btn.png');
	background-color: transparent;
	border: none;
	text-transform:uppercase;
	padding: 0;
	font-weight: bold;
	font-family: Tahoma,Geneva,sans-serif;
	font-size: 11px;
	text-shadow:none;
}
.uploadify:hover .uploadify-button {
	background-color: transparent;
	background-image:url('../images/btn_hover.png');
}
</style>
<div id="rec">
<table width="100%">
<tr>
<td align="left" valign="top"><input type="file" name="file_upload" id="file_upload" /></td>
<td align="right" valign="top"><button onclick="Check_Rec()" class="btn">Uploaded</button></td>
</tr>
</table>
</div>
<div id="rec2">
</div>
</div>

<div id="dialog-confirm_dd" title="Direct Debit Switch">
<table width="100%" height="55px">
<tr height="100%">
<td valign="middle" align="center"><button onclick="DD_CC()" class="btn">Credit Card</button></td>
<td valign="middle" align="center"><button onclick="DD_Bank()" class="btn">Bank Account</button></td>
</tr>
</table>
</div>

<div id="dialog-confirm_dd_cc" title="Direct Debit - Credit Card">
<p class="validateTips7">All fields are required</p><br />
<table>
<tr>
<td width="85px">Cardholder Name </td>
<td><input type="text" id="cardholder" style="width:150px;" value="" /></td>
</tr>
<tr>
<td width="85px">Card Type </td>
<td><select id="card_type" style="width:151px;">
<option></option>
<option>AMEX</option>
<option>DINERS</option>
<option>MASTERCARD</option>
<option>VISA</option>
</select></td>
</tr>
<tr>
<td width="85px">Card Number </td>
<td><input type="text" id="card_number" style="width:150px;" value="" /></td>
</tr>
<tr>
<td width="85px">Card Expiry Date </td>
<td><input type="text" id="card_expiry_m" style="width:25px;" value="" /> / <input type="text" id="card_expiry_y" style="width:25px;" value="" /></td>
</tr>
</table>
</div>

<div id="dialog-confirm_dd_bank" title="Direct Debit - Bank Account">
<p class="validateTips8">All fields are required</p><br />
<table>
<tr>
<td width="85px">Account Name </td>
<td><input type="text" id="accountname" style="width:150px;" value="" /></td>
</tr>
<tr>
<td width="85px">BSB </td>
<td><input type="text" id="bsb" style="width:75px;" value="" /></td>
</tr>
<tr>
<td width="85px">Account Number </td>
<td><input type="text" id="accountnumber" style="width:150px;" value="" /></td>
</tr>
</table>
</div>

<div id="dialog-form" title="Call Type">
<table width="100%" height="55px">
<tr height="100%">
<td valign="middle" align="center"><button onclick="Business_Customers()" class="btn">Business</button></td>
<td valign="middle" align="center"><button onclick="Residential_Customers()" class="btn">Residential</button></td>
</tr>
</table>
</div>

<div id="dialog-confirm_na" title="N/A Switch">
<table width="100%" height="55px">
<tr height="100%">
<td valign="middle" align="center"><button onclick="No_Answer()" class="btn">No Answer</button></td>
<td valign="middle" align="center"><button onclick="Call_Back()" class="btn">Call Back</button></td>
</tr>
</table>
</div>

<div id="dialog-confirm_call_back" title="Call Back">
<p class="validateTips5">All fields are required</p>
<table>
<tr>
<td width="85px">Call Back Date </td>
<td><input type="text" id="cb_datepicker2" readonly style="width:80px;" value="<?php echo date("d/m/Y"); ?>" /> <input type="hidden" id="cb_datepicker" value="<?php echo date("Y-m-d"); ?>" /></td>
</tr>
<tr>
<td width="85px">Call Back Time </td>
<td><select id="cb_time_h" style="width:40px;"><option></option><option>01</option><option>02</option><option>03</option><option>04</option><option>05</option><option>06</option><option>07</option><option>08</option><option>09</option><option>10</option><option>11</option><option>12</option></select> : <select id="cb_time_m" style="width:40px;"><option></option><option>00</option><option>15</option><option>30</option><option>45</option></select> <select id="cb_time_p" style="width:40px;"><option></option><option>AM</option><option>PM</option></select></td>
</tr>
</table>
</div>

<div id="dialog-confirm_complete" title="Complete Switch">
<table width="100%" height="55px">
<tr height="100%">
<td valign="middle" align="center"><button onclick="Approve()" class="btn">Approve</button></td>
<td valign="middle" align="center"><button onclick="Reject()" class="btn_red">Cancel</button></td>
</tr>
</table>
</div>

<div id="dialog-confirm_reject" title="Cancel Account">
<p class="validateTips6">All fields are required</p>
<table>
<tr>
<td width="50px">Reason </td>
<td><select id="reject_reason" style="width:100px;" />
<option></option>
<option>Contract</option>
<option>Fraud</option>
<option>Rates</option>
<option>Telstra</option>
<option>Other</option>
</select></td>
</tr>
<tr>
<td>Notes </td>
<td><textarea id="reject_notes" style="width: 210px; height: 75px; resize: none;"></textarea>
</td>
</tr>
</table>
</div>

<div id="dialog-confirm_upgrade" title="Upgrade">
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="100%" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th width="20%">CLI</th>
<th width="75%">Plan</th>
<th width="5%">Edit</th>
</tr>
</thead>
<tbody id="upgrade_packages">
</tbody>
</table>
</div>
<button onclick="Add_Package()" class="btn">Add Package</button>
</div>

<div id="dialog-form2" title="Add a Package">
<p class="validateTips2">All fields are required</p><br />
<table>
<tr>
<td width="50px">CLI </td>
<td><input type="text" size="15" id="cli" onchange="Plan_Dropdown()" style="margin-top:0px;" /></td>
</tr>
<td>Plan </td>
<td><select id="plan" style="width:210px;">
<option></option>
</select></td>
</tr>
</table>
</div>

<div id="dialog-form3" title="Edit Package">
<p class="validateTips3">All fields are required</p><br />
<table>
<tr>
<td width="50px">CLI </td>
<td><input type="text" size="15" id="edit_cli" disabled="disabled" style="margin-top:0px;" /></td>
</tr>
<tr>
<td>Plan </td>
<td><select id="edit_plan" style="margin-left:0px; width:210px; padding:1px 0 0;">
<option></option>
</select></td>
</tr>
</table>
</div>

<div id="dialog-form4" title="Error">
<span class='ui-icon ui-icon-alert' style='float:left; margin-right:.3em; margin-top:4px'></span><p class="validateTips4"></p>
</div>

<div id="dialog-confirm_physical2" title="Verifying Physical Address">
<table width="100%">
<tr>
<td>
<div id="physical_search_div">
</div>
<div id="physical_address_code" style="display:none;">
</div>
</td>
</tr>
<tr>
<td><div id="physical_manual_store" style="display:none;">
<a onclick="Manual_Physical()" style="cursor:pointer; text-decoration:underline;">Address not found? Click here to store it manually</a>
</div></td>
</tr>
</table>
</div>

<div id="dialog-confirm_physical" title="Physical Address">
<p class="validateTipsPhysical">Enter the customer's suburb or postcode below to begin searching the GNAF dataset.</p><br />
<input type="hidden" id="physical_locality_pid" value="" />
<table width="100%">
<tr id="physical_input_tr">
<td width="200px" align="center">Suburb<br /><input type="text" id="physical_input" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
<td align="center">OR</td>
<td width="200px" align="center">Postcode<br /><input type="text" id="physical_input2" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
</tr>
</table>
<center><table>
<tr id="physical_type_tr" style="display:none;">
<td><input type="radio" name="physical_type" value="FS" onclick="FS_Physical()" style="height:auto; margin:0 3px;" />Freestanding Premises</td>
<td><input type="radio" name="physical_type" value="OB" onclick="OB_Physical()" style="height:auto; margin:0 3px;" />Office Building</td>
<td><input type="radio" name="physical_type" value="BU" onclick="BU_Physical()" style="height:auto; margin:0 3px;" />Flat, Unit or Apartment</td>
<td><input type="radio" name="physical_type" value="LOT" onclick="LOT_Physical()" style="height:auto; margin:0 3px;" />Lot</td>
<td><input type="radio" name="physical_type" value="OTH" onclick="OTH_Physical()" style="height:auto; margin:0 3px;" />Other</td>
</tr>
</table></center>
<table width="100%" style="margin-top:10px;">
<tr id="physical_building_type_tr" style="display:none;">
<td width="80px">Building Type</td>
<td><select id="physical_building_type" style="width:95px; height:auto; padding:0px; margin:0px;"></select></td>
</tr>
<tr id="physical_building_number_tr" style="display:none;">
<td width="80px"><span id="physical_building_number_span">Building Number </span></td>
<td><input type="text" id="physical_building_number" value="" style="width:50px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="physical_building_name_tr" style="display:none;">
<td width="80px">Building Name </td>
<td><input type="text" id="physical_building_name" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="physical_street_number_tr" style="display:none;">
<td width="80px">Street Number </td>
<td><input type="text" id="physical_street_number" value="" style="width:50px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="physical_street_tr" style="display:none;">
<td width="80px">Street</td>
<td><input type="text" id="physical_street_name" value="" style="width:107px; height:auto; padding-left:3px;" /> <input type="text" id="physical_street_type" value="" style="width:75px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="physical_suburb_tr" style="display:none;">
<td width="80px">Suburb </td>
<td><input type="text" id="physical_suburb" disabled="disabled" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="physical_state_tr" style="display:none;">
<td width="80px">State </td>
<td><input type="text" id="physical_state" disabled="disabled" value="" style="width:30px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="physical_postcode_tr" style="display:none;">
<td width="80px">Postcode </td>
<td><input type="text" id="physical_postcode" disabled="disabled" value="" style="width:30px; height:auto; padding-left:3px;" /></td>
</tr>
</table>
</div>

<div id="dialog-confirm_postal4" title="Postal Address Switch">
<table width="100%" height="55px">
<tr height="100%">
<td valign="middle" align="center"><button onclick="MailBox()" class="btn">Mail Box</button></td>
<td valign="middle" align="center"><button onclick="MailAddress()" class="btn">Address</button></td>
</tr>
</table>
</div>

<div id="dialog-confirm_postal3" title="Verifying Postal Address">
<table width="100%">
<tr>
<td>
<div id="postal_search_div">
</div>
<div id="postal_address_code" style="display:none;">
</div>
</td>
</tr>
<tr>
<td><div id="postal_manual_store" style="display:none;">
<a onclick="Manual_Postal()" style="cursor:pointer; text-decoration:underline;">Address not found? Click here to store it manually</a>
</div></td>
</tr>
</table>
</div>

<div id="dialog-confirm_postal2" title="Postal Mail Box">
<p class="validateTipsMB">Enter the customer's suburb or postcode below to begin searching the PAF dataset.</p><br />
<table width="100%">
<tr id="mb_input_tr">
<td width="200px" align="center">Suburb<br /><input type="text" id="mb_input" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
<td align="center">OR</td>
<td width="200px" align="center">Postcode<br /><input type="text" id="mb_input2" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
</tr>
</table>
<table width="100%" style="margin-top:10px;">
<tr id="mb_building_type_tr" style="display:none;">
<td width="80px">Mail Box Type</td>
<td><select id="mb_building_type" style="width:115px; height:auto; padding:0px; margin:0px;">
<option></option>
<option>Care of Post Office</option>
<option>GPO BOX</option>
<option>LOCKED BAG</option>
<option>PO BOX</option>
<option>PRIVATE BAG</option>
<option>RMB</option>
<option>RMD</option>
<option>RSD</option>
</select></td>
</tr>
<tr id="mb_building_number_tr" style="display:none;">
<td width="80px">Mail Box Number </td>
<td><input type="text" id="mb_building_number" value="" style="width:50px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="mb_suburb_tr" style="display:none;">
<td width="80px">Suburb </td>
<td><input type="text" id="mb_suburb" disabled="disabled" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="mb_state_tr" style="display:none;">
<td width="80px">State </td>
<td><input type="text" id="mb_state" disabled="disabled" value="" style="width:30px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="mb_postcode_tr" style="display:none;">
<td width="80px">Postcode </td>
<td><input type="text" id="mb_postcode" disabled="disabled" value="" style="width:30px; height:auto; padding-left:3px;" /></td>
</tr>
</table>
</div>

<div id="dialog-confirm_postal" title="Postal Address">
<p class="validateTipsPostal">Enter the customer's suburb or postcode below to begin searching the GNAF dataset.</p><br />
<input type="hidden" id="postal_locality_pid" value="" />
<table width="100%">
<tr id="postal_input_tr">
<td width="200px" align="center">Suburb<br /><input type="text" id="postal_input" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
<td align="center">OR</td>
<td width="200px" align="center">Postcode<br /><input type="text" id="postal_input2" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
</tr>
</table>
<center><table>
<tr id="postal_type_tr" style="display:none;">
<td><input type="radio" name="postal_type" value="FS" onclick="FS_Postal()" style="height:auto; margin:0 3px;" />Freestanding Premises</td>
<td><input type="radio" name="postal_type" value="OB" onclick="OB_Postal()" style="height:auto; margin:0 3px;" />Office Building</td>
<td><input type="radio" name="postal_type" value="BU" onclick="BU_Postal()" style="height:auto; margin:0 3px;" />Flat, Unit or Apartment</td>
<td><input type="radio" name="postal_type" value="LOT" onclick="LOT_Postal()" style="height:auto; margin:0 3px;" />Lot</td>
<td><input type="radio" name="postal_type" value="OTH" onclick="OTH_Postal()" style="height:auto; margin:0 3px;" />Other</td>
</tr>
</table></center>
<table width="100%" style="margin-top:10px;">
<tr id="postal_building_type_tr" style="display:none;">
<td width="80px">Building Type</td>
<td><select id="postal_building_type" style="width:95px; height:auto; padding:0px; margin:0px;"></select></td>
</tr>
<tr id="postal_building_number_tr" style="display:none;">
<td width="80px"><span id="postal_building_number_span">Building Number </span></td>
<td><input type="text" id="postal_building_number" value="" style="width:50px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="postal_building_name_tr" style="display:none;">
<td width="80px">Building Name </td>
<td><input type="text" id="postal_building_name" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="postal_street_number_tr" style="display:none;">
<td width="80px">Street Number </td>
<td><input type="text" id="postal_street_number" value="" style="width:50px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="postal_street_tr" style="display:none;">
<td width="80px">Street</td>
<td><input type="text" id="postal_street_name" value="" style="width:107px; height:auto; padding-left:3px;" /> <input type="text" id="postal_street_type" value="" style="width:75px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="postal_suburb_tr" style="display:none;">
<td width="80px">Suburb </td>
<td><input type="text" id="postal_suburb" disabled="disabled" value="" style="width:190px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="postal_state_tr" style="display:none;">
<td width="80px">State </td>
<td><input type="text" id="postal_state" disabled="disabled" value="" style="width:30px; height:auto; padding-left:3px;" /></td>
</tr>
<tr id="postal_postcode_tr" style="display:none;">
<td width="80px">Postcode </td>
<td><input type="text" id="postal_postcode" disabled="disabled" value="" style="width:30px; height:auto; padding-left:3px;" /></td>
</tr>
</table>
</div>

<input type="hidden" id="customer_type_store" value="" />

<div id="display">
</div>

<?php
include "../source/footer.php";
?>