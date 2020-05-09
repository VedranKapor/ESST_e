<?php
/////////////// Maintenance CLASS ///////////////////
//************************************************
//************************************************

//************** CREATED BY VK *******************
//************ esst ver 2.6.5 2019****************
///////////////////////////////////////////////////

//define('ROOT_DIR','C:\wamp64\www\ESST\esst.ver.2.7.0');
require_once ROOT_DIR.'/classes/HourlyAnalysis.Class.php';
require_once ROOT_DIR.'/classes/Const.Class.php';
require_once ROOT_DIR.'/classes/paths.php';


class Maintenance extends HourlyData {

	protected $HGi = array(); //Hourly Generation by Intermitent Techs by years
    protected $RHLD    = array();
    protected $RHLD_p    = array();
    protected $MSPACE_long    = array();
    protected $MSPACE_short    = array();
    protected $MCLASS    = array();  //maintennce unit size
    protected $MCLASSnumbers = array();
    protected $MCLASS_order = array();

    //protected $MCLASS_order_h = array();

    protected $MCLASS_order_h_cp = array();
    protected $TAIP = array();
    protected $TIC_h = array();

    protected $nPeriodLong; 
    protected $nPeriodShort; 
    protected $hPeriod;  
    protected $mChunkLong;
    protected $mChunkShort;

    public function __construct($pCase){                
		
        parent::__construct($pCase);

        $this->nPeriodShort = 24;
        $this->nPeriodLong = 12;

        $this->hPeriod = 8760;

        $this->MDLong = array_values(array_filter(array_map( function($tech, $duration) { if($duration==4) return $tech; }, array_keys($this->MD), array_values($this->MD)))); 
        $this->MDShort = array_values(array_filter(array_map( function($tech, $duration) { if($duration==2) return $tech; }, array_keys($this->MD), array_values($this->MD))));
        $this->NoMaintenance = array_values(array_filter(array_map( function($tech, $duration) { if($duration==0) return $tech; }, array_keys($this->MD), array_values($this->MD))));

        $this->mChunkLong = $this->hPeriod/$this->nPeriodLong;
        $this->mChunkShort = $this->hPeriod/$this->nPeriodShort;

        //$this->TICD_p1 = $this->getTDC_y();  
    }

	//izracunati  SUM_HG smao za intermiyent tehnologije da mozemo smanjiti demand za tu proizvodnju za svaku godinu za svaki sat
    public function getHGi(){
		if (isset($this->HGi) && empty($this->HGi)) {
            //HG nam treba bez Storage, prvo radimo maintennace pa onda storage
            //$HG = $this->getHG(false);
            $HG = $this->initHG();
            foreach($this->yrs as $year){
                $this->HGi[$year] = array_fill(0, 8760, 0);
		        foreach ($this->tech[$year] as $tec){
                    if (in_array($tec, Constant::HourlyAnalysisTech)) {
                        for($i=0;$i<8760;$i++){
                            $this->HGi[$year][$i] = $this->HGi[$year][$i] + $HG[$year][$tec][$i];
                        } 
                    }
                }
            }
		}
		return $this->HGi;
	}
	
    //izracunati RHLD Resulting Hourly Load Dispatchable RHD = HD -ImportExport; RHLD = RHD - HGI(Solar, Hydro, Wind)
   	public function getRHLD(){
		if (isset($this->RHLD) && empty($this->RHLD)) {
            $RHD = $this->getRHD();
            $HGi = $this->getHGi();
            foreach($this->yrs as $year){
		        foreach ($this->tech[$year] as $tec){
                    if (in_array($tec, Constant::HourlyAnalysisTech)) {
    				    $this->RHLD[$year] = array_map( function($val, $j) { return ($val-$j) > 0 ? ($val-$j) : 0; }, $RHD[$year],  $HGi[$year] );
                    }
                }
            }
        }
		return $this->RHLD;
	}

    //podijeliti RHLD niz na 12 perioda za odrzavanje
    public function getRHLD_p($mChunk, $nPeriod){
        //if (isset($this->RHLD_p) && empty($this->RHLD_p)) {
            $RHLD = $this->getRHLD();
            foreach($this->yrs as $year){
                $tmp[$year] = array_chunk($RHLD[$year], $mChunk);
                for($i=1; $i<=$nPeriod; $i++){
    				$this->RHLD_p[$year][$i] = max($tmp[$year][$i-1]); 
                }
            }
		//}
		return $this->RHLD_p;
    }

