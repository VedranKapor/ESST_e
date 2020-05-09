<?php
/////////////// HPattern CLASS ///////////////////
//************************************************
//************************************************

//************** CREATED BY VK *******************
//************ esst ver 2.6.5 2019****************
///////////////////////////////////////////////////

//define('ROOT_DIR','C:\wamp64\www\ESST\esst.ver.2.7.0);
require_once ROOT_DIR.'/classes/EsstCase.Class.php';
require_once ROOT_DIR.'/classes/Const.Class.php';
require_once ROOT_DIR.'/classes/paths.php';

class hPattern extends EsstCaseClass {

    protected $HP_Demand;
    protected $HP_Wind;
	protected $HP_Solar;
    protected $HP_Hydro;

    protected $MaxHDP = array(); //maximum of Hourly Pattern
    protected $MaxHP = array(); //maximum of Hourly Pattern
    protected $SumHDP = array(); //Suma Hourly Pattern
    protected $SumHP = array(); //Suma Hourly Pattern
    protected $LF     = array();
	protected $PMAX   = array();
    protected $CF = array();    // Racunanje CF Capacity Factor
    protected $NHP = array(); //Normalized Hourly Pattern
    protected $NHDP   = array();

    protected $HDp     = array();
    protected $HGp = array(); //Hourly Generation by Tech
    protected $CFp = array();

    protected $EDgwh = array();
    protected $TIC = array();
    protected $elImportMW = array();

    protected $hSimulation;

    public function __construct($pCase, $hDemand, $hSolar, $hWind, $hHydro, $hYears, $hTechs){                
		
        parent::__construct($pCase);

        $this->HP_Demand = $hDemand;
        $this->HP_Solar = $hSolar;
        $this->HP_Wind = $hWind;
        $this->HP_Hydro  = $hHydro;
        $this->yrs = $hYears;
        $this->tech = $hTechs;
        
        $this->EDgwh = $this->getElD_y_gwh();
        $this->elImportMW = $this->getElImport_y_mw();    
        $this->TIC = $this->getTIC_yt();
        
        $this->hSimulation = XML.$_SESSION['us'].'/'.$pCase."/hSimulation";      

    }

    //max value of HDP,HWG,HSG,HHP....
    public function getMaxHDP(){
        if (isset($this->MaxHDP) && empty($this->MaxHDP)) {
            foreach($this->yrs as $year){
                $this->MaxHDP[$year] = max($this->HP_Demand[$year]);
            }
        }
        return $this->MaxHDP;	
    }

    //max value of HDP,HWG,HSG,HHP....
    public function getMaxHP(){
        if (isset($this->MaxHP) && empty($this->MaxHP)) {
            foreach($this->yrs as $year){
                foreach ($this->tech as $tec){
                    if (in_array($tec, Constant::HourlyAnalysisTech)) {
                        $this->MaxHP[$year][$tec] = max($this->{"HP_$tec"}[$year]);
                    }
                }
            }
        }
        return $this->MaxHP;	
    }

    //izracunati  EHDP (energija iz paterna) za odredjenu godinu; Energy Contained in hourly demand pattern
    public function getSumHDP(){
        if (isset($this->SumHDP) && empty($this->SumHDP)) {
            foreach($this->yrs as $year){
                $this->SumHDP[$year] = array_sum($this->HP_Demand[$year]);
            }
        }
        return $this->SumHDP;
    }

    //izracunati  EHDP (energija iz paterna) za odredjenu godinu; Energy Contained in hourly demand pattern
    public function getSumHP(){
        if (isset($this->SumHP) && empty($this->SumHP)) {
            foreach($this->yrs as $year){
                foreach ($this->tech as $tec){
                    if (in_array($tec, Constant::HourlyAnalysisTech)) {
                        $this->SumHP[$year][$tec] = array_sum($this->{"HP_$tec"}[$year]);
                    }
                }
            }
        }
        return $this->SumHP;
    }

    //izracunati Load Factor
    public function getLF(){
        if (isset($this->LF) && empty($this->LF)) {
            $MaxHDP = $this->getMaxHDP();
            $SumHDP =  $this->getSumHDP();
            foreach($this->yrs as $year){
                $this->LF[$year] = $SumHDP[$year]/($MaxHDP[$year]*87.6);
            }
        }
        return $this->LF;
    }

