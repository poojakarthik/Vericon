<?php
include "../auth/iprestrict.php";
include "../source/header.php";
$q = mysql_query("SELECT centres FROM vericon.operations WHERE user = '$ac[user]'") or die(mysql_error());
$cen = mysql_fetch_row($q);
if ($cen[0] == "All")
{
	$centres = array();
	$q1 = mysql_query("SELECT centre FROM vericon.centres WHERE status = 'Enabled' ORDER BY centre ASC") or die(mysql_error());
	while ($centre = mysql_fetch_row($q1))
	{
		array_push($centres, $centre[0]);
	}
	$centres_link = implode(",", $centres);
}
elseif ($cen[0] == "Captive" || $cen[0] == "Self")
{
	$centres = array();
	$q1 = mysql_query("SELECT centre FROM vericon.centres WHERE status = 'Enabled' AND type = '$cen[0]' ORDER BY centre ASC") or die(mysql_error());
	while ($centre = mysql_fetch_row($q1))
	{
		array_push($centres, $centre[0]);
	}
	$centres_link = implode(",", $centres);
}
else
{
	$centres_link = $cen[0];
}
?>
<style>
div#users-contain table { margin: 1em 0; margin-bottom:0; border-collapse: collapse; width:98% }
div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: center; }
div#users-contain table td { border: 1px solid #eee; padding: .1em 10px; text-align: center; }
</style>
<script>
function Centre()
{
	var centre = $( "#centre" ),
		centres = "<?php echo $centres_link; ?>",
		date = $( "#datepicker" );
	
	$( "#display" ).hide( 'blind', '', 'slow', function() {
		$( "#display" ).load('payable_display.php?centre=' + centre.val() + '&centres=' + centres + '&date=' + date.val(), function(){
			$( "#display" ).show( 'blind', '', 'slow');
		});
	});
}
</script>
<script>
function Edit_View()
{
	var centre = $( "#centre" ),
		centres = "<?php echo $centres_link; ?>",
		date = $( "#datepicker" );
	
	$( "#display" ).hide( 'blind', '', 'slow', function() {
		$( "#display" ).load('payable_edit.php?centre=' + centre.val() + '&centres=' + centres + '&date=' + date.val(), function(){
			$( "#display" ).show( 'blind', '', 'slow');
		});
	});
}
</script>
<script>
function Done()
{
	var centre = $( "#centre" ),
		centres = "<?php echo $centres_link; ?>",
		date = $( "#datepicker" );
	
	$( "#display" ).hide( 'blind', '', 'slow', function() {
		$( "#display" ).load('payable_display.php?centre=' + centre.val() + '&centres=' + centres + '&date=' + date.val(), function(){
			$( "#display" ).show( 'blind', '', 'slow');
		});
	});
}
</script>
<script>
function Hours(user)
{
	var field = "#" + user + "_hours",
		field2 = "#" + user + "_cps",
		hours = $( field ),
		date = $( "#datepicker" );

	function checkRegexp( o, regexp, n ) {
		if ( !( regexp.test( o.val() ) ) ) {
			alert( n );
			o.val("");
			return false;
		}
		else {
			return true;
		}
	}

	bValid = checkRegexp( hours, /^([0-9.])+$/, "'" + hours.val() + "' is not a valid input" );
	
	if (bValid)
	{
		$.get("payable_process.php", { method: "hours", date: date.val(), user: user, hours: hours.val() }, function (data) { 
			$( field2 ).html(data);
		});
	}
}
</script>
<script>
function Bonus(user)
{
	var field = "#" + user + "_bonus",
		field2 = "#" + user + "_cps",
		bonus = $( field ),
		date = $( "#datepicker" );

	function checkRegexp( o, regexp, n ) {
		if ( !( regexp.test( o.val() ) ) ) {
			alert( n );
			o.val("");
			return false;
		}
		else {
			return true;
		}
	}

	bValid = checkRegexp( bonus, /^([0-9.])+$/, "'" + bonus.val() + "' is not a valid input" );
	
	if (bValid)
	{
		$.get("payable_process.php", { method: "bonus", date: date.val(), user: user, bonus: bonus.val() }, function (data) { 
			$( field2 ).html(data);
		});
	}
}
</script>

<div id="display">
<script>
$( "#display" ).load('payable_display.php?centre=Centre&centres=<?php echo $centres_link; ?>&date=<?php echo date("Y-m-d", strtotime(date("Y")."W".(date("W") - 2)."7")); ?>',
function() {
	$( "#display" ).show('blind', '', 'slow');
});
</script>
</div>

<?php
include "../source/footer.php";
?>