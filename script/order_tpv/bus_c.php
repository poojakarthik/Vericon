<?php
$next_default = '<td width="33.33%" align="right"><input type="button" onClick="Next()" style="display: none;" id="Btn_Next" class="next" /></td>';

if($page == 1)
{
	echo $landline[1];
	$next_btn = $next_default;
}

if($page == 2)
{
	echo $landline[3];
	$next_btn = $next_default;
}

if($page == 3)
{
	echo $landline[24];
	$next_btn = $next_default;
}

if($page == 4)
{
	if($plan == "TC064" || $plan == "TC104")
	{
		echo $landline["4_2"];
	}
	else
	{
		echo $landline[4];
	}
	$next_btn = $next_default;
}

if($page == 5)
{
	echo $landline[5];
	$next_btn = $next_default;
}

if($page == 6)
{
	echo $landline[6];
	$next_btn = $next_default;
}

if($page == 7)
{
	echo $landline[7];
	$next_btn = $next_default;
}

if($page == 8)
{
	echo $landline[8];
	echo $input["bus_info"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'bus_info\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 9)
{
	echo $landline[9];
	echo $input["name"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'name\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 10)
{
	echo $landline[10];
	echo $input["dob"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'dob\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 11)
{
	echo $landline[12];
	echo $input["physical"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'physical\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 12)
{
	echo $landline[13];
	echo $input["postal"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'postal\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 13)
{
	echo $landline[14];
	echo $input["mobile"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'mobile\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 14)
{
	echo $landline[15];
	echo $landline[22];
	echo $input["email"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'email\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 15)
{
	echo $landline[16];
	$next_btn = $next_default;
}

if($page == 16)
{
	echo $landline[17];
	echo $input["lines"];
	$next_btn = $next_default;
}

if($page == 17)
{
	echo $landline["rates"];
	$q4 = mysql_query("SELECT * FROM sales_packages WHERE sid = '$id' ORDER BY plan DESC") or die(mysql_error());
	while ($plan_rate = mysql_fetch_assoc($q4))
	{
		$q = mysql_query("SELECT id FROM vericon.campaigns WHERE campaign = '" . mysql_real_escape_string($campaign) . "'") or die(mysql_error());
		$c = mysql_fetch_row($q);
		$campaign_id = $c[0];
		
		$q = mysql_query("SELECT * FROM script_plans WHERE id = '$plan_rate[plan]' AND campaign = '$campaign_id'") or die(mysql_error());
		$plan_script = mysql_fetch_assoc($q);
		
		if($pl[$plan_rate["plan"]] == 0)
		{
			if ($plan_rate["plan"] == "121RPX039" || $plan_rate["plan"] == "121BPX039")
			{
				echo '<p><b><span style="color:#000080;">If your stated line is a duet line, a standard charge of $6 including GST will apply. If not, the line will be charged as an additional add–on line.</span></b></p>';
			}
			
			echo "<table width='100%'>";
			echo "<tr>";
			echo "<td style='padding:4.9pt;border-top:1pt solid black;border-right:1pt solid black;border-bottom:1pt solid black;border-left:1pt solid black;'>";
			echo $plan_script["script"];
			echo "</td>";
			echo "</tr>";
			echo "</table><br>";
			
			$pl[$plan_rate["plan"]] = 1;
		}
	}
	$next_btn = $next_default;
}

if($page == 18)
{
	echo $landline[18];
    echo $landline["NRO"];
	$next_btn = $next_default;
}

if($page == 19)
{
	if ($data["billing"] == "post")
	{
		echo $landline["19_post"];
	}
	elseif ($data["billing"] == "email")
	{
		echo $landline["19_email"];
	}
	echo $landline["dd2"];
	$next_btn = $next_default;
}

if($page == 20)
{
	echo $landline[20];
	$next_btn = $next_default;
}

if($page == 21)
{
	echo $landline[21];
	$next_btn = $next_default;
}

if($page == 22)
{
	echo $landline[23];
	echo $input["edit_details"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="parent.Submit()" style="display: none;" id="Btn_Next"  class="btn" value="SUBMIT" /></td>';
}

?>