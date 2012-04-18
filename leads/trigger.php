<?php
if (file_exists("/home/leads/lock.txt"))
{
	echo "Currently uploading leads. Please wait";
}
elseif (file_exists("/home/leads/trigger/go.txt"))
{
	echo "Trigger file already created!";
}
elseif (!file_exists("/home/leads/upload/leads/leads.csv"))
{
	echo "The file 'leads.csv' is not on the FTP!";
}
else
{
	exec("touch /home/leads/trigger/go.txt");
	echo "valid";
}
?>