<?php
require_once '../../config.php';
require_once '../../classes/paths.php';
require_once '../../functions/xml_functions.php';
require_once '../../classes/Const.Class.php';
require_once '../../classes/EsstCase.Class.php';
require_once '../../classes/HourlyAnalysis.Class.php';
require_once '../../classes/Emission.Class.php';
require_once '../../classes/LCOE.Class.php';
require_once '../../classes/hPattern.Class.php';
require_once '../../classes/Calculate.Class.php';
require_once '../../includes/session.php';

//spasi dispatch
if (isset($_POST['action']) && $_POST['action']=='saveDispatch'){
    $hData = new HourlyData($_SESSION['case']);
    try{
        $dispatch = $_POST['sort'];
        $year = $_POST['year'];

        $chbDispatch = $_POST['chbDispatch'];
        $import = $hData->elImportMW;

        //echo  "chb dispatch " .$chbDispatch. "<br>";
        $urlgenData = USER_CASE_PATH.$_SESSION['case'].'/hSimulation/genData.json';
        $contentgenData = file_get_contents( $urlgenData );
        $genData = json_decode($contentgenData, true);

        if($chbDispatch == "true"){
            foreach($hData->yrs as $y){
                if($import[$y] < 0){
                    //postavi Export na prvo mjesto
                    // $i = $dispatch['ImportExport'];
                    // unset( $dispatch['ImportExport']);
                    $dispatch = array_diff( $dispatch, ['ImportExport'] );
                    array_unshift($dispatch, "ImportExport");
                    //postavi Storage na zadnje
                    // $s = $dispatch['Storage'];
                    // unset( $dispatch['Storage']);
                    // array_push($dispatch, $s);
                    $genData['DISPATCH'][$y] = array_values($dispatch);
                }
                else if($import[$y] == 0){
                    //postavi Export na zadnje mjesto
                    // $i = $dispatch['ImportExport'];
                    // unset( $dispatch['ImportExport']);
                    $dispatch = array_diff( $dispatch, ['ImportExport'] );
                    array_push($dispatch, "ImportExport");
                    //postavi Storage na zadnje
                    // $s = $dispatch['Storage'];
                    // unset( $dispatch['Storage']);
                    $dispatch = array_diff( $dispatch, ['Storage'] );
                    array_push($dispatch, "Storage");
                    $genData['DISPATCH'][$y] = array_values($dispatch);
                }
                else if($import[$y] > 0){
                    //postavi Storage na zadnje
                    $dispatch = array_diff( $dispatch, ['Storage'] );
                    array_push($dispatch, "Storage");
                    $genData['DISPATCH'][$y] = array_values($dispatch);
                }
                
            }
        }else{
            $genData['DISPATCH'][$year] = $dispatch;
        }
        

        $fp = fopen(USER_CASE_PATH.$_SESSION['case'].'/hSimulation/genData.json', 'w');
       	fwrite($fp, json_encode($genData));
       	fclose($fp);
        echo json_encode(array('msg'=>"Dispatch order has been changed!", "type"=>"SUCCESS"));
        die();
    }
    catch(runtimeException $e){
		echo json_encode(array('msg'=>$e->getMessage(), "type"=>"ERROR"));
        die();
	}
}
//save MOR FOR
if (isset($_POST['action']) && $_POST['action']=='saveFORMOR'){
    try{
        $urlgenData = USER_CASE_PATH.$_SESSION['case'].'/hSimulation/genData.json';
        $contentgenData = file_get_contents( $urlgenData );
        $genData = json_decode($contentgenData, true);

        $DATA = json_decode($_POST['data'], true);

        $genData['MUS'] = $DATA['MUS'];
        $genData['MD'] = $DATA['MD'];
        $genData['FOR'] = $DATA['FOR'];

        $fp = fopen(USER_CASE_PATH.$_SESSION['case'].'/hSimulation/genData.json', 'w');
        fwrite($fp, json_encode($genData));
        fclose($fp);

        echo json_encode(array('msg'=>"Data for FOR and MOR is updated!", "type"=>"SUCCESS"));
        die();
    }
    catch(runtimeException $e){
		echo json_encode(array('msg'=>$e->getMessage(), "type"=>"ERROR"));
        die();
	}
}
//save MOR FOR
if (isset($_POST['action']) && $_POST['action']=='saveSTG'){
    try{
        $urlgenData = USER_CASE_PATH.$_SESSION['case'].'/hSimulation/genData.json';
        $contentgenData = file_get_contents( $urlgenData );
        $genData = json_decode($contentgenData, true);

        $DATA = json_decode($_POST['data'], true);

        $genData['STG'] = $DATA;

        $fp = fopen(USER_CASE_PATH.$_SESSION['case'].'/hSimulation/genData.json', 'w');
        fwrite($fp, json_encode($genData));
        fclose($fp);

        echo json_encode(array('msg'=>"Storge parameters are updated!", "type"=>"SUCCESS"));
        die();
    }
    catch(runtimeException $e){
		echo json_encode(array('msg'=>$e->getMessage(), "type"=>"ERROR"));
        die();
	}
}
//save ETA
if (isset($_POST['action']) && $_POST['action']=='saveEta'){
    try{
        //$urlETA = fopen(USER_CASE_PATH.$_SESSION['case'].'/hSimulation/ETA.json', 'w');
        $urlETA = USER_CASE_PATH.$_SESSION['case'].'/hSimulation/ETA.json';
        $contentETA = file_get_contents( $urlETA );
        $ETA = json_decode($contentETA, true);

        $year = $_POST['year'];
        $data = json_decode($_POST['data']);

        $ETA[$year] = $data; 

        file_put_contents($urlETA, json_encode($ETA));
        
        // $fp = fopen(USER_CASE_PATH.$_SESSION['case'].'/hSimulation/ETA.json', 'w');
        // fwrite($fp, json_encode($ETA, JSON_PRETTY_PRINT));
        // fclose($fp);

        echo json_encode(array('msg'=>"Efficiency of technology has been changed!", "type"=>"SUCCESS"));
        die();
    }
    catch(runtimeException $e){
		echo json_encode(array('msg'=>$e->getMessage(), "type"=>"ERROR"));
        die();
	}
}
//save HData
if (isset($_POST['action']) && $_POST['action']=='saveHData'){
    try{
        $urlHpattern = USER_CASE_PATH.$_SESSION['case'].'/hSimulation/hPattern.json';
        $contentHpattern = file_get_contents( $urlHpattern );
        $hPattern = json_decode($contentHpattern, true);

        $year = $_POST['year'];
        $allYears = $_POST['allYears'];
        $yearsArr = $_POST['yearsArr'];
        $data = json_decode($_POST['data']);

        if($allYears=="true"){
            foreach($yearsArr as $yr){
                $hPattern[$yr] = $data;
            }
        }else{
            $hPattern[$year] = $data;
        }
 
        initHData($hPattern);

        file_put_contents($urlHpattern, json_encode($hPattern));

        echo json_encode(array('msg'=>"Hourly data pattern has been changed!", "type"=>"SUCCESS"));
        die();
    }
    catch(runtimeException $e){
		echo json_encode(array('msg'=>$e->getMessage(), "type"=>"ERROR"));
        die();
	}
}

