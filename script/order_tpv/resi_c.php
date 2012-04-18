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
	if($plan == "TC049" || $plan == "TC059" || $plan == "TC099")
	{
		echo $landline["4_2"];
	}
	else
	{
		echo $landline[4];
	}
	$next_btn = $next_default;
}

if($page == 6)
{
	echo $landline[5];
	$next_btn = $next_default;
}

if($page == 7)
{
	echo $landline[6];
	$next_btn = $next_default;
}

if($page == 8)
{
	echo $landline[7];
	$next_btn = $next_default;
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
	echo $landline[11];
	echo $input["id_info"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'id_info\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 12)
{
	echo $landline[12];
	echo $input["physical"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'physical\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 13)
{
	echo $landline[13];
	echo $input["postal"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'postal\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 14)
{
	echo $landline[14];
	echo $input["mobile"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'mobile\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 15)
{
	echo $landline[15];
	echo $landline[22];
	echo $input["email"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'email\')" style="display: none;" id="Btn_Next" class="next" /></td>';
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
	$q4 = mysql_query("SELECT * FROM sales_packages WHERE sid = '$id'") or die(mysql_error());
	while ($plan_rate = mysql_fetch_assoc($q4))
	{
		$q = mysql_query("SELECT name FROM plan_matrix WHERE id = '$plan_rate[plan]'") or die(mysql_error());
		$plan_name = mysql_fetch_row($q);
		$plan_rate["plan"] = $plan_name[0];
		
		if($pl[$plan_rate["plan"]] == 0)
		{
			if($plan_rate["plan"] == "$49.95 24 Month Contract")
			{
				echo $landline["49.95"];
			}
			elseif($plan_rate["plan"] == "$59.95 24 Month Contract")
			{
				echo $landline["59.95"];
			}
			elseif($plan_rate["plan"] == "$64.95 12 Month Contract")
			{
				echo $landline["64.95"];
			}
			elseif($plan_rate["plan"] == "$99.95 24 Month Contract")
			{
				echo $landline["99.95"];
			}
			elseif($plan_rate["plan"] == "$104.95 12 Month Contract")
			{
				echo $landline["104.95"];
			}
			elseif($plan_rate["plan"] == "Addon")
			{
				echo $landline["duet"];
			}
			else
			{
				echo "<b>" . $plan_rate["plan"] . "</b> is not a Contract plan, must be verified in a seperate recording!<br>Please go back and remove it from the packages!";
			}
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
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="parent.Submit()" style="display: none;" id="Btn_Next" class="submit" /></td>';
}

?>