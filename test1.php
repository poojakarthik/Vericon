<?php
$planc = "Bundle $89.95 No Contract";

if (preg_match("/24 Month Contract/", $planc))
{
	echo 24;
}
elseif (preg_match("/12 Month Contract/", $planc))
{
	echo 12;
}
else
{
	echo 0;
}

?>