if(isset($_POST['action']) && $_POST['action'] == 'Calculate'){    
    try{
        // $validity = checkPhase1Validity();
        // //print_r($validity);
        // if($validity['Valid']){
            $hSimulation = USER_CASE_PATH.$_SESSION['case']."/hSimulation";          
            //uslov da su patterni uplodoavni jer init funkcija kreira HGp.json file
            if (file_exists($hSimulation.'/HGp.json')){ 
                $hSimulation = new Calculate($_SESSION['case']);
                $pStorage = $_POST['storage'] === 'true'? true: false;
                $pMaintenence = $_POST['maintenance'] === 'true'? true: false;
                $hSimulation->Calculate($pMaintenence, $pStorage);
                echo json_encode(array('msg'=>"Simulation success!", "type"=>"SUCCESS"));
                die();
            }
            else{
                echo json_encode(array('msg'=>"You have to input hourly patterns in order to run simulation!", "type"=>"WARNING"));
                die();
                 //throw new Exception("You have to upload Hourly Data Paterns first!");
            }
        // }else{
        //     $msg = " ";
        //     foreach($validity as $v=>$flag){
        //         if($flag){
        //             $msg = $v . ", ".$msg;
        //         }
        //     }
        //     echo json_encode(array('msg'=>$msg . "  value(s) is zero or missing in PHASE 1!", "type"=>"WARNING"));
        //     die();
        //     //throw new Exception("Some of the values in phase 1 are missing for ".$msg);
        // }

     }
    catch(Exception $e){
        echo json_encode(array('msg'=>$e->getMessage(), "type"=>"ERROR"));
        die();
    }
}
//prilagodin CF u fazi 1 sa onim sto smo zadali kroz hourly pattern  
if(isset($_POST['action']) && $_POST['action'] == 'AdjustCF'){    
    try{
        $hSimulation = USER_CASE_PATH.$_SESSION['case']."/hSimulation";
        if (file_exists($hSimulation.'/HGp.json')){
            $hData = new HourlyData($_SESSION['case']);       
            $hData->adjustCF();
            // $hSimulation = new Calculate($_SESSION['case']);
            // $pStorage = $_POST['storage'] === 'true'? true: false;
            // $pMaintenence = $_POST['maintenance'] === 'true'? true: false;
            // $hSimulation->Calculate($pMaintenence, $pStorage);
            echo json_encode(array('msg'=>"You have succesfully adjusted capacity factor of intermittent technologies according to hourly pattern!", "type"=>"SUCCESS"));
            die();
        }
        else{
             throw new Exception("You have to upload Hourly Data Paterns first!");
        }
    }
    catch(Exception $e){
        echo json_encode(array('msg'=>$e->getMessage(), "type"=>"ERROR"));
        die();
    }
} 
if(isset($_POST['action']) && $_POST['action'] == 'CheckInputPhase1'){    
    try{
        $validity = checkPhase1Validity();
        if($validity['Valid']){
            echo json_encode(array('msg'=>"Phase 1 inputs are valid!", "type"=>"SUCCESS"));
            die();
        }else{
            $msg = " ";
            foreach($validity as $v=>$flag){
                if($flag){
                    $msg = $v . ", ".$msg;
                }
            }
            echo json_encode(array('msg'=>$msg . "  value(s) is zero or missing in PHASE 1!</div>", "type"=>"WARNING"));
            die();
            //throw new Exception("Some of the values in phase 1 are missing for ".$msg);
        }

     }
    catch(Exception $e){
        echo json_encode(array('msg'=>$e->getMessage(), "type"=>"ERROR"));
        die();
    }
}
//iyracun ya LCOE
if(isset($_GET['action']) && $_GET['action'] == 'getCO2'){    
    try{
        $hSimulation = USER_CASE_PATH.$_SESSION['case']."/hSimulation";
        $emission = new Emission($_SESSION['case']);       
        $lcoe = $emission->getElGenEmissions('phase2');
        echo json_encode( $lcoe);
        die();
    }
    catch(Exception $e){
        echo json_encode(array('msg'=>$e->getMessage(), "type"=>"ERROR"));
        die();
    }
} 
//grid za hPattern sati u redovima generise prazan grid ako nema hPattern.json file 
//potrebno napraviti rutinu koja se pokrece nakon punjanja patterna ili promjene vrijednosti
#poziva se prilikom otvaranje HData modala i ucitaval default vrijednosti ukoliko nema hPattern.json
if(isset($_POST['action']) && $_POST['action'] == 'getHPattern'){    
    try{
        $hSimulation = USER_CASE_PATH.$_SESSION['case']."/hSimulation";
        $urlHData = $hSimulation."/hPattern.json";

        if (file_exists($urlHData)) {
            $contentHData = file_get_contents( $urlHData );
            $hPattern = json_decode($contentHData, true);
        }else{
            if(!file_exists($hSimulation)) { mkdir($hSimulation, 0777, true); }

            $esstCase = new EsstCaseClass($_SESSION['case']);
            $years = $esstCase->getYears(); 

            foreach($years as $year){
                for($i=0; $i<8760; $i++){
                    $hPattern[$year][$i]['Hour'] = 'H'.$i;
                    $hPattern[$year][$i]['Solar'] = 1;
                    $hPattern[$year][$i]['Wind'] = 1;
                    $hPattern[$year][$i]['Hydro'] = 1;
                    $hPattern[$year][$i]['Demand'] = 1;
                } 
            }
        }
            //hPattern
        $fp = fopen($urlHData, 'w');
        fwrite($fp, json_encode($hPattern));
        fclose($fp);
        echo json_encode($hPattern);
    }
    catch (exception $ex){
        echo json_encode(array('msg'=>$ex->getMessage(), "type"=>"ERROR"));
    }
}
//sinhronizuje sa fazom jedan
//potomo pokrece hPattern clasu da bi automatski pripremio patterne za kalculaciju
//normalizuje krivulje i racuna HDp i HGp za eventualne nove godine tehnologije ili promjenjene kapacitete
if(isset($_POST['action']) && $_POST['action'] == 'sync'){   
    try{
        $esstCase = new LCOE($_SESSION['case']);
        $years = $esstCase->getYears(); 
        $dispatch = $esstCase->getTechs();
        $hSimulation = USER_CASE_PATH.$_SESSION['case']."/hSimulation";



        //potrebno je updatovadi HM file jer smo mozda dodali tehnologiju ili godinu pa da popunimo vrijednosti sa 0
        // //generisi podatke za MD.json
        foreach($years as $year){
            foreach($dispatch as $tech){
                if (!in_array($tech, Constant::HourlyAnalysisTech)  && $tech!='ImportExport') {
                    $HM[$year][$tech] = array_fill(0,8760,0);
                }  
            }
        }
        $fp = fopen($hSimulation.'/HM.json', 'w');
        fwrite($fp, json_encode($HM,JSON_FORCE_OBJECT));
        fclose($fp);




        //potrebno je updateovati hPattern ukoliko so dodali neku tehnologijuod H, W, S

        $urlHData = $hSimulation."/hPattern.json";
        $contentHData = file_get_contents( $urlHData );
        $hPattern = json_decode($contentHData, true);

        //ukoliko smo promijenili year ili tech moremo promijeniti i hPattern ali da sadrzimo stare podatke iz hPatterna
        //Pripremamo podatke za hPattern Classu
        $hPatternChunk = $dispatch;
        array_push($hPatternChunk, "Demand");

        if (in_array('ImportExport', $hPatternChunk)) {
            unset($hPatternChunk[array_search('ImportExport',$hPatternChunk)]);
        }
        foreach($hPatternChunk as $tech){
            if (in_array($tech, Constant::HourlyAnalysisTech) || $tech =='Demand') {
                foreach($years as $year){
                    for($i=0; $i<8760; $i++){
                        if(isset( $hPattern[$year][$i][$tech])){
                            $hData[$tech][$year][$i] =  $hPattern[$year][$i][$tech];
                        }
                        else{
                            $hData[$tech][$year][$i] = 1;
                        }
                        
                    } 
                }
            }
        }


        //update hPatterna 
        foreach($years as $year){
            for($i=0; $i<8760; $i++){
                $hPattern[$year][$i]['Hour'] = 'H'.$i;
                foreach($hPatternChunk as $tech){
                    if (in_array($tech, Constant::HourlyAnalysisTech) || $tech =='Demand') {
                        if(isset( $hPattern[$year][$i][$tech])){
                            $hPattern[$year][$i][$tech]  =  $hPattern[$year][$i][$tech];
                        }
                        else{
                            $hPattern[$year][$i][$tech] = 1;
                        }
                    }
                }
            } 
        }
        $fp = fopen($urlHData, 'w');
        fwrite($fp, json_encode($hPattern));
        fclose($fp);


        $hPatternData = new hPattern($_SESSION['case'],$hData['Demand'], $hData['Solar'], $hData['Wind'], $hData['Hydro'], $years, $dispatch);
        $HDp = $hPatternData->getHDp();
        $HGp = $hPatternData->getHGp();

        $fp = fopen($hSimulation.'/HDp.json', 'w');
        fwrite($fp, json_encode($HDp));
        fclose($fp);
        $fp = fopen($hSimulation.'/HGp.json', 'w');
        fwrite($fp, json_encode($HGp));
        fclose($fp);

        $PMAX = $hPatternData->getPMAX();
        $LF = $hPatternData->getLF();
        $CFp = $hPatternData->getCFp();

        //PODACI IZ FAZE 1
        $CF = $hPatternData->getCF_yt();
        $EDgwh = $hPatternData->getElD_y_gwh();
        $elImportMW = $hPatternData->getElImport_y_mw();
        $TIC = $hPatternData->getTIC_yt();
        $TDC = $hPatternData->getTDC_y();
        $MCoT = $esstCase->getMCoT_yt();

                #dio za Emission i LCOe Class
                $Unit = $esstCase->getUnit();
                $Sectors = $esstCase->getSectors();
                $TPES = $esstCase->getTPES_yt();
                $TFC = $esstCase->getTFC_ysc();
                $ELMIX = $esstCase->getSESElMix_yt();
                $PEEG = $esstCase->getPEEG_yt();
                $CarbonCost = $esstCase->getCC_y();
                $Eff = $esstCase->getEff_yt();
                $EF = $esstCase->getEnv_yt();
                $LT = $esstCase->getLt_t();
                $DR = $esstCase->getDR_y();
                $Cost = $esstCase->getCost_yt();
                $TAC = $esstCase->getTAC_d_yt();

        $urlHData = $hSimulation."/genData.json";
        $contentHData = file_get_contents( $urlHData );
        $genData = json_decode($contentHData, true);


        $genData['UNIT'] = $Unit;
        $genData['YEARS'] = $years;
        $genData['Sectors'] = $Sectors;
        $genData['PMAX'] = $PMAX;
        $genData['LF'] = $LF;
        $genData['CF'] = $CF;
        $genData['CFp'] = $CFp;
        $genData['EDgwh'] = $EDgwh;
        $genData['elImportMW'] = $elImportMW;
        $genData['TIC'] = $TIC;
        $genData['TDC'] = $TDC;
        $genData['TPES'] = $TPES;
        $genData['TFC'] = $TFC;
        $genData['ELMIX'] = $ELMIX;
        $genData['PEEG'] = $PEEG;
        $genData['CarbonCost'] = $CarbonCost;
        $genData['Eff'] = $Eff;
        $genData['EF'] = $EF;
        $genData['LT'] = $LT;
        $genData['DR'] = $DR;
        $genData['Cost'] = $Cost;
        $genData['TAC'] = $TAC;
        $genData['MCoT'] = $MCoT;

        foreach($dispatch as $tech){
            if (!in_array($tech, Constant::HourlyAnalysisTech) && $tech != 'ImportExport'){
                if(isset($genData['MD'][$tech])){
                    $MD[$tech] = $genData['MD'][$tech];
                }else{
                    $MD[$tech] = Constant::MTD[$tech];
                }
                if(isset($genData['MUS'][$tech])){
                    $MUS[$tech] = $genData['MUS'][$tech];
                }else{
                    $MUS[$tech] = Constant::MTS[$tech];
                }
                if(isset($genData['FOR'][$tech])){
                    $FOR[$tech] = $genData['FOR'][$tech];
                }else{
                    $FOR[$tech] = 0;
                }
                // $MD[$tech] = Constant::MTD[$tech];
                // $MUS[$tech] = Constant::MTS[$tech];
                // $FOR[$tech] = 0;
            }
        }
        $genData['MD'] = $MD;
        $genData['MUS'] = $MUS;
        $genData['FOR'] = $FOR;
        //ETA
        foreach($years as $year){
            $i = 0;
            $disp[$year] = $dispatch;

          
            if(isset($genData['STG'][$year])){
                $STG[$year] = $genData['STG'][$year];
            }else{
                $STG[$year] = Constant::STG;
            }

            array_push($disp[$year], "Storage");
            foreach(Constant::EtaTechs as $tech=>$value){
                $ETA[$year][$i][$tech] = 0; 
                $ETA[$year][$i]['ETA1'] = $value;
                $ETA[$year][$i]['ETA2'] = 0;
                $ETA[$year][$i]['Pmin'] = 0;
                $i++;
            }
        }
        $genData['DISPATCH'] = $disp;
        $genData['ETA'] = $ETA;
        $genData['STG'] = $STG;

        $fp = fopen($hSimulation.'/genData.json', 'w');
        // fwrite($fp, json_encode($genData, JSON_PRETTY_PRINT));
        fwrite($fp, json_encode($genData));
        fclose($fp);



        echo json_encode(array('msg'=>"Syncronization success!", "type"=>"SUCCESS"));
        die();

    }
    catch (exception $ex){
        throw new Exception($ex);
    }
}

