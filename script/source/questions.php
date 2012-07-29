<?php 

//declare variables

if ($firstname == "")
{
	$first_name = "<span style=\"color:#FF0000;\"><b>(Customer Name)</b></span>";
}
else
{
	$first_name = $firstname;
}

if ($alias == "")
{
	$alias = "<span style=\"color:#FF0000;\">_______</span>";
}
else
{
	$alias = "<b>" . $alias . "</b>";
}

//Landline

$landline[1] = "<p>Thank you <b>$first_name</b>, my name is $alias from <b>$campaign</b> and today's date is $date. Do you allow <b>$campaign</b> to act on your behalf and carry out the transfer of your phone services and conduct a standard credit check?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$landline[2] = "<p>Please confirm that you are authorised to make this transfer, and your name appears on the bill.</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$landline[3] = "<p>Do you understand that by consenting to this voice recording, you are applying to transfer your existing phone services to <b>$campaign</b> and not just to receive information or paperwork from our company?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$landline[4] = "<p>Do you understand that you will be under a <b>12 month contract with $campaign</b> for your landline service?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$landline["4_2"] = "<p>Do you understand that you will be under a <b>24 month contract with $campaign</b> for your landline service?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$landline[5] = "<p>If you have any bundled services that include your landline, you may lose any discounts or benefits you are receiving from your current provider. Do you understand and agree with this?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$landline[6] = "<p>Is your current line being provided on the Optus or the Telstra Network?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">TELSTRA</span></b></p>";

$landline[7] = "<p>Could you please repeat this sentence after me;</p>
<p><u>I understand that <b>$campaign</b> is an independent company, and is not a part of Telstra; either Wholesale or Retail, or of any other carrier and I agree to use <b>$campaign</b> as my new service provider.</u></p>
<p><b>->CUSTOMER MUST <span style=\"color:#FF0000;\">REPEAT CLEARLY</span></b></p>";

$landline[8] = "<p>Could you please state your <b>company's full name, your position in the company, and that company's ABN</b>?</p>
<p><i><span style=\"color:#FF0000;\">Customer must clearly state the full company name and ABN</span></i></p>";

$landline[9] = "<p>Please state your <b>full name</b> including the spelling of your first and last name.</p>
<p><i><span style=\"color:#FF0000;\">Customer must clearly state their first and last name</span></i></p>";

$landline[10] = "<p>Please state your <b>DOB</b> as it appears on your birth certificate.</p>
<p><i><span style=\"color:#FF0000;\">Customer must clearly state their DOB</span></i></p>";

$landline[11] = "<p>Please state your Drivers Licence No...<strong>OR</strong> <u>other form of ID</u> (e.g. Medicare Card...)</p>";

$landline[12] = "<p>Please state your <b>full physical address</b> including any shop, unit, level, or lot numbers.</p>";

$landline[13] = "<p>Is that the same as your <b>mailing address</b>?</p>";

$landline[14] = "<p>Could you please state your mobile phone number as an alternate contact?</p>";

$landline[15] = "<p>A paper invoice is a cost of \$2 otherwise we can email the invoice free of charge. Please provide your email address for billing purposes.</p>
<p><i><span style=\"color:#FF0000;\">Advise of \$2 Admin Fee if paper bill, otherwise repeat the email address back to the customer phonetically</span></i></p>
<br><p>Do you also allow us to send you notifications and future offers to your email address or postal address?</p>";

$landline[16] = "<p>Could you please confirm that you are the authorised contact of the business and have the authority and provide consent to transfer your existing phone services from your current service provider to <b>$campaign</b>?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$landline[17] = "<p>Please state the phone number that you wish to transfer to <b>$campaign</b>, including the area code?</p>
<p><i><span style=\"color:#FF0000;\">Customer must state the phone number including the area codes that they wish to transfer</span></i></p>
<p>Are there any other numbers that you wish to also transfer at this time, such as fax numbers?</p>";

$landline[18] = "<p>Plans rates & contracts terms are valid only for PSTN services. ISDN services or services on other network may attract additional porting fees and plan fees and may be subject to additional terms and conditions.</p>";

$landline[19] = "<p>For a limited time, <b>$campaign</b> will offer a bonus discount of \$5.00 <i><span style=\"color:#FF0000;\">(\$3 if no E-Bill)</span></i> inc. GST off your monthly bill, if you choose direct debit, in conjunction with e-mail billing.</p>
<p>Would you like me to set this up for you?</p>";

