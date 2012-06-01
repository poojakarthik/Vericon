<?php
mysql_connect('localhost','vericon','18450be');
mysql_select_db('vericon');

$id = $_GET["id"];
$account_status = $_GET["account_status"];
$adsl_status = $_GET["adsl_status"];
$wireless_status = $_GET["wireless_status"];
$building_type = $_GET["building_type"];
$building_number = $_GET["building_number"];
$building_number_suffix = $_GET["building_number_suffix"];
$building_name = $_GET["building_name"];
$street_number_start = $_GET["street_number_start"];
$street_number_end = $_GET["street_number_end"];
$street_name = $_GET["street_name"];
$street_type = $_GET["street_type"];
$suburb = $_GET["suburb"];
$state = $_GET["state"];
$postcode = $_GET["postcode"];
$po_box_number = $_GET["po_box_number"];
$mail_street_number = $_GET["mail_street_number"];
$mail_street = $_GET["mail_street"];
$mail_suburb = $_GET["mail_suburb"];
$mail_state = $_GET["mail_state"];
$mail_postcode = $_GET["mail_postcode"];
$contract_months = $_GET["contract_months"];
$credit_offered = $_GET["credit_offered"];
$payway = $_GET["payway"];
$direct_debit = $_GET["direct_debit"];
$additional_information = $_GET["additional_information"];
$billing_comment = $_GET["billing_comment"];
$provisioning_comment = $_GET["provisioning_comment"];
$mobile_comment = $_GET["mobile_comment"];
$other_comment = $_GET["other_comment"];
$cli_1 = $_GET["cli_1"];
$plan_1 = $_GET["plan_1"];
$cli_2 = $_GET["cli_2"];
$plan_2 = $_GET["plan_2"];
$cli_3 = $_GET["cli_3"];
$plan_3 = $_GET["plan_3"];
$cli_4 = $_GET["cli_4"];
$plan_4 = $_GET["plan_4"];
$cli_5 = $_GET["cli_5"];
$plan_5 = $_GET["plan_5"];
$cli_6 = $_GET["cli_6"];
$plan_6 = $_GET["plan_6"];
$cli_7 = $_GET["cli_7"];
$plan_7 = $_GET["plan_7"];
$cli_8 = $_GET["cli_8"];
$plan_8 = $_GET["plan_8"];
$cli_9 = $_GET["cli_9"];
$plan_9 = $_GET["plan_9"];
$cli_10 = $_GET["cli_10"];
$plan_10 = $_GET["plan_10"];
$msn_1 = $_GET["msn_1"];
$mplan_1 = $_GET["mplan_1"];
$msn_2 = $_GET["msn_2"];
$mplan_2 = $_GET["mplan_2"];
$msn_3 = $_GET["msn_3"];
$mplan_3 = $_GET["mplan_3"];
$wmsn_1 = $_GET["wmsn_1"];
$wplan_1 = $_GET["wplan_1"];
$wmsn_2 = $_GET["wmsn_2"];
$wplan_2 = $_GET["wplan_2"];
$acli = $_GET["acli"];
$aplan = $_GET["aplan"];
$bundle = $_GET["bundle"];

$q = mysql_query("SELECT * FROM sales_customers WHERE id = '$id'") or die(mysql_error());
$data = mysql_fetch_assoc($q);

$q1 = mysql_query("SELECT id FROM customers WHERE sale_id = '$id'") or die(mysql_error());
$sid = mysql_fetch_row($q1);

$q2 = mysql_query("SELECT id FROM campaigns WHERE campaign = '$data[campaign]'") or die(mysql_error());
$who = mysql_fetch_row($q2);

$q4 = mysql_query("SELECT first,last FROM auth WHERE user = '$data[agent]'") or die(mysql_error());
$ag = mysql_fetch_row($q4);

$dsr_number = substr($sid[0],0,5);
$recording = $data["lead_id"] . ".gsm";
$sale_id = $sid[0];
$date_of_sale = date("Y-m-d", strtotime($data["approved_timestamp"]));
$whoisit = $who[0];
$rating = $data["campaign"] . " " . $data["type"];
$agent = $ag[0] . " " . $ag[1];

if ($data["mobile"] == "N/A") {
	$mobile_contact = "";
} else {
	$mobile_contact = $data["mobile"];
}

if ($data["email"] == "N/A") {
	$email_address = "";
} else {
	$email_address = $data["email"];
}

if ($data["welcome"] == "post") {
	$welcome_email = "N";
} else {
	$welcome_email = "Y";
}

if ($data["billing"] == "post") {
	$ebill = "N";
} else {
	$ebill = "Y";
}

if ($data["type"] == "Business") {
	$account_name = $_GET["account_name"];
} else {
	$account_name = strtoupper($data["lastname"]) . ", " . $data["firstname"];
}

$q3 = mysql_query("SELECT * FROM tmp_dsr WHERE sale_id = '$sale_id'") or die(mysql_error());

