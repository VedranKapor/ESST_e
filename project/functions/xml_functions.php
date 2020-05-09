<?php
global $xml;
global $esstCase;

	function create_case($ime, $datum,$valute,$jedinice, $opis, $newfolder, $sector, $fuel, $year, $elmix, $technology, $envirioment, $finance, $ver){
    $xml = new DOMDocument("1.0");

                 $root = $xml->createElement("Cases");
                 $xml->appendChild($root);
                            
                 $Case = $xml->createElement("Case");
    
                    $name   = $xml->createElement("name");
                    $nameText = $xml->createTextNode("$ime");
                    $name->appendChild($nameText);
                                
                    $date   = $xml->createElement("date");
                    $dateText = $xml->createTextNode("$datum");
                    $date->appendChild($dateText);
                    
                    $valuta = $xml->createElement("currency");
                    $valutaText = $xml->createTextNode("$valute");
                    $valuta->appendChild($valutaText);
                    
                    $units = $xml->createElement("units");
                    $unitsText = $xml->createTextNode("$jedinice");
                    $units->appendChild($unitsText);
                                
                    $description   = $xml->createElement("description");
                    $descriptionText = $xml->createTextNode("$opis");
                    $description->appendChild($descriptionText);

                    $version   = $xml->createElement("version");
                    $versionText = $xml->createTextNode("$ver");
                    $version->appendChild($versionText);

                $Case->appendChild($name);
                $Case->appendChild($date);
                $Case->appendChild($valuta);
                $Case->appendChild($units);
                $Case->appendChild($description);
                $Case->appendChild($version);
                
                //read sector array, initialize with 1 active sectors
                $Sectors = $xml->createElement("Sectors");
                    foreach ($sector as $key => $value) {
                     if ($value == "true"){
                        $$key = $xml->createElement("$key");
                        $nodeText = $xml->createTextNode("1");
                        $$key->appendChild($nodeText);
                     $Sectors->appendChild($$key);
                     }
                     else{
                        $$key = $xml->createElement("$key");
                        $nodeText = $xml->createTextNode("0");
                        $$key->appendChild($nodeText);
                     $Sectors->appendChild($$key);
                        
                     }
                    }
                 //read fuel array, initialize with 1 active fuels   
                $Fuels = $xml->createElement("Fuels");
                    foreach ($fuel as $key => $value) {
                     if ($value == "true"){
                        $$key = $xml->createElement("$key");
                        $nodeText = $xml->createTextNode("1");
                        $$key->appendChild($nodeText);
                     $Fuels->appendChild($$key);
                     }
                     else{
                        $$key = $xml->createElement("$key");
                        $nodeText = $xml->createTextNode("0");
                        $$key->appendChild($nodeText);
                     $Fuels->appendChild($$key);
                     }
                    }
                    
                
                 //read year array, initialize with 1 active years     
                $Years = $xml->createElement("Years");
                    foreach ($year as $key => $value) {
                        
                     if ($value == "true"){
                        //$tmp = substr($key, 1);
                        $tmp = $key;
                        $$key = $xml->createElement("_$key");
                        $nodeText = $xml->createTextNode("1");
                        $$key->appendChild($nodeText);
                     $Years->appendChild($$key);
                     }
                     else{
                        //$tmp = substr($key, 1);
                        $$key = $xml->createElement("_$key");
                        $nodeText = $xml->createTextNode("0");
                        $$key->appendChild($nodeText);
                     $Years->appendChild($$key);
                     }
                    }
                    //el mix fuels
                    $ElMix_fuels = $xml->createElement("ElMix_fuels");

                                                            //import export
                                                            // $ImportExport = $xml->createElement("ImportExport");
                                                            // $nodeText = $xml->createTextNode("1");
                                                            // $ImportExport->appendChild($nodeText);
                                                            // $ElMix_fuels->appendChild($ImportExport);

                    foreach ($elmix as $key => $value) {
                       // echo "elmix " . $key . " value " . $value . "<br>";
                     if ($value == "true"){
                        $$key = $xml->createElement("$key");
                        $nodeText = $xml->createTextNode("1");
                        $$key->appendChild($nodeText);
                        $ElMix_fuels->appendChild($$key);
                     }
                     else{
                        $$key = $xml->createElement("$key");
                        $nodeText = $xml->createTextNode("0");
                        $$key->appendChild($nodeText);
                     $ElMix_fuels->appendChild($$key);
                     }
                    }

                
                //node Final energy demand
                $Final_energy_demand = $xml->createElement("Final_energy_demand");
                //for svaku aktivnu godinu napraavi fed_bysectors  inicijalizuj na 1 svaki aktivan sektor
                foreach ($year as $year_key => $year_value) 
                { 
                    //$tmp="";
                    if ($year_value=="true")
                    {
                        // $tmp = substr($year_key, 1);
                        $tmp = $year_key;
                        $fed_bysectors = $xml->createElement("fed_bysectors");
                        $fed_bysectors->setAttribute("year","$tmp");
                      
                        foreach ($sector as $sector_key => $sector_value) 
                        {
                        if ($sector_value=="true")
                        {
                          $$sector_key = $xml->createElement("$sector_key");
                          $nodevalue = $xml->createTextNode('0');
                          $$sector_key->appendChild($nodevalue);
                        $fed_bysectors->appendChild($$sector_key);
                        }
                      else{
                        $$sector_key = $xml->createElement("$sector_key");
                        $nodevalue = $xml->createTextNode('');
                        $$sector_key->appendChild($nodevalue);
                        
                      $fed_bysectors->appendChild($$sector_key);
                          }
                        }
                    $Final_energy_demand->appendChild($fed_bysectors);
                    }
                      else
                      {
                        //$tmp = substr($year_key, 1);
                        $tmp = $year_key;
                        $fed_bysectors = $xml->createElement("fed_bysectors");
                        $fed_bysectors->setAttribute("year","$tmp");
                      
                        foreach ($sector as $sector_key => $sector_value) 
                        {
                        if ($sector_value=="true")
                        {
                          $$sector_key = $xml->createElement("$sector_key");
                          $nodevalue = $xml->createTextNode('');
                          $$sector_key->appendChild($nodevalue);
                        
                      $fed_bysectors->appendChild($$sector_key);
                        }
                          else
                          {
                            $$sector_key = $xml->createElement("$sector_key");
                          $nodevalue = $xml->createTextNode('');
                          $$sector_key->appendChild($nodevalue);
                        
                      $fed_bysectors->appendChild($$sector_key);
                          }
                        }
                        $Final_energy_demand->appendChild($fed_bysectors);
                        
                      }
                }
                  //fuelshares    
            foreach ($year as $year_key => $year_value) 
            { 
                //$tmp="";
                if ($year_value=="true")
                {
                    //$tmp = substr($year_key, 1);
                    $tmp = $year_key;
                    foreach ($sector as $sector_key => $sector_value) 
                    {
                        if ($sector_value=="true"){
                            $fed_fuel_shares = $xml->createElement("fed_fuelshares");
                            $fed_fuel_shares->setAttribute("year","$tmp");
                            $fed_fuel_shares->setAttribute("sector","$sector_key");
                            
                              foreach ($fuel as $fuel_key => $fuel_value) 
                              {
                                if ($fuel_value=="true")
                                {
                                  $$fuel_key = $xml->createElement("$fuel_key");
                                  $nodeValue = $xml->createTextNode("0");
                                  $$fuel_key->appendChild($nodeValue);
                                    
                                  $fed_fuel_shares->appendChild($$fuel_key);
                                }
                                else
                                {
                                  $$fuel_key = $xml->createElement("$fuel_key");
                                  $nodeValue = $xml->createTextNode("");
                                  $$fuel_key->appendChild($nodeValue);
                                    
                                  $fed_fuel_shares->appendChild($$fuel_key);
                                }
                              }
                              $Final_energy_demand->appendChild($fed_fuel_shares);
                            }
                            else 
                            {
                                $fed_fuel_shares = $xml->createElement("fed_fuelshares");
                            $fed_fuel_shares->setAttribute("year","$tmp");
                            $fed_fuel_shares->setAttribute("sector","$sector_key");
                            
                              foreach ($fuel as $fuel_key => $fuel_value) 
                              {
                                if ($fuel_value=="true")
                                {
                                  $$fuel_key = $xml->createElement("$fuel_key");
                                  $nodeValue = $xml->createTextNode("");
                                  $$fuel_key->appendChild($nodeValue);
                                    
                                  $fed_fuel_shares->appendChild($$fuel_key);
                                }
                                else
                                {
                                  $$fuel_key = $xml->createElement("$fuel_key");
                                  $nodeValue = $xml->createTextNode("");
                                  $$fuel_key->appendChild($nodeValue);
                                    
                                  $fed_fuel_shares->appendChild($$fuel_key);
                                }
                              }
                              $Final_energy_demand->appendChild($fed_fuel_shares);    
                            }
                        }
                       
                    }
                    else
                    {
                    //$tmp = substr($year_key, 1);
                    $tmp = $year_key;
                    foreach ($sector as $sector_key => $sector_value) 
                    {
                        if ($sector_value=="true"){
                            $fed_fuel_shares = $xml->createElement("fed_fuelshares");
                            $fed_fuel_shares->setAttribute("year","$tmp");
                            $fed_fuel_shares->setAttribute("sector","$sector_key");
                            
                              foreach ($fuel as $fuel_key => $fuel_value) 
                              {
                                if ($fuel_value=="true")
                                {
                                  $$fuel_key = $xml->createElement("$fuel_key");
                                  $nodeValue = $xml->createTextNode("");
                                  $$fuel_key->appendChild($nodeValue);
                                    
                                  $fed_fuel_shares->appendChild($$fuel_key);
                                }
                                else
                                {
                                  $$fuel_key = $xml->createElement("$fuel_key");
                                  $nodeValue = $xml->createTextNode("");
                                  $$fuel_key->appendChild($nodeValue);
                                    
                                  $fed_fuel_shares->appendChild($$fuel_key);
                                }
                              }
                              $Final_energy_demand->appendChild($fed_fuel_shares);
                            }
                            else 
                            {
                                $fed_fuel_shares = $xml->createElement("fed_fuelshares");
                            $fed_fuel_shares->setAttribute("year","$tmp");
                            $fed_fuel_shares->setAttribute("sector","$sector_key");
                            
                              foreach ($fuel as $fuel_key => $fuel_value) 
                              {
                                if ($fuel_value=="true")
                                {
                                  $$fuel_key = $xml->createElement("$fuel_key");
                                  $nodeValue = $xml->createTextNode("");
                                  $$fuel_key->appendChild($nodeValue);
                                    
                                  $fed_fuel_shares->appendChild($$fuel_key);
                                }
                                else
                                {
                                  $$fuel_key = $xml->createElement("$fuel_key");
                                  $nodeValue = $xml->createTextNode("");
                                  $$fuel_key->appendChild($nodeValue);
                                    
                                  $fed_fuel_shares->appendChild($$fuel_key);
                                }
                              }
                              $Final_energy_demand->appendChild($fed_fuel_shares);    
                            }
                        }
                        
                    }

            }
            
            //fed_losses
             foreach ($year as $year_key => $year_value) 
                { 
                    //$tmp="";
                    if ($year_value=="true")
                    {
                        //$tmp = substr($year_key, 1);
                        $tmp = $year_key;
                        $fed_losses = $xml->createElement("fed_losses");
                        $fed_losses->setAttribute("year","$tmp");
                      
                        foreach ($fuel as $fuel_key => $fuel_value) 
                        {
                        if ($fuel_value=="true")
                        {
                          $$fuel_key = $xml->createElement("$fuel_key");
                          $nodevalue = $xml->createTextNode('0');
                          $$fuel_key->appendChild($nodevalue);
                        $fed_losses->appendChild($$fuel_key);
                        }
                      else{
                        $$fuel_key = $xml->createElement("$fuel_key");
                        $nodevalue = $xml->createTextNode(null);
                        $$fuel_key->appendChild($nodevalue);
                        
                      $fed_losses->appendChild($$fuel_key);
                          }
                        }
                    $Final_energy_demand->appendChild($fed_losses);
                    }
                      else
                      {
                        //$tmp = substr($year_key, 1);
                        $tmp = $year_key;
                        $fed_losses = $xml->createElement("fed_losses");
                        $fed_losses->setAttribute("year","$tmp");
                      
                        foreach ($fuel as $fuel_key => $fuel_value) 
                        {
                        if ($fuel_value=="true")
                        {
                          $$fuel_key = $xml->createElement("$fuel_key");
                          $nodevalue = $xml->createTextNode(null);
                          $$fuel_key->appendChild($nodevalue);
                        $fed_losses->appendChild($$fuel_key);
                        }
                      else{
                        $$fuel_key = $xml->createElement("$fuel_key");
                        $nodevalue = $xml->createTextNode(null);
                        $$fuel_key->appendChild($nodevalue);
                        
                      $fed_losses->appendChild($$fuel_key);
                          }
                        }
                    $Final_energy_demand->appendChild($fed_losses);
                        
                      }
                }
                
                //node Secondary Energy Supplies
                $Secondary_energy_supplies = $xml->createElement("Secondary_energy_supplies");
                //dodaj ses_elmix
                 foreach ($year as $year_key => $year_value) 
                { 
                    //$tmp="";
                    if ($year_value=="true")
                    {
                        //$tmp = substr($year_key, 1);
                        $tmp = $year_key;
                        $ses_elmix = $xml->createElement("ses_elmix");
                        $ses_elmix->setAttribute("year","$tmp");
                        
                        foreach ($elmix as $key => $value) {
                         if ($value == "true"){
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("0");
                            $$key->appendChild($nodeText);
                         $ses_elmix->appendChild($$key);
                         }
                         else{
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("");
                            $$key->appendChild($nodeText);
                         $ses_elmix->appendChild($$key);
                         }
                        }                            
                        $Secondary_energy_supplies->appendChild($ses_elmix);
                    }
                      else
                      {
                        //$tmp = substr($year_key, 1);
                        $tmp = $year_key;
                        $ses_elmix = $xml->createElement("ses_elmix");
                        $ses_elmix->setAttribute("year","$tmp");
                        
                        foreach ($elmix as $key => $value) {
                         if ($value == "true"){
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("");
                            $$key->appendChild($nodeText);
                         $ses_elmix->appendChild($$key);
                         }
                         else{
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("");
                            $$key->appendChild($nodeText);
                         $ses_elmix->appendChild($$key);
                        }
                        }
                        $Secondary_energy_supplies->appendChild($ses_elmix);
                        
                      }
                }
                
                //node Primary+ Energy Supplies
                $Primary_energy_supplies = $xml->createElement("Primary_energy_supplies");
                //dodaj ses_elmix
                 foreach ($year as $year_key => $year_value) 
                { 
                    //$tmp="";
                    if ($year_value=="true")
                    {
                        //$tmp = substr($year_key, 1);
                        $tmp = $year_key;
                        $pes_domestic_production = $xml->createElement("pes_domestic_production");
                        $pes_domestic_production->setAttribute("year","$tmp");
                        
                        foreach ($fuel as $key => $value) {
                         if ($value == "true" && $key!='Electricity' && $key!='Heat'){
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("0");
                            $$key->appendChild($nodeText);
                         $pes_domestic_production->appendChild($$key);
                         }
                         else if ($value == "false" && $key!='Electricity' && $key!='Heat'){
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("");
                            $$key->appendChild($nodeText);
                         $pes_domestic_production->appendChild($$key);
                         }
                        }                            
                        $Primary_energy_supplies->appendChild($pes_domestic_production);
                    }
                      else
                      {
                        //$tmp = substr($year_key, 1);
                        $tmp = $year_key;
                        $pes_domestic_production = $xml->createElement("pes_domestic_production");
                        $pes_domestic_production->setAttribute("year","$tmp");
                        
                        foreach ($fuel as $key => $value) {
                         if ($value == "true" && $key!='Electricity'&& $key!='Heat'){
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("");
                            $$key->appendChild($nodeText);
                         $pes_domestic_production->appendChild($$key);
                         }
                         else if ($value == "false" && $key!='Electricity' && $key!='Heat'){
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("");
                            $$key->appendChild($nodeText);
                         $pes_domestic_production->appendChild($$key);
                        }
                        }
                        $Primary_energy_supplies->appendChild($pes_domestic_production);
                        
                      }
                }
                
                 //node Transformation
                $Transformation = $xml->createElement("Transformation");
                //dodaj tra_efficiency
                 foreach ($year as $year_key => $year_value) 
                { 
                    //$tmp="";
                    if ($year_value=="true")
                    {
                        //$tmp = substr($year_key, 1);
                        $tmp = $year_key;
                        $tra_efficiency = $xml->createElement("tra_efficiency");
                        $tra_efficiency->setAttribute("year","$tmp");
                        


                        foreach ($elmix as $key => $value) {
                            //echo $key . " " . $value."<br>";
                         if ($value == "true" && $key!='ImportExport' && $key!='Hydro'&& $key!='Solar'&& $key!='Wind'&& $key!='Nuclear'&& $key!='Geothermal')
                         {
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("0");
                            $$key->appendChild($nodeText);
                         $tra_efficiency->appendChild($$key);
                         }
                         else if ($value == "true" && $key!='ImportExport' && ($key=='Hydro'||$key=='Solar'||$key=='Wind'))
                         {
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("100");
                            $$key->appendChild($nodeText);
                         $tra_efficiency->appendChild($$key);
                         }
                         else if ($value == "true" && $key!='ImportExport' && $key=='Geothermal')
                         {
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("10");
                            $$key->appendChild($nodeText);
                         $tra_efficiency->appendChild($$key);
                         }
                         else if ($value == "true" && $key!='ImportExport' && $key=='Nuclear')
                         {
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("33");
                            $$key->appendChild($nodeText);
                         $tra_efficiency->appendChild($$key);
                         }
                         else if ($value == "false" && $key!='ImportExport')
                         {
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("");
                            $$key->appendChild($nodeText);
                         $tra_efficiency->appendChild($$key);
                         }
                        }                            
                        $Transformation->appendChild($tra_efficiency);
                    }
                      else
                      {
                        //$tmp = substr($year_key, 1);
                        $tmp = $year_key;
                        $tra_efficiency = $xml->createElement("tra_efficiency");
                        $tra_efficiency->setAttribute("year","$tmp");
                        
                        foreach ($elmix as $key => $value) {
                         if ($value == "true" && $key!='ImportExport'){
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("");
                            $$key->appendChild($nodeText);
                         $tra_efficiency->appendChild($$key);
                         }
                         else if ($value == "false" && $key!='ImportExport'){
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("");
                            $$key->appendChild($nodeText);
                         $tra_efficiency->appendChild($$key);
                        }
                        }
                        $Transformation->appendChild($tra_efficiency);
                        
                      }
                }
                
                

                
                
                //dodaj tra_reserve capacity
                foreach ($year as $year_key => $year_value) { 
                    if ($year_value=="true") {
                        //$tmp = substr($year_key, 1);
                        $tmp = $year_key;
                        $reserve_capacity = $xml->createElement("tra_reserve_capacity");
                        $reserve_capacity->setAttribute("year","$tmp");
                        $reserve_capacity->setAttribute("total","0");
                        
                        foreach ($elmix as $key => $value) {
                         if ($value == "true" && $key!='ImportExport' && $key!='Hydro'&& $key!='Solar'&& $key!='Wind'&& $key!='Nuclear'&& $key!='Geothermal'&& $key!='Biofuels' && $key!='Wind' && $key!='Peat' && $key!='Waste') {
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("0");
                            $$key->appendChild($nodeText);
                         $reserve_capacity->appendChild($$key);
                         }
                         else if ($value == "true" && ($key=='ImportExport' || $key=='Hydro' || $key=='Solar' || $key=='Wind' || $key=='Nuclear' || $key=='Geothermal' || $key=='Biofuels' || $key=='Wind' || $key=='Peat' || $key=='Waste')) {
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("");
                            $$key->appendChild($nodeText);
                         $reserve_capacity->appendChild($$key);
                         }
                         else if ($value == "false"){
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("");
                            $$key->appendChild($nodeText);
                         $reserve_capacity->appendChild($$key);
                         }
                        }                            
                        $Transformation->appendChild($reserve_capacity);
                    }
                    else {
                        //$tmp = substr($year_key, 1);
                        $tmp = $year_key;
                        $reserve_capacity = $xml->createElement("tra_reserve_capacity");
                        $reserve_capacity->setAttribute("year","$tmp");
                        $reserve_capacity->setAttribute("total","0");
                        
                        foreach ($elmix as $key => $value) {
                            if ($value == "true" && $key!='ImportExport'){
                                $$key = $xml->createElement("$key");
                                $nodeText = $xml->createTextNode("");
                                $$key->appendChild($nodeText);
                            $reserve_capacity->appendChild($$key);
                            }
                            else if ($value == "false" && $key!='ImportExport'){
                                $$key = $xml->createElement("$key");
                                $nodeText = $xml->createTextNode("");
                                $$key->appendChild($nodeText);
                            $reserve_capacity->appendChild($$key);
                            }
                        }
                        $Transformation->appendChild($reserve_capacity);
                    }
                }
                
                // tra_carbon_cost
                // foreach ($year as $year_key => $year_value) { 
                //     if ($year_value=="true") {
                //         $tmp = $year_key;
                //         $carbon_cost = $xml->createElement("tra_carbon_cost");
                //         $carbon_cost->setAttribute("year","$tmp");
                //         $carbon_cost->setAttribute("total","0");
                                                    
                //         $Transformation->appendChild($carbon_cost);
                //     }
                //     else {
                //         $tmp = $year_key;
                //         $carbon_cost = $xml->createElement("tra_carbon_cost");
                //         $carbon_cost->setAttribute("year","$tmp");
                //         $carbon_cost->setAttribute("total","");
                        
                //         $Transformation->appendChild($carbon_cost);
                //     }
                // }
                // tra_carbon_cost
                foreach ($year as $year_key => $year_value) { 
                    if ($year_value=="true") {
                        $tmp = $year_key;

                        $tra_carbon_cost = $xml->createElement("tra_carbon_cost");
                        $tra_carbon_cost->setAttribute("year","$tmp");
                        
                        $carbon_cost = $xml->createElement("carbon_cost");
                        $nodeText = $xml->createTextNode("0");

                        $carbon_cost->appendChild($nodeText);
                        $tra_carbon_cost->appendChild($carbon_cost);                
                        $Transformation->appendChild($tra_carbon_cost);
                    } else {
                        $tmp = $year_key;

                        $tra_carbon_cost = $xml->createElement("tra_carbon_cost");
                        $tra_carbon_cost->setAttribute("year","$tmp");
                        
                        $carbon_cost = $xml->createElement("carbon_cost");
                        $nodeText = $xml->createTextNode("");

                        $carbon_cost->appendChild($nodeText);
                        $tra_carbon_cost->appendChild($carbon_cost);
                                  
                        $Transformation->appendChild($tra_carbon_cost);
                    }
                }

                // tra dicoun rate
                foreach ($year as $year_key => $year_value) { 
                    if ($year_value=="true") {
                        $tmp = $year_key;

                        $tra_discount_rate = $xml->createElement("tra_discount_rate");
                        $tra_discount_rate->setAttribute("year","$tmp");
                        
                        $discount_rate = $xml->createElement("discount_rate");
                        $nodeText = $xml->createTextNode("0");

                        $discount_rate->appendChild($nodeText);
                        $tra_discount_rate->appendChild($discount_rate);                
                        $Transformation->appendChild($tra_discount_rate);
                    } else {
                        $tmp = $year_key;

                        $tra_discount_rate = $xml->createElement("tra_discount_rate");
                        $tra_discount_rate->setAttribute("year","$tmp");
                        
                        $discount_rate = $xml->createElement("discount_rate");
                        $nodeText = $xml->createTextNode("");

                        $discount_rate->appendChild($nodeText);
                        $tra_discount_rate->appendChild($discount_rate);
                                  
                        $Transformation->appendChild($tra_discount_rate);
                    }
                }

                                
                //dodaj construction time
                foreach ($year as $year_key => $year_value){ 
                    if ($year_value=="true") {
                        $tmp = $year_key;
                        $construction_time = $xml->createElement("construction_time");
                        $construction_time->setAttribute("year","$tmp");
                        foreach ($elmix as $key => $value) {
                         if ($value == "true" && $key!='ImportExport' && $key!='Storage') {
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("0");
                            $$key->appendChild($nodeText);
                         $construction_time->appendChild($$key);
                         }
                         else if ($value == "false" && $key!='ImportExport' && $key!='Storage'){
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("");
                            $$key->appendChild($nodeText);
                         $construction_time->appendChild($$key);
                         }
                        }                            
                        $Transformation->appendChild($construction_time);
                    }
                    else{
                        $tmp = $year_key;
                        $construction_time = $xml->createElement("construction_time");
                        $construction_time->setAttribute("year","$tmp");
                        
                        foreach ($elmix as $key => $value) {
                         if ($value == "true" && $key!='ImportExport'&& $key!='Storage'){
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("");
                            $$key->appendChild($nodeText);
                            $construction_time->appendChild($$key);
                         }
                         else if ($value == "false" && $key!='ImportExport'&& $key!='Storage'){
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("");
                            $$key->appendChild($nodeText);
                            $construction_time->appendChild($$key);
                            }
                        }
                        $Transformation->appendChild($construction_time);
                      }
                }

                                
                //dodaj ens capacity
                 foreach ($year as $year_key => $year_value){ 
                    if ($year_value=="true")
                    {
                        $tmp = $year_key;
                        $ens_capacity = $xml->createElement("ens_capacity");
                        $ens_capacity->setAttribute("year","$tmp");
                        
                        foreach ($elmix as $key => $value) {
                         if ($value == "true")
                         {
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("0");
                            $$key->appendChild($nodeText);
                         $ens_capacity->appendChild($$key);
                         }

                         else if ($value == "false"){
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("");
                            $$key->appendChild($nodeText);
                         $ens_capacity->appendChild($$key);
                         }
                        }                            
                        $Transformation->appendChild($ens_capacity);
                    }
                    else{
                        $tmp = $year_key;
                        $ens_capacity = $xml->createElement("ens_capacity");
                        $ens_capacity->setAttribute("year","$tmp");
                        
                        foreach ($elmix as $key => $value) {
                         if ($value == "true" && $key!='ImportExport'){
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("");
                            $$key->appendChild($nodeText);
                            $ens_capacity->appendChild($$key);
                         }
                         else if ($value == "false" && $key!='ImportExport'){
                            $$key = $xml->createElement("$key");
                            $nodeText = $xml->createTextNode("");
                            $$key->appendChild($nodeText);
                            $ens_capacity->appendChild($$key);
                            }
                        }
                        $Transformation->appendChild($ens_capacity);
                      }
                }
                
                
                
                
                //tra load factor
                 foreach ($year as $year_key => $year_value) 
                { 
                    //$tmp="";
                    if ($year_value=="true")
                    {
                        //$tmp = substr($year_key, 1);
                        $tmp = $year_key;
                        $tra_loadfactor = $xml->createElement("tra_loadfactor");
                        $tra_loadfactor->setAttribute("year","$tmp");
                        
                            $LoadFactor = $xml->createElement("Load_factor");
                            $nodeText = $xml->createTextNode("0");
                            $LoadFactor->appendChild($nodeText);
                         $tra_loadfactor->appendChild($LoadFactor);
                         
                                          
                        $Transformation->appendChild($tra_loadfactor);
                    }
                      else
                        {
                        //$tmp = substr($year_key, 1);
                        $tmp = $year_key;
                        $tra_loadfactor = $xml->createElement("tra_loadfactor");
                        $tra_loadfactor->setAttribute("year","$tmp");
                        
                      
                            $LoadFactor = $xml->createElement("Load_factor");
                            $nodeText = $xml->createTextNode("");
                            $LoadFactor->appendChild($nodeText);
                         $tra_loadfactor->appendChild($LoadFactor);
                         
                                    
                        $Transformation->appendChild($tra_loadfactor);
                    }
                }
                
                
                    //tra lifetime
                //  foreach ($elmix as $key => $value) 
                // { 
                //     if ($value=="true" && $key !='ImportExport')
                //     {
                //         $tra_lifetime = $xml->createElement("tra_lifetime");
                //         $tra_lifetime->setAttribute("technology","$key");
                        
                //             $Lifetime = $xml->createElement("Lifetime");
                //             $nodeText = $xml->createTextNode("0");
                //             $Lifetime->appendChild($nodeText);
                //          $tra_lifetime->appendChild($Lifetime);
                         
                                          
                //         $Transformation->appendChild($tra_lifetime);
                //     }
                //       else if ($value=="false" && $key !='ImportExport')
                //         {
                       
                //         $tra_lifetime = $xml->createElement("tra_lifetime");
                //         $tra_lifetime->setAttribute("technology","$key");
                        
                      
                //             $Lifetime = $xml->createElement("Lifetime");
                //             $nodeText = $xml->createTextNode("");
                //             $Lifetime->appendChild($nodeText);
                //          $tra_lifetime->appendChild($Lifetime);
                         
                                    
                //         $Transformation->appendChild($tra_lifetime);
                //     }
                // }
                    //dodaj construction time
                    foreach ($year as $year_key => $year_value){ 
                        if ($year_value=="true") {
                            $tmp = $year_key;
                            $tra_lifetime = $xml->createElement("tra_lifetime");
                            $tra_lifetime->setAttribute("year","$tmp");
                            foreach ($elmix as $key => $value) {
                             if ($value == "true" && $key!='ImportExport' && $key!='Storage') {
                                $$key = $xml->createElement("$key");
                                $nodeText = $xml->createTextNode("0");
                                $$key->appendChild($nodeText);
                             $tra_lifetime->appendChild($$key);
                             }
                             else if ($value == "false" && $key!='ImportExport' && $key!='Storage'){
                                $$key = $xml->createElement("$key");
                                $nodeText = $xml->createTextNode("");
                                $$key->appendChild($nodeText);
                             $tra_lifetime->appendChild($$key);
                             }
                            }                            
                            $Transformation->appendChild($tra_lifetime);
                        }
                        else{
                            $tmp = $year_key;
                            $tra_lifetime = $xml->createElement("tra_lifetime");
                            $tra_lifetime->setAttribute("year","$tmp");
                            
                            foreach ($elmix as $key => $value) {
                             if ($value == "true" && $key!='ImportExport'&& $key!='Storage'){
                                $$key = $xml->createElement("$key");
                                $nodeText = $xml->createTextNode("");
                                $$key->appendChild($nodeText);
                                $tra_lifetime->appendChild($$key);
                             }
                             else if ($value == "false" && $key!='ImportExport'&& $key!='Storage'){
                                $$key = $xml->createElement("$key");
                                $nodeText = $xml->createTextNode("");
                                $$key->appendChild($nodeText);
                                $tra_lifetime->appendChild($$key);
                                }
                            }
                            $Transformation->appendChild($tra_lifetime);
                          }
                    }
                
                
                
                
                //pes technical data
                 foreach ($year as $year_key => $year_value) 
                { 
                    //$tmp="";
                    if ($year_value=="true")
                    {
                        foreach($elmix as $elmix_key=>$elmix_value)
                        {
                            if($elmix_value=="true" && $elmix_key!='ImportExport')
                            {
                                //$tmp = substr($year_key, 1);
                                $tmp = $year_key;
                                $tra_technical = $xml->createElement("tra_technical");
                                $tra_technical->setAttribute("year","$tmp");
                                $tra_technical->setAttribute("technology","$elmix_key");
                                
                                foreach ($technology as $key => $value) 
                                {
                                 if ($value == "1")
                                 {
                                    $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("0");
                                    $$key->appendChild($nodeText);
                                 $tra_technical->appendChild($$key);
                                 }
                                 else
                                 {
                                  $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("");
                                    $$key->appendChild($nodeText);
                                 $tra_technical->appendChild($$key);
                                 }
                                                            
                                $Transformation->appendChild($tra_technical);
                                }
                            
                            }
                            else if ($elmix_value=="false" && $elmix_key!='ImportExport')
                             {
                                //$tmp = substr($year_key, 1);
                                $tmp = $year_key;
                                $tra_technical = $xml->createElement("tra_technical");
                                $tra_technical->setAttribute("year","$tmp");
                                $tra_technical->setAttribute("technology","$elmix_key");
                                
                                foreach ($technology as $key => $value) 
                                {
                                 if ($value == "1")
                                 {
                                    $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("");
                                    $$key->appendChild($nodeText);
                                 $tra_technical->appendChild($$key);
                                 }
                                 else
                                 {
                                  $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("");
                                    $$key->appendChild($nodeText);
                                 $tra_technical->appendChild($$key);
                                 }
                                                            
                                $Transformation->appendChild($tra_technical);
                                }
                            
                            }
                        }
                     }
                     else
                     {
                        
                        foreach($elmix as $elmix_key=>$elmix_value)
                        {
                            if($elmix_value=="true" && $elmix_key!='ImportExport')
                            {
                                //$tmp = substr($year_key, 1);
                                $tmp = $year_key;
                                $tra_technical = $xml->createElement("tra_technical");
                                $tra_technical->setAttribute("year","$tmp");
                                $tra_technical->setAttribute("technology","$elmix_key");
                                
                                foreach ($technology as $key => $value) 
                                {
                                 if ($value == "1")
                                 {
                                    $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("");
                                    $$key->appendChild($nodeText);
                                 $tra_technical->appendChild($$key);
                                 }
                                 else
                                 {
                                  $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("");
                                    $$key->appendChild($nodeText);
                                 $tra_technical->appendChild($$key);
                                 }
                                                            
                                $Transformation->appendChild($tra_technical);
                                }
                            
                            }
                            else if ($elmix_value=="false" && $elmix_key!='ImportExport')
                             {
                                //$tmp = substr($year_key, 1);
                                $tmp = $year_key;
                                $tra_technical = $xml->createElement("tra_technical");
                                $tra_technical->setAttribute("year","$tmp");
                                $tra_technical->setAttribute("technology","$elmix_key");
                                
                                foreach ($technology as $key => $value) 
                                {
                                 if ($value == "1")
                                 {
                                    $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("");
                                    $$key->appendChild($nodeText);
                                 $tra_technical->appendChild($$key);
                                 }
                                 else
                                 {
                                  $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("");
                                    $$key->appendChild($nodeText);
                                 $tra_technical->appendChild($$key);
                                 }
                                                            
                                $Transformation->appendChild($tra_technical);
                                }
                            
                            }
                        }
                        
                      }
                }
                
                //pes envirioment data
                 foreach ($year as $year_key => $year_value) 
                { 
                    //$tmp="";
                    if ($year_value=="true")
                    {
                        foreach($elmix as $elmix_key=>$elmix_value)
                        {
                            if($elmix_value=="true" && $elmix_key!='ImportExport')
                            {
                                //$tmp = substr($year_key, 1);
                                $tmp = $year_key;
                                $tra_envirioment = $xml->createElement("tra_envirioment");
                                $tra_envirioment->setAttribute("year","$tmp");
                                $tra_envirioment->setAttribute("technology","$elmix_key");
                                
                                foreach ($envirioment as $key => $value) 
                                {
                                 if ($value == "1")
                                 {
                                    $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("0");
                                    $$key->appendChild($nodeText);
                                 $tra_envirioment->appendChild($$key);
                                 }
                                 else
                                 {
                                  $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("");
                                    $$key->appendChild($nodeText);
                                 $tra_envirioment->appendChild($$key);
                                 }
                                                            
                                $Transformation->appendChild($tra_envirioment);
                                }
                            
                            }
                            else if ($elmix_value=="false" && $elmix_key!='ImportExport')
                             {
                                //$tmp = substr($year_key, 1);
                                $tmp = $year_key;
                                $tra_envirioment = $xml->createElement("tra_envirioment");
                                $tra_envirioment->setAttribute("year","$tmp");
                                $tra_envirioment->setAttribute("technology","$elmix_key");
                                
                                foreach ($envirioment as $key => $value) 
                                {
                                 if ($value == "1")
                                 {
                                    $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("");
                                    $$key->appendChild($nodeText);
                                 $tra_envirioment->appendChild($$key);
                                 }
                                 else
                                 {
                                  $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("");
                                    $$key->appendChild($nodeText);
                                 $tra_envirioment->appendChild($$key);
                                 }
                                                            
                                $Transformation->appendChild($tra_envirioment);
                                }
                            
                            }
                        }
                     }
                     else
                     {
                        
                        foreach($elmix as $elmix_key=>$elmix_value)
                        {
                            if($elmix_value=="true" && $elmix_key!='ImportExport')
                            {
                                //$tmp = substr($year_key, 1);
                                $tmp = $year_key;
                                $tra_envirioment = $xml->createElement("tra_envirioment");
                                $tra_envirioment->setAttribute("year","$tmp");
                                $tra_envirioment->setAttribute("technology","$elmix_key");
                                
                                foreach ($envirioment as $key => $value) 
                                {
                                 if ($value == "1")
                                 {
                                    $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("");
                                    $$key->appendChild($nodeText);
                                 $tra_envirioment->appendChild($$key);
                                 }
                                 else
                                 {
                                    $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("");
                                    $$key->appendChild($nodeText);
                                 $tra_envirioment->appendChild($$key);
                                 }
                                                            
                                $Transformation->appendChild($tra_envirioment);
                                }
                            
                            }
                            else if ($elmix_value=="false" && $elmix_key!='ImportExport')
                             {
                                //$tmp = substr($year_key, 1);
                                $tmp = $year_key;
                                $tra_envirioment = $xml->createElement("tra_envirioment");
                                $tra_envirioment->setAttribute("year","$tmp");
                                $tra_envirioment->setAttribute("technology","$elmix_key");
                                
                                foreach ($envirioment as $key => $value) 
                                {
                                 if ($value == "1")
                                 {
                                    $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("");
                                    $$key->appendChild($nodeText);
                                 $tra_envirioment->appendChild($$key);
                                 }
                                 else
                                 {
                                  $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("");
                                    $$key->appendChild($nodeText);
                                 $tra_envirioment->appendChild($$key);
                                 }
                                                            
                                $Transformation->appendChild($tra_envirioment);
                                }
                            
                            }
                        }
                        
                      }
                }
                
                //pes finance data
                foreach ($year as $year_key => $year_value) 
                { 
                    //$tmp="";
                    if ($year_value=="true")
                    {
                        foreach($elmix as $elmix_key=>$elmix_value)
                        {
                            if($elmix_value=="true" && $elmix_key!='ImportExport')
                            {
                                //$tmp = substr($year_key, 1);
                                $tmp = $year_key;
                                $tra_finance = $xml->createElement("tra_finance");
                                $tra_finance->setAttribute("year","$tmp");
                                $tra_finance->setAttribute("technology","$elmix_key");
                                
                                foreach ($finance as $key => $value) 
                                {
                                 if ($value == "1")
                                 {
                                    $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("0");
                                    $$key->appendChild($nodeText);
                                 $tra_finance->appendChild($$key);
                                 }
                                 else
                                 {
                                  $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("");
                                    $$key->appendChild($nodeText);
                                 $tra_finance->appendChild($$key);
                                 }
                                                            
                                $Transformation->appendChild($tra_finance);
                                }
                            
                            }
                            else if ($elmix_value=="false" && $elmix_key!='ImportExport')
                             {
                                //$tmp = substr($year_key, 1);
                                $tmp = $year_key;
                                $tra_finance = $xml->createElement("tra_finance");
                                $tra_finance->setAttribute("year","$tmp");
                                $tra_finance->setAttribute("technology","$elmix_key");
                                
                                foreach ($finance as $key => $value) 
                                {
                                 if ($value == "1")
                                 {
                                    $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("");
                                    $$key->appendChild($nodeText);
                                 $tra_finance->appendChild($$key);
                                 }
                                 else
                                 {
                                  $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("");
                                    $$key->appendChild($nodeText);
                                 $tra_finance->appendChild($$key);
                                 }
                                                            
                                $Transformation->appendChild($tra_finance);
                                }
                            
                            }
                        }
                     }
                     else
                     {
                        
                        foreach($elmix as $elmix_key=>$elmix_value)
                        {
                            if($elmix_value=="true" && $elmix_key!='ImportExport')
                            {
                                //$tmp = substr($year_key, 1);
                                $tmp = $year_key;
                                $tra_finance = $xml->createElement("tra_finance");
                                $tra_finance->setAttribute("year","$tmp");
                                $tra_finance->setAttribute("technology","$elmix_key");
                                
                                foreach ($finance as $key => $value) 
                                {
                                 if ($value == "1")
                                 {
                                    $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("");
                                    $$key->appendChild($nodeText);
                                 $tra_finance->appendChild($$key);
                                 }
                                 else
                                 {
                                    $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("");
                                    $$key->appendChild($nodeText);
                                 $tra_finance->appendChild($$key);
                                 }
                                                            
                                $Transformation->appendChild($tra_finance);
                                }
                            
                            }
                            else if ($elmix_value=="false" && $elmix_key!='ImportExport')
                             {
                                //$tmp = substr($year_key, 1);
                                $tmp = $year_key;
                                $tra_finance = $xml->createElement("tra_finance");
                                $tra_finance->setAttribute("year","$tmp");
                                $tra_finance->setAttribute("technology","$elmix_key");
                                
                                foreach ($finance as $key => $value) 
                                {
                                 if ($value == "1")
                                 {
                                    $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("");
                                    $$key->appendChild($nodeText);
                                 $tra_finance->appendChild($$key);
                                 }
                                 else
                                 {
                                  $$key = $xml->createElement("$key");
                                    $nodeText = $xml->createTextNode("");
                                    $$key->appendChild($nodeText);
                                 $tra_finance->appendChild($$key);
                                 }
                                                            
                                $Transformation->appendChild($tra_finance);
                                }
                            
                            }
                        }
                        
                      }
                }
                
                $root->appendChild($Case);  
                $root->appendChild($Sectors); 
                $root->appendChild($Fuels); 
                $root->appendChild($Years); 
                $root->appendChild($ElMix_fuels);
                $root->appendChild($Final_energy_demand);  
                $root->appendChild($Secondary_energy_supplies);   
                $root->appendChild($Primary_energy_supplies);
                $root->appendChild($Transformation);    

            $xml->formatOutput = true;
            //echo "<xmp>". $xml->saveXML() ."</xmp>";
            chmod($newfolder, 0755);
            $xml->save($newfolder . '/' .$ime . '.xml') or die("Error");
            
            if (file_exists($newfolder . '/' .$ime . '.xml')){
                
                return true;
            }
            else{
                
                return false;
            }
                
}

