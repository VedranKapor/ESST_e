<?php
/////////////// LCOE CLASS ///////////////////
//************************************************
//************************************************

//************** CREATED BY VK *******************
//************ esst ver 2.6.4 2019****************
///////////////////////////////////////////////////

//define('ROOT_DIR','C:\wamp.3.2.0\www\ESST\esst.ver.2.7.0');
require_once ROOT_DIR.'/classes/EsstCase.Class.php';
require_once ROOT_DIR.'/classes/Const.Class.php';
require_once ROOT_DIR.'/classes/paths.php';

class LCOE extends EsstCaseClass {

    public function __construct($pCase){                
        parent::__construct($pCase);
        $this->eYears = $this->getYears();
        $this->eTechs = $this->getTechs();
    }

    //LCOE calculation phase 1 
    public function getLCOE($CRF, $INV_c, $FOM_c, $VOM_c, $FUEL_c, $CO2_ef, $CARBON_c, $ETA, $CF, $IPCC ){
        $INV =  $INV_c * $CRF / ($CF/100 * 8.76);
        $FOM = $FOM_c/ ( $CF/100 * 8.76 );
        $VOM = $VOM_c;
        $FC = 3.6 * $FUEL_c / ($ETA/100);
        $CO2 = (($IPCC * $CARBON_c) * (100 -  $CO2_ef) / $ETA) *3.6/1000000;
        $this->lcoe_yt['INV'] = $INV;
        $this->lcoe_yt['FOM'] = $FOM;
        $this->lcoe_yt['VOM'] =  $VOM;
        $this->lcoe_yt['FC'] = $FC;
        $this->lcoe_yt['CO2'] = $CO2;
        $this->lcoe_yt['Total'] =  $INV + $FOM + $VOM + $FC + $CO2;
        return $this->lcoe_yt;
    }

    //LCOE calculation phase 1 
    public function getLCOE_0( ){
        $lcoe_0['INV'] = 0;
        $lcoe_0['FOM'] = 0;
        $lcoe_0['VOM'] =  0;
        $lcoe_0['FC'] = 0;
        $lcoe_0['CO2'] = 0;
        $lcoe_0['Total'] =  0;
        return $lcoe_0;
    }

    //Simple LCOe calculation for all yers and all technologies
    public function getLCOE_yt(){
        if (!isset($this->lcoe)) {
            $this->lcoe = array();
            //$ac = $this->getAC_d_yt(); 
            //instalirani akumulativni reserve capacity
            $IRC = $this->getIRC_yt();           
            $CF = $this->getCF_yt();
            $N =  $this->getLt_t();
            $dr =  $this->getDR_y();
            $COST = $this->getCost_yt();
            $ETA = $this->getEff_yt();
            $EF = $this->getEnv_yt();
            $CC = $this->getCC_y();
            $TIC = $this->getTIC_yt();
            $coc_yt = array(); //carryover capacity by tech
            // foreach($ac as $year=> $acObj){
            //     foreach($acObj as $tec=>$acVal){
            foreach($this->eYears as $year){
                foreach ($this->eTechs as $tec){
                    //if( $acVal != 0 && $N[$year][$tec] != 0 && $N[$year][$tec] != "" && $CF[$year][$tec] != 0){
                    if($tec != 'ImportExport' && $tec != 'Storage'){
                        if( $N[$year][$tec] != 0 && $N[$year][$tec] != "" && $CF[$year][$tec] != 0){
                            $CRF = ( $dr[$year]/100 * pow( (1 + $dr[$year]/100), $N[$year][$tec]) ) / ( pow( (1 + $dr[$year]/100), $N[$year][$tec]) -1);
                            $INV_c = $COST[$year][$tec]['Investment_cost'];
                            $FOM_c = $COST[$year][$tec]['Operating_cost_fixed'];
                            $VOM_c = $COST[$year][$tec]['Operating_cost_variable'];
                            $FUEL_c = $COST[$year][$tec]['Fuel_cost'];
                            $CO2_ef = $EF[$year][$tec]['CO2'];
                            $CARBON_c = $CC[$year];
                            $ETA_yt = $ETA[$year][$tec];
                            $IPCC = Constant::IPCC2016_CO2EmissionFactors[$tec]; 
                            //$CF_yt = $CF[$year][$tec];
                            if (array_key_exists($tec, $IRC[$year]) && $IRC[$year][$tec] != 0 && $IRC[$year][$tec] != $TIC[$year][$tec]) {
                                $CF_yt = (1 - $IRC[$year][$tec] / $TIC[$year][$tec]) * $CF[$year][$tec];
                                
                            }else{
                                $CF_yt = $CF[$year][$tec];
                            }
                            $this->lcoe[$year][$tec] =  $this->getLCOE($CRF, $INV_c, $FOM_c, $VOM_c, $FUEL_c, $CO2_ef, $CARBON_c, $ETA_yt, $CF_yt, $IPCC );
                        }
                        else{
                            $this->lcoe[$year][$tec] =  $this->getLCOE_0();
                        }
                    }
                }
            }
        }
        return $this->lcoe;
    }


