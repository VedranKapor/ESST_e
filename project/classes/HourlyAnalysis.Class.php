<?php
/////////////////////////////////////////////////////////////////////// Hourly Analysis CLASS /////////////
//*********************************************************************************************************
//*********************************************************************************************************

//*********************************************************************** CREATED BY VK *******************
//********************************************************************* esst ver 2.0 2016 *****************
///////////////////////////////////////////////////////////////////////////////////////////////////////////

//define('ROOT_DIR','C:\wamp.3.2.0\www\ESST\esst.ver.2.7.0');
//error_reporting(E_ALL ^ E_WARNING);
//require_once ROOT_DIR.'/classes/EsstCase.php';
require_once ROOT_DIR.'/classes/EsstCase.Class.php';
require_once ROOT_DIR.'/classes/Const.Class.php';
require_once ROOT_DIR.'/functions/xml_functions.php';
require_once ROOT_DIR.'/classes/paths.php';
ini_set('max_execution_time', 300);
ini_set('memory_limit', -1);
//ini_set('xdebug.max_nesting_level', 512);

// class HourlyData extends EsstCaseClass {
    class HourlyData  {


    //protected $MaintenanceUnitSize = array("Nuclear"=>1000,"Coal"=>400,"Gas"=>300,"Oil"=>200,"OilShale"=>200,"Peat"=>200,"Waste"=>100,"Biofuels"=>100,"Geothermal"=>100 );

    protected $EDgwh = array();
    //protected $elImportMW = array();
    //protected $elMixFuels = array();
    protected $TIC = array();
    protected $DP = array();
    protected $TICD_p1 = array();  //Total InstalledCapacity Dispatchable phase 1
    protected $ELMIX = array();
  	// protected $HP_Demand;
    // protected $HP_Wind;
	// protected $HP_Solar;
    // protected $HP_Hydro;

   	public $yrs  = array();
	public $tech  = array();
    protected $MD  = array();
    protected $MUS = array();
    protected $FOR = array();

    // protected $MaxHDP = array(); //maximum of Hourly Pattern
    // protected $MaxHP = array(); //maximum of Hourly Pattern
    // protected $SumHDP = array(); //Suma Hourly Pattern
    // protected $SumHP = array(); //Suma Hourly Pattern
    protected $LF     = array();
    protected $PMAX   = array();
    
    protected $Unit;
    protected $Sectors      = array();
    protected $TPES         = array();
    protected $TFC          = array();
    protected $PEEG         = array();
    protected $CarbonCost   = array();
    protected $Eff          = array();
    protected $EF           = array();
    protected $LT           = array();
    protected $DR           = array();
    protected $Cost         = array();
    protected $TAC          = array();
    public $elImportMW   = array();

    //protected $CF1 = array();    // Racunanje CF Capacity Facto
    // protected $NHP = array(); //Normalized Hourly Pattern
    // protected $NHDP   = array();

    protected $HDp = array(); // Hourly Demand from  Pattern
    protected $HGp   = array();  //Horly Generation from pattern
    protected $hDATA   = array();  //Horly data PMAX, CF, LF, DISPATCH, YEARS

    protected $HD     = array();
    protected $RHD    = array();
    protected $DF    = array();
    protected $CHG_yh = array();
    protected $STG = array();
    protected $STGparam = array();

    protected $RHD_tl = array();
    protected $HG = array(); //Hourly Generation by Tech
    protected $initHG = array(); // initial Hourly Generation by Tech bez maintenance bez storage samo umanjen za eventualni Deration
    protected $initRHD_tl = array();
    protected $initCHG_tl = array();

    protected $stgHG = array(); //  Hourly Generation korisiti se za izracun storage, inicijalni HG minus odrzavanje
    protected $stgRHD_tl = array();
    protected $stgCHG_tl = array();

    protected $CHG = array(); //Courtailed Hourly Generation by Tech
    protected $TCHG_tl = array(); //Courtailed Hourly Generation by Tech
    protected $SUM_RHG = array(); //Suma Hourly Pattern
    protected $SUM_CHG_yt = array(); //Suma Hourly Pattern
    protected $SUM_RHD_yt = array(); //Suma Hourly PatternSUM_RHD_yt
    protected $SUM_RHG_y = array(); //Suma Hourly Pattern
    protected $SUM_RHG_yh = array(); //Suma Hourly Pattern
    protected $SUM_HG = array(); //Suma Hourly Pattern
    protected $SUM_RHD = array(); //Suma Hourly Pattern
    protected $SUM_HD = array(); //Suma Hourly Pattern
    protected $RHG = array(); //Resulting Hourly Generation = HG-CHG  by Tech
    protected $RCF = array(); //Resulting Capacity Factor

    protected $RHDcp = array();
    protected $RHGcp = array(); //Resulting Hourly Generation = HG-CHG  by Tech

    protected $MHG = array();
    protected $DHG = array();
    protected $UD = array();
    protected $CHGStat = array(); //Courtailed Hourly Generation by Tech
    //protected $technology = array();

    protected $OutputElMix = array();
    protected $ElMixSharesP2 = array();
    protected $HDValues = array();

    //protected $LCOE = array();

    protected $CF = array();    // Racunanje CF Capacity Factor
    protected $CF1 = array();    // Racunanje CF Capacity Facto

    //protected $Loading = array();

    public $MCLASS_order_h = array();
    //protected $HGCount;
    protected $hSimulation;
    //protected $storageCap;
    //protected $storageFlag;
    //public $hMaintenance;

	public function __construct($pCase){

       // parent::__construct($pCase);

        // $this->EDgwh = $this->getSESP2('Electricity');
        // $this->elImportMW = $this->getElImport();
        // $this->ElmixSharesPhase1 = $this->getSesElmixP2();
        // $this->TIC = $this->getTIC();
        // $this->TICD_p1 = $this->getTICDy();

        // $this->EDgwh = $this->getElD_y();
        // //$this->elImportMW = $this->getElImport_y_mw();
        // //$this->ElmixSharesPhase1 = $this->getSESElMix_yt();
        // $this->TIC = $this->getTIC_yt();
        // $this->TICD_p1 = $this->getTDC_y();

        $this->hSimulation = XML.$_SESSION['us'].'/'.$pCase."/hSimulation";

        // if (file_exists ( $this->hSimulation )){

            $urlHDp = $this->hSimulation."/HDp.json";
            $contentHDp = file_get_contents( $urlHDp );
            $this->HDp = json_decode($contentHDp, true);

            $urlHGp = $this->hSimulation."/HGp.json";
            $contentHGp = file_get_contents( $urlHGp );
            $this->HGp = json_decode($contentHGp, true);

            $urlhDATA = $this->hSimulation."/genData.json";
            $contenthDATA = file_get_contents( $urlhDATA );
            $this->hData = json_decode($contenthDATA, true);

            $urlHM = $this->hSimulation."/HM.json";
            $contentHM = file_get_contents( $urlHM );
            $this->MCLASS_order_h = json_decode($contentHM, true);

            $this->yrs = $this->hData['YEARS'];
            $this->tech= $this->hData['DISPATCH'];
            $this->MD = $this->hData['MD'];
            $this->MUS = $this->hData['MUS'];
            $this->LF = $this->hData['LF'];
            $this->PMAX = $this->hData['PMAX'];
            $this->CF1 = $this->hData['CFp']; //CF iz hourly pattern
            $this->EDgwh = $this->hData['EDgwh'];
            $this->TIC = $this->hData['TIC'];
            $this->TICD_p1 = $this->hData['TDC'];
            $this->CF = $this->hData['CF'];
            $this->FOR = $this->hData['FOR'];

            $this->Unit          = $this->hData['UNIT'];
            $this->Sectors       = $this->hData['Sectors'];
            $this->TPES          = $this->hData['TPES'];
            $this->TFC           = $this->hData['TFC'];
            $this->ELMIX         = $this->hData['ELMIX'];
            $this->PEEG          = $this->hData['PEEG'];
            $this->CarbonCost    = $this->hData['CarbonCost'];
            $this->Eff           = $this->hData['Eff'];
            $this->EF            = $this->hData['EF'];
            $this->LT            = $this->hData['LT'];
            $this->DR            = $this->hData['DR'];
            $this->Cost          = $this->hData['Cost'];
            $this->TAC           = $this->hData['TAC'];
            $this->elImportMW    = $this->hData['elImportMW'];
            $this->STGparam      = $this->hData['STG'];

            $this->pCase = $pCase;
            //$this->HGCount = 1;
            //$this->storageCap = 5000;
           // $this->storageFlag = false;

	}

    //HD Hourly Demand je jednak HD patternu iz hPattern clase
	public function getHD(){
		if (isset($this->HD) && empty($this->HD)) {
            //$NHDP = $this->getNHDP();
            //$PMAX = $this->getPMAX();
			// foreach($this->yrs as $year){
			// 	$this->HD[$year] = array_map( function($val, $j) { return $val * $j; }, $this->NHDP[$year], array_fill(0, 8760, $this->PMAX[$year] ) );
            // }
            $this->HD = $this->HDp;
		}
		return $this->HD;
	}

       //izracunati  RHD Resulting Hourly Demand => Hourly Demand - Import
       //18.07.2019. Mario odlucio da Demand ne treba umanjivati za Import/Export RHD postaje HD
       //19.07.2019 potrebno je samo dodati export na demand a import ne oduzimati vec tretirati kao tehnologiju koju cemo dispatchirati....
	public function getRHD(){
		if (isset($this->RHD) && empty($this->RHD)) {
            // $HD =$this->getHD();
            $HD =$this->HDp;
			// foreach($this->yrs as $year){
			//      $this->RHD[$year]= array_map( function($val, $i) { return $val - $i; }, $HD[$year],array_fill(0, 8760, $this->elImportMW[$year]));
            // }
            $this->RHD = $HD;
		}
		return $this->RHD;
	}

    //Derated Power NE KORISTI SE
    /*
    public function getDeratedPower(){
        if (isset($this->DP) && empty($this->DP)){
            foreach($this->tech as $tech){
                if (!in_array($tech, $this->HourlyAnalysisTech)) {
                    foreach($this->yrs  as $year){
                        if (in_array($tech, $this->MDLong)) {
                            //$CFmor = 100 - 730 * $MCLASS[$year][$tech] * 100 / 8760;
                            //$CFmor = 100;
                            $CFmor = 100 - 730 * 100 / 8760;
                            if($CFmorCF2 > $this->CF1[$year][$tech]){
                                $factor = $this->CF1[$year][$tech] / $CFmor;
                                $this->DP[$tech][$year] = $this->TIC[$year][$tech] * $factor;
                            }
                            else{
                                $this->DP[$tech][$year] = $this->TIC[$year][$tech];
                            }
                        }
                        else{
                            //$CFmor = 100 - 360 * $MCLASS[$year][$tech] *100 / 8760;
                            //$CFmor = 100;
                            $CFmor = 100 - 365 * 100 / 8760;
                            if($CFmor > $this->CF1[$year][$tech]){
                                $factor = $this->CF1[$year][$tech] / $CFmor;
                                $this->DP[$tech][$year] = $this->TIC[$year][$tech] * $factor;
                            }
                            else{
                                $this->DP[$tech][$year] = $this->TIC[$year][$tech];
                            }
                        }
                    }
                }
            }
        }
        return $this->DP;
    }
    */

    //Derated factor
    // public function getDeratedFactor(){
    //     if (isset($this->DF) && empty($this->DF)) {
    //         foreach($this->yrs  as $year){
    //             foreach($this->tech[$year] as $tech){
    //                 if (!in_array($tech, $this->HourlyAnalysisTech) && $tech != 'Storage' && $tech != 'ImportExport'){
    //                     if (in_array($tech, $this->MDLong)) {
    //                         $CFmor = 100 - 730 * 100 / 8760;
    //                         if($CFmor > $this->CF1[$year][$tech]){
    //                             //$factor = $this->CF1[$year][$tech] / $CFmor;
    //                             //$DF[$tech][$year] =  $factor;
    //                             $this->DF[$year][$tech] =  1;
    //                         }
    //                         else{
    //                             $this->DF[$year][$tech] =  1;
    //                         }
    //                     }
    //                     else{
    //                         $CFmor = 100 - 365 * 100 / 8760;
    //                         if($CFmor > $this->CF1[$year][$tech]){
    //                             //$factor = $this->CF1[$year][$tech] / $CFmor;
    //                             //$DF[$tech][$year] = $factor;
    //                             $this->DF[$year][$tech] =  1;
    //                         }
    //                         else{
    //                             $this->DF[$year][$tech] =  1;
    //                         }
    //                     }
    //                 }
    //                 else if( $tech == 'ImportExport'){
    //                     $this->DF[$year][$tech] =  1;
    //                 }
    //             }

    //         }
    //     }
    //     return $this->DF;
    // }

    //Derated factor
    public function getDeratedFactor(){
        if (isset($this->DF) && empty($this->DF)) {
            foreach($this->FOR  as $tec=>$for){
                $this->DF[$tec] = (100 - $for) / 100;
            }
            //$this->DF['ImportExport'] = 1;
        }
        return $this->DF;
    }

    //inicijalna generacija je jednaka HG iz hPattern-a samo umanjena za DF ukoliko je zadat, storage u incijalnoj generaciji je jendak 0
    public function initHG(){
        if (isset($this->initHG) && empty($this->initHG)) {
            $DF = $this->getDeratedFactor();
            foreach($this->yrs as $year){
                foreach ($this->tech[$year] as $tec){
                    if (in_array($tec, Constant::HourlyAnalysisTech)) {
                        $this->initHG[$year][$tec] = $this->HGp[$year][$tec];
                    }
                    else if($tec != 'Storage' && $tec != 'ImportExport'){
                        $this->initHG[$year][$tec] = array_map( function($HGp, $DF) {return ($HGp)*$DF; },  $this->HGp[$year][$tec], array_fill(0, 8760, $DF[$tec]) );
                    }
                    else if($tec == 'ImportExport'){
                        $this->initHG[$year][$tec] = $this->HGp[$year][$tec];
                    }
                    else if($tec == 'Storage'){
                        $this->initHG[$year][$tec] =  array_fill(0, 8760, 0);
                    }
                }

            }
        }
        return $this->initHG;
    }

    public function initRHD_tl(){
        if (isset($this->initRHD_tl) && empty($this->initRHD_tl)) {
            $this->initRHD_tl = $this->getRHD();
            $HG = $this->initHG();
            foreach($this->yrs as $year){
                foreach ($this->tech[$year] as $tec){
                    $initTRHD_tl[$year][$tec] = array_map( function($val, $j) { return $val - $j; }, $this->initRHD_tl[$year], $HG[$year][$tec]);
                    $this->initRHD_tl[$year] = array_map( function($val) { return $val > 0 ? $val : 0; }, $initTRHD_tl[$year][$tec]); //unserved energy after W,S,H
                }
            }
        }
        return $this->initRHD_tl;
    }

    //izracunbati curtailed power by tech dispatch order
    //slicno kao predhodna funkcija getRHD_tl() samo ovdje pamtimo negativne vrijednosti koje predstavljaju Courtailment odnosto Underutilization
    public function initCHG_tl(){
        if (isset($this->initCHG_tl) && empty($this->initCHG_tl)) {
            $RHD_tl = $this->getRHD();  //vec je inicijaliziran jer smo prvo racunali RHD
            $HG = $this->initHG();
            foreach($this->yrs as $year){
                foreach ($this->tech[$year] as $tec){
                    $TCHG_tl[$year][$tec] = array_map( function($val, $j) { return $val - $j; }, $RHD_tl[$year], $HG[$year][$tec]);
                    $RHD_tl[$year] = array_map( function($val) { return $val > 0 ? $val : 0; }, $TCHG_tl[$year][$tec]);
                    $this->initCHG_tl[$year][$tec] = array_map( function($val) { return $val < 0 ? -$val : 0; }, $TCHG_tl[$year][$tec]);
                }
            }
        }
        return $this->initCHG_tl;
    }


    //HG za izracun Storage, toje inicijalni HG initHG sa oduzimanjem odrzavanja ukoliko je odabrano
    public function stgHG(){
        if (isset($this->stgHG) && empty($this->stgHG)) {
            $initHG = $this->initHG();
            foreach($this->yrs as $year){
                foreach ($this->tech[$year] as $tec){
                    if (in_array($tec, Constant::HourlyAnalysisTech)) {
                        $this->stgHG[$year][$tec] = $initHG[$year][$tec];
                    }
                    else if($tec != 'Storage' && $tec != 'ImportExport'){
                        $this->stgHG[$year][$tec] = array_map( function($HGp, $MP ) {return ($HGp-$MP); },  $initHG[$year][$tec], $this->MCLASS_order_h[$year][$tec] );
                    }
                    else if($tec == 'ImportExport'){
                        $this->stgHG[$year][$tec] = $initHG[$year][$tec];
                    }
                    else if($tec == 'Storage'){
                        $this->stgHG[$year][$tec] =  array_fill(0, 8760, 0);
                    }

                }

            }
        }
        return $this->stgHG;
    }

    //curtilment za HG koji se koristi kao ulaz za izracun storage
    public function stgCHG_tl(){
        if (isset($this->stgCHG_tl) && empty($this->stgCHG_tl)) {
            $RHD_tl = $this->getRHD();  //vec je inicijaliziran jer smo prvo racunali RHD
            $HG = $this->stgHG();
            foreach($this->yrs as $year){
                foreach ($this->tech[$year] as $tec){
                    $TCHG_tl[$year][$tec] = array_map( function($val, $j) { return $val - $j; }, $RHD_tl[$year], $HG[$year][$tec]);
                    $RHD_tl[$year] = array_map( function($val) { return $val > 0 ? $val : 0; }, $TCHG_tl[$year][$tec]);
                    $this->stgCHG_tl[$year][$tec] = array_map( function($val) { return $val < 0 ? -$val : 0; }, $TCHG_tl[$year][$tec]);
                }
            }
        }
        return $this->stgCHG_tl;
    }

    public function stgRHD_tl(){
        if (isset($this->stgRHD_tl) && empty($this->stgRHD_tl)) {
            $this->stgRHD_tl = $this->getRHD();
            $HG = $this->stgHG();
            foreach($this->yrs as $year){
                foreach ($this->tech[$year] as $tec){
                    $initTRHD_tl[$year][$tec] = array_map( function($val, $j) { return $val - $j; }, $this->stgRHD_tl[$year], $HG[$year][$tec]);
                    $this->stgRHD_tl[$year] = array_map( function($val) { return $val > 0 ? $val : 0; }, $initTRHD_tl[$year][$tec]); //unserved energy after W,S,H
                }
            }
        }
        return $this->stgRHD_tl;
    }

    //Samo Curtailment H, s, W koristi se za Storage, suma CHG po satu
    //ukupni curtailment u odrdjenom satu iz H, W, S
    public function getCHG_yh(){
        if (isset($this->CHG_yh) && empty($this->CHG_yh)) {
            //$CHG = $this->initCHG_tl();
            $CHG = $this->stgCHG_tl();
            foreach($this->yrs as $year){
                for($h=0;$h<8760;$h++){
                    $this->CHG_yh[$year][$h] = 0;
                    foreach ($this->tech[$year] as $tec){
                        if (in_array($tec, Constant::HourlyAnalysisTech)) {
                            $this->CHG_yh[$year][$h] = $this->CHG_yh[$year][$h] + $CHG[$year][$tec][$h];
                        }
                    }
                }
            }
        }
		return $this->CHG_yh;
	}

    //napuni capacitet za STORAGE od curtailmenta
    //Storage se puni nakon prvog kruga simulacije kada znamo tacno koliko ima curtailmenta, a generise samo kada ima UD.
    //ne moze se koristiti kao dispatch jer ako je u jednom satu potrosi odredjena kolicina storage onda tu vrijednos treba prpagirati i u sljedece sate.
    //napunjeni STG je kumulativan. U jednom prolazu se racuna i kapacitet i generacija
    //da bi se koristio umjesto neke druge tehnologije treba to znati unaprijed i izracunati u getSTg funciji kad ce se storage koristiti umjesto neke tehnologije a ne samo za UD
    public function getSTG(){
        if (isset($this->STG) && empty($this->STG)) {

            $CHG = $this->getCHG_yh();
            $RDH = $this->stgRHD_tl();

            foreach($this->yrs as $year){

                $storageCapacityLimit = $this->STGparam[$year]['CAP'];
                $storageEff = $this->STGparam[$year]['Eff'] / 100;
                $storageVolume = $this->STGparam[$year]['VOL'] * 1000; //da dobijemo MW radi usporedbe sa Cap koji je u MW
                $storageCapacityInit = ( $this->STGparam[$year]['INIT'] / 100 ) * $storageVolume;
                $storageLosses = ( 100 -  $this->STGparam[$year]['LOS'] / 8760 ) / 100;
                //inicijalno stanje storage
                $STG_cap[$year] = array_fill(0, 8760, 0);
                $STG_gen[$year] = array_fill(0, 8760, 0);

                if($storageCapacityLimit != 0 && $storageVolume!=0){
                    for($h=0;$h<8760;$h++){

                        $UnservedDemand_yh = $RDH[$year][$h];
                        $Curtailment_yh = $CHG[$year][$h];
    
                        //ako imamo UD onda koristimo Storage
                        if($UnservedDemand_yh > 0){
                            //prvi sat u godini resetujemo stanje storage na 0
                            if($h == 0){
                                // $STG_cap[$year][$h] = $storageCapacityInit;
                                // $STG_gen[$year][$h] = 0;
                                if( $storageCapacityInit >= $storageCapacityLimit){
                                    //ako imamo u storegu vise negi sto nam treba 
                                    if( $storageCapacityInit >= $UnservedDemand_yh){
                                        //ako je ono sto storage moze dati u satu (storCapLimit * eff) vece od onoga sto se trazi UD
                                        if($storageCapacityLimit * $storageEff >= $UnservedDemand_yh){
                                            $STG_cap[$year][$h] = ( $storageCapacityInit - ($UnservedDemand_yh + ($UnservedDemand_yh - $UnservedDemand_yh * $storageEff)) ) * $storageLosses;
                                            $STG_gen[$year][$h] = $UnservedDemand_yh;
                                        }
                                        else{
                                            $STG_cap[$year][$h] = ( $storageCapacityInit - $storageCapacityLimit ) * $storageLosses;
                                            $STG_gen[$year][$h] = $storageCapacityLimit * $storageEff;
                                        }
                                    }
                                    else{
                                        //ovo nikad nece biti jer je VOL veci od CAP a ako je VOL manji od UD onda je i CAP sigurno manji od UD
                                        //tako da imamo samo sljedeci slucaj kad je UD najveci i od VOL i od CAP VOL je veci od CAP
                                        $STG_cap[$year][$h] = ( $storageCapacityInit - $storageCapacityLimit ) * $storageLosses;
                                        $STG_gen[$year][$h] = $storageCapacityLimit * $storageEff;
                                    }
                                }
                                //dio kad je VOl manji od CAP
                                else{
                                    if( $storageCapacityInit * $storageEff >= $UnservedDemand_yh){
                                        $STG_cap[$year][$h] = ( $storageCapacityInit - ($UnservedDemand_yh + ($UnservedDemand_yh - $UnservedDemand_yh * $storageEff)) ) * $storageLosses;
                                        $STG_gen[$year][$h] = $UnservedDemand_yh;
                                    }
                                    else{
                                        $STG_cap[$year][$h] = 0;
                                        $STG_gen[$year][$h] = $storageCapacityInit * $storageEff;
                                    }
                                    
                                }  
                            }
                            if($h != 0){
                                if( $STG_cap[$year][$h-1] >= $storageCapacityLimit){
                                    //ako imamo u storegu vise negi sto nam treba 
                                    if( $STG_cap[$year][$h-1] >= $UnservedDemand_yh){
                                        //ako je ono sto storage moze dati u satu (storCapLimit * eff) vece od onoga sto se trazi UD
                                        if($storageCapacityLimit * $storageEff >= $UnservedDemand_yh){
                                            $STG_cap[$year][$h] = ( $STG_cap[$year][$h-1] - ($UnservedDemand_yh + ($UnservedDemand_yh-$UnservedDemand_yh * $storageEff)) ) * $storageLosses;
                                            $STG_gen[$year][$h] = $UnservedDemand_yh;
                                        }
                                        else{
                                            $STG_cap[$year][$h] = ( $STG_cap[$year][$h-1] - $storageCapacityLimit ) * $storageLosses;
                                            $STG_gen[$year][$h] = $storageCapacityLimit * $storageEff;
                                        }
                                    }
                                    else{
                                        //ovo nikad nece biti jer je VOL veci od CAP a ako je VOL manji od UD onda je i CAP sigurno manji od UD
                                        //tako da imamo samo sljedeci slucaj kad je UD najveci i od VOL i od CAP VOL je veci od CAP
                                        $STG_cap[$year][$h] = ( $STG_cap[$year][$h-1] - $storageCapacityLimit ) * $storageLosses;
                                        $STG_gen[$year][$h] = $storageCapacityLimit * $storageEff;
                                    }
                                }
                                //dio kad je VOl manji od CAP
                                else{
                                    if( $STG_cap[$year][$h-1] * $storageEff >= $UnservedDemand_yh){
                                        $STG_cap[$year][$h] = ( $STG_cap[$year][$h-1] - ($UnservedDemand_yh + ($UnservedDemand_yh-$UnservedDemand_yh * $storageEff)) ) * $storageLosses;
                                        $STG_gen[$year][$h] = $UnservedDemand_yh;
                                    }
                                    else{
                                        $STG_cap[$year][$h] = 0;
                                        $STG_gen[$year][$h] = $STG_cap[$year][$h-1] * $storageEff;
                                    }
                                    
                                }    
                            }
                        }
                        //ako imao curtailment onda punimo storage
                        else if ($Curtailment_yh > 0) {
                            //prvi sat u godini
                            if($h == 0) {
                                //echo "year " . $year . " tech " . $tec . " h " . $h . " curtaiment " . $Curtailment_yth . "<br>";
                                if($storageCapacityLimit >= $Curtailment_yh * $storageEff){
                                    $STG_cap[$year][$h] =  ( $storageCapacityInit + $Curtailment_yh * $storageEff) * $storageLosses;
                                    $STG_gen[$year][$h] = 0;
                                }else{
                                    $STG_cap[$year][$h] = ($storageCapacityInit + $storageCapacityLimit * $storageEff) * $storageLosses;
                                    $STG_gen[$year][$h] = 0;
                                }
                            }
                            else if ($h != 0) {
                                if($storageCapacityLimit >= $Curtailment_yh * $storageEff){
                                    //ovo je dio gdje treba provjeravatu volumne storage
                                    //if( ($Curtailment_yh +  $STG_cap[$year][$h-1]) < $storageCapacityLimit){
                                    //ako u volumen storage mozemo staviti potencijalni novi Curtailment umanjen za efikasnost i ono sto imamom u storage u prethodnom satu
                                    
                                    //ispitujemo da li volumen storage veci od eventualnog dodatka (Curtailment*Eff) i onoga sto imamo u prethodnom satu
                                    if( $storageVolume >= ($Curtailment_yh * $storageEff +  $STG_cap[$year][$h-1]  ) ){
                                        $STG_cap[$year][$h] = $Curtailment_yh * $storageEff * $storageLosses + $STG_cap[$year][$h-1]; //dodajemo prethondi sat na koji je vec dodan losses
                                        $STG_gen[$year][$h] = 0;
                                    }else{
                                        $STG_cap[$year][$h] = ($storageVolume - $STG_cap[$year][$h-1]) * $storageEff * $storageLosses + $STG_cap[$year][$h-1];
                                        $STG_gen[$year][$h] = 0;
                                    }
                                }else{
                                    // $STG_cap[$year][$h] = $storageCapacityLimit * $storageEff;;
                                    // $STG_gen[$year][$h] = 0;
                                    if( $storageVolume >= ($storageCapacityLimit * $storageEff +  $STG_cap[$year][$h-1]) ){
                                        $STG_cap[$year][$h] = $storageCapacityLimit * $storageEff * $storageLosses + $STG_cap[$year][$h-1]; //+ $CurtailmentPrevious_yth;
                                        $STG_gen[$year][$h] = 0;
                                    }else{
                                        $STG_cap[$year][$h] = ($storageVolume - $STG_cap[$year][$h-1]) * $storageEff * $storageLosses + $STG_cap[$year][$h-1];
                                        $STG_gen[$year][$h] = 0;
                                    }
                                }
                            }
                        }
                        //ako nemamo ni curtailment ni Ud onda je  generacija 0
                        //volumne je isti kao u prethodnom satu umanjen za losses
                        else{
                            if($h == 0) {
                                $STG_cap[$year][$h] = $storageCapacityInit * $storageLosses;
                                $STG_gen[$year][$h] = 0; 
                            }else{
                                $STG_cap[$year][$h] =  $STG_cap[$year][$h-1] * $storageLosses;
                                $STG_gen[$year][$h] = 0;
                            }
                        }
                    }
                }
            }
            $this->STG['Curtailment'] =  $STG_cap;
            $this->STG['Generation'] = $STG_gen;
        }
        return $this->STG;
    }

    //radi 04282020 prije implementacije STORAGE params
    // public function getSTG(){
    //     if (isset($this->STG) && empty($this->STG)) {
    //         // $storageCapacityLimit = $this->storageCap;
    //         $storageCapacity = 0;
    //         $CHG = $this->getCHG_yh();
    //         $RDH = $this->stgRHD_tl();

    //         foreach($this->yrs as $year){
    //             $storageCapacityLimit = $this->STGparam[$year]['CAP'];
    //             $STG_cap[$year] = array_fill(0, 8760, 0);
                
    //             for($h=0;$h<8760;$h++){
    //                 $UnservedDemand_yh = $RDH[$year][$h];
    //                 $Curtailment_yh = $CHG[$year][$h];
    //                 if($UnservedDemand_yh > 0){
    //                     if($h == 0){
    //                         $STG_cap[$year][$h] = 0;
    //                         $STG_gen[$year][$h] = 0;
    //                     }
    //                     if($h != 0){
    //                         if($UnservedDemand_yh <= $STG_cap[$year][$h-1]){
    //                             $STG_cap[$year][$h] = $STG_cap[$year][$h-1] - $UnservedDemand_yh;
    //                             $STG_gen[$year][$h] = $UnservedDemand_yh;
    //                         }
    //                         else{
    //                             $STG_cap[$year][$h] = 0;
    //                             $STG_gen[$year][$h] = $STG_cap[$year][$h-1];
    //                         }
    //                     }
    //                 }
    //                 else if ($Curtailment_yh >= 0) {
    //                     //punjenje storage akoima curtailmenta
    //                     if($h == 0) {
    //                         //echo "year " . $year . " tech " . $tec . " h " . $h . " curtaiment " . $Curtailment_yth . "<br>";
    //                         if($storageCapacityLimit >= $Curtailment_yh){
    //                             $STG_cap[$year][$h] =  $Curtailment_yh;
    //                             $STG_gen[$year][$h] = 0;
    //                         }else{
    //                             $STG_cap[$year][$h] = $storageCapacityLimit;
    //                             $STG_gen[$year][$h] = 0;
    //                         }
    //                     }
    //                     else if ($h != 0) {
    //                         if($storageCapacityLimit >= $Curtailment_yh){
    //                             if( ($Curtailment_yh +  $STG_cap[$year][$h-1]) < $storageCapacityLimit){
    //                                 $STG_cap[$year][$h] = $Curtailment_yh + $STG_cap[$year][$h-1]; //+ $CurtailmentPrevious_yth;
    //                                 $STG_gen[$year][$h] = 0;
    //                             }else{
    //                                 $STG_cap[$year][$h] = $storageCapacityLimit;
    //                                 $STG_gen[$year][$h] = 0;
    //                             }
    //                         }else{
    //                             $STG_cap[$year][$h] = $storageCapacityLimit;
    //                             $STG_gen[$year][$h] = 0;
    //                         }
    //                     }
    //                 }
    //                 else{
    //                     //$STG_cap[$year][$h] = 0;
    //                     $STG_gen[$year][$h] = 0;
    //                 }
    //             }
    //         }
    //         $this->STG['Curtailment'] =  $STG_cap;
    //         $this->STG['Generation'] = $STG_gen;
    //     }
    //     return $this->STG;
    // }


    public function getHG($pStorage){
        if (isset($this->HG) && empty($this->HG)) {
            $initHG = $this->initHG();
            if($pStorage){
                $STG = $this->getSTG();
            }
            foreach($this->yrs as $year){
                foreach ($this->tech[$year] as $tec){
                    if (in_array($tec, Constant::HourlyAnalysisTech)) {
                        $this->HG[$year][$tec] = $initHG[$year][$tec];
                    }
                    else if($tec != 'Storage' && $tec != 'ImportExport'){
                        $this->HG[$year][$tec] = array_map( function($HGp, $MP ) {return ($HGp-$MP); },  $initHG[$year][$tec], $this->MCLASS_order_h[$year][$tec] );
                    }
                    else if($tec == 'ImportExport'){
                        $this->HG[$year][$tec] = $initHG[$year][$tec];
                    }
                    else if($pStorage && $tec == 'Storage'){
                        $this->HG[$year][$tec] = $STG['Generation'][$year];
                    }
                    else if(!$pStorage && $tec == 'Storage'){
                        $this->HG[$year][$tec] =  $initHG[$year][$tec];
                    }

                }

            }
        }
        return $this->HG;
    }

   	//izracunati RHD_tl resulting demand after technologies i loading order are deducted//ova funckija se koristi za izracun Unserver Demand
    //kroz petlju oduzima od Demand-a generaciju svake tehnologije i vidi koliko ostane nezadovoljenog demanda za sljedecu tehnologiju. Zavisi od Dispatch Order-a
    //kad se zadovolji  demand sve ostale vrijednosti su negativne i to znaci da nema Unserved Demand ukoliko zadnji prolaz kroz petlju da pozitivnu vrijednost znaci da postoji Unserveed Demand
	public function getRHD_tl($pStorage){
		if (isset($this->RHD_tl) && empty($this->RHD_tl)) {
		    $this->RHD_tl = $this->getRHD();
            $HG = $this->getHG($pStorage);
            foreach($this->yrs as $year){
                foreach ($this->tech[$year] as $tec){
                    if($tec != 'Storage'){
                        $TRHD_tl[$year][$tec] = array_map( function($val, $j) { return $val - $j; }, $this->RHD_tl[$year], $HG[$year][$tec]);
                        $this->RHD_tl[$year] = array_map( function($val) { return $val > 0 ? $val : 0; }, $TRHD_tl[$year][$tec]); //unserved energy after W,S,H
                    }
                    else if($pStorage && $tec == 'Storage'){
                        $tmp[$year] = array_map( function($val, $j) { return $val - $j; }, $this->RHD_tl[$year], $HG[$year][$tec]);
                        $this->RHD_tl[$year] = array_map( function($val) { return $val > 0 ? $val : 0; }, $tmp[$year]);
                    }
                    // else if(!$pStorage && $tec == 'Storage'){
                    //     $this->RHD_tl[$year] =  array_fill(0, 8760, 0);
                    // }
                }
            }
		}
		return $this->RHD_tl;
	}

    //izracunbati curtailed power by tech dispatch order
    //slicno kao predhodna funkcija getRHD_tl() samo ovdje pamtimo negativne vrijednosti koje predstavljaju Courtailment odnosto Underutilization
   	public function getCHG_tl($pStorage){
		if (isset($this->CHG) && empty($this->CHG)) {
		    $this->RHD_tl = $this->getRHD();  //vec je inicijaliziran jer smo prvo racunali RHD
            $HG = $this->getHG($pStorage);
			foreach($this->yrs as $year){
                foreach ($this->tech[$year] as $tec){
                    if($tec != 'Storage'){
                        $TCHG_tl[$year][$tec] = array_map( function($val, $j) { return $val - $j; }, $this->RHD_tl[$year], $HG[$year][$tec]);
                        $this->RHD_tl[$year] = array_map( function($val) { return $val > 0 ? $val : 0; }, $TCHG_tl[$year][$tec]);
                        $this->CHG[$year][$tec] = array_map( function($val) { return $val < 0 ? -$val : 0; }, $TCHG_tl[$year][$tec]);
                    }
                    else if($pStorage && $tec == 'Storage'){
                        $tmp[$year] = array_map( function($val, $j) { return $val - $j; }, $this->RHD_tl[$year], $HG[$year][$tec]);
                        $this->RHD_tl[$year] = array_map( function($val) { return $val > 0 ? $val : 0; }, $tmp[$year]);
                        $this->CHG[$year][$tec] = array_map( function($val) { return $val < 0 ? -$val : 0; }, $tmp[$year]);
                    }
                    else if(!$pStorage && $tec == 'Storage'){
                        $this->CHG[$year][$tec] =  array_fill(0, 8760, 0);
                    }
                }
            }
		}
		return $this->CHG;
	}

    //izracunati RHWG resulting hourly wind generation HourlyWindGeneration-CurtailedHourlyWindGeneration
    //hourly generation result po year, tech, hour
	public function getRHG($pStorage){
		if (isset($this->RHG) && empty($this->RHG)) {
		    $CHG = $this->getCHG_tl($pStorage);
            $HG =  $this->getHG($pStorage);
            foreach($this->yrs as $year){
		        foreach ($this->tech[$year] as $tec){
                    $this->RHG[$year][$tec] = array_map( function($val, $j) { return $val - $j; },$HG[$year][$tec], $CHG[$year][$tec]);
        		}
            }
        }
		return $this->RHG;
	}

    //izracunati  SUM_RHWG (energija iz paterna) za odredjenu godinu; Energy Contained in hourly Wind generation pattern //proizvodnja za godinu
	public function getSUM_RHG($pStorage){
		if (isset($this->SUM_RHG) && empty($this->SUM_RHG)) {
            $RHG = $this->getRHG($pStorage);
            foreach($this->yrs as $year){
		        foreach ($this->tech[$year] as $tec){
                    $this->SUM_RHG[$year][$tec] = array_sum($RHG[$year][$tec]);
        		}
            }
        }
		return $this->SUM_RHG;
    }

    /////////////////////////////////////////////////////za dio izvjestaj apo godinama
    /////////////////////////////////////////////////////imamo vec HG sumu iz funckije getSUM_RHG, potrebno dodati jos za UD i CHG
    public function getSUM_CHG_yt($pStorage){
		if (isset($this->SUM_CHG_yt) && empty($this->SUM_CHG_yt)) {
            $CHG = $this->getCHG_tl($pStorage);
            foreach($this->yrs as $year){
		        foreach ($this->tech[$year] as $tec){
                    $this->SUM_CHG_yt[$year][$tec] = array_sum($CHG[$year][$tec]);
        		}
            }
        }
		return $this->SUM_CHG_yt;
    }

    public function getSUM_RHD_yt($pStorage){
		if (isset($this->SUM_RHD_yt) && empty($this->SUM_RHD_yt)) {
            $RHD_tl = $this->getRHD_tl($pStorage);
            foreach($this->yrs as $year){
                $this->SUM_RHD_yt[$year] = array_sum($RHD_tl[$year]); //da dobijemoGWh
            }
        }
		return $this->SUM_RHD_yt;
    }

    //podijeliti RHLD niz na 12 perioda za odrzavanje
    public function getSUM_RHG_c($pStorage, $mChunk, $nPeriod){
        //if (isset($this->RHLD_p) && empty($this->RHLD_p)) {
            $RHG = $this->getRHG($pStorage);
            foreach($this->yrs as $year){
                foreach ($this->tech[$year] as $tech){
                    $tmp[$year][$tech] = array_chunk($RHG[$year][$tech], $mChunk);
                    for($i=1; $i<=$nPeriod; $i++){
                        $this->RHG_c[$year][$tech][$i] = array_sum($tmp[$year][$tech][$i-1]); 
                    }
                }
            }
        //}
        return $this->RHG_c;
    }

    //podijeliti RHLD niz na 12 perioda za odrzavanje
    public function getSUM_CHG_c($pStorage, $mChunk, $nPeriod){
        //if (isset($this->RHLD_p) && empty($this->RHLD_p)) {
            $CHG = $this->getCHG_tl($pStorage);
            foreach($this->yrs as $year){
                foreach ($this->tech[$year] as $tech){
                    $tmp[$year][$tech] = array_chunk($CHG[$year][$tech], $mChunk);
                    for($i=1; $i<=$nPeriod; $i++){
                        $this->CHG_c[$year][$tech][$i] = array_sum($tmp[$year][$tech][$i-1]); 
                    }
                }
            }
        //}
        return $this->CHG_c;
    }

    //podijeliti RHLD niz na 12 perioda za odrzavanje
    public function getSUM_RHD_c($pStorage, $mChunk, $nPeriod){
        //if (isset($this->RHLD_p) && empty($this->RHLD_p)) {
            $RHD_tl = $this->getRHD_tl($pStorage);
            foreach($this->yrs as $year){
                $tmp[$year] = array_chunk($RHD_tl[$year], $mChunk);
                for($i=1; $i<=$nPeriod; $i++){
                    $this->RHLD_c[$year][$i] = array_sum($tmp[$year][$i-1]); 
                }
            }
        //}
        return $this->RHLD_c;
    }

    ///////////////////////////////////////////////////////////////////////////kraj za reportByYears


    //izracunati  SUM_RHWG ukupna generacija svih tehnologija u jednom satu po godinama
	public function getSUM_RHG_yh($pStorage){
		if (isset($this->SUM_RHG_yh) && empty($this->SUM_RHG_yh)) {
            $RHG = $this->getRHG($pStorage);
            foreach($this->yrs as $year){
                for($i=0;$i<8760;$i++){
                    if(!isset($this->SUM_RHG_yh[$year][$i])){$this->SUM_RHG_yh[$year][$i] = 0;}
                    foreach ($this->tech[$year] as $tec){
                        $this->SUM_RHG_yh[$year][$i] = $this->SUM_RHG_yh[$year][$i] + $RHG[$year][$tec][$i];
                    }
                }
            }
        }
		return $this->SUM_RHG_yh;
    }
    
    
    //koristi se za izracun LCOE
    public function getSUM_RHG_y($pStorage){
		if (isset($this->SUM_RHG_y) && empty($this->SUM_RHG_y)) {
            $RHG_yt = $this->getSUM_RHG($pStorage);
            foreach($this->yrs as $year){
		        foreach ($this->tech[$year] as $tec){
                    if(!isset($this->SUM_RHG_y[$year])){$this->SUM_RHG_y[$year] = 0;}
                    $this->SUM_RHG_y[$year] = $this->SUM_RHG_y[$year] + $RHG_yt[$year][$tec]/1000;//da dobijemo GWh
        		}
            }
        }
		return $this->SUM_RHG_y;
	}

   	//izracunati  SUM_HG ukupn aprozivodnja po tehnolgojiji ukljucujuci i courtailment - NE KORSITI SE
	public function getSUM_HG(){
		if (isset($this->SUM_HG) && empty($this->SUM_HG)) {
            $HG = $this->getHG($pStorage);
            foreach($this->yrs as $year){
		        foreach ($this->tech[$year] as $tec){
                    $this->SUM_HG[$year][$tec] = array_sum($HG[$year][$tec]);
        		}
            }
		}
		return $this->SUM_HG;
	}

    //suma demanda po godini ukljhucujucu import/export
	public function getSUM_HD(){
		if (isset($this->SUM_HD) && empty($this->SUM_HD)) {
            $HD = $this->getHD();
			foreach($this->yrs as $year){
                $this->SUM_HD[$year] = array_sum($HD[$year]);
			}
		}
		return $this->SUM_HD;
	}

    //suma demanda po godini neukljucujuci import/export  --NE KORISTI SE
	// public function getSUM_RHD(){
	// 	if (isset($this->SUM_RHD) && empty($this->SUM_RHD)) {
    //         $RHD = $this->getRHD();
	// 		foreach($this->yrs as $year){
	// 			$this->SUM_RHD[$year] = 0;
	// 			foreach($RHD[$year] as $k1=>$v1){
	// 					$this->SUM_RHD[$year] = $this->SUM_RHD[$year] + $v1;
	// 				}
	// 		}
	// 	}
	// 	return $this->SUM_RHD;
	// }

    //godisnja proizvodnja iz svih tehnologija - NE KORISTI SE
	// public function getTotal_RHG_byYear(){
	// 	if (isset($this->Total_RHG) && empty($this->Total_RHG)) {
    //         $SUM_RHG = $this->getSUM_RHG();
	// 	    foreach($this->yrs as $year){
	// 	            $this->Total_RHG[$year] = 0;
    //                 foreach ($this->tech[$year] as $tec){
    //                     $this->Total_RHG[$year] = $this->Total_RHG[$year] + $SUM_RHG[$year][$tec];
    //     			}
    //         }
	// 	}
	// 	return $this->Total_RHG;
	// }

	//izracunati RCFW resulting hourly wind generation HourlyWindGeneration-CurtailedHourlyWindGeneration
	public function getRCF($pStorage){
		if (isset($this->RCF) && empty($this->RCF)) {
            $SUM_RHG = $this->getSUM_RHG($pStorage);
            foreach($this->yrs as $year){
		        foreach ($this->tech[$year] as $tec){
                    if($tec != 'Storage'){
                        if($this->TIC[$year][$tec]!=0){
                            $this->RCF[$year][$tec] = $SUM_RHG[$year][$tec] / ($this->TIC[$year][$tec] * 87.6);
                        }
                        else{
                            $this->RCF[$year][$tec] = 0;
                        }
                    }
                    // else{
                    //     $this->RCF[$year][$tec] = $SUM_RHG[$year][$tec] / ($this->storageCap * 87.6);
                    // }
                    else if($tec == 'Storage'){
                        if($this->STGparam[$year]['VOL'] !=0){
                            $this->RCF[$year][$tec] = $SUM_RHG[$year][$tec] / ($this->STGparam[$year]['VOL'] * 87.6);
                        }else{
                            $this->RCF[$year][$tec] = 0;
                        }
                        
                    }
                }
            }
		}
		return $this->RCF;
	}


///////////////////////////////////////////////////////////PRETY PRINT & CHART PRINT//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

   	//Result Hourly Demand by year CHART PRINT  RHD
	public function getRHDcp(){
		if (isset($this->RHDcp) && empty($this->RHDcp)) {
            $RHD = $this->getRHD();
            foreach($this->yrs as $year){
                for($i=0;$i<8760;$i++){
                    $m['hour'] = 'H'.$i;
                    $m['value'] = $RHD[$year][$i];
                    $this->RHDcp[$year]['HourlyData'][$i] =  $m;
                }
            }
		}
		return $this->RHDcp;
    }

           //Result Hourly Demand by year CHART PRINT  RHD
           //ovjde ako nije storage uzet u obzr mozemo brze naputnirit niz sa nulama
	public function getSTGcp($pStorage){
		if (!isset($this->STGcp) ) {
            if($pStorage){
                $STG = $this->getSTG()['Curtailment'];
            }
            foreach($this->yrs as $year){
                for($i=0;$i<8760;$i++){
                    $m['hour'] = 'H'.$i;
                    if($pStorage){
                        $m['value'] = $STG[$year][$i]; 
                    }else{
                        $m['value'] = 0;
                    }
                    $this->STGcp[$year]['HourlyData'][$i] =  $m;
                }
            }
		}
		return $this->STGcp;
	}


    //Result Hourly Generation by fuel,year CHART PRINT RHSG, RHWG, RHHG
    //04202020 sastavicemo H, W, S u jedan file
	// public function getRHGcp($pStorage){
	// 	if (isset($this->RHGcp) && empty($this->RHGcp)) {
    //         $RHG = $this->getRHG($pStorage);
	// 	    // foreach ($this->tech[$year] as $tec){
    //     	// 	foreach($RHG as $g=>$v){
    //     	// 	     $this->RHGcp[$tec][$g]['HourlyData'] = array_map(function($i, $j){ $k =array(); $k['hour'] = 'H'+$i; $k['value']=$j; return $k; }, array_keys($v), $v);
    //         //     }
    //         // }
    //         foreach($this->yrs as $year){
    //             foreach ($this->tech[$year] as $tec){
    //                 for($i=0;$i<8760;$i++){
    //                     $m['hour'] = 'H'.$i;
    //                     $m['value'] = $RHG[$year][$tec][$i];
    //                     $this->RHGcp[$tec][$year]['HourlyData'][$i] =  $m;
    //                 }
    //             }
    //         }
    //     }
	// 	return $this->RHGcp;
    // }

    public function getRHGcp($pStorage){
		if (isset($this->RHGcp) && empty($this->RHGcp)) {
            $RHG = $this->getRHG($pStorage);
            foreach($this->yrs as $year){
                for($i=0;$i<8760;$i++){
                    $m['hour'] = 'H'.$i;
                    foreach ($this->tech[$year] as $tec){
                        $m[$tec] = $RHG[$year][$tec][$i];
                    }
                    $this->RHGcp[$year]['HourlyData'][$i] =  $m;
                }
            }
        }
		return $this->RHGcp;
    }



//////////////////////////////////////////////////////////////////CHART GRID OUTPUT//////////////////////////////////////////////////////////////////////////////////////////////////
   	//Maximum Hourly Generation in System
	public function getMHG($pStorage){
		if (isset($this->MHG) && empty($this->MHG)) {
			foreach($this->yrs as $year){
			     for($i=0;$i<8760;$i++){
                    $k['hour'] = 'H'.$i;
                    $k['value'] = $this->RHD[$year][$i];  //value + demand
                    foreach ($this->tech[$year] as $tec){
                        $k[$tec] = $this->HG[$year][$tec][$i];
                    }
                    $this->MHG[$year]['HourlyData'][$i] =  $k;
                 }
            }
		}
		return $this->MHG;
	}

   	//Dispatched Hourly Generation in System
	public function getDHG($pStorage){
		if (isset($this->DHG) && empty($this->DHG)) {
            $RHD = $this->getRHD();
            $RHG = $this->getRHG($pStorage);
			foreach($this->yrs as $years){
			     for($i=0;$i<8760;$i++){
                    $k['hour'] = 'H'.$i;
                    $k['value'] = $RHD[$years][$i];  //value + demand
                    foreach ($this->tech[$years] as $tec){
                        $k[$tec] = $RHG[$years][$tec][$i];
                    }
                    $this->DHG[$years]['HourlyData'][$i] =  $k;
                 }
            }
		}
		return $this->DHG;
	}

   	//Unserved Demand
    public function getUD($pStorage){
		if (isset($this->UD) && empty($this->UD)) {
		    $RHD_tl = $this->getRHD_tl($pStorage);
			foreach($this->yrs as $years){
			     $counter = 0;
			     $this->UD[$years]['MaxUD'] = max($RHD_tl[$years]);
			     $this->UD[$years]['SumUD'] = 0;
			     for($i=0;$i<8760;$i++){
                    $k['hour'] = 'H'.$i;
                    $k['value'] = $RHD_tl[$years][$i];  //value + demand
                    $this->UD[$years]['HourlyData'][$i] =  $k;
                    $this->UD[$years]['SumUD'] =  $this->UD[$years]['SumUD'] + $RHD_tl[$years][$i];
                    if($RHD_tl[$years][$i]>0)
                       $counter++;
                 }
                 $this->UD[$years]['CountUD'] = $counter;
            }
		}
		return $this->UD;
	}

   	//Courtailment generation by hour, count of courtaiment, max courtaiment
	// public function getCHGStat($pStorage){
	// 	if (isset($this->CHGStat) && empty($this->CHGStat)) {
    //             $CHG = $this->getCHG_tl($pStorage);
    //             foreach($this->yrs as $years){
    //                 foreach($this->tech[$years] as $tec){
    //     			     $counter = 0;
    //     			     $this->CHGStat[$tec][$years]['MaxCHG'] = max($CHG[$years][$tec]);
    //     			     $this->CHGStat[$tec][$years]['SumCHG'] = 0;
    //     			     for($i=0;$i<8760;$i++){
    //                         $k['hour'] = 'H'.$i;
    //                         $k['value'] = $CHG[$years][$tec][$i];  //value + demand
    //                         $this->CHGStat[$tec][$years]['HourlyData'][$i] =  $k;
    //                         $this->CHGStat[$tec][$years]['SumCHG'] =  $this->CHGStat[$tec][$years]['SumCHG'] + $CHG[$years][$tec][$i];
    //                         if($CHG[$years][$tec][$i]>0)
    //                            $counter++;
    //                      }
    //                      $this->CHGStat[$tec][$years]['CountCHG'] = $counter;
    //                 }
    //             }
	// 	}
	// 	return $this->CHGStat;
    // }
    
    //Courtailment generation by hour, count of courtaiment, max courtaiment
    //stavicemo sav curtailment u jedan file  za W, S, H
	public function getCHGStat($pStorage){
		if (isset($this->CHGStat) && empty($this->CHGStat)) {
            $CHG = $this->getCHG_tl($pStorage);
            foreach($this->yrs as $years){
                foreach($this->tech[$years] as $tec){
        		     $counter = 0;
        		     $this->CHGStat[$years]['MaxCHG'][$tec] = max($CHG[$years][$tec]);
                     $this->CHGStat[$years]['SumCHG'][$tec] = 0;
        		     for($i=0;$i<8760;$i++){
                        $this->CHGStat[$years]['HourlyData'][$i]['hour'] =  'H'.$i;
                        $this->CHGStat[$years]['HourlyData'][$i][$tec] =  $CHG[$years][$tec][$i]; 
                        $this->CHGStat[$years]['SumCHG'][$tec] =  $this->CHGStat[$years]['SumCHG'][$tec] + $CHG[$years][$tec][$i];
                        if($CHG[$years][$tec][$i]>0)
                           $counter++;
                     }
                     $this->CHGStat[$years]['CountCHG'][$tec] = $counter;
                }
            }
		}
		return $this->CHGStat;
    }
    

    public function getElMixSharesP2($pStorage){
        if (isset($this->ElMixSharesP2) && empty($this->ElMixSharesP2)) {
            $HD = $this->getSum_HD();
            $UD = $this->getUD($pStorage);
            $RHG = $this->getSUM_RHG($pStorage);

            ///$this->ElmixSharesPhase1 = $this->getSESElMix_yt();
            $ElMixP1 = $this->ELMIX;

            foreach($this->yrs as $year){
                //ako radimo izvoz onda se mora na regularni demand doati taj iznos, ako radimo import onda se import ponasa kao tehnologija i racunamo njen share u demandu
                //dok u slucaju izvoza moramo prvo dodati taj iznos pa racunati share zato u ovom slucaju  kortistimo RHD gdje je Import/Export dodat/oduzet
                $Demand = $HD;
                foreach($this->tech[$year] as $fuel){
                    if($fuel != 'Storage'){
                        if($Demand[$year] != 0){
                            $this->ElMixSharesP2[$year][] = array(
                                'tech' => $fuel,
                                'valueP1' => $ElMixP1[$year][$fuel],
                                'valueP2' => $RHG[$year][$fuel]/$Demand[$year]*100,
                            );
                        }
                        else{
                            $this->ElMixSharesP2[$year][] = array(
                                'tech' => $fuel,
                                'valueP1' => $ElMixP1[$year][$fuel],
                                'valueP2' => 0,
                            );
                        }
                    }
                    else{
                        if($Demand[$year] != 0){
                            $this->ElMixSharesP2[$year][] = array(
                                'tech' => $fuel,
                                'valueP1' => 0,
                                'valueP2' => $RHG[$year][$fuel]/$Demand[$year]*100,
                            );
                        }
                        else{
                            $this->ElMixSharesP2[$year][] = array(
                                'tech' => $fuel,
                                'valueP1' => 0,
                                'valueP2' => 0,
                            );
                        }
                    }
                }
                if($Demand[$year] != 0){
                   $this->ElMixSharesP2[$year][] = array(
                       'tech' => 'Unserved Demand',
                       'valueP1' => 0,
                       'valueP2' => $UD[$year]['SumUD']/$Demand[$year]*100,
                   );
                //    $this->ElMixSharesP2[$year][] = array(
                //        'tech' => 'ImportExport',
                //        'valueP1' => $ElMixP1[$year]['ImportExport'],
                //        'valueP2' => $ElMixP1[$year]['ImportExport'],
                //    );
                }
                else{
                   $this->ElMixSharesP2[$year][] = array(
                       'tech' => 'Unserved Demand',
                       'valueP1' => 0,
                       'valueP2' => 0,
                   );
                //    $this->ElMixSharesP2[$year][] = array(
                //        'tech' => 'ImportExport',
                //        'valueP1' => $ElMixP1[$year]['ImportExport'],
                //        'valueP2' => 0,
                //    );
                }
            }
            foreach($this->yrs as $year){
                $this->OutputElMix[] = array(
                        'year' => $year,
                        'UD'   => $UD[$year]['SumUD']/1000,     //da dobijemo GWH
                        'maxUD'   => $UD[$year]['MaxUD'],
                        'countUD'   => $UD[$year]['CountUD'],
                        'elMix' => array(
                            'technology' => $this->ElMixSharesP2[$year],
                        ),
               	);
            }
        }
        return $this->OutputElMix;
    }
    //HD data kojise dobija iz modal grida sa ukupnom generacijom
    public function getHourlyDataValues($pStorage){
        if (isset($this->HDValues) && empty($this->HDValues)) {
            $HG = $this->getRHG($pStorage);
            $UD = $this->getRHD_tl($pStorage);
            $CHG = $this->getCHG_tl($pStorage);
            if($pStorage){
                $STG = $this->getSTG();
            }

            foreach($this->yrs as $year){
                for($i=0;$i<8760;$i++){
                    $k['Hour'] = 'H'.$i;
                    $k['Demand'] = $this->RHD[$year][$i];  //value + demand
                    $k['UD'] = $UD[$year][$i];
                    foreach($this->tech[$year] as $tech){
                        if($tech != 'ImportExport' && $tech != 'Storage'){
                            $k[$tech] =  $HG[$year][$tech][$i];
                            $k[$tech.'Curtail'] = $CHG[$year][$tech][$i];
                        }
                        else if ($tech == 'Storage' && $pStorage){
                            $k[$tech] =  $STG['Generation'][$year][$i];
                            $k[$tech.'Curtail'] = $STG['Curtailment'][$year][$i];
                        }
                    }
                    $this->HDValues[$year][$i] =  $k;
                }
            }
        }
        return $this->HDValues;
    }

    //trenutno smo iskljucili opcijuENS tako da se ova funkcije NE KORISTI
    // public function resetENSPower(){
    //     $filepath = USER_CASE_PATH.$this->pCase."/".$this->pCase.".xml";
    //     $years = $this->yrs;
    //     $techs = $this->tech[$year];
    //     foreach($years as $year){
    //         foreach($techs as $tech){
    //              appent_ens_capacity($filepath, $year, $tech, "0");
    //         }
    //     }
    // }

    //prilagoditi tehnicki Cf sa onim sto se nalazi u patternu za W, S, H
    public function adjustCF(){
        $array = array();
        foreach($this->yrs as $year){
            foreach($this->tech[$year] as $fuel){
                if (in_array($fuel, Constant::HourlyAnalysisTech)) {
                    unset($array);
                    $array = array();
                    // $CF = $this->getCapacityFactor($year, $fuel);
                    $CF = $this->CF[$year][$fuel];
                    $CF2 =  $this->CF1[$year][$fuel];
                    if($CF!=$CF2){
                        $filepath = USER_CASE_PATH.$this->pCase."/".$this->pCase.".xml";
                        $array = array(
                        'Capacity_factor' => $CF2
                        );
                        appent_tra_technical($filepath, $year, $array, $fuel);
                    }
                }
            }
        }
    }
}

