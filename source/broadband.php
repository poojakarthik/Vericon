<?php
mysql_connect('localhost','adsl','18450be');
mysql_select_db('adsl');

$phone = $_GET["phone"];

$q = mysql_query("SELECT * FROM bigList WHERE `To` >= '$phone' AND `From` <= '$phone' AND NumberLength = CHAR_LENGTH('$phone') LIMIT 1") or die(mysql_error());

if (mysql_num_rows($q) == 0)
{
	echo "Invalid CLI";
	exit;
}

if (preg_match('/0([2378])([0-9]{8})/',$phone,$d))
{
	$q = mysql_query("SELECT * FROM Enabled_Exchanges WHERE Range_From <= $d[2] AND Range_To >= $d[2] AND AC = '$d[1]'") or die(mysql_error());

	if(mysql_num_rows($q) == 0)
	{
		echo "We are unable to provide ADSL at the present time on <b>$phone</b>.";
	}
	else
	{
		$r = mysql_fetch_row($q);
		$t = mysql_num_rows(mysql_query("SELECT * FROM ADSL2P WHERE Exch_ID = '$r[2]'"));
		if($t > 0)
		{
			$net = "ADSL2+";
		}
		else
		{
			$net = "ADSL";
		}
		
		if ($r[8] == "Zone 1")
		{
			$zone = "Metro";
		}
		else
		{
			$zone = "Regional";
		}
		echo "The telephone number you have provided is in the <b>$r[3]</b> exchange and qualifies for an <b>$net</b> internet connection on a <b>$zone</b> plan!";
	}
}

?>