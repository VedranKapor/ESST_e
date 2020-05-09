<?php


require_once '../config.php';
require_once '../classes/Emission.Class.php';
require_once '../includes/session.php';

$filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";

if (file_exists($filepath)){

    $Emission = new Emission($_SESSION['case']);   
    $sysEmissions = $Emission->getSystemEmission_CO2();
    // $years = $Emission->eYears;
    // $techs = $Emission->eTechs;

    // $years = $Emission->getYearsArray();
    // $techs = $Emission->getTechsArray();
    $years = $Emission->eYears;
    $techs = $Emission->eTechs;

    $ENV = array();
    $ENV_t = array();
    
    foreach($sysEmissions as $year=>$obj){
         $ENVtmp = array();
         $ENVtmp['year'] = $year;
        // $ENVtmp_t[$i]['year'] = $year;
         foreach($obj as $tech=>$val){
             $ENVtmp[$tech] = $val;
             
         }
         $ENV[] = $ENVtmp;
        // $i++;
     }

     foreach($techs as $tech){
        if($tech!='ImportExport' && $tech!='Storage'){
            $ENVtmp_t = array();
            $ENVtmp_t['name'] = $tech;
            foreach($years  as $year){
                $ENVtmp_t[$year] = $sysEmissions[$year][$tech];
            }
            $ENV_t[] = $ENVtmp_t; 
        }
    }

    
    $carbonCosts = $Emission->getCarbonContentCost();
     
    $COST = array();
    $COST_t = array();

    foreach($carbonCosts as $year=>$obj){
        $COSTtmp = array();
        $COSTtmp['year'] = $year;
        foreach($obj as $tech=>$val){
            $COSTtmp[$tech] = $val;
            
        }
        $COST[] = $COSTtmp;
    }

    foreach($techs as $tech){
        if($tech!='ImportExport'&& $tech!='Storage'){
            $COSTtmp_t = array();
            $COSTtmp_t['name'] = $tech;
            foreach($years  as $year){
                $COSTtmp_t[$year] = $carbonCosts[$year][$tech];
            }
            $COST_t[] = $COSTtmp_t; 
        }
    }
          
    if (isset($_GET['action']) && $_GET['action']=="emissions"&& $_GET['trans']=='0'){  
        //$arraye=array_filter($eoutput);
        echo '{"data":'.json_encode(array_values($ENV)).'}';  
        die();
    }

    if (isset($_GET['action']) && $_GET['action']=="emissions"  && isset($_GET['trans'])&& $_GET['trans']==1){
        //$arrayet=array_filter($eoutput_t);
        echo '{"data":'.json_encode(array_values($ENV_t)).'}';
        die();
    }

    if (isset($_GET['action']) && $_GET['action']=="costs"&& $_GET['trans']=='0'){  
        //$arraye=array_filter($eoutput);
        echo '{"data":'.json_encode(array_values($COST)).'}';  
        die();
    }

    if (isset($_GET['action']) && $_GET['action']=="costs"  && isset($_GET['trans'])&& $_GET['trans']==1){
        //$arrayet=array_filter($eoutput_t);
        echo '{"data":'.json_encode(array_values($COST_t)).'}';
        die();
    }


            
}
?>
