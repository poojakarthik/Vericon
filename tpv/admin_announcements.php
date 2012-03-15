<?php
	$poster = $ac["first"] . " " . $ac["last"];
	$date = date("d F Y");
?>
<script> //post announcement
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
	var poster = "<?php echo $poster; ?>",
		subject = $( "#subject" ),
		message = $( "#message" ),
		allFields = $( [] ).add( poster ).add( subject ).add( message ),
		tips = $( ".validateTips" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-error" );
	}

	$( "#dialog-form" ).dialog({
		autoOpen: false,
		height: 275,
		width: 400,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Post Announcement": function() {
				var bValid = true;
				if ( bValid ) {
					$.get("admin_submit.php?p=announcements&method=post", { poster: poster, subject: subject.val(), message: message.val()},
function(data) {
   
   if (data == "posted")
   {
	   $( "#dialog-form" ).dialog( "close" );
	   location.reload();
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
			allFields.val( "" ).removeClass( "ui-state-error" );
		}
	});

	$( "#post-announcement" )
		.button()
		.click(function() {
			$( "#dialog-form" ).dialog( "open" );
		});
});
</script>
<script> //edit announcement
$(function() {
	$( "#dialog:ui-dialog2" ).dialog( "destroy" );
	
	var id = $( "#e_id" ),
		subject = $( "#e_subject" ),
		message = $( "#e_message" ),
		allFields = $( [] ).add( id ).add( subject ).add( message ),
		tips = $( ".validateTips2" );

	function updateTips( t ) {
		tips
			.text( t )
			.addClass( "ui-state-error" );
	}

	$( "#dialog-form2" ).dialog({
		autoOpen: false,
		height: 275,
		width: 400,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Edit Announcement": function() {
				var bValid = true;
				if ( bValid ) {
					$.get("admin_submit.php?p=announcements&method=edit", { id: id.val(), subject: subject.val(), message: message.val()},
function(data) {
   
   if (data == "edited")
   {
	   $( "#dialog-form2" ).dialog( "close" );
	   location.reload();
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
			allFields.val( "" ).removeClass( "ui-state-error" );
		}
	});

	$( "#edit-announcement" )
		.button()
		.click(function() {
			$( "#dialog-form" ).dialog( "open" );
		});
});
function Edit(id)
{
	$( "#e_id" ).val(id);
	$.get("admin_submit.php?p=announcements&method=subject", { id: id},
	function(data) {
		$( "#e_subject" ).val(data);
	});
	$.get("admin_submit.php?p=announcements&method=message", { id: id},
	function(data) {
		$( "#e_message" ).val(data);
	});
	$( "#dialog-form2" ).dialog( "open" );
}
</script>
<script> // hide confirmation
	$(function() {
		$( "#dialog:ui-dialog" ).dialog( "destroy" );
	
		$( "#dialog-confirm" ).dialog({
			autoOpen: false,
			resizable: false,
			draggable: false,
			height:140,
			modal: true,
			buttons: {
				"Hide Announcement": function() {
					var hide_announcement = $( "#hide_announcement" ).val();
					$.get("admin_submit.php?p=announcements&method=hide", { id: hide_announcement });
					$( this ).dialog( "close" );
					setTimeout( "location.reload()", 500 );
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			}
		});
	});
	function Hide(id)
	{
		$( "#hide_announcement" ).val(id);
		$( "#dialog-confirm" ).dialog( "open" );
	}
</script>
<script> // display confirmation
	$(function() {
		$( "#dialog:ui-dialog2" ).dialog( "destroy" );
	
		$( "#dialog-confirm2" ).dialog({
			autoOpen: false,
			resizable: false,
			draggable: false,
			height:140,
			modal: true,
			buttons: {
				"Display Announcement": function() {
					var display_announcement = $( "#display_announcement" ).val();
					$.get("admin_submit.php?p=announcements&method=display", { id: display_announcement });
					$( this ).dialog( "close" );
					setTimeout( "location.reload()", 500 );
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			}
		});
	});
	function Display(id)
	{
		$( "#display_announcement" ).val(id);
		$( "#dialog-confirm2" ).dialog( "open" );
	}
</script>

<p><img src="../images/tpv_announcements_header.png" width="215" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p><br />

<div id="dialog-form" title="Post Announcement">
	<p class="validateTips"></p>
<form autocomplete="off">
<input type="hidden" id="e_id" name="e_id" value="" />
<table>
<tr>
<td><label>Department: </label></td>
<td><b>TPV</b></td>
</tr>
<tr>
<td><label>Subject: </label></td>
<td><input type="text" id="subject" name="subject" size="25" value="" style="width:300px;" /></td>
</tr>
<tr>
<td><label>Message: </label></td>
<td><textarea id="message" name="message" style="width:300px; height:100px; resize:none;"></textarea></td>
</tr>
</table>
</form>
</div>

<div id="dialog-form2" title="Edit Announcement">
	<p class="validateTips2"></p>
<form autocomplete="off">
<table>
<tr>
<td><label>Department: </label></td>
<td><b>TPV</b></td>
</tr>
<tr>
<td><label>Subject: </label></td>
<td><input type="text" id="e_subject" name="e_subject" size="25" value="" style="width:300px;" /></td>
</tr>
<tr>
<td><label>Message: </label></td>
<td><textarea id="e_message" name="e_message" style="width:300px; height:100px; resize:none;"></textarea></td>
</tr>
</table>
</form>
</div>

<div id="dialog-confirm" title="Hide Announcement?">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you would like to hide this announcement?</p>
    <input type="hidden" id="hide_announcement" value="" />
</div>

<div id="dialog-confirm2" title="Display Announcement?">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Are you sure you would like to display this announcement?</p>
    <input type="hidden" id="display_announcement" value="" />
</div>

<div id="users-contain" class="ui-widget">
	<table id="users" class="ui-widget ui-widget-content">
		<thead>
			<tr class="ui-widget-header ">
				<th>Date</th>
				<th>Subject</th>
                <th colspan="2">Options</th>
			</tr>
		</thead>
		<tbody>
        <?php
		$q = mysql_query("SELECT * FROM announcements WHERE department = 'tpv' ORDER BY id DESC")
			  or die(mysql_error());

		if (mysql_num_rows($q) == 0)
		{
			echo "<tr>";
			echo "<td colspan='4' style=\"text-align:center\">No Announcements!</td>";
			echo "</tr>";
		}
		else
		{	
			while($r = mysql_fetch_assoc($q))
			{
				echo "<tr>";
				echo "<td>" . $r["date"] . "</td>";
				echo "<td>" . $r["subject"] . "</td>";
				echo "<td><a onclick='Edit(\"$r[id]\")' style='cursor:pointer; text-decoration:underline;'>Edit</a></td>";
				if($r["display"] == "Yes")
				{
					echo "<td><a onclick='Hide(\"$r[id]\")' style='cursor:pointer; text-decoration:underline;'>Hide</a></td>";
				}
				else
				{
					echo "<td><a onclick='Display(\"$r[id]\")' style='cursor:pointer; text-decoration:underline;'>Display</a></td>";
				}
				echo "</tr>";
			}
		}
		?>
		</tbody>
	</table>
</div>

<button id="post-announcement">Post Announcement</button>