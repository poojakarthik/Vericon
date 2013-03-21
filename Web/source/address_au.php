<?php
include("../auth/restrict_inner.php");

$method = $_POST["method"];
$type = $_POST["type"];

if ($method == "auto")
{
?>
<script>
$(function() {
	$( "#address_input" ).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url: "/source/tcGet.php",
				dataType: "json",
				type: "POST",
				data: {
					method: "search",
					input: request.term
				},
				success: function( data ) {
					response(data);
				},
				complete: function() {
					$( "#address_input" ).removeClass("ui-autocomplete-loading");
				}
			});
		},
		response: function( event, ui ) {
			if (ui.content.length === 0) {
                alert("No results found");
            }
		},
		minLength: 4,
		delay: 200,
		select: function( event, ui ) {
			V_Loading_Start();
			$.post("/source/tcGet.php", { method: "select", input: ui.item.search, index: ui.item.id, postal: ui.item.postal, whitepages: ui.item.whitepages }, function(data) {
				$( "#dpid" ).val(data.dpid);
				$( "#barcode" ).val(data.barcode);
				$( "#formattedAddress" ).val(data.formattedAddress);
				$( "#building_name" ).html(data.buildingName);
				$( "#sub_premise" ).html(data.subPremise);
				$( "#street_number" ).html(data.streetNumber);
				$( "#street_name" ).html(data.streetName);
				$( "#street_type" ).html(data.streetType);
				$( "#street_type_suffix" ).html(data.streetSuffix);
				$( "#suburb_town" ).html(data.suburb);
				$( "#state" ).html(data.state);
				$( "#postcode" ).html(data.postcode);
				V_Loading_End();
			}).error( function(xhr, text, err) {
				$(".loading_message").html("<p><b>An error occurred while performing this action.</b></p><p><b>Error: " + xhr.status + " " + xhr.statusText + "</b></p>");
				setTimeout(function() {
					V_Loading_End();
				}, 2500);
			});
		}
	});
});
</script>
<input type="hidden" id="address_type" value="<?php echo $type; ?>" />
<input type="hidden" id="address_method" value="auto" />
<input type="hidden" id="dpid" value="" />
<input type="hidden" id="barcode" value="" />
<input type="hidden" id="formattedAddress" value="" />
<table>
<tr>
<td colspan="2" id="addess_edit_error"><p>Enter the address below to begin the search.</p></td>
</tr>
<tr>
<td colspan="2"><input type="text" id="address_input" style="width:400px;" placeholder="Enter Address Here" /></td>
</tr>
<tr>
<td width="125px"><b>Building Name </b></td>
<td><span id="building_name"></span></td>
</tr>
<tr>
<td><b>Sub Premise </b></td>
<td><span id="sub_premise"></span></td>
</tr>
<tr>
<td><b>Street Number </b></td>
<td><span id="street_number"></span></td>
</tr>
<tr>
<td><b>Street Name </b></td>
<td><span id="street_name"></span></td>
</tr>
<tr>
<td><b>Street Type </b></td>
<td>
<table width="100%">
<tr>
<td width="150px" style="padding:0;"><span id="street_type"></span></td>
<td width="50px" style="padding:0;"><b>Suffix </b></td>
<td style="padding:0;"><span id="street_type_suffix"></span></td>
</tr>
</table>
</td>
</tr>
<tr>
<td><b>Suburb / Town </b></td>
<td><span id="suburb_town"></span></td>
</tr>
<tr>
<td><b>State </b></td>
<td><span id="state"></span></td>
</tr>
<tr>
<td><b>Postcode </b></td>
<td><span id="postcode"></span></td>
</tr>
<tr>
<td colspan="2" align="right"><button onclick="Edit_Address_Submit()" class="btn">Submit</button> <button onclick="Edit_Address_Cancel()" class="btn">Cancel</button></td>
</tr>
</table>
<?php
}
?>