<?php
//require_once('../log4php/Logger.php');
//define('ROOT_DIR','C:\wamp.3.2.0\www\ESST\esst.ver.2.7.0');
require_once ROOT_DIR.'/config.php';
require_once ROOT_DIR.'/classes/Const.Class.php';
require_once ROOT_DIR.'/classes/paths.php';

class EsstCaseClass {

	public function __construct($pCase){
   	    if (!isset($this->xml)) {
            $filepath = USER_CASE_PATH.$pCase."/".$pCase.".xml";
            if (file_exists($filepath)) {
                $this->xml = simplexml_load_file($filepath)
                or die("Error: Cannot create object");
            }
        }
	}

    ///////////////////////////////////XML FUNC///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //daje samo niz godina koje su izabrane u fazi 1 ['2000', '2010'...]
    public function getYears() {
		if (!isset($this->yearsArr)) {
			$this->yearsArr = array();
			foreach($this->xml->Years[0] as $aYr) {
                if($aYr == '1'){
                    $this->yearsArr[] = substr($aYr->getName(), 1);
                }
			}
		}
		return $this->yearsArr;
    }

    //daje samo niz sectora koje su izabrane u fazi 1 ['Industry', 'Treansport'...]
    public function getSectors() {
        if (!isset($this->sectorsArr)) {
            $this->sectorsArr = array();
            foreach($this->xml->Sectors[0] as $aSc) {
                if($aSc == '1'){
                    $this->sectorsArr[] = $aSc->getName();
                }
            }
        }
        return $this->sectorsArr;
    }

   	public function getCommodities() {
		if (!isset($this->fuels)) {
			$this->fuels = array();
			foreach($this->xml->Fuels[0] as $aFuel) {
                if($aFuel == '1'){
                    $this->fuels[] = $aFuel->getName();
                }
			}
		}
		return $this->fuels;
    }

    //daje samo niz sectora koje su izabrane u fazi 1 ['Industry', 'Treansport'...]
    public function getTechs() {
        if (!isset($this->techsArr)) {
            $this->techsArr = array();
            foreach($this->xml->ElMix_fuels[0] as $aTe) {
                if($aTe == '1'){
                    $this->techsArr[] = $aTe->getName();
                }
            }
        }
        return $this->techsArr;
    }

    public function getUnit() {
		if (!isset($this->unit)){
			$this->unit = (string)$this->xml->Case->units;
		}
		return $this->unit;
	}

    public function getCurrency() {
		if (!isset($this->currency)) {
			$this->currency = (string)$this->xml->Case->currency;
		}
		return $this->currency;
	}

    //get Carbon content cost by years
    public function getCC_y() {
        if (!isset($this->cc)) {
            $this->cc = array();
            $years = $this->getYears();
            foreach($years as $year){
                $node = $this->xml->xpath("//tra_carbon_cost[@year='{$year}']/carbon_cost");
                $this->cc[$year] = (string)$node[0];
            }
        }
		return $this->cc;
    }

    //get dicount rate by Years
    public function getDR_y() {
        if (!isset($this->dr)) {
            $this->dr = array();
            $years = $this->getYears();
            foreach($years as $year){
                $node = $this->xml->xpath("//tra_discount_rate[@year='{$year}']/discount_rate");

                $this->dr[$year] = (string)$node[0];
            }
        }
		return $this->dr;
    }

    // //get lifetime by technology
    // public function getLt_t() {
    //     if (!isset($this->lt)) {
    //         $this->lt = array();
    //         $techs = $this->getTechs();
    //         foreach($techs as $tech){
    //             if ($tech != 'ImportExport'){
    //                 $node = $this->xml->xpath("//tra_lifetime[@technology='{$tech}']/Lifetime");
    //                 $this->lt[$tech] = (string)$node[0];
    //             }
    //         }
    //     }
    //     return $this->lt;
    // }

    //get lifetime by technology prmijenjeo da bi se dodalo lifetime po godinama 04252020
    public function getLt_t() {
        if (!isset($this->lt)) {
            $this->lt = array();
            $years = $this->getYears();
            $techs = $this->getTechs();
            foreach($years as $year){
                foreach ($techs as $tech){
                    if ($tech != 'ImportExport'){
                        $node = $this->xml->xpath("//tra_lifetime[@year='{$year}']/{$tech}");
                        $this->lt[$year][$tech] = (string)$node[0];
                    }
                }
            }
        }
        return $this->lt;
    }

    //get efficienacies by year and technology
    public function getEff_yt() {
		if (!isset($this->eff)) {
            $this->eff = array();
            $years = $this->getYears();
            $techs = $this->getTechs();
            foreach($years as $year){
                foreach ($techs as $tech){
                    if ($tech != 'ImportExport'){
                        $node = $this->xml->xpath("//tra_efficiency[@year='{$year}']/{$tech}");
                        $this->eff[$year][$tech] = (string)$node[0];
                    }
                }
            }
		}
		return $this->eff;
    }