    //Marginal Cost of technology
    public function getMCoT_yt(){
        if (!isset($this->mcot)) {
            $this->mcot = array();
            $lcoe = $this->getLCOE_yt();          
            foreach($this->lcoe as $year=>$lcoeObj){
                foreach ($lcoeObj as $tec=>$lcoeVal){
                    $this->mcot[$year][$tec] = $lcoeVal['VOM'] + $lcoeVal['FC'] + $lcoeVal['CO2'];
                }
                // $this->mcot[$year]['ImportExport'] =0;
                // $this->mcot[$year]['Storage'] =0;
            }
        }
        return $this->mcot;
    }

    ///////////////////////////////////////////////////////////////////Average Unit Cost
    public function getINV($CRF, $INV_c, $TAC ){
        $INV =  $INV_c * $CRF * $TAC * 1000;
        return $INV;
    }

    public function getOC($CRF, $VOM_c, $FUEL_c, $CO2_ef, $CARBON_c, $ETA, $EG, $IPCC ){
        $OC['VOM'] = $VOM_c * $EG * 1000;
        $OC['FC'] = ( $EG * 3.6 / ($ETA/100)) * $FUEL_c * 1000;
        $OC['CO2'] =   ( $EG * 3.6 / ($ETA/100)) * $IPCC * (100 -  $CO2_ef)/100 * $CARBON_c / 1000;
        $OC['Total'] = $OC['VOM'] + $OC['FC'] + $OC['CO2'];
        return $OC;
    }

    //LCOE calculation phase 1 
    public function getOC_0(){
        $oc_0['VOM'] =  0;
        $oc_0['FC'] = 0;
        $oc_0['CO2'] = 0;
        $oc_0['Total'] =  0;
        return $oc_0;
    }
    
    //Fixed Operating Cost
    public function getFOC( $FOM_c, $TIC ){
        $FOM = $FOM_c * $TIC * 1000;
        return $FOM;
    }

    //Adidtional Investment cost
    public function getAddINV_yt(){
        if (!isset($this->inv)) {
            $this->inv = array();
            $tac = $this->getTAC_d_yt();
            $N =  $this->getLt_t();
            $dr =  $this->getDR_y();
            $COST = $this->getCost_yt();
            foreach($tac as $year=> $tacObj){
                foreach($tacObj as $tec=>$tacVal){
                    if($tec != 'ImportExport' && $tec != 'Storage'){
                        if( $tacVal != 0 && $N[$year][$tec] != 0 && $N[$year][$tec] != ""){
                            $CRF = ( $dr[$year]/100 * pow( (1 + $dr[$year]/100), $N[$year][$tec]) ) / ( pow( (1 + $dr[$year]/100), $N[$year][$tec]) -1);
                            $INV_c = $COST[$year][$tec]['Investment_cost']; 
                            $this->inv[$year][$tec] =  $this->getINV($CRF, $INV_c, $tacVal ) ;
                        }
                        else{
                            $this->inv[$year][$tec] = 0;
                        }
                    }
                }
            }
        }
        return $this->inv;
    }

    //moramo izracunati decom od investemetn da bi izracunali accumulated Investemnt po godinama
    //getDC_yt get decomission Capacity by year and tech
    public function getDecomINV_yt() {
        if (!isset($this->di)) {
            $ai = $this->getAddINV_yt();
            $lt = $this->getLt_t();
            $this->dc = array();
            foreach($ai as $year=>$aiObj){
                foreach($aiObj as $tech=>$aiVal){
                    $lt_t = $lt[$tech];
                    $decomYear = $year + $lt_t;
                    if(isset($this->di[$decomYear][$tech])){
                        $this->di[$decomYear][$tech] = $this->di[$decomYear][$tech] + $aiVal;
                    }else{
                        $this->di[$decomYear][$tech] = $aiVal;
                    }
                }
            }
            ksort($this->di);
        }
        return $this->di;
    }

    //getDC_yt get decomission Capacity by year and tech
    public function adjustDecomINV_yt() {
        if (!isset($this->di_y)) {
            $dc = $this->getDecomINV_yt();
            $years = $this->getYears();
            $this->di_y = array();
            foreach($dc as $year=>$dcObj){
                if(in_array($year, $years)){
                    foreach($dcObj as $tech=>$dcVal){
                        if(isset( $this->di_y[$year][$tech])){
                            $this->di_y[$year][$tech] = $this->di_y[$year][$tech] + $dcVal;
                        }else{
                            $this->di_y[$year][$tech] = $dcVal;
                        }
                    }
                }else{
                    foreach($years as $yr){
                        if(($yr-$year)>0){
                            foreach($dcObj as $tech=>$dcVal){
                                if(isset( $this->di_y[$year][$tech])){
                                    $this->di_y[$yr][$tech] = $this->di_y[$year][$tech] + $dcVal;
                                }else{
                                    $this->di_y[$yr][$tech] = $dcVal;
                                }
                            }
                        break;
                        }
                    }
                }

            }
        }
        return $this->di_y;
    }

