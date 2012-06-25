<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>

<style>
.edit {
	background-image:url('../images/edit_icon.png');
	background-repeat:no-repeat;
	height:16px;
	width:16px;
	border:none;
	background-color:transparent;
	cursor:pointer;
}

.search
{
	background-image:url('../images/search_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
	margin-right:10px;
}

.search:hover
{
	background-image:url('../images/search_btn_hover.png');
	cursor:pointer;
}

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
	$( "#display" ).load("designation_display.php?page=" + page + "&user=<?php echo $ac["user"] ?>");
}
</script>
<script> //edit
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var user = $( "#user" ),
		designation = $( "#designation" ),
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
		buttons: {
			"Submit": function() {
				$.get("designation_submit.php?method=edit", { user: user.val(), designation: designation.val() },
				function(data) {
					if (data == "submitted")
					{
						$( "#dialog-form" ).dialog( "close" );
						var page_link = $( "#page_link" );
						$( "#display" ).load("designation_display.php" + page_link.val());
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

function Edit(user,name,designation)
{
	$( "#user" ).val(user);
	$( "#name" ).val(name);
	$( "#designation" ).val(designation);
	$( "#dialog-form" ).dialog( "open" );
}
</script>
<?php
$q = mysql_query("SELECT centres FROM operations WHERE user = '$ac[user]'") or die(mysql_error());
$cen = mysql_fetch_row($q);
$centres = explode(",",$cen[0]);
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
		height:160,
		modal: true,
		buttons: {
			"Open": function() {				
				$.get("designation_submit.php?method=check", { agent: agent.val() },
				function(data) {
					if (data == "valid")
					{
						$( "#dialog-form2" ).dialog( "close" );
						$( "#display" ).load("designation_display.php?query=" + agent.val());
					}
					else
					{
						updateTips(data);
					}
				});
			},
			
			"Close": function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

$(function() {
	$( "#search_box" ).autocomplete({
		source: function(request, response) {
			$.ajax({
				url: "designation_submit.php",
				dataType: "json",
				data: {
					method: "search",
					centres : "<?php echo str_replace(",", "_", $cen[0]); ?>",
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

<div id="dialog-form" title="Agent Designation">
<p class="validateTips"><span style="color:#ff0000;">*</span> Required Fields</p>
<input type="hidden" id="user" value="">
<table>
<tr>
<td width='80px'>Agent Name </td>
<td><input type="text" id="name" disabled="disabled" size="20" style='padding:0px; margin:0px;'></td>
</tr>
<tr>
<td width='80px'>Designation<span style="color:#ff0000;">*</span> </td>
<td><select id="designation" style="margin:0px; padding:4px 4px 4px 0px; width:135px; height:28px;">
<option></option>
<option>Team Leader</option>
<option>Closer</option>
<option>Agent</option>
<option>Probation</option>
</select></td>
</tr>
</table>
</div>

<div id="dialog-form2" title="Search">
<p class="validateTips2">Please Type the Agent's Name Below</p><br />
Agent: <input type="text" id="search_box" size="25" />
<input type="hidden" id="search_agent" value="" />
</div>

<table width="100%">
<tr>
<td align="left" valign="bottom"><img src="../images/manage_designations_header.png" width="215" height="25" style="margin-left:6px;" /></td>
<td align="right" style="padding-right:10px;"><input type="button" onClick="Search()" class="search"></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="740" height="9" /></td>
</tr>
</table>

<div id="display">
<script>
$( "#display" ).load("designation_display.php?page=0&user=<?php echo $ac["user"] ?>");
</script>
</div>

<?php
include "../source/footer.php";
?>