    //Installed Capacity by year technology
    public function getIC_yt() {
		if (!isset($this->ic)) {
            $this->ic = array();
            $years = $this->getYears();
            $techs = $this->getTechs();
            foreach($years as $year){
                foreach ($techs as $tech){
                    if ($tech != 'ImportExport'){
                        $node = $this->xml->xpath("//tra_technical[@year='{$year}'and @technology='{$tech}']/Installed_power");
                        $this->ic[$year][$tech] = (string)$node[0];
                    }
                }
            }
		}
		return $this->ic;
    }

    //Capacity Factor by year Technology
    public function getCF_yt() {
		if (!isset($this->cf)) {
            $this->cf = array();
            $years = $this->getYears();
            $techs = $this->getTechs();
            foreach($years as $year){
                foreach ($techs as $tech){
                    if ($tech != 'ImportExport'){
                        $node = $this->xml->xpath("//tra_technical[@year='{$year}'and @technology='{$tech}']/Capacity_factor");
                        $this->cf[$year][$tech] = (string)$node[0];
                    }
                }
            }
		}
		return $this->cf;
    }

    public function getEnv_yt() {
		if (!isset($this->environment)) {
            $this->environment = array();
            $years = $this->getYears();
            $techs = $this->getTechs();
            foreach($years as $year){
                foreach ($techs as $tech){
                    if ($tech != 'ImportExport'){
                        $node = $this->xml->xpath("//tra_envirioment[@year='{$year}'and @technology='{$tech}']");
                        foreach($node[0] as $type => $val) {
                            $this->environment[$year][$tech][(string)$type] = (string)$val[0];
                        }
                    }
                }
            }
		}
		return $this->environment;
	}

    public function getCost_yt() {
		if (!isset($this->financeArray)) {
            $this->financeArray = array();
            $years = $this->getYears();
            $techs = $this->getTechs();
            foreach($years as $year){
                foreach ($techs as $tech){
                    if ($tech != 'ImportExport'){
                        $node = $this->xml->xpath("//tra_finance[@year='{$year}'and @technology='{$tech}']");
                        foreach($node[0] as $type => $val) {
                            $this->financeArray[$year][$tech][(string)$type] = (string)$val[0];
                        }
                    }
                }
            }
		}
		return $this->financeArray;
    }

    //Final energy Demand by Year Sector
    public function getFED_ys() {
        if (!isset($this->fedBySector)) {
            $years = $this->getYears();
            $sectors = $this->getSectors();
            foreach($years as $year){
                foreach($sectors as $sector) {
                    $node = $this->xml->xpath("//fed_bysectors[@year='{$year}']/{$sector}");
                    $this->fedBySector[$year][$sector] = (string)$node[0];
                }
            }
        }
        return $this->fedBySector;
    }

    //Final Enrgy Demand Share by year sector commodity
    public function getFEDShare_ysc() {
		if (!isset($this->FedFuelShare)) {
            $years = $this->getYears();
            $sectors = $this->getSectors();
            $commodities = $this->getCommodities();

            foreach($years as $year){
                foreach ($sectors as $sector){
                    foreach($commodities as $commodity){
                        $node = $this->xml->xpath("//fed_fuelshares[@year='{$year}'and @sector='{$sector}']/{$commodity}");
                        $this->FedFuelShare[$year][$sector][$commodity] = (string)$node[0];
                    }
                }
            }
		}
		return $this->FedFuelShare;
    }

    //Final Energy demand by year commodity
    public function getFEDLosses_yc(){
		if (!isset($this->fedLosses)) {
            $years = $this->getYears();
            $commodities = $this->getCommodities();
            foreach($years as $year){
                foreach($commodities as $commodity){
                    $node = $this->xml->xpath("//fed_losses[@year='{$year}']/{$commodity}");
                    $this->fedLosses[$year][$commodity] = (string)$node[0];
                }
            }
        }
	    return $this->fedLosses;
    }

    //Mix of technologies for electricity by year and technology
    public function getSESElMix_yt() {
		if (!isset($this->sesElmix)) {
            $years = $this->getYears();
            $techs = $this->getTechs();
            $this->sesElmix = array();
            foreach($years as $year){
                foreach($techs as $tech){
                    $node = $this->xml->xpath("//ses_elmix[@year='{$year}']/{$tech}");
                    $this->sesElmix[$year][$tech] = (string)$node[0];
                }
            }


		}
		return $this->sesElmix;
    }