#################################################Functions

#poziva se nakon spasavanje HData, potrebo je od hPatterna napraviti normalizovane vrijednosti
function initHData($hPattern){
    try{
        $hSimulation = USER_CASE_PATH.$_SESSION['case']."/hSimulation";

        $esstCase = new LCOE($_SESSION['case']);
        $years = $esstCase->getYears(); 
        $dispatch = $esstCase->getTechs();

        foreach($hPattern as $yr=>$obj1){
            foreach($obj1 as $hr=>$obj2){
                foreach($obj2 as $tech=>$value){
                    if($tech != 'Hour'){
                        $hData[$tech][$yr][$hr] = $value;
                    }
                }
            }
        }    


        //pokreni hPattern klasu za normalizovanje paterna i izracun HDp, HGp, PMAX, LF, CFp
        $hPatternData = new hPattern($_SESSION['case'],$hData['Demand'], $hData['Solar'], $hData['Wind'], $hData['Hydro'], $years, $dispatch);

        $HDp = $hPatternData->getHDp();
        $HGp = $hPatternData->getHGp();

        $fp = fopen($hSimulation.'/HDp.json', 'w');
        fwrite($fp, json_encode($HDp));
        fclose($fp);
        $fp = fopen($hSimulation.'/HGp.json', 'w');
        fwrite($fp, json_encode($HGp));
        fclose($fp);

        //////////////////////////////////////////////////////////////////////////Maintenance INIT data
        // //generisi podatke za MD.json
        if( !file_exists($hSimulation.'/HM.json') ){
            foreach($years as $year){
                foreach($dispatch as $tech){
                    if (!in_array($tech, Constant::HourlyAnalysisTech) && $tech!='ImportExport') {
                        $HM[$year][$tech] = array_fill(0,8760,0);
                    }  
                }
            }
            $fp = fopen($hSimulation.'/HM.json', 'w');
            fwrite($fp, json_encode($HM,JSON_FORCE_OBJECT ));
            fclose($fp);
        }

        ///////////////////////////////////////////////genData part PMAX, LF, CFp su varijale koje zavise od patterna i promjene 
        $PMAX = $hPatternData->getPMAX();
        $LF = $hPatternData->getLF();
        $CFp = $hPatternData->getCFp();


        //Ako ne postoji hData file sve moremo napraviti ako postoji potrebno je updateovati smao ono sto dolazi iz paterna a to je PMAX, LF, CF
        if( !file_exists($hSimulation.'/genData.json') ){
            $EDgwh = $hPatternData->getElD_y_gwh();
            $elImportMW = $hPatternData->getElImport_y_mw();
            $TIC = $hPatternData->getTIC_yt();
            $TDC = $hPatternData->getTDC_y();
            $CF = $hPatternData->getCF_yt();

            #dio za Emission i LCOe Class
            $Unit = $esstCase->getUnit();
            $Sectors = $esstCase->getSectors();
            $TPES = $esstCase->getTPES_yt();
            $TFC = $esstCase->getTFC_ysc();
            $ELMIX = $esstCase->getSESElMix_yt();

            $PEEG = $esstCase->getPEEG_yt();
            $CarbonCost = $esstCase->getCC_y();
            $Eff = $esstCase->getEff_yt();
            $EF = $esstCase->getEnv_yt();
            $LT = $esstCase->getLt_t();
            $DR = $esstCase->getDR_y();
            $Cost = $esstCase->getCost_yt();
            $TAC = $esstCase->getTAC_d_yt();

            $MCoT = $esstCase->getMCoT_yt();

            $genData['UNIT'] = $Unit;
            $genData['Sectors'] = $Sectors;
            $genData['YEARS'] = $years;
            $genData['PMAX'] = $PMAX;
            $genData['LF'] = $LF;
            $genData['CF'] = $CF;
            $genData['CFp'] = $CFp;
            $genData['EDgwh'] = $EDgwh;
            $genData['elImportMW'] = $elImportMW;
            $genData['TIC'] = $TIC;
            $genData['TDC'] = $TDC;
            $genData['TPES'] = $TPES;
            $genData['TFC'] = $TFC;
            $genData['ELMIX'] = $ELMIX;
            $genData['PEEG'] = $PEEG;
            $genData['CarbonCost'] = $CarbonCost;
            $genData['Eff'] = $Eff;
            $genData['EF'] = $EF;
            $genData['LT'] = $LT;
            $genData['DR'] = $DR;
            $genData['Cost'] = $Cost;
            $genData['TAC'] = $TAC;
            $genData['MCoT'] = $MCoT;



            foreach($dispatch as $tech){
                if (!in_array($tech, Constant::HourlyAnalysisTech) && $tech != 'ImportExport'){
                    $genData['MD'][$tech] = Constant::MTD[$tech];
                    $genData['MUS'][$tech] = Constant::MTS[$tech];
                    $genData['FOR'][$tech] = 0;
                }
            }
            //Maintenance duration
            //$genData['MD'] = array("Coal"=>4,"Gas"=>2,"Oil"=>2,"Biofuels"=>2,"Waste"=>2,"Nuclear"=>4 );  
            //Maintenenac e unit size
            //$genData['MUS'] = array("Nuclear"=>1000,"Coal"=>400,"Gas"=>300,"Oil"=>200,"OilShale"=>200,"Peat"=>200,"Waste"=>100,"Biofuels"=>100,"Geothermal"=>100 );
            
            //ETA
            foreach($years as $year){
                $i = 0;
                $genData['DISPATCH'][$year] = $dispatch;
                $STG[$year] = Constant::STG;
                array_push($genData['DISPATCH'][$year], "Storage");
                foreach(Constant::EtaTechs as $tech=>$value){
                    $ETA[$year][$i][$tech] = 0; 
                    $ETA[$year][$i]['ETA1'] = $value;
                    $ETA[$year][$i]['ETA2'] = 0;
                    $ETA[$year][$i]['Pmin'] = 0;
                    $i++;
                }
            }
            $genData['ETA'] = $ETA;
            $genData['STG'] = $STG;
        }else{
            $urlHData = $hSimulation."/genData.json";
            $contentHData = file_get_contents( $urlHData );
            $genData = json_decode($contentHData, true);

            //prilikom izmjeneHData nije potrebno ponovo puniti godine, dispatch, CF, EDgwh, elImportMW, TIC, TDC
            //ovi podaci dolaze iskljucivo iz faze 1. Ove podatke je neophodno sihnronizovati ako je doslo do promjene u podacima u fazi 1
            //za tu svrhu nam koristi opcija SYNC
            // $genData['YEARS'] = $years;
            // foreach($years as $year){
            //     $genData['DISPATCH'][$year] = $dispatch;
            //     array_push($genData['DISPATCH'][$year], "Storage");
            // }
            $genData['PMAX'] = $PMAX;
            $genData['LF'] = $LF;
            //$genData['CF'] = $CF;
            $genData['CFp'] = $CFp;
            //$genData['EDgwh'] = $EDgwh;
            //$genData['elImportMW'] = $elImportMW;
            //$genData['TIC'] = $TIC;
            //$genData['TDC'] = $TDC;
        }

        $fp = fopen($hSimulation.'/genData.json', 'w');
        // fwrite($fp, json_encode($genData, JSON_PRETTY_PRINT));
        fwrite($fp, json_encode($genData));
        fclose($fp);
    }
    catch (exception $ex){
        throw $ex;
    }
}

