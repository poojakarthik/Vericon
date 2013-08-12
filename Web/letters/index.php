<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>VeriCon :: Letters</title>
<script>
function Download(file, filename)
{
	window.location = "download.php?file=" + file + "&filename=" + filename;
}
</script>
<style>
a {
	color: blue;
	text-decoration: underline;
	cursor: pointer;
}
</style>
</head>

<body>
<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

function CheckAccess()
{
	$q = mysql_query("SELECT * FROM allowedip") or die(mysql_error());
	
	while ($iplist = mysql_fetch_assoc($q))
	{
		$allowedip[$iplist['IP']] = $iplist['status'];
	}
  	$ip = $_SERVER['REMOTE_ADDR'];
	return ($allowedip[$ip]);
}

if (!CheckAccess())
{
	header("Location: ../index.php");
	exit;
}

$id = $_POST["id"];

echo '<form method="post">
<input name="id" value="" placeholder="Transaction Number" />
<button type="submit">Submit</button>
</form>';

if ($id != "")
{
	$q = mysql_query("SELECT `wl_date`, `file_name` FROM `letters`.`log` WHERE `id` = '" . mysql_real_escape_string($id) . "'") or die(mysql_error());
	
	if (mysql_num_rows($q) == 0)
	{
		echo "<br>Incorrect Transaction Number or Letter Does Not Exist";
	}
	else
	{
		$i = 1;
		while ($file = mysql_fetch_row($q))
		{
			$filename = "WL_" . $id . "_" . $file[0] . ".pdf";
			
			echo "<br>" . $i . " - <a onclick='Download(\"$file[1]\",\"$filename\")'>" . $filename . "</a>";
			$i++;
		}
	}
}
?>
</body>
</html>