$landline["19_post"] = "<p>For a limited time, <b>$campaign</b> will offer a bonus discount of \$3.00 inc. GST off your monthly bill, if you choose direct debit.</p>
<p>Would you like me to set this up for you?</p>";

$landline["19_email"] = "<p>For a limited time, <b>$campaign</b> will offer a bonus discount of \$5.00 inc. GST off your monthly bill, if you choose direct debit, in conjunction with e-mail billing.</p>
<p>Would you like me to set this up for you?</p>";

$landline["19_bus_nc"] = "<p>For a limited time, as an introductory offer, <b>$campaign</b> will credit your monthly invoice \$25 for the first 4 monthly invoices which equates to a saving of \$100.</p>
<p>If you agree to having your monthly account direct debited, you will also receive a bonus discount of \$5.00 <i><span style=\"color:#FF0000;\">(\$3 if no E-Bill)</span></i> Inc. GST off your monthly bill, if you choose direct debit, in conjunction with e-mail billing.</p>
<p>Would you like me to set this up for you?</p>";

$landline[20] = "<p>It is your responsibility to check the terms and conditions of any <i>pre-existing</i> contracts, which relate to the services you are transferring to us today.</p>
<p>Do you understand this?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$landline[21] = "<p><b>$campaign</b> bills on a pro rata basis, this means that your first bill will contain the plan fee from the day you join to the end of the month, and for the first full month in advance.</p>
<p>Do you understand this? <b>Yes or No</b></p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$landline[22] = "";

$landline[23] = "<p>Thank you <b>$first_name</b>, your services will begin with us when we submit your application to your current provider, <b>SUBJECT TO CREDIT APPROVAL</b>. Receipt of the welcome pack could take up to 10 days depending on your location. You will receive a welcome call within the next 3 days. The transfer can typically take approx. 10 to 14 days. In the meantime, should you have any further questions, please contact <b>$campaign</b> on <b>$number</b>.</p>
<p>I will now stop the recording.</p>
<p><i><b><span style=\"color:#FF0000;\">RECORDING STOPS HERE</span></b></i></p>";

$landline[24] = "<p>You have the right to cancel this agreement by contacting us within 10 business days from and including the day after you receive written confirmation of this agreement. Do you understand this?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$landline["No_Contract_ETF"] = "<p>If you receive a better deal from one of our competitors, please give us a call and we will try to match or better that deal for you.</p>
<p>Do you understand this?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$landline["Contract_ETF"] = "<p>If the landline contract is cancelled or terminated within the contract term then an early termination fee is charged which is \$199 per line.</p>
<p>If you agree to the termination fees and wish to proceed, please state a clear YES</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$landline["NRO"] = "<p>The No Risk offer will attempt to match any comparable advertised offer for only fixed landline services or release you from your contract term without any penalty. To be applicable for the No Risk Offer benefit you need to provide <b>$campaign</b> with 1 month written notice to match or better the competitive offer otherwise you will incur an early termination fee of \$199 per phone line. The No Risk Offer does not apply to broadband or mobile offers whether standalone or bundled with your phone line.</p>
<p>If you agree to the termination fees and wish to proceed, please state a clear YES</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$landline["rates"] = "<p>I'll just confirm the rates you have been offered are as follows:</p><br>";

$landline["dd"] = "<button onclick=\"DD('$campaign')\" class=\"btn\">Direct Debit</button>";

$landline["dd2"] = "<button onclick=\"DD('$campaign')\" class=\"btn\">Direct Debit</button>";

$landline["dd3"] = "<p>Do you agree to receive a monthly e-mail telephone bill, which you'll receive in the 2nd week of the month?</p>
<p><b>->CUSTOMER MUST SAY <span style='color:#FF0000;'>YES</span></b></p><br>
<p>Please state your preferred email address</p>
<p><i><span style='color:#FF0000;'>Spell back to customer slowly to confirm.</span></i></p>
<br><p>Do you also allow us to send you notifications and future offers to your email address or postal address?</p>";


//ADSL & Wireless