    //izracunati Maintanence space prostor izmedju instaliranse snage za dipstchable goriva i demanda
    public function getMSPACE_long(){
        if (isset($this->MSPACE_long) && empty($this->MSPACE_long)) {
            $RHLD_p = $this->getRHLD_p($this->mChunkLong, $this->nPeriodLong);
            $TICD = $this->TICD_p1;
            foreach($this->yrs as $year){
                for($i=1; $i<=$this->nPeriodLong; $i++){
                    $tmp = $TICD[$year] - $RHLD_p[$year][$i];
                    if($tmp > 0){
    				    $this->MSPACE_long[$year][$i] = floor($tmp);
                    }
                    else{
                        $this->MSPACE_long[$year][$i] = 0;
                    }
                }
            }
		}
		return $this->MSPACE_long;
    }

    //izracunati Maintanence space prostor izmedju instaliranse snage za dipstchable goriva i demanda
    public function getMSPACE_short(){
        if (isset($this->MSPACE_short) && empty($this->MSPACE_short)) {
            $RHLD_p = $this->getRHLD_p($this->mChunkShort, $this->nPeriodShort);
            $TICD = $this->TICD_p1;
            foreach($this->yrs as $year){
                for($i=1; $i<=$this->nPeriodShort; $i++){
                    // if ($year == '2020'){
                    //     echo "godina " . $year . " period " . $i . " instalirano = " .  $TICD[$year]  . " demand = " . $RHLD_p[$year][$i]. " SPACE = ". ($TICD[$year] - $RHLD_p[$year][$i]) . "<br>";
                    // }
                    $tmp = $TICD[$year] - $RHLD_p[$year][$i];
                    if($tmp > 0){
    				    $this->MSPACE_short[$year][$i] = floor($tmp);
                    }
                    else{
                        $this->MSPACE_short[$year][$i] = 0;
                    }
                }
            }
		}
		return $this->MSPACE_short;
    }

    //izracunati Maintanence unit size za sve thnologije po godinama
    public function getMCLASS(){
        if (isset($this->MCLASS) && empty($this->MCLASS)) {
            //$MUS = $this->MaintenanceUnitSize;
            $MUS = $this->MUS;
            $TICyt = $this->TIC;
            foreach($this->yrs as $year){
		        foreach ($this->tech[$year] as $tec){
                    if (!in_array($tec, Constant::HourlyAnalysisTech) && $tec != 'ImportExport'&& $tec != 'Storage') {
                        $res = $TICyt[$year][$tec] / $MUS[$tec];
                        //$mod = $TICyt[$tec][$year] % $MUS[$tec];
                        $mod = fmod($TICyt[$year][$tec], $MUS[$tec]);
                        switch (true) {
                            case ($res<=1):
                                $this->MCLASS['UnitSize'][$year][$tec] = $TICyt[$year][$tec];
                                $this->MCLASS['UnitNumber'][$year][$tec] = ceil($res);
                                break;
                            case ($res>1):
                                if($mod != 0){
                                    $this->MCLASS['UnitSize'][$year][$tec] = $TICyt[$year][$tec] / ceil($res);
                                    $this->MCLASS['UnitNumber'][$year][$tec] = ceil($res);
                                }
                                else{
                                    $this->MCLASS['UnitSize'][$year][$tec] = $TICyt[$year][$tec] / $res;
                                    $this->MCLASS['UnitNumber'][$year][$tec] = $res;
                                }
                                break;
                        } 
                    }
                    //Hydro Slar Wind se ne odrzavaju ali m orarmo setovati broj jedniinca i velicniu jednince na 0
                    else if($tec != 'ImportExport'&& $tec != 'Storage')
                        {
                        $this->MCLASS['UnitSize'][$year][$tec] = 0;
                        $this->MCLASS['UnitNumber'][$year][$tec] = 0;
                    }
                }
            }
		}
		return $this->MCLASS;
    }