function checkPhase1Validity(){
    $esstCase = new EsstCaseClass($_SESSION['case']);
    $years = $esstCase->getYears(); 
    $techs = $esstCase->getTechs(); 
    $validation = array(
        "Valid"=>true, 
        "Lifetime"=>false,
        "Discount rate"=>false,
        "Carbon cost"=>false, 
        "Efficiency"=>false, 
        "Capacity factor"=>false, 
        "ECONOMIC PARAMETERS [Fuel cost, Investment cost, Operating cost fixed, Operating cost variable] "=>false,
        "ENVIRONMENT PARAMETERS [CO2, SO2, NOX, Other] " => false
    );

    $LT = $esstCase->getLt_t();
    $dr = $esstCase->getDR_y();
    $CC = $esstCase->getCC_y();
    $Eff = $esstCase->getEff_yt();
    $CF = $esstCase->getCF_yt();

    $ENV = $esstCase->getEnv_yt(); 
    $FIN = $esstCase->getCost_yt();

    // $FED = $esstCase->getFED_ys();
    // $FEDShare = $esstCase->getFEDShare_ysc();
    // $FEDLosses = $esstCase->getFEDLosses_yc();
    // $SESElMix = $esstCase->getSESElMix_yt();

    //if (in_array(0, $LT) || in_array('', $LT)) {$validation['Valid'] = false;$validation['Lifetime'] = true;}
    if (in_array(0, $dr) || in_array('', $dr)) {$validation['Valid'] = false;$validation['Discount rate'] = true;}
    if (in_array(0, $CC) || in_array('', $CC)) {$validation['Valid'] = false;$validation['Carbon cost'] = true;}

    foreach($years as $yr){
        if (in_array(0, $Eff[$yr]) || in_array('', $Eff[$yr])) {
            $validation['Valid'] = false;
            $validation['Efficiency'] = true;
        }
        if (in_array(0, $CF[$yr]) || in_array('', $CF[$yr])) {
            $validation['Valid'] = false;
            $validation['Capacity factor'] = true;
        }
        foreach($techs as $tech){
            if (in_array(0, $LT[$yr][$tech]) || in_array('', $LT[$yr][$tech])) {
                $validation['Valid'] = false;
                $validation[ "Lifetime"] = true;
            }
        }
        foreach($techs as $tech){
            if ( in_array('', $FIN[$yr][$tech])) {
                $validation['Valid'] = false;
                $validation[ "FINANCE PARAMETERS [Fuel cost, Investment cost, Operating cost fixed, Operating cost variable] "] = true;
            }
        }
        foreach($techs as $tech){
            if (in_array('', $ENV[$yr][$tech])) {
                $validation['Valid'] = false;
                $validation[ "ENVIRONMENT PARAMETERS [CO2, SO2, NOX, Other] "] = true;
            }
        }
        // if (in_array(0, $SESElMix[$yr])) {
        //     $validation['Valid'] = false;
        //     $validation['Electricity_mix'] = true;
        // }
    }
    return $validation;    
}