    //Reserve Capacity Shares by year and RC tech
    public function getRC_s_yt() {
        if (!isset($this->rc_s)) {
            $years = $this->getYears();
            $this->rc_s = array();
            foreach($years as $year){
                $node = $this->xml->xpath("//tra_reserve_capacity[@year='{$year}']");
                foreach(Constant::ReserveCapacityTechs as $tech) {
                //foreach($this->ReserveCapacityTechs as $tech) {
                    $this->rc_s[$year][$tech] = (string)$node[0]->$tech;
                }
            }
        }
        return $this->rc_s;
    }

    //Reserve Capacity Total Percent of  by year and RC tech
    public function getRC_tp_y() {
        if (!isset($this->rc_t)) {
            $years = $this->getYears();
            $this->rc_t = array();
            foreach($years as $year){
                $node = $this->xml->xpath("//tra_reserve_capacity[@year='{$year}']");
                foreach($node[0]->attributes() as $att=>$val) {
                    $this->rc_t[$year] = (string)$val;
                }
            }
        }
        return $this->rc_t;
    }

    /////////////////////////PHASE 1 CALCULATIONS/////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //total final cncumptio by year sector commodity
    public function getTFC_ysc(){
        if (!isset($this->tfc)) {
            $fedShare = $this->getFEDShare_ysc();
            $fedBySector = $this->getFED_ys();
            foreach($fedShare as $year=>$obj){
                foreach($obj as $sector=>$commodityObj){
                    foreach($commodityObj as $commodity=>$share){
                        $this->tfc[$year][$sector][$commodity] = $share / 100 * $fedBySector[$year][$sector];
                    }
                }
            }
        }
        return $this->tfc;
    }

    //total final cncumptio (aagregated by sector) by year commodity
    public function getTFC_yc(){
        if (!isset($this->tfc_c)) {
            $tfc = $this->getTFC_ysc();
            $this->tfc_c = array();
            foreach($tfc as $year=>$sectorObj){
                foreach($sectorObj as $sector=>$commodityObj){
                    foreach($commodityObj as $commodity=>$tfcValue){
                        if(!isset($this->tfc_c[$year][$commodity])){
                            $this->tfc_c[$year][$commodity] =  $tfcValue;
                        }else{
                            $this->tfc_c[$year][$commodity] = $this->tfc_c[$year][$commodity] + $tfcValue;
                        }
                    }
                }
            }
        }
        return $this->tfc_c;
    }

    //Secondary energy Supply by year commodity [PJ, ktoe, Mtoe, GWh]
    public function getSES_yc(){
        if (!isset($this->ses)) {
            $this->ses = array();
            $losses = $this->getFEDLosses_yc();
            $tfc = $this->getTFC_yc();
            foreach($tfc as $year=>$commodityObj){
                foreach($commodityObj as $commodity=>$tfcVal){
                    $this->ses[$year][$commodity] = $tfcVal/(1-$losses[$year][$commodity]/100);
                }
            }
        }
        return $this->ses;
    }

    //kolicina primarne energije koja se iskoristi za generaciju elekricne energije, dobijemo iz
    //SES(electricity) kad dodamo gubitne i efikasnost po gorivima
    //Primary Energy for Electricity Genration
    public function getPEEG_yt() {
        if (!isset($this->peeg)) {
            $this->peeg = array();
            $ed = $this->getElD_yt();
            $eff = $this->getEff_yt();
            foreach($ed as $year=>$edObj){
                foreach($edObj as $commodity=>$edVal){
                    if($commodity != 'ImportExport'){
                        if($eff[$year][$commodity] != 0){
                            $this->peeg[$year][$commodity] = $edVal * (100 / $eff[$year][$commodity]);
                        }else{
                            $this->peeg[$year][$commodity] = 0;
                        }
                    }else{
                        $this->peeg[$year][$commodity] = $edVal;
                    }

                }
            }
        }
        return $this->peeg;
    }

    //total Primary energy Supply by year technology [PJ, ktoe, Mtoe, GWh]
    public function getTPES_yt(){
        if (!isset($this->tpes)) {
            $this->tpes = array();
            $peeg = $this->getPEEG_yt();
            $ses = $this->getSES_yc();

            foreach($peeg as $year=>$commodityObj){
                foreach($commodityObj as $commodity=>$peegVal){
                    if(!isset($ses[$year][$commodity])){ $ses[$year][$commodity] = 0; }
                    $this->tpes[$year][$commodity] = $peegVal + $ses[$year][$commodity];
                }
            }
        }
        return $this->tpes;
    }


    //Deman for Electricity => Secondary energy Supply for electricity [GWh] by year all techs
    //vrijednosti se vracaju energetskoj jedinici izabranoj za case
    public function getElD_y(){
        if (!isset($this->elD_y)) {
            $this->elD_y = array();
            $ses = $this->getSES_yc();
            //$unit = $this->getUnit();
            foreach($ses as $year=>$commodityObj){
                $this->elD_y[$year] = $commodityObj['Electricity'];
            }
        }
        return $this->elD_y;
    }