function edit_case_name($filepath, $name)
            {
                
            $xml = new DOMDocument("1.0");
            $xml->formatOutput = true;
            $xml->preserveWhiteSpace = false;
            $xml->load("$filepath");    
            $xpath = new DOMXPath($xml);  
    
            $ime = $xpath->query('//Case/name')->item(0);
            $ime->nodeValue = $name; 
          
         file_put_contents($filepath,$xml->saveXML()); 
}

function edit_case_general($filepath, $name, $date, $description, $unit, $currency)
            {
                
            $xml = new DOMDocument("1.0");
            $xml->formatOutput = true;
            $xml->preserveWhiteSpace = false;
            $xml->load("$filepath");    
            $xpath = new DOMXPath($xml);  
    
            $ime = $xpath->query('//Case/name')->item(0);
            $ime->nodeValue = $name; 
            $opis = $xpath->query('//Case/description')->item(0);
            $opis->nodeValue = $description;
            $datum = $xpath->query('//Case/date')->item(0);
            $datum->nodeValue = $date;  

            $jedinice = $xpath->query('//Case/units')->item(0);
            $jedinice->nodeValue = $unit;
            $valuta = $xpath->query('//Case/currency')->item(0);
            $valuta->nodeValue = $currency;  
            
           
            $filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";
            $filepath2 = USER_CASE_PATH.$name."/".$name.".xml";
            $folder = dirname($filepath);
            $folder2 = dirname($filepath2);
            
            if ($filepath != $filepath2 && file_exists($folder2)){
				return 3; //case already exists
				die();
            }
			//chmod($folder, 0755);
            rename($folder, USER_CASE_PATH.$name);
            $filepath2 =  USER_CASE_PATH.$name."/".$name.".xml";
             $xml->save($filepath2) or die("Error");
             $filename = basename($filepath);
             if ($filepath != $filepath2){
				 unlink(USER_CASE_PATH.$name."/".$filename);
				 unset($_SESSION['case']);
				 $_SESSION['case'] = $name;
				 if (!file_exists(USER_CASE_PATH.$name."/".$filename))
				 return 1;
				 
				 else return 0;
             }
             else
             if ($filepath==$filepath2 && file_exists(USER_CASE_PATH.$name."/".$filename))
             return 1;
             else 
             return 0;
              
            // header('Location: cases/EditCase_data.php?casename='.$name);
           // file_put_contents($filepath2,$xml->asXML($name.".xml"));
}

