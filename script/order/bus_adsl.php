<?php

if($page == 1)
    {
        echo $internet[1];
    }

    if($page == 2)
    {
        echo $internet[3];
    }
	
	if($page == 3)
    {
        echo $internet[27];
    }

    if($page == 4)
    {
        echo $internet[4];
    }
    
    if($page == 5)
    {
        echo $internet["5_ADSL"];
    }
    
    if($page == 6)
    {
        echo $internet[6];
    }

    if($page == 7)
    {
        echo $internet[7];
    }

    if($page == 8)
    {
        echo $internet[8];
    }
    
    if($page == 9)
    {
        echo $internet[9];
    }
    
    if($page == 10)
    {
        echo $internet[10];
    }
    
    if($page == 11)
    {
        echo $internet[12];
    }
    
    if($page == 12)
    {
        echo $internet[13];
    }
    
    if($page == 13)
    {
        echo $internet[14];
    }
    
    if($page == 14)
    {
        echo $internet[15];
    }
    
    if($page == 15)
    {
        echo $internet[16];
    }
    
    if($page == 16)
    {
        echo $internet[17];
    }
    
    if($page == 17)
    {
        echo $internet["rates"];
        if($plan == "AC015")
        {
            echo $internet["15GB"];
        }
        elseif($plan == "AC500")
        {
            echo $internet["500GB"];
        }
        elseif($plan == "AC999")
        {
            echo $internet["Unlimited"];
        }
    }
    
    if($page == 18)
    {
        if ($plan == "AC999")
		{
			echo $internet["18_ADSL_U"];
		}
		else
		{
			echo $internet["18_ADSL"];
		}
    }
    
    if($page == 19)
    {
        echo $internet[19];
    }
    
    if($page == 20)
    {
        echo $internet[20];
		echo $internet[24];
    }
    
    if($page == 21)
    {
        echo $internet[21];
        echo $landline["dd"];
    }
    
    if($page == 22)
    {
        echo $internet[22];
    }
    
    if($page == 23)
    {
        echo $internet[23];
    }
    
    if($page == 24)
    {
        echo $internet[25];
    }

?>