//////////////////////////////////////////////OPSOLETE//////////////////////////////////////

//duzina trajanja odrzavanja 2 ili 4 sedmice
// if (isset($_POST['action']) && $_POST['action']=='saveMaintenanceDuration'){
//     try{
//         $urlgenData = USER_CASE_PATH.$_SESSION['case'].'/hSimulation/genData.json';
//         $contentgenData = file_get_contents( $urlgenData );
//         $genData = json_decode($contentgenData, true);

//         $genData['MD'] = $_POST['duration'];

//         $fp = fopen(USER_CASE_PATH.$_SESSION['case'].'/hSimulation/genData.json', 'w');
//         fwrite($fp, json_encode($genData, JSON_PRETTY_PRINT));
//         fclose($fp);

//         echo json_encode(array('msg'=>"Maintenance duration has been changed!", "type"=>"SUCCESS"));
//         die();
//     }
//     catch(runtimeException $e){
// 		echo json_encode(array('msg'=>$e->getMessage(), "type"=>"ERROR"));
//         die();
// 	}
// }
// //velicinia unit size za odrzavanje
// if (isset($_POST['action']) && $_POST['action']=='saveMaintenanceUnitSize'){
//     try{
//         $urlgenData = USER_CASE_PATH.$_SESSION['case'].'/hSimulation/genData.json';
//         $contentgenData = file_get_contents( $urlgenData );
//         $genData = json_decode($contentgenData, true);

