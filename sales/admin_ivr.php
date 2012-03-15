<script>
function Check_IVR()
{
	var id = $( "#sale_id" ),
		tips = $( ".ivr_result" );
		
	$.get("ivr_check.php", { sale_id: id.val() },
	function(data) {
		tips.html(data);
	});
}
</script>
<p><img src="../images/ivr_check_header.png" width="100" height="25" style="margin-left:3px;" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p><br />

<form onsubmit="event.preventDefault()">
Sale ID&nbsp;&nbsp; <input type="text" size="15" id="sale_id" value="" /> <input type="submit" onclick="Check_IVR()" value="Check" />
</form><br>
<p>Result: <span class="ivr_result"></span></p>