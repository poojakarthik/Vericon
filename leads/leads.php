<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .3em 10px; text-align: center; }
</style>
<script>
function Details(centre)
{
	
}
</script>
<script>
function Export(centre)
{
	
}
</script>
<script> //Leads Search
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:275,
		height:200,
		modal: true,
		show: 'blind',
		hide: 'blind',
		buttons: {
			"Search": function() {
				var lead = $( "#lead" );
				
				$( ".result" ).html('<table width="100%"><tr height="60px"><td valign="middle" align="center"><img src="../images/ajax-loader.gif" /></td></tr></table>');
				
				$.get("check.php", { lead: lead.val() },
				function(data) {
					$( ".result" ).html(data);
				});
			},
			
			"Close": function() {
				$( this ).dialog( "close" );
			}
		}
	});
});

function Search()
{
	$( ".result" ).html("");
	$( "#dialog-confirm" ).dialog( "open" );
}
</script>

<div id="dialog-confirm" title="Lead Search">
	<p>Lead ID: &nbsp;&nbsp;<input type="text" size="25" id="lead" /></p>
    <p class="result" style="margin-top:10px;"></p>
</div>

<div id="display">
<center><img src="../images/ajax-loader.gif" /><br /><br />
<p>Compiling Leads Report. Please Wait...</p></center>
<script>
$( "#display" ).load("leads_display.php",
function() {
	$( "#display" ).show('blind', '' , 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>