    //izracunati i napraviti redosljed odrzavanja 
    public function getMCLASS_order($MCLASS, $MSPACE_long, $MSPACE_short, $year){
        //najveci broj jedinica po tehnologiji za odrzavanje
        if(max($MCLASS['UnitNumber'][$year]) > 0){
            //tehnologija koja ima najvise jedinica za odrzavanje
            $technology = array_keys($MCLASS['UnitSize'][$year], max($MCLASS['UnitSize'][$year]));
            $tech = $technology[0];

            if (in_array($tech , $this->MDLong)) {
                
                if(max($MSPACE_long[$year]) >= max($MCLASS['UnitSize'][$year])){

                    $periodOdrzavanja = array_keys($MSPACE_long[$year], max($MSPACE_long[$year]));
                    $period = $periodOdrzavanja[0];
                    $unitSize = $MCLASS['UnitSize'][$year][$tech];
                    $maxSpace = max($MSPACE_long[$year]);
                   
                    
                    if($unitSize <= $maxSpace && $MCLASS['UnitNumber'][$year][$tech]!=0){

                        $MSPACE_long[$year][$period] = $MSPACE_long[$year][$period] - $unitSize;
                        $MSPACE_short[$year][2*$period-1] = $MSPACE_short[$year][2*$period-1] - $unitSize;
                        $MSPACE_short[$year][2*$period] = $MSPACE_short[$year][2*$period] - $unitSize;
                        //da bi omogucili da se vise jedinica odrzava u istom periodu moramo izvrsiti agregaciju unit sizeova u tom periodu
                        if(isset($this->MCLASS_order[$year][$tech][$period])){
                            $this->MCLASS_order[$year][$tech][$period] = $this->MCLASS_order[$year][$tech][$period] + $unitSize;
                        }
                        else{
                            $this->MCLASS_order[$year][$tech][$period] =  $unitSize;
                        }

                        $MCLASS['UnitNumber'][$year][$tech]--;
                    }
                    
                    if($MCLASS['UnitNumber'][$year][$tech]==0){
                            $MCLASS['UnitSize'][$year][$tech] = 0;
                    }
                    
                    $this->MCLASS_order[$year][$tech][0] = 0;
                    $this->getMCLASS_order($MCLASS, $MSPACE_long, $MSPACE_short, $year);
                }
                //nema prostora MSPACE-a
                //nema prostora za odrzavanje ove jedinice jer je njena velicina veca od maxSpace za odrzavanje, potrebno je ispisati da ta jedinica ne moze biti odrzavana
                //i pokusati sa tehnologijom koja ima manju jedinicu za odrzavanje...
                else{
                    $technology = array_keys($MCLASS['UnitSize'][$year], max($MCLASS['UnitSize'][$year]));
                    $unitSize = $MCLASS['UnitSize'][$year][$tech];
                    $unitNumber = $MCLASS['UnitNumber'][$year][$tech];
                    $this->MCLASS_order[$year][$tech][0] = $unitNumber;
                    $MCLASS['UnitNumber'][$year][$tech]=0;
                    $MCLASS['UnitSize'][$year][$tech] = 0;
                   // echo "prije rekurzije 1";
                    $this->getMCLASS_order($MCLASS,  $MSPACE_long, $MSPACE_short, $year);
                }
            }
            else if (in_array($tech , $this->MDShort)) {
                if(max($MSPACE_short[$year]) >= max($MCLASS['UnitSize'][$year])){

                    $unitSize = $MCLASS['UnitSize'][$year][$tech];
                    $maxSpace = max($MSPACE_short[$year]);
                    $periodOdrzavanja = array_keys($MSPACE_short[$year], max($MSPACE_short[$year]));
                    $period = $periodOdrzavanja[0];
                    
                    if($unitSize <= $maxSpace && $MCLASS['UnitNumber'][$year][$tech]!=0){

                        $MSPACE_short[$year][$period] = $MSPACE_short[$year][$period] - $unitSize;
                        //$MSPACE_long[$year][($period+1)/2] = $MSPACE_long[$year][($period+1)/2] - $unitSize;

                        $periodL = floor(($period+1)/2);
                        $MSPACE_long[$year][$periodL] = $MSPACE_long[$year][$periodL] - $unitSize;

                        //$this->MCLASS_order[$year][$tech][$period] = $unitSize;
                        //da bi omogucili da se vise jedinica odrzava u istom periodu moramo izvrsiti agregaciju unit sizeova u tom periodu
                        if(isset($this->MCLASS_order[$year][$tech][$period])){
                            $this->MCLASS_order[$year][$tech][$period] = $this->MCLASS_order[$year][$tech][$period] + $unitSize;
                        }
                        else{
                            $this->MCLASS_order[$year][$tech][$period] =  $unitSize;
                        }

                        $MCLASS['UnitNumber'][$year][$tech]--;
                    }
                    
                    if($MCLASS['UnitNumber'][$year][$tech]==0){
                        $MCLASS['UnitSize'][$year][$tech] = 0;
                    }
                    
                    $this->MCLASS_order[$year][$tech][0] = 0;
                     //echo "prije rekurzije 2";
                    $this->getMCLASS_order($MCLASS, $MSPACE_long, $MSPACE_short, $year);
                }
                //nema prostora MSPACE-a
                //nema prostora za odrzavanje ove jedinice jer je njena velicina veca od maxSpace za odrzavanje, potrebno je ispisati da ta jedinica ne moze biti odrzavana
                //i pokusati sa tehnologijom koja ima manju jedinicu za odrzavanje...
                else{
                    $technology = array_keys($MCLASS['UnitSize'][$year], max($MCLASS['UnitSize'][$year]));
                    $unitSize = $MCLASS['UnitSize'][$year][$tech];
                    $unitNumber = $MCLASS['UnitNumber'][$year][$tech];
                    $this->MCLASS_order[$year][$tech][0] = $unitNumber;
                    $MCLASS['UnitNumber'][$year][$tech] = 0;
                    $MCLASS['UnitSize'][$year][$tech] = 0;
                    $this->getMCLASS_order($MCLASS,  $MSPACE_long, $MSPACE_short, $year);
                }
            }
            //tehnlogoje cijei je Maintenece duration = 0 04182020 dodano nakon sto smo ukljucili MD = 0
            else if (in_array($tech , $this->NoMaintenance)) {
                $unitSize = $MCLASS['UnitSize'][$year][$tech];
                $unitNumber = $MCLASS['UnitNumber'][$year][$tech];
                $this->MCLASS_order[$year][$tech][0] = $unitNumber;
                $MCLASS['UnitNumber'][$year][$tech] = 0;
                $MCLASS['UnitSize'][$year][$tech] = 0;
                $this->getMCLASS_order($MCLASS,  $MSPACE_long, $MSPACE_short, $year);
            }
        }
        else{
            //nema vise jedinica za odrzavanje 
            foreach ($this->tech[$year] as $tec){
                if (in_array($tec, Constant::HourlyAnalysisTech)) {
                        $this->MCLASS_order[$year][$tec][0] = 0;
                }
                else if($tec != 'ImportExport'&& $tec != 'Storage')
                {
                    if($this->MCLASS['UnitSize'][$year][$tec] == 0 && $this->MCLASS['UnitNumber'][$year][$tec] == 0){
                        $this->MCLASS_order[$year][$tec][0] = 0;
                    }
                }
            } 
        }   
		return $this->MCLASS_order[$year];
	}

