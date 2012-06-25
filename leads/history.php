<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .3em 10px; text-align: center; }
.lead_id-loading { background: white url('../images/ajax-loader.gif') right center no-repeat; }
</style>
<script>
function Search()
{
	var id = $( "#lead_id" );
	
	id.addClass("lead_id-loading")
	
	$.get("history_submit.php", { lead: id.val() },
	function (data)
	{
		$( "#history" ).html(data);
		id.removeClass("lead_id-loading");
	});
	
}
</script>

<p><img src="../images/lead_history_header.png" width="130" height="25" style="margin-left:3px;" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p><br />

&nbsp;Lead ID &nbsp;<input type="text" onchange="Search()" id="lead_id" /><br /><br />

<center><div id="users-contain" class="ui-widget" style="width:99%;">
<table id="users" class="ui-widget ui-widget-content" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th>Centre</th>
<th>Issue Date</th>
<th>Expiry Date</th>
<th>Packet Expiry</th>
</tr>
</thead>
<tbody id="history">
<tr>
<td colspan="4">Please Enter the Lead ID to Search Above!</td>
</tr>
</tbody>
</table>
</div></center>

<?php
include "../source/footer.php";
?>