function get_currency($filepath)
{
//    $curr = $GLOBALS[xml]->xpath("//currency");
//    $currency = (string)$curr[0];
//    return $currency;
 return $GLOBALS['esstCase']->getCurrency();
}

function get_unit($filepath)
{
//    $jedinice = $GLOBALS[xml]->xpath("//units");
//    $unit = (string)$jedinice[0];
//    return $unit;
 return $GLOBALS['esstCase']->getUnit();
}

function get_years($filepath)
{

    global $esstCase;
//    $godine = $GLOBALS[xml]->xpath("//Years/*");
//    $years = array();
//	foreach($godine as $god)
//    {
//       $years[$god->getName()]= (string)$god;  //napuni niz iz xml-a za godine
//    }
//    return $years;

 return $GLOBALS['esstCase']->getYears();
}

function get_sectors($filepath)
{
//    global $xml;
//    $sektori = $GLOBALS[xml]->xpath("//Sectors/*");
//    $sectors = array();
//	  foreach($sektori as $sek)
//    {
//       $sectors[$sek->getName()]= (string)$sek;  //napuni niz iz xml-a za godine
//    }
//    return $sectors;
 return $GLOBALS['esstCase']->getSectors();
}

function get_fuels($filepath)
{
//    $goriva = $GLOBALS[xml]->xpath("//Fuels/*");
//    $fuels = array();
//	foreach($goriva as $gor)
//    {
//       $fuels[$gor->getName()]= (string)$gor;  //napuni niz iz xml-a za godine
//    }
//    return $fuels;
         
 return $GLOBALS['esstCase']->getFuels();
}

