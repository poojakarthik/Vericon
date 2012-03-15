<?php 

//convert plans
if ($plan == "\$49.95 24 Month Contract")
{
    $plan = "TC049";
}
elseif ($plan == "\$54.95 No Contract")
{
    $plan = "TN054";
}
elseif ($plan == "\$59.95 24 Month Contract")
{
    $plan = "TC059";
}
elseif ($plan == "\$64.95 12 Month Contract")
{
    $plan = "TC064";
}
elseif ($plan == "\$69.95 No Contract")
{
    $plan = "TN069";
}
elseif ($plan == "\$79.95 No Contract")
{
    $plan = "TN079";
}
elseif ($plan == "\$99.95 24 Month Contract")
{
    $plan = "TC099";
}
elseif ($plan == "\$104.95 12 Month Contract")
{
    $plan = "TC104";
}
elseif ($plan == "\$109.95 No Contract")
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
elseif ($plan == "Bundle \$84.95 24 Month Contract")
{
    $plan = "BC084";
}
elseif ($plan == "Bundle \$97.95 24 Month Contract")
{
    $plan = "BC097";
}
elseif ($plan == "Bundle \$99.95 24 Month Contract")
{
    $plan = "BC099";
}
elseif ($plan == "Bundle \$114.95 24 Month Contract")
{
    $plan = "BC114";
}
elseif ($plan == "Bundle \$122.95 24 Month Contract")
{
    $plan = "BC122";
}
elseif ($plan == "Bundle \$124.95 24 Month Contract")
{
    $plan = "BC124";
}
elseif ($plan == "Bundle \$119.95 24 Month Contract")
{
    $plan = "BC119";
}
elseif ($plan == "Bundle \$129.95 24 Month Contract")
{
    $plan = "BC129";
}
elseif ($plan == "Bundle \$134.95 24 Month Contract")
{
    $plan = "BC134";
}
elseif ($plan == "Bundle \$149.95 24 Month Contract")
{
    $plan = "BC149";
}
else
{
	$plan = "";
}

//convert campaign names
if ($campaign == "Speed Telecom Business")
{
    $campaign = "Speed Telecom";
    $campaign_name = "Speed Telecom Business";
    $campaign_check = "ST_B";
    $website = "<a href=\"http://www.speedtelecom.com.au\" target=\"new\">www.speedtelecom.com.au</a>";
    $number = "1300 885 925";
}
elseif ($campaign == "Speed Telecom Residential")
{
    $campaign = "Speed Telecom";
    $campaign_name = "Speed Telecom Residential";
    $campaign_check = "ST_R";
    $website = "<a href=\"http://www.speedtelecom.com.au\" target=\"new\">www.speedtelecom.com.au</a>";
    $number = "1300 885 925";
}
elseif ($campaign == "Magnum Telecom Business")
{
    $campaign = "Magnum Telecom";
    $campaign_name = "Magnum Telecom Business";
    $campaign_check = "MT_B";
    $website = "<a href=\"http://www.magnumtelecom.com.au\" target=\"new\">www.magnumtelecom.com.au</a>";
    $number = "1300 529 277";
}
elseif ($campaign == "Magnum Telecom Residential")
{
    $campaign = "Magnum Telecom";
    $campaign_name = "Magnum Telecom Residential";
    $campaign_check = "MT_R";
    $website = "<a href=\"http://www.magnumtelecom.com.au\" target=\"new\">www.magnumtelecom.com.au</a>";
    $number = "1300 529 277";
}
elseif ($campaign == "Precise Telecom Business")
{
    $campaign = "Precise Telecom";
    $campaign_name = "Precise Telecom Business";
    $campaign_check = "PT_B";
    $website = "<a href=\"http://www.precisetelecom.com.au\" target=\"new\">www.precisetelecom.com.au</a>";
    $number = "1300 798 911";
}
elseif ($campaign == "Precise Telecom Residential")
{
    $campaign = "Precise Telecom";
    $campaign_name = "Precise Telecom Residential";
    $campaign_check = "PT_R";
    $website = "<a href=\"http://www.precisetelecom.com.au\" target=\"new\">www.precisetelecom.com.au</a>";
    $number = "1300 798 911";
}
elseif ($campaign == "Oasis Telecom Business")
{
    $campaign = "Oasis Telecom";
    $campaign_name = "Oasis Telecom Business";
    $campaign_check = "OT_B";
    $website = "<a href=\"http://www.oasistelecom.com.au\" target=\"new\">www.oasistelecom.com.au</a>";
    $number = "1300 734 399";
}
elseif ($campaign == "Oasis Telecom Residential")
{
    $campaign = "Oasis Telecom";
    $campaign_name = "Oasis Telecom Residential";
    $campaign_check = "OT_R";
    $website = "<a href=\"http://www.oasistelecom.com.au\" target=\"new\">www.oasistelecom.com.au</a>";
    $number = "1300 734 399";
}
elseif ($campaign == "Spiral Communications Business")
{
    $campaign = "Spiral Communications";
    $campaign_name = "Spiral Communications Business";
    $campaign_check = "SC_B";
    $website = "<a href=\"http://www.spiralcommunications.com.au\" target=\"new\">www.spiralcommunications.com.au</a>";
    $number = "1300 559 069";
}
elseif ($campaign == "Spiral Communications Residential")
{
    $campaign = "Spiral Communications";
    $campaign_name = "Spiral Communications Residential";
    $campaign_check = "SC_R";
    $website = "<a href=\"http://www.spiralcommunications.com.au\" target=\"new\">www.spiralcommunications.com.au</a>";
    $number = "1300 559 069";
}
elseif ($campaign == "Telcoshare Business")
{
    $campaign = "Telcoshare";
    $campaign_name = "Telcoshare Business";
    $campaign_check = "TS_B";
    $website = "<a href=\"http://www.telcoshare.com.au\" target=\"new\">www.telcoshare.com.au</a>";
    $number = "1300 732 355";
}
elseif ($campaign == "Telcoshare Residential")
{
    $campaign = "Telcoshare";
    $campaign_name = "Telcoshare Residential";
    $campaign_check = "TS_R";
    $website = "<a href=\"http://www.telcoshare.com.au\" target=\"new\">www.telcoshare.com.au</a>";
    $number = "1300 732 355";
}
?>