    //vrijednosti izlaza iz transformacije za proizvodnju el energije po gorivima
    //na Secondary Energy Supply (El Energija)*SES_El_MIX/100
    //energetska jedinica koja je izabrana u cas-u
    public function getElD_yt(){
        if (!isset($this->elD_yt)) {
            $this->elD_yt = array();
            $elD_y = $this->getElD_y();
            $sesElmix = $this->getSESElMix_yt();
            foreach( $sesElmix as $year=>$techObj){
                foreach($techObj as $tech=>$elMix){
                    $this->elD_yt[$year][$tech] = $elMix/100*$elD_y[$year];
                }
            }

        }
        return $this->elD_yt;
    }

    //Deman for Electricity => Secondary energy Supply for electricity [GWh] by year all techs
    //vrijednosti se vracaju u GWh, treba faktor ako je case u nekoj drugoj jedinici
    //energetska u energetsku jedinicu
    public function getElD_y_gwh(){
        if (!isset($this->elD_y_gwh)) {
            $this->elD_y_gwh = array();
            $ses = $this->getSES_yc();
            $unit = $this->getUnit();
            foreach($ses as $year=>$commodityObj){
                $this->elD_y_gwh[$year] = $commodityObj['Electricity'] * constant("Constant::{$unit}_GWh");
            }
        }
        return $this->elD_y_gwh;
    }

    //vrijednosti izlaza iz transformacije za proizvodnju el energije po gorivima
    //na Secondary Energy Supply (El Energija)*SES_El_MIX/100
    public function getElD_yt_gwh(){
        if (!isset($this->elD_yt_gwh)) {
            $this->elD_yt_gwh = array();
            $elD_y = $this->getElD_y_gwh();
            $sesElmix = $this->getSESElMix_yt();
            foreach( $sesElmix as $year=>$techObj){
                foreach($techObj as $tech=>$elMix){
                    $this->elD_yt_gwh[$year][$tech] = $elMix/100*$elD_y[$year];
                }
            }

        }
        return $this->elD_yt_gwh;
    }

    //Import of Electricity in MW (snaga) by year
    public function getElImport_y_mw(){
        if (!isset($this->elImport)) {
            $this->elImport = array();
            $elDemand = $this->getElD_y_gwh();
            $ImportShare = $this->getSESElMix_yt();
            foreach($elDemand as $year=>$val){
                $this->elImport[$year] = ($val * $ImportShare[$year]['ImportExport']/100) * Constant::GWh_MW; //dijelimo sa 8.76 da iz GWh dobijemo MW
            }
        }
        return $this->elImport;
    }

    //izracunaj instaliranu energiju u sistemu iz CF i instalirane snage by year tech in selected unit
    //fakto za prebacivanje iz MW u energetske jedinice je koristen
    public function getIE_yt_u(){
        if (!isset($this->ie)) {
            $this->ie =array();
            $ic = $this->getIC_yt();
            $cf = $this->getCF_yt();
            $unit = $this->getUnit();
            foreach($ic as $year=>$techObj){
                foreach($techObj as $tech=>$icVal){
                    // $conv = "MW_".$unit;
                    // echo constant("Constant::{MW_.$unit}");
                    $this->ie[$year][$tech] = ($cf[$year][$tech] / 100 * $icVal) *  constant("Constant::MW_{$unit}");
                }
            }
        }
        return $this->ie;
    }

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //CLACULATION FOR ADDITIONAL CAPACITY BY YEAR TECH with carryover capacity - moramo prvo carryover da bi znali sta tacno izlazi ici u deccomissioning
    //CALC DECOMISSIONING CAPACITY
    //ADJUST YEARS OF DECCOMISSIONING
    //CLACULATION FOR ADDITIONAL CAPACITY BY YEAR TECH WITH LIFETIME AND DECCOMISSIONING CONSIDERATION
    //CALCULATE ACCUMALUATIVE INSSTALLEED ADDITION CAPACITY THROUGH YEARS