function get_elmix_fuels($filepath)
{
//    $el_goriva = $GLOBALS[xml]->xpath("//ElMix_fuels/*");
//    $elmix_fuels = array();
//	foreach($el_goriva as $gor)
//    {
//       $elmix_fuels[$gor->getName()]= (string)$gor;  //napuni niz iz xml-a za godine
//    }
//    return $elmix_fuels;
 return $GLOBALS['esstCase']->getElMixFuels();    
}

function get_technical($filepath)
{
//    $tech = $GLOBALS[xml]->xpath("//tra_technical/*");
//    $technology = array();
//	foreach($tech as $tmp)
//    {
//       $technology[$tmp->getName()]= (string)$tmp;  //napuni niz iz xml-a za godine
//    }
//    return $technology;
    
 return $GLOBALS['esstCase']->getTechnical();
}

function get_envirioment($filepath)
{
//    $env = $GLOBALS[xml]->xpath("//tra_envirioment/*");
//    $envirioment = array();
//	foreach($env as $tmp)
//    {
//       $envirioment[$tmp->getName()]= (string)$tmp;  //napuni niz iz xml-a za godine
//    }
//    return $envirioment;
 
  return $GLOBALS['esstCase']->getEnvironment();
}

function get_finance($filepath)
{
//    $fin =$GLOBALS[xml]->xpath("//tra_finance/*");
//    $finance = array();
//	foreach($fin as $tmp)
//    {
//       $finance[$tmp->getName()]= (string)$tmp;  //napuni niz iz xml-a za godine
//    }
//    return $finance;
   return $GLOBALS['esstCase']->getFinance();
}

function get_loadfactor($filepath)
{ 
    $lf =$GLOBALS['xml']->xpath("//tra_loadfactor/*");
    $loadfactor = array();
	foreach($lf as $tmp)
    {
       $loadfactor[$tmp->getName()]= (string)$tmp;  //napuni niz iz xml-a za godine
    }
    return $loadfactor;
}

//function get_lifetime($filepath, $tech)
//{
//    if (file_exists($filepath))
//    {
//          $years = get_years($filepath);
//          $elmix_fuels = get_elmix_fuels($filepath);
//          $unit = get_unit($filepath);
//          $sectors = get_sectors($filepath);
//           foreach ($elmix_fuels as $key=>$value)
//                {
//                    $index = 1990;
//                    $temp = 0;
//                    if ($value =="1" && $key == $tech) 
//                    {
//                         ${"Out$key"} = array();
//                         
//                         $ltresult = $GLOBALS[xml]->xpath(sprintf('//tra_lifetime[@technology="%s"]/Lifetime', $key));
//                         $lifetime = (int)$ltresult[0];
//                         
//                         //$lifetime = $GLOBALS['esstCase']->getLifetime($key);
//                         
//                         foreach ($years as $key1=>$value1)
//                         {
//                            if ($value1=="1")
//                            {    
//                            $tmp = substr($key1, 1);
//                         
//                            $result1 = $GLOBALS[xml]->xpath(sprintf('//tra_technical[@year="%s" and @technology="%s"]/Installed_power', $tmp, $key));
//                            $result2 = $GLOBALS[xml]->xpath(sprintf('//tra_technical[@year="%s" and @technology="%s"]/Capacity_factor', $tmp, $key));
//                            
//                            //izracunati energiju iz instalirane snage
//                            if ($unit == 'PJ') 
//                                ${"Installed$key1"} = ((float)$result1[0]*(float)$result2[0]/100)/1000*365*24*3.6/1000;
//                            if ($unit == 'ktoe')
//                                ${"Installed$key1"} = ((float)$result1[0]*(float)$result2[0]/100)/1000*365*24*8.6/100;
//                            if ($unit == 'GWh')
//                                ${"Installed$key1"} = ((float)$result1[0]*(float)$result2[0]/100)/1000*365*24;  
//                            ${"CF$key1"} = (float)$result2[0];
//                          
//                             $result = $GLOBALS[xml]->xpath(sprintf('//ses_elmix[@year="%s"]/%s', $tmp, $key)); 
//                             $elmix_fuel = (string)$result[0];
//                             $Electricity =0;
//                             foreach($sectors as $key2=>$value2)
//                             {
//                                
//                                $result1 = $GLOBALS[xml]->xpath(sprintf('//fed_bysectors[@year="%s"]/%s', $tmp, $key2)); 
//                                $SectorV = (string)$result1[0];
//                                
//                                $result2 = $GLOBALS[xml]->xpath(sprintf('//fed_fuelshares[@year="%s" and @sector="%s"]/Electricity', $tmp, $key2));
//                                $ShareV = (string)$result2[0];
//                               
//                                $result3 = $GLOBALS[xml]->xpath(sprintf('//fed_losses[@year="%s"]/Electricity', $tmp));                       
//                                $LossesV = (string)$result3[0];
//                            
//                              if ((string)$result1[0]!=""&&(string)$result2[0]!=""&&(string)$result3[0]!="")
//                              //napravi varijablu sa imenom goriva i saberi sve crijednosti tog goriva za sve aktivne sektore u aktivnoj godini
//                              //i dodijeli u niz $SES
//                               $Electricity = $Electricity + ($SectorV*$ShareV/100/(1-$LossesV/100));   
//                             }
//                             
//                            ${"Demanded$key1"} = $Electricity*$elmix_fuel/100;  
//                                 
//                            if (${"CF$key1"}!=0)
//                               {
//                                    if ($unit == "PJ")
//                                    {
//                                        
//                                        if ((( ${"Installed$key1"} - ${"Demanded$key1"})/${"CF$key1"}/365/24/3.6*100000000 + abs($temp))<0)
//                                        {
//                                            //echo $tmp."<br>";
//                                            //echo $index."<br>";
//                                           // echo ${"Out$key"}[$index];
//                                           
//                                            $$key1 = abs(( ${"Installed$key1"} - ${"Demanded$key1"})/${"CF$key1"}/365/24/3.6*100000000 + abs($temp)) + ${"Out$key"}[$tmp];
//                                           
//                                            //$$key1 = ceil(abs(( ${"Installed$key1"} - ${"Demanded$key1"})/${"CF$key1"}/365/24/3.6*100000000 + abs($temp)) );
//                                            //$temp instalirani kapacitet koji se prenosi u iducu godinu
//                                            $temp = (${"Installed$key1"}- ${"Demanded$key1"})/${"CF$key1"}/365/24/3.6*100000000; 
//                                            $index = (int)$tmp + (int)$lifetime;
//                                           //$index2 = (int)$tmp + (int)$lifetime + (int)$lifetime;
//                                            //echo $lifetime."<br>";
////                                            echo $index."<br>";
//                                            if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
//                                                ${"Out$key"}[$index] = $$key1;
//                                                
//                                            if (!array_key_exists("_".$index, $years))
//                                            {
//                                                $index = ceil($index/5)*5;
//                                                if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
//                                                    ${"Out$key"}[$index] = $$key1;  
//                                                else 
//                                                $index = $index+5;
//                                                if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
//                                                ${"Out$key"}[$index] = $$key1;                                              
//                                            }
//                                            if (array_key_exists("_".$index, $years)&& 0 == $years["_".$index])
//                                            {
//                                                $index = $index+5;
//                                                if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
//                                                ${"Out$key"}[$index] = $$key1; 
//                                                                                               
//                                            }  
//                                            //if ($index==1995)
////                                            {
////                                                $index = $index+5;
////                                                if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
////                                                ${"Out$key"}[$index] = $$key1; 
////                                                                                               
////                                            }                                            
//                                         }
//                                        else
//                                        $$key1 = 0;  
//                                    }
//                                    
//                                    if ($unit == "ktoe")
//                                    {
//                                        if ((( ${"Installed$key1"} - ${"Demanded$key1"})/${"CF$key1"}/365/24*11630*100 + abs($temp))<0)
//                                        {
//                                            $$key1 = ceil(abs(( ${"Installed$key1"} - ${"Demanded$key1"})/${"CF$key1"}/365/24*11630*100 + abs($temp)));
//                                            //$temp instalirani kapacitet koji se prenosi u iducu godinu
//                                            $temp = (${"Installed$key1"}- ${"Demanded$key1"})/${"CF$key1"}/365/24*11630*100;                                                
//                                            $index = (int)$tmp + (int)$lifetime;
//                                            if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
//                                                ${"Out$key"}[$index] = $$key1;
//                                                
//                                            else if (!array_key_exists("_".$index, $years))
//                                            {
//                                                $index = ceil($index/5)*5;
//                                                if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
//                                                ${"Out$key"}[$index] = $$key1;                                                
//                                            }
//                                            else if (array_key_exists("_".$index, $years)&& 0 == $years["_".$index])
//                                            {
//                                                $index = $index+5;
//                                                if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
//                                                ${"Out$key"}[$index] = $$key1; 
//                                                                                               
//                                            }
//                                        }
//                                        else
//                                        $$key1 = 0;
//                                    }
//                                  
//                                    if ($unit == "GWh")
//                                    {
//                                        if ((( ${"Installed$key1"} - ${"Demanded$key1"})/${"CF$key1"}*100/365/24*1000 + abs($temp))<0)
//                                        {
//                                            $$key1 = ceil(abs(( ${"Installed$key1"} - ${"Demanded$key1"})/${"CF$key1"}*100/365/24*1000 + abs($temp)));
//                                            //$temp instalirani kapacitet koji se prenosi u iducu godinu
//                                            $temp = (${"Installed$key1"}- ${"Demanded$key1"})/${"CF$key1"}*100/365/24*1000;                                              
//                                            $index = (int)$tmp + (int)$lifetime;
//                                            if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
//                                                ${"Out$key"}[$index] = $$key1;
//                                                
//                                            else if (!array_key_exists("_".$index, $years))
//                                            {
//                                                $index = ceil($index/5)*5;
//                                                if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
//                                                ${"Out$key"}[$index] = $$key1;                                                
//                                            }
//                                            else if (array_key_exists("_".$index, $years)&& 0 == $years["_".$index])
//                                            {
//                                                $index = $index+5;
//                                                if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
//                                                ${"Out$key"}[$index] = $$key1; 
//                                                                                               
//                                            }
//                                        }
//                                        else
//                                        $$key1 = 0;
//                                    }
//                               }
//                                else
//                                $$key1=0;  
//     
//                            }
//                        }
//                       // if($key=='Coal' )
//                        //    {
//                                //echo $Installed_2010."<br>";
//                               // echo $Demanded_2010."<br>";
//                               // $rez = ceil(abs(( $Installed_2010 - $Demanded_2010)/50/365/24/3.6*100000000 + ($Installed_2010- $Demanded_2010)/50/365/24/3.6*100000000));
//                               //echo $rez;
//                                
//                          //  }              
//                        return  ${"Out$key"};
//                 }   
//         }           
//    }     
//}