    public function getMCLASS_orderLoop(){
        $MCLASS = $this->getMCLASS();
        $MSPACE_long = $this->getMSPACE_long();
        $MSPACE_short = $this->getMSPACE_short();
        foreach($this->yrs as $year){
            $this->getMCLASS_order($MCLASS, $MSPACE_long, $MSPACE_short, $year);
        }

    }

    //izracunati i napraviti redosljed odrzavanja po satima 
    public function getMCLASS_order_h(){
		//if (isset($this->MCLASS_order_h) && empty($this->MCLASS_order_h)) {
            // $MCLASS = $this->getMCLASS();
            // $MSPACE_long = $this->getMSPACE_long();
            // $MSPACE_short = $this->getMSPACE_short();

            $this->getMCLASS_orderLoop();

            foreach($this->yrs as $year){
                //$this->getMCLASS_order($MCLASS, $MSPACE_long, $MSPACE_short, $year);
                foreach( $this->MCLASS_order[$year] as $tec=>$v){
                    foreach($v as $period=>$value){
                        if($period != 0){
                            if (in_array($tec , $this->MDLong)) {
                            //if($tec == 'Nuclear' || $tec == 'Coal'){
                                //echo "year ".$year." tech " . $tec . " period ".$period. ' chumk ' . ($period-1)*$this->mChunkLong . " value " . $value .'<br>';
                                array_splice($this->MCLASS_order_h[$year][$tec], ($period-1)*$this->mChunkLong,   $this->mChunkLong,     array_fill(0,$this->mChunkLong,$value));
                            }
                            else{
                               // echo "year ".$year." tech " . $tec . " period ".$period. ' chumk ' . ($period-1)*$this->mChunkShort . " value " . $value .'<br>';
                                array_splice($this->MCLASS_order_h[$year][$tec], ($period-1)*$this->mChunkShort,  $this->mChunkShort,    array_fill(0,$this->mChunkShort,$value));
                            }
                        }
                    }
                }
            }
        //}
		return $this->MCLASS_order_h;
	}

