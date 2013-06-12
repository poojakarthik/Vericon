<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$q = mysql_query("SELECT * FROM international ORDER BY country ASC") or die(mysql_error());
?>
<style>
.international_table{
	font-family:Calibri;
	font-size:18px;
	color:#36C;
	padding:10px 0 0 10px;
	}
.style3 {
	font-size: 16px;
	font-family: Arial, Helvetica, sans-serif;
	color: #333333;
}
.style4 {
	font-size: 12px;
	font-family: Arial, Helvetica, sans-serif;
	color:#999999;
}
.style5 {
	font-size: 12px;
	font-family:Calibri;
	font:bold 12px;
	color:#666666;
}
.style6 {
	font-size: 14px;
	font-family: Arial, Helvetica, sans-serif;
	color:#666666;
}
</style>
<div class="container" style="overflow:hidden;">
    <div class="contents">
	<script type="text/javascript" language="javascript">

function _rate (place,rate,OpRate,TelRate,prefix) {
	var h = document.getElementById(prefix+'_h3');
	var c = document.getElementById(prefix);
	
	h.innerHTML = place;
	c.innerHTML = rate+' per minute';
	
	if(OpRate && TelRate){ 
		
		var e = document.getElementById(prefix+'w2');
		var f = document.getElementById(prefix+'w3');
		
		e.innerHTML = 'Optus W.S <br/>'+ OpRate;
		f.innerHTML = 'Telstra W.S <br/>'+ TelRate;
	}
}
function setRate (s,prefix) {
	var e = s.value.split(',');
	_rate(e[0],e[1],e[2],e[3],prefix);
}
</script>
       <table width="600" border="0" cellspacing="0" cellpadding="0" align="left">
         <tr>
           <th width="300" align="left" valign="top" scope="row"><select name="select2" size="14" style="width: 240px; font-family:Calibri; font-size:12px; height:150px;" onchange="setRate(this,'iddd');">
<?php
while ($int = mysql_fetch_assoc($q))
{
	echo '<option value="' . $int["country"] . ',' . $int["rate"] . '">' . $int["country"] . '</option>';
}
?>
</select></th>
           <th align="left" valign="top" scope="row"><h3 class="style3" id="idd_h3">Select a Destination<br />
             <span class="style4">All rates are Inc. GST </span></h3>
        <h3 id="idd" class="style4"></h3><br />
        
        <h3 class="style6" id="iddd_h3"></h3>
        <h3 id="iddd" class="style6"></h3></th>
         </tr>
         <tr>
           <th align="left" valign="top" scope="row" class="style5">International flag fall 39c</th>
           <th align="left" valign="top" scope="row">&nbsp;</th>
         </tr>
       </table>
  </div>
</div>