// $hData = new HourlyData('DEMO');


// echo " getSUM_RHD_c";
// echo "<pre>";
// print_r($hData->getSUM_RHD_c(true, 730, 12));
// echo "</pre>";

// echo " getSUM_CHG_c";
// echo "<pre>";
// print_r($hData->getSUM_CHG_c(true, 730, 12));
// echo "</pre>";

// echo " getSUM_RHG(";
// echo "<pre>";
// print_r($hData->getSUM_RHG(false));
// echo "</pre>";



// echo "RHG";
// echo "<pre>";
// print_r($hData->getRHG()['2050']['Gas'][0]);
// echo "</pre>";

// // $hData = new HourlyData('ImportExport');
// echo "DH";
// echo "<pre>";
// print_r($hData->getHourlyDataValues()['2050'][0]);
// echo "</pre>";


// // echo "RHG";
// // echo "<pre>";
// // print_r($hData->getRHG()['2050']['ImportExport']);
// // echo "</pre>";
// echo '2040 HG ' . $hData->getRHG()['2040']['ImportExport'][0] . " RHG " . $hData->getHG()['2040']['ImportExport'][0] . " UD " . $hData->getRHD_tl()['2040'][0] . " curtailment " .  $hData->getCHG_tl()['2040']['ImportExport'][0] . "<br>";
// echo '2045 HG ' . $hData->getRHG()['2045']['ImportExport'][0] . " RHG " . $hData->getHG()['2045']['ImportExport'][0] . " UD " . $hData->getRHD_tl()['2045'][0] . " curtailment " .  $hData->getCHG_tl()['2045']['ImportExport'][0] . "<br>";
// echo '2050 HG ' . $hData->getRHG()['2050']['ImportExport'][0] . " RHG " . $hData->getHG()['2050']['ImportExport'][0] . " UD " . $hData->getRHD_tl()['2050'][0] . " curtailment " .  $hData->getCHG_tl()['2050']['ImportExport'][0] . "<br>";