//         $genData['MUS'] = $_POST['unitSize'];

//         $fp = fopen(USER_CASE_PATH.$_SESSION['case'].'/hSimulation/genData.json', 'w');
//         fwrite($fp, json_encode($genData, JSON_PRETTY_PRINT));
//         fclose($fp);

//         echo json_encode(array('msg'=>"Maintenance unit sizes have been changed!", "type"=>"SUCCESS"));
//         die();
//     }
//     catch(runtimeException $e){
// 		echo json_encode(array('msg'=>$e->getMessage(), "type"=>"ERROR"));
//         die();
// 	}
// }

//vratei el mix goriva
// if (isset($_POST['action']) && $_POST['action']=='getElMixFuels'){
//     $esstCase = new EsstCase($_POST['case']);
//     $techs = $esstCase->getTechs();
//     foreach($techs as $tech){
//         if (!in_array($tech, $esstCase->HourlyAnalysisTech)) {
//             $fuels[]['fuel'] = $tech;
//         }
//     }
//     echo json_encode($fuels);
// }
// //vraca godine
// if (isset($_POST['action']) && $_POST['action']=='getYears'){
//     $esstCase = new EsstCase($_POST['case']);
//     $years = $esstCase->getYrs();
//     foreach($years as $year){
//         $yrs[]['year'] = $year;
//     }
//     echo json_encode($yrs);
// }
// //vraca cases
// if (isset($_POST['action']) && $_POST['action']=='getCases'){
//     $cases = array();
//     $directories = glob(USER_CASE_PATH."*" , GLOB_ONLYDIR);
//     foreach($directories as $folder){
//         if ($folder != '.' && $folder != '..'){
//            $cases[]['case'] =  basename($folder);
//         }
//     }
//     echo json_encode($cases, JSON_PRETTY_PRINT);
// }