    //getAC_yt additional Capacity by year and tech no Decommisioning accounted
    public function getAC_yt() {
        if (!isset($this->ac)) {

            $ed = $this->getElD_yt();
            $ie = $this->getIE_yt_u();
            $cf = $this->getCF_yt();
            $unit = $this->getUnit();

            $coc_yt = array(); //carryover capacity by tech
            foreach($ie as $year=>$techObj){
                foreach($techObj as $tech=>$ieVal){
                    if($cf[$year][$tech] > 0 ){
                        #echo "const " . constant("Constant::{$unit}_MW");
                        $ac_yt_mw = (($ieVal - $ed[$year][$tech]) / ($cf[$year][$tech] / 100)) * constant("Constant::{$unit}_MW");

                        if(!isset($coc_yt[$tech])){ $coc_yt[$tech] = 0;}
                        $ac_yt = $ac_yt_mw + abs($coc_yt[$tech]);

                        if($ac_yt < 0){
                            $this->ac[$year][$tech] = abs($ac_yt);
                            //$coc_yt[$tech] = $ac_yt_mw;
                            $coc_yt[$tech] = $coc_yt[$tech] + abs($ac_yt);
                        }else{
                            $this->ac[$year][$tech] = 0;
                        }
                    }else{
                        $this->ac[$year][$tech] = 0;
                    }
                }
            }
        }
        return $this->ac;
    }

    
    //getAC_yt extra  Capacity by year and tech, viak kakpciteta koji prenosimo iz godine u godinu
    public function getAC_yt_surplus() {
        if (!isset($this->extrac)) {
            $ed = $this->getElD_yt();
            $ie = $this->getIE_yt_u();
            $cf = $this->getCF_yt();
            $unit = $this->getUnit();
            $coc_yt = array(); //carryover capacity by tech
            foreach($ie as $year=>$techObj){
                foreach($techObj as $tech=>$ieVal){
                    if($cf[$year][$tech] > 0 ){
                        $ac_yt_mw = (($ieVal - $ed[$year][$tech]) / ($cf[$year][$tech] / 100)) * constant("Constant::{$unit}_MW");
                        if(!isset($coc_yt[$tech])){ $coc_yt[$tech] = 0;}
                        $ac_yt = $ac_yt_mw + abs($coc_yt[$tech]);
                        if($ac_yt < 0){
                            //$this->extrac[$year][$tech] = abs($ac_yt);
                            $coc_yt[$tech] = $coc_yt[$tech] + abs($ac_yt);
                        }
                        else if($ac_yt > 0){
                            $this->extrac[$year][$tech] = abs($ac_yt);
                            //$coc_yt[$tech] = $coc_yt[$tech] + abs($ac_yt);
                        }
                        else{
                            $this->extrac[$year][$tech] = 0;
                        }
                    }else{
                        $this->extrac[$year][$tech] = 0;
                    }
                }
            }
        }
        return $this->extrac;
    }

    //getDC_yt get decomission Capacity by year and tech
    public function getDC_yt() {
        if (!isset($this->dc)) {
            $ac = $this->getAC_yt();
            $lt = $this->getLt_t();
            $this->dc = array();
            foreach($ac as $year=>$acObj){
                foreach($acObj as $tech=>$asVal){
                    $lt_t = $lt[$year][$tech];
                    $decomYear = $year + $lt_t;
                    if(isset($this->dc[$decomYear][$tech])){
                        $this->dc[$decomYear][$tech] = $this->dc[$decomYear][$tech] + $asVal;
                    }else{
                        $this->dc[$decomYear][$tech] = $asVal;
                    }
                }
            }
            ksort($this->dc);
        }
        return $this->dc;
    }

    //getDC_yt get decomission Capacity by year and tech
    public function adjustDC_yt() {
        if (!isset($this->dc_y)) {
            $dc = $this->getDC_yt();
            $years = $this->getYears();
            $this->dc_y = array();
            foreach($dc as $year=>$dcObj){
                if(in_array($year, $years)){
                    foreach($dcObj as $tech=>$dcVal){
                        if(isset( $this->dc_y[$year][$tech])){
                            $this->dc_y[$year][$tech] = $this->dc_y[$year][$tech] + $dcVal;
                        }else{
                            $this->dc_y[$year][$tech] = $dcVal;
                        }
                    }
                }else{
                    foreach($years as $yr){
                        if(($yr-$year)>0){
                            foreach($dcObj as $tech=>$dcVal){
                                if(isset( $this->dc_y[$year][$tech])){
                                    $this->dc_y[$yr][$tech] = $this->dc_y[$year][$tech] + $dcVal;
                                }else{
                                    $this->dc_y[$yr][$tech] = $dcVal;
                                }
                            }
                        break;
                        }
                    }
                }

            }
        }
        return $this->dc_y;
    }

    //Additional Capacity by year and tech with Decommisioning accounted
    // public function getAC_d_yt() {
    //     if (!isset($this->ac_d)) {
    //         $this->ac_d = array();
    //         $ed = $this->getElD_yt();
    //         $ie = $this->getIE_yt_u();
    //         $cf = $this->getCF_yt();
    //         $unit = $this->getUnit();
    //         $dc = $this->adjustDC_yt();
    //         $coc_yt = array(); //carryover capacity by tech
    //         foreach($ie as $year=>$techObj){
    //             foreach($techObj as $tech=>$ieVal){
    //                 if($cf[$year][$tech] > 0 ){
    //                     //inicijalni Additional capacity u godini po tehnologiji samo razlika instaliranog i potrrebnog be CoC
    //                     $initAC_yt_mw = (($ieVal - $ed[$year][$tech]) / ($cf[$year][$tech] / 100)) * constant("Constant::{$unit}_MW");