function get_lifetime($filepath, $tech)
    {
    if (file_exists($filepath))
    {
          $years = get_years($filepath);
          $elmix_fuels = get_elmix_fuels($filepath);
          $unit = get_unit($filepath);
          $sectors = get_sectors($filepath);
          
           foreach ($elmix_fuels as $key=>$value)
                {
                    $index = 1990;
                    $temp = 0;
                    
                    if ($value =="1" && $key == $tech) 
                    {
                      
                         ${"Out$key"} = array();
                         $lifetime = $GLOBALS['esstCase']->getLifetime($key);
                //print_r ($lifetime);
                         foreach ($years as $key1=>$value1)
                         {
                            if ($value1=="1")
                            {    
                            $tmp = substr($key1, 1);
                            ${"Installed$key1"} = get_installed_energy ($filepath, $key, $tmp, $unit);        
                            ${"CF$key1"} = $GLOBALS['esstCase']->getCapacityFactor($tmp, $key); 
                            ${"Demanded$key1"} = get_el_demand($key, $tmp);    
               
                            if (${"CF$key1"}!=0)
                               {
                                    if ($unit == "PJ")
                                    {
                                        if ((( ${"Installed$key1"} - ${"Demanded$key1"})/${"CF$key1"}/365/24/3.6*100000000 + abs($temp))<0)
                                        { 
                                            $$key1 = abs(( ${"Installed$key1"} - ${"Demanded$key1"})/${"CF$key1"}/365/24/3.6*100000000 + abs($temp));// + ${"Out$key"}[$tmp];      
                                            //$temp instalirani kapacitet koji se prenosi u iducu godinu
                                            $temp = (${"Installed$key1"}- ${"Demanded$key1"})/${"CF$key1"}/365/24/3.6*100000000; 
                                            $index = (int)$tmp + (int)$lifetime;

                                            if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
                                                ${"Out$key"}[$index] = $$key1;
                                                
                                            if (!array_key_exists("_".$index, $years))
                                            {
                                                $index = ceil($index/5)*5;
                                                if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
                                                    ${"Out$key"}[$index] = $$key1;  
                                                else 
                                                $index = $index+5;
                                                if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
                                                ${"Out$key"}[$index] = $$key1;                                              
                                            }
                                            if (array_key_exists("_".$index, $years)&& 0 == $years["_".$index])
                                            {
                                                $index = $index+5;
                                                if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
                                                ${"Out$key"}[$index] = $$key1;                                                       
                                            }  
                                            //if ($index==1995)
//                                            {
//                                                $index = $index+5;
//                                                if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
//                                                ${"Out$key"}[$index] = $$key1; 
//                                                                                               
//                                            }                                            
                                         }
                                        else
                                            $$key1 = 0;  
                                    }
                                    
                                    if ($unit == "ktoe")
                                    {
                                        if ((( ${"Installed$key1"} - ${"Demanded$key1"})/${"CF$key1"}/365/24*11630*100 + abs($temp))<0)
                                        {
                                            $$key1 = ceil(abs(( ${"Installed$key1"} - ${"Demanded$key1"})/${"CF$key1"}/365/24*11630*100 + abs($temp)));
                                            //$temp instalirani kapacitet koji se prenosi u iducu godinu
                                            $temp = (${"Installed$key1"}- ${"Demanded$key1"})/${"CF$key1"}/365/24*11630*100;                                                
                                            $index = (int)$tmp + (int)$lifetime;
                                            if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
                                                ${"Out$key"}[$index] = $$key1;
                                                
                                            else if (!array_key_exists("_".$index, $years))
                                            {
                                                $index = ceil($index/5)*5;
                                                if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
                                                ${"Out$key"}[$index] = $$key1;                                                
                                            }
                                            else if (array_key_exists("_".$index, $years)&& 0 == $years["_".$index])
                                            {
                                                $index = $index+5;
                                                if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
                                                ${"Out$key"}[$index] = $$key1; 
                                                                                               
                                            }
                                        }
                                        else
                                        $$key1 = 0;
                                    }
                                  
                                    if ($unit == "GWh")
                                    {
                                        if ((( ${"Installed$key1"} - ${"Demanded$key1"})/${"CF$key1"}*100/365/24*1000 + abs($temp))<0)
                                        {
                                            $$key1 = ceil(abs(( ${"Installed$key1"} - ${"Demanded$key1"})/${"CF$key1"}*100/365/24*1000 + abs($temp)));
                                            //$temp instalirani kapacitet koji se prenosi u iducu godinu
                                            $temp = (${"Installed$key1"}- ${"Demanded$key1"})/${"CF$key1"}*100/365/24*1000;                                              
                                            $index = (int)$tmp + (int)$lifetime;
                                            if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
                                                ${"Out$key"}[$index] = $$key1;
                                                
                                            else if (!array_key_exists("_".$index, $years))
                                            {
                                                $index = ceil($index/5)*5;
                                                if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
                                                ${"Out$key"}[$index] = $$key1;                                                
                                            }
                                            else if (array_key_exists("_".$index, $years)&& 0 == $years["_".$index])
                                            {
                                                $index = $index+5;
                                                if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
                                                ${"Out$key"}[$index] = $$key1;                                                  
                                            }
                                        }
                                        else
                                        $$key1 = 0;
                                    }
                               }
                                else
                                $$key1=0;  
                            }
                        }           
                        return  ${"Out$key"};
                 }   
         }           
    }     
}


function get_lifetime_energy($filepath, $tech)
{
    if (file_exists($filepath))
 
    {
       //$xml = simplexml_load_file($filepath)
       //   or die("Error: Cannot create object");
          $years = get_years($filepath);
          $elmix_fuels = get_elmix_fuels($filepath);
          $unit = get_unit($filepath);
          $sectors = get_sectors($filepath);
           foreach ($elmix_fuels as $key=>$value)
                {
                    $temp = 0;
                    if ($value =="1" && $key==$tech) 
                    {
                         ${"Out$key"} = array();
                         
                         //$ltresult = $GLOBALS[xml]->xpath(sprintf('//tra_lifetime[@technology="%s"]/Lifetime', $key));
                         $ltresult = $GLOBALS[xml]->xpath(sprintf('//tra_lifetime[@year="%s"]/%s', $tmp, $key));
                         $lifetime = (int)$ltresult[0];
                         foreach ($years as $key1=>$value1)
                         {
                            if ($value1=="1")
                            {    
                            $tmp = substr($key1, 1);
                         
                            $result1 = $GLOBALS[xml]->xpath(sprintf('//tra_technical[@year="%s" and @technology="%s"]/Installed_power', $tmp, $key));
                            $result2 = $GLOBALS[xml]->xpath(sprintf('//tra_technical[@year="%s" and @technology="%s"]/Capacity_factor', $tmp, $key));
                            //izracunati energiju iz instalirane snage
                            if ($unit == 'PJ') 
                                ${"Installed$key1"} = ((float)$result1[0]*(float)$result2[0]/100)/1000*365*24*3.6/1000;
                            if ($unit == 'ktoe')
                                ${"Installed$key1"} = ((float)$result1[0]*(float)$result2[0]/100)/1000*365*24*8.6/100;
                            if ($unit == 'GWh')
                                ${"Installed$key1"} = ((float)$result1[0]*(float)$result2[0]/100)/1000*365*24;  
                            ${"CF$key1"} = (float)$result2[0];
                          
                             $result = $GLOBALS[xml]->xpath(sprintf('//ses_elmix[@year="%s"]/%s', $tmp, $key)); 
                             $elmix_fuel = (string)$result[0];
                             $Electricity =0;
                             foreach($sectors as $key2=>$value2)
                             {
                                
                                $result1 = $GLOBALS[xml]->xpath(sprintf('//fed_bysectors[@year="%s"]/%s', $tmp, $key2)); 
                                $SectorV = (string)$result1[0];
                                
                                $result2 = $GLOBALS[xml]->xpath(sprintf('//fed_fuelshares[@year="%s" and @sector="%s"]/Electricity', $tmp, $key2));
                                $ShareV = (string)$result2[0];
                               
                                $result3 = $GLOBALS[xml]->xpath(sprintf('//fed_losses[@year="%s"]/Electricity', $tmp));                       
                                $LossesV = (string)$result3[0];
                            
                              if ((string)$result1[0]!=""&&(string)$result2[0]!=""&&(string)$result3[0]!="")
                              //napravi varijablu sa imenom goriva i saberi sve crijednosti tog goriva za sve aktivne sektore u aktivnoj godini
                              //i dodijeli u niz $SES
                               $Electricity = $Electricity + ($SectorV*$ShareV/100/(1-$LossesV/100));   
                             }
                            ${"Demanded$key1"} = $Electricity*$elmix_fuel/100;        
                            if (${"CF$key1"}!=0)
                               {
                                    
                                        if (( ${"Installed$key1"}  - ${"Demanded$key1"} + abs($temp))<0)
                                        {
                                            $$key1 = ${"Installed$key1"} - ${"Demanded$key1"} +abs($temp);
                                            $temp = ${"Installed$key1"}- ${"Demanded$key1"};                                          
                                            $index = (int)$tmp + (int)$lifetime;              
    
                                            if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
                                                ${"Out$key"}[$index] = $$key1;
                                                
                                            if (!array_key_exists("_".$index, $years))
                                            {
                                                $index = ceil($index/5)*5;
                                                if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
                                                    ${"Out$key"}[$index] = $$key1;                                                                                                  
                                            }
                                            if (array_key_exists("_".$index, $years)&& 0 == $years["_".$index])
                                            {
                                                $index = $index+5;
                                                if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
                                                    ${"Out$key"}[$index] = $$key1; 
                                                                                               
                                            }
                                            
                                        }
                                        else
                                        $$key1 = 0;  
                  
                               }
                                else
                                $$key1=0;  
     
                            }
                        }

                                          
                        
                 }
               return  ${"Out$key"};
         }
            
    }     

}

function get_fuel_array()
{            
    $goriva = $GLOBALS['xml']->xpath("//Fuels/*");
    $fuels = array();
	foreach($goriva as $gor) {
       if ($gor == 1)
       $fuels[]= $gor->getName();  //napuni niz iz xml-a za goriva
    }
 return $fuels;
}

function get_dom_prod_fuel_array()
{            
    $goriva = $GLOBALS['xml']->xpath("//Fuels/*");
    $fuels = array();
	foreach($goriva as $gor)
    {
       if ($gor == 1 && $gor->getName()!='Electricity'&&$gor->getName()!='Heat')
       $fuels[]= $gor->getName();  //napuni niz iz xml-a za goriva
    }
 return $fuels;
}
//nizovi za goriva i sektore potrebni za update kroz grid (problem nasta sa prevodom) moramo indexirat niz goriva, sketora sa indexom ne imenom ...
function get_elmix_fuel_array()
{            
    $goriva = $GLOBALS['xml']->xpath("//ElMix_fuels/*");
    $elmix_fuels = array();
	foreach($goriva as $gor)
    {
       if ($gor == 1)
       $elmix_fuels[]= $gor->getName();  //napuni niz iz xml-a za goriva
    }
 return $elmix_fuels;
}
function get_sector_array()
{            
    $sektori = $GLOBALS['xml']->xpath("//Sectors/*");
    $sectors = array();
	foreach($sektori as $sek)
    {
        if ($sek == 1)
       $sectors[]= $sek->getName();  //napuni niz iz xml-a za sektore2
    }
 return $sectors;
}


