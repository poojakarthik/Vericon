<?php

if($page == 1)
    {
        echo $landline[1];
    }

    if($page == 2)
    {
        echo $landline[3];
    }
	
	if($page == 3)
    {
        echo $landline[24];
    }

    if($page == 4)
    {
		if($plan == "TC049" || $plan == "TC059" || $plan == "TC099")
        {
            echo $landline["4_2"];
        }
        else
        {
            echo $landline[4];
        }
    }
    
    if($page == 5)
    {
        echo $landline[5];
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
        echo $landline[8];
    }
    
    if($page == 9)
    {
        echo $landline[9];
    }
    
    if($page == 10)
    {
        echo $landline[10];
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
        echo $landline[15];
		echo $landline[22];
    }
    
    if($page == 15)
    {
        echo $landline[16];
    }
    
    if($page == 16)
    {
        echo $landline[17];
    }
    
    if($page == 17)
    {
        echo $landline["rates"];
        if($plan == "TC049")
        {
            echo $landline["49.95"];
        }
        elseif($plan == "TC059")
        {
            echo $landline["59.95"];
        }
		elseif($plan == "TC064")
        {
            echo $landline["64.95"];
        }
        elseif($plan == "TC099")
        {
            echo $landline["99.95"];
        }
        elseif($plan == "TC104")
        {
            echo $landline["104.95"];
        }
        echo $landline["addon"];
		echo $landline["other_c"];
    }
    
    if($page == 18)
    {
        echo $landline[18];
        echo $landline["NRO"];
    }
    
    if($page == 19)
    {
        echo $landline[19];
        echo $landline["dd"];
    }
    
    if($page == 20)
    {
        echo $landline[20];
    }
    
    if($page == 21)
    {
        echo $landline[21];
    }
    
    if($page == 22)
    {
        echo $landline[23];
    }

?>