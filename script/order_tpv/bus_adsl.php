<?php
$next_default = '<td width="33.33%" align="right"><input type="button" onClick="Next()" style="display: none;" id="Btn_Next" class="next" /></td>';

if($page == 1)
{
	echo $internet[1];
	$next_btn = $next_default;
}

if($page == 2)
{
	echo $internet[3];
	$next_btn = $next_default;
}

if($page == 3)
{
	echo $internet[27];
	$next_btn = $next_default;
}

if($page == 4)
{
	echo $internet[4];
	$next_btn = $next_default;
}

if($page == 5)
{
	echo $internet["5_ADSL"];
	$next_btn = $next_default;
}

if($page == 6)
{
	echo $internet[6];
	$next_btn = $next_default;
}

if($page == 7)
{
	echo $internet[7];
	$next_btn = $next_default;
}

if($page == 8)
{
	echo $internet[8];
	echo $input["bus_info"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'bus_info\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 9)
{
	echo $internet[9];
	echo $input["name"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'name\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 10)
{
	echo $internet[10];
	echo $input["dob"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'dob\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 11)
{
	echo $internet[12];
	echo $input["physical"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'physical\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 12)
{
	echo $internet[13];
	echo $input["postal"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'postal\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 13)
{
	echo $internet[14];
	$next_btn = $next_default;
}

if($page == 14)
{
	echo $internet[15];
	echo $input["mobile"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'mobile\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 15)
{
	echo $internet[16];
	$next_btn = $next_default;
}

if($page == 16)
{
	echo $internet[17];
	echo $input["lines"];
	$next_btn = $next_default;
}

if($page == 17)
{
	echo $internet["rates"];
	$q4 = mysql_query("SELECT * FROM sales_packages WHERE sid = '$id'") or die(mysql_error());
	while ($plan_rate = mysql_fetch_assoc($q4))
	{
		if($pl[$plan_rate["plan"]] == 0)
		{
			if($plan_rate["plan"] == "ADSL 15GB 24 Month Contract")
			{
				echo $internet["15GB"];
			}
			elseif($plan_rate["plan"] == "ADSL 500GB 24 Month Contract")
			{
				echo $internet["500GB"];
			}
			elseif($plan_rate["plan"] == "ADSL Unlimited 24 Month Contract")
			{
				echo $internet["Unlimited"];
			}
			else
			{
				echo "<b>" . $plan_rate["plan"] . "</b> is not an ADSL plan, must be verified in a seperate recording!<br>Please go back and remove it from the packages!";
			}
			$pl[$plan_rate["plan"]] = 1;
		}
	}
	$next_btn = $next_default;
}

if($page == 18)
{
	echo $internet["18_ADSL"];
	$next_btn = $next_default;
}

if($page == 19)
{
	echo $internet[19];
	$next_btn = $next_default;
}

if($page == 20)
{
	echo $internet[20];
	echo $internet[24];
	echo $input["email"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'email\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 21)
{
	echo $internet[21];
	echo $landline["dd2"];
	$next_btn = $next_default;
}

if($page == 22)
{
	echo $internet[22];
	$next_btn = $next_default;
}

if($page == 23)
{
	echo $internet[23];
	$next_btn = $next_default;
}

if($page == 24)
{
	echo $internet[25];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="parent.Submit()" style="display: none;" id="Btn_Next" class="submit" /></td>';
}

?>