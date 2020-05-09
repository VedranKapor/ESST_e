<?php 
require_once '../../functions/xml_functions.php'; 
require_once '../../config.php';
require_once '../../classes/EsstCase.php';

$year = array ('1990'=>'false', '2000'=>'false', '2005'=>'false', '2010'=>'false', '2015'=>'false', '2020'=>'false', '2025'=>'false','2030'=>'false','2035'=>'false','2040'=>'false','2045'=>'false','2050'=>'false');
$sector = array ('Industry'=>'false', 'Transport'=>'false', 'Residential'=>'false', 'Commercial'=>'false', 'Agriculture'=>'false', 'Fishing'=>'false', 'Non_energy_use'=>'false','Other'=>'false');
$fuel = array ('Electricity'=>'false', 'Coal'=>'false', 'Oil'=>'false', 'Gas'=>'false', 'Biofuels'=>'false', 'Peat'=>'false', 'Heat'=>'false', 'Waste'=>'false','OilShale'=>'false');
$elmix_fuel = array ('Hydro'=>'false', 'Coal'=>'false', 'Oil'=>'false', 'Gas'=>'false', 'Biofuels'=>'false', 'Peat'=>'false', 'Waste'=>'false','OilShale'=>'false','Solar'=>'false','Wind'=>'false','Geothermal'=>'false','Nuclear'=>'false','ImportExport'=>'true');