// echo "TIC";
// echo "<pre>";
// print_r($hData->TIC);
// echo "</pre>";

// echo "el import MW";
// echo "<pre>";
// print_r($hData->getElImport());
// echo "</pre>";

// echo "import expor capaciy";
// echo "<pre>";
// print_r($hData-> getCapacityForImportExport('2050'));
// echo "</pre>";

// //$hData = new HourlyData('Case study 1');
// //public function getOutputDetails($pMaintenance){
// echo "techs";
// echo "<pre>";
// print_r($hData->getOutputDetails(true));
// echo "</pre>";


// echo $hData->pCase;


// //$hData->calculateWithMaintenance();
//   $hData->getMCLASS_order_h_cp(true);
//   $hData->getTAIC_cp(true);

// echo $hData->xml;
//getCHG_tl

//echo "Hydro max HP " . $hData->getMaxHP() . "</br>";

// echo "techs";
// echo "<pre>";
// print_r($hData->tech);
// echo "</pre>";

// echo "max HP";
// echo "<pre>";
// print_r($hData->getMaxHP());
// echo "</pre>";

// echo "hp hydro";
// echo "<pre>";
// print_r($hData->HP_Hydro);
// echo "</pre>";

// echo "NHP";
// echo "<pre>";
// print_r($hData->getNHP()["2010"]["Hydro"]);
// echo "</pre>";


