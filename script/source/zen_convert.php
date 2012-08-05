<?php 

//convert plans
if ($plan == "\$30 24 Month Contract")
{
    $plan = "TC030";
}
elseif ($plan == "\$34.95 24 Month Contract")
{
    $plan = "TC034";
}
elseif ($plan == "\$35 No Contract")
{
    $plan = "TN035";
}
elseif ($plan == "\$39.95 No Contract")
{
    $plan = "TN039";
}
elseif ($plan == "\$45 24 Month Contract")
{
    $plan = "TC045";
}
elseif ($plan == "\$49.95 24 Month Contract")
{
    $plan = "TC049";
}
elseif ($plan == "\$50 24 Month Contract")
{
    $plan = "TC050";
}
elseif ($plan == "\$50 No Contract")
{
    $plan = "TN050";
}
elseif ($plan == "\$54.95 No Contract")
{
    $plan = "TN054";
}
elseif ($plan == "\$55 24 Month Contract")
{
    $plan = "TC055";
}
elseif ($plan == "\$55 No Contract")
{
    $plan = "TN055";
}
elseif ($plan == "\$59.95 24 Month Contract")
{
    $plan = "TC059";
}
elseif ($plan == "\$60 24 Month Contract")
{
    $plan = "TC060";
}
elseif ($plan == "\$65 No Contract")
{
    $plan = "TN065";
}
elseif ($plan == "\$69.95 No Contract")
{
    $plan = "TN069";
}
elseif ($plan == "\$70 No Contract")
{
    $plan = "TN070";
}
elseif ($plan == "\$75 24 Month Contract")
{
    $plan = "TC075";
}
elseif ($plan == "\$80 No Contract")
{
    $plan = "TN080";
}
elseif ($plan == "\$80 24 Month Contract")
{
    $plan = "TC080";
}
elseif ($plan == "\$85 No Contract")
{
    $plan = "TN085";
}
elseif ($plan == "\$95 24 Month Contract")
{
    $plan = "TC095";
}
elseif ($plan == "\$99 24 Month Contract")
{
    $plan = "TC099";
}
elseif ($plan == "\$99.95 24 Month Contract")
{
    $plan = "TC099";
}
elseif ($plan == "\$105 No Contract")
{
    $plan = "TN105";
}
elseif ($plan == "\$109.95 No Contract")
{
    $plan = "TC109";
}
elseif ($plan == "\$109 No Contract")
{
    $plan = "TN109";
}
elseif ($plan == "ADSL 15GB 24 Month Contract")
{
    $plan = "AC015";
}
elseif ($plan == "ADSL 500GB 24 Month Contract")
{
    $plan = "AC500";
}
elseif ($plan == "ADSL Unlimited 24 Month Contract")
{
    $plan = "AC999";
}
elseif ($plan == "Wireless 2GB 24 Month Contract")
{
    $plan = "WC002";
}
elseif ($plan == "Wireless 4GB 24 Month Contract")
{
    $plan = "WC004";
}
elseif ($plan == "Wireless 6GB 24 Month Contract")
{
    $plan = "WC006";
}
elseif ($plan == "Wireless 8GB 24 Month Contract")
{
    $plan = "WC008";
}
/////////////////////////////////////////////
elseif ($plan == "Bundle \$80 24 Month Contract")
{
    $plan = "BC080";
}
elseif ($plan == "Bundle \$85 24 Month Contract")
{
    $plan = "BC085";
}
elseif ($plan == "Bundle \$90 24 Month Contract")
{
    $plan = "BC090";
}
elseif ($plan == "Bundle \$95 24 Month Contract")
{
    $plan = "BC095";
}
elseif ($plan == "Bundle \$100 24 Month Contract")
{
    $plan = "BC100";
}
elseif ($plan == "Bundle \$105 24 Month Contract")
{
    $plan = "BC105";
}
elseif ($plan == "Bundle \$110 24 Month Contract")
{
    $plan = "BC110";
}
elseif ($plan == "Bundle \$115 24 Month Contract")
{
    $plan = "BC115";
}
elseif ($plan == "Bundle \$135 24 Month Contract")
{
    $plan = "BC135";
}
elseif ($plan == "Bundle \$140 24 Month Contract")
{
    $plan = "BC140";
}
else
{
	$plan = "";
}