//zadovolji UD sa nekim od tehnologija
// if(isset($_POST['action']) && $_POST['action'] == 'ENSfromFuel'){    
//     try{
//         $hSimulation = USER_CASE_PATH.$_SESSION['case']."/hSimulation";          
//         if (file_exists($hSimulation.'/HDPcp.json')&&file_exists($hSimulation.'/HDP.json')){                
//             $hData = new HourlyData($_SESSION['case']);
//             $EnsCap = $hData->getEnsCapacity();
//             $filepath = USER_CASE_PATH.$hData->pCase."/".$hData->pCase.".xml";
//             $UD = $hData->getUD();
//             foreach($hData->yrs as $year){ 
//                 $newEnsCap = $UD[$year]['MaxUD'] + $EnsCap[$year][$_POST['fuel']];
//                 if($UD[$year]['MaxUD'] != 0){
//                       appent_ens_capacity($filepath, $year, $_POST['fuel'], $newEnsCap);
//                 }
//             }
//             //sa prebacivanjem ENS capaciteta iz prethodne godine
//             // $i = 0;
//             // foreach($hData->yrs as $year){ 
//             //     if($i == 0){
//             //         $newEnsCap[$i] = $UD[$year]['MaxUD'] + $EnsCap[$year][$_POST['fuel']];
//             //     }else{
//             //         $newEnsCap[$i] = ($UD[$year]['MaxUD'] - $newEnsCap[$i - 1]) + $EnsCap[$year][$_POST['fuel']];
//             //     }
//             //     if($UD[$year]['MaxUD'] != 0){
//             //           appent_ens_capacity($filepath, $year, $_POST['fuel'], $newEnsCap[$i]);
//             //     }
//             //     $i++;
//             // }

//             $hData1 = new HourlyData($_SESSION['case']);
//             $hData1->calculateWithMaintenance();
//             echo json_encode(array('msg'=>"You have succesfully incresed capacity to serve unserved demand!", "type"=>"SUCCESS"));
//             die();
//         }
//         else{
//              throw new Exception("You have to upload Hourly Data Paterns first!");
//         }
//      }
//     catch(Exception $e){
//         echo json_encode(array('msg'=>$e->getMessage(), "type"=>"ERROR"));
//         die();
//     }
// }
//upload xls i popunjajvanje HD patterna
// if(isset($_POST['action']) && $_POST['action'] == 'HourlyData'){    
//     try{
//         //print_r($_POST);
//         $hSimulation = USER_CASE_PATH.$_SESSION['case']."/hSimulation";
//         $xlsx = new SimpleXLSX($hSimulation."/".$_POST["file"]);
//         $data = $xlsx->rowsHDSW();
//         $years = $xlsx->rowsY();
//         $techs = $xlsx->rowsT();             
        
//         foreach($data as $key=>$value) {
//             foreach($value as $g=>$v){   
//                  $dataCP[$key][$g]['HourlyData'] = array_map(function($i, $j){ $eoutput = array('hour' => 'H'.$i,'value' => $j,);return $eoutput;},array_keys($v), array_values($v));
//             } 
//         } 
//        $esstCase = new EsstCase($_SESSION['case']);
//         //$dispatch = array("Solar", "Hydro","Wind"); 

       
//         $v = $esstCase->getElMixFuels();
//         $dispatch = array_map(function($i, $j){ if($j ==1 && $i!="ImportExport") return $i; }, array_keys($v), array_values($v));
//            //  		print_r($v);
//         //print_r(array_filter($dispatch));
//         //die();

//         $godineP1 = $esstCase->getYears();  
//         $godine = array();
//         foreach($godineP1  as $key=>$value){
//             $tmp = substr($key, 1);
//             if($value=="1"){
//                 $godine[] = $tmp;
//             }
//         }
        
//         foreach($godine as $year){
//             for($i=0; $i<8760; $i++){
//                 $hPattern[$year][$i]['Hour'] = 'H'.$i;
//                 $hPattern[$year][$i]['Solar'] = $data['Solar'][$year][$i+2];
//                 $hPattern[$year][$i]['Wind'] = $data['Wind'][$year][$i+2];
//                 $hPattern[$year][$i]['Hydro'] = $data['Hydro'][$year][$i+2];
//                 $hPattern[$year][$i]['Demand'] = $data['Demand'][$year][$i+2];
//             } 
//         }
    
//        //echo "<pre>";
//        //print_r($godine);
//        //echo "</pre>"; 
//         $diff = array_diff($godine,$years);
//         if (!empty($diff)) {
//             $mesage = '';
//             foreach($diff as $t){
//                 $mesage .= $t.', ';
//             }
//           throw new Exception($mesage." years missing in hourly patterns. Please add paterns for these years."); 
//         }
        
//         $diff = array_diff($years,$godine);
//         if (!empty($diff)) {
//             $mesage = '';
//             foreach($diff as $t){
//                 $mesage .= $t.', ';
//             }
//           throw new Exception($mesage." years missing in phase1. Please add years in  your case."); 
//         }

//         $diff = array_diff($techs, $dispatch);
//         if (!empty($diff)) {
//             $mesage = '';
//             foreach($diff as $t){
//                 $mesage .= $t.', ';
//             }
//           throw new Exception($mesage." technology missing in phase1. Please add technology or select another case."); 
//         }

//         foreach($godine as $year){
//             foreach($dispatch as $tech){
//                 if($tech != 'Solar' && $tech != 'Wind' && $tech != 'Hydro')
//                 $HM[$year][$tech] = array_fill(0,8760,0); 
//             }
//         }

//         //{"Coal":4,"Oil":2,"Gas":2,"Biofuels":2,"Waste":2,"Nuclear":4}
//         $md = array("Coal"=>4,"Gas"=>2,"Oil"=>2,"Biofuels"=>2,"Waste"=>2,"Nuclear"=>4 );

//        // $eff = $esstCase->getEfficiency();

//         if( !file_exists($hSimulation.'/HM.json') ){
//             foreach($years as $year){
//                 foreach($dispatch as $tech){
//                     if (!in_array($tech, $esstCase->HourlyAnalysisTech)) {
//                         $HM[$year][$tech] = array_fill(0,8760,0);
//                     }  
//                 }
//             }
//             //Hourly maintenace
//             $fp = fopen($hSimulation.'/HM.json', 'w');
//             fwrite($fp, json_encode($HM,JSON_FORCE_OBJECT | JSON_PRETTY_PRINT));
//             fclose($fp);
//         }

