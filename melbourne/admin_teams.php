<script>
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var username = $( "#username" ),
		team = $( "#team" ),
		tips = $( ".validateTips" );
	
	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		height: 175,
		width: 250,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Assign": function() {
				$.get("admin_submit.php?p=teams&method=assign", { username: username.val(), team: team.val() },
				function(data) {
				   if (data == "assigned")
				   {
					   $( "#dialog-confirm" ).dialog( "close" );
					   location.reload();
				   }
				   else
				   {
						tips.html(data);
				   }
				});
			},
			Cancel: function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
	function Assign(user)
	{
		$( "#username" ).val(user);
		$( "#dialog-confirm" ).dialog( "open" );
	}
</script>
<script> //search button
$(function() {
	$( "input:submit", ".demo" ).button();
});
</script>

<div id="dialog-confirm" title="Assign User to Team">
	<p class="validateTips">Select a Team</p>
    <table>
    <tr>
    <td width="40px">User </td>
    <td><input type="text" id="username" style="width:112px; padding-left:5px;" disabled="disabled" value="" ></td>
    </tr>
    <tr>
    <td width="40px">Team </td>
    <td><select id="team" style="width:120px; height:auto; padding:0px; margin:0px;">
    <option></option>
    <option>Damith</option>
    <option>Daniel</option>
    <option>Liam</option>
    <option>Sanu</option>
    </select></td>
    </tr>
    </table>
</div>

<p><img src="../images/manage_users_header.png" width="150" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p><br />
<table border="0">
<form method="get" action="admin.php" autocomplete="off">
<tr><td>Search By Username: </td>
<input type="hidden" name="p" value="teams" />
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
                <th colspan="2">Assigned To</th>
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
			$check = mysql_query("SELECT * FROM auth WHERE type LIKE '%melbourne%' AND centre = '$ac[centre]' AND status = 'Enabled'") or die(mysql_error());
			$rows = mysql_num_rows($check);
			
			if($rows == 0)
			{
				echo "<tr>";
				echo "<td colspan='6'>No Users?!?!?!</td>";
				echo "</tr>";
			}
			else
			{
				$st = $_GET["page"]*10;
				$q = mysql_query("SELECT * FROM auth WHERE type LIKE '%melbourne%' AND centre = '$ac[centre]' AND status = 'Enabled' LIMIT $st , 10") or die(mysql_error());
				
				while($r = mysql_fetch_assoc($q))
				{
					$q1 = mysql_query("SELECT team FROM teams WHERE user = '$r[user]'") or die(mysql_error());
					$t = mysql_fetch_row($q1);
					echo "<tr>";
					echo "<td>" . $r["user"] . "</td>";
					echo "<td>" . $r["last"] . ", " . $r["first"] . "</td>";
					echo "<td>" . $t[0] . "</td>";
					echo "<td><a onclick='Assign(\"$r[user]\")' style='cursor:pointer; text-decoration:underline;'>Assign</a></td>";
					echo "</tr>";
				}
			}
		}
		else
		{
			$check = mysql_query("SELECT * FROM auth WHERE " . $method . " LIKE '%" . mysql_escape_string($query) . "%' AND type LIKE '%melbourne%' AND centre = '$ac[centre]' AND status = 'Enabled'") or die(mysql_error());
			$rows = mysql_num_rows($check);
		
			if($rows == 0)
			{
				echo "<tr>";
				echo "<td colspan='6'>No Results Found!</td>";
				echo "</tr>";
			}
			else
			{
				$st = $_GET["page"]*10;
				$q = mysql_query("SELECT * FROM auth WHERE " . $method . " LIKE '%" . mysql_escape_string($query) . "%' AND type LIKE '%melbourne%' AND centre = '$ac[centre]' AND status = 'Enabled' ORDER BY user ASC LIMIT $st , 10") or die(mysql_error());

				while($r = mysql_fetch_assoc($q))
				{
					$q1 = mysql_query("SELECT team FROM teams WHERE user = '$r[user]'") or die(mysql_error());
					$t = mysql_fetch_row($q1);
					echo "<tr>";
					echo "<td>" . $r["user"] . "</td>";
					echo "<td>" . $r["last"] . ", " . $r["first"] . "</td>";
					echo "<td>" . $t[0] . "</td>";
					echo "<td><a onclick='Assign(\"$r[user]\")' style='cursor:pointer; text-decoration:underline;'>Assign</a></td>";
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
    echo "<a href='?p=teams&page=$page&method=$method&query=$query' class='back'></a>";
}
?>
</td>
<td align="right">
<?php
if (($st + 10) < $rows)
{
	$page = $_GET["page"]+1;
	echo "<a href='?p=teams&page=$page&method=$method&query=$query' class='next'></a>";
}
?>
</td>
</tr>
</table>
</div>