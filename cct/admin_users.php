<script> // create user modal
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var password = $( "#password" ),
		password2 = $( "#password2" ),
		first = $( "#first" ),
		last = $( "#last" ),
		allFields = $( [] ).add( password ).add( password2 ).add( first ).add( last ),
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
			o.addClass( "ui-state-error" );
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
		height: 275,
		width: 350,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Create an Account": function() {
				var bValid = true;
				allFields.removeClass( "ui-state-error" );

				bValid = bValid && checkLength( first, "first", 2, 20 );
				bValid = bValid && checkLength( last, "last", 2, 20 );
				bValid = bValid && checkLength( password, "password", 6, 16 );
				bValid = bValid && checkLength( password2, "password2", 6, 16 );
				bValid = bValid && checkRegexp( first, /^[a-zA-Z]+$/i, "First Name may only consist of letters." );
				bValid = bValid && checkRegexp( last, /^[a-zA-Z]+$/i, "Last Name may only consist of letters." );
				
				if ( bValid ) {
					$.get("admin_submit.php?p=users&method=create", { first: first.val(), last: last.val(), password: password.val(), password2: password2.val() },
function(data) {
   
   if (data.substring(0,7) == "created")
   {
	   $( "#dialog-form" ).dialog( "close" );
	   $( ".user-created" ).html(data.substring(7));
	   $( "#dialog-confirm3" ).dialog( "open" );
   }
   else
   {
		tips.html(data);
   }
});
				}
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
			allFields.val( "" ).removeClass( "ui-state-error" );
		}
	});

	$( "#create-user" )
		.button()
		.click(function() {
			$( "#dialog-form" ).dialog( "open" );
		});
});
</script>
<script> // user created confirmation
	$(function() {
		$( "#dialog:ui-dialog3" ).dialog( "destroy" );
	
		$( "#dialog-confirm3" ).dialog({
			autoOpen: false,
			resizable: false,
			draggable: false,
			height:140,
			modal: true,
			buttons: {
				"OK": function() {
					$( this ).dialog( "close" );
					location.reload();
				}
			}
		});
	});
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
			buttons: {
				"Disable User": function() {
					var disable_user = $( "#disable_user" ).val();
					$.get("admin_submit.php?p=users&method=disable", { username: disable_user });
					$( this ).dialog( "close" );
					setTimeout( "location.reload()", 500 );
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
			buttons: {
				"Enable User": function() {
					var enable_user = $( "#enable_user" ).val();
					$.get("admin_submit.php?p=users&method=enable", { username: enable_user });
					$( this ).dialog( "close" );
					setTimeout( "location.reload()", 500 );
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
		$( "#dialog-confirm2" ).dialog( "open" );
	}
</script>
<script> // modify user modal
$(function() {
	$( "#dialog:ui-dialog2" ).dialog( "destroy" );
	
	var username = $( "#m_username" ),
		password = $( "#m_password" ),
		password2 = $( "#m_password2" ),
		first = $( "#m_first" ),
		last = $( "#m_last" ),
		allFields = $( [] ).add( username ).add( password ).add( password2 ).add( first ).add( last ),
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
			o.addClass( "ui-state-error" );
			updateTips( "Length of " + n + " must be between " +
				min + " and " + max + "." );
			return false;
		} else {
			return true;
		}
	}
	
	$( "#dialog-form2" ).dialog({
		autoOpen: false,
		height: 275,
		width: 350,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Modify an account": function() {
				var bValid = true;
				allFields.removeClass( "ui-state-error" );

				bValid = bValid && checkLength( password, "password", 6, 16 );
				bValid = bValid && checkLength( password2, "password2", 6, 16 );
				
				if ( bValid ) {
					$.get("admin_submit.php?p=users&method=modify", { username: username.val(), password: password.val(), password2: password2.val() },
function(data) {
   
   if (data == "modified")
   {
	   $( "#dialog-form2" ).dialog( "close" );
	   location.reload();
   }
   else
   {
		tips.html(data);
   }
});
				}
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
			allFields.val( "" ).removeClass( "ui-state-error" );
		}
	});
});
	function Modify(user,first,last)
	{
		$( "#m_username" ).val(user);
		$( "#m_first" ).val(first);
		$( "#m_last" ).val(last);
		$( "#dialog-form2" ).dialog( "open" );
	}
</script>
<script> //search button
$(function() {
	$( "input:submit", ".demo" ).button();
	$( "a", ".demo" ).click(function() { return false; });
});
</script>

<div id="dialog-confirm" title="Disable User?">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you would like to disable this user?</p>
    <input type="hidden" id="disable_user" value="" />
</div>

<div id="dialog-confirm2" title="Enable User?">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you would like to enable this user?</p>
    <input type="hidden" id="enable_user" value="" />
</div>

<div id="dialog-confirm3" title="User Created">
	<p class="user-created"></p>
</div>

<div id="dialog-form" title="Create User">
	<p class="validateTips">All form fields are required.</p>