if (mysql_num_rows($q3) == 0)
{
	mysql_query("INSERT INTO tmp_dsr (dsr_number, recording, sale_id, account_status, adsl_status, wireless_status, agent, centre, date_of_sale, whoisit, telco_name, rating, industry, title, first_name, middle_name, last_name, position, dob, account_name, abn, cli_1, plan_1, cli_2, plan_2, cli_3, plan_3, cli_4, plan_4, cli_5, plan_5, cli_6, plan_6, cli_7, plan_7, cli_8, plan_8, cli_9, plan_9, cli_10, plan_10, msn_1, mplan_1, msn_2, mplan_2, msn_3, mplan_3, wmsn_1, wplan_1, wmsn_2, wplan_2, acli, aplan, bundle, building_type, building_number, building_number_suffix, building_name, street_number_start, street_number_end, street_name, street_type, suburb, state, post_code, po_box_number_only, mail_street_number, mail_street, mail_suburb, mail_state, mail_post_code, contract_months, credit_offered, welcome_email, payway, direct_debit, ebill, sale_type, mobile_contact, current_provider, email_address, additional_information, billing_comment, provisioning_comment, mobile_comment, other_comment) VALUES ('$dsr_number', '$recording', '$sale_id', '" . mysql_escape_string($account_status) . "', '" . mysql_escape_string($adsl_status) . "', '" . mysql_escape_string($wireless_status) . "', '$agent', '$data[centre]', '$date_of_sale', '$whoisit', '$data[campaign]', '$rating', '$data[industry]', '" . mysql_escape_string($data[title]) . "', '" . mysql_escape_string($data[firstname]) . "', '" . mysql_escape_string($data[middlename]) . "', '" . mysql_escape_string($data[lastname]) . "', '" . mysql_escape_string($data[position]) . "', '$data[dob]', '" . mysql_escape_string($account_name) . "', '" . mysql_escape_string($data[abn]) . "', '" . mysql_escape_string($cli_1) . "', '" . mysql_escape_string($plan_1) . "', '" . mysql_escape_string($cli_2) . "', '" . mysql_escape_string($plan_2) . "', '" . mysql_escape_string($cli_3) . "', '" . mysql_escape_string($plan_3) . "', '" . mysql_escape_string($cli_4) . "', '" . mysql_escape_string($plan_4) . "', '" . mysql_escape_string($cli_5) . "', '" . mysql_escape_string($plan_5) . "', '" . mysql_escape_string($cli_6) . "', '" . mysql_escape_string($plan_6) . "', '" . mysql_escape_string($cli_7) . "', '" . mysql_escape_string($plan_7) . "', '" . mysql_escape_string($cli_8) . "', '" . mysql_escape_string($plan_8) . "', '" . mysql_escape_string($cli_9) . "', '" . mysql_escape_string($plan_9) . "', '" . mysql_escape_string($cli_10) . "', '" . mysql_escape_string($plan_10) . "', '" . mysql_escape_string($msn_1) . "', '" . mysql_escape_string($mplan_1) . "', '" . mysql_escape_string($msn_2) . "', '" . mysql_escape_string($mplan_2) . "', '" . mysql_escape_string($msn_3) . "', '" . mysql_escape_string($mplan_3) . "', '" . mysql_escape_string($wmsn_1) . "', '" . mysql_escape_string($wplan_1) . "', '" . mysql_escape_string($wmsn_2) . "', '" . mysql_escape_string($wplan_2) . "', '" . mysql_escape_string($acli) . "', '" . mysql_escape_string($aplan) . "', '" . mysql_escape_string($bundle) . "', '" . mysql_escape_string($building_type) . "', '" . mysql_escape_string($building_number) . "', '" . mysql_escape_string($building_number_suffix) . "', '" . mysql_escape_string($building_name) . "', '" . mysql_escape_string($street_number_start) . "', '" . mysql_escape_string($street_number_end) . "', '" . mysql_escape_string($street_name) . "', '" . mysql_escape_string($street_type) . "', '" . mysql_escape_string($suburb) . "', '" . mysql_escape_string($state) . "', '" . mysql_escape_string($postcode) . "', '" . mysql_escape_string($po_box_number) . "', '" . mysql_escape_string($mail_street_number) . "', '" . mysql_escape_string($mail_street) . "', '" . mysql_escape_string($mail_suburb) . "', '" . mysql_escape_string($mail_state) . "', '" . mysql_escape_string($mail_postcode) . "', '" . mysql_escape_string($contract_months) . "', '" . mysql_escape_string($credit_offered) . "', '" . mysql_escape_string($welcome_email) . "', '" . mysql_escape_string($payway) . "', '" . mysql_escape_string($direct_debit) . "', '" . mysql_escape_string($ebill) . "', 'N', '" . mysql_escape_string($mobile_contact) . "', 'Telstra', '" . mysql_escape_string($email_address) . "', '" . mysql_escape_string($additional_information) . "', '" . mysql_escape_string($billing_comment) . "', '" . mysql_escape_string($provisioning_comment) . "', '" . mysql_escape_string($mobile_comment) . "', '" . mysql_escape_string($other_comment) . "')") or die(mysql_error());
}
else
{
	mysql_query("UPDATE tmp_dsr SET dsr_number = '$dsr_number', recording = '$recording', account_status = '" . mysql_escape_string($account_status) . "', adsl_status = '" . mysql_escape_string($adsl_status) . "', wireless_status = '" . mysql_escape_string($wireless_status) . "', agent = '$agent', centre = '$data[centre]', date_of_sale = '$date_of_sale', whoisit = '$whoisit', telco_name = '$data[campaign]', rating = '$rating', industry = '$data[industry]', title = '" . mysql_escape_string($data[title]) . "', first_name = '" . mysql_escape_string($data[firstname]) . "', middle_name = '" . mysql_escape_string($data[middlename]) . "', last_name = '" . mysql_escape_string($data[lastname]) . "', position = '" . mysql_escape_string($data[position]) . "', dob = '$data[dob]', account_name = '" . mysql_escape_string($account_name) . "', abn = '" . mysql_escape_string($data[abn]) . "', cli_1 = '" . mysql_escape_string($cli_1) . "', plan_1 = '" . mysql_escape_string($plan_1) . "', cli_2 = '" . mysql_escape_string($cli_2) . "', plan_2 = '" . mysql_escape_string($plan_2) . "', cli_3 = '" . mysql_escape_string($cli_3) . "', plan_3 = '" . mysql_escape_string($plan_3) . "', cli_4 = '" . mysql_escape_string($cli_4) . "', plan_4 = '" . mysql_escape_string($plan_4) . "', cli_5 = '" . mysql_escape_string($cli_5) . "', plan_5 = '" . mysql_escape_string($plan_5) . "', cli_6 = '" . mysql_escape_string($cli_6) . "', plan_6 = '" . mysql_escape_string($plan_6) . "', cli_7 = '" . mysql_escape_string($cli_7) . "', plan_7 = '" . mysql_escape_string($plan_7) . "', cli_8 = '" . mysql_escape_string($cli_8) . "', plan_8 = '" . mysql_escape_string($plan_8) . "', cli_9 = '" . mysql_escape_string($cli_9) . "', plan_9 = '" . mysql_escape_string($plan_9) . "', cli_10 = '" . mysql_escape_string($cli_10) . "', plan_10 = '" . mysql_escape_string($plan_10) . "', msn_1 = '" . mysql_escape_string($msn_1) . "', mplan_1 = '" . mysql_escape_string($mplan_1) . "', msn_2 = '" . mysql_escape_string($msn_2) . "', mplan_2 = '" . mysql_escape_string($mplan_2) . "', msn_3 = '" . mysql_escape_string($msn_3) . "', mplan_3 = '" . mysql_escape_string($mplan_3) . "', wmsn_1 = '" . mysql_escape_string($wmsn_1) . "', wplan_1 = '" . mysql_escape_string($wplan_1) . "', wmsn_2 = '" . mysql_escape_string($wmsn_2) . "', wplan_2 = '" . mysql_escape_string($wplan_2) . "', acli = '" . mysql_escape_string($acli) . "', aplan = '" . mysql_escape_string($aplan) . "', bundle = '" . mysql_escape_string($bundle) . "', building_type = '" . mysql_escape_string($building_type) . "', building_number = '" . mysql_escape_string($building_number) . "', building_number_suffix = '" . mysql_escape_string($building_number_suffix) . "', building_name = '" . mysql_escape_string($building_name) . "', street_number_start = '" . mysql_escape_string($street_number_start) . "', street_number_end = '" . mysql_escape_string($street_number_end) . "', street_name = '" . mysql_escape_string($street_name) . "', street_type = '" . mysql_escape_string($street_type) . "', suburb = '" . mysql_escape_string($suburb) . "', state = '" . mysql_escape_string($state) . "', post_code = '" . mysql_escape_string($postcode) . "', po_box_number_only = '" . mysql_escape_string($po_box_number) . "', mail_street_number = '" . mysql_escape_string($mail_street_number) . "', mail_street = '" . mysql_escape_string($mail_street) . "', mail_suburb = '" . mysql_escape_string($mail_suburb) . "', mail_state = '" . mysql_escape_string($mail_state) . "', mail_post_code = '" . mysql_escape_string($mail_postcode) . "', contract_months = '" . mysql_escape_string($contract_months) . "', credit_offered = '" . mysql_escape_string($credit_offered) . "', welcome_email = '" . mysql_escape_string($welcome_email) . "', payway = '" . mysql_escape_string($payway) . "', direct_debit = '" . mysql_escape_string($direct_debit) . "', ebill = '" . mysql_escape_string($ebill) . "', sale_type = 'N', mobile_contact = '" . mysql_escape_string($mobile_contact) . "', current_provider = 'Telstra', email_address = '" . mysql_escape_string($email_address) . "', additional_information = '" . mysql_escape_string($additional_information) . "', billing_comment = '" . mysql_escape_string($billing_comment) . "', provisioning_comment = '" . mysql_escape_string($provisioning_comment) . "', mobile_comment = '" . mysql_escape_string($mobile_comment) . "', other_comment = '" . mysql_escape_string($other_comment) . "' WHERE sale_id = '$sale_id' LIMIT 1") or die(mysql_error());
}

echo 1;
?>

