<?php
mysql_connect('localhost','vericon','18450be');

$date = date("Y-m-d", strtotime("-3 weekdays"));

$q = mysql_query("SELECT * FROM vericon.customers WHERE status = 'Waiting Welcome Call' AND DATE(`timestamp`) < '$date'") or die(mysql_error());
while ($data = mysql_fetch_assoc($q))
{
	mysql_query("UPDATE vericon.customers SET status = 'Waiting Provisioning', last_edit_by = 'onaj001' WHERE id = '$data[id]' LIMIT 1") or die(mysql_error());
		
	mysql_query("INSERT INTO vericon.customers_log (id, sf_id, tb_id, status, last_edit_by, industry, lead_id, sale_id, timestamp, agent, centre, campaign, type, title, firstname, middlename, lastname, dob, email, mobile, billing, welcome, promotions, physical, postal, id_type, id_num, abn, position, ongoing_credit, onceoff_credit, payway, dd_type, billing_comments, other_comments) VALUES ('$data[id]', '$data[sf_id]', '$data[tb_id]', 'Waiting Provisioning', 'onaj001', '$data[industry]', '$data[lead_id]', '$data[sale_id]', '$data[timestamp]', '$data[agent]', '$data[centre]', '$data[campaign]', '$data[type]', '" . mysql_real_escape_string($data["title"]) . "', '" . mysql_real_escape_string($data["firstname"]) . "', '" . mysql_real_escape_string($data["middlename"]) . "', '" . mysql_real_escape_string($data["lastname"]) . "', '" . mysql_real_escape_string($data["dob"]) . "', '" . mysql_real_escape_string($data["email"]) . "', '" . mysql_real_escape_string($data["mobile"]) . "', '$data[billing]', '$data[welcome]', '$data[promotions]', '$data[physical]', '$data[postal]', '" . mysql_real_escape_string($data["id_type"]) . "', '" . mysql_real_escape_string($data["id_num"]) . "', '" . mysql_real_escape_string($data["abn"]) . "', '" . mysql_real_escape_string($data["position"]) . "', '" . mysql_real_escape_string($data["ongoing_credit"]) . "', '" . mysql_real_escape_string($data["onceoff_credit"]) . "', '" . mysql_real_escape_string($data["payway"]) . "', '" . mysql_real_escape_string($data["dd_type"]) . "', '" . mysql_real_escape_string($data["billing_comments"]) . "', '" . mysql_real_escape_string($data["other_comments"]) . "')") or die(mysql_error());
}
?>