    //accumulated investmet over zears
    public function getAccINV_yt() {
        if (!isset($this->acci)) {
            $this->acci = array();
            $ai = $this->getAddINV_yt();
            $dc = $this->adjustDecomINV_yt(); 
            foreach($ai as $year=>$aiObj){
                foreach($aiObj as $tech=>$aiVal){
                    if(!isset($dc[$year][$tech])){ $dc[$year][$tech] = 0; }
                    if (isset($coc[$tech])){
                        //kumulativni CoC kapacitet
                        $coc[$tech] = $coc[$tech] + $aiVal - $dc[$year][$tech];
                        $this->acci[$year][$tech] = $coc[$tech];
                    }else{
                        $this->acci[$year][$tech] = $aiVal;
                        $coc[$tech] = $aiVal;
                    }
                }
            }
        }
        return $this->acci;
    }

    //Fixed costs 
    public function getFOC_yt(){
        if (!isset($this->fc)) {
            $this->fc = array();
            $N =  $this->getLt_t();
            //$dr =  $this->getDR_y();
            $COST = $this->getCost_yt();
            $TIC = $this->getTIC_yt();
            foreach($TIC as $year=> $ticObj){
                foreach($ticObj as $tec=>$ticVal){
                    if($tec != 'ImportExport' && $tec != 'Storage'){
                        if( $ticVal != 0 && $N[$year][$tec] != 0 && $N[$year][$tec] != "" ){
                            $FOM_c = $COST[$year][$tec]['Operating_cost_fixed'];
                            $this->fc[$year][$tec] =  $this->getFOC( $FOM_c, $ticVal );
                        }
                        else{
                            $this->fc[$year][$tec] = 0;
                        }
                    }
                }
            }
        }
        return $this->fc;
    }

    //Other costs, only when there is generation of electricitry per year and tech (no decom and carryover)
    public function getOC_yt(){
        if (!isset($this->oc)) {
            $this->oc = array();
            $EG = $this->getElD_yt_gwh();
            $N =  $this->getLt_t();
            $dr =  $this->getDR_y();
            $COST = $this->getCost_yt();
            $ETA = $this->getEff_yt();
            $EF = $this->getEnv_yt();
            $CC = $this->getCC_y();
            $TIC = $this->getTIC_yt();
            foreach($EG as $year=> $acObj){
                foreach($acObj as $tec=>$acVal){
                    if($tec != 'ImportExport' && $tec != 'Storage'){
                        if( $acVal != 0 && $N[$year][$tec] != 0 && $N[$year][$tec] != ""){
                            $CRF = ( $dr[$year]/100 * pow( (1 + $dr[$year]/100), $N[$year][$tec]) ) / ( pow( (1 + $dr[$year]/100), $N[$year][$tec]) -1);
                            $VOM_c = $COST[$year][$tec]['Operating_cost_variable'];
                            $FUEL_c = $COST[$year][$tec]['Fuel_cost'];
                            $CO2_ef = $EF[$year][$tec]['CO2'];
                            $CARBON_c = $CC[$year];
                            $ETA_yt = $ETA[$year][$tec];
                            $IPCC = Constant::IPCC2016_CO2EmissionFactors[$tec]; 
                            $this->oc[$year][$tec] =  $this->getOC($CRF, $VOM_c, $FUEL_c, $CO2_ef, $CARBON_c, $ETA_yt, $acVal, $IPCC );
                        }
                        else{
                            $this->oc[$year][$tec] = $this->getOC_0();
                        }
                    }
                }
            }
        }
        return $this->oc;
    }

    //Total Cost OC + AccINV
    public function getTC() {
        if (!isset($this->tc)) {
            $this->tc = array();
            $in = $this->getAccINV_yt();
            $oc = $this->getOC_yt(); 
            $fc = $this->getFOC_yt();
            foreach($this->eYears as $year){
                foreach ($this->eTechs as $tec){
                    if($tec != 'ImportExport' && $tec != 'Storage'){
                        $this->tc[$year][$tec]['INV'] = $in[$year][$tec];
                        $this->tc[$year][$tec]['FOM'] =  $fc[$year][$tec];
                        $this->tc[$year][$tec]['VOM'] = $oc[$year][$tec]['VOM'];
                        $this->tc[$year][$tec]['FC'] = $oc[$year][$tec]['FC'];
                        $this->tc[$year][$tec]['CO2'] = $oc[$year][$tec]['CO2'];
    
                        $this->tc[$year][$tec]['Total'] = $in[$year][$tec] + $oc[$year][$tec]['Total']+ $fc[$year][$tec];
                    }
                }
            }
        }
        return $this->tc;
    }