    //                     //inicijalizacij nizova za akumulativne vrijednosi ili ako nema decomissioning za odredjenu godinu
    //                     if(!isset($coc_yt[$tech])){ $coc_yt[$tech] = 0;}
    //                     if(!isset($dc[$year][$tech])){$dc[$year][$tech] = 0;}

    //                     //ukupni dodatni kapacite treba biti umanjen za ono sto se prenosi iz prethodne godina [CoC] i povecan za ono sto se dekominisuje [dc]
    //                     $ac_yt = $initAC_yt_mw +  $coc_yt[$tech] - $dc[$year][$tech];
    //                     if($ac_yt < 0){
    //                         $this->ac_d[$year][$tech] = abs($ac_yt);
    //                         //sljedece dvije linije vracaju iste vrijednosti
    //                         $coc_yt[$tech] = abs($initAC_yt_mw);
    //                         //$coc_yt[$tech] = $coc_yt[$tech] + abs($ac_yt) - $dc[$year][$tech];
    //                     }else{
    //                         $this->ac_d[$year][$tech] = 0;
    //                     }
    //                 }else{
    //                     $this->ac_d[$year][$tech] = 0;
    //                 }
    //             }
    //         }
    //     }
    //     return $this->ac_d;
    // }

    //Adidtional Capacity with Decomissioning accounted
    //dodalo smo provjeru za eventualni surplus instalirane snage koja se prenosi
    public function getAC_d_yt() {
        if (!isset($this->ac_d2)) {
            $this->ac_d2 = array();
            $ac = $this->getAC_yt();
            $dc = $this->adjustDC_yt();
            $surplus = $this->getAC_yt_surplus();
            foreach($ac as $year=> $acObj){
                foreach($acObj as $tech=>$acVal){
                    if( isset($dc[$year][$tech])){
                        if( $acVal + $dc[$year][$tech] >  $surplus[$year][$tech]){
                            $this->ac_d2[$year][$tech] = $acVal + $dc[$year][$tech] -  $surplus[$year][$tech];
                        }else{
                            $this->ac_d2[$year][$tech] = 0;
                        }
                        
                    }else{
                        $this->ac_d2[$year][$tech] = $acVal;
                    }
                }
            }
        }
        return $this->ac_d2;
    }

    //Installed Additional capacity [accumulated-with carryover] Total Installed + Additional Capacity [Installed Capacity + Additional Capacity] by year and tech no Reserve Capacity
    public function getIAC_yt() {
        if (!isset($this->iac)) {
            $this->tic = array();
            $ac = $this->getAC_d_yt();
            $ic = $this->getIC_yt();
            $dc = $this->adjustDC_yt(); //??? zasto ponovo trazimo Decom ovo bi trebao biti jednistavan zbir IC + AC
            foreach($ac as $year=>$acObj){
                foreach($acObj as $tech=>$acVal){
                    //$this->iac[$year][$tech] = $acVal + $ic[$year][$tech];
                    if(!isset($dc[$year][$tech])){ $dc[$year][$tech] = 0; }
                    if (isset($coc[$tech])){
                        //kumulativni CoC kapacitet
                        $coc[$tech] = $coc[$tech] + $acVal - $dc[$year][$tech];
                        $this->iac[$year][$tech] = $coc[$tech] + $ic[$year][$tech];//- $dc[$year][$tech];
                    }else{
                        $this->iac[$year][$tech] = $acVal + $ic[$year][$tech];//- $dc[$year][$tech];
                        $coc[$tech] = $acVal;//- $dc[$year][$tech];
                    }
                }
            }
        }
        return $this->iac;
    }

