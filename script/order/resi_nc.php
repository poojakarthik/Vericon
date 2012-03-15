<?php

if($page == 1)
    {
        echo $landline[1];
    }

    if($page == 2)
    {
        echo $landline[2];
    }

    if($page == 3)
    {
        echo $landline[3];
    }
	
	if($page == 4)
    {
        echo $landline[24];
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
        echo $landline[17];
    }
    
    if($page == 15)
    {
        echo $landline["rates"];
        if($plan == "TN054")
        {
            echo $landline["54.95"];
        }
        elseif($plan == "TN069")
        {
            echo $landline["69.95"];
        }
        elseif($plan == "TN079")
        {
            echo $landline["79.95"];
        }
        elseif($plan == "TN109")
        {
            echo $landline["109.95"];
        }
        echo $landline["addon"];
		echo $landline["other_nc"];
    }
    
    if($page == 16)
    {
        echo $landline[18];
        echo $landline["No_Contract_ETF"];
    }
    
    if($page == 17)
    {
		echo $landline["dd3"];
		echo "<br><p><i><span style='color:#FF0000;'>Customer must sign up for Direct Debit.</span></i></p>";
        echo $landline["dd"];
    }
    
    if($page == 18)
    {
        echo $landline[20];
    }
    
    if($page == 19)
    {
        echo $landline[21];
    }

    if($page == 20)
    {
        echo $landline[23];
    }

?>