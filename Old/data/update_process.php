<?php
mysql_connect('localhost','vericon','18450be');
$file = $_GET["file"];

$path = "/var/vtmp/" . $file["name"];
$timestamp = date("Y-m-d H:i:s");

exec("dos2unix " . $path);

$lines = file($path);
$i = 0;

foreach ($lines as $row)
{
	$data = explode(",", $row);
	if ($i > 0)
	{
		$q = mysql_query("SELECT * FROM vericon.customers WHERE id = '$data[0]'") or die(mysql_error());
		$da = mysql_fetch_assoc($q);
		
		if ($da["sf_id"] != $data[1])
		{
			mysql_query("UPDATE vericon.customers SET sf_id = '" . mysql_real_escape_string($data[1]) . "', last_edit_timestamp = last_edit_timestamp WHERE id = '$data[0]'") or die(mysql_error());
		}
		
		if ($da["tb_id"] != $data[2])
		{
			mysql_query("UPDATE vericon.customers SET tb_id = '" . mysql_real_escape_string($data[2]) . "', last_edit_timestamp = last_edit_timestamp WHERE id = '$data[0]'") or die(mysql_error());
		}
		
		if ($da["status"] != $data[3])
		{
			mysql_query("UPDATE vericon.customers SET status = '" . mysql_real_escape_string($data[3]) . "', last_edit_timestamp = last_edit_timestamp WHERE id = '$data[0]'") or die(mysql_error());
			
			if ($data[3] == "Cancelled")
			{
				mysql_query("INSERT INTO vericon.customers_notes (id, user, timestamp, type, note) VALUES ('$data[0]', 'onaj001', '$timestamp', 'Cancelled', '" . mysql_real_escape_string($data[4]) . "')") or die(mysql_error());
			}
		}
	}
	$i++;
}

unlink($path);

echo "done";
?>