$internet[1] = "<p>Thank you <b>$first_name</b>, my name is $alias from <b>$campaign</b> and today's date is $date. Do you allow <b>$campaign</b> to act on your behalf and carry out the transfer of your internet services and conduct a standard credit check?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$internet[2] = "<p>Please confirm that you are authorised to make this transfer, and your name appears on the bill.</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$internet[3] = "<p>Do you understand that by consenting to this voice recording, you are applying to transfer your internet services to <b>$campaign</b> and not just to receive information or paperwork from our company?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$internet[4] = "<p>Do you understand that you will be under a <b>24 month contract with $campaign</b> for your internet service?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$internet["5_ADSL"] = "<p>Do you understand you have agreed to an ADSL Broadband service which runs off the Telstra infrastructure and is available to most customers in Australia?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$internet["5_Wireless"] = "<p>Do you understand you have agreed to a 3G Wireless USB broadband service which runs off the same infrastructure as Optus mobile phones. They are small cordless devices that plug into the USB port on your computer.</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$internet[6] = "<p>Is your current line being provided on the Optus or the Telstra Network?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">TELSTRA</span></b></p>";

$internet[7] = "<p>Could you please repeat this sentence after me;</p>
<p><u>I understand that <b>$campaign</b> is an independent company, and is not a part of Telstra; either Wholesale or Retail, or of any other carrier and I agree to use <b>$campaign</b> as my new internet service provider.</u></p>
<p><b>->CUSTOMER MUST <span style=\"color:#FF0000;\">REPEAT CLEARLY</span></b></p>";

$internet[8] = "<p>Could you please state your <b>company's full name, your position in the company, and that company's ABN</b>?</p>
<p><i><span style=\"color:#FF0000;\">Customer must clearly state the full company name and ABN</span></i></p>";

$internet[9] = "<p>Please state your <b>full name</b> including the spelling of your first and last name.</p>
<p><i><span style=\"color:#FF0000;\">Customer must clearly state their first and last name</span></i></p>";

$internet[10] = "<p>Please state your <b>DOB</b> as it appears on your birth certificate.</p>
<p><i><span style=\"color:#FF0000;\">Customer must clearly state their DOB</span></i></p>";

$internet[11] = "<p>Please state your Drivers Licence No.……..…<strong>OR</strong> <u>other form of ID</u> (e.g. Medicare Card………..)</p>";

$internet[12] = "<p>Please state your <b>full physical address where the internet service will be connected</b> including any shop, unit, level, or lot numbers.</p>";

$internet[13] = "<p>Is that the same as your <b>mailing address</b>?</p>";

$internet[14] = "Do you currently have ADSL/ADSL 2+ internet connected at your premises? If so who is your internet service provider?";

$internet["14_Wireless"] = "<p>Do you understand that you are applying for a wireless data card service and not for a wireless ADSL modem?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$internet[15] = "<p>Could you please state your mobile phone number as an alternate contact?</p>";

$internet[16] = "<p>Could you please confirm that you are the authorised contact of the business and have the authority and provide consent to apply for internet with <b>$campaign</b>?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$internet[17] = "Please confirm that the phone number you wish the internet service to be provided on is <span style=\"color:#FF0000;\"><b>(Phone Number)</b></span>.
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$internet["17_Wireless"] = "<p>Please state your <b>phone number</b> including the area code.</p>
<p><i><span style=\"color:#FF0000;\">Customer must state the phone number including the area code</span></i></p>";