function  edit_case_addsectors($filepath, $addsector)
{

            $sectors =get_sectors($filepath);
            $fuels = get_fuels($filepath);
            $years = get_years($filepath);
            
            $xml = new DOMDocument("1.0");
            $xml->formatOutput = true;
            $xml->preserveWhiteSpace = false;
            $xml->load("$filepath");    
            $xpath = new DOMXPath($xml);
    
            foreach($addsector as $key=>$value)
            {
                if ($value=="true")
                {
                    
                        //dodaj agriculture u Setors
                       $xpath->query(sprintf('//Sectors/%s', $key))->item(0)->nodeValue="1";
                       // dodaj agriculture u fed_by sectors
                      foreach ($years as $year_key=>$year_value)
                      {
                       if ($year_value == "1")
                            {
                              $tmp = substr($year_key, 1);
                              $xpath->query(sprintf('//fed_bysectors[@year="%s"]/%s', $tmp, $key))->item(0)->nodeValue = "0";   
                            }
                        //dodaj agriculture u fuel shares
                        foreach ($years as $year_key=>$year_value)
                        {
                            if ($year_value == "1")
                            {
                                foreach ($fuels as $fuel_key=>$fuel_value)
                                {
                                    $tmp = substr($year_key, 1);
                                    if ($fuel_value == "1")
                                    {
                                        $xpath->query(sprintf('//fed_fuelshares[@year="%s" and @sector="%s"]/%s', $tmp,$key, $fuel_key))->item(0)->nodeValue = "0";
                                    }
                                    else
                                    {
                                        $xpath->query(sprintf('//fed_fuelshares[@year="%s" and @sector="%s"]/%s', $tmp,$key, $fuel_key))->item(0)->nodeValue = "";
                                    }
                                    
                                }
                            }
                            else
                            {
                                foreach ($fuels as $fuel_key=>$fuel_value)
                                {
                                    $tmp = substr($year_key, 1);
                                    if ($fuel_value == "1")
                                    {
                                        $xpath->query(sprintf('//fed_fuelshares[@year="%s" and @sector="%s"]/%s', $tmp,$key, $fuel_key))->item(0)->nodeValue = "";
                                    }
                                    else
                                    {
                                        $xpath->query(sprintf('//fed_fuelshares[@year="%s" and @sector="%s"]/%s', $tmp,$key, $fuel_key))->item(0)->nodeValue = "";
                                    }
                                    
                                }
                            }
                        }
                    }
                    
                }
            }
            file_put_contents($filepath,$xml->saveXML()); 
}


function  edit_case_removesectors($filepath, $removesector)
{
            $sectors = get_sectors($filepath);
            $fuels = get_fuels($filepath);
            $years = get_years($filepath);
          
            
            $xml = new DOMDocument("1.0");
            $xml->formatOutput = true;
            $xml->preserveWhiteSpace = false;
            $xml->load("$filepath");    
            $xpath = new DOMXPath($xml);
            
            
            foreach($removesector as $key1=>$value1)
            {
                 if ($value1=='true')
                    {
                        //remove agriculture from Sectors
                        $xpath->query(sprintf('//Sectors/%s', $key1))->item(0)->nodeValue="0"; 
                        //remove agriculture from fed_bysectors
                        foreach ($years as $key=>$value)
                        {
                            $tmp = substr($key, 1);
                            $xpath->query(sprintf('//fed_bysectors[@year="%s"]/%s', $tmp, $key1))->item(0)->nodeValue = "";
                        }
                        //remove agriculture from fuels shares
                        foreach ($years as $year_key=>$year_value)
                        {
                            foreach ($fuels as $fuel_key=>$fuel_value)
                            
                                {
                                    $tmp = substr($year_key, 1);
                                    //echo $tmp;
                                    //echo $fuel_key;
                                    //$ele = $xpath->query(sprintf('//fed_fuelshares[@year="%s" and @sector="%s"]/Electricity', $year, $sector))->item(0);
                                    $xpath->query(sprintf('//fed_fuelshares[@year="%s" and @sector="%s"]/%s', $tmp,$key1, $fuel_key))->item(0)->nodeValue = "";
                                }
                        }
                    }
            }
            file_put_contents($filepath,$xml->saveXML()); 
}

function edit_case_addfuels($filepath, $addfuel)
{
            $sectors =get_sectors($filepath);
            $fuels = get_fuels($filepath);
            $years = get_years($filepath);
            
            $xml = new DOMDocument("1.0");
            $xml->formatOutput = true;
            $xml->preserveWhiteSpace = false;
            $xml->load("$filepath");    
            $xpath = new DOMXPath($xml);
            
            
            foreach ($addfuel as $key=>$value)
            {
                if ($value=='true')
                {
                    //add Heat to fuels
                    $xpath->query(sprintf('//Fuels/%s',$key))->item(0)->nodeValue = "1";
                    //add Heat to fuelshares
                     foreach ($years as $year_key=>$year_value)
                    {
                        if ($year_value == "1")
                        {
                            foreach ($sectors as $sector_key=>$sector_value)
                            {
                                $tmp = substr($year_key, 1);
                                if ($sector_value == "1")
                                {
                                    $xpath->query(sprintf('//fed_fuelshares[@year="%s" and @sector="%s"]/%s', $tmp, $sector_key, $key))->item(0)->nodeValue = "0";
                                }
                                else
                                {
                                    $xpath->query(sprintf('//fed_fuelshares[@year="%s" and @sector="%s"]/%s', $tmp, $sector_key, $key))->item(0)->nodeValue = "";
                                }
                                
                            }
                        }
                        else
                        {
                            foreach ($sectors as $sector_key=>$sector_value)
                            {
                                $tmp = substr($year_key, 1);
                                if ($sector_value == "1")
                                {
                                    $xpath->query(sprintf('//fed_fuelshares[@year="%s" and @sector="%s"]/%s', $tmp, $sector_key, $key))->item(0)->nodeValue = "";
                                }
                                else
                                {
                                    $xpath->query(sprintf('//fed_fuelshares[@year="%s" and @sector="%s"]/%s', $tmp, $sector_key, $key))->item(0)->nodeValue = "";
                                }
                                
                            }
                        }
                    }
                    //add fuels to losses
                      foreach ($years as $year_key=>$year_value)
                        {
                            if ($year_value == "1")
                            {
                                $tmp = substr($year_key, 1);
                            
                                    $xpath->query(sprintf('//fed_losses[@year="%s"]/%s', $tmp, $key))->item(0)->nodeValue = "0";
                                
                            }
                        }
                        
                     //add pes_domestic production
                    foreach ($years as $year_key=>$year_value)
                    {
                        if ($year_value == "1")
                        {
                                $tmp = substr($year_key, 1);
                                $xpath->query(sprintf('//pes_domestic_production[@year="%s"]/%s', $tmp, $key))->item(0)->nodeValue = "0";
       
                        }
                        else
                        {
                            $tmp = substr($year_key, 1);
                               $xpath->query(sprintf('//pes_domestic_production[@year="%s"]/%s', $tmp, $key))->item(0)->nodeValue = "";
                        }
                    }
                     
                }
            }
            file_put_contents($filepath,$xml->saveXML());
}

function edit_case_removefuels($filepath, $removefuel)
{
            $sectors =get_sectors($filepath);
            $fuels = get_fuels($filepath);
            $years = get_years($filepath);
            
            $xml = new DOMDocument("1.0");
            $xml->formatOutput = true;
            $xml->preserveWhiteSpace = false;
            $xml->load("$filepath");    
            $xpath = new DOMXPath($xml);
            
            foreach ($removefuel as $key=>$value)
            {
                 if ($value=='true')
                 {
                    //remove  from fuels
                    $xpath->query(sprintf('//Fuels/%s', $key))->item(0)->nodeValue = "0";
                    
                    //remove from fuelshares
                    $HeatR = $xpath->query(sprintf('//fed_fuelshares/%s', $key));
                    foreach ($HeatR as $tmp)
                    {
                        $tmp->nodeValue = "";
                    } 
                    
                    //remove from losses
                    $losses=$xpath->query(sprintf('//fed_losses/%s', $key));
                    foreach ($losses as $tmp2)
                    {
                        $tmp2->nodeValue = "";
                    }
                                        
                    //remove  from pes_domestic_production
                    $resultR = $xpath->query(sprintf('//pes_domestic_production/%s', $key));
                    foreach ($resultR as $tmp)
                    {
                        $tmp->nodeValue = "";
                    }  
                }
            }
            file_put_contents($filepath,$xml->saveXML());
}
function edit_case_addPfuels($filepath, $addfuel)
{
            $sectors =get_sectors($filepath);
            $fuels = get_fuels($filepath);
            $years = get_years($filepath);
            $elmix_fuels = get_elmix_fuels($filepath);
            
            $xml = new DOMDocument("1.0");
            $xml->formatOutput = true;
            $xml->preserveWhiteSpace = false;
            $xml->load("$filepath");    
            $xpath = new DOMXPath($xml);
            
            
            foreach ($addfuel as $key=>$value)
            {
                if ($value=='true')
                {
                    //add elmix fuels
                    $xpath->query(sprintf('//ElMix_fuels/%s',$key))->item(0)->nodeValue = "1";
                    //add ses_elmix
                     foreach ($years as $year_key=>$year_value)
                    {
                        if ($year_value == "1")
                        {
                                $tmp = substr($year_key, 1);
                                $xpath->query(sprintf('//ses_elmix[@year="%s"]/%s', $tmp, $key))->item(0)->nodeValue = "0";
                           
                                
                        }
                        else
                        {
                            $tmp = substr($year_key, 1);
                               $xpath->query(sprintf('//ses_elmix[@year="%s"]/%s', $tmp, $key))->item(0)->nodeValue = "";
                        }
                    }
                    //pes efficiency
                    foreach ($years as $year_key=>$year_value)
                    {
                        if ($year_value == "1")
                        {
                            
                                $tmp = substr($year_key, 1);
                                if ( $key!='Hydro' && $key!='Solar' && $key!='Wind'&& $key!='Geothermal'&& $key!='Nuclear')
                                {
                                    $xpath->query(sprintf('//tra_efficiency[@year="%s"]/%s', $tmp, $key))->item(0)->nodeValue = "0";
                                }
                                else if ( $key=='Hydro' || $key=='Solar' || $key=='Wind')
                                {
                                    $xpath->query(sprintf('//tra_efficiency[@year="%s"]/%s', $tmp, $key))->item(0)->nodeValue = "100";
                                }
                                else if ( $key=='Geothermal')
                                {
                                    $xpath->query(sprintf('//tra_efficiency[@year="%s"]/%s', $tmp, $key))->item(0)->nodeValue = "10";
                                }
                                else if ($key=='Nuclear')
                                {
                                    $xpath->query(sprintf('//tra_efficiency[@year="%s"]/%s', $tmp, $key))->item(0)->nodeValue = "33";
                                }
                                
                                else
                                {
                                    $xpath->query(sprintf('//tra_efficiency[@year="%s"]/%s', $tmp, $key))->item(0)->nodeValue = "";
                                }

                                $xpath->query(sprintf('//tra_lifetime[@year="%s"]/%s', $tmp, $key))->item(0)->nodeValue = "0";
                                //$xpath->query(sprintf('//tra_lifetime[@technology="%s"]/*', $key));                    
                            //  //   foreach($result as $data)
                            //  {
                            //     $data->nodeValue = "0"; 
                            //  } 
                                
                            
                        }
                        else
                        {
                                $tmp = substr($year_key, 1);
                                if ($value == "1")
                                {
                                    $xpath->query(sprintf('//tra_efficiency[@year="%s"]/%s', $tmp, $key))->item(0)->nodeValue = "";
                                    $xpath->query(sprintf('//tra_lifetime[@year="%s"]/%s', $tmp, $key))->item(0)->nodeValue = "";
                                }
                                else
                                {
                                    $xpath->query(sprintf('//tra_efficiency[@year="%s"]/%s', $tmp, $key))->item(0)->nodeValue = "";
                                    $xpath->query(sprintf('//tra_lifetime[@year="%s"]/%s', $tmp, $key))->item(0)->nodeValue = "";
                                }
                                
                            
                        }
                    }
                    //add technology
                    
                    foreach ($years as $year_key=>$year_value)
                    {
                        if ($year_value == "1")
                        {
                            
                            $tmp = substr($year_key, 1);  
                             $result = $xpath->query(sprintf('//tra_technical[@year="%s"and @technology="%s"]/*',$tmp, $key)); 
                                               
                   	         foreach($result as $data)
                             {
                               //echo $data->nodeValue;
                                $data->nodeValue = "0"; 
                             }
                         }
                     }
                     //add envirioment
                   
                    
                    foreach ($years as $year_key=>$year_value)
                    {
                        if ($year_value == "1")
                        {
                            
                            $tmp = substr($year_key, 1);  
                             $result = $xpath->query(sprintf('//tra_envirioment[@year="%s"and @technology="%s"]/*',$tmp, $key)); 
                                               
                   	         foreach($result as $data)
                             {
                               //echo $data->nodeValue;
                                $data->nodeValue = "0"; 
                             }
                         }
                     }
                     
                          //add finance
                    
                    foreach ($years as $year_key=>$year_value)
                    {
                        if ($year_value == "1")
                        {
                            
                            $tmp = substr($year_key, 1);  
                             $result = $xpath->query(sprintf('//tra_finance[@year="%s"and @technology="%s"]/*',$tmp, $key)); 
                                               
                   	         foreach($result as $data)
                             {
                               //echo $data->nodeValue;
                                $data->nodeValue = "0"; 
                             }
                         }
                     }
                     //addlifetime 
                    //  $result = $xpath->query(sprintf('//tra_lifetime[@technology="%s"]/*', $key));                    
           	        //  foreach($result as $data)
                    //  {
                    //     $data->nodeValue = "0"; 
                    //  }     
                }
            }
            file_put_contents($filepath,$xml->saveXML());
}
function edit_case_removePfuels($filepath, $removefuel)
{
          //  $sectors =get_sectors($filepath);
//            $fuels = get_fuels($filepath);
            $years = get_years($filepath);
//            $elmix_fuels = get_elmix_fuels($filepath);
            
            $xml = new DOMDocument("1.0");
            $xml->formatOutput = true;
            $xml->preserveWhiteSpace = false;
            $xml->load("$filepath");    
            $xpath = new DOMXPath($xml);
            
            foreach ($removefuel as $key=>$value)
            {
                 if ($value=='true')
                 {
                    //remove from elmix fuels
                    $xpath->query(sprintf('//ElMix_fuels/%s', $key))->item(0)->nodeValue = "0";
                    
                    //remove  from ses_elmix
                    $resultR = $xpath->query(sprintf('//ses_elmix/%s', $key));
                    foreach ($resultR as $tmp)
                    {
                        $tmp->nodeValue = "";
                    } 
                    
                    //remove from tra_efficiency
                    $efficiency=$xpath->query(sprintf('//tra_efficiency/%s', $key));
                    foreach ($efficiency as $tmp2)
                    {
                        $tmp2->nodeValue = "";
                    //remove tecnology
                    foreach($years as $key1=>$value1)
                    {
                        $tmp = substr($key1, 1);
                        if ($value1 == "1")
                        {
                          $result =  $xpath->query(sprintf('//tra_technical[@year="%s" and @technology="%s"]/*', $tmp, $key));
                           foreach($result as $data)
                             {
                               //echo $data->nodeValue;
                                $data->nodeValue = ""; 
                             }
                        }
                    }
                    } 
                      //remove envirioment
                    foreach($years as $key1=>$value1)
                    {
                        $tmp = substr($key1, 1);
                        if ($value1 == "1")
                        {
                          $result =  $xpath->query(sprintf('//tra_envirioment[@year="%s" and @technology="%s"]/*', $tmp, $key));
                           foreach($result as $data)
                             {
                               //echo $data->nodeValue;
                                $data->nodeValue = ""; 
                             }
                        }
                    }
                      //remove finance
                    foreach($years as $key1=>$value1)
                    {
                        $tmp = substr($key1, 1);
                        if ($value1 == "1")
                        {
                          $result =  $xpath->query(sprintf('//tra_finance[@year="%s" and @technology="%s"]/*', $tmp, $key));
                           foreach($result as $data)
                             {
                               //echo $data->nodeValue;
                                $data->nodeValue = ""; 
                             }
                        }
                    }

                    //remove from removelifetime
                    $lifteime=$xpath->query(sprintf('//tra_lifetime/%s', $key));
                    foreach ($lifteime as $tmp2)
                    {
                        $tmp2->nodeValue = "";
                    //remove tecnology
                    foreach($years as $key1=>$value1)
                    {
                        $tmp = substr($key1, 1);
                        if ($value1 == "1")
                        {
                          $result =  $xpath->query(sprintf('//tra_lifetime[@year="%s" and @technology="%s"]/*', $tmp, $key));
                           foreach($result as $data)
                             {
                               //echo $data->nodeValue;
                                $data->nodeValue = ""; 
                             }
                        }
                    }
                    } 


                    //removelifetime 
                    //  $result = $xpath->query(sprintf('//tra_lifetime[@technology="%s"]/*', $key));                    
           	        //  foreach($result as $data)
                    //  {
                    //     $data->nodeValue = ""; 
                    //  }  
                }
            }
            
            file_put_contents($filepath,$xml->saveXML());
}

