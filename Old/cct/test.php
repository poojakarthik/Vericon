<pre>
<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$q = mysql_query("SELECT * FROM sales_customers WHERE status = 'Approved' AND DATE(approved_timestamp) < '2012-04-01' ORDER BY approved_timestamp ASC") or die(mysql_error());
while ($data = mysql_fetch_assoc($q))
{
	echo $data["id"] . "\t";
	echo $data["centre"] . "\t";
	echo date("Y-m-d", strtotime($data["approved_timestamp"])) . "\t";
	if (file_exists("/home/odai/rec2/$data[lead_id].gsm"))
	{
		echo "$data[lead_id].gsm Exists\n";
		$exists++;
	}
	else
	{
		echo "$data[lead_id].gsm Does Not Exist\n";
		$doesnt_exist++;
	}
}
?>
</pre>
<?php
echo "<b>" . $exists . " Recordings Exist<br>";
echo $doesnt_exist . " Recordings Don't Exist</b>";
?>