// echo "getMCLASS_order_h";
// echo 'MCLASS';
// echo "<pre>";
// print_r($hData->MCLASS_order_h['2020']['Gas']);
// echo "</pre>";

// echo 'courtailmnt';
// echo "<pre>";
// print_r($hData->getCHG_tl()['2020']['Gas']);
// echo "</pre>";

// echo 'TIC';
// echo "<pre>";
// print_r($hData->TIC);
// echo "</pre>";

// echo 'TICD_p1';
// echo "<pre>";
// print_r($hData->TICD_p1);
// echo "</pre>";

// //  $hData->getHG();

// echo 'getCHG_tl';
// echo "<pre>";
// print_r($hData->getCHG_tl()['2020']['Gas']);
// echo "</pre>";

// echo 'getRHG';
// echo "<pre>";
// print_r($hData->getRHG()['2020']['Gas']);
// echo "</pre>";

// echo 'getHG';
// echo "<pre>";
// print_r($hData->getHG()['2020']['Gas']);
// echo "</pre>";


// echo "getMCLASS_order_h";
// echo 'MCLASS';
// echo "<pre>";
// print_r($hData->getMCLASS_order_h()['2020']['Gas']);
// echo "</pre>";

// echo 'MCLASS';
// echo "<pre>";
// print_r($hData->getMCLASS());
// echo "</pre>";

