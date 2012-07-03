<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
.ui-dialog .ui-state-highlight { padding: .3em; }
.validateTips { border: 1px solid transparent; padding: 0.3em; }
.validateTips2  { border: 1px solid transparent; padding: 0.3em; }
.validateTips3  { border: 1px solid transparent; padding: 0.3em; }
.validateTips4  { border: 1px solid transparent; padding: 0.3em; }
.ui-autocomplete-loading { background: white url('../images/ajax-loader.gif') right center no-repeat; }
</style>
<script>
function Display(page)
{
	$( "#display2" ).load("user_display2.php?page=" + page + "&user=<?php echo $ac["user"] ?>");
}
</script>
<script> // create user modal
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var password = $( "#password" ),
		password2 = $( "#password2" ),
		first = $( "#first" ),
		last = $( "#last" ),
		centre = $( "#centre" ),
		designation = $( "#designation" ),
		alias = $( "#alias" ),
		allFields = $( [] ).add( password ).add( password2 ).add( first ).add( last ).add( centre ).add( designation ).add( alias ),
		tips = $( ".validateTips" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	function checkLength( o, n, min, max ) {
		if ( o.val().length > max || o.val().length < min ) {
			updateTips( "Length of " + n + " must be between " +
				min + " and " + max + "." );
			return false;
		} else {
			return true;
		}
	}
	
	function checkRegexp( o, regexp, n ) {
		if ( !( regexp.test( o.val() ) ) ) {
			updateTips( n );
			return false;
		} else {
			return true;
		}
	}
	
	$( "#dialog-form" ).dialog({
		autoOpen: false,
		height: 320,
		width: 315,
		modal: true,
		resizable: false,
		draggable: false,
		show: 'blind',
		hide: 'blind',
		buttons: {
			"Submit": function() {
				var bValid = true;
				bValid = bValid && checkLength( first, "First Name", 2, 30 );
				bValid = bValid && checkRegexp( first, /^[a-zA-Z '-]+$/i, "First Name may only contain letters, dashes, apostrophes and spaces." );
				bValid = bValid && checkLength( last, "Last Name", 2, 30 );
				bValid = bValid && checkRegexp( last, /^[a-zA-Z '-]+$/i, "Last Name may only contain letters, dashes, apostrophes and spaces." );
				bValid = bValid && checkLength( password, "Password", 6, 16 );
				
				if ( bValid ) {
					$.get("user_submit.php?method=create", { first: first.val(), last: last.val(), password: password.val(), password2: password2.val(), centre: centre.val(), designation: designation.val(), alias: alias.val() },
					function(data) {
						if (data.substring(0,7) == "created")
						{
							allFields.val( "" );
							tips.html('<span style="color:#ff0000;">*</span> Required Fields');
							$( "#dialog-form" ).dialog( "close" );
							$( ".user-created" ).html(data.substring(7));
							$( "#dialog-confirm" ).dialog( "open" );
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
				allFields.val( "" );
				tips.html('<span style="color:#ff0000;">*</span> Required Fields');
			}
		},
		close: function() {
			allFields.val( "" );
			tips.html('<span style="color:#ff0000;">*</span> Required Fields');
		}
	});
});

function Create()
{
	$( "#dialog-form" ).dialog( "open" );
}
</script>
<script> // user created confirmation
$(function() {
	$( "#dialog:ui-dialog2" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:250,
		height:100,
		modal: true,
		show: 'blind',
		hide: 'blind',
		close: function() {
			var page_link = $( "#page_link" );
			$( "#display2" ).load("user_display2.php" + page_link.val());
		}
	});
});
</script>
<script> // modify user modal
$(function() {
	$( "#dialog:ui-dialog3" ).dialog( "destroy" );
	
	var username = $( "#m_username" ),
		centre = $( "#m_centre" ),
		designation = $( "#m_designation" ),
		alias = $( "#m_alias" ),
		tips = $( ".validateTips2" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	function checkLength( o, n, min, max ) {
		if ( o.val().length > max || o.val().length < min ) {
			updateTips( "Length of " + n + " must be between " +
				min + " and " + max + "." );
			return false;
		} else {
			return true;
		}
	}
	
	$( "#dialog-form2" ).dialog({
		autoOpen: false,
		height: 260,
		width: 315,
		modal: true,
		resizable: false,
		draggable: false,
		show: 'blind',
		hide: 'blind',
		buttons: {
			"Submit": function() {
				$.get("user_submit.php?method=modify", { username: username.val(), centre: centre.val(), designation: designation.val(), alias: alias.val() },
				function(data) {
					if (data == "modified")
					{
						$( "#dialog-form2" ).dialog( "close" );
						tips.html('<span style="color:#ff0000;">*</span> Required Fields');
						var page_link = $( "#page_link" );
						$( "#display2" ).load("user_display2.php" + page_link.val());
					}
					else
					{
						updateTips(data);
					}
				});
			},
			Cancel: function() {
				tips.html('<span style="color:#ff0000;">*</span> Required Fields');
				$( this ).dialog( "close" );
			}
		},
		close: function() {
			tips.html('<span style="color:#ff0000;">*</span> Required Fields');
		}
	});
});

function Modify(user)
{
	$( "#m_username" ).val(user);
	$.get("user_submit.php", { method: "first", user: user }, function(data) { $( "#m_first" ).val(data); });
	$.get("user_submit.php", { method: "last", user: user }, function(data) { $( "#m_last" ).val(data); });
	$.get("user_submit.php", { method: "centre", user: user }, function(data) { $( "#m_centre" ).val(data); });
	$.get("user_submit.php", { method: "designation", user: user }, function(data) { $( "#m_designation" ).val(data); });
	$.get("user_submit.php", { method: "alias", user: user }, function(data) { $( "#m_alias" ).val(data); });
	$( "#dialog-form2" ).dialog( "open" );
}
</script>
<script> // modify password modal
$(function() {
	$( "#dialog:ui-dialog6" ).dialog( "destroy" );
	
	var username = $( "#m_username2" ),
		password = $( "#m_password" ),
		password2 = $( "#m_password2" ),
		tips = $( ".validateTips4" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-highlight" );
		setTimeout(function() {
			tips.removeClass( "ui-state-highlight", 1500 );
		}, 500 );
	}

	function checkLength( o, n, min, max ) {
		if ( o.val().length > max || o.val().length < min ) {
			updateTips( "Length of " + n + " must be between " +
				min + " and " + max + "." );
			return false;
		} else {
			return true;
		}
	}
	
	$( "#dialog-form4" ).dialog({
		autoOpen: false,
		height: 240,
		width: 315,
		modal: true,
		resizable: false,
		draggable: false,
		show: 'blind',
		hide: 'blind',
		buttons: {
			"Submit": function() {
				var bValid = true;
				bValid = bValid && checkLength( password, "Password", 6, 16 );
				
				if (bValid)
				{
					$.get("user_submit.php?method=modify_pw", { username: username.val(), password: password.val(), password2: password2.val() },
					function(data) {
						if (data == "modified")
						{
							$( "#dialog-form4" ).dialog( "close" );
							tips.html('<span style="color:#ff0000;">*</span> Required Fields');
							var page_link = $( "#page_link" );
							$( "#display2" ).load("user_display2.php" + page_link.val());
						}
						else
						{
							updateTips(data);
						}
					});
				}
			},
			Cancel: function() {
				tips.html('<span style="color:#ff0000;">*</span> Required Fields');
				$( this ).dialog( "close" );
			}
		},
		close: function() {
			tips.html('<span style="color:#ff0000;">*</span> Required Fields');
		}
	});
});

function Modify_PW(user)
{
	$( "#m_username2" ).val(user);
	$.get("user_submit.php", { method: "first", user: user }, function(data) { $( "#m_first2" ).val(data); });
	$.get("user_submit.php", { method: "last", user: user }, function(data) { $( "#m_last2" ).val(data); });
	$( "#m_password" ).val("");
	$( "#m_password2" ).val("");
	$( "#dialog-form4" ).dialog( "open" );
}
</script>
<script> // disable confirmation
$(function() {
	$( "#dialog:ui-dialog4" ).dialog( "destroy" );

	$( "#dialog-confirm2" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		height:140,
		modal: true,
		buttons: {
			"Disable User": function() {
				var disable_user = $( "#disable_user" ).val();
				$.get("user_submit.php?method=disable", { username: disable_user }, function (data) {
					$( "#dialog-confirm2" ).dialog( "close" );
					var page_link = $( "#page_link" );
					$( "#display2" ).load("user_display2.php" + page_link.val());
				});
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
function Disable(user)
{
	$( "#disable_user" ).val(user);
	$( "#dialog-confirm2" ).dialog( "open" );
}
</script>
<script> // enable confirmation
$(function() {
	$( "#dialog:ui-dialog5" ).dialog( "destroy" );

	$( "#dialog-confirm3" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		height:140,
		modal: true,
		buttons: {
			"Enable User": function() {
				var enable_user = $( "#enable_user" ).val();
				$.get("user_submit.php?p=users&method=enable", { username: enable_user }, function (data) {
					$( "#dialog-confirm3" ).dialog( "close" );
					var page_link = $( "#page_link" );
					$( "#display2" ).load("user_display2.php" + page_link.val());
				});
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
function Enable(user)
{
	$( "#enable_user" ).val(user);
	$( "#dialog-confirm3" ).dialog( "open" );
}
</script>
<?php
$q = mysql_query("SELECT centres FROM operations WHERE user = '$ac[user]'") or die(mysql_error());
$cen = mysql_fetch_row($q);
$centres = explode(",",$cen[0]);
?>
<script> // search users
$(function() {
	$( "#dialog:ui-dialog6" ).dialog( "destroy" );
	
	var agent = $( "#search_agent" ),
		tips = $( ".validateTips3" );
	
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
				url: "user_submit.php",
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
			$( "#dialog-form3" ).dialog( "close" );
			$( "#display2" ).load("user_display2.php?query=" + ui.item.id);
		}
	});
});

function Search()
{
	$( "#search_agent" ).val("");
	$( "#search_box" ).val("");
	$( "#dialog-form3" ).dialog( "open" );
}
</script>

<div id="dialog-form" title="Create User">
	<p class="validateTips"><span style="color:#ff0000;">*</span> Required Fields</p>
<table>
<tr>
<td width="105px">Username<span style="color:#ff0000;">*</span> </td>
<td><input type="text" style="width:150px;" value="Automatically Generated" disabled="disabled"></td>
</tr>
<tr>
<td>First Name<span style="color:#ff0000;">*</span> </td>
<td><input id="first" type="text" style="width:150px;"></td>
</tr>
<tr>
<td>Last Name<span style="color:#ff0000;">*</span> </td>
<td><input id="last" type="text" style="width:150px;"></td>
</tr>
<tr>
<td>Password<span style="color:#ff0000;">*</span> </td>
<td><input id="password" type="password" style="width:150px;"></td>
</tr>
<tr>
<td>Re-Type Password<span style="color:#ff0000;">*</span> </td>
<td><input id="password2" type="password" style="width:150px;"></td>
</tr>
<tr>
<td>Centre<span style="color:#ff0000;">*</span> </td>
<td><select id="centre" style="width:152px;">
<option></option>
<?php
for ($i = 0; $i < count($centres); $i++)
{
	echo "<option>" . $centres[$i] . "</option>";
}
?>
</select></td>
</tr>
<tr>
<td>Designation<span style="color:#ff0000;">*</span> </td>
<td><select id="designation" style="width:152px;">
<option></option>
<option>Team Leader</option>
<option>Closer</option>
<option>Agent</option>
<option>Probation</option>
</select></td>
</tr>
<tr>
<td>Alias<span style="color:#ff0000;">*</span> </td>
<td><input id="alias" type="text" style="width:150px;"></td>
</tr>
</table>
</div>

<div id="dialog-confirm" title="User Created">
	<p class="user-created"></p>
</div>

<div id="dialog-form2" title="Edit User">
	<p class="validateTips2"><span style="color:#ff0000;">*</span> Required Fields</p>
<table>
<tr>
<td width="105px">Username<span style="color:#ff0000;">*</span> </td>
<td><input id="m_username" type="text" style="width:150px;" disabled="disabled" value=""></td>
</tr>
<tr>
<td>First Name<span style="color:#ff0000;">*</span> </td>
<td><input id="m_first" type="text" style="width:150px;" disabled="disabled"></td>
</tr>
<tr>
<td>Last Name<span style="color:#ff0000;">*</span> </td>
<td><input id="m_last" type="text" style="width:150px;" disabled="disabled"></td>
</tr>
<tr>
<td>Centre<span style="color:#ff0000;">*</span> </td>
<td><select id="m_centre" style="width:152px;">
<?php
for ($i = 0; $i < count($centres); $i++)
{
	echo "<option>" . $centres[$i] . "</option>";
}
?>
</select></td>
</tr>
<tr>
<td>Designation<span style="color:#ff0000;">*</span> </td>
<td><select id="m_designation" style="width:152px;">
<option></option>
<option>Team Leader</option>
<option>Closer</option>
<option>Agent</option>
<option>Probation</option>
</select></td>
</tr>
<tr>
<td>Alias<span style="color:#ff0000;">*</span> </td>
<td><input id="m_alias" type="text" style="width:150px;"></td>
</tr>
</table>
</div>

<div id="dialog-form4" title="Edit Password">
	<p class="validateTips4"><span style="color:#ff0000;">*</span> Required Fields</p>
<table>
<tr>
<td width="105px">Username<span style="color:#ff0000;">*</span> </td>
<td><input id="m_username2" type="text" style="width:150px;" disabled="disabled" value=""></td>
</tr>
<tr>
<td>First Name<span style="color:#ff0000;">*</span> </td>
<td><input id="m_first2" type="text" style="width:150px;" disabled="disabled"></td>
</tr>
<tr>
<td>Last Name<span style="color:#ff0000;">*</span> </td>
<td><input id="m_last2" type="text" style="width:150px;" disabled="disabled"></td>
</tr>
<tr>
<td>Password<span style="color:#ff0000;">*</span> </td>
<td><input id="m_password" type="password" style="width:150px;"></td>
</tr>
<tr>
<td>Re-Type Password<span style="color:#ff0000;">*</span> </td>
<td><input id="m_password2" type="password" style="width:150px;"></td>
</tr>
</table>
</div>

<div id="dialog-confirm2" title="Disable User?">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you would like to disable this user?</p>
    <input type="hidden" id="disable_user" value="" />
</div>

<div id="dialog-confirm3" title="Enable User?">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you would like to enable this user?</p>
    <input type="hidden" id="enable_user" value="" />
</div>

<div id="dialog-form3" title="Search">
<p class="validateTips3">Please Type the Agent's Name Below</p><br />
Agent: <input type="text" id="search_box" size="25" />
<input type="hidden" id="search_agent" value="" />
</div>

<div id="display">
<script>
$( "#display" ).hide();
$( "#display" ).load('user_display.php',
function() {
	$( "#display2" ).load('user_display2.php?page=0&user=<?php echo $ac["user"]; ?>',
	function() {
		$( "#display" ).show('blind', '' , 'slow');
	});
});
</script>
</div>

<?php
include "../source/footer.php";
?>