<?php
/////////////// Emission CLASS ///////////////////
//************************************************
//************************************************

//************** CREATED BY VK *******************
//************ esst ver 2.6.4 2019****************
///////////////////////////////////////////////////

//define('ROOT_DIR','C:\wamp64\www\ESST\esst.ver.2.7.0');
//require_once 'C:\wamp64\www\ESST\esst.ver.2.7.0/config.php';
require_once ROOT_DIR.'/classes/Maintenance.Class.php';
require_once ROOT_DIR.'/classes/Const.Class.php';
require_once ROOT_DIR.'/classes/paths.php';


class hEmission extends Maintenance {

    //protected $IPCC2016_CO2EmissionFactors  = array();
    //protected $eUnit;
    //protected $eSectors;
    protected $sysEmission_CO2_p2 = array();
    protected $elGenEmissions_p2 = array();
    protected $carbonContentCost_p2 = array();

    public function __construct($pCase){                
		
        parent::__construct($pCase);

    }

    //system emissions according to IPCC 2016 factors
    public function getSystemEmission_CO2_p2($pStorage){
        if (isset($this->sysEmission_CO2_p2) && empty($this->sysEmission_CO2_p2)) {
            // $TPES = $this->getTPES_yt();
            // $TFC = $this->getTFC_ysc();
            // $PEEG = $this->getPEEG_yt();
            $TPES = $this->TPES;
            $TFC = $this->TFC;
            $PEEG = $this->PEEG;
            $elGenEmission = $this->getElGenEmissions_p2($pStorage);
            foreach($this->yrs as $year){
                foreach($this->tech[$year] as $tech){
                    if($tech != 'ImportExport' && $tech != 'Storage'){ 
                        if (!in_array($tech, Constant::HourlyAnalysisTech)) {
                            if(isset( $TFC[$year]['Non_energy_use'][$tech])){
                                $nonEnergyUse = $TFC[$year]['Non_energy_use'][$tech];
                            }else{
                                $nonEnergyUse = 0;
                            }   
                            $this->sysEmission_CO2_p2[$year][$tech] = ( $TPES[$year][$tech] - $nonEnergyUse - $PEEG[$year][$tech]  ) * Constant::IPCC2016_CO2EmissionFactors[$tech] *  constant("Constant::{$this->Unit}_TJ") / 1000000 + $elGenEmission[$year][$tech]['CO2']; //dijelimo sa 10e6 da bi dobili kton
                        }else{
                            $this->sysEmission_CO2_p2[$year][$tech] = 0;
                        }
                    }
                }
            }
        }
        return $this->sysEmission_CO2_p2;
    }  
    
    //out 1000 currency
    public function getCarbonContentCost_p2($pStorage){
        if (isset($this->carbonContentCost_p2) && empty($this->carbonContentCost_p2)) {
            // $carbonCost = $this->getCC_y();
            $carbonCost = $this->CarbonCost;
            $sysEmission_CO2 = $this->getSystemEmission_CO2_p2($pStorage);
            foreach($sysEmission_CO2 as $year=>$techEm){
                foreach($techEm as $tech=>$em){
                    $this->carbonContentCost_p2[$year][$tech] = $em * $carbonCost[$year];
                }
            }
        }
        return $this->carbonContentCost_p2;      
    }

    public function getElGenEmissions_p2( $pStorage){
        if (isset($this->elGenEmissions_p2) && empty($this->elGenEmissions_p2)) {
            // $emissionFactors = $this->getEnv_yt();
            $emissionFactors = $this->EF;
            $RHG = $this->getSUM_RHG($pStorage);
            //ova efikasnost bi se trebala promijeniti jer dolayi iy faye 1
            // $eff = $this->getEff_yt();
            $eff = $this->Eff;
            foreach($this->yrs as $year){
                foreach($this->tech[$year] as $tech){
                    if($tech != 'ImportExport' && $tech != 'Storage'){ 
                        $elGen = $RHG[$year][$tech]/1000; //da dobijemo GWh
                        foreach (Constant::emissionType as $type){
                            if($type != 'CO2'){
                                $this->elGenEmissions_p2[$year][$tech][$type]  = $emissionFactors[$year][$tech][$type] * $elGen / 1000; //dijelimo sa 100 da dobijemo Ktone
                            }else{
                                $this->elGenEmissions_p2[$year][$tech][$type]  = ((100 - $emissionFactors[$year][$tech][$type])/100) * Constant::IPCC2016_CO2EmissionFactors[$tech] * $elGen / ($eff[$year][$tech]/100) * 3.6/1000000; //dijelimo sa 1000 da dobijemo Ktone
                            }
                        }
                    }
                }
            }
        }
        return $this->elGenEmissions_p2;
    }

    public function getEmissions_p2( $pStorage){
        if (!isset($this->emissions_p2)) {
            $this->emissions_p2 = array();
            $elEmissions = $this->getElGenEmissions_p2( $pStorage);
            $sysEmissions = $this->getSystemEmission_CO2_p2($pStorage);
            $this->emissions_p2 =  $elEmissions;
            foreach($this->yrs as $year){
                foreach($this->tech[$year] as $tech){
                    if($tech != 'ImportExport' && $tech != 'Storage'){ 
                        $this->emissions_p2[$year][$tech]['Sys'] =  $sysEmissions[$year][$tech];
                    }
                }
            }
        }
        return $this->emissions_p2;
    }
}
// $emission = new Emission('esst ver 2-6-5 v3-2');
// echo "getElGenEmissions_p2";
// echo "<pre>";
// print_r($emission->getElGenEmissions_p2(true));
// echo "</pre>";
?>