// echo "HD";
// echo "<pre>";
// print_r($hData->getHD()['2020']['1825']);
// echo "</pre>";

// echo "RHD";
// echo "<pre>";
// print_r($hData->getRHD()['2020']['1825']);
// echo "</pre>";

// echo "demand kad se oduzme intermitent i import i uzme maximuim po chunku";
// echo "<pre>";
// print_r($hData->getRHLD_p(365, 24)['2020']);
// echo "</pre>";

// echo 'intysalirana snaga iz p1 samo ona koja se koristi za odrzavanje';
// echo "<pre>";
// print_r($hData->getTICDy()['2020']);
// echo "</pre>";

// echo 'Space Long';
// echo "<pre>";
// print_r($hData->getMSPACE_long()['2020']);
// echo "</pre>";

// echo 'Space sshort';
// echo "<pre>";
// print_r($hData->getMSPACE_short()['2020']);
// echo "</pre>";

//    $hData->getMCLASS_orderLoop();


// echo 'odrzavanja';
// echo "<pre>";
// print_r($hData->MCLASS_order['2020']);
// echo "</pre>";

// echo 'DF';
// echo "<pre>";
// print_r($hData->getDeratedFactor());
// echo "</pre>";

// $hData->getMCLASS_order_h();
// echo 'HG Coal';
// echo "<pre>";
// print_r($hData->getHG()['1990']['Coal']);
// echo "</pre>";


