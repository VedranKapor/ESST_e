<?php
/////////////// Emission CLASS ///////////////////
//************************************************
//************************************************

//************** CREATED BY VK *******************
//************ esst ver 2.6.4 2019****************
///////////////////////////////////////////////////

//define('ROOT_DIR','C:\wamp64\www\ESST\esst.ver.2.7.0');
//require_once 'C:\wamp64\www\ESST\esst.ver.2.7.0/config.php';
require_once ROOT_DIR.'/classes/EsstCase.Class.php';
require_once ROOT_DIR.'/classes/Const.Class.php';
require_once ROOT_DIR.'/classes/paths.php';

class Emission extends EsstCaseClass {

    //protected $IPCC2016_CO2EmissionFactors  = array();
    protected $eUnit;
    protected $eSectors;
    protected $sysEmission_CO2 = array();
    protected $elGenEmissions = array();
    protected $carbonContentCost = array();

    public function __construct($pCase){                
		
        parent::__construct($pCase);

        $this->eUnit = $this->getUnit();
        $this->eYears = $this->getYears();
        $this->eTechs = $this->getTechs();
    }

    public function getSystemEmission_CO2(){
        if (isset($this->sysEmission_CO2) && empty($this->sysEmission_CO2)) {
            $TPES = $this->getTPES_yt();
            $TFC = $this->getTFC_ysc();
            //$SESel = $this->getElD_yt();
            $PEEG = $this->getPEEG_yt();
            $elGenEmission = $this->getElGenEmissions();
            foreach($this->eTechs as $tech){
                if($tech != 'ImportExport' && $tech != 'Storage'){ 
                    foreach($this->eYears as $year){
                        if (!in_array($tech, Constant::HourlyAnalysisTech)) {
                            if(isset($TFC[$year]['Non_energy_use'][$tech])){
                                $nonEnergyUse = $TFC[$year]['Non_energy_use'][$tech];
                            }
                            else{
                                $nonEnergyUse = 0;
                            }
                            $this->sysEmission_CO2[$year][$tech] = ( $TPES[$year][$tech] - $nonEnergyUse - $PEEG[$year][$tech]  ) * Constant::IPCC2016_CO2EmissionFactors[$tech] *  constant("Constant::{$this->eUnit}_TJ") / 1000000 + $elGenEmission[$year][$tech]['CO2']; //dijelimo sa 10e6 da bi dobili kton
                        }else{
                            $this->sysEmission_CO2[$year][$tech] = 0;
                        }
                        
                    }
                }
            }
        }
        return $this->sysEmission_CO2;
    }  
    
    //out 1000 currency
    public function getCarbonContentCost(){
        if (isset($this->carbonContentCost) && empty($this->carbonContentCost)) {
            $carbonCost = $this->getCC_y();
            $sysEmission_CO2 = $this->getSystemEmission_CO2();
            foreach($sysEmission_CO2 as $year=>$techEm){
                foreach($techEm as $tech=>$em){
                    $this->carbonContentCost[$year][$tech] = $em * $carbonCost[$year] / 1000;   //[kton] * [EUR/ton] dijelomo sa 1000 da dobijemo milione eur
                }
            }
        }
        return $this->carbonContentCost;      
    }

    public function getElGenEmissions(){
        if (isset($this->elGenEmissions) && empty($this->elGenEmissions)) {
            $emissionFactors = $this->getEnv_yt();
            foreach($this->eTechs as $tech){
                if($tech != 'ImportExport' && $tech != 'Storage'){ 
                    foreach($this->eYears as $year){
                        $elGen = $this->getElD_yt_gwh();
                        $eff = $this->getEff_yt();
                        foreach (Constant::emissionType as $type){
                            //koristenje emisiskih faktora IPCC za CO2
                            if($type != 'CO2'){
                                $this->elGenEmissions[$year][$tech][$type]  = $emissionFactors[$year][$tech][$type] * $elGen[$year][$tech] / 1000; //dijelimo sa 100 da dobijemo Ktone
                            }else{
                                $this->elGenEmissions[$year][$tech][$type]  = ((100 - $emissionFactors[$year][$tech][$type])/100) * Constant::IPCC2016_CO2EmissionFactors[$tech] * $elGen[$year][$tech] / ($eff[$year][$tech]/100) * 3.6/1000000; //dijelimo sa 1000 da dobijemo Ktone
                            }
                           
                        }                        
                    }
                }
            }
        }
        return $this->elGenEmissions;
    }
}

// $emission = new Emission('esst ver 2-6-5 v3-2');
// echo "getElGenEmissions_p2";
// echo "<pre>";
// print_r($emission->getElGenEmissions_p2(true));
// echo "</pre>";
?>