function edit_case_removeyears($filepath, $removeyear) {
    $sectors =get_sectors($filepath);
    $fuels = get_fuels($filepath);
    $years = get_years($filepath);
    $elmix_fuels = get_elmix_fuels($filepath);
        
    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);
    
    foreach($removeyear as $key=>$value) {
        if ($value=='true') {
            //remove from years
            $xpath->query(sprintf('//Years/_%s',$key))->item(0)->nodeValue = "0"; 
            //remove from bysectors
            foreach ($sectors as $sector_key=>$sector_value) {
                $xpath->query(sprintf('//fed_bysectors[@year="%s"]/%s', $key,$sector_key))->item(0)->nodeValue = ""; 
            }
            //remove from losses
            foreach ($fuels as $fuel_key=>$fuel_value) {
             $xpath->query(sprintf('//fed_losses[@year="%s"]/%s',$key, $fuel_key))->item(0)->nodeValue = "";     
            }
            //remove from fuelshares
            foreach ($fuels as $fuel_key=>$fuel_value) {
                foreach ($sectors as $sector_key=>$sector_value) {
                    $xpath->query(sprintf('//fed_fuelshares[@year="%s" and @sector="%s"]/%s',$key, $sector_key, $fuel_key))->item(0)->nodeValue = "";                          
                }   
            }
            //remove from elmix
            foreach ($elmix_fuels as $el_fuel_key=>$el_fuel_value) {
             $xpath->query(sprintf('//ses_elmix[@year="%s"]/%s',$key, $el_fuel_key))->item(0)->nodeValue = "";     
            }
            //remove from pes domestic production
            foreach ($fuels as $fuel_key=>$fuel_value) {
                if ($fuel_key!='Electricity' && $fuel_key!='Heat'){
                    //print_r($fuels);
                    //echo $xpath->query(sprintf('//pes_domestic_production[@year="%s"]/%s',$key, $fuel_key))->item(0)->nodeValue;
                    //if (sprintf('//pes_domestic_production[@year="%s"]/%s',$key, $fuel_key)!=null)
					$xpath->query(sprintf('//pes_domestic_production[@year="%s"]/%s',$key, $fuel_key))->item(0)->nodeValue = "";  
				}					 
            }
            //remove from tra_efficiency
            foreach ($elmix_fuels as $el_fuel_key=>$el_fuel_value) {
                if($el_fuel_key !='ImportExport')
                    $xpath->query(sprintf('//tra_efficiency[@year="%s"]/%s',$key, $el_fuel_key))->item(0)->nodeValue = "";  
                    
                    $xpath->query(sprintf('//tra_lifetime[@year="%s"]/%s',$key, $el_fuel_key))->item(0)->nodeValue = "";  
            }
            
            //remove load factor   
            $xpath->query(sprintf('//tra_loadfactor[@year="%s"]/Load_factor',$key))->item(0)->nodeValue = "";

            //remove carbon content   
            $xpath->query(sprintf('//tra_carbon_cost[@year="%s"]/carbon_cost',$key))->item(0)->nodeValue = "";
            
            //remove dicount rate   
            $xpath->query(sprintf('//tra_discount_rate[@year="%s"]/discount_rate',$key))->item(0)->nodeValue = "";

            //remove from tra_technical
            foreach ($elmix_fuels as $key1=>$value1) {
                if ($key1!='ImportExport') {
                    $result = $xpath->query(sprintf('//tra_technical[@year="%s"and @technology="%s"]/*',$key, $key1)); 
           	        foreach($result as $data) {
                       //echo $data->nodeValue;
                        $data->nodeValue = ""; 
                    }
                }
             }
             
             //remove from pes_tenvirioment
             foreach ($elmix_fuels as $key1=>$value1) {
                if ($key1!='ImportExport') {
                    $result = $xpath->query(sprintf('//tra_envirioment[@year="%s"and @technology="%s"]/*',$key, $key1)); 
                    foreach($result as $data) {
                       //echo $data->nodeValue;
                        $data->nodeValue = ""; 
                     }
                 }
             }

            //remove from tra_finance
            foreach ($elmix_fuels as $key1=>$value1) {
                if ($key1!='ImportExport') {
                     $result = $xpath->query(sprintf('//tra_finance[@year="%s"and @technology="%s"]/*',$key, $key1)); 
           	         foreach($result as $data) {
                       //echo $data->nodeValue;
                        $data->nodeValue = ""; 
                    }
                }
            }
        }
    }
    file_put_contents($filepath,$xml->saveXML());
}


function edit_case_addyears($filepath, $addyear) {
    $sectors =get_sectors($filepath);
    $fuels = get_fuels($filepath);
    $years = get_years($filepath);
    $elmix_fuels = get_elmix_fuels($filepath);
        
    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);
    //print_r($addyear);

    foreach ($addyear as $key=>$value){
        if ($value=='true'){
                //add years
            $xpath->query(sprintf('//Years/_%s', $key))->item(0)->nodeValue='1';

                //add u fed_bysectors
                foreach ($sectors as $sector_key=>$sector_value){
                    if ($sector_value =="1"){
                        $xpath->query(sprintf('//fed_bysectors[@year="%s"]/%s',$key, $sector_key))->item(0)->nodeValue = "0"; 
                    }
                    else
                        $xpath->query(sprintf('//fed_bysectors[@year="%s"]/%s',$key, $sector_key))->item(0)->nodeValue = ""; 
                    }
                        
                    //add fuelshares
                foreach ($fuels as $fuel_key=>$fuel_value){
                    if ($fuel_value == "1"){
                        foreach ($sectors as $sector_key=>$sector_value){
                            if ($sector_value == "1"){
                                $xpath->query(sprintf('//fed_fuelshares[@year="%s" and @sector="%s"]/%s',$key, $sector_key, $fuel_key))->item(0)->nodeValue = "0";
                            }
                            else{
                                $xpath->query(sprintf('//fed_fuelshares[@year="%s" and @sector="%s"]/%s',$key, $sector_key, $fuel_key))->item(0)->nodeValue = "";
                            }   
                        }
                    }
                    else{
                        foreach ($sectors as $sector_key=>$sector_value){
                            if ($sector_value == "1"){
                                    $xpath->query(sprintf('//fed_fuelshares[@year="%s" and @sector="%s"]/%s',$key, $sector_key, $fuel_key))->item(0)->nodeValue = "";
                                }
                                else
                                {
                                    $xpath->query(sprintf('//fed_fuelshares[@year="%s" and @sector="%s"]/%s',$key, $sector_key, $fuel_key))->item(0)->nodeValue = "";
                                }
                                
                            }
                        }
                    }

                    //add losses
                    foreach ($fuels as $fuel_key=>$fuel_value) {
                        if ($fuel_value=="1")
                        $xpath->query(sprintf('//fed_losses[@year="%s"]/%s', $key, $fuel_key))->item(0)->nodeValue = "0"; 
                    }
                    //add elmix 
                    foreach ($elmix_fuels as $el_fuel_key=>$el_fuel_value) {
                        if ($el_fuel_value=="1")
                        $xpath->query(sprintf('//ses_elmix[@year="%s"]/%s', $key, $el_fuel_key))->item(0)->nodeValue = "0"; 
                    }
                    
                    //add domestic production 
                    foreach ($fuels as $fuel_key=>$fuel_value) {
                        if ($fuel_value=="1"){
							if ($fuel_key!='Electricity' && $fuel_key!='Heat')
							{
								$xpath->query(sprintf('//pes_domestic_production[@year="%s"]/%s', $key, $fuel_key))->item(0)->nodeValue = "0";  
							}
						}
                    }
                    
                    //add efficiency
                    foreach ($elmix_fuels as $el_fuel_key=>$el_fuel_value) {
                        if ($el_fuel_value=="1" &&$el_fuel_key!="ImportExport"&&$el_fuel_key!="Hydro" && $el_fuel_key!="Solar" && $el_fuel_key!="Wind" &&$el_fuel_key!="Geothermal"&&$el_fuel_key!="Nuclear")
                            $xpath->query(sprintf('//tra_efficiency[@year="%s"]/%s', $key, $el_fuel_key))->item(0)->nodeValue = "0"; 

                        if ($el_fuel_value=="1" &&$el_fuel_key!="ImportExport"&&($el_fuel_key=="Hydro"||$el_fuel_key=="Solar"||$el_fuel_key=="Wind"))
                            $xpath->query(sprintf('//tra_efficiency[@year="%s"]/%s', $key, $el_fuel_key))->item(0)->nodeValue = "100"; 
                            
                        if ($el_fuel_value=="1" &&$el_fuel_key!="ImportExport"&&$el_fuel_key=="Geothermal")
                            $xpath->query(sprintf('//tra_efficiency[@year="%s"]/%s', $key, $el_fuel_key))->item(0)->nodeValue = "10";

                        if ($el_fuel_value=="1" &&$el_fuel_key!="ImportExport"&&$el_fuel_key=="Nuclear")
                            $xpath->query(sprintf('//tra_efficiency[@year="%s"]/%s', $key, $el_fuel_key))->item(0)->nodeValue = "33";  

                        if ($el_fuel_value=="1"&&$el_fuel_key!="ImportExport")
                            $xpath->query(sprintf('//tra_lifetime[@year="%s"]/%s', $key, $el_fuel_key))->item(0)->nodeValue = "0"; 
                    }
                    
                    //add load factor 
                    $xpath->query(sprintf('//tra_loadfactor[@year="%s"]/Load_factor', $key))->item(0)->nodeValue = "0"; 
                    
                    //add carbon content 
                    $xpath->query(sprintf('//tra_carbon_cost[@year="%s"]/carbon_cost', $key))->item(0)->nodeValue = "0"; 
                                   
                    //add dicount rate 
                    $xpath->query(sprintf('//tra_discount_rate[@year="%s"]/discount_rate', $key))->item(0)->nodeValue = "0"; 
                    
                    
                    //add tra_technical
                    foreach ($elmix_fuels as $key1=>$value1)
                     {
                        if ($key1!='ImportExport'&& $value1=="1")
                        {
                             $result = $xpath->query(sprintf('//tra_technical[@year="%s"and @technology="%s"]/*',$key, $key1)); 
                                               
                   	         foreach($result as $data)
                             {
                               //echo $data->nodeValue;
                                $data->nodeValue = "0"; 
                             }
                         }
                     }            
                     
                     
                     //add tra_envirioment
                     foreach ($elmix_fuels as $key1=>$value1)
                     {
                        if ($key1!='ImportExport'&& $value1=="1")
                        {
                             $result = $xpath->query(sprintf('//tra_envirioment[@year="%s"and @technology="%s"]/*',$key, $key1)); 
                                               
                   	         foreach($result as $data)
                             {
                               //echo $data->nodeValue;
                                $data->nodeValue = "0"; 
                             }
                         }
                     }           
                     
                     //add tra_finance
                     foreach ($elmix_fuels as $key1=>$value1)
                     {
                        if ($key1!='ImportExport'&& $value1=="1")
                        {
                             $result = $xpath->query(sprintf('//tra_finance[@year="%s"and @technology="%s"]/*',$key, $key1)); 
                                               
                   	         foreach($result as $data)
                             {
                               //echo $data->nodeValue;
                                $data->nodeValue = "0"; 
                             }
                         }
                     }         
                }
            }
            file_put_contents($filepath,$xml->saveXML());
}


