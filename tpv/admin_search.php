<script> //mytpv search
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-form" ).dialog({
		autoOpen: false,
		height: 450,
		width: 500,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Close": function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
		}
	});
});

function mytpv()
{
	var cli = $( "#mytpv_cli" );
	$( "#mytpv" ).load('admin_old_search.php?type=mytpv&cli=' + cli.val());
	$( "#dialog-form" ).dialog( "open" );
}
</script>
<script> //mcrm search
$(function() {
	$( "#dialog:ui-dialog2" ).dialog( "destroy" );

	$( "#dialog-form2" ).dialog({
		autoOpen: false,
		height: 450,
		width: 500,
		modal: true,
		resizable: false,
		draggable: false,
		buttons: {
			"Close": function() {
				$( this ).dialog( "close" );
			}
		},
		close: function() {
		}
	});
});

function mcrm()
{
	var method = $( "#mcrm_method" ),
		query = $( "#mcrm_query" ),
		results = $( ".results" );

	$.get("admin_old_search.php?type=mcrm", { method: method.val(), query: query.val() },
	function(data) {
		results.html(data);
	});
}

function mcrm_display(id)
{
	$( "#mcrm" ).load('admin_old_search.php?type=mcrm_display&id=' + id);
	$( "#dialog-form2" ).dialog( "open" );
}
</script>
<script>
function vericon()
{
	var method = $( "#vericon_method" ),
		query = $( "#vericon_query" ),
		results = $( ".results" );

	$.get("admin_submit.php?p=search", { method: method.val(), query: query.val() },
	function(data) {
		results.html(data);
	});
}

function vericon_display(id)
{
	window.location = "../tpv/admin.php?p=details&id=" + id;
}
</script>

<div id="dialog-form" title="MyTPV Search Results">
<div id="mytpv"></div>
</div>

<div id="dialog-form2" title="MCRM Search Results">
<div id="mcrm"></div>
</div>

<table width="100%">
<tr>
<td width="43%" valign="top">
<table width="100%">
<tr>
<td><img src="../images/mytpv_search_header.png" width="150" height="25" /></td>
</tr>
<tr>
<td><img src="../images/line.png" width="97%" height="9" /></td>
</tr>
<tr valign="top">
<td><form onsubmit="event.preventDefault()">
Phone Number <input type="text" size="15" id="mytpv_cli" value=""> <input type="submit" onClick="mytpv()" class="search" value="">
</form></td>
</tr>
</table>
</td>
<td width="57%" valign="top">
<table width="100%">
<tr>
<td><img src="../images/mcrm_search_header.png" width="140" height="25" /></td>
</tr>
<tr>
<td><img src="../images/line.png" width="100%" height="9" /></td>
</tr>
<tr>
<td><form onsubmit="event.preventDefault()">
Search By <select id="mcrm_method" style="min-width:120px; margin:0px; height:auto; padding:3px;">
<option value="line">Phone Number</option>
<option value="id">Sale ID</option>
</select> <input type="text" size="15" id="mcrm_query" value="" /> <input type="submit" onClick="mcrm()" class="search" value="">
</form></td>
</tr>
</table>
</td>
</tr>
<tr>
<td colspan="2">&nbsp;</td>
</tr>
<tr>
<td colspan="2">
<table>
<tr>
<td><img src="../images/vericon_search_header.png" width="160" height="25" /></td>
</tr>
<tr>
<td><img src="../images/line.png" width="740" height="9" /></td>
</tr>
<tr>
<td><form onsubmit="event.preventDefault()">
Search By <select id="vericon_method" style="min-width:120px; margin:0px; height:auto; padding:3px;">
<option value="line">Phone Number</option>
<option value="id">Sale ID</option>
<option value="lead_id">Lead ID</option>
</select> <input type="text" size="15" id="vericon_query" value="" /> <input type="submit" onClick="vericon()" class="search" value="">
</form></td>
</tr>
</table>
</td>
</tr>
</table>
<br />
<div class="results">
</div>