if (isset($_POST['action'])){
	switch($_POST['action']){
		default:
		break;	
        case 'saveNew':
            try{
                $newfolder = USER_CASE_PATH."{$_POST['Casename']}";      
                if (!file_exists($newfolder)){

                    //ukoliko folder ne postoji napravi folder sa imenom case koji je unio korisnik
                    mkdir($newfolder, 777, true);

                    $years = $_POST['Years'];
                    $sectors = $_POST['Sector'];
                    $fuels = $_POST['Commodity'];
                    $elmix_fuels = $_POST['Technology'];

                    foreach ($years as $yr=>$flag){
                        $year[$yr] = 'true';
                    }
                    foreach ($sectors as $sec=>$flag){
                        $sector[$sec] = 'true';
                    }
                    foreach ($fuels as $com=>$flag){
                        $fuel[$com] ='true';
                    }
                    foreach ($elmix_fuels as $tech=>$flag){
                        $elmix_fuel[$tech] = 'true';
                    }

                    $technical_data = array('Capacity_factor'=>1, 'Installed_power'=>1);
                    $enviromental_data = array('CO2'=>1, 'NOX'=>1, 'SO2'=>1,  'Other'=>1);
                    $financial_data = array('Fuel_cost'=>1, 'Investment_cost'=>1, 'Operating_cost_fixed'=>1, 'Operating_cost_variable'=>1,);

                    unset($_SESSION['case']);
                    $_SESSION['case']= $_POST['Casename'];
                    
                    if($_POST['Description'] == ""){
                        $_POST['Description'] = '-';
                    }

                    $test = create_case($_POST['Casename'], 
                                        $_POST['Date'],
                                        $_POST['Currency'], 
                                        $_POST['Unit'], 
                                        $_POST['Description'], 
                                        $newfolder,
                                        $sector, 
                                        $fuel, 
                                        $year, 
                                        $elmix_fuel, 
                                        $technical_data, 
                                        $enviromental_data,
                                        $financial_data,
                                        "2.7.0.");

                    if ($test){
                        echo json_encode(array('msg'=>"Case created!", "type"=>"SUCCESS"));
                    }
                    else{
                        echo json_encode(array('msg'=>"Case not created, error!", "type"=>"ERROR"));
                    }           
            }
                else {
                    echo json_encode(array('msg'=>"Cese with same name exists!", "type"=>"EXIST"));
                    }
                die();
                echo json_encode(array('msg'=>"Case created!", "type"=>"SUCCESS"));
            }
            catch(runtimeException $e){
                echo json_encode(array('msg'=>$ex->getMessage(), "type"=>"ERROR"));
            }
		break;
		case 'saveEdit': 
			try{
                //  echo 'post';
                //  echo "<pre>";
                //  print_r($_POST);
                // echo "</pre>";
                //general data update
                $casename = $_POST['Casename'];
                $filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";
                $urlgenData = USER_CASE_PATH.$_SESSION['case'].'/hSimulation/genData.json';
                //print_r($_POST);
                $date = $_POST['Date'];
                if($_POST['Description'] == ""){
                    $_POST['Description'] = '-';
                }
                $description = $_POST['Description'];
                $unit = $_POST['Unit'];
                $currency = $_POST['Currency'];

                $res = edit_case_general($filepath, $casename, $date, $description, $unit, $currency);
                if($res ==3){
                     echo json_encode(array('msg'=>"Case exists!", "type"=>"EXIST"));
                     die();
                }
          
           
                $filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";
                $xml = simplexml_load_file($filepath)
                    or die("Error: Cannot create object");
                //print_r($xml);
                //potrebno kao globalna variabla u xml_functions
                $esstCase = new EsstCase($_POST['Casename'], $xml);

                ////////////////////////////////////////////////////////////////////////////////////////////////////Years                
                foreach($xml->Years[0] as $yr=>$flag){
                    if($flag == 1){
                        $yearVal = str_replace('_', '', $yr);
                        $yearsXml[$yearVal] = true;
                    }
                }

                $yearsPost = array_fill_keys(array_keys($_POST['Years']), true);
                $AddYears = array_diff_key ($yearsPost, $yearsXml);
                $RemoveYears = array_diff_key ($yearsXml, $yearsPost);

                if (file_exists($urlgenData)){
                    
                    $contentgenData = file_get_contents( $urlgenData );
                    $genData = json_decode($contentgenData, true);
            
                    $genData['YEARS'] = array_keys($yearsPost);

                    $fp = fopen($urlgenData, 'w');
                    fwrite($fp, json_encode($genData, JSON_PRETTY_PRINT));
                    fclose($fp);
                }

                edit_case_removeyears($filepath, $RemoveYears);
                edit_case_addyears($filepath, $AddYears);

                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////Sectors
                foreach($xml->Sectors[0] as $sec=>$flag){
                    if($flag == 1){
                        $sectorsXml[$sec] = true;
                    }
                }
                $sectorsPost = array_fill_keys(array_keys($_POST['Sector']), true);
                $AddSectors = array_diff_key ($sectorsPost, $sectorsXml);
                $RemoveSectors = array_diff_key ($sectorsXml, $sectorsPost);

                edit_case_addsectors($filepath, $AddSectors);
                edit_case_removesectors($filepath, $RemoveSectors);

                //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////Fuels
                foreach($xml->Fuels[0] as $fl=>$flag){
                    if($flag == 1){
                        $fuelsXml[$fl] = true;
                    }
                }
                $fuelsPost = array_fill_keys(array_keys($_POST['Commodity']), true);
                $AddFuels = array_diff_key ($fuelsPost, $fuelsXml);
                $RemoveFuels = array_diff_key ($fuelsXml, $fuelsPost);

                edit_case_addfuels($filepath, $AddFuels);
                edit_case_removefuels($filepath, $RemoveFuels);

                ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////techs
                foreach($xml->ElMix_fuels[0] as $elmix=>$flag){
                    if($flag == 1){
                        $elmixXml[$elmix] = true;
                    }
                }
                $_POST['Technology']['ImportExport'] = 'on';
                //$_POST['Technology']['Storage'] = 'on';
                $elmixPost = array_fill_keys(array_keys($_POST['Technology']), true);
                $AddElmix = array_diff_key ($elmixPost, $elmixXml);
                $RemoveElMix = array_diff_key ($elmixXml, $elmixPost);
                //remove ImportExport iz dipatch tehnologija
                //20072019 dodLI iMPORTeXPORT U HsIMULACIJU, sada je potreban ImportExport u dispatch file
                //unset($RemoveElMix['ImportExport']);

                edit_case_addPfuels($filepath, $AddElmix);
                edit_case_removePfuels($filepath, $RemoveElMix);

                    //     print_r($fuelsPost);
                    //     print_r($fuelsXml);
                    //    print_r($AddFuels);
                    //    print_r($RemoveFuels);
                    // echo "el mix Xml";
                    // echo "<pre>";
                    // print_r($elmixXml);
                    // echo "</pre>";

                    // echo "add techc";
                    // echo "<pre>";
                    // print_r($AddElmix);
                    // echo "</pre>";
                    
                    // echo "remove techs";
                    // echo "<pre>";
                    // print_r($RemoveElMix);
                    // echo "</pre>";
                    
                    // echo "techs";
                    // echo "<pre>";
                    // print_r(array_keys($elmixPost));
                    // echo "</pre>";
                //dispatch technologijes
                if (file_exists($urlgenData)){
                    $contentgenData = file_get_contents( $urlgenData );
                    $genData = json_decode($contentgenData, true);
            
                    $yrs = array_keys($yearsPost);
                    foreach($yrs as $y){
                        $genData['DISPATCH'][$y] = array_keys($elmixPost);
                        array_push($genData['DISPATCH'][$y], "Storage");
                    }

                    

                    $fp = fopen($urlgenData, 'w');
                    fwrite($fp, json_encode($genData, JSON_PRETTY_PRINT));
                    fclose($fp);
                }
				echo json_encode(array('msg'=>"Case updated!", "type"=>"SUCCESS"));
			}
			catch(runtimeException $e){
				echo json_encode(array('msg'=>$ex->getMessage(), "type"=>"ERROR"));
			}
		break;
	}
}
?>