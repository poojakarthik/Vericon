<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
label, input { display:block; }
input.text { margin-bottom:12px; width:95%; padding: .4em; font-family:Tahoma, Geneva, sans-serif;
font-size:13px; }
fieldset { padding:0; border:0; margin-top:25px; }
h1 { font-size: 1.2em; margin: .6em 0; }
div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
.ui-dialog .ui-state-error { padding: .3em; }
.validateTips { border: 1px solid transparent; padding: 0.3em; }
</style>
<script> // create user modal
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var ip = $( "#ip" ),
		description = $( "#description" ),
		allFields = $( [] ).add( ip ).add( description ),
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
			o.addClass( "ui-state-error" );
			updateTips( n );
			return false;
		} else {
			return true;
		}
	}
	
	$( "#dialog-form" ).dialog({
		autoOpen: false,
		height: 250,
		width: 350,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Add an Allowed IP": function() {
				var bValid = true;
				allFields.removeClass( "ui-state-error" );

				bValid = bValid && checkLength( ip, "ip", 4, 16 );
				bValid = bValid && checkRegexp( ip, /^([0-9\.])+$/i, "IP may consist of 0-9 and '.'" );
								
				bValid = bValid && checkLength( description, "description", 8, 300 );
				
				if ( bValid ) {
					$.get("access_submit.php?method=create", { ip: ip.val(), description: description.val()},
function(data) {
   
   if (data == "created")
   {
	   $( "#dialog-form" ).dialog( "close" );
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

	$( "#add-ip" )
		.button()
		.click(function() {
			$( "#dialog-form" ).dialog( "open" );
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
				"Disable IP": function() {
					var disable_ip = $( "#disable_ip" ).val();
					$.get("access_submit.php?method=disable", { ip: disable_ip });
					$( this ).dialog( "close" );
					setTimeout( "location.reload()", 500 );
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			}
		});
	});
	function Disable(ip)
	{
		$( "#disable_ip" ).val(ip);
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
				"Enable IP": function() {
					var enable_ip = $( "#enable_ip" ).val();
					$.get("access_submit.php?method=enable", { ip: enable_ip });
					$( this ).dialog( "close" );
					setTimeout( "location.reload()", 500 );
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			}
		});
	});
	function Enable(ip)
	{
		$( "#enable_ip" ).val(ip);
		$( "#dialog-confirm2" ).dialog( "open" );
	}
</script>
<script> //search button
	$(function() {
		$( "input:submit", ".demo" ).button();
		$( "a", ".demo" ).click(function() { return false; });
	});
</script>

<div id="dialog-confirm" title="Disable IP?">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you would like to disable this IP?</p>
    <input type="hidden" id="disable_ip" value="" />
</div>

<div id="dialog-confirm2" title="Enable IP?">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you would like to enable this IP?</p>
    <input type="hidden" id="enable_ip" value="" />
</div>

<div id="dialog-form" title="Add Allowed IP">
	<p class="validateTips">All form fields are required.</p>

<table>
<form autocomplete="off">
<tr><td>IP: </td>
<td><input id="ip" name="ip" type="text" size="25"></td></tr>
<tr><td>Description: </td>
<td><textarea id="description" name="description" rows="4" style="resize:none; width:163px; display:block; padding-top:5px; padding-bottom:5px;"></textarea></td></tr>
</form>
</table>
</div>

	<p><img src="../images/allowed_ips_header.png" width="130" height="25" /></p>
	<p><img src="../images/line.png" width="740" height="9" /></p><br />
    <table border="0">
    <form method="get" action="access.php" autocomplete="off">
    <tr><td>Search By: </td>
    <td><select name="method" style="width:120px;">
    <option value="Description">Description</option>
    <option value="IP">IP</option>
    </select></td>
    <td><input name="query" type="text" size="25" style="height:28px;"></td>
    <td><input type="submit" value="Search" style="height:30px; padding-bottom:5px; padding: 0em 1em 3px;" /></td></tr>
    </form>
    </table>

<div id="users-contain" class="ui-widget">
	<table id="users" class="ui-widget ui-widget-content">
		<thead>
			<tr class="ui-widget-header ">
				<th>IP</th>
				<th>Description</th>
                <th>Change IP Status</th>
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
			$q = mysql_query("SELECT * FROM allowedip LIMIT 0,10")
			  or die(mysql_error());

			if(mysql_num_rows($q) == 0)
			{
				echo "<tr>";
				echo "<td colspan='3'>No IPs?!?!?!</td>";
				echo "</tr>";
			}
			else
			{
				while($r = mysql_fetch_assoc($q))
				{
					echo "<tr>";
					echo "<td>" . $r["IP"] . "</td>";
					echo "<td>" . $r["Description"] . "</td>";
					if($r["status"] == 1)
					{
						echo "<td><a onclick='Disable(\"$r[IP]\")' style='cursor:pointer; text-decoration:underline;'>Disable</a></td>";
					}
					else
					{
						echo "<td><a onclick='Enable(\"$r[IP]\")' style='cursor:pointer; text-decoration:underline;'>Enable</a></td>";
					}
					echo "</tr>";
				}
			}
		}
		else
		{	
			$q = mysql_query("SELECT * FROM allowedip WHERE " . $method . " LIKE '%" . mysql_escape_string($query) . "%' LIMIT 0,10")
			  or die(mysql_error());
			  
			if(mysql_num_rows($q) == 0)
			{
				echo "<tr>";
				echo "<td colspan='3'>No Results Found!</td>";
				echo "</tr>";
			}
			else
			{
				while($r = mysql_fetch_assoc($q))
				{
					echo "<tr>";
					echo "<td>" . $r["IP"] . "</td>";
					echo "<td>" . $r["Description"] . "</td>";
					if($r["status"] == 1)
					{
						echo "<td><a onclick='Disable(\"$r[IP]\")' style='cursor:pointer; text-decoration:underline;'>Disable</a></td>";
					}
					else
					{
						echo "<td><a onclick='Enable(\"$r[IP]\")' style='cursor:pointer; text-decoration:underline;'>Enable</a></td>";
					}
					echo "</tr>";
				}
			}
		}
		?>
		</tbody>
	</table>
</div>

<button id="add-ip">Add Allowed IP</button>

<?php
include "../source/footer.php";
?>