    //izracunati PMAX Peak Load
    public function getPMAX(){
        if (isset($this->PMAX) && empty($this->PMAX)) {
            $LF = $this->getLF();
            foreach($this->yrs as $year){
                $this->PMAX[$year] = $this->EDgwh[$year]/($LF[$year]/100*8760)*1000; //da dobijemo MW
            }
        }
        return $this->PMAX;
    }

    //izracunati Capacity Factor 
    //CF za hAnalysis tech se racuna iz patterna CF_pattern
    public function getCFp(){
        if (isset($this->CFp) && empty($this->CFp)) {
            $SumHP = $this->getSumHP();
            $MaxHP = $this->getMaxHP();
            $CF = $this->getCF_yt();
            foreach($this->yrs as $year){
                foreach ($this->tech as $tec){
                    if($tec != 'ImportExport'){
                        if (in_array($tec, Constant::HourlyAnalysisTech) && $MaxHP[$year][$tec] != 0) {
                            $this->CFp[$year][$tec] = $SumHP[$year][$tec]/($MaxHP[$year][$tec]*87.6);
                        }
                        else{
                            $this->CFp[$year][$tec] = $CF[$year][$tec];
                            //$this->CF[$year][$tec] = $this->getCapacityFactor($year, $tec);
                        }
                    }
                }
            }
        }
        return $this->CFp;
    }

    //izracunati NHDP Normalizovani
    public function getNHDP(){
        if (isset($this->NHDP) && empty($this->NHDP)) {
            $MaxHDP = $this->getMaxHDP();
            foreach($this->yrs as $year){				
                $this->NHDP[$year] = array_map( function($val, $i) { return $val / $i; }, $this->HP_Demand[$year], array_fill(0, count($this->HP_Demand[$year]), $MaxHDP[$year])); 
            }
        }
        return $this->NHDP;
    }

    //izracunati NHP Normalizovani
    public function getNHP(){
        if (isset($this->NHP) && empty($this->NHP)) {
            $MaxHP = $this->getMaxHP();
            foreach($this->yrs as $year){	
                foreach ($this->tech as $tec){
                    if (in_array($tec, Constant::HourlyAnalysisTech)) {	
                        //$this->NHP[$year][$tec] = array_map( function($val, $i) { return $val / $i; }, $this->{"HP_$tec"}[$year],array_fill(0, 8760, $MaxHP[$year][$tec])); 
                        $this->NHP[$year][$tec] = array_map( function($val, $i) { return $i <> 0 ? $val / $i : 0  ; }, $this->{"HP_$tec"}[$year],array_fill(0, 8760, $MaxHP[$year][$tec])); 
                    }
                }
            }
        }
        return $this->NHP;
    }

       	//izracunati  HD Hourly Demand
	public function getHDp(){
		if (isset($this->HDp) && empty($this->HDp)) {
            $NHDP = $this->getNHDP();
            $PMAX = $this->getPMAX();
			foreach($this->yrs as $year){
				$this->HDp[$year] = array_map( function($val, $j) { return $val * $j; }, $NHDP[$year], array_fill(0, 8760, $PMAX[$year] ) );
            }
		}
		return $this->HDp;
    }
    
        //izracunati HG Hourly Generation [NHPFt,y * TotalPowerP1[MW] = Hourly Generation by tec, year, hour]
    public function getHGp(){
        if (isset($this->HGp) && empty($this->HGp)) {
            $NHP = $this->getNHP();
            foreach($this->yrs as $year){
                foreach ($this->tech as $tec){
                    if (in_array($tec, Constant::HourlyAnalysisTech)) {
                        $this->HGp[$year][$tec] = array_map( function($val, $j) { return $val * $j; }, $NHP[$year][$tec], array_fill(0, 8760, $this->TIC[$year][$tec] ) );
                    }
                    else{
                        $this->HGp[$year][$tec] = array_fill(0, 8760, $this->TIC[$year][$tec]);                      
                    }
                }
                $this->HGp[$year]['ImportExport'] = array_fill(0, 8760, $this->elImportMW[$year]) ;
            }
        }
        return $this->HGp;
    }
}
?>