    //izracunati i napraviti redosljed odrzavanja po satima preatty print
	public function getMCLASS_order_h_cp($pMaintenance){
		if (isset($this->MCLASS_order_h_cp) && empty($this->MCLASS_order_h_cp)) {
            if($pMaintenance){
                $HM = $this->getMCLASS_order_h();
            }	
            //RHLD se mora pozvati poslije getMCLASS_order_h da bi getHG imala vrijednosti odrzavanja
            $RHLD = $this->getRHLD();   //RHD, HD, HGi, HG, NHP, DP
	    
			foreach($this->yrs as $years){   
			     for($i=0;$i<8760;$i++){

                    $k['hour'] = 'H'.$i;
                    $k['value'] = $RHLD[$years][$i];  //value + demand
                    foreach ($this->tech[$years] as $tec){
                        if (!in_array($tec, Constant::HourlyAnalysisTech) && $tec != 'ImportExport' && $tec != 'Storage') {
							if($pMaintenance){
								$k[$tec] = $HM[$years][$tec][$i];
							}
							else{
								$k[$tec] = 0;
							}
                        }
                        else if($tec != 'ImportExport'&& $tec != 'Storage'){
                            $k[$tec] = 0;
                        }
                    }
                    $this->MCLASS_order_h_cp[$years]['HourlyData'][$i] =  $k;
                 }
            }
		}
		return $this->MCLASS_order_h_cp;
    }
    
    //instalirana snaga P1  u satima
	public function getTIC_h(){
		if (isset($this->TIC_h) && empty($this->TIC_h)) {
            $TIC = $this->TIC;
			foreach($this->yrs as $years){   
                foreach ($this->tech[$years] as $tec){
                    if($tec != 'Storage'){
                        $this->TIC_h[$years][$tec] = array_fill(0, 8760, $TIC[$years][$tec]);
                        // for($i=0;$i<8760;$i++){
                        //     //$TIC_h[$years][$tec] = array_fill(0, 8760, $TIC[$tec][$years]);
                        //     $this->TIC_h[$years][$tec][$i] = $TIC[$tec][$years];
                        // }
                    }
                }
            }
        }
		return $this->TIC_h;
	}

    //izracunati Total Available Installed Power 
	public function getTAIC_cp($pMaintenance){
		if (isset($this->TAIP) && empty($this->TAIP)) {
            $RHD = $this->getRHLD();
            //$RHD = $this->getRHD();
            $TICh = $this->getTIC_h();
			if($pMaintenance){
                //$M_order = $this->getMCLASS_order_h();
                //potebno je citati iz vec napravljenog niza inace dupla vrijednost za odrzavanje
                $M_order = $this->MCLASS_order_h;
            }
			//$HG = $this->getHG();
			foreach($this->yrs as $years){   
			     for($i=0;$i<8760;$i++){
                    $k['hour'] = 'H'.$i;
                    $k['value'] = $RHD[$years][$i];  //value + demand
                    foreach ($this->tech[$years] as $tec){
                        if (!in_array($tec, Constant::HourlyAnalysisTech) && $tec !='ImportExport' && $tec != 'Storage') {
							if($pMaintenance){
								$k[$tec] = $TICh[$years][$tec][$i] - $M_order[$years][$tec][$i];
							}
							else{
								$k[$tec] = $TICh[$years][$tec][$i];
							}
                        }
                        else if($tec != 'ImportExport'&& $tec != 'Storage'){
                            //intsalirana snaga i za intermitent, ne treba generacija
                            //$k[$tec] = $TICh[$years][$tec][$i];
                            //$k[$tec] = $HG[$years][$tec][$i];
                            $k[$tec] = 0;
                        }
                    }
                    $this->TAIP[$years]['HourlyData'][$i] =  $k;
                 }
            }
        }
		return $this->TAIP;
	}
}


// $hData = new Maintenance('esst IDC_copy');



// echo " MDLong";
// echo "<pre>";
// print_r($hData->MDLong);
// echo "</pre>";


// echo " MDShort";
// echo "<pre>";
// print_r($hData->MDShort);
// echo "</pre>";


// echo " getMSPACE_short";
// echo "<pre>";
// print_r($hData->getMSPACE_short());
// echo "</pre>";

// echo " getMCLASS";
// echo "<pre>";
// print_r($hData->getMCLASS());
// echo "</pre>";


?>