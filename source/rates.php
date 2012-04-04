<script> //PSTN
$(function() {
	$( "#dialog:ui-dialog" ).dialog( "destroy" );

	$( "#dialog-confirm" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:450,
		height:350,
		modal: true,
		buttons: {
			"Close": function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
</script>
<script> //ADSL
$(function() {
	$( "#dialog:ui-dialog2" ).dialog( "destroy" );

	$( "#dialog-confirm2" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:450,
		height:320,
		modal: true,
		buttons: {
			"Close": function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
</script>
<script> //Bundle
$(function() {
	$( "#dialog:ui-dialog3" ).dialog( "destroy" );

	$( "#dialog-confirm3" ).dialog({
		autoOpen: false,
		resizable: false,
		draggable: false,
		width:450,
		height:420,
		modal: true,
		buttons: {
			"Close": function() {
				$( this ).dialog( "close" );
			}
		}
	});
});
</script>
<script> //Display plan rates
function PSTN(plan,fee,contract,local,national,mobile,etf)
{
	$('.plan').html(plan);
	$('.fee').html(fee);
	$('.contract').html(contract);
	$('.local').html(local);
	$('.national').html(national);
	$('.mobile').html(mobile);
	$('.etf').html(etf);
	$( "#dialog-confirm" ).dialog( "open" );
}
function ADSL(plan,fee,contract,setup,data,etf)
{
	$('.plan').html(plan);
	$('.fee').html(fee);
	$('.contract').html(contract);
	$('.setup').html(setup);
	$('.data').html(data);
	$('.etf').html(etf);
	$( "#dialog-confirm2" ).dialog( "open" );
}
function Bundle(plan,fee,contract,setup,local,national,mobile,data,etf)
{
	$('.plan').html(plan);
	$('.fee').html(fee);
	$('.contract').html(contract);
	$('.setup').html(setup);
	$('.local').html(local);
	$('.national').html(national);
	$('.mobile').html(mobile);
	$('.data').html(data);
	$('.etf').html(etf);
	$( "#dialog-confirm3" ).dialog( "open" );
}
</script>
<style>
.rates_btn
{
	background-image:url('../images/plan_btn_bg.png');
	background-repeat:no-repeat;
	height:76px;
	width:132px;
	display:block;
	border:none;
	background-color:transparent;
	color:#fff;
	font-family:Tahoma, Geneva, sans-serif;
	font-size:24px;
}

.rates_btn:hover
{
	background-image:url('../images/plan_btn_hover_bg.png');
	cursor:pointer;
}
</style>

<div style="display:none;">
<img src="../images/plan_btn_hover_bg.png">
</div>

<div id="dialog-confirm" title="Plan Rates">
	<p class="rates">
		<h1 class="plan"></h1>
        <img src="../images/line.png" width="420" height="9" alt="line" />
        <table width="420" cellspacing="0" cellpadding="5">
        <tr>
        <td width="60" height="35"><div align="center"><strong><img src="../images/planfee_icon.jpg" width="23" height="23" /></strong></div></td>
        <td width="150" height="35"><b>Plan Fee</b></td>
        <td width="210" height="35"><p class="fee"></p></td>
        </tr>
        
        <tr bgcolor="#EFEFEF">
        <td height="35"><div align="center"><img src="../images/contract_icon_orange.png" width="32" height="33" /></div></td>
        <td width="150" height="35"><b>Contract Length</b></td>
        <td width="210" height="35"><p class="contract"></p></td>
        </tr>
        
        <tr>
        <td height="35"><div align="center"><img src="../images/localcalls_icon_transparent.png" width="32" height="33" /></div></td>
        <td width="150" height="35"><b>Local Calls</b></td>
        <td width="210" height="35"><p class="local"></p></td>
        </tr>
        
        <tr bgcolor="#EFEFEF">
        <td height="35"><div align="center"><img src="../images/localcalls_icon_transparent.png" width="32" height="33" /></div></td>
        <td width="150" height="35"><b>National Calls</b></td>
        <td width="210" height="35"><p class="national"></p></td>
        </tr>
        
        <tr>
        <td height="35"><div align="center"><img src="../images/mobilecappedcalls_icon_oran.png" width="32" height="33" /></div></td>
        <td width="150" height="35"><b>Mobile Calls</b></td>
        <td width="210" height="35"><p class="mobile"></p></td>
        </tr>
        
        <tr bgcolor="#EFEFEF">
        <td height="35"><div align="center"><img src="../images/businesshours_icon_tranpare.png" alt="img" width="32" height="33" /></div></td>
        <td width="150" height="35"><b>Early Termination Fee</b></td>
        <td width="210" height="35"><p class="etf"></p></td>
        </tr>
        </table>
    </p>
</div>

<div id="dialog-confirm2" title="Plan Rates">
	<p class="rates">
		<h1 class="plan"></h1>
        <img src="../images/line.png" width="420" height="9" alt="line" />
        <table width="420" cellspacing="0" cellpadding="5">
        <tr>
        <td width="60" height="35"><div align="center"><strong><img src="../images/planfee_icon.jpg" width="23" height="23" /></strong></div></td>
        <td width="150" height="35"><b>Plan Fee</b></td>
        <td width="210" height="35"><p class="fee"></p></td>
        </tr>
        
        <tr bgcolor="#EFEFEF">
        <td height="35"><div align="center"><img src="../images/contract_icon_orange.png" width="32" height="33" /></div></td>
        <td width="150" height="35"><b>Contract Length</b></td>
        <td width="210" height="35"><p class="contract"></p></td>
        </tr>
        
        <tr>
        <td height="35"><div align="center"><img src="../images/data_icon.png" width="32" height="33" /></div></td>
        <td width="150" height="35"><b>Setup Fee</b></td>
        <td width="210" height="35"><p class="setup"></p></td>
        </tr>
        
        <tr bgcolor="#EFEFEF">
        <td height="35"><div align="center"><img src="../images/download_icon.png" width="30" height="31" /></div></td>
        <td width="150" height="35"><b>Internet Data</b></td>
        <td width="210" height="35"><p class="data"></p></td>
        </tr>
        
        <tr>
        <td height="35"><div align="center"><img src="../images/businesshours_icon_tranpare.png" alt="img" width="32" height="33" /></div></td>
        <td width="150" height="35"><b>Early Termination Fee</b></td>
        <td width="210" height="35"><p class="etf"></p></td>
        </tr>
        </table>
    </p>
</div>

<div id="dialog-confirm3" title="Plan Rates">
	<p class="rates">
		<h1 class="plan"></h1>
        <img src="../images/line.png" width="420" height="9" alt="line" />
        <table width="420" cellspacing="0" cellpadding="5">
        <tr>
        <td width="60" height="35"><div align="center"><strong><img src="../images/planfee_icon.jpg" width="23" height="23" /></strong></div></td>
        <td width="150" height="35"><b>Plan Fee</b></td>
        <td width="210" height="35"><p class="fee"></p></td>
        </tr>
        
        <tr bgcolor="#EFEFEF">
        <td height="35"><div align="center"><img src="../images/contract_icon_orange.png" width="32" height="33" /></div></td>
        <td width="150" height="35"><b>Contract Length</b></td>
        <td width="210" height="35"><p class="contract"></p></td>
        </tr>
        
        <tr>
        <td height="35"><div align="center"><img src="../images/data_icon.png" width="32" height="33" /></div></td>
        <td width="150" height="35"><b>Setup Fee</b></td>
        <td width="210" height="35"><p class="setup"></p></td>
        </tr>
        
        <tr bgcolor="#EFEFEF">
        <td height="35"><div align="center"><img src="../images/localcalls_icon_transparent.png" width="32" height="33" /></div></td>
        <td width="150" height="35"><b>Local Calls</b></td>
        <td width="210" height="35"><p class="local"></p></td>
        </tr>
        
        <tr>
        <td height="35"><div align="center"><img src="../images/localcalls_icon_transparent.png" width="32" height="33" /></div></td>
        <td width="150" height="35"><b>National Calls</b></td>
        <td width="210" height="35"><p class="national"></p></td>
        </tr>
        
        <tr bgcolor="#EFEFEF">
        <td height="35"><div align="center"><img src="../images/mobilecappedcalls_icon_oran.png" width="32" height="33" /></div></td>
        <td width="150" height="35"><b>Mobile Calls</b></td>
        <td width="210" height="35"><p class="mobile"></p></td>
        </tr>
        
        <tr>
        <td height="35"><div align="center"><img src="../images/download_icon.png" width="30" height="31" /></div></td>
        <td width="150" height="35"><b>Internet Data</b></td>
        <td width="210" height="35"><p class="data"></p></td>
        </tr>
        
        <tr bgcolor="#EFEFEF">
        <td height="35"><div align="center"><img src="../images/businesshours_icon_tranpare.png" alt="img" width="32" height="33" /></div></td>
        <td width="150" height="35"><b>Early Termination Fee</b></td>
        <td width="210" height="35"><p class="etf"></p></td>
        </tr>
        </table>
    </p>
</div>

<p><img src="../images/pstn_rates_header.png" width="120" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p>

<table>
<tr>
<td><input type="button" onClick="PSTN('PSTN $49.95','$49.95 per month (Inc GST)','24 Month Contract','Included','99c per call','99c per call','$199 (No Risk Offer)')" class="rates_btn" value="$49.95"></td>
<td><input type="button" onClick="PSTN('PSTN $54.95','$54.95 per month (Inc GST)','No Contract','Included','99c per call','99c per call','No ETF')" class="rates_btn" value="$54.95"></td>
<td><input type="button" onClick="PSTN('PSTN $59.95','$59.95 per month (Inc GST)','24 Month Contract','Included','Included','99c per call','$199 (No Risk Offer)')" class="rates_btn" value="$59.95"></td>
<td><input type="button" onClick="PSTN('PSTN $64.95','$64.95 per month (Inc GST)','12 Month Contract','Included','Included','99c per call','$199 (No Risk Offer)')" class="rates_btn" value="$64.95"></td>
<td><input type="button" onClick="PSTN('PSTN $69.95','$69.95 per month (Inc GST)','No Contract','Included','Included','99c per call','No ETF')" class="rates_btn" value="$69.95"></td>
</tr>
<tr>

<td><input type="button" onClick="PSTN('PSTN $79.95','$79.95 per month (Inc GST)','No Contract','Included','Included','20c per call','No ETF')" class="rates_btn" value="$79.95"></td>
<td><input type="button" onClick="PSTN('PSTN $99.95','$99.95 per month (Inc GST)','24 Month Contract','Included','Included','Included','$199 (No Risk Offer)')" class="rates_btn" value="$99.95"></td>
<td><input type="button" onClick="PSTN('PSTN $104.95','$104.95 per month (Inc GST)','12 Month Contract','Included','Included','Included','$199 (No Risk Offer)')" class="rates_btn" value="$104.95"></td>
<td><input type="button" onClick="PSTN('PSTN $109.95','$109.95 per month (Inc GST)','No Contract','Included','Included','Included','No ETF')" class="rates_btn" value="$109.95"></td>

<td><input type="button" onClick="PSTN('PSTN $39.95','$39.95 per month (Inc GST)','Inhertits from main plan','20c per call','20c per call with a 39c flagfall and capped at $1.10 for 30 minutes','33c per call with a 39c flagfall and capped at $1.35 for 10 minutes','Inhertits from main plan')" class="rates_btn" value="Addon"></td>
</tr>
</table>
<br>

<p><img src="../images/adsl_rates_header.png" width="120" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p>

<table>
<tr>
<td><input type="button" onClick="ADSL('ADSL 15GB','Metro: $54.95 per month (Inc GST)<br>Regional: $64.95 per month (Inc GST)','24 Month Contract','Free','15GB','$199')" class="rates_btn" value="15GB"></td>
<td><input type="button" onClick="ADSL('ADSL Unlimited','Metro: $69.95 per month (Inc GST)<br>Regional: $79.95 per month (Inc GST)','24 Month Contract','Free','Unlimited','$199')" class="rates_btn" value="Unlimited"></td>
</tr>
</table>
<br>

<p><img src="../images/bundle_rates_header.png" width="135" height="25" /></p>
<p><img src="../images/line.png" width="740" height="9" /></p>

<table>
<tr>
<td><input type="button" onClick="Bundle('Bundle $84.95','$84.95 per month (Inc GST)','24 Month Contract','Free','29c per call','99c per call','99c per call','15GB','$199 per service')" class="rates_btn" value="$84.95"></td>
<td><input type="button" onClick="Bundle('Bundle $99.95','$99.95 per month (Inc GST)','24 Month Contract','Free','29c per call','99c per call','99c per call','Unlimited','$199 per service')" class="rates_btn" value="$99.95"></td>
<td><input type="button" onClick="Bundle('Bundle $114.95','$114.95 per month (Inc GST)','24 Month Contract','Free','Included','99c per call','99c per call','15GB','$199 per service')" class="rates_btn" value="$114.95"></td>
<td><input type="button" onClick="Bundle('Bundle $124.95','$124.95 per month (Inc GST)','24 Month Contract','Free','Included','99c per call','99c per call','Unlimited','$199 per service')" class="rates_btn" value="$124.95"></td>
</tr>
<tr>
<td><input type="button" onClick="Bundle('Bundle $119.95','$119.95 per month (Inc GST)','24 Month Contract','Free','Included','Included','99c per call','15GB','$199 per service')" class="rates_btn" value="$119.95"></td>
<td><input type="button" onClick="Bundle('Bundle $129.95','$129.95 per month (Inc GST)','24 Month Contract','Free','Included','Included','99c per call','500GB','$199 per service')" class="rates_btn" value="$129.95"></td>
<td><input type="button" onClick="Bundle('Bundle $134.95','$134.95 per month (Inc GST)','24 Month Contract','Free','Included','Included','99c per call','Unlimited','$199 per service')" class="rates_btn" value="$134.95"></td>
<td><input type="button" onClick="Bundle('Bundle $149.95','$149.95 per month (Inc GST)','24 Month Contract','Free','Included','Included','Included','Unlimited','$199 per service')" class="rates_btn" value="$149.95"></td>
</tr>
</table>
<br>