function appent_fed_bysectors($filepath, $year, $sect)
{
    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml); 
    //print_r($sect);
    if (is_array($sect))
    {
    foreach ($sect as $k => $val)
    {
        $xpath->query(sprintf('//fed_bysectors[@year="%s"]/%s', $year, $k))->item(0)->nodeValue = $val;
     
    }
    }
    file_put_contents($filepath,$xml->saveXML());
  }  

function appent_pes_domestic_production($filepath, $year, $fuel)
{
    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml); 
    if (is_array($fuel))
    {
    foreach ($fuel as $k => $val)
    {
        $xpath->query(sprintf('//pes_domestic_production[@year="%s"]/%s', $year, $k))->item(0)->nodeValue = $val;
     
    }
    }
    file_put_contents($filepath,$xml->saveXML());
}
     
function appent_fed_fuelshares($filepath, $year, $sector, $shares){
    
    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);
    if (is_array($shares))
    {   
        foreach($shares as $key=>$value)
        {
            $xpath->query(sprintf('//fed_fuelshares[@year="%s" and @sector="%s"]/%s', $year, $sector, $key))->item(0)->nodeValue=$value;
        }
    }
    file_put_contents($filepath,$xml->saveXML());
 }  


function appent_fed_losses($filepath, $year, $losses){
    
    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);   
    if (is_array($losses))
    {
        foreach($losses as $key=>$value)
        {
            $xpath->query(sprintf('//fed_losses[@year="%s"]/%s', $year, $key))->item(0)->nodeValue = $value;
        }
    }
    file_put_contents($filepath,$xml->saveXML());
    
}

function appent_ses_elmix($filepath, $year, $elmix){
    
    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);   
    if (is_array($elmix))
    {
        foreach($elmix as $key=>$value)
        {
            $xpath->query(sprintf('//ses_elmix[@year="%s"]/%s', $year, $key))->item(0)->nodeValue = $value;
        }
    }
    file_put_contents($filepath,$xml->saveXML());
    
}

function appent_reserve_capacity($filepath, $year, $rc, $total){
    
    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);  
    
    $xpath->query("//tra_reserve_capacity[@year=$year]")->item(0)->setAttribute("total", $total); 
    if (is_array($rc))
    {
        foreach($rc as $key=>$value)
        {
            if($value!="" and $key!='ImportExport'){
                //echo "vrijedmnost " . $value . " key " . $key; 
                $xpath->query(sprintf('//tra_reserve_capacity[@year="%s"]/%s', $year, $key))->item(0)->nodeValue = $value;
            }
        }
    }
    file_put_contents($filepath,$xml->saveXML());
}


function appent_ens_capacity($filepath, $year, $fuel, $ensCap){
    
    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);  

    $xpath->query("//ens_capacity[@year=$year]/$fuel")->item(0)->nodeValue = $ensCap;
    
    file_put_contents($filepath,$xml->saveXML());
}


function appent_pes_efficiency($filepath, $year, $efficiency)
{
    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);   
    if (is_array($efficiency))
    {
        foreach($efficiency as $key=>$value)
        {
            if ($key!='Hydro'&&$key!='Solar'&&$key!='Wind'&&$key!='Geothermal'&&$key!='Nuclear')
            $xpath->query(sprintf('//tra_efficiency[@year="%s"]/%s', $year, $key))->item(0)->nodeValue = $value;
        }
    }
    file_put_contents($filepath,$xml->saveXML());
}

function appent_tra_loadfactor($filepath, $year, $loadfactor)
{
    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);   

    $xpath->query(sprintf('//tra_loadfactor[@year="%s"]/Load_factor', $year))->item(0)->nodeValue = $loadfactor;
  
    file_put_contents($filepath,$xml->saveXML());
}

function appent_tra_carbon_content($filepath, $year, $carbon_content)
{
    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load($filepath);    
    $xpath = new DOMXPath($xml);   
    $xpath->query(sprintf('//tra_carbon_cost[@year="%s"]/carbon_cost', $year))->item(0)->nodeValue = $carbon_content;
  
    file_put_contents($filepath,$xml->saveXML());
}

function appent_tra_discount_rate($filepath, $year, $discount_rate)
{
    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load($filepath);    
    $xpath = new DOMXPath($xml);   
    $xpath->query(sprintf('//tra_discount_rate[@year="%s"]/discount_rate', $year))->item(0)->nodeValue = $discount_rate;
  
    file_put_contents($filepath,$xml->saveXML());
}



// function appent_tra_lifetime($filepath, $tech, $llifetime)
// {
//     $xml = new DOMDocument("1.0");
//     $xml->formatOutput = true;
//     $xml->preserveWhiteSpace = false;
//     $xml->load("$filepath");    
//     $xpath = new DOMXPath($xml);   

//     $xpath->query(sprintf('//tra_lifetime[@technology="%s"]/Lifetime', $tech))->item(0)->nodeValue = $llifetime;
  
//     file_put_contents($filepath,$xml->saveXML());
// }

function appent_tra_technical($filepath, $year, $technology, $tech)
{
    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);  
    //rint_r($xml);
    if (is_array($technology))
    {
        foreach($technology as $key=>$value)
        {
            //if ($key!='year'&&$key!='casename'&&$key!='action')
            if ($key == 'Capacity_factor' or $key == 'Installed_power')
            $xpath->query(sprintf('//tra_technical[@year="%s" and @technology="%s"]/%s', $year,$tech, $key))->item(0)->nodeValue = $value;
        }
    }
    file_put_contents($filepath,$xml->saveXML());
}

function appent_tra_envirioment($filepath, $year, $envirioment, $tech)
{
    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);   
    if (is_array($envirioment))
    {
        foreach($envirioment as $key=>$value)
        {
            if ($key!='year'&&$key!='casename'&&$key!='action'&&$key!='tech')
            $xpath->query(sprintf('//tra_envirioment[@year="%s" and @technology="%s"]/%s', $year,$tech, $key))->item(0)->nodeValue = $value;
        }
    }
    file_put_contents($filepath,$xml->saveXML());
}

function appent_tra_finance($filepath, $year, $finance, $tech)
{
    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);   
    if (is_array($finance))
    {
        foreach($finance as $key=>$value)
        {
            if ($key!='year'&&$key!='casename'&&$key!='action'&&$key!='tech')
            $xpath->query(sprintf('//tra_finance[@year="%s" and @technology="%s"]/%s', $year,$tech, $key))->item(0)->nodeValue = $value;
        }
    }
    file_put_contents($filepath,$xml->saveXML());
}

function appent_tra_lifetime_gen($filepath, $tech, $lifetime)
{
    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);   

    $xpath->query(sprintf('//tra_lifetime[@technology="%s"]/Lifetime', $tech))->item(0)->nodeValue = $lifetime;
  
    file_put_contents($filepath,$xml->saveXML());
}


function appent_tra_technical_gen($filepath,  $technology, $tech)
{
    $year = get_years($filepath);
    //print_r($year);
    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);
    //$year = get_years($filepath);
    foreach ($year as $key1=>$value1) 
    { 
        if ($value1 =="1")
        {
            $tmp = substr($key1, 1);
            if (is_array($technology))
            {
                foreach($technology as $key=>$value)
                {
                    if ($key!='year'&&$key!='casename'&&$key!='action'&&$key!='tech')
                    $xpath->query(sprintf('//tra_technical[@year="%s" and @technology="%s"]/%s', $tmp,$tech, $key))->item(0)->nodeValue = $value;
                }
            }
        }
    }
    file_put_contents($filepath,$xml->saveXML());
}

function appent_tra_envirioment_gen($filepath,  $envirioment, $tech)
{
    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath"); 
    $year = get_years($filepath);   
    $xpath = new DOMXPath($xml); 
    foreach ($year as $key1=>$value1) 
    { 
      if ($value1 =="1")
        {
            $tmp = substr($key1, 1);
 
                if (is_array($envirioment))
                {
                    foreach($envirioment as $key=>$value)
                    {
                        if ($key!='year'&&$key!='casename'&&$key!='action'&&$key!='tech')
                        $xpath->query(sprintf('//tra_envirioment[@year="%s" and @technology="%s"]/%s', $tmp ,$tech, $key))->item(0)->nodeValue = $value;
                    }
                }
            
        }
      }
    file_put_contents($filepath,$xml->saveXML());
}

function appent_tra_finance_gen($filepath,  $finance, $tech)
{
    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $year = get_years($filepath);
    $xpath = new DOMXPath($xml); 
    foreach ($year as $key1=>$value1) 
    { 
      if ($value1 =="1")
        {
            $tmp = substr($key1, 1); 
                if (is_array($finance))
                {
                    foreach($finance as $key=>$value)
                    {
                        if ($key!='year'&&$key!='casename'&&$key!='action'&&$key!='tech')
                        $xpath->query(sprintf('//tra_finance[@year="%s" and @technology="%s"]/%s', $tmp,$tech, $key))->item(0)->nodeValue = $value;
                    }
                }
            
        }
      }
    file_put_contents($filepath,$xml->saveXML());
}

function update_fed_bysectors($filepath, $year, $sector, $value){

    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);   
    $nodes = $xpath->query(sprintf('//fed_bysectors[@year="%s"]/%s', $year,$sector))->item(0);
    $nodes->nodeValue = $value; 
    file_put_contents($filepath,$xml->saveXML()); 
}

function update_fed_fuelshares($filepath, $year, $sector, $fuel, $value){

    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);  
    $nodes = $xpath->query(sprintf('//fed_fuelshares[@year="%s" and @sector="%s"]/%s', $year,$sector,$fuel))->item(0);
    $nodes->nodeValue = $value;
    file_put_contents($filepath,$xml->saveXML()); 
}

function update_fed_losses($filepath, $year, $fuel, $value){

    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);       
    $nodes = $xpath->query(sprintf('//fed_losses[@year="%s"]/%s', $year,$fuel))->item(0);
    $nodes->nodeValue = $value; 
    file_put_contents($filepath,$xml->saveXML()); 
}

function update_ses_elmix($filepath, $year, $fuel, $value){

    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);       
    $nodes = $xpath->query(sprintf('//ses_elmix[@year="%s"]/%s', $year, $fuel))->item(0);
    $nodes->nodeValue = $value; 
    file_put_contents($filepath,$xml->saveXML()); 
}
function update_efficiency($filepath, $year, $fuel, $value){

    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);       
    $nodes = $xpath->query(sprintf('//tra_efficiency[@year="%s"]/%s', $year, $fuel))->item(0);
    $nodes->nodeValue = $value; 
    file_put_contents($filepath,$xml->saveXML()); 
}
function update_reserve_capacity($filepath, $year, $fuel, $value){

    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);       
    $nodes = $xpath->query(sprintf('//tra_reserve_capacity[@year="%s"]/%s', $year, $fuel))->item(0);
    $nodes->nodeValue = $value; 
    file_put_contents($filepath,$xml->saveXML()); 
}
function update_reserve_capacity_total($filepath, $year, $value){

    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);       
    $nodes = $xpath->query(sprintf('//tra_reserve_capacity[@year="%s"]', $year))->item(0);
    $nodes->setAttribute('total', $value);
    //$nodes->attribute()->total = $value; 
    file_put_contents($filepath,$xml->saveXML()); 
}
function update_pes_domestic_production($filepath, $year, $fuel, $value){

    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);   
    $nodes = $xpath->query(sprintf('//pes_domestic_production[@year="%s"]/%s', $year,$fuel))->item(0);
    $nodes->nodeValue = $value; 
    file_put_contents($filepath,$xml->saveXML()); 
}
function update_tra_technical($filepath, $year, $fuel, $value){

    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);  
    $nodes = $xpath->query(sprintf('//tra_technical[@year="%s" and @technology="%s"]/Installed_power', $year,$fuel))->item(0);
    $nodes->nodeValue = $value;
    file_put_contents($filepath,$xml->saveXML()); 
}

function update_tra_finance($filepath, $year, $fuel, $value){

    $xml = new DOMDocument("1.0");
    $xml->formatOutput = true;
    $xml->preserveWhiteSpace = false;
    $xml->load("$filepath");    
    $xpath = new DOMXPath($xml);  
    $nodes = $xpath->query(sprintf('//tra_finance[@year="%s" and @technology="%s"]/Investment_cost', $year, $fuel))->item(0);
    $nodes->nodeValue = $value;
    file_put_contents($filepath,$xml->saveXML()); 
}



function raw_json_encode($input) {

    return preg_replace_callback(
        '/\\\\u([0-9a-zA-Z]{4})/',
        function ($matches) {
            return mb_convert_encoding(pack('H*',$matches[1]),'UTF-8','UTF-16');
        },
        json_encode($input)
    );

}
?>