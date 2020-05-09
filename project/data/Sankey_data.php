<?php
header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=file.csv");
header("Pragma: no-cache");
header("Expires: 0");
//require_once '../functions/xml_functions.php';
//require_once '../functions/calc_functions.php';
require_once '../config.php';
require_once '../classes/EsstCase.php';
require_once '../includes/session.php';

$filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";
$year = $_SESSION['year'];
if (file_exists($filepath)) {

    $xml = simplexml_load_file($filepath)
        or die("Error: Cannot create object");
    $esstCase = new EsstCase($_SESSION['case'], $xml);

    $sectors = $esstCase->getSectors();
    $elmix_fuels = $esstCase->getElMixFuels(); 
    $fuels = $esstCase->getFuels();
    $year = $_GET['year'];

    if(isset($_GET['action']) && $_GET['action'] == 'sankey'){

        $flow = array($Electricity_Plants,$Distribution_Losses,$Industry,$Transport,$Residential,$Commercial,$Agriculture,$Fishing,$Non_energy_use,$Other);
    
        echo "source,target,value\n";      
               
        //dio za TFC          
        foreach ($sectors as $key_sector=>$value_sector){
            if($value_sector == '1') {
                foreach ($fuels as $key_fuel=>$value_fuel){
                    if ($value_fuel == "1") {

                        $tmp = $esstCase->getTFC($key_fuel, $year, $key_sector);

                        if ($key_fuel == 'Electricity' && $tmp != 0){
                            echo $Transformation.",".$key_sector.",".$tmp.","."\n";
                        }else if($key_fuel != 'Electricity' && $tmp != 0){
                            echo $fuel_obj[$key_fuel].",".$key_sector.",".$tmp.","."\n";
                        }
                    }
                } 
            }
        }
             
        


        //dio za transformacije
        foreach ($elmix_fuels as $key=>$value){
            if ($value =="1" && $key!='ImportExport') {
                $tmp = $esstCase->getPrimaryEnergyForElGeneration($key, $year);
                if($tmp != 0){
                    echo $fuel_obj[$key].",".$Transformation.",".$tmp."\n";
                }
            }
        }

    
        //dio za losses      
        foreach ($fuels as $key1=>$value1){
            if ($value1 == "1") {
                $tmp = $esstCase->getFedLossesValue($key1, $year);
                if($tmp != 0){
                    //echo "fuel                             " . $key1 . "\n";
                    if ($key1 == 'Electricity'){
                        echo  $Transformation.",".$Losses.",".$tmp."\n";
                    }else{
                        echo $fuel_obj[$key1].",".$Losses.",".$tmp."\n";
                    }
                }
            }
        }
    }
}

//echo "Agricultural Energy Use,Carbon Dioxide,1.4\n";
//echo "Agriculture,Agriculture Soils,5.2 ";
//echo "Agriculture,Livestock and Manure,5.4 ";
//echo "Agriculture,Other Agriculture,1.7 ";
//echo "Agriculture,Rice Cultivation,1.5 ";
//echo "Agriculture Soils,Nitrous Oxide,5.2 ";

//source,target,value
//Agricultural Energy Use,Carbon Dioxide,1.4
//Agriculture,Agriculture Soils,5.2
//Agriculture,Livestock and Manure,5.4
//Agriculture,Other Agriculture,1.7
//Agriculture,Rice Cultivation,1.5
//Agriculture Soils,Nitrous Oxide,5.2
//Air,Carbon Dioxide,1.7
//Aluminium Non-Ferrous Metals,Carbon Dioxide,1
//Aluminium Non-Ferrous Metals,HFCs - PFCs,0.2
//Cement,Carbon Dioxide,5
//Chemicals,Carbon Dioxide,3.4
//Chemicals,HFCs - PFCs,0.5
//Chemicals,Nitrous Oxide,0.2
//Coal Mining,Carbon Dioxide,0.1
//Coal Mining,Methane,1.2
//Commercial Buildings,Carbon Dioxide,6.3
//Deforestation,Carbon Dioxide,10.9
//Electricity and heat,Agricultural Energy Use,0.4
//Electricity and heat,Aluminium Non-Ferrous Metals,0.4
//Electricity and heat,Cement,0.3
//Electricity and heat,Chemicals,1.3
//Electricity and heat,Commercial Buildings,5
//Electricity and heat,Food and Tobacco,0.5
//Electricity and heat,Iron and Steel,1
//Electricity and heat,Machinery,1
//Electricity and heat,Oil and Gas Processing,0.4
//Electricity and heat,Other Industry,2.7
//Electricity and heat,Pulp - Paper and Printing,0.6
//Electricity and heat,Residential Buildings,5.2
//Electricity and heat,T and D Losses,2.2
//Electricity and heat,Unallocated Fuel Combustion,2
//Energy,Electricity and heat,24.9
//Energy,Fugitive Emissions,4
//Energy,Industry,14.7
//Energy,Other Fuel Combustion,8.6
//Energy,Transportation,14.3
//Food and Tobacco,Carbon Dioxide,1
//Fugitive Emissions,Coal Mining,1.3
//Fugitive Emissions,Oil and Gas Processing,3.2
//Harvest \/ Management,Carbon Dioxide,1.3
//Industrial Processes,Aluminium Non-Ferrous Metals,0.4
//Industrial Processes,Cement,2.8
//Industrial Processes,Chemicals,1.4
//Industrial Processes,Other Industry,0.5
//Industry,Aluminium Non-Ferrous Metals,0.4
//Industry,Cement,1.9
//Industry,Chemicals,1.4
//Industry,Food and Tobacco,0.5
//Industry,Iron and Steel,3
//Industry,Oil and Gas Processing,2.8
//Industry,Other Industry,3.8
//Industry,Pulp - Paper and Printing,0.5
//Iron and Steel,Carbon Dioxide,4
//Land Use Change,Deforestation,10.9
//Land Use Change,Harvest \/ Management,1.3
//Landfills,Methane,1.7
//Livestock and Manure,Methane,5.1
//Livestock and Manure,Nitrous Oxide,0.3
//Machinery,Carbon Dioxide,1
//Oil and Gas Processing,Carbon Dioxide,3.6
//Oil and Gas Processing,Methane,2.8
//Other Agriculture,Methane,1.4
//Other Agriculture,Nitrous Oxide,0.3
//Other Fuel Combustion,Agricultural Energy Use,1
//Other Fuel Combustion,Commercial Buildings,1.3
//Other Fuel Combustion,Residential Buildings,5
//Other Fuel Combustion,Unallocated Fuel Combustion,1.8
//Other Industry,Carbon Dioxide,6.6
//Other Industry,HFCs - PFCs,0.4
//Pulp - Paper and Printing,Carbon Dioxide,1.1
//Rail - Ship and Other Transport,Carbon Dioxide,2.5
//Residential Buildings,Carbon Dioxide,10.2
//Rice Cultivation,Methane,1.5
//Road,Carbon Dioxide,10.5
//T and D Losses,Carbon Dioxide,2.2
//Transportation,Air,1.7
//Transportation,Rail - Ship and Other Transport,2.5
//Transportation,Road,10.5
//Unallocated Fuel Combustion,Carbon Dioxide,3
//Unallocated Fuel Combustion,Methane,0.4
//Unallocated Fuel Combustion,Nitrous Oxide,0.4
//Waste,Landfills,1.7
//Waste,Waste water - Other Waste,1.5
//Waste water - Other Waste,Methane,1.2
//Waste water - Other Waste,Nitrous Oxide,0.3
//

?>