<?php
mysql_connect('localhost','vericon','18450be');

$id = $_GET["lead"];

if (preg_match('/0([2378])([0-9]{8})/',$id))
{
	$id = substr($id,1,9);
	
	$q = mysql_query("SELECT * FROM leads.leads WHERE cli = '$id'") or die(mysql_error());
	$data = mysql_fetch_assoc($q);
	
	if (mysql_num_rows($q) == 0)
	{
		$result = "Not Found";
	}
	elseif (strtotime($data["expiry_date"]) >= strtotime(date("Y-m-d")))
	{
		$result = "Available";
	}
	elseif (strtotime($data["expiry_date"]) < strtotime(date("Y-m-d")))
	{
		$result = "Expired";
	}
}
elseif (preg_match('/([2378])([0-9]{8})/',$id))
{
	$q = mysql_query("SELECT * FROM leads.leads WHERE cli = '$id'") or die(mysql_error());
	$data = mysql_fetch_assoc($q);
	
	if (mysql_num_rows($q) == 0)
	{
		$result = "Not Found";
	}
	elseif (strtotime($data["expiry_date"]) >= strtotime(date("Y-m-d")))
	{
		$result = "Available";
	}
	elseif (strtotime($data["expiry_date"]) < strtotime(date("Y-m-d")))
	{
		$result = "Expired";
	}
}
else
{
	$result = "Invalid Lead ID";
}

?>

<table>
<tr>
<td width="50px"><b>Result: </b></td>
<td><?php echo $result; ?></td>
</tr>
<tr>
<td width="50px"><b>Centre: </b></td>
<td><?php echo $data["centre"]; ?></td>
</tr>
<tr>
<td width="50px"><b>Issued: </b></td>
<td><?php echo $data["issue_date"]; ?></td>
</tr>
<tr>
<td width="50px"><b>Expiry: </b></td>
<td><?php echo $data["expiry_date"]; ?></td>
</tr>
</table>