$internet["18_ADSL"] = "<p>We ask you to note that:</p>
<ul>
<li>Your internet ADSL speed will be up to 8mbps / ADSL2+ up to 20mbps.</li>
<li>Actual speeds may vary as many factors affect speed such as internet traffic, your hardware and software, the source of your download and your location.</li>
<li>Unused data allowance if forfeited each month.</li>
<li>Once data limit has been exceeded, no extra cost is incurred but speed is reduced to 64kbps.</li>
<li>Where plan is upgraded between months a new plan cost is charged for that current month and for remainder of the contract term if not changed.</li>
<li>Your internet may be disconnected from your current supplier for a short period while internet services are ported to us.</li>
<li>Once your internet account is reassigned to us, you may lose any existing email address that your present internet provider has offered you.</li>
</ul>
<p>Do you understand this?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$internet["18_ADSL_U"] = "<p>We ask you to note that:</p>
<ul>
<li>Your internet ADSL speed will be up to 8mbps / ADSL2+ up to 20mbps.</li>
<li>Actual speeds may vary as many factors affect speed such as internet traffic, your hardware and software, the source of your download and your location.</li>
<li>Where plan is upgraded between months a new plan cost is charged for that current month and for remainder of the contract term if not changed.</li>
<li>Your internet may be disconnected from your current supplier for a short period while internet services are ported to us.</li>
<li>Once your internet account is reassigned to us, you may lose any existing email address that your present internet provider has offered you.</li>
</ul>
<p>Do you understand this?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$internet["18_Wireless"] = "<p>We ask you to note that:</p>
<ul>
<li>Your internet speed will be up to 3.6mbps.</li>
<li>Actual speeds may vary as many factors affect speed such as internet traffic, your hardware and software, the source of your download and your location.</li>
<li>Unused data allowance if forfeited each month.</li>
<li>Once data limit has been exceeded, the connection will be suspended within 24 hours, so excess charges may be incurred if your usage in that period exceeds the data limit for your plan. In the context where a service is reactivated on request after the suspension has been applied, excess data usage will be charged at 15c/MB Inc. GST.</li>
<li>Where plan is upgraded between months a new plan cost is charged for that current month and for remainder of the contract term if not changed.</li>
</ul>
<p>Do you understand this?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$internet[19] = "<p>If the internet contract is cancelled or terminated within the contract term then an early termination fee is charged which is \$199.</p>
<p>If you agree to the termination fees and wish to proceed, please state a clear YES</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$internet[20] = "<p><b>$campaign</b> offers a $2.00 discount if you opt for email billing.</p>
<p>Are you happy to receive an emailed invoice?</p>
<br><p>Do you also allow us to send you notifications and future offers to your email address or postal address?</p>";

$internet[21] = "<p>For a limited time, <b>$campaign</b> will offer a bonus discount of $3.00 off your monthly bill, if you choose direct debit.</p>
<p>Would you like me to set this up for you?</p><br>";

$internet[22] = "<p>It is your responsibility to check the terms and conditions of any pre-existing contracts you might be having with your current provider.</p>
<p>Do you understand this?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$internet[23] = "<p><b>$campaign</b> bills on a pro rata basis, this means that your first bill will contain the plan fee from the day you join to the end of the month, and for the first full month in advance.</p>
<p>Do you understand this? <b>Yes or No</b></p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$internet[24] = "";

$internet[25] = "<p>Thank you <b>$first_name</b>, your services will begin with us when we submit your application to our provisioning department, <b>SUBJECT TO CREDIT APPROVAL and TELEPHONE LINE TESTING</b>. Receipt of the welcome pack could take up to 10 days depending on your location. You will receive a welcome call within the next 3 days. The transfer can typically take approx. up to 14 days. In the meantime, should you have any further questions, please contact <b>$campaign</b> on <b>$number</b>.</p>
<p>I will now stop the recording.</p>
<p><i><b><span style=\"color:#FF0000;\">RECORDING STOPS HERE</span></b></i></p>";

$internet[26] = "<p>Thank you <b>$first_name</b>, your services will begin with us when we submit your application to our provisioning department, <b>SUBJECT TO CREDIT APPROVAL</b>. Receipt of the welcome pack could take up to 10 days depending on your location. You will receive a welcome call within the next 3 days. The transfer can typically take approx. up to 14 days. In the meantime, should you have any further questions, please contact <b>$campaign</b> on <b>$number</b>.</p>
<p>I will now stop the recording.</p>
<p><i><b><span style=\"color:#FF0000;\">RECORDING STOPS HERE</span></b></i></p>";

$internet[27] = "<p>You have the right to cancel this agreement by contacting us within 10 business days from and including the day after you receive written confirmation of this agreement. Do you understand this?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$internet["rates"] = "<p>I'll just confirm the rates you have been offered are as follows:</p><br>";

$internet["2GB"] = "<table cellspacing=\"0\" cellpadding=\"0pt\" style=\"table-layout:fixed;border-collapse:collapse;\">
<tr align=\"left\" valign=\"top\">
<td style=\"padding:4.9pt;border-top:1pt solid black;border-right:1pt solid black;border-bottom:1pt solid black;border-left:1pt solid black;\">
<p><span style=\"color:#000080;\">Your plan fee is <b>\$24.95 per month inc. GST</b> with a minimum cost of $598.80 over the 24 month contract.</span></p>
<p>You have 2GB of data usage (No peak/off peak restriction times).</p>
<p><b><span style=\"color:#FF0000;\">Please confirm that you accept these rates by saying a clear YES, only if you understand, and accept the terms.</span></b></p>
<p><b><u>CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></u></b></p>
</td>
</tr>
</table><br>";