<table>
<form autocomplete="off">
<tr><td>Username<span style="color:#ff0000;">*</span> </td>
<td><input type="text" size="25" value="Automatically Generated" disabled="disabled"></td></tr>
<tr><td>First Name<span style="color:#ff0000;">*</span> </td>
<td><input id="first" name="first" type="text" size="25"></td></tr>
<tr><td>Last Name<span style="color:#ff0000;">*</span> </td>
<td><input id="last" name="last" type="text" size="25"></td></tr>
<tr><td>Password<span style="color:#ff0000;">*</span> </td>
<td><input id="password" name="password" type="password" size="25"></td></tr>
<tr><td>Re-Type Password<span style="color:#ff0000;">*</span> </td>
<td><input id="password2" name="password2" type="password" size="25"></td></tr>
</form>
</table>
</div>

<div id="dialog-form2" title="Modify User">
	<p class="validateTips2">All form fields are required.</p>

<table>
<form autocomplete="off">

<tr><td>First Name<span style="color:#ff0000;">*</span> </td>
<td><input id="m_first" name="m_first" type="text" size="25" disabled="disabled"></td></tr>
<tr><td>Last Name<span style="color:#ff0000;">*</span> </td>
<td><input id="m_last" name="m_last" type="text" size="25" disabled="disabled"></td></tr>
<tr><td>Username<span style="color:#ff0000;">*</span> </td>
<td><input id="m_username" name="m_username" type="text" size="25" disabled="disabled" value=""></td></tr>
<tr><td>Password<span style="color:#ff0000;">*</span> </td>
<td><input id="m_password" name="m_password" type="password" size="25"></td></tr>
<tr><td>Re-Type Password<span style="color:#ff0000;">*</span> </td>
<td><input id="m_password2" name="m_password2" type="password" size="25"></td></tr>
</form>
</table>
</div>

<p><img src="../images/manage_users_header.png" width="150" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p><br />
<table border="0">
<form method="get" action="admin.php" autocomplete="off">
<tr><td>Search By Username: </td>
<input type="hidden" name="p" value="users" />
<input type="hidden" name="method" value="user" />
<td><input name="query" type="text" size="25" style="height:28px;"></td>
<td><input type="submit" value="Search" style="height:30px; padding-bottom:5px; padding: 0em 1em 3px;" /></td></tr>
</form>
</table>
<div id="users-contain" class="ui-widget">
	<table id="users" class="ui-widget ui-widget-content">
		<thead>
			<tr class="ui-widget-header ">
				<th>Username</th>
				<th>Full Name</th>
                <th>Account Type</th>
                <th>Access Level</th>
                <th colspan="2">Edit User</th>
			</tr>
		</thead>
		<tbody>
        <?php
		mysql_connect('localhost','vericon','18450be');
		mysql_select_db('vericon');
		
		$method = $_GET["method"];
		$query = $_GET["query"];
		
		if ($query == "")
		{
			$q = mysql_query("SELECT * FROM auth WHERE type LIKE '%cct%' LIMIT 0,10")
			  or die(mysql_error());
			  
			if(mysql_num_rows($q) == 0)
			{
				echo "<tr>";
				echo "<td colspan='6'>No Users?!?!?!</td>";
				echo "</tr>";
			}
			else
			{
				while($r = mysql_fetch_assoc($q))
				{
					echo "<tr>";
					echo "<td>" . $r["user"] . "</td>";
					echo "<td>" . $r["last"] . ", " . $r["first"] . "</td>";
					echo "<td>" . $r["type"] . "</td>";
					echo "<td>" . $r["access"] . "</td>";
					echo "<td><a onclick='Modify(\"$r[user]\",\"$r[first]\",\"$r[last]\")' style='cursor:pointer; text-decoration:underline;'>Modify</a></td>";
					if($r["status"] == "Enabled")
					{
						echo "<td><a onclick='Disable(\"$r[user]\")' style='cursor:pointer; text-decoration:underline;'>Disable</a></td>";
					}
					else
					{
						echo "<td><a onclick='Enable(\"$r[user]\")' style='cursor:pointer; text-decoration:underline;'>Enable</a></td>";
					}
					echo "</tr>";
				}
			}
		}
		else
		{	
			$q = mysql_query("SELECT * FROM auth WHERE " . $method . " LIKE '%" . mysql_escape_string($query) . "%' AND type LIKE '%cct%' ORDER BY user ASC")
			  or die(mysql_error());
			
			if(mysql_num_rows($q) == 0)
			{
				echo "<tr>";
				echo "<td colspan='6'>No Results Found!</td>";
				echo "</tr>";
			}
			else
			{
				while($r = mysql_fetch_assoc($q))
				{
					echo "<tr>";
					echo "<td>" . $r["user"] . "</td>";
					echo "<td>" . $r["last"] . ", " . $r["first"] . "</td>";
					echo "<td>" . $r["type"] . "</td>";
					echo "<td>" . $r["access"] . "</td>";
					echo "<td><a onclick='Modify(\"$r[user]\",\"$r[first]\",\"$r[last]\")' style='cursor:pointer; text-decoration:underline;'>Modify</a></td>";
					if($r["status"] == "Enabled")
					{
						echo "<td><a onclick='Disable(\"$r[user]\")' style='cursor:pointer; text-decoration:underline;'>Disable</a></td>";
					}
					else
					{
						echo "<td><a onclick='Enable(\"$r[user]\")' style='cursor:pointer; text-decoration:underline;'>Enable</a></td>";
					}
					echo "</tr>";
				}
			}
		}
		?>
		</tbody>
	</table>
</div>

<button id="create-user">Create new user</button>