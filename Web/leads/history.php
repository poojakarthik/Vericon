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

<div id="display">
<script>
$( "#display" ).load("history_display.php",
function() {
	$( "#display" ).show('blind', '' , 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>