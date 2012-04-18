<?php
$next_default = '<td width="33.33%" align="right"><input type="button" onClick="Next()" style="display: none;" id="Btn_Next" class="next" /></td>';

if($page == 1)
{
	echo $bundle[1];
	$next_btn = $next_default;
}

if($page == 2)
{
	echo $bundle[2];
	$next_btn = $next_default;
}

if($page == 3)
{
	echo $landline[24];
	$next_btn = $next_default;
}

if($page == 4)
{
	echo $bundle[5];
	$next_btn = $next_default;
}

if($page == 5)
{
	echo $landline[6];
	$next_btn = $next_default;
}

if($page == 6)
{
	echo $landline[7];
	$next_btn = $next_default;
}

if($page == 7)
{
	echo $landline[8];
	echo $input["bus_info"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'bus_info\')" style="display: none;" id="Btn_Next" class="next" /></td>';
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
	echo $landline[12];
	echo $input["physical"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'physical\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 11)
{
	echo $landline[13];
	echo $input["postal"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'postal\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 12)
{
	echo $landline[14];
	echo $input["mobile"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="Next(' . $id . ',\'mobile\')" style="display: none;" id="Btn_Next" class="next" /></td>';
}

if($page == 13)
{
	echo $internet[14];
	$next_btn = $next_default;
}

if($page == 14)
{
	echo $internet["5_ADSL"];
	$next_btn = $next_default;
}

if($page == 15)
{
	echo $bundle[3];
	$next_btn = $next_default;
}

if($page == 16)
{
	echo $bundle[4];
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
			if($plan_rate["plan"] == "Bundle $84.95 24 Month Contract")
			{
				echo $bundle["84.95"];
			}
			elseif($plan_rate["plan"] == "Bundle $97.95 24 Month Contract")
			{
				echo $bundle["97.95"];
			}
			elseif($plan_rate["plan"] == "Bundle $99.95 24 Month Contract")
			{
				echo $bundle["99.95"];
			}
			elseif($plan_rate["plan"] == "Bundle $114.95 24 Month Contract")
			{
				echo $bundle["114.95"];
			}
			elseif($plan_rate["plan"] == "Bundle $122.95 24 Month Contract")
			{
				echo $bundle["122.95"];
			}
			elseif($plan_rate["plan"] == "Bundle $124.95 24 Month Contract")
			{
				echo $bundle["124.95"];
			}
			elseif($plan_rate["plan"] == "Bundle $119.95 24 Month Contract")
			{
				echo $bundle["119.95"];
			}
			elseif($plan_rate["plan"] == "Bundle $129.95 24 Month Contract")
			{
				echo $bundle["129.95"];
			}
			elseif($plan_rate["plan"] == "Bundle $134.95 24 Month Contract")
			{
				echo $bundle["134.95"];
			}
			elseif($plan_rate["plan"] == "Bundle $149.95 24 Month Contract")
			{
				echo $bundle["149.95"];
			}
			elseif($plan_rate["plan"] == "$49.95 24 Month Contract")
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
	if ($plan == "BC099" || $plan == "BC124" || $plan == "BC134" || $plan == "BC149")
	{
		echo $internet["18_ADSL_U"];
	}
	else
	{
		echo $internet["18_ADSL"];
	}
	$next_btn = $next_default;
}

if($page == 19)
{
	echo $landline[18];
	echo $bundle[6];
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
	echo $input["edit_details"];
	$next_btn = '<td width="33.33%" align="right"><input type="button" onClick="parent.Submit()" style="display: none;" id="Btn_Next" class="submit" /></td>';
}

?>