// echo 'Long';
// echo "<pre>";
// print_r($hData->MDLong);
// echo "</pre>";

// echo 'Short';
// echo "<pre>";
// print_r($hData->MDShort);
// echo "</pre>";



// //$hData->TIC = $hData->TIPD_p1;
//  'DP';
// echo "<pre>";
// print_r($hData->getDeratedPower());
// echo "</pre>";


//  'MDShort';
// echo "<pre>";
// print_r($hData->MDShort);
// echo "</pre>";

// // // // // //$hData->getMCLASS_order_h_cp(true);
// // // // // //$hData->getMCLASS_order_h();



// $MCLASS = $hData->getMCLASS();
// $MSPACE_long = $hData->getMSPACE_long();
// $MSPACE_short = $hData->getMSPACE_short();

//  'Unit size MCLASS';
// echo "<pre>";
// print_r($MCLASS);
// echo "</pre>";

// echo 'Unit size 2050';
// echo "<pre>";
// print_r($MCLASS['UnitSize']['2050']);
// echo "</pre>";

// echo 'Unit number 2050';
// echo "<pre>";
// print_r($MCLASS['UnitNumber']['2050']);
// echo "</pre>";

// echo 'getMSPACE long period=> size';
// echo "<pre>";
// print_r($MSPACE_long['2050']);
// echo "</pre>";