    //getIAC Installed Capacity [Installed Capacity + Additional Capacity] by year no Reserve Capacity [all Techs]
    public function getIAC_y() {
        if (!isset($this->iac_y)) {
            $this->iac_y = array();
            $iac = $this->getIAC_yt();
            foreach($iac as $year=>$iacObj){
                $this->iac_y[$year] = array_sum($iacObj);
            }
        }
        return $this->iac_y;
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    //Additiona Reserve Capacity for years and tech with caryover CoC but no lifetime included
    public function getARC_yt() {
        if (!isset($this->arc)) {
            $this->arc = array();
            $iac = $this->getIAC_y();
            $rc_tp = $this->getRC_tp_y();
            $rc_s = $this->getRC_s_yt();
            $coc_yt = array(); //carryover capacity by tech
            foreach($rc_s as $year=>$rcObj){
                $this->arc[$year]['Total'] = $iac[$year] * $rc_tp[$year] / 100;
                foreach($rcObj as $tech=>$share){
                    $rc_yt = $this->arc[$year]['Total'] * $share / 100;
                    if(!isset($coc_yt[$tech])){ $coc_yt[$tech] = 0;}
                    $arc_yt = $rc_yt - $coc_yt[$tech];
                    if( $arc_yt > 0){
                        $this->arc[$year][$tech] = $arc_yt;
                        $coc_yt[$tech] = $coc_yt[$tech] + $arc_yt;
                    }else{
                        $this->arc[$year][$tech] = 0;
                    }
                    //ono sto se prenosi iz svake godine je RC koji je izgradjen za tu godinu a to je procenata od ukuone instaliane snage.
                    //ovo ne radi jer ako u jednoj godini nema RC onda ne prenosi ono sto je bilo u prethodnoj
                    //$coc_yt[$tech] = $rc_yt;
                }
            }
        }
        return $this->arc;
    }
    //Decomission Reserve Capacity by year and tech
    public function getDRC_yt() {
        if (!isset($this->drc)) {
            $arc = $this->getARC_yt();
            $lt = $this->getLt_t();
            $this->drc = array();
            $techs = $this->getTechs();
            foreach($arc as $year=>$arcObj){
                foreach($arcObj as $tech=>$arcVal){
                    //u RC koristimo Gas, Oil,
                    if($tech != 'Total' && in_array($tech, $techs) ){
                        $lt_t = $lt[$year][$tech];
                        $decomYear = $year + $lt_t;
                        if(isset($this->drc[$decomYear][$tech])){
                            $this->drc[$decomYear][$tech] = $this->drc[$decomYear][$tech] + $arcVal;
                        }else{
                            $this->drc[$decomYear][$tech] = $arcVal;
                        }
                    }
                }
            }
            ksort($this->drc);
        }
        return $this->drc;
    }

    //Decomission Reserve Capacity adjusted by Case years by year and tech
    public function adjustDRC_yt() {
        if (!isset($this->drc_y)) {
            $drc = $this->getDRC_yt();
            $years = $this->getYears();
            $this->drc_y = array();
            foreach($drc as $year=>$dcObj){
                if(in_array($year, $years)){
                    foreach($dcObj as $tech=>$dcVal){
                        if(isset( $this->drc_y[$year][$tech])){
                            $this->drc_y[$year][$tech] = $this->drc_y[$year][$tech] + $dcVal;
                        }else{
                            $this->drc_y[$year][$tech] = $dcVal;
                        }
                    }
                }else{
                    foreach($years as $yr){
                        if(($yr-$year)>0){
                            foreach($dcObj as $tech=>$dcVal){
                                if(isset( $this->drc_y[$year][$tech])){
                                    $this->drc_y[$yr][$tech] = $this->drc_y[$year][$tech] + $dcVal;
                                }else{
                                    $this->drc_y[$yr][$tech] = $dcVal;
                                }
                            }
                        break;
                        }
                    }
                }

            }
        }
        return $this->drc_y;
    }

    //Adidtional Reserve Capacity with Decomissioning accounted
    public function getRC_yt() {
        if (!isset($this->rc)) {
            $this->rc = array();
            $arc = $this->getARC_yt();
            $dc = $this->adjustDRC_yt();
            $coc_yt = array(); //carryover capacity by tech
            foreach($arc as $year=> $arcObj){
                foreach($arcObj as $tech=>$arcVal){
                    if( isset($dc[$year][$tech])){
                        $this->rc[$year][$tech] = $arcVal + $dc[$year][$tech];
                    }else{
                        $this->rc[$year][$tech] = $arcVal;
                    }
                }
            }
        }
        return $this->rc;
    }

    //Installed Reserved capacity [accumulated-with carryover] 
    public function getIRC_yt() {
        if (!isset($this->irc)) {
            $this->irc = array();
            //additional reserve capacity by yer tech
            $arc = $this->getRC_yt();
            $dc = $this->adjustDRC_yt();
            foreach($arc as $year=>$arcObj){
                foreach($arcObj as $tech=>$arcVal){
                    if(!isset($dc[$year][$tech])){ $dc[$year][$tech] = 0; }
                    if (isset($coc[$tech])){
                        //kumulativni CoC kapacitet
                        $coc[$tech] = $coc[$tech] + $arcVal - $dc[$year][$tech];
                        $this->irc[$year][$tech] = $coc[$tech];
                    }else{
                        $this->irc[$year][$tech] = $arcVal;
                        $coc[$tech] = $arcVal;
                    }
                }
            }
        }
        return $this->irc;
    }

    /////////////////////////////////////////////////Novi kapacitet koji treba graditi i u ojoj gocini, uzimajuci u ibziru i Resreve Capacity
    //total additional new capacity to be buold in year by tech. To je AC (Additiona CopCITY) + RC (reserve Capacity) with decomissioning accounted
    public function getTAC_d_yt() {
        if (!isset($this->tac)) {
            $this->tac = array();
            $rc = $this->getRC_yt();
            $ac = $this->getAC_d_yt();
            $years = $this->getYears();
            $techs = $this->getTechs();
            foreach($years as $year){
                foreach ($techs as $tec){
                    if($tec != 'ImportExport' && $tec != 'Storage'){
                        if(!isset($rc[$year][$tec])){ $rc[$year][$tec] = 0; }
                        if(!isset($ac[$year][$tec])){ $ac[$year][$tec] = 0; }
                        $this->tac[$year][$tec] = $rc[$year][$tec] + $ac[$year][$tec];
                    }
                }
            }
        }
        return $this->tac;
    }

    //getTIC Total Installed Capacity [Installed Capacity + Additional Capacity] by year by techs with Reserve Capacity
    public function getTIC_yt() {
        if (!isset($this->tic_yt)) {
            $this->tic_yt = array();
            $iac = $this->getIAC_yt();
            //$rc = $this->getRC_yt();
            $rc = $this->getIRC_yt();
            $elImport = $this->getElImport_y_mw();
            foreach($iac as $year=>$iacObj){
                foreach($iacObj as $tech=>$iacVal){
                    if(!isset($rc[$year][$tech])){ $rc[$year][$tech] = 0; }
                    $this->tic_yt[$year][$tech] = $iacVal + $rc[$year][$tech];
                }
                $this->tic_yt[$year]['ImportExport'] = $elImport[$year];
            }
        }
        return $this->tic_yt;
    }

    //getTDC Total Dispatchable Capacity by year
    public function getTDC_y() {
        if (!isset($this->tdc)) {
            $this->tdc = array();
            $tic = $this->getTIC_yt();
            foreach($tic as $year=>$ticObj){
                foreach($ticObj as $tech=>$ticVal){
                    if(!in_array($tech, Constant::HourlyAnalysisTech) && $tech!='ImportExport') {
                        if(!isset($this->tdc[$year])){ $this->tdc[$year] = 0; }
                        $this->tdc[$year] = $this->tdc[$year] + $ticVal;
                    }
                }
            }
        }
        return $this->tdc;
    }


    //getTIC Total Installed Capacity [Installed Capacity + Additional Capacity] by year with Reserve Capacity [all Techs]
    public function getTIC_y() {
        if (!isset($this->tic_y)) {
            $this->tic_y = array();
            $iac = $this->getIAC_y();
            $rc = $this->getRC_yt();
            foreach($iac as $year=>$val){
                $this->tic_y[$year] = $val + $rc[$year]['Total'];
            }
        }
        return $this->tic_y;
    }

    //getInvestment COst by year tech in 10^6 millions of Currency
    public function getInvC_yt() {
        if (!isset($this->invc)) {
            $this->invc = array();
            $ac = $this->getAC_d_yt();
            $c = $this->getCost_yt();
            $rc = $this->getRC_yt();

            foreach($ac as $year=>$acObj){
                foreach ($acObj as $tech=>$acVal){
                    if(in_array($tech, Constant::ReserveCapacityTechs)){
                        $this->invc[$year][$tech] = ($acVal + $rc[$year][$tech]) * $c[$year][$tech]['Investment_cost'] / 1000;
                    }else{
                        $this->invc[$year][$tech] = $acVal * $c[$year][$tech]['Investment_cost'] / 1000;
                    }
                }
            }
        }
        return $this->invc;
    }
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

}

//  $emission = new EsstCaseClass('DEMO CASE 1');

 
//  echo " getElD_yt() NOVI";
//  echo "<pre>";
//  print_r($emission->getElD_yt());
//  echo "</pre>";

//  echo " getSES_yc() NOVI";
// echo "<pre>";
// print_r($emission->getSES_yc());
// echo "</pre>";

// echo " getTPES_yt() NOVI";
// echo "<pre>";
// print_r($emission->getTPES_yt());
// echo "</pre>";

// echo " getRC_yt() ";
// echo "<pre>";
// print_r($emission->getRC_yt());
// echo "</pre>";

// echo " getTIC_y() ";
// echo "<pre>";
// print_r($emission->getTIC_y());
// echo "</pre>";

// echo " getDC_yt() ";
// echo "<pre>";
// print_r($emission->getDC_yt());
// echo "</pre>";

// echo " getIE_yt_u() ";
// echo "<pre>";
// print_r($emission->getIE_yt_u()['1990']['Hydro']);
// echo "</pre>";

// echo " getCF() ";
// echo "<pre>";
// print_r($emission->getCF_yt()['1990']['Hydro']);
// echo "</pre>";

// echo " getElImport_y_mw()2 ";
// echo "<pre>";
// print_r($emission->getElImport_y_mw());
// echo "</pre>";

// echo " getSESElMix_yt() ";
// echo "<pre>";
// print_r($emission->getSESElMix_yt());
// echo "</pre>";

// echo " getTFC() ";
// echo "<pre>";
// print_r($emission->getTFC());
// echo "</pre>";

?>