//convert campaign names
if ($campaign == "Action Telecom Business")
{
    $campaign = "Action Telecom";
    $campaign_name = "Action Telecom Business";
    $campaign_check = "AT_B";
    $website = "<a href=\"http://www.actiontelecom.com.au\" target=\"new\">www.actiontelecom.com.au</a>";
    $number = "1300 789 142";
}
elseif ($campaign == "Action Telecom Residential")
{
    $campaign = "Action Telecom";
    $campaign_name = "Action Telecom Residential";
    $campaign_check = "AT_R";
    $website = "<a href=\"http://www.actiontelecom.com.au\" target=\"new\">www.actiontelecom.com.au</a>";
    $number = "1300 789 142";
}
elseif ($campaign == "Alpha Talk Business")
{
    $campaign = "Alpha Talk";
    $campaign_name = "Alpha Talk Business";
    $campaign_check = "AA_B";
    $website = "<a href=\"http://www.alphatalk.com.au\" target=\"new\">www.alphatalk.com.au</a>";
    $number = "1300 834 474";
}
elseif ($campaign == "Alpha Talk Residential")
{
    $campaign = "Alpha Talk";
    $campaign_name = "Alpha Talk Residential";
    $campaign_check = "AA_R";
    $website = "<a href=\"http://www.alphatalk.com.au\" target=\"new\">www.alphatalk.com.au</a>";
    $number = "1300 834 474";
}
elseif ($campaign == "Telkokey Business")
{
    $campaign = "Telkokey";
    $campaign_name = "Telkokey Business";
    $campaign_check = "TK_B";
    $website = "<a href=\"http://www.telkokey.com.au\" target=\"new\">www.telkokey.com.au</a>";
    $number = "1300 975 212";
}
elseif ($campaign == "Telkokey Residential")
{
    $campaign = "Telkokey";
    $campaign_name = "Telkokey Residential";
    $campaign_check = "TK_R";
    $website = "<a href=\"http://www.telkokey.com.au\" target=\"new\">www.telkokey.com.au</a>";
    $number = "1300 975 212";
}
elseif ($campaign == "Venus Telecom Business")
{
    $campaign = "Venus Telecom";
    $campaign_name = "Venus Telecom Business";
    $campaign_check = "VT_B";
    $website = "<a href=\"http://www.venustelecom.com.au\" target=\"new\">www.venustelecom.com.au</a>";
    $number = "1300 413 373";
}
elseif ($campaign == "Venus Telecom Residential")
{
    $campaign = "Venus Telecom";
    $campaign_name = "Venus Telecom Residential";
    $campaign_check = "VT_R";
    $website = "<a href=\"http://www.venustelecom.com.au\" target=\"new\">www.venustelecom.com.au</a>";
    $number = "1300 413 373";
}
elseif ($campaign == "XLN Telecom Business")
{
    $campaign = "XLN Telecom";
    $campaign_name = "XLN Telecom Business";
    $campaign_check = "XL_B";
    $website = "<a href=\"http://www.xlntelecom.com.au\" target=\"new\">www.xlntelecom.com.au</a>";
    $number = "1300 874 427";
}
elseif ($campaign == "XLN Telecom Residential")
{
    $campaign = "XLN Telecom";
    $campaign_name = "XLN Telecom Residential";
    $campaign_check = "XL_R";
    $website = "<a href=\"http://www.xlntelecom.com.au\" target=\"new\">www.xlntelecom.com.au</a>";
    $number = "1300 874 427";
}
?>