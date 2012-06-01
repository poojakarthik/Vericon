<?php
include "../auth/iprestrict.php";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>VeriCon :: HR :: Users</title>
<link rel="shortcut icon" href="../images/vericon.ico">
<link rel="stylesheet" href="../css/inner.css" type="text/css"/>
<?php
include "../source/jquery.php";
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

.enable {
	background-image:url('../images/enable_icon.png');
	background-repeat:no-repeat;
	height:16px;
	width:16px;
	border:none;
	background-color:transparent;
	cursor:pointer;
}

.disable {
	background-image:url('../images/disable_icon.png');
	background-repeat:no-repeat;
	height:16px;
	width:16px;
	border:none;
	background-color:transparent;
	cursor:pointer;
}

.create_user
{
	background-image:url('../images/create_user_btn.png');
	background-repeat:no-repeat;
	height:30px;
	width:102px;
	border:none;
	background-color:transparent;
}

.create_user:hover
{
	background-image:url('../images/create_user_btn_hover.png');
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
.ui-dialog .ui-state-highlight { padding: .3em; }
.validateTips { border: 1px solid transparent; padding: 0.3em; }
.validateTips2  { border: 1px solid transparent; padding: 0.3em; }
.validateTips3  { border: 1px solid transparent; padding: 0.3em; }
.ui-autocomplete-loading { background: white url('../images/ajax-loader.gif') right center no-repeat; }
</style>
<script>
function Display(page)
{
	$( "#display" ).load("user_display.php?page=" + page + "&user=<?php echo $ac["user"] ?>");
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
		alias = $( "#alias" ),
		allFields = $( [] ).add( password ).add( password2 ).add( first ).add( last ).add( centre ).add( alias ),
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
		width: 300,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Submit": function() {
				var bValid = true;

				bValid = bValid && checkLength( first, "First Name", 2, 30 );
				bValid = bValid && checkLength( last, "Last Name", 2, 30 );
				bValid = bValid && checkLength( password, "Password", 6, 16 );
				bValid = bValid && checkRegexp( first, /^[a-zA-Z]+$/i, "First Name may only consist of letters." );
				bValid = bValid && checkRegexp( last, /^[a-zA-Z]+$/i, "Last Name may only consist of letters." );
				
				if ( bValid ) {
					$.get("user_submit.php?method=create", { first: first.val(), last: last.val(), password: password.val(), password2: password2.val(), centre: centre.val(), alias: alias.val() },
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
		height:140,
		modal: true,
		buttons: {
			"OK": function() {
				$( "#dialog-confirm" ).dialog( "close" );
				var page_link = $( "#page_link" );
				$( "#display" ).load("user_display.php" + page_link.val());
			}
		}
	});
});
</script>
<script> // modify user modal
$(function() {
	$( "#dialog:ui-dialog3" ).dialog( "destroy" );
	
	var username = $( "#m_username" ),
		password = $( "#m_password" ),
		password2 = $( "#m_password2" ),
		centre = $( "#m_centre" ),
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
		height: 320,
		width: 300,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Submit": function() {
				var bValid = true;
				bValid = bValid && checkLength( password, "Password", 6, 16 );
				
				if ( bValid ) {
					$.get("user_submit.php?method=modify", { username: username.val(), password: password.val(), password2: password2.val(), centre: centre.val(), alias: alias.val() },
					function(data) {
						if (data == "modified")
						{
							$( "#dialog-form2" ).dialog( "close" );
							tips.html('<span style="color:#ff0000;">*</span> Required Fields');
							var page_link = $( "#page_link" );
							$( "#display" ).load("user_display.php" + page_link.val());
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
	function Modify(user,first,last,centre,alias)
	{
		$( "#m_username" ).val(user);
		$( "#m_first" ).val(first);
		$( "#m_last" ).val(last);
		$( "#m_centre" ).val(centre);
		$( "#m_alias" ).val(alias);
		$( "#dialog-form2" ).dialog( "open" );
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
					$( "#display" ).load("user_display.php" + page_link.val());
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
					$( "#display" ).load("user_display.php" + page_link.val());
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
		height:160,
		modal: true,
		buttons: {
			"Open": function() {				
				$.get("user_submit.php?method=check", { agent: agent.val() },
				function(data) {
					if (data == "valid")
					{
						$( "#dialog-form3" ).dialog( "close" );
						$( "#display" ).load("user_display.php?query=" + agent.val());
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
</head>

<body>
<div style="display:none;">
<img src="../images/back_hover_btn.png" /><img src="../images/next_hover_btn.png" /><img src="../images/create_user_btn_hover.png" /><img src="../images/search_btn_hover.png" />
</div>

<div id="main_wrapper">

<?php
include "../source/header.php";
include "../source/hr_menu.php";
?>

<div id="text" class="demo">

<div id="dialog-form" title="Create User">
	<p class="validateTips"><span style="color:#ff0000;">*</span> Required Fields</p>
<table>
<tr>
<td width="105px">Username<span style="color:#ff0000;">*</span> </td>
<td><input type="text" size="20" value="Automatically Generated" disabled="disabled"></td>
</tr>
<tr>
<td>First Name<span style="color:#ff0000;">*</span> </td>
<td><input id="first" type="text" size="20"></td>
</tr>
<tr>
<td>Last Name<span style="color:#ff0000;">*</span> </td>
<td><input id="last" type="text" size="20"></td>
</tr>
<tr>
<td>Password<span style="color:#ff0000;">*</span> </td>
<td><input id="password" type="password" size="20"></td>
</tr>
<tr>
<td>Re-Type Password<span style="color:#ff0000;">*</span> </td>
<td><input id="password2" type="password" size="20"></td>
</tr>
<tr>
<td>Centre<span style="color:#ff0000;">*</span> </td>
<td><select id="centre" style="margin:0px; padding:4px 4px 4px 0px; width:135px; height:28px;">
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
<td>Alias<span style="color:#ff0000;">*</span> </td>
<td><input id="alias" type="text" size="20"></td>
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
<td><input id="m_username" type="text" size="20" disabled="disabled" value=""></td>
</tr>
<tr>
<td>First Name<span style="color:#ff0000;">*</span> </td>
<td><input id="m_first" type="text" size="20" disabled="disabled"></td>
</tr>
<tr>
<td>Last Name<span style="color:#ff0000;">*</span> </td>
<td><input id="m_last" type="text" size="20" disabled="disabled"></td>
</tr>
<tr>
<td>Password<span style="color:#ff0000;">*</span> </td>
<td><input id="m_password" type="password" size="20"></td>
</tr>
<tr>
<td>Re-Type Password<span style="color:#ff0000;">*</span> </td>
<td><input id="m_password2" type="password" size="20"></td>
</tr>
<tr>
<td>Centre<span style="color:#ff0000;">*</span> </td>
<td><select id="m_centre" style="margin:0px; padding:4px 4px 4px 0px; width:135px; height:28px;">
<?php
for ($i = 0; $i < count($centres); $i++)
{
	echo "<option>" . $centres[$i] . "</option>";
}
?>
</select></td>
</tr>
<tr>
<td>Alias<span style="color:#ff0000;">*</span> </td>
<td><input id="m_alias" type="text" size="20"></td>
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

<table width="100%">
<tr>
<td align="left" valign="bottom"><img src="../images/manage_users_header.png" width="150" height="25" style="margin-left:3px;" /></td>
<td align="right" style="padding-right:10px;"><input type="button" onClick="Search()" class="search"><input type="button" onClick="Create()" class="create_user"></td>
</tr>
<tr>
<td colspan="2"><img src="../images/line.png" width="740" height="9" /></td>
</tr>
</table>

<div id="display">
<script>
$( "#display" ).load("user_display.php?page=0&user=<?php echo $ac["user"] ?>");
</script>
</div>

</div>

</div>

<?php
include "../source/footer.php";
?>
</body>
</html>