<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$id = $_GET["id"];

$q = mysql_query("SELECT link FROM tutorials WHERE id = '$id'") or die(mysql_error());
$link = mysql_fetch_row($q);

if (mysql_num_rows($q) == 0)
{
	echo "<h1><center>Video Not Found!</center></h1>";
}
else
{
?>

<script src="../js/standard.js" type="text/javascript"></script>
<center>
	<div id="CaptivateContent" style="border:none;">&nbsp;
	</div>
	<script type="text/javascript">
	   var so = new SWFObject("<?php echo $link[0]; ?>", "Captivate", "1025", "800", "10", "#CCCCCC");
		so.addParam("quality", "high");
		so.addParam("name", "Captivate");
		so.addParam("id", "Captivate");
		so.addParam("wmode", "window");
		so.addParam("bgcolor","#f5f4f1");
		so.addParam("menu", "false");
		so.addVariable("variable1", "value1");
		so.setAttribute("redirectUrl", "http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash");
		so.write("CaptivateContent");
	</script>
</center>
<?php
}
?>