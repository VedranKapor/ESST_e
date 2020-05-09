<?php
require_once '../config.php';
require_once '../functions/xml_functions.php';
require_once '../classes/EsstCase.php';

	//$filepath = "../xml/".$_SESSION['case']."/".$_SESSION['case'].".xml";
    $filepath = USER_CASE_PATH.$_GET['casename']."/".$_GET['casename'].".xml";
    $orders = array();
    //echo $filepath;
    if (file_exists($filepath))
    {
       $xml = simplexml_load_file($filepath)
        or die("Error: Cannot create object");
       $esstCase = new EsstCase($_SESSION['case'], $xml);
       
       $technology = get_technical($filepath); 
       $elmix = get_elmix_fuels($filepath);                     
       $years = get_years($filepath);
       $envirioment = get_envirioment($filepath); 
       $finance = get_finance($filepath);   
       $loadfactor = get_loadfactor($filepath);
      

            foreach ($years as $key1=>$value1) {
                if ($value1=="1")
                {
                    $tmp = substr($key1, 1);
                    $lfresult = $xml->xpath(sprintf('//tra_loadfactor[@year="%s"]/Load_factor', $tmp));
                    $Load_factor = (string)$lfresult[0];
                    foreach ($elmix as $key2=>$value2)
                    {
                        if ($value2=="1"&& $key2!='ImportExport')
                        {
                            foreach ($technology as $keyt=>$valuet)
                            {
                                    $result = $xml->xpath(sprintf('//tra_technical[@year="%s" and @technology="%s"]/%s', $tmp, $key2, $keyt));
                                    $$keyt = (string)$result[0];
                            } 
                            foreach ($envirioment as $keye=>$valuee)
                            {
                                    $result = $xml->xpath(sprintf('//tra_envirioment[@year="%s" and @technology="%s"]/%s', $tmp,$key2, $keye));
                                    $$keye = (string)$result[0];
                            }
                            foreach ($finance as $keyf=>$valuef)
                            {
                                    $result = $xml->xpath(sprintf('//tra_finance[@year="%s" and @technology="%s"]/%s', $tmp,$key2, $keyf));
                                    $$keyf = (string)$result[0];
                            }   
                            foreach ($loadfactor as $keyl=>$valuel)
                            {
                                    $result = $xml->xpath(sprintf('//tra_loadfactor[@year="%s"]/%s', $tmp, $keyl));
                                    $$keyl = (string)$result[0];
                            } 
                            
                            $result_eff = $xml->xpath(sprintf('//tra_efficiency[@year="%s"]/%s', $tmp,$key2));
                            $Efficiency = (string)$result_eff[0];

                            $result_const = $xml->xpath(sprintf('//construction_time[@year="%s"]/%s', $tmp,$key2));
                            $Construction_time = (string)$result_const[0];

                            $result_Lifetime = $xml->xpath(sprintf('//tra_lifetime[@year="%s"]/%s', $tmp,$key2));
                            $Lifetime = (string)$result_Lifetime[0];
                            
                            // $result = $xml->xpath(sprintf('//tra_lifetime[@technology="%s"]/Lifetime', $key2));
                            // $Lifetime = (string)$result[0];
                            
                              
                                   	    $orders[] = array(
                            			'year' => $tmp,
                                        'technology' => $key2,
                                        'Installed_power' => $Installed_power,
                            			'Capacity_factor' => $Capacity_factor,
                            			'Load_factor' => $Load_factor,
                                        'Lifetime' => $Lifetime,
                            			'CO2' => $CO2,
                                        'NOX' => $NOX,
                                        'SO2' => $SO2,
                                        'PM' => $Other,
                                        'Fuel_cost' => $Fuel_cost,
                                        'Investment_cost' => $Investment_cost,
                                        'Operating_cost_fixed' => $Operating_cost_fixed,
                                        'Operating_cost_variable' => $Operating_cost_variable,
                                        'Efficiency' => $Efficiency,
                                        'Construction_time' => $Construction_time
                            			);
                         }
                      }
                    
                 }
            }

    echo '{"data":'.json_encode($orders).'}';
    }


?>
