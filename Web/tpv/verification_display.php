<?php
mysql_connect('localhost','vericon','18450be');

$q = mysql_query("SELECT user FROM vericon.currentuser WHERE hash = '" . $_COOKIE["hash"] . "'") or die(mysql_error());
$user = mysql_fetch_row($q);

$q1 = mysql_query("SELECT * FROM vericon.auth WHERE user = '$user[0]'") or die(mysql_error());
$ac = mysql_fetch_assoc($q1);
?>
<script> //init form
function Get_Sale()
{
	var id = $( "#id" ),
		user = "<?php echo $ac["user"]; ?>";
	
	$.get("verification_submit.php?method=get", { id: id.val(), user: user }, function(data) {
		d = data.split("_");
		if (d[0] == "valid")
		{
			$( "#display" ).hide('blind', '' , 'slow', function() {
				$( "#display" ).load('verification_dash_' + d[1] + '.php?id=' + id.val(), function() {
					$( "#packages" ).load('packages_' + d[1] + '.php?id=' + id.val(), function() {
						$( "#display" ).show('blind', '' , 'slow');
					});
				});
			});
		}
		else
		{
			$( ".error" ).html(data);
		}
	});
}
</script>

<div id="get_sale_table" style="margin-top:75px;">
<form onsubmit="event.preventDefault()">
<table>
<tr>
<td><p>Enter the Customer's Sale ID</p></td>
<td><input type="text" id="id" size="25" autocomplete="off" /></td>
<td><button type="submit" class="get_sale_btn" onclick="Get_Sale()"></button></td>
</tr>
</table>
</form>
<center><p class="error" style="color:#C00;"></p></center>
</div>