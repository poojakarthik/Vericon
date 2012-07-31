<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
.ui-dialog .ui-dialog2 .ui-state-highlight { padding: .3em; }
.validateTips { border: 1px solid transparent; padding: 0.3em; }
.validateTips2  { border: 1px solid transparent; padding: 0.3em; }
.ui-autocomplete-loading { background: white url('../images/ajax-loader.gif') right center no-repeat; }
</style>
<script>
function Display(page)
{
	$( "#display2" ).load("rate_display2.php?page=" + page + "&user=<?php echo $ac["user"] ?>");
}
</script>
<script> //edit
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var user = $( "#user" ),
		rate = $( "#rate" ),
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
		height: 175,
		width: 275,
		modal: true,
		resizable: false,
		draggable: false,
		show: 'blind',
		hide: 'blind',
		buttons: {
			"Submit": function() {
				$.get("rate_submit.php?method=edit", { user: user.val(), rate: rate.val() },
				function(data) {
					if (data == "submitted")
					{
						$( "#dialog-form" ).dialog( "close" );
						var page_link = $( "#page_link" );
						$( "#display2" ).load("rate_display2.php" + page_link.val());
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
		},
		close: function() {
		}
	});
});

function Edit(user,rate)
{
	$( "#user" ).val(user);
	$.get("rate_submit.php?method=name", { user: user }, function(data) { $( "#name" ).val(data) });
	$( "#rate" ).val(rate);
	$( "#dialog-form" ).dialog( "open" );
}
</script>
<?php
$q = mysql_query("SELECT centres FROM vericon.operations WHERE user = '$ac[user]'") or die(mysql_error());
$cen = mysql_fetch_row($q);
if ($cen[0] == "All")
{
	$centres = array();
	$q1 = mysql_query("SELECT centre FROM vericon.centres WHERE status = 'Enabled' ORDER BY centre ASC") or die(mysql_error());
	while ($centre = mysql_fetch_row($q1))
	{
		array_push($centres, $centre[0]);
	}
	$centre = implode(",", $centres);
}
elseif ($cen[0] == "Captive" || $cen[0] == "Self")
{
	$centres = array();
	$q1 = mysql_query("SELECT centre FROM vericon.centres WHERE status = 'Enabled' AND type = '$cen[0]' ORDER BY centre ASC") or die(mysql_error());
	while ($centre = mysql_fetch_row($q1))
	{
		array_push($centres, $centre[0]);
	}
	$centre = implode(",", $centres);
}
else
{
	$centres = explode(",",$cen[0]);
	$centre = implode(",", $centres);
}
?>
<script> // search users
$(function() {
	$( "#dialog:ui-dialog2" ).dialog( "destroy" );
	
	var agent = $( "#search_agent" ),
		tips = $( ".validateTips2" );
	
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
		resizable: false,
		draggable: false,
		width:250,
		height:125,
		modal: true,
		show: 'blind',
		hide: 'blind'
	});
});

$(function() {
	$( "#search_box" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "rate_submit.php",
				dataType: "json",
				data: {
					method: "search",
					centres : "<?php echo str_replace(",", "_", $centre); ?>",
					term : request.term
				},
				success: function(data) {
					response(data);
				}
			});
		},
		minLength: 2,
		select: function( event, ui ) {
			$( "#search_agent" ).val(ui.item.id);
			$( "#dialog-form2" ).dialog( "close" );
			$( "#display2" ).load("rate_display2.php?query=" + ui.item.id);
		}
	});
});

function Search()
{
	$( "#search_agent" ).val("");
	$( "#search_box" ).val("");
	$( "#dialog-form2" ).dialog( "open" );
}
</script>

<div id="dialog-form" title="Agent Pay Rate">
<p class="validateTips"><span style="color:#ff0000;">*</span> Required Fields</p>
<input type="hidden" id="user" value="">
<table>
<tr>
<td width='80px'>Agent Name </td>
<td><input type="text" id="name" disabled="disabled" size="20" style='padding:0px; margin:0px;'></td>
</tr>
<tr>
<td width='80px'>Rate ($)<span style="color:#ff0000;">*</span> </td>
<td><input type="text" id="rate" size="20" style='padding:0px; margin:0px;'></td>
</tr>
</table>
</div>

<div id="dialog-form2" title="Search">
<p class="validateTips2">Please Type the Agent's Name Below</p><br />
Agent: <input type="text" id="search_box" size="25" />
<input type="hidden" id="search_agent" value="" />
</div>

<div id="display">
<script>
$( "#display" ).hide();
$( "#display" ).load('rate_display.php',
function() {
	$( "#display2" ).load('rate_display2.php?page=0&user=<?php echo $ac["user"]; ?>',
	function() {
		$( "#display" ).show('blind', '' , 'slow');
	});
});
</script>
</div>

<?php
include "../source/footer.php";
?>