<?php
//require_once '../functions/xml_functions.php';
//require_once '../functions/calc_functions.php';
require_once '../config.php';
require_once '../classes/EsstCase.php';
require_once '../includes/session.php';

$filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";
$orders = array();
$year = $_GET['year'];

if (file_exists($filepath)){
        
        $xml = simplexml_load_file($filepath)
          or die("Error: Cannot create object");
        $esstCase = new EsstCase($_SESSION['case'], $xml);
       
        $sectors = $esstCase->getSectors();
        $elmix_fuels = $esstCase->getElMixFuels(); 
        $fuels = $esstCase->getFuels();

        $flow = array($Production, $import_export, $TPES, $Transformation, $Electricity_Plants,$Distribution_Losses,$TFC,$TFEC,$Industry,$Transport,$Residential,$Commercial,$Agriculture,$Fishing,$Other,$Non_energy_use);
        
        $commodities = array("Coal", "Oil", "OilShale", "Gas", "Biofuels", "Peat", "Waste", "Solar", "Wind", "Hydro", "Geothermal", "Nuclear", "ImportExport", "Electricity", "Heat");

        if (!isset($_GET['trans'])){
            foreach ($flow as $flowkey){                
                switch ($flowkey) {
                    case $Production:
                        foreach($fuels as $k=>$val){
                            if($val == "1"  && $k!= 'Electricity' && $k!='Heat'){
                                
                                $$k = $esstCase->getDomProduction($_GET['year'], $k); 
                               // echo 'comodity ' . $k . ' dom prod ' . $$k . "<br>";
                                $$k = $$k == 0 ? null : $$k;                                 
                            }else{
                                $$k = null;
                            }
                        }
                        foreach ($elmix_fuels as $key=>$value){
                            if  ($value =="1" && ($key=='Nuclear' || $key=='Geothermal' || $key=='Solar' || $key=='Wind' || $key=='Hydro')) {
                            
                                $$key = $esstCase->getTPES($key, $_GET['year']);
                                $$key = $$key == 0 ? null : $$key;
                            }
                            else if (!array_key_exists ( $key,  $fuels ))
                               $$key = null;                            
                        } 


                        // foreach ($elmix_fuels as $key=>$value){
                        //     if ($value =="1" && $key!='ImportExport'  && $key!='Nuclear' && $key!='Geothermal' && $key!='Solar' && $key!='Wind' && $key!='Hydro')  {
                        //         $$key = $esstCase->getDomProduction($_GET['year'], $key); 
                        //         $$key = $$key == 0 ? null : $$key;               
                        //     }
                        //     else if  ($value =="1" && ($key=='Nuclear' || $key=='Geothermal' || $key=='Solar' || $key=='Wind' || $key=='Hydro')) {
                            
                        //         $$key = $esstCase->getTPES($key, $_GET['year']);
                        //         $$key = $$key == 0 ? null : $$key;
                        //     }
                        //     else
                        //         $$key = null;                            
                        // } 


                        $Heat = null;
                        $Electricity = null;
                    break;

                    case $import_export:
                        foreach ($elmix_fuels as $key=>$value){
                            //if ($value =="1" && $key!='ImportExport'  && $key!='Nuclear' && $key!='Geothermal' && $key!='Solar' && $key!='Wind' && $key!='Hydro' && $fuels[$key]==1){
                            if ( $key!='ImportExport'  && $key!='Nuclear' && $key!='Geothermal' && $key!='Solar' && $key!='Wind' && $key!='Hydro' ){ 
                                $tpes = $esstCase->getTPES($key, $_GET['year']);
                                $domProd = $esstCase->getDomProduction($_GET['year'], $key);
                                //echo "tpes " . $tpes . " dom prod " . $domProd . "<br>";
                                if (isset($domProd) && $domProd != ""){
                                    $$key = $tpes - $domProd; 
                                }else {
                                    $$key = $tpes;
                                }
                                   
                                $$key = $$key == 0 ? null : $$key; 
                                       
                            }
                            else
                                $$key = null;
                        } 
                        //$Electricity = get_tpes('ImportExport', $_GET['year']);
                        //$Electricity = $esstCase->getTPES('ImportExport', $_GET['year']);
                        $Electricity = $esstCase->getElDemand("ImportExport", $_GET['year']);
                        $Electricity = $Electricity==0 ? null : $Electricity; 
                        $Heat = null;                         
                    break;
                        
                    case $TPES:
                        foreach ($elmix_fuels as $key=>$value) {
                            //if ($value =="1" && $key!='ImportExport' ) {
                                $$key = $esstCase->getTPES($key, $_GET['year']);    
                                $$key = $$key == 0 ? null : $$key;    
                            // }
                            // else
                            //     $$key = null;
                        } 
                        //$Electricity =  get_tpes('ImportExport', $_GET['year']);
                        $Electricity = $esstCase->getTPES('ImportExport', $_GET['year']);
                        $Electricity = $Electricity==0 ? null : $Electricity; 
                        $Heat = null; 
                        //$Heat =  get_tpes('ImportExport', $_GET['year']);
                        break;
                    
                    case $Transformation:
                        $Electricity=0;
                        foreach ($elmix_fuels as $key=>$value){
                            if ($value =="1" && $key!='ImportExport'){
                                 //$$key = get_pe_el_gen($filepath,$key, $_GET['year']);
                                 $$key = $esstCase->getPrimaryEnergyForElGeneration($key, $_GET['year']);
                                 $$key = $$key == 0 ? null : $$key;

                                 $Electricity = $Electricity + $esstCase->getElDemand($key,  $_GET['year']);
                             }
                        }
                        
                        //$Electricity=0;
                        //$Electricity = $Electricity + get_ses('Electricity', $_GET['year']);
                       // $Electricity = $Electricity + $esstCase->getSES('Electricity', $_GET['year']);
                        $Electricity = $Electricity==0 ? null : $Electricity;
                        
                     //   foreach ($fuels as $key=>$value)
//                        {
//                            if ($value =="1") 
//                            {
//                                 $Electricity = $Electricity + get_el_output($filepath, $key, $_GET['year']);
//                                 $Electricity = $Electricity==0 ? null : $Electricity; 
//                             }
//                             else
//                                $Electricity = null;
//                        }
                        break;
                        
                    case $Electricity_Plants:
                        $Electricity=0;
                        foreach ($elmix_fuels as $key=>$value) {
                            if ($value =="1" && $key!='ImportExport') {
                                $$key = $esstCase->getPrimaryEnergyForElGeneration($key, $_GET['year']);
                                $$key = $$key == 0 ? null : $$key;
                                $Electricity = $Electricity + $esstCase->getElDemand($key,  $_GET['year']);
                            }
                        }
                        
                        //$Electricity=0;
                         //$Electricity = $Electricity + get_el_output($filepath,$key, $_GET['year']);
                         //$Electricity = $Electricity + get_transformation_output($filepath,'Electricity', $_GET['year']);
                         //$Electricity = $Electricity + get_ses('Electricity', $_GET['year']);
                         //$Electricity = $Electricity + $esstCase->getSES('Electricity', $_GET['year']);
                         $Electricity = $Electricity==0 ? null : $Electricity; 
                        
                        break;
                        
                    case $Own_Use:
                        
                        break;
                    case $Distribution_Losses:
                        foreach ($fuels as $key=>$value)
                        {
                            if ($value == "1") 
                            {
                                //$$key = get_losses_value($key, $_GET['year']);
                                $$key = $esstCase->getFedLossesValue($key, $_GET['year']);
                                $$key = $$key == 0 ? null : $$key;
                             }
                             
                             else
                                $$key = null;
                                //echo $Electricity;
                        }
                        break;
                    case $TFC:
                        foreach ($fuels as $key=>$value){
                            if ($value == "1") {
                               foreach($sectors as $key1=>$value1) {
                                   if($value1 =='1') {

                                        //echo " fuel7  val " . $key . " sector " . $key1  . "<br>";
                                        $$key = $$key + $esstCase->getTFC($key, $_GET['year'], $key1);
                                        $$key = $$key == 0 ? null : $$key;
                                   }
                               }
                            }
                            else
                                $$key = null;
                        } 
                        
                        break;
                     case $TFEC:
                        foreach ($fuels as $key=>$value)
                        {
                            if ($value == "1") 
                            {
                               foreach($sectors as $key1=>$value1)
                               {
                                   if($value1=='1'&& $key1!= 'Non_energy_use')
                                   {
                                        // $$key = $$key + get_tfc($key, $_GET['year'], $key1);
                                        $$key = $$key + $esstCase->getTFC($key, $_GET['year'], $key1);
                                        $$key = $$key == 0 ? null : $$key;
                                   }
                               }
                            }
                            else
                                $$key = null;
                        } 
                        
                        break;
                    case $Industry:
                        foreach ($fuels as $key=>$value)
                        {
                            if ($value == "1" && $sectors[$flowkey] == 1) 
                            {
                               $$key = $esstCase->getTFC($key, $_GET['year'], 'Industry');
                               $$key = $$key == 0 ? null : $$key;
                            }
                            else
                                $$key = null;
                        }                        
                        break;
                        
                    case $Transport:
                        foreach ($fuels as $key=>$value)
                        {
                            if ($value == "1" && $sectors[$flowkey] == 1) 
                            {
                               $$key = $esstCase->getTFC($key, $_GET['year'], 'Transport');
                                $$key = $$key == 0 ? null : $$key;
                             }
                             else
                                $$key = null;
                        }
                        
                        break;
                    case $Residential:
                        foreach ($fuels as $key=>$value)
                        {
                            if ($value == "1" && $sectors['Residential'] == 1) 
                            {
                               $$key = $esstCase->getTFC($key, $_GET['year'], 'Residential');
                                $$key = $$key == 0 ? null : $$key;
                             }
                             else
                                $$key = null;
                        }
                        
                        break;
                    case $Commercial:
                        foreach ($fuels as $key=>$value) {
                            if ($value == "1" && $sectors[$flowkey] == 1){
                                $$key = $esstCase->getTFC($key, $_GET['year'], 'Commercial');
                                $$key = $$key == 0 ? null : $$key;
                            }
                            else
                                $$key = null;
                        }
                        
                        break;
                    case $Agriculture:
                        foreach ($fuels as $key=>$value)
                        {
                            if ($value == "1" && $sectors[$flowkey] == 1) 
                            {
                               $$key = $esstCase->getTFC($key, $_GET['year'], 'Agriculture');
                                $$key = $$key == 0 ? null : $$key;
                             }
                             else
                                $$key = null;
                        }
                        
                        break;
                    case $Fishing:
                        foreach ($fuels as $key=>$value)
                        {
                            if ($value == "1"&& $sectors[$flowkey] == 1) 
                            {
                               $$key = $esstCase->getTFC($key, $_GET['year'], 'Fishing');
                                $$key = $$key == 0 ? null : $$key;
                             }
                             else
                                $$key = null;
                        }
                        
                        break;
                    case  $Non_energy_use:
                        foreach ($fuels as $key=>$value)
                        {
                            if ($value == "1" && $sectors['Non_energy_use'] == 1) 
                            {
                               $$key = $esstCase->getTFC($key, $_GET['year'], 'Non_energy_use');
                                $$key = $$key == 0 ? null : $$key;
                             }
                             else
                                $$key = null;
                        }
                        
                        break;
                    case $Other:
                        foreach ($fuels as $key=>$value)
                        {
                            if ($value == "1" && $sectors[$flowkey] == 1) 
                            {
                               $$key = $esstCase->getTFC($key, $_GET['year'], 'Other');
                                $$key = $$key == 0 ? null : $$key;
                             }
                             else
                                $$key = null;
                        }
                        
                        break;
                }

                $SES[] = array(
        			'flow' => $flowkey,
        			'Hydro' => $Hydro,
        			'Coal' => $Coal,
        			'Oil' => $Oil,
        			'Gas' => $Gas,
                    'Biofuels' => $Biofuels,
                    'Peat' => $Peat,
                    'Waste' => $Waste,
                    'OilShale' => $OilShale,
                    'Solar' => $Solar,
                    'Wind' => $Wind,
                    'Geothermal' => $Geothermal,
                    'Nuclear' => $Nuclear,
                    'Electricity' => $Electricity,
                    'Heat' => $Heat,
                    'ImportExport' => $ImportExport
                    
        			);
                     foreach ($elmix_fuels as $key=>$value)
                        {
                            if ($value =="1" ) 
                            {
                            $$key = null;                      
                            }
                        } 
                     foreach ($fuels as $key=>$value)
                        {
                            if ($value =="1" ) 
                            {
                             $$key = null;                       
                            }
                        }  
                    
                }
        $array1=array_filter($SES);
        //$array1 = array_filter(array_map('array_filter', $array1));
	    echo '{"data":'.json_encode($array1).'}';
        die(); 
    } 
}
?>