//         if( !file_exists($hSimulation.'/ETA.json')){
//             foreach($years as $year){
//                 $i = 0;
//                 foreach($esstCase->EtaTechs as $tech=>$value){
//                     $ETA[$year][$i][$tech] = 0; 
//                     $ETA[$year][$i]['ETA1'] = $value;
//                     $ETA[$year][$i]['ETA2'] = 0;
//                     $ETA[$year][$i]['Pmin'] = 0;
//                     $i++;
//                 }
//             }
//             //eta
//             $fp = fopen($hSimulation.'/ETA.json', 'w');
//             fwrite($fp, json_encode($ETA, JSON_PRETTY_PRINT));
//             fclose($fp);
//         }

//         if( !file_exists($hSimulation.'/MD.json') ){
//             $md = array("Coal"=>4,"Gas"=>2,"Oil"=>2,"Biofuels"=>2,"Waste"=>2,"Nuclear"=>4 ); 
//             //maintennace duration   
//             $fp = fopen($hSimulation.'/MD.json', 'w');
//             fwrite($fp, json_encode($md, JSON_PRETTY_PRINT));
//             fclose($fp);
//         }
    
//         if( !file_exists($hSimulation.'/dispatch.json') ){
//             //dispatch
//             $fp = fopen($hSimulation.'/dispatch.json', 'w');
//             fwrite($fp, json_encode($dispatch, JSON_PRETTY_PRINT));
//             fclose($fp);
//         }

//         $fp = fopen($hSimulation.'/hPattern.json', 'w');
//         fwrite($fp, json_encode($hPattern));
//         fclose($fp);

//         $fp = fopen($hSimulation.'/years.json', 'w');
//         fwrite($fp, json_encode($years, JSON_PRETTY_PRINT));
//         fclose($fp);

//         // $fp = fopen($hSimulation.'/data.json', 'w');
//         // fwrite($fp, json_encode($data, JSON_PRETTY_PRINT));
//         // fclose($fp);

//         // $fp = fopen($hSimulation.'/dataCP.json', 'w');
//         // fwrite($fp, json_encode($dataCP, JSON_PRETTY_PRINT));
//         // fclose($fp);
        
//         $fp = fopen($hSimulation.'/HDP.json', 'w');
//         fwrite($fp, json_encode($data['Demand'], JSON_PRETTY_PRINT));
//         fclose($fp);
        
//         $fp = fopen($hSimulation.'/HSP.json', 'w');
//         fwrite($fp, json_encode($data['Solar'], JSON_PRETTY_PRINT));
//         fclose($fp);
        
//            $fp = fopen($hSimulation.'/HWP.json', 'w');
//         fwrite($fp, json_encode($data['Wind'], JSON_PRETTY_PRINT));
//         fclose($fp);
        
//            $fp = fopen($hSimulation.'/HHP.json', 'w');
//         fwrite($fp, json_encode($data['Hydro'], JSON_PRETTY_PRINT));
//         fclose($fp);
        
//            $fp = fopen($hSimulation.'/HDPcp.json', 'w');
//         fwrite($fp, json_encode($dataCP['Demand'], JSON_PRETTY_PRINT));
//         fclose($fp);
        
//            $fp = fopen($hSimulation.'/HSPcp.json', 'w');
//         fwrite($fp, json_encode($dataCP['Solar'], JSON_PRETTY_PRINT));
//         fclose($fp);
        
//            $fp = fopen($hSimulation.'/HWPcp.json', 'w');
//         fwrite($fp, json_encode($dataCP['Wind'], JSON_PRETTY_PRINT));
//         fclose($fp);
        
//            $fp = fopen($hSimulation.'/HHPcp.json', 'w');
//         fwrite($fp, json_encode($dataCP['Hydro'], JSON_PRETTY_PRINT));
//         fclose($fp);

//         // $fp = fopen($hSimulation.'/HM.json', 'w');
//         // fwrite($fp, json_encode($HM,JSON_FORCE_OBJECT | JSON_PRETTY_PRINT));
//         // fclose($fp);

//         echo json_encode(array('msg'=>"You have succesfully uploaded Hourly Data patern: HDP, HSP, HWP!", "type"=>"SUCCESS"));
//     }
//     catch (exception $ex){
//         echo json_encode(array('msg'=>$ex->getMessage(), "type"=>"ERROR"));
//     }
// }
//simulacija sa odrzavanjem
// if(isset($_POST['action']) && $_POST['action'] == 'Maintenance'){    
//     try{
//         $hSimulation = USER_CASE_PATH.$_SESSION['case']."/hSimulation";
//         if (file_exists($hSimulation.'/HDPcp.json') && file_exists($hSimulation.'/HDP.json')){
//             $hData = new HourlyData($_SESSION['case']);
//             $hData->resetENSPower();
// 			$hData->calculateWithMaintenance();
//             echo json_encode(array('msg'=>"You have succesfully calculated Maintenance!", "type"=>"SUCCESS"));
//             die();
//         }
//         else{
//              throw new Exception("You have to upload Hourly Data Paterns first!");
//         }
//      }
//     catch(Exception $e){
//         echo json_encode(array('msg'=>$e->getMessage(), "type"=>"ERROR"));
//         die();
//     }
// }
//simulacija
// if(isset($_POST['action']) && $_POST['action'] == 'Calculate'){    
//     try{
//         $hSimulation = USER_CASE_PATH.$_SESSION['case']."/hSimulation";          
//         if (file_exists($hSimulation.'/HDPcp.json')&&file_exists($hSimulation.'/HDP.json')){ 

//             $hData = new HourlyData($_SESSION['case']);
//             $hData->resetENSPower();
//             //ocistitiENS kapacitet ukoliko ga ima u xml da bi mogli ponovo vrsiti simulaciju
//             $hData->saveToFiles();
//             echo json_encode(array('msg'=>"You have succesfully calculated RHD, RHSG, RHWG!", "type"=>"SUCCESS"));
//             die();
//         }
//         else{
//              throw new Exception("You have to upload Hourly Data Paterns first!");
//         }
//      }
//     catch(Exception $e){
//         echo json_encode(array('msg'=>$e->getMessage(), "type"=>"ERROR"));
//         die();
//     }
// }
?>
