<?php

if($page == 1)
    {
        echo $bundle[1];
    }
	   
    if($page == 2)
    {
        echo $landline[2];
    }

    if($page == 3)
    {
        echo $bundle[2];
    }
	
	if($page == 4)
    {
        echo $landline[24];
    }
    
    if($page == 5)
    {
        echo $bundle[5];
    }

    if($page == 6)
    {
        echo $landline[6];
    }
    
    if($page == 7)
    {
        echo $landline[7];
    }
    
    if($page == 8)
    {
        echo $landline[9];
    }
    
    if($page == 9)
    {
        echo $landline[10];
    }
	
    if($page == 10)
    {
        echo $landline[11];
    }
    
    if($page == 11)
    {
        echo $landline[12];
    }
    
    if($page == 12)
    {
        echo $landline[13];
    }
    
    if($page == 13)
    {
        echo $landline[14];
    }
    
    if($page == 14)
    {
        echo $internet[14];
    }
    
    if($page == 15)
    {
        echo $internet["5_ADSL"];
    }
    
    if($page == 16)
    {
        echo $bundle[4];
    }
    
    if($page == 17)
    {
        echo $landline["rates"];
        if($plan == "BC084")
        {
            echo $bundle["84.95"];
        }
        elseif($plan == "BC097")
        {
            echo $bundle["97.95"];
        }
		elseif($plan == "BC099")
        {
            echo $bundle["99.95"];
        }
		elseif($plan == "BC114")
        {
            echo $bundle["114.95"];
        }
		elseif($plan == "BC122")
        {
            echo $bundle["122.95"];
        }
		elseif($plan == "BC124")
        {
            echo $bundle["124.95"];
        }
		elseif($plan == "BC119")
        {
            echo $bundle["119.95"];
        }
		elseif($plan == "BC129")
        {
            echo $bundle["129.95"];
        }
		elseif($plan == "BC134")
        {
            echo $bundle["134.95"];
        }
		elseif($plan == "BC149")
        {
            echo $bundle["149.95"];
        }
        echo $landline["addon"];
    }
    
    if($page == 18)
    {
        echo $internet["18_ADSL"];
    }
	
	if($page == 19)
    {
        echo $landline[18];
        echo $bundle[6];
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