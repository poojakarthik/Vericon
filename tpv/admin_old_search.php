<?php
$type = $_GET["type"];
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

if($type == "mytpv")
{
	$cli = $_GET["cli"];
	if (!preg_match("/^0[2378][0-9]{8}$/",$cli))
	{
		echo "Invalid CLI";
		exit;
	}
	$q = mysql_query("SELECT * FROM archive_mytpv_sales WHERE phone1 LIKE '%$cli%' OR phone2 LIKE '%$cli%' OR phone3 LIKE '%$cli%' OR phone4 LIKE '%$cli%'") or die(mysql_error());
	while ($data = mysql_fetch_row($q))
	{
	?>
<table width="100%" style="border-bottom:1px solid black; border-left:1px solid black; border-top:1px solid black; border-right:1px solid black; padding:5px;">
<tr>
<td width="85px">Sale Code</td>
<td><b><?php echo $data[10]; ?></b></td>
</tr>
<tr>
<td>Sale Date</td>
<td><b><?php echo $data[0] . " " . $data[1]; ?></b></td>
</tr>
<tr>
<td>Status</td>
<td><b><?php echo $data[4]; ?></b></td>
</tr>
<tr>
<td>Centre</td>
<td><b><?php echo $data[3]; ?></b></td>
</tr>
<tr>
<td>Campaign</td>
<td><b><?php echo $data[15]; ?></b></td>
</tr>
<tr>
<td>Verifier</td>
<td><b><?php echo $data[2]; ?></b></td>
</tr>
<tr>
<td>Customer Name</td>
<td><b><?php echo $data[5] . " " . $data[6]; ?></b></td>
</tr>
<tr>
<td>Date of Birth</td>
<td><b><?php echo $data[17]; ?></b></td>
</tr>
<tr>
<td>Physical Address</td>
<td><b><?php echo $data[18]; ?></b></td>
</tr>
<tr>
<td>Postal Address</td>
<td><b><?php echo $data[19]; ?></b></td>
</tr>
<tr>
<td>Mobile</td>
<td><b><?php echo $data[20]; ?></b></td>
</tr>
<tr>
<td>Email</td>
<td><b><?php echo $data[21]; ?></b></td>
</tr>
<tr>
<td>ID</td>
<td><b><?php echo $data[22] . " - " . $data[23]; ?></b></td>
</tr>
<tr>
<tr>
<td>Phone 1</td>
<td><b><?php echo $data[11]; ?></b></td>
</tr>
<tr>
<td>Phone 2</td>
<td><b><?php echo $data[12]; ?></b></td>
</tr>
<tr>
<td>Phone 3</td>
<td><b><?php echo $data[13]; ?></b></td>
</tr>
<tr>
<td>Phone 4</td>
<td><b><?php echo $data[14]; ?></b></td>
</tr>
<td>Comments</td>
<td><b><?php echo $data[7]; ?></b></td>
</tr>
</table>
<br>
	<?php
    }
}
elseif ($type == "mcrm")
{
?>
<div id="users-contain" class="ui-widget">
<table id="users" class="ui-widget ui-widget-content" width="100%" style="margin-top:0px;">
<thead>
<tr class="ui-widget-header ">
<th>ID</th>
<th>Status</th>
<th>Campaign</th>
<th>Agent</th>
<th>Sale Date</th>
</tr>
</thead>
<tbody>
<?php
	$method = $_GET["method"];
	$query = $_GET["query"];
	if ($method == "line")
	{
		if (!preg_match("/^0[2378][0-9]{8}$/",$query))
		{
			echo "<tr>";
			echo "<td colspan='5' align='center'>Invalid Phone Number</td>";
			echo "</tr>";
			exit;
		}
		$q = mysql_query("SELECT * FROM archive_mcrm_packages WHERE line = '$query'") or die(mysql_error());
		
		if (mysql_num_rows($q) == 0)
		{
			echo "<tr>";
			echo "<td colspan='5' align='center'>No Results</td>";
			echo "</tr>";
			exit;
		}
		
		while ($id = mysql_fetch_row($q))
		{
			$q2 = mysql_query("SELECT * FROM archive_mcrm_customers WHERE id = '$id[0]'") or die(mysql_error());
			$data = mysql_fetch_assoc($q2);
			
			echo "<tr>";
			echo "<td><a onclick='mcrm_display(\"$data[id]\")' style='cursor:pointer; text-decoration:underline;'>" . $data["id"] . "</a></td>";
			echo "<td>" . $data["status"] . "</td>";
			echo "<td>" . $data["campaign"] . "</td>";
			echo "<td>" . $data["saleAgent"] . "</td>";
			echo "<td>" . date("d/m/Y", strtotime($data["saleTS"])) . "</td>";
			echo "</tr>";
		}
	}
	else
	{
		$q = mysql_query("SELECT * FROM archive_mcrm_customers WHERE id = '$query'") or die(mysql_error());
		$data = mysql_fetch_assoc($q);
		
		if (mysql_num_rows($q) == 0)
		{
			echo "<tr>";
			echo "<td colspan='5' align='center'>No Results</td>";
			echo "</tr>";
			exit;
		}
		
		echo "<tr>";
		echo "<td><a onclick='mcrm_display(\"$data[id]\")' style='cursor:pointer; text-decoration:underline;'>" . $data["id"] . "</a></td>";
		echo "<td>" . $data["status"] . "</td>";
		echo "<td>" . $data["campaign"] . "</td>";
		echo "<td>" . $data["saleAgent"] . "</td>";
		echo "<td>" . date("d/m/Y", strtotime($data["saleTS"])) . "</td>";
		echo "</tr>";
	}
?>
</tbody>
</table>
</div>
<?php
}
elseif ($type == "mcrm_display")
{
	$id = $_GET["id"];
	
	$q = mysql_query("SELECT * FROM archive_mcrm_customers WHERE id = '$id'") or die(mysql_error());
	$data = mysql_fetch_assoc($q);
?>

<table width="100%" style="padding:5px;">
<tr>
<td width="85px">Sale Code</td>
<td><b><?php echo $data["id"]; ?></b></td>
</tr>
<tr>
<td>Status</td>
<td><b><?php echo $data["status"]; ?></b></td>
</tr>
<tr>
<td>Sale Date</td>
<td><b><?php echo date("d/m/Y", strtotime($data["saleTS"])); ?></b></td>
</tr>
<tr>
<td>Sale Agent</td>
<td><b><?php echo $data["saleAgent"]; ?></b></td>
</tr>
<tr>
<td>Campaign</td>
<td><b><?php echo $data["campaign"]; ?></b></td>
</tr>
<tr>
<td>Customer Name</td>
<td><b><?php echo $data["fname"] . " " . $data["sname"]; ?></b></td>
</tr>
<tr>
<td>Date of Birth</td>
<td><b><?php echo $data["dob"]; ?></b></td>
</tr>
<tr>
<td>Physical Address</td>
<td><b><?php echo $data["addr1"] . " " . $data["addr2"] . ", " . $data["suburb"]. ", " . $data["state"] . " " . $data["postcode"]; ?></b></td>
</tr>
<tr>
<td>Postal Address</td>
<td><b><?php echo $data["p_addr1"] . " " . $data["p_addr2"] . ", " . $data["p_suburb"]. ", " . $data["p_state"] . " " . $data["p_postcode"]; ?></b></td>
</tr>
<tr>
<td>Mobile</td>
<td><b><?php echo $data["mobile"]; ?></b></td>
</tr>
<tr>
<td>Email</td>
<td><b><?php echo $data["email"]; ?></b></td>
</tr>
<tr>
<td>ABN</td>
<td><b><?php echo $data["abn"] . " - " . $data["biz_name"]; ?></b></td>
</tr>
<tr>
<td>ID</td>
<td><b><?php echo $data["idType"] . " - " . $data["idNum"]; ?></b></td>
</tr>
<?php
$q2 = mysql_query("SELECT * FROM archive_mcrm_packages WHERE sid = '$data[id]'") or die(mysql_error());
$i = 1;
while ($lines = mysql_fetch_assoc($q2))
{
	echo "<tr>";
	echo "<td>Line $i</td>";
	echo "<td><b>" . $lines["line"] . " - " . $lines["plan"] . "</b></td>";
	echo "</tr>";
	$i++;
}
?>
<tr>
<td>Agent Notes</td>
<td><textarea rows="3" readonly="readonly" style="width:375px; resize:none;"><?php echo $data["agentNotes"]; ?></textarea></td>
</tr>
<tr>
<td>Comments</td>
<td><textarea rows="3" readonly="readonly" style="width:375px; resize:none;"><?php echo $data["tpvNotes"]; ?></textarea></td>
</tr>
</table>
<?php
}
?>