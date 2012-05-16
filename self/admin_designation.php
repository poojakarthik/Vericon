<style>
.ui-dialog .ui-state-error { padding: .3em; }
.error { border: 1px solid transparent; padding: 0.3em; }
.edit_agent {
	background-image:url('../images/edit_icon.png');
	background-repeat:no-repeat;
	height:16px;
	width:16px;
	border:none;
	background-color:transparent;
	cursor:pointer;
}
</style>
<script> //edit
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var user = $( "#user" ),
		designation = $( "#designation" ),
		tips = $( ".error" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-error" );
		setTimeout(function() {
			tips.removeClass( "ui-state-error", 1500 );
		}, 500 );
	}

	$( "#dialog-form" ).dialog({
		autoOpen: false,
		height: 170,
		width: 300,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Submit": function() {
				$.get("admin_submit.php?p=designation&method=edit", { user: user.val(), designation: designation.val() },
				function(data) {
					if (data == "submitted")
					{
						$( "#dialog-form" ).dialog( "close" );
						location.reload();
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

function Edit(user,designation)
{
	$( "#user" ).val(user);
	$( "#designation" ).val(designation);
	$( "#dialog-form" ).dialog( "open" );
}
</script>
<script> //search button
$(function() {
	$( "input:submit", ".demo" ).button();
});
</script>

<div id="dialog-form" title="Agent Timesheet">
<p class="error"><span style="color:#ff0000;">*</span> Required Fields</p>
<table>
<tr>
<td width='80px'>Agent Name </td>
<td><input type="text" id="user" disabled="disabled" style='width:89px; height:20px; padding:0px; margin:0px;'></td>
</tr>
<tr>
<td width='80px'>Designation<span style="color:#ff0000;">*</span> </td>
<td><select id="designation" style='width:91px; height:20px; padding:0px; margin:0px;'>
<option></option>
<option>Probation</option>
<option>Agent</option>
<option>Closer</option>
<option>Team Leader</option>
</select></td>
</tr>
</table>
</div>

<p><img src="../images/manage_users_header.png" width="150" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p><br />
<table border="0">
<form method="get" action="admin.php" autocomplete="off">
<tr><td>Search By Username: </td>
<input type="hidden" name="p" value="designation" />
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
<th>Designation</th>
<th>Edit</th>
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
	$check = mysql_query("SELECT * FROM auth WHERE type LIKE '%self%' AND centre = '$ac[centre]' AND status = 'Enabled'") or die(mysql_error());
	$rows = mysql_num_rows($check);
	
	if($rows == 0)
	{
		echo "<tr>";
		echo "<td colspan='4'>No Users?!?!?!</td>";
		echo "</tr>";
	}
	else
	{
		$st = $_GET["page"]*10;
		$q = mysql_query("SELECT * FROM auth WHERE type LIKE '%self%' AND centre = '$ac[centre]' AND status = 'Enabled' ORDER BY user ASC LIMIT $st , 10") or die(mysql_error());
		
		while($r = mysql_fetch_assoc($q))
		{
			$q1 = mysql_query("SELECT * FROM timesheet_designation WHERE user = '$r[user]'") or die(mysql_error());
			$d = mysql_fetch_assoc($q1);
			
			echo "<tr>";
			echo "<td>" . $r["user"] . "</td>";
			echo "<td>" . $r["last"] . ", " . $r["first"] . "</td>";
			echo "<td>" . $d["designation"] . "</td>";
			echo "<td style='text-align:center;'><input type='button' onClick='Edit(\"$r[user]\",\"$d[designation]\")' class='edit_agent' title='Edit'></td>";
			echo "</tr>";
		}
	}
}
else
{
	$check = mysql_query("SELECT * FROM auth WHERE " . $method . " LIKE '%" . mysql_escape_string($query) . "%' AND type LIKE '%self%' AND centre = '$ac[centre]' AND status = 'Enabled'") or die(mysql_error());
	$rows = mysql_num_rows($check);

	if($rows == 0)
	{
		echo "<tr>";
		echo "<td colspan='4'>No Results Found!</td>";
		echo "</tr>";
	}
	else
	{
		$st = $_GET["page"]*10;
		$q = mysql_query("SELECT * FROM auth WHERE " . $method . " LIKE '%" . mysql_escape_string($query) . "%' AND type LIKE '%self%' AND centre = '$ac[centre]' AND status = 'Enabled' ORDER BY user ASC LIMIT $st , 10") or die(mysql_error());

		while($r = mysql_fetch_assoc($q))
		{
			$q1 = mysql_query("SELECT * FROM timesheet_designation WHERE user = '$r[user]'") or die(mysql_error());
			$d = mysql_fetch_assoc($q1);
			
			echo "<tr>";
			echo "<td>" . $r["user"] . "</td>";
			echo "<td>" . $r["last"] . ", " . $r["first"] . "</td>";
			echo "<td>" . $d["designation"] . "</td>";
			echo "<td><input type='button' onClick='Edit(\"$r[user]\")' class='edit_agent' title='Edit'></td>";
			echo "</tr>";
		}
	}
}
?>
</tbody>
</table>
</div>

<table width="100%" style="border:0;">
<tr>
<td align="left">
<?php
if (($st - 10) < $rows && $_GET["page"] > 0)
{
    $page = $_GET["page"]-1;
    echo "<a href='?p=designation&page=$page&method=$method&query=$query' class='back'></a>";
}
?>
</td>
<td align="right">
<?php
if (($st + 10) < $rows)
{
	$page = $_GET["page"]+1;
	echo "<a href='?p=designation&page=$page&method=$method&query=$query' class='next'></a>";
}
?>
</td>
</tr>
</table>
</div>