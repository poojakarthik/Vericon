<?php
$date = $_GET["date"];
$date_path = date("Y/F/d.m.Y", strtotime($date));
$dir = "/var/dsr/" . $date_path . "/";
$dh = opendir($dir);
$count = 0;

while ($file = readdir($dh))
{
	if ($file != "." && $file != ".."  && $file != "Update")
	{
		$count++;
		if (filetype($dir . $file) == "dir")
		{
			echo "<b>" . $file . "</b><br>";
			
			$dh2 = opendir($dir . $file . "/");
			while ($file2 = readdir($dh2))
			{
				if ($file2 != "." && $file2 != "..")
				{
					echo "-- <a onClick='Export(\"$file\",\"$file2\")' style='text-decoration:underline; cursor: pointer;'>" . $file2 . "</a><br>";
				}
			}
			closedir($dh2);
		}
	}
}

closedir($dh);

if ($count < 1)
{
	echo "Nothing to Download";
}
?>