    //Average Unit cost per year and tech
    public function getAUC_yt(){
        if (!isset($this->auc)) {
            $this->auc = array();
            $ed_y = $this->getElD_y_gwh();       
            $TC = $this->getTC();
            foreach($TC as $year=> $ticObj){
                foreach($ticObj as $tec=>$ticVal){
                    if( $ticVal['Total'] != 0){
                        if($tec != 'ImportExport' && $tec != 'Storage'){
                            $this->auc[$year][$tec]['INV'] =  $TC[$year][$tec]['INV'] / $ed_y[$year] /1000;
                            $this->auc[$year][$tec]['FOM'] =  $TC[$year][$tec]['FOM'] / $ed_y[$year]/1000;
                            $this->auc[$year][$tec]['VOM'] =  $TC[$year][$tec]['VOM'] / $ed_y[$year]/1000;
                            $this->auc[$year][$tec]['FC']  =  $TC[$year][$tec]['FC']  / $ed_y[$year]/1000;
                            $this->auc[$year][$tec]['CO2'] =  $TC[$year][$tec]['CO2'] / $ed_y[$year]/1000;
                            //$this->auc[$year][$tec]['Total'] =  $TC[$year][$tec]['Total'] / $ed_y[$year];
                        }
                    }
                    else{
                        $this->auc[$year][$tec]['INV']  = 0;
                        $this->auc[$year][$tec]['FOM']  = 0;
                        $this->auc[$year][$tec]['VOM']  = 0;
                        $this->auc[$year][$tec]['FC']   = 0;
                        $this->auc[$year][$tec]['CO2']  = 0;
                        //$this->auc[$year][$tec]['Total'] = 0;
                    }
                }
            }
            
        }
        return $this->auc;
    }

    //Average Unit Cost per year total, INV, FOM, VOM, FC, CO2
    public function getAUC_y() {
        if (!isset($this->auc_y)) {
            $this->auc_y = array();
            $auc = $this->getAUC_yt();
            foreach($auc as $year=>$aucObj){
                foreach($aucObj as $tech=>$aucVal){
                    if(!isset( $this->auc_y[$year]['INV'])){$this->auc_y[$year]['INV'] = 0;}
                    if(!isset( $this->auc_y[$year]['FOM'])){$this->auc_y[$year]['FOM'] = 0;}
                    if(!isset( $this->auc_y[$year]['VOM'])){$this->auc_y[$year]['VOM'] = 0;}
                    if(!isset( $this->auc_y[$year]['FC'])){$this->auc_y[$year]['FC'] = 0;}
                    if(!isset( $this->auc_y[$year]['CO2'])){$this->auc_y[$year]['CO2'] = 0;}
                    //if(!isset( $this->auc_y[$year]['Total'])){$this->auc_y[$year]['Total'] = 0;}
                    // $this->auc_y[$year] = array_sum($aucObj);
                    $this->auc_y[$year]['INV'] = $this->auc_y[$year]['INV'] + $aucVal['INV'];
                    $this->auc_y[$year]['FOM'] = $this->auc_y[$year]['FOM'] + $aucVal['FOM'];
                    $this->auc_y[$year]['VOM'] = $this->auc_y[$year]['VOM'] + $aucVal['VOM'];
                    $this->auc_y[$year]['FC'] = $this->auc_y[$year]['FC'] + $aucVal['FC'];
                    $this->auc_y[$year]['CO2'] = $this->auc_y[$year]['CO2'] + $aucVal['CO2'];
                    //$this->auc_y[$year]['Total'] = $this->auc_y[$year]['Total'] + $aucVal['Total'];
                }
            }
        }
        return $this->auc_y;
    }
}


// $emission = new LCOE('DEMO');

// // echo "getMCoSdcp";
// // echo "<pre>";
// // $test =$emission->getMCoS_yh(true)[2000]['MCoS'];
// // arsort($test);
// // $test2 =array_values($test);
// // print_r($test2);
// // echo "</pre>";

// echo "getMCoT_yt";
// echo "<pre>";
// print_r($emission->getMCoT_yt());
// echo "</pre>";


// echo "getMCoScp";
// echo "<pre>";
// print_r($emission->getMCoScp(true));
// echo "</pre>";

// echo "getMCoS_yth";
// echo "<pre>";
// print_r($emission->getMCoS_yh(true));
// echo "</pre>";


// echo "getSUM_RHG_y";
// echo "<pre>";
// print_r($emission->getSUM_RHG_y(true));
// echo "</pre>";
?>