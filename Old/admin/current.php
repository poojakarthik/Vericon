<?php
include "../auth/iprestrict.php";
include "../source/header.php";
?>
<style>
	div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
	div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: center; }
	div#users-contain table td { border: 1px solid #eee; padding: .3em 5px; text-align: center; }
</style>
<script>
function Log_out(hash,user)
{
	var hash = hash,
		user = user;
	
	$.get("current_process.php?method=logout", { hash: hash, username: user},
	function(data) {
		$( "#display" ).hide('blind', '' , 'slow',
		function() {
			$( "#display" ).load("current_display.php",
			function() {
				$( "#display" ).show('blind', '' , 'slow');
			});
		});
	});
}
</script>

<div id="display">
<script>
$( "#display" ).load("current_display.php",
function() {
	$( "#display" ).show('blind', '' , 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>