<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
.ui-dialog .ui-dialog2 .ui-state-highlight { padding: .3em; }
.ui-dialog3 { padding: .3em; }
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
		type = $( "#rate_type" ),
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
		height: 200,
		width: 275,
		modal: true,
		resizable: false,
		draggable: false,
		show: 'blind',
		hide: 'blind',
		buttons: {
			"Submit": function() {
				$.get("rate_submit.php?method=edit", { user: user.val(), type: type.val(), rate: rate.val() },
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
		}
	});
});

function Edit(user,rate)
{
	$( "#user" ).val(user);
	$.get("rate_submit.php?method=name", { user: user }, function(data) { $( "#name" ).val(data) });
	$.get("rate_submit.php?method=rate_type", { user: user }, function(data) {
		$( "#rate_type" ).val(data);
		if (data == "F")
		{
			$( "#rate_tr" ).removeAttr("style");
			$( "#rate" ).val(rate);
		}
		else if (data == "T")
		{
			$( "#rate_tr" ).attr("style","display:none;");
			$( "#rate" ).val("");
		}
		else
		{
			$( "#rate_tr" ).attr("style","display:none;");
			$( "#rate" ).val("");
		}
	});
	$( "#dialog-form" ).dialog( "open" );
}

function Fixed()
{
	$( "#rate_tr" ).removeAttr("style");
	$( "#rate" ).val("");
}

function Tiered()
{
	$( "#rate_tr" ).attr("style","display:none;");
	$( "#rate" ).val("");
}
</script>
<?php
$q = mysql_query("SELECT centre FROM vericon.centres WHERE type = 'Self' ORDER BY centre ASC") or die(mysql_error());
while($centre = mysql_fetch_row($q))
{
	$centres .= $centre[0] . ",";
}
$centres = substr($centres,0,-1);
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
					centres : "<?php echo str_replace(",", "_", $centres); ?>",
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
<td>Type </td>
<td><select id="rate_type" style="width:75px;">
<option></option>
<option value="F" onclick="Fixed()">Fixed</option>
<option value="T" onclick="Tiered()">Tiered</option>
</select></td>
</tr>
<tr id="rate_tr" style="display:none;">
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