$internet["4GB"] = "<table cellspacing=\"0\" cellpadding=\"0pt\" style=\"table-layout:fixed;border-collapse:collapse;\">
<tr align=\"left\" valign=\"top\">
<td style=\"padding:4.9pt;border-top:1pt solid black;border-right:1pt solid black;border-bottom:1pt solid black;border-left:1pt solid black;\">
<p><span style=\"color:#000080;\">Your plan fee is <b>\$34.95 per month inc. GST</b> with a minimum cost of $838.80 over the 24 month contract.</span></p>
<p>You have 4GB of data usage (No peak/off peak restriction times).</p>
<p><b><span style=\"color:#FF0000;\">Please confirm that you accept these rates by saying a clear YES, only if you understand, and accept the terms.</span></b></p>
<p><b><u>CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></u></b></p>
</td>
</tr>
</table><br>";

$internet["6GB"] = "<table cellspacing=\"0\" cellpadding=\"0pt\" style=\"table-layout:fixed;border-collapse:collapse;\">
<tr align=\"left\" valign=\"top\">
<td style=\"padding:4.9pt;border-top:1pt solid black;border-right:1pt solid black;border-bottom:1pt solid black;border-left:1pt solid black;\">
<p><span style=\"color:#000080;\">Your plan fee is <b>\$44.95 per month inc. GST</b> with a minimum cost of $1078.80 over the 24 month contract.</span></p>
<p>You have 6GB of data usage (No peak/off peak restriction times).</p>
<p><b><span style=\"color:#FF0000;\">Please confirm that you accept these rates by saying a clear YES, only if you understand, and accept the terms.</span></b></p>
<p><b><u>CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></u></b></p>
</td>
</tr>
</table><br>";

$internet["8GB"] = "<table cellspacing=\"0\" cellpadding=\"0pt\" style=\"table-layout:fixed;border-collapse:collapse;\">
<tr align=\"left\" valign=\"top\">
<td style=\"padding:4.9pt;border-top:1pt solid black;border-right:1pt solid black;border-bottom:1pt solid black;border-left:1pt solid black;\">
<p><span style=\"color:#000080;\">Your plan fee is <b>\$54.95 per month inc. GST</b> with a minimum cost of $1318.80 over the 24 month contract.</span></p>
<p>You have 8GB of data usage (No peak/off peak restriction times).</p>
<p><b><span style=\"color:#FF0000;\">Please confirm that you accept these rates by saying a clear YES, only if you understand, and accept the terms.</span></b></p>
<p><b><u>CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></u></b></p>
</td>
</tr>
</table><br>";

//Bundle

$bundle[1] = "<p>Thank you <b>$first_name</b>, my name is $alias from <b>$campaign</b> and today's date is $date. Do you allow <b>$campaign</b> to act on your behalf and carry out the transfer of your phone and internet services and conduct a standard credit check?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$bundle[2] = "<p>Do you understand that by consenting to this voice recording, you are applying to transfer your services to <b>$campaign</b> and not just to receive information or paperwork from our company?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$bundle[3] = "<p>Could you please confirm that you are the authorised contact of the business and have the authority and provide consent to transfer your services from your current service provider to <b>$campaign</b>?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$bundle[4] = "<p>Please state the phone numbers that you wish to transfer to <b>$campaign</b>, including the area code and confirm that the internet service will be provided on that line?</p>
<p><i><span style=\"color:#FF0000;\">Customer must state the phone number including the area codes that they wish to transfer</span></i></p>
<p>Are there any other numbers that you wish to also transfer at this time, such as fax numbers?</p>";

$bundle[5] = "<p>Do you understand that you will be under a <b>24 month contract with $campaign</b>?</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";

$bundle[6] = "<p>If the contract is cancelled or terminated within the contract term then an early termination fee is charged which is \$199 per service.</p>
<p>If you agree to the termination fees and wish to proceed, please state a clear YES</p>
<p><b>->CUSTOMER MUST SAY <span style=\"color:#FF0000;\">YES</span></b></p>";
?>