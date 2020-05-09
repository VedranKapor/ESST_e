<?php
//require_once '../functions/xml_functions.php';
require_once '../config.php';
require_once '../classes/Emission.Class.php';
require_once '../includes/session.php';

$filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";

if (file_exists($filepath)){

   $Emission = new Emission($_SESSION['case']);   
//    $years = $Emission->eYears;
//    $techs = $Emission->eTechs;

   $years = $Emission->eYears;
   $techs = $Emission->eTechs;
// $years = $Emission->getYearsArray();
// $techs = $Emission->getTechsArray();
     
   $getElGenEmissions = $Emission->getElGenEmissions();
   $NOX = array();
   $CO2 = array();
   $SO2 = array();
   $PM = array();
   
   foreach($getElGenEmissions as $year=>$obj){
        $NOXtmp = array();
        $CO2tmp = array();
        $SO2tmp = array();
        $PMtmp = array();

        $NOXtmp['year'] = $year;
        $CO2tmp['year'] = $year;
        $SO2tmp['year'] = $year;
        $PMtmp['year'] = $year;
        foreach($obj as $tech=>$emission){
            if($tech!='ImportExport' && $tech!='Storage'){
                $NOXtmp[$tech] = $emission['NOX'];
                $CO2tmp[$tech] = $emission['CO2'];
                $SO2tmp[$tech] = $emission['SO2'];
                $PMtmp[$tech] = $emission['Other'];
            }
        }
        $NOX[] = $NOXtmp;
        $CO2[] = $CO2tmp; 
        $SO2[] = $SO2tmp; 
        $PM[]  = $PMtmp; 
    }

    $NOX_t = array();
    $CO2_t = array();
    $SO2_t = array();
    $PM_t = array();    

    foreach($techs as $tech){
        if($tech!='ImportExport' && $tech!='Storage'){
            $NOXtmp_t = array();
            $CO2tmp_t = array();
            $SO2tmp_t = array();
            $PMtmp_t = array();

            $NOXtmp_t['name'] = $tech;
            $CO2tmp_t['name'] = $tech;
            $SO2tmp_t['name'] = $tech;
            $PMtmp_t['name'] = $tech;
            foreach($years  as $year){
                $NOXtmp_t[$year] = $getElGenEmissions[$year][$tech]['NOX'];
                $CO2tmp_t[$year] = $getElGenEmissions[$year][$tech]['CO2'];
                $SO2tmp_t[$year] = $getElGenEmissions[$year][$tech]['SO2'];
                $PMtmp_t[$year] = $getElGenEmissions[$year][$tech]['Other'];
            }
            $NOX_t[] = $NOXtmp_t;
            $CO2_t[] = $CO2tmp_t; 
            $SO2_t[] = $SO2tmp_t; 
            $PM_t[]  = $PMtmp_t; 
        }
    }
   

//NOx emissions 
if (isset($_GET['action']) && $_GET['action']=="NOX"&& $_GET['trans']=='0') {  
    $arraye=array_filter($NOX);
	echo '{"data":'.json_encode(array_values($arraye)).'}';  
    die();
}

//Nox emissions trans
 if (isset($_GET['action']) && $_GET['action']=="NOX"  && isset($_GET['trans'])&& $_GET['trans']==1){  

    $arrayet=array_filter($NOX_t);
    //$array1 = array_filter(array_map('array_filter', $array1));
    echo '{"data":'.json_encode(array_values($arrayet)).'}';
    die();
 }
  
 //SO2 emission 
if (isset($_GET['action']) && $_GET['action']=="SO2"&& $_GET['trans']=='0') {  
    $arraye=array_filter($SO2);
    //$array1 = array_filter(array_map('array_filter', $array1));
	echo '{"data":'.json_encode(array_values($arraye)).'}';  
    die();
}

//СО2 emissions trans
 if (isset($_GET['action']) && $_GET['action']=="SO2"  && isset($_GET['trans'])&& $_GET['trans']==1){  

    $arrayet=array_filter($SO2_t);
    //$array1 = array_filter(array_map('array_filter', $array1));
    echo '{"data":'.json_encode(array_values($arrayet)).'}';
    die();
} 

//PM emisssions 
if (isset($_GET['action']) && $_GET['action']=="PM"&& $_GET['trans']=='0'){  
    $arraye=array_filter($PM);
    //$array1 = array_filter(array_map('array_filter', $array1));
	echo '{"data":'.json_encode(array_values($arraye)).'}';  
    die();
}

//PM emissions trans
 if (isset($_GET['action']) && $_GET['action']=="PM"  && isset($_GET['trans'])&& $_GET['trans']==1){  

    $arrayet=array_filter($PM_t);
    //$array1 = array_filter(array_map('array_filter', $array1));
    echo '{"data":'.json_encode(array_values($arrayet)).'}';
    die();
}   

//CO2 emissions 
if (isset($_GET['action']) && $_GET['action']=="CO2"&& $_GET['trans']=='0'){  
    $arraye=array_filter($CO2);
    //$array1 = array_filter(array_map('array_filter', $array1));
	echo '{"data":'.json_encode(array_values($arraye)).'}';  
    die();
}

//CO2 emissions trans
 if (isset($_GET['action']) && $_GET['action']=="CO2"  && isset($_GET['trans'])&& $_GET['trans']==1){  

    $arrayet=array_filter($CO2_t);
    //$array1 = array_filter(array_map('array_filter', $array1));
    echo '{"data":'.json_encode(array_values($arrayet)).'}';
    die();
}                
}
?>
