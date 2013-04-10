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
					response( data );
				},
				complete: function() {
					$( "#address_input" ).removeClass("ui-autocomplete-loading");
				}
			});
		},
		response: function( event, ui ) {
			if (ui.content.length === 0) {
				
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
<td><button onclick='Edit_Address_Manual("<?php echo $type; ?>")' class="btn">Manual</button></td>
<td align="right"><button onclick="Edit_Address_Submit()" class="btn">Submit</button> <button onclick="Edit_Address_Cancel()" class="btn">Cancel</button></td>
</tr>
</table>
<?php
}
elseif ($method == "manual")
{
?>
<style>
.ui-combobox {
	position: relative; display: inline-block; margin-left: -2px;
}
</style>
<script>
(function( $ ) {
	$.widget( "ui.combobox", {
		_create: function() {
			this.wrapper = $( "<span>" )
				.addClass( "ui-combobox" )
				.insertAfter( this.element );
			this._createAutocomplete();
		},
		_createAutocomplete: function() {
			var selected = this.element.children( ":selected" ),
				value = selected.val() ? selected.text() : "",
				input_width = this.element.outerWidth();
			this.input = $( "<input>" )
				.appendTo( this.wrapper )
				.val( value )
				.attr( "title", "" )
				.attr( "type", "text" )
				.attr( "style", "width:" + input_width + "px;")
				.autocomplete({
					delay: 0,
					minLength: 0,
					source: $.proxy( this, "_source" )
				})
				.tooltip({
					tooltipClass: "ui-state-highlight"
				});
			this._on( this.input, {
				autocompleteselect: function( event, ui ) {
					ui.item.option.selected = true;
					this._trigger( "select", event, {
						item: ui.item.option
					});
				},
				autocompletechange: "_removeIfInvalid"
			});
		},
		_source: function( request, response ) {
			var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
			response( this.element.children( "option" ).map(function() {
			var text = $( this ).text();
			var value = $( this ).val();
			if ( this.value && ( !request.term || matcher.test(text) ) )
				return {
					label: text,
					value: value,
					option: this
				};
			}) );
		},
		_removeIfInvalid: function( event, ui ) {
			// Selected an item, nothing to do
			if ( ui.item ) {
				return;
			}
			// Search for a match (case-insensitive)
			var value = this.input.val(),
				valueLowerCase = value.toLowerCase(),
				valid = false;
			this.element.children( "option" ).each(function() {
				if ( $( this ).text().toLowerCase() === valueLowerCase ) {
					this.selected = valid = true;
					return false;
				}
			});
			// Found a match, nothing to do
			if ( valid ) {
				return;
			}
			// Remove invalid value
			this.input
				.val( "" )
				.attr( "title", value + " didn't match any item" )
				.tooltip( "open" );
			this.element.val( "" );
			this._delay(function() {
				this.input.tooltip( "close" ).attr( "title", "" );
			}, 2500 );
			this.input.data( "ui-autocomplete" ).term = "";
			Manual_Format();
		},
		_destroy: function() {
			this.wrapper.remove();
			this.element.show();
		}
	});
})( jQuery );
</script>
<script>
$(function() {
	$( "#street_type" ).combobox();
	$( "#street_type_suffix" ).combobox();
});

$(function() {
	$( "#suburb_town" ).autocomplete({
		source: function( request, response ) {
			$.ajax({
				url: "/source/tcGet.php",
				dataType: "json",
				type: "POST",
				data: {
					method: "suburb_town",
					input: request.term
				},
				success: function( data ) {
					response( data );
				},
				complete: function() {
					$( "#suburb_town" ).removeClass("ui-autocomplete-loading");
				}
			});
		},
		response: function( event, ui ) {
			if (ui.content.length === 0) {
				
            }
		},
		minLength: 4,
		delay: 200,
		select: function( event, ui ) {
			$( "#suburb_town" ).val(ui.item.suburb_town);
			$( "#state" ).val(ui.item.state);
			$( "#postcode" ).val(ui.item.postcode);
			Manual_Format();
		}
	});
});
</script>
<input type="hidden" id="address_type" value="<?php echo $type; ?>" />
<input type="hidden" id="address_method" value="manual" />
<input type="hidden" id="dpid" value="" />
<input type="hidden" id="barcode" value="" />
<input type="hidden" id="formattedAddress" value="" />
<table>
<tr>
<td colspan="2" id="addess_edit_error"><p>Enter the address below to store it manually.</p></td>
</tr>
<tr>
<td colspan="2"><input type="text" disabled="disabled" style="width:400px;" placeholder="Enter Address Manually Below" /></td>
</tr>
<tr>
<td width="125px"><b>Building Name </b></td>
<td><input type="text" id="building_name" /></td>
</tr>
<tr>
<td><b>Sub Premise </b></td>
<td><input type="text" id="sub_premise" /></td>
</tr>
<tr>
<td><b>Street Number </b></td>
<td><input type="text" id="street_number" style="width:60px;" /></td>
</tr>
<tr>
<td><b>Street Name </b></td>
<td><input type="text" id="street_name" /></td>
</tr>
<tr>
<td><b>Street Type </b></td>
<td>
<table width="100%">
<tr>
<td width="150px" style="padding:0;"><select id="street_type" style="width:80px; display:none;">
<option></option>
<?php
$q = $mysqli->query("SELECT * FROM `aut`.`street_type`") or die($mysqli->error);
while ($data = $q->fetch_assoc())
{
	echo "<option value='" . $data["name"] . "'>" . $data["name"] . "</option>";
}
$q->free();
?>
</select></td>
<td width="50px" style="padding:0;"><b>Suffix </b></td>
<td style="padding:0;"><select id="street_type_suffix" style="width:44px; display:none;">
<option></option>
<?php
$q = $mysqli->query("SELECT * FROM `aut`.`street_type_suffix`") or die($mysqli->error);
while ($data = $q->fetch_assoc())
{
	echo "<option value='" . $data["code"] . "'>" . $data["name"] . "</option>";
}
$q->free();
?>
</select></td>
</tr>
</table>
</td>
</tr>
<tr>
<td><b>Suburb / Town </b></td>
<td><input type="text" id="suburb_town" /></td>
</tr>
<tr>
<td><b>State </b></td>
<td><select id="state" style="width:72px;">
<option></option>
<option>ACT</option>
<option>NSW</option>
<option>NT</option>
<option>QLD</option>
<option>SA</option>
<option>TAS</option>
<option>VIC</option>
<option>WA</option>
</select></td>
</tr>
<tr>
<td><b>Postcode </b></td>
<td><input type="text" id="postcode" style="width:50px;" /></td>
</tr>
<tr>
<td><button onclick='Edit_Address("<?php echo $type; ?>")' class="btn">Auto</button></td>
<td align="right"><button onclick="Edit_Address_Submit()" class="btn">Submit</button> <button onclick="Edit_Address_Cancel()" class="btn">Cancel</button></td>
</tr>
</table>
<?php
$mysqli->close();
}
?>