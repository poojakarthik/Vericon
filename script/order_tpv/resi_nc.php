<?php
$next_default = '<td width="33.33%" align="right"><input type="button" onClick="Next()" style="display: none;" id="Btn_Next" class="next" /></td>';

if($page == 1)
{
	echo $landline[1];
	$next_btn = $next_default;
}

if($page == 2)
{
	echo $landline[2];
	$next_btn = $next_default;
}

if($page == 3)
{
	echo $landline[3];
	$next_btn = $next_default;
}

if($page == 4)
{
	echo $landline[24];
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
	echo $landline[9];
	echo $input["name"];
$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'name\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 9)
{
	echo $landline[10];
	echo $input["dob"];
$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'dob\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 10)
{
	echo $landline[11];
	echo $input["id_info"];
$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'id_info\')" style="display: none;" id="Btn_Next" class="next" /></td>';
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
	echo $landline[17];
	echo $input["lines"];
	$next_btn = $next_default;
}

if($page == 15)
{
	echo $landline["rates"];
	$q4 = mysql_query("SELECT * FROM sales_packages WHERE sid = '$id'") or die(mysql_error());
	while ($plan_rate = mysql_fetch_assoc($q4))
	{
		if($pl[$plan_rate["plan"]] == 0)
		{
			if($plan_rate["plan"] == "$54.95 No Contract")
			{
				echo $landline["54.95"];
			}
			elseif($plan_rate["plan"] == "$69.95 No Contract")
			{
				echo $landline["69.95"];
			}
			elseif($plan_rate["plan"] == "$79.95 No Contract")
			{
				echo $landline["79.95"];
			}
			elseif($plan_rate["plan"] == "$109.95 No Contract")
			{
				echo $landline["109.95"];
			}
			elseif($plan_rate["plan"] == "Addon")
			{
				echo $landline["addon1"];
			}
			elseif($plan_rate["plan"] == "Duet")
			{
				echo $landline["duet"];
			}
			else
			{
				echo $plan_rate["plan"] . " is not a No Contract plan, must be verified in a seperate recording!<br>Please go back and remove it from the packages!";
			}
			$pl[$plan_rate["plan"]] = 1;
		}
	}
	$next_btn = $next_default;
}

if($page == 16)
{
	echo $landline[18];
	echo $landline["No_Contract_ETF"];
	$next_btn = $next_default;
}

if($page == 17)
{
	echo $landline["dd3"];
	echo $input["email2"] . "<br><p><i><span style='color:#FF0000;'>Customer must sign up for Direct Debit.</span></i></p>";
	echo $landline["dd2"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'email2\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 18)
{
	echo $landline[20];
	$next_btn = $next_default;
}

if($page == 19)
{
	echo $landline[21];
	$next_btn = $next_default;
}

if($page == 20)
{
	echo $landline[23];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="parent.Submit()" style="display: none;" id="Btn_Next" class="submit" /></td>';
}

?>