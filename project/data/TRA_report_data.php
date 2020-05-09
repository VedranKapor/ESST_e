<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once '../functions/xml_functions.php';
//require_once '../functions/calc_functions.php';
require_once '../config.php';
// require_once '../classes/EsstCase.php';
// require_once '../classes/EsstCase.Class.php';
require_once '../classes/LCOE.Class.php';
require_once '../includes/session.php';

$filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";

if (file_exists($filepath)){
    
    //    $xml = simplexml_load_file($filepath)
    //      or die("Error: Cannot create object");
    //    $esstCase = new EsstCase($_SESSION['case'], $xml);

    //    $elmix_fuels = $esstCase->getElMixFuels();
    //    $years = $esstCase->getYears(); 
    //    $unit = $esstCase->getUnit();
    
    //    $AdditionalPower = $esstCase->getAdditionalPower();
    //    $ReserveCapacityPower = $esstCase->getReserveCapacityPower();
    //    $InvestmentCost = $esstCase->getFinanceData();

    //    //total nneded power je potrebna snaga za sistem na to dodajemo Reserve capacity tako da za prikaz RC mozemo koisiti Needed Power
    //    $totalNeededPower = $esstCase->getTotalNeededPowerAllFuels();

    //    $EnsPower = $esstCase->getEnsCapacity();
    //    $TIC = $esstCase->getTIC();

    $esstCaseClass = new LCOE($_SESSION['case']);
    $techs = $esstCaseClass->getTechs();
    $yrs = $esstCaseClass->getYears();

    $installedCapacity = $esstCaseClass->getIC_yt();
    $IC = array();
    $IC_t = array();

    // $additionalCapacity = $esstCaseClass->getAC_d_yt();
    $additionalCapacity = $esstCaseClass->getAC_d_yt();
    $AC = array();
    $AC_t = array();

    $reserveCapacity = $esstCaseClass->getRC_yt();
    $RC = array();
    $RC_t = array();

    $elGeneration = $esstCaseClass->getElD_yt_gwh();
    $EG = array();
    $EG_t = array();

    $totalInstalledCapacity = $esstCaseClass->getTIC_yt();
    $TIC = array();
    $TIC_t = array();

    $neededCapacity = $esstCaseClass->getIAC_y();
    $totalICperY = $esstCaseClass->getTIC_y();

    $InvestmentCost = $esstCaseClass->getInvC_yt();
    $INV = array();
    $INV_t = array();

    $LevalizedCost = $esstCaseClass->getLCOE_yt();
    $LCOE = array();
    $LCOE_t = array();
   

    //////////////////////////////////////////////////////////////Average Unit Cost
    $AverageUnitCost_t = $esstCaseClass->getAUC_y();
    $AVGUC = array();
    $AVGUC_t = array();
    $lcoeVar = ['INV', 'FOM', 'VOM', 'FC', 'CO2'];
    $AVGUCtmp = array();
    $AVGUCtmp_t = array();

    foreach($lcoeVar as $var){
        $AVGUCtmp_t['name'] = $var;
        foreach($yrs as $year){
           // $AVGUCtmp['year'] = $year;
           // $AVGUCtmp[$var] = $AverageUnitCost_t[$year][$var];
            $AVGUCtmp_t[$year] = $AverageUnitCost_t[$year][$var];
        }
        //$AVGUC[] = $AVGUCtmp;
        $AVGUC_t[] = $AVGUCtmp_t;
    }


    foreach($yrs as $year){
        $AVGUCtmp['year'] = $year;
        foreach($lcoeVar as $var){ 
            $AVGUCtmp[$var] = $AverageUnitCost_t[$year][$var];
        }
        $AVGUC[] = $AVGUCtmp;
    }

    foreach($yrs as $year){
        $ICtmp = array();
        $ICtmp['year'] = $year;
        $ACtmp = array();
        $ACtmp['year'] = $year;
        $RCtmp = array();
        $RCtmp['year'] = $year;
        $EGtmp = array();
        $EGtmp['year'] = $year;
        $TICtmp = array();
        $TICtmp['year'] = $year;
        $INVtmp = array();
        $INVtmp['year'] = $year;
        $LCOEtmp = array();
        $LCOEtmp['year'] = $year;

        foreach($techs as $tech){
            if($tech!='ImportExport' && $tech!='Storage'){
                $ICtmp[$tech] = $installedCapacity[$year][$tech];
                $ACtmp[$tech] = $additionalCapacity[$year][$tech];
                if(isset( $reserveCapacity[$year][$tech])){
                    $RCtmp[$tech] = $reserveCapacity[$year][$tech];
                }else{
                    $RCtmp[$tech] = 0;
                }
                $EGtmp[$tech] = $elGeneration[$year][$tech];
                $TICtmp[$tech] = $totalInstalledCapacity[$year][$tech];
                $INVtmp[$tech] = $InvestmentCost[$year][$tech];
                $LCOEtmp[$tech] = $LevalizedCost[$year][$tech]['Total'];

                // $LCOEtmp['INV'.$tech] = $LevalizedCost[$year][$tech]['INV'];
                // $LCOEtmp['FOM'.$tech] = $LevalizedCost[$year][$tech]['FOM'];
                // $LCOEtmp['VOM'.$tech] = $LevalizedCost[$year][$tech]['VOM'];
                // $LCOEtmp['FC'.$tech] = $LevalizedCost[$year][$tech]['FC'];
                // $LCOEtmp['CO2'.$tech] = $LevalizedCost[$year][$tech]['CO2'];
            }
        }
        $TICtmp['NP'] = $neededCapacity[$year];
        $TICtmp['RS'] = $totalICperY[$year];     
        $IC[] = $ICtmp;
        $AC[] = $ACtmp;
        $RC[] = $RCtmp;
        $EG[] = $EGtmp;
        $TIC[] = $TICtmp;
        $INV[] = $INVtmp;
        $LCOE[] = $LCOEtmp;
        
    }


    foreach($techs as $tech){
       if($tech!='ImportExport' && $tech!='Storage'){
           $ICtmp_t = array();
           $ICtmp_t['name'] = $tech;
           $ACtmp_t = array();
           $ACtmp_t['name'] = $tech;
           $RCtmp_t = array();
           $RCtmp_t['name'] = $tech;
           $EGtmp_t = array();
           $EGtmp_t['name'] = $tech;
           $TICtmp_t = array();
           $TICtmp_t['name'] = $tech;
           $INVtmp_t = array();
           $INVtmp_t['name'] = $tech;
           $LCOEtmp_t = array();
           $LCOEtmp_t['name'] = $tech;

           foreach($yrs  as $year){
               $ICtmp_t[$year] = $installedCapacity[$year][$tech];
               $ACtmp_t[$year] = $additionalCapacity[$year][$tech];
               if(isset( $reserveCapacity[$year][$tech])){
                    $RCtmp_t[$year] = $reserveCapacity[$year][$tech];
                }else{
                    $RCtmp_t[$year] = 0;
                }
            //    $RCtmp_t[$year] = $reserveCapacity[$year][$tech];
               $EGtmp_t[$year] = $elGeneration[$year][$tech];
               $TICtmp_t[$year] = $totalInstalledCapacity[$year][$tech];
               $INVtmp_t[$year] = $InvestmentCost[$year][$tech];

               $LCOEtmp_t['INV'.$year] = $LevalizedCost[$year][$tech]['INV'];
               $LCOEtmp_t['FOM'.$year] = $LevalizedCost[$year][$tech]['FOM'];
               $LCOEtmp_t['VOM'.$year] = $LevalizedCost[$year][$tech]['VOM'];
               $LCOEtmp_t['FC'.$year] = $LevalizedCost[$year][$tech]['FC'];
               $LCOEtmp_t['CO2'.$year] = $LevalizedCost[$year][$tech]['CO2'];
               $LCOEtmp_t['Total'.$year] = $LevalizedCost[$year][$tech]['Total'];
           }
           $IC_t[] = $ICtmp_t; 
           $AC_t[] = $ACtmp_t;
           $RC_t[] = $RCtmp_t;
           $EG_t[] = $EGtmp_t;
           $TIC_t[] = $TICtmp_t;
           $INV_t[] = $INVtmp_t;
           $LCOE_t[] = $LCOEtmp_t;
       }
    }
  
          
          

    if (isset($_GET['action']) && $_GET['action']=="installed_power" && isset($_GET['trans'])&& $_GET['trans']==0 ) { 
        echo '{"data":'.json_encode(array_values($IC)).'}';  
        die();
    } 

    if (isset($_GET['action']) && $_GET['action']=="installed_power"  && isset($_GET['trans'])&& $_GET['trans']==1){  
        echo '{"data":'.json_encode(array_values($IC_t)).'}';
        die();
    }  

    if (isset($_GET['action']) && $_GET['action']=="elDef"&& $_GET['trans']=='0')  {  
        echo '{"data":'.json_encode(array_values($EG)).'}';
        die();
    }

    if (isset($_GET['action']) && $_GET['action']=="elDef"  && isset($_GET['trans'])&& $_GET['trans']==1){  
        echo '{"data":'.json_encode(array_values($EG_t)).'}';
        die();
    }     

    if (isset($_GET['action']) && $_GET['action']=="power_output"&& $_GET['trans']=='0'){    
        echo '{"data":'.json_encode(array_values($AC)).'}';  
        die();
    }

    if (isset($_GET['action']) && $_GET['action']=="power_output"  && isset($_GET['trans'])&& $_GET['trans']==1){  
        echo '{"data":'.json_encode(array_values($AC_t)).'}';  
        die();
    }     

    if (isset($_GET['action']) && $_GET['action']=="RC"&& $_GET['trans']=='0'){    
        echo '{"data":'.json_encode(array_values($RC)).'}';  
        die();
    }

    if (isset($_GET['action']) && $_GET['action']=="RC"  && isset($_GET['trans'])&& $_GET['trans']==1){  
        echo '{"data":'.json_encode(array_values($RC_t)).'}';  
        die();
    }    

    if (isset($_GET['action']) && $_GET['action']=="investment"&& $_GET['trans']=='0'){   
        echo '{"data":'.json_encode(array_values($INV)).'}';  
        die();  
    }

    if (isset($_GET['action']) && $_GET['action']=="investment"  && isset($_GET['trans'])&& $_GET['trans']==1) {
        echo '{"data":'.json_encode(array_values($INV_t)).'}';  
        die();
    }


    if (isset($_GET['action']) && $_GET['action']=="TIC_output"&& $_GET['trans']=='0'){   
        echo '{"data":'.json_encode(array_values($TIC)).'}';  
        die(); 
    }

    if (isset($_GET['action']) && $_GET['action']=="TIC_output"  && isset($_GET['trans']) && $_GET['trans']==1){  
        echo '{"data":'.json_encode(array_values($TIC_t)).'}';  
        die();
    }     

    if (isset($_GET['action']) && $_GET['action']=="lcoe"&& $_GET['trans']=='0'){   
        echo '{"data":'.json_encode(array_values($LCOE)).'}';  
        die(); 
    }
    if (isset($_GET['action']) && $_GET['action']=="lcoe"&& $_GET['trans']=='1'){   
        echo '{"data":'.json_encode(array_values($LCOE_t)).'}';  
        die(); 
    }

    if (isset($_GET['action']) && $_GET['action']=="avguc"&& $_GET['trans']=='0'){   
        echo '{"data":'.json_encode(array_values($AVGUC)).'}';  
        die(); 
    }
    if (isset($_GET['action']) && $_GET['action']=="avguc"&& $_GET['trans']=='1'){   
        echo '{"data":'.json_encode(array_values($AVGUC_t)).'}';  
        die(); 
    }
}
?>