// echo 'getMSPACE short';
// echo "<pre>";
// print_r($hData->getMSPACE_short()['2020']);
// echo "</pre>";


// echo 'order';
// echo "<pre>";
// print_r($hData->getMCLASS_order($MCLASS, $MSPACE_long, $MSPACE_short, '2050'));
// //print_r($hData->MCLASS_order);
// echo "</pre>";



// echo 'out getMCLASS_order_h';
// echo "<pre>";
// print_r($hData->getMCLASS_order_h()['2005']['Coal']);
// echo "</pre>";

// echo 'jaaaaaaaaaaaaaaaaa';
// echo 5 / 3 ."<br>";
// echo 5 % 3 ."<br>";

// $array =  Array(
//         "Gas" => array(
//                     '0'=> 0,
//                     '1'=> 0,
//                     '2'=> 0,
//                     '3'=> 0,
//                     '4'=> 0,
//                     '5'=> 0,
//                     '6'=> 0,
//                     '7'=> 0,
//                     '8'=> 0,
//                     '9'=> 0,
//                     '10'=> 0,
//                     '11' => 0,
//                     '12' => 0,
//                     '13' => 0,
//                     '14' => 0,
//                     '15' => 0,
//                     '16' => 0,
//                     '17' => 0,
//                     '18' => 0,
//                     '19' => 0,
//                     '20' => 0,
//                     '21' => 0,
//                     '22' => 0,
//                     '23' => 0,
//                     '24' => 0,
//                     '25' => 0,
//                     '26' => 0,
//                     '27' => 0,
//                     '28' => 0,
//                     '29' => 0,
//                     '30' => 0
//         )
// );

//   echo "<pre>";
//                 print_r($array);
//  echo "</pre>";


// array_splice($array['Gas'], 1,7,array_fill(1,7,'B'));


// echo "<pre>";
//     print_r($array);
//  echo "</pre>";

?>