<?php
//require_once('../log4php/Logger.php');
 //define('ROOT_DIR','C:\wamp64\www\ESST\esst.ver.2.7.0');
require_once ROOT_DIR.'/config.php';
//require_once ROOT_DIR.'/classes/paths.php';

class EsstCase {
    public $xml;
    public $calculatedPower = array();
    public $SESP2 = array();
    public $lifeTime;
    public $installedPower = array();
    public $existingPower = array();
    public $totalNeededPower = array();
    public $totalNeededPowerAllFuels = array();
    public $reserveCapacityPower = array();
    public $ensCapacityPower = array();
    public $totalInstalledPower = array();
    public $capacityFactorArray = array();
    public $getLifetimeArray = array();
    public $totalInstalledPowerDispatchable = array();
    public $IntermittentTechs = array("Hydro","Wind","Solar");
    public $ReserveCapacityTechs = array("Coal","Oil","Gas");
    public $EtaTechs = array("Coal"=>36,"Oil"=>38,"Gas"=>45, "Biofuels"=>35, "Waste"=>35, "Nuclear"=>33);

    public $EnergyCommodities =  array("Coal", "Oil", "OilShale", "Gas", "Biofuels", "Peat", "Waste", "Solar", "Wind", "Hydro", "Geothermal", "Nuclear", "ImportExport", "Electricity", "Heat");
    
    public $HourlyAnalysisTech = array("Hydro","Wind","Solar");
    
	public function __construct($pCase){
        //$this->logger = Logger::getLogger(get_class($this));
        //$this->xml = $pXml;
   	    if (!isset($this->xml)) {
            $filepath = USER_CASE_PATH.$pCase."/".$pCase.".xml";
            if (file_exists($filepath)) {
                $this->xml = simplexml_load_file($filepath)
                or die("Error: Cannot create object");
               //print_r($this->xml);
            }
        }
	}

//////////////////////////////////////////////// CASES MANAGEMENT ///////////////////////////////////////////////////////////////////////////////////////////////
    // //return all case studies in array form
	// public static function getAllCaseStudies(){
	// 	if ($handle = opendir(ROOT_DIR."/xml/")) {
	// 		$caseStudies = array();
    //         $i=0;
	// 		while (false !== ($file = readdir($handle))) {
	// 			if($file != '.' && $file != '..' && is_dir(ROOT_DIR."/xml/".$file)){
    // 				$caseStudies[$i]['title'] = $file;
    //                 $caseStudies[$i]['type'] = 'ps';  
    //                 $caseStudies[$i]['desc'] =  (string)simplexml_load_file( ROOT_DIR."/xml/".$file."/".$file.".xml")->Case->description;  
    //                 $i++;                
	// 			}  
	// 		}
	// 		return $caseStudies;
	// 		closedir($handle);
	// 	}
	// 	return false;
    // }
/*
    //update planning study description
   	public static function updateCaseName($src, $dst, $pCase){
        $getDataurl = $dst."/" .$pCase.'.xml';
        $saveUrl = $dst."/" .$pCase.'_copy.xml';
        if (file_exists($getDataurl)){
            $xml = simplexml_load_file($getDataurl);
            $xml->Case[0]->name = $_POST['case']."_copy";
            $xml->asXML($getDataurl);
            rename($getDataurl, $saveUrl);
        }
		return true; 
   }

    public function getCaseDescription() {
		//if (!isset($this->description)){
			$this->description = $this->xml->Case->description;           
		//}
		return $this->description;
	}

       //backup planning study 
   	public static function backupCase($pCase){
        if(isset($pCase)){
            // Get real path for our folder
            $rootPath = realpath(ROOT_DIR."/xml/".$pCase);

            // Initialize archive object
            $zip = new ZipArchive();
            $zip->open($rootPath.'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
            //$zip->open($rootPath.'.zip', ZipArchive::CREATE);

            // Create recursive directory iterator
            // @var SplFileInfo[] $files //
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($rootPath),
                RecursiveIteratorIterator::LEAVES_ONLY
            );
            foreach ($files as $name => $file) {
                // Skip directories (they would be added automatically)
                if (!$file->isDir())
                {
                    // Get real and relative path for current file
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($rootPath) + 1);
                    // Add current file to archive
                    //$zip->addFile($filePath, $relativePath);
                    $zip->addFile($filePath, $pCase. '/'. $relativePath);
                }
            }
            // Zip archive will be created only after closing object
            $zip->close();
        }
    }
    
    */




    //XML FUNC///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //vraca godna kakve su u xml ['_1990'=>0, '_2000'=>1....]
	public function getYears() {
		if (!isset($this->years)) {
			$this->years = array();
			//$this->logger->debug( print_r($this->xml->units, true) );
			foreach($this->xml->Years[0] as $aYr) {
				//$this->logger->info( "<br/>".print_r($aYr, true) );
				$this->years[$aYr->getName()] = (String)$aYr;
			}
		}
		//$this->logger->info( print_r($this->years, true) );
		return $this->years;
    }
    
    //daje samo niz godina koje su izabrane u fazi 1 ['2000', '2010'...]
    public function getYearsArray() {
		if (!isset($this->yearsArr)) {
			$this->yearsArr = array();
			foreach($this->xml->Years[0] as $aYr) {
                if($aYr == '1'){
                    //$this->yearsArr[$aYr->getName()] = (String)$aYr;
                    $yr = substr($aYr->getName(), 1);
                    array_push($this->yearsArr, $yr);
                }	
			}
		}
		return $this->yearsArr;
	}
    
    public function getUnit() {
		if (!isset($this->unit)){
			$this->unit = $this->xml->Case->units;           
		}
		return $this->unit;
	}
    
    public function getCurrency() {
		if (!isset($this->currency)) {
			$this->currency = $this->xml->Case->currency;          
		}
		return $this->currency;
	}
	
	public function getSectors() {
		if (!isset($this->sectors)) {
			$this->sectors = array();
			foreach($this->xml->Sectors[0] as $aSec) {
                //echo "sec " .  $aSec . ' name ' . $aSec->getName() . "<br>";
				$this->sectors[$aSec->getName()] = (String)$aSec;
			}
		}
		return $this->sectors;
    }
    
    //daje samo niz sectora koje su izabrane u fazi 1 ['Industry', 'Treansport'...]
    public function getSectorsArray() {
        if (!isset($this->sectorsArr)) {
            $this->sectorsArr = array();
            foreach($this->xml->Sectors[0] as $aSc) {
                if($aSc == '1'){
                    //$this->yearsArr[$aYr->getName()] = (String)$aYr;
                    //$sc = substr($aSc->getName(), 1);
                    $sc = $aSc->getName();
                    array_push($this->sectorsArr, $sc);
                }	
            }
        }
        return $this->sectorsArr;
    }
    
   	public function getFuels() {
		if (!isset($this->fuels)) {
			$this->fuels = array();
			foreach($this->xml->Fuels[0] as $aFuel) {
				$this->fuels[$aFuel->getName()] = (String)$aFuel;   
			}
		}
		return $this->fuels;
	}
	
    public function getElMixFuels() {
		if (!isset($this->elmix_fuels)) {
			$this->elmix_fuels = array();
			foreach($this->xml->ElMix_fuels[0] as $aFuel) {
				$this->elmix_fuels[$aFuel->getName()] = (String)$aFuel;               
			}
		}
		return $this->elmix_fuels;
    }

    //daje samo niz sectora koje su izabrane u fazi 1 ['Industry', 'Treansport'...]
    public function getTechsArray() {
        if (!isset($this->techsArr)) {
            $this->techsArr = array();
            foreach($this->xml->ElMix_fuels[0] as $aTe) {
                if($aTe == '1'){
                    //$this->yearsArr[$aYr->getName()] = (String)$aYr;
                    //$te = substr($aTe->getName(), 1);
                    $te = $aTe->getName();
                    array_push($this->techsArr, $te);
                }	
            }
        }
        return $this->techsArr;
    }

        
    public function getCarbonContent() {
        if (!isset($this->carbon)) {
			$this->carbon = array();
			foreach($this->xml->xpath('//tra_carbon_cost') as $fbs) {
                $yr = $fbs['year'];
                foreach($fbs as $carbonCost => $val) {
                    if($val != ""){
                        //echo "fuel " . $carbonCost . " val " . $val. "<br>";     
                        $this->carbon[(string)$yr] = (string)$val[0];
                    }
                }
			}
        }
		return $this->carbon;
    }


    
    public function getDiscountRate() {
        if (!isset($this->discount)) {
			$this->discount = array();
			foreach($this->xml->xpath('//tra_discount_rate') as $fbs) {
                $yr = $fbs['year'];
                foreach($fbs as $discountRate => $val) {
                    if($val != ""){
                        //echo "fuel " . $carbonCost . " val " . $val. "<br>";     
                        $this->discount[(string)$yr] = (string)$val[0];
                    }
                }
			}
        }
		return $this->discount;
	}
    
    public function getEfficiency() {
		if (!isset($this->efficiency)) {
			$this->efficiency = array();
			foreach($this->xml->Transformation->tra_efficiency[0] as $aEfficiency) {
				$this->efficiency[$aEfficiency->getName()] = (String)$aEfficiency;               
			}
		}
		return $this->efficiency;
	}
    
    public function getTechnical() {
		if (!isset($this->technical)) {
			$this->technical = array();
			foreach($this->xml->Transformation->tra_technical[0] as $aTechnical=>$aValue) {
				$this->technical[$aTechnical] = (String)$aValue;               
			}
		}
		return $this->technical;
	}
    
    public function getEnvironment() {
		if (!isset($this->environment)) {
			$this->environment = array();
			foreach($this->xml->Transformation->tra_envirioment[0] as $aEnvironment) {
				$this->environment[$aEnvironment->getName()] = (String)$aEnvironment;               
			}
		}
		return $this->environment;
	}
    
    public function getFinance() {
		if (!isset($this->finance)) {
			$this->finance = array();
			foreach($this->xml->Transformation->tra_finance[0] as $aFinance) {
				$this->finance[$aFinance->getName()] = (String)$aFinance;               
			}
		}
		return $this->finance;
	}
    

    // public function getFinanceArray() {
	// 	if (!isset($this->financeArray)) {
    //         $this->financeArray = array();
            
    //         $years = $this->getYearsArray();
    //         $techs = $this->getTechsArray();

    //         foreach($this->xml->xpath('//tra_finance') as $fbs) {
    //             $yr  = $fbs['year'];
    //             $tec = $fbs['technology'];
    //             if ( in_array($yr, $years) && in_array($tec, $techs) ){
    //                 foreach($fbs as $type => $val) {
                        
    //                     if($val != ''){
    //                        // echo  "type " . $type . " val " . $val . "<br>";
    //                         $this->financeArray[(String)$yr][(String)$tec][(String)$type] = (string)$val[0];
    //                     }else{
    //                         //echo "val prazno " . $val . "<br>";
    //                         $this->financeArray[(String)$yr][(String)$tec][(String)$type]  = 0;
    //                     }
                        
    //                 }
    //             }
    //         }
	// 	}
	// 	return $this->financeArray;
    // }
    
    public function getFinanceArray() {
		if (!isset($this->financeArray)) {
            $this->financeArray = array();
            
            $years = $this->getYearsArray();
            $techs = $this->getTechsArray();

            $this->financeArray = array();
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

    
	// public function getFedBySector($pYear, $pSector) {
	// 	if (!isset($this->fedBySector)) {
	// 		$this->fedBySector = array();
	// 		//foreach($this->xml->xpath('//fed_bysectors') as $fbs) { // Cases/Final_energy_demand/fed_bysectors
    //         foreach($this->xml->Final_energy_demand->fed_bysectors as $fbs) { // Cases/Final_energy_demand/fed_bysectors
	// 			$yr = $fbs['year'];
	// 			$this->fedBySector[(String)$yr] = array();
	// 			foreach($fbs as $sec => $val) {
	// 				//$this->logger->info( "FBS yr:".$yr." sec:".$sec." val:".$val."<br/>");
	// 				$this->fedBySector[(String)$yr][(String)$sec] = $val;
	// 			}
	// 			//$this->logger->info( "<br/>FBS:".$yr.":".print_r($fbs, true) );
	// 			//$this->years[$aYr->getName()] = (String)$aYr;
	// 		}
	// 	}
    //     //print_r($this->fedBySector[2000]['Industry']);
	// 	//$retval = $GLOBALS['xml']->xpath(sprintf('//fed_bysectors[@year="%s"]/%s', $pYear, $pSector));
	// 	$retval = $this->fedBySector[(String)$pYear][(String)$pSector];
	// 	//$this->logger->info( "RETVAL ".print_r($retval, true)." - ".$retval2." <br/>" );
	// 	return (float)$retval[0]; 
	// }


    //nema potrebe da se vrte dvije foreach petlje jer je povratna vrijednost jedan broj, 
    //gonji kod je dobar za pravljenje citavog 2D niza od fed_bysectors
    	public function getFedBySector($pYear, $pSector) {
    		if (!isset($this->fedBySector)) {
    			$retval = $this->xml->xpath(sprintf('//fed_bysectors[@year="%s"]/%s', $pYear, $pSector));	
    		}      
    		return (string)$retval[0]; 
    	}
	
	// public function getFedFuelShare($pYear, $pSector, $pFuel) {
	// 	if (!isset($this->fedFuelShare)) {
	// 		$this->fedFuelShare = array();
	// 		foreach($this->xml->xpath('//fed_fuelshares') as $fbs) {
	// 			$yr  = $fbs['year'];
	// 			$sec = $fbs['sector'];
	// 			foreach($fbs as $fuel => $val) {
    //                 if($val != ''){
    //                     $this->fedFuelShare[(String)$yr.'|'.(String)$sec.'|'.(String)$fuel] = $val;
    //                 }else{
    //                     $this->fedFuelShare[(String)$yr.'|'.(String)$sec.'|'.(String)$fuel] = 0;
    //                 }
					
	// 			}
	// 		}
    //     }
    //     if(isset($this->fedFuelShare[(String)$pYear.'|'.(String)$pSector.'|'.(String)$pFuel])){
    //         $retval = $this->fedFuelShare[(String)$pYear.'|'.(String)$pSector.'|'.(String)$pFuel];
    //     }
    //     else{
    //         $retval = 0;
    //     }
    //     //echo "fed fuel share " . $retval . " return value " . (string)$retval;
	// 	return (float)$retval[0]; 
	// }
	
	public function getFedFuelShare($pYear, $pSector, $pFuel) {
		if (!isset($this->FedFuelShare)) {
            $FedFuelShare = $this->xml->xpath(sprintf('//fed_fuelshares[@year="%s" and @sector="%s"]/%s', $pYear, $pSector, $pFuel));

            // echo "fule share";
            // echo "<pre>";
            // print_r($FedFuelShare);
            // echo "</pre>";
            // echo "year " . $pYear . " sector " . $pSector . " fuel " . $pFuel . " fuel share " . $FedFuelShare[0] . "</br>";
            if (isset($FedFuelShare[0])){
                $retval = (string)$FedFuelShare[0];
            }else{
                $retval = 0;
            }
		}
		return $retval; 
	}
    
	// public function getFedLosses($pYear, $pFuel) {
	// 	//if (!isset($this->fedLosses)) {
	// 		$this->fedLosses = array();
	// 		foreach($this->xml->xpath('//fed_losses') as $fbs) {
	// 			$yr = $fbs['year'];
	// 			$this->fedLosses[(String)$yr] = array();
	// 			foreach($fbs as $fuel => $val) {
	// 				//$this->logger->info( "LOSS yr:".$yr." sec:".$fuel." val:".$val."<br/>");
	// 				$this->fedLosses[(String)$yr][(String)$fuel] = $val;
	// 			}
	// 		//}
	// 	}
	// 	//$retval2 = //$GLOBALS[xml]->xpath(sprintf('//fed_losses[@year="%s"]/%s', $year, $fuel)); 
    //     //if (array_key_exists($pFuel, $this->fedLosses)) {
    //     if(isset($this->fedLosses[(String)$pYear][(String)$pFuel])){
    //         $retval = $this->fedLosses[(String)$pYear][(String)$pFuel];
    //     }else{
    //         $retval = 0;
    //     }
		
    //     //print_r($this->fedLosses[2000]['Electricity']);
    //     // print_r($this->fedLosses);
	// 	//$this->logger->info( "RETVAL ".print_r($retval, true)." - ".$retval2." <br/>" );
	// 	return (float)$retval[0]; 
    //     //0}
    //     //else
    //     //return 0;
	// }
    
  	public function getFedLosses($pYear, $pFuel){
		//if (!isset($this->fedLosses)) {
           $fedLosses = $this->xml->xpath(sprintf('//fed_losses[@year="%s"]/%s',  $pYear, $pFuel));
        //    echo "fed losses array </br>";
        //    echo "year " .  $pYear . " fuel " . $pFuel . "</br>";
           //print_r($fedLosses);
           if (isset($fedLosses[0]) && $fedLosses[0] != ""){
                $this->fedLosses = (string)$fedLosses[0];
            }else{
                $this->fedLosses = 0;
            }
            
        //}
	    return $this->fedLosses; 
	}

	// public function getInstalledPower($pYear, $pTec) {
	// 	//if (!isset($this->installedPower)) {
	// 		$this->installedPower = array();
	// 		$this->capacityFactor = array();
	// 		foreach($this->xml->xpath('//tra_technical') as $fbs) {
	// 			$yr  = $fbs['year'];
	// 			$tec = $fbs['technology'];
	// 			$this->installedPower[(String)$yr.'|'.(String)$tec] = $fbs->Installed_power;
	// 			//$this->capacityFactor[(String)$yr.'|'.(String)$tec] = $fbs->Capacity_factor;
	// 		}
	// 	//}
	// 	$retval = $this->installedPower[(String)$pYear.'|'.(String)$pTec];
	// 	return (string)$retval[0]; 
	// }
    
     public function getInstalledPower($pYear, $pTec) {
    	//if (!isset($this->installedPower)) {
    	    $installedPower = $this->xml->xpath(sprintf('//tra_technical[@year="%s" and @technology="%s"]/Installed_power', $pYear, $pTec)); 	
           // echo  " pTec ". $pTec . " pYear " .  $pYear . " installe power " . $installedPower . "<br>";

            if (isset($installedPower[0]) && $installedPower[0] != ""){
                $this->installedPower = (string)$installedPower[0];
            }else{
                $this->installedPower = 0;
            }
        //}
        //echo  " pTec ". $pTec . " pYear " .  $pYear . " installe power " . $installedPower[0] . "<br>";
        // echo "installedPower";
        // echo "<pre>";
        // print_r($this->installedPower);
        // echo "</pre>";
        // echo "installed power "  . $this->installedPower . "<br>";
        return $this->installedPower;	
    }
        
	public function getSesElmix($pYear, $pFuel) {
		if (!isset($this->sesElmix)) {
			$this->sesElmix = array();
			foreach($this->xml->xpath('//ses_elmix') as $fbs) { 
				$yr = $fbs['year'];
				$this->sesElmix[(String)$yr] = array();
				foreach($fbs as $fuel => $val) {
                    //$this->logger->info( "FBS yr:".$yr." fuel:".$fuel." val:".$val."<br/>");
                    //echo  " fuel ". $fuel . " val " .  $val . "<br>";
                    if($val != ''){
                        $this->sesElmix[(String)$yr][(String)$fuel] = $val;
                    }else{
                        $this->sesElmix[(String)$yr][(String)$fuel] = 0;
                    }
					
				}
				//$this->logger->info( "<br/>FBS:".$yr.":".print_r($fbs, true) );
				//$this->years[$aYr->getName()] = (String)$aYr;
			}
        }
        if ( isset($this->sesElmix[(String)$pYear][(String)$pFuel]) && $this->sesElmix[(String)$pYear][(String)$pFuel] != 0 ){ 
           // echo "elmic real XML " . $this->sesElmix[(String)$pYear][(String)$pFuel] .  "<br>";
            $retval = (string)$this->sesElmix[(String)$pYear][(String)$pFuel][0];
            //echo "elmic real XML string " . $retval . "<br>";
        }else{
            $retval = 0;
        }
        //print_r($this->sesElmix);
		//$retval2 = $GLOBALS['xml']->xpath(sprintf('//fed_bysectors[@year="%s"]/%s', $pYear, $pFuel));
		//$retval = $this->sesElmix[(String)$pYear][(String)$pFuel];
		//$this->logger->info( "RETVAL ".print_r($retval, true)." - ".$retval2." <br/>" );
		//return (float)$retval[0];
        return $retval; 
    }
    
    // public function getEmissionFactor($pYear, $pTec, $pFactor) {
	// 	if (!isset($this->emissionFactor)) {
	// 		$this->emissionFactor = array();
	// 		foreach($this->xml->Transformation->tra_envirioment as $fbs) {
	// 			$yr  = $fbs['year'];
	// 			$tec = $fbs['technology'];
	// 			$this->emissionFactor[(String)$yr.'|'.(String)$tec.'|'.(String)$pFactor] = $fbs->$pFactor;
	// 		}
    //         //echo $this->xml->Transformation->tra_envirioment[(String)$pYear.'|'.(String)$pTec.'|'.(String)$pFactor];
    //     }
    //     //print_r($this->emissionFactor);
	// 	$retval = $this->emissionFactor[(String)$pYear.'|'.(String)$pTec.'|'.(String)$pFactor];
	// 	return $retval; 
	// }
   
    public function getEmissionFactor($pYear, $pTec, $pFactor) {
    	if (!isset($this->emissionFactor)) {
    	    $emissionFactor = $this->xml->xpath("//tra_envirioment[@year='{$pYear}' and @technology='{$pTec}']/{$pFactor}"); 
    	}
    	return (string)$emissionFactor[0]; 
    }

    // public function getEmissionFactorArray() {
    //     if (!isset($this->emissionFactor)) {
    //         $years = $this->getYearsArray();
    //         $techs = $this->getTechsArray();
    //         $this->emissionFactor = array();
    //         foreach($this->xml->xpath('//tra_envirioment') as $fbs) {
    //             $yr  = $fbs['year'];
    //             $tec = $fbs['technology'];
    //             if ( in_array($yr, $years) && in_array($tec, $techs) ){
    //                 foreach($fbs as $type => $val) {
                        
    //                     if($val != ''){
    //                        // echo  "type " . $type . " val " . $val . "<br>";
    //                         $this->emissionFactor[(String)$yr][(String)$tec][(String)$type] = (string)$val[0];
    //                     }else{
    //                         //echo "val prazno " . $val . "<br>";
    //                         $this->emissionFactor[(String)$yr][(String)$tec][(String)$type]  = 0;
    //                     }
                        
    //                 }
    //             }
    //         }
    //     }
    //     return $this->emissionFactor;
    // }

    public function getEmissionFactorArray() {
        if (!isset($this->emissionFactor)) {
            $years = $this->getYearsArray();
            $techs = $this->getTechsArray();
            $this->emissionFactor = array();
            foreach($years as $year){
                foreach ($techs as $tech){
                    if ($tech != 'ImportExport'){
                        $node = $this->xml->xpath("//tra_envirioment[@year='{$year}'and @technology='{$tech}']");
                        foreach($node[0] as $type => $val) {
                            $this->emissionFactor[$year][$tech][(string)$type] = (string)$val[0];
                        }
                    }
                }
            }
        }
        return $this->emissionFactor;
    }


    //izracunava stvarnu vrijednos gubitaka u sistemu po gorivima
    public function getFedLossesValue ($fuel, $year){    
        $losses = $this->getFedLosses($year, $fuel);
        $ses = $this->getSES($fuel, $year);
        $lossesValue = $ses*$losses/100;   
        return $lossesValue;
    }

    //////////////////////////////////////////////////////XML funkcije koje vracaju nizove
    public function getReserveCapacity() {
		//if (empty($this->reserveCapacity)) {
			$this->reserveCapacity = array();
			foreach($this->xml->xpath('//tra_reserve_capacity') as $fbs) { 
				$yr = $fbs['year'];
                $total = $fbs['total'];
                $this->reserveCapacity[(string)$yr]["total"] = (string)$total[0];
				foreach($fbs as $fuel => $val) {
					//$this->logger->info( "FBS yr:".$yr." fuel:".$fuel." val:".$val."<br/>");
                   // echo $val."<br>";
					$this->reserveCapacity[(string)$yr][(string)$fuel] = (string)$val[0];
				}
				//$this->logger->info( "<br/>FBS:".$yr.":".print_r($fbs, true) );
				//$this->years[$aYr->getName()] = (String)$aYr;
			}
		//}
		$retval = $this->reserveCapacity;
		return $retval;
	}

    public function getEnsCapacity() {
		if (empty($this->ensCapacity)) {
			$this->ensCapacity = array();
			foreach($this->xml->xpath('//ens_capacity') as $fbs) { 
				$yr = $fbs['year'];
				foreach($fbs as $fuel => $val) {
					$this->ensCapacity[(string)$yr][(string)$fuel] = (string)$val[0];
				}
			}
		}
		$retval = $this->ensCapacity;
		return $retval;
	}

     public function getFinanceData() {
		if (empty($this->finance)) {
			$this->finance = array();
			foreach($this->xml->xpath('//tra_finance') as $fbs) { 
				$yr = $fbs['year'];
                $tech = $fbs['technology'];
				foreach($fbs as $key => $val) {
					$this->finance[(string)$key][(string)$yr][(string)$tech] = (string)$val[0];
				}
			}
		}
		$retval = $this->finance;
		return $retval;
	}
    
    public function getSesElmixP2() {
		if (!isset($this->sesElmixP2)) {
            $this->sesElmixP2 = array();
           // echo 'XML';
            //print_r($this->xml);
			foreach($this->xml->xpath('//ses_elmix') as $fbs) { 
				$yr = $fbs['year'];
				//$this->sesElmix[(String)$yr] = array();
				foreach($fbs as $fuel => $val) {
					//$this->logger->info( "FBS yr:".$yr." fuel:".$fuel." val:".$val."<br/>");
                   // echo $val."<br>";
					$this->sesElmixP2[(string)$yr][(string)$fuel] = (string)$val[0];
				}
				//$this->logger->info( "<br/>FBS:".$yr.":".print_r($fbs, true) );
				//$this->years[$aYr->getName()] = (String)$aYr;
			}
		}
		$retval = $this->sesElmixP2;
		return $retval;
    }
    
    //	$retval = $this->xml->xpath(sprintf('//sesElmix[@year="%s"]/%s', $pYear, $pFuel));
    //   	public function getSesElmix($pYear, $pFuel) {
    //		if (!isset($this->sesElmix)) {
    //		$sesElmix = $this->xml->xpath(sprintf('//ses_elmix[@year="%s"]/%s', $pYear, $pFuel));
    //        $retval = $sesElmix;
    //			}
    //		return (string)$retval[0]; 
    //	}

    ///////////////////////////////////////////////////////////XML functions bz zear fuel parametetrs///////////////////////////////////////////////////////////////////////////
    public function getEfficiencyByYearFuel($pYear, $pFuel) {
		if (!isset($this->Efficiency)) {
			$this->Efficiency = array();
			foreach($this->xml->xpath('//tra_efficiency') as $fbs) {
				$yr = $fbs['year'];
				$this->Efficiency[(String)$yr] = array();
				foreach($fbs as $fuel => $val) {
					//$this->logger->info( "LOSS yr:".$yr." sec:".$fuel." val:".$val."<br/>");
					$this->Efficiency[(String)$yr][(String)$fuel] = $val;
				}
			}
		}
		//$retval2 = //$GLOBALS[xml]->xpath(sprintf('//fed_losses[@year="%s"]/%s', $year, $fuel)); 
        if ($pFuel!='ImportExport')
            //$retval = $this->Efficiency[(String)$pYear][(String)$pFuel];
            if (isset($this->Efficiency[(String)$pYear][(String)$pFuel])){
                $retval = (string)$this->Efficiency[(String)$pYear][(String)$pFuel][0];
            }else{
                $retval = 0;
            }
        else
            $retval = 0;
		//$this->logger->info( "RETVAL ".print_r($retval, true)." - ".$retval2." <br/>" );
        // return (string)$retval[0]; 
        return $retval; 
    }
    
    public function getReserveCapacityByYearFuel($pYear, $pFuel) {
		if (!isset($this->ReserveCapacity)) {
			foreach($this->xml->xpath('//tra_reserve_capacity') as $fbs) {
				$yr = $fbs['year'];
				$this->ReserveCapacity[(String)$yr] = array();
				foreach($fbs as $fuel => $val) {
					$this->ReserveCapacity[(String)$yr][(String)$fuel] = $val;
				}
			}
		}
        if ($pFuel!='ImportExport')
		  $retval = $this->ReserveCapacity[(String)$pYear][(String)$pFuel];
        else
            $retval = 0;
		return (string)$retval[0]; 
	}

    public function getReserveCapacityTotalByYear($pYear) {
		if (!isset($this->ReserveCapacityTotal)) {
			foreach($this->xml->xpath('//tra_reserve_capacity') as $fbs) {
                $yr = $fbs['year'];
                $total = $fbs['total'];
	            $this->ReserveCapacityTotal[(String)$yr] = (String)$total;
			}
		}
		$retval = $this->ReserveCapacityTotal[(String)$pYear];
 
		return (string)$retval; 
    }
    
    //public function getEfficiencyByYearFuel($pYear, $pFuel) {
//		if (!isset($this->efficiency)) {
//			$efficiency = $this->xml->xpath(sprintf('//tra_efficiency[@year="%s"]/%s', $pYear, $pFuel));
//		}
//        print_r($efficiency[0]);
//      	return (string)$efficiency[0];
//        //return $efficiency;
//	}
	
    
    public function getDomProduction($pYear, $pFuel) {
		if (!isset($this->domProduction)) {
			$this->domProduction = array();
			foreach($this->xml->xpath('//pes_domestic_production') as $fbs) {
				$yr = $fbs['year'];
				$this->domProduction[(String)$yr] = array();
				foreach($fbs as $fuel => $val) {
					//$this->logger->info( "LOSS yr:".$yr." sec:".$fuel." val:".$val."<br/>");
					$this->domProduction[(String)$yr][(String)$fuel] = $val;
				}
			}
		}
        //$retval2 = //$GLOBALS[xml]->xpath(sprintf('//fed_losses[@year="%s"]/%s', $year, $fuel)); 
        if (isset($this->domProduction[(String)$pYear][(String)$pFuel])){
            $retval = $this->domProduction[(String)$pYear][(String)$pFuel];
        }else{
            $retval = 0;
        }

        //$this->logger->info( "RETVAL ".print_r($retval, true)." - ".$retval2." <br/>" );
        //print_r($this->domProduction);
		return (string)$retval[0]; 
	}    
    
    //     $result = $GLOBALS['xml']->xpath(sprintf('//pes_domestic_production[@year="%s"]/%s',$year, $fuel));
    // $production = (int)$result[0];

    public function getDomProductionByID($pYear, $pFuel) {
		if (!isset($this->domProdByID)) {
		    $domProdByID = $this->xml->xpath("//pes_domestic_production[@year='{$pYear}']/{$pFuel}");
		}
		return (string)$domProdByID[0]; 
	}    
    
   	public function getCapacityFactor($pYear, $pTec) {
		//if (!isset($this->capacityFactor)) {
			$this->installedPower = array();
			$this->capacityFactor = array();
			foreach($this->xml->Transformation->tra_technical as $fbs) {
				$yr  = $fbs['year'];
				$tec = $fbs['technology'];
				//$this->installedPower[(String)$yr.'|'.(String)$tec] = $fbs->Installed_power;
				$this->capacityFactor[(String)$yr.'|'.(String)$tec] = $fbs->Capacity_factor;
			}
            //echo $this->xml->Transformation->tra_technical[(String)$pYear.'|'.(String)$pTec];
		//}
		$retval = $this->capacityFactor[(String)$pYear.'|'.(String)$pTec];
			return (string)$retval[0]; 
    }
    

    public function getCF_old() {
		if (isset($this->capacityFactorArray) && empty($this->capacityFactorArray) ) {
            $years = $this->getYearsArray();
            $techs = $this->getTechsArray();
			foreach($this->xml->Transformation->tra_technical as $fbs) {
				$yr  = $fbs['year'];
                $tec = $fbs['technology'];
                if ( in_array($yr, $years) && in_array($tec, $techs) ){
                    if($fbs->Capacity_factor != ""){
                        //echo 'year ' . $yr . " tech " . $tec . " cap fac " . $fbs->Capacity_factor ."<br>";
                        $this->capacityFactorArray[(String)$yr][(String)$tec] = (string)$fbs->Capacity_factor[0];
                    }else{
                        $this->capacityFactorArray[(String)$yr][(String)$tec] = 0;
                    }
                }
			}
            //echo $this->xml->Transformation->tra_technical[(String)$pYear.'|'.(String)$pTec];
		}
		//$retval = $this->capacityFactorArray[(String)$pYear.'|'.(String)$pTec];
        //return (string)$retval[0]; 
        return $this->capacityFactorArray;
    }
    
    
    public function getCF() {
		if (isset($this->capacityFactorArray) && empty($this->capacityFactorArray) ) {
            $years = $this->getYearsArray();
            $techs = $this->getTechsArray();

            foreach($years as $year){
                foreach ($techs as $tech){
                    if ($tech != 'ImportExport'){
                        $node = $this->xml->xpath("//tra_technical[@year='{$year}'and @technology='{$tech}']");
                        foreach($node[0] as $type => $val) {
                            if($type == 'Capacity_factor'){
                                $this->capacityFactorArray[$year][$tech] = (string)$val[0];
                            }
                        }
                    }
                }
            }
        }
        return $this->capacityFactorArray;
	}

    //   	public function getCapacityFactor($pYear, $pTec) {
    //		if (!isset($this->capacityFactor)) {
    //		$capacityFactor = $this->xml->xpath("//tra_technical[@year='$pYear' and @technology='$pTec']/Installed_power");
    //		}
    //		return $capacityFactor[0]; 
    //	}

    //kolicina primarne energije koja se iskoristi za generaciju elekricne energije, dobijemo iz TFC(electricity) kad dodamo gubitne i efikasnost po gorivima
    public function getPrimaryEnergyForElGeneration($fuel, $year) {
        $elDemand = $this->getElDemand($fuel, $year);
        $efficiency =  $this->getEfficiencyByYearFuel($year,$fuel);
        if ($efficiency != 0)
            $fuel = $elDemand*(100/$efficiency);
        else
            $fuel = 0;
        return $fuel;   
    }

        //vrijednosti izlaza iz transformacije za proizvodnju el energije po gorivima
    //na Secondary Energy Supply (El Energija)*SES_El_MIX/100
    public function getElDemand($pFuel, $pYear){    
        $electricity = $this->getSES('Electricity', $pYear);
        $elmix_fuel =  $this->getSesElmix($pYear,$pFuel);

       // echo "electricity " . $electricity . " SES el mix " . $elmix_fuel . "<br>";

        $fuel = $electricity*$elmix_fuel/100;
        return $fuel;  
    }

	public function getLifetime($pTec) {
		if (!isset($this->lifetime)) {
			$this->lifetime = array();
			 foreach($this->xml->Transformation->tra_lifetime as $fbs) {

				$tec = $fbs['technology'];
				$this->lifetime[(String)$tec] = $fbs->Lifetime;
			}
		}
		$retval = $this->lifetime[(String)$pTec];
		return $retval; 
    }
    
    public function getLifetimeArray() {
		//if (!isset($this->LifetimeArray)) {
            $this->LifetimeArray = array();
            $techs = $this->getTechsArray();

			foreach($this->xml->Transformation->tra_lifetime as $fbs) {
                $tec = $fbs['technology'];
                if ( in_array($tec, $techs) ){
                   
                    if($fbs->Lifetime != ""){
                        $this->LifetimeArray[(String)$tec] = (string)$fbs->Lifetime[0];
                    }
                    else{
                        //echo "tech " . $tec . " liftetim " . $fbs->Lifetime . "<br>";
                        $this->LifetimeArray[(String)$tec] = 0;
                    }
                   
                }
				
            }
        //}
		return $this->LifetimeArray; 
	}
    
    //	public function getLifetime($pTec) {
    //	//if (!isset($this->lifetime)) 
    //    //{
    //    	$lifeTime = $this->xml->xpath("//tra_lifetime[@technology='{$pTec}']/Lifetime"); 
    //	//}
    //	    return $this->lifeTime; 
    //	}
    
    public function getTFC($fuel, $year, $sector){
        $share = $this->getFedFuelShare($year,$sector, $fuel);
        $sector_consumtion = $this->getFedBySector($year,$sector); 
        if($share!="" && $sector_consumtion!=""){
            $result = $sector_consumtion*$share/100;
        }else{
            $result = 0;
        }
        return $result;
    }

    public function getTFCtotal($fuel, $year){
        $sectors = $this->getSectors();
        $result = 0;
        foreach($sectors as $sector=>$flag){
            if($flag=='1'){
                $result = $result + $this->getTFC($fuel, $year, $sector);
            }
        }
        return $result;
    }
    
    //Secondary ENergy Supply calc SES = TFC(for all sectors)/(1-losses(year,fuel)/100)
    //TFC = SES - SES*Losses
    public function getSES($fuel, $year){
        $output = 0;
        $tfc = 0;
        $sectors = $this->getSectors();
        foreach($sectors as $key2=>$value2){  
            if($value2 == 1){
                $tfc = $tfc + $this->getTFC($fuel, $year, $key2);
            }                
        }
        $losses = $this->getFedLosses($year,$fuel); 

      // echo  " year " .$year  . " fuel " . $fuel .  " tfc " . $tfc .   " losses " . $losses . "<br>";

        $output = $tfc/(1-$losses/100); 
        return $output;
    }

    
    // //Total Primary Energy Supply  POTREBNO NAPRAVITI!!!!! SSMO KOPIRANO IZ calc_function
    // public function getTPES($fuel, $year) {

    //     $Electricity = $this->getSES('Electricity', $year);
    //     $elmix_fuel = $this->getSesElmix($year,$fuel);  
    //     $efficiency =  $this->getEfficiencyByYearFuel($year,$fuel);
        
    //     if($fuel!='Hydro' && $fuel!='Solar' && $fuel!='Wind' && $fuel!='Geothermal' && $fuel!='Nuclear' && $fuel!='ImportExport')
    //         ${"SES$fuel"} = $this->getSES($fuel, $year);
    //     else
    //         ${"SES$fuel"} = 0;
        
    //     //upisi 0 gdje je efikasnost 0 da bi izbjegli dijeljenje sa nulom
    //     if ($efficiency != 0 && $fuel!='Coal'&& $fuel!='Oil'&& $fuel!='Gas'&& $fuel!='Biofuels'&& $fuel!='Peat'&& $fuel!='Waste'&& $fuel!='Oil_shale')
    //         $tpes = $Electricity*$elmix_fuel/100*(100/$efficiency);
            
    //     else if ($efficiency != 0 && ($fuel=='Coal'|| $fuel=='Oil'|| $fuel=='Gas' || $fuel=='Biofuels' || $fuel=='Peat'|| $fuel=='Waste' || $fuel=='Oil_shale'))
    //         $tpes = $Electricity*$elmix_fuel/100*(100/$efficiency) + ${"SES$fuel"};
            
    //     else if  (${"SES$fuel"}!=null && $efficiency == 0 && ($fuel=='Coal'|| $fuel=='Oil' || $fuel=='Gas' || $fuel=='Biofuels' || $fuel=='Peat' || $fuel=='Waste' || $fuel=='Oil_shale'))
    //         $tpes = ${"SES$fuel"};
            
    //     else if ($fuel=='ImportExport')
    //         $tpes = $Electricity*$elmix_fuel/100;
    //     else
    //         $tpes = 0;
    //         //echo "fuel " . $fuel . " fule var " . $tpes ."</br>";
    //         //print_r($$fuel);
    //     return  $tpes;
    // }   

        
    public function getTPES($fuel, $year) {
        if($fuel != 'ImportExport'){
            $tmp = $this->getPrimaryEnergyForElGeneration($fuel, $year);
            $ses = $this->getSES($fuel, $year);
            //echo "commodity " . $fuel . " year " . $year  . " primar energy  " . $tmp  . " ses " . $ses  .  "<br>";
            $tpes = $tmp + $ses;
        }
        else{
            //$Electricity = $this->getSES('Electricity', $year);
            $tpes = $this->getElDemand( 'ImportExport', $year);
        }
        return  $tpes;
    }  
    
    public function getTPESArray() {

        $Years = $this->getYearsArray();
        foreach($Years as $year){
            foreach($this->EnergyCommodities as $comm){
                if($comm != 'ImportExport'){
                    $tpes[$year][$comm] = $this->getTPES($comm, $year);
                   
                }else{
                    $tpes[$year][$comm] = $this->getElDemand( 'ImportExport', $year);
                }
            }
        }
        return  $tpes;
    }  
    
    //////////////////////////////////////////////////////////////////////////////////////////////////////////Phase 2 ESST
    public function getSESP2($fuel){
        foreach($this->getYrs() as $year){
            $tfc = 0;
            $sectors = $this->getSectors();
            foreach($sectors as $key2=>$value2){  
                $tfc = $tfc + $this->getTFC($fuel, $year, $key2);                   
            } 
            $losses = $this->getFedLosses($year,$fuel);
            $tmp =  $tfc/(1-$losses/100);
            if ($this->getUnit() == 'PJ'){
            	$tmp = $tmp*1000/3.6;
            }
            if ($this->getUnit() == 'ktoe'){
            	$tmp = $tmp*11.63;
            }
            if ($this->getUnit() == 'Mtoe'){
            	$tmp = $tmp*1000*11.63;
            }
            $this->SESP2[$year] = $tmp;  //da dobijemo GWh 
        }
        return $this->SESP2;
    }

    public function getElImport(){  
        $Import = array();
        foreach($this->getYears() as $key=>$val){
            $year = substr($key, 1);
            if($val==1){
                $Electricity = $this->getSESP2('Electricity');
                $ImportShare = $this->getSesElmix($year,'ImportExport'); 
                $tmp = $Electricity[$year] * $ImportShare/100;
                if ($this->getUnit() == 'PJ'){
                	//$tmp = $tmp*1000/3.6/8.76; //MW
                }
                $Import[$year] = $tmp/8.76;
            }
        }
        return $Import;
    }

    public function getCapacityForImportExport($year){
        //if(empty($this->capacityForImportExport)){
            $ImportExport = $this->getElDemand('ImportExport', $year);
            $unit = $this->getUnit();
            $CF = 100;
            if($unit == 'PJ'){
                $capacityForImportExport = $ImportExport/$CF/365/24/3.6*100000000;
            }
            if ($unit == "ktoe"){
                $capacityForImportExport = $ImportExport/$CF/365/24*11630*100;
            }
            if ($unit == "Mtoe"){
                $capacityForImportExport = $ImportExport/$CF/365/24*11630*100*1000;
            }
            if ($unit == "GWh"){
                $capacityForImportExport = $ImportExport/$CF*100/365/24*1000;
            }
        //}
        return $capacityForImportExport;
    }
    
    public function getExistingPower() {
		if (empty($this->existingPower)) {
    			$this->installedPower = array();
    			//$this->capacityFactor = array();
    			foreach($this->xml->Transformation->tra_technical as $fbs) {
        			// echo "tehnologija " . $fbs['technology']." instalirana " .  $fbs->Installed_power."<br";
                    $yr  = $fbs['year'];
       				$tec = $fbs['technology'];
                    if($fbs->Installed_power!=""){
                        $this->existingPower[(String)$tec][(String)$yr] = (String)$fbs->Installed_power;
        			}
                    else{
                        $this->existingPower[(String)$tec][(String)$yr] = 0;
                    }
                }
            
		}
		return $this->existingPower;
	}
    
    //izracunaj instaliranu energiju u sistemu iz CF i instalirane snage 
    public function getInstalledEnergy($pTech, $pYear, $pUnit){
         $result1 = $this->getInstalledPower($pYear, $pTech);
         $result2 = $this->getCapacityFactor($pYear, $pTech);

        //echo "Instaled Powewr " . " year " . $pYear . " tech " . $pTech . " value " .$result1."<br>";
        //echo "result 1" .$result1."<br>";
        //echo "result 1 sa [0]" .$result1[0]."<br>";
         
        //racuna  energiju iz instalirane snage  u tri jedinice
        if ($pUnit == 'PJ') 
            $this->InstalledEnergy = ((float)$result1*(float)$result2/100)/1000*365*24*3.6/1000;
        if ($pUnit == 'ktoe')
            $this->InstalledEnergy = ((float)$result1*(float)$result2/100)/1000*365*24*8.6/100;
        if ($pUnit == 'Mtoe')
            $this->InstalledEnergy = ((float)$result1*(float)$result2/100)/1000*365*24*8.6/100/1000;
        if ($pUnit == 'GWh')
            $this->InstalledEnergy = ((float)$result1*(float)$result2/100)/1000*365*24;
        
        return $this->InstalledEnergy;
    }    
    
    //Energija koja izlazi iz upotrebe po godinama
    public function getDecomEnergy($tech){
        $years = $this->getYears();
        $elmix_fuels = $this->getElMixFuels();
        $unit = $this->getUnit();
        foreach ($elmix_fuels as $key=>$value){
                $index = 1990;
                $temp = 0;
                if ($value =="1" && $key == $tech) 
                {
                     $out = array();
                     $lifetime = $this->getLifetime($key);
                     
                     foreach ($years as $key1=>$value1)
                     {
                        if ($value1=="1")
                        {    
                        $tmp = substr($key1, 1);
                        //echo "year ". $tmp." tech " . $key. " unit " .$unit."<br>";
                        
                        $InstalledEnergy = $this->getInstalledEnergy($key, $tmp, $unit);        
                        $CF = $this->getCapacityFactor($tmp, $key); 
                        $ElDemand = $this->getElDemand($key, $tmp);    
           
                        if ($CF!=0)
                           {
                                if ($unit == "PJ")
                                {
                                    if ((( $InstalledEnergy - $ElDemand)/$CF/365/24/3.6*100000000 + abs($temp))<0)
                                    { 
                                        $$key1 = abs(( $InstalledEnergy - $ElDemand)/$CF/365/24/3.6*100000000 + abs($temp));// + $out[$tmp];      
                                        //$temp instalirani kapacitet koji se prenosi u iducu godinu
                                        $temp = ($InstalledEnergy- $ElDemand)/$CF/365/24/3.6*100000000; 
                                        $index = (int)$tmp + (int)$lifetime;
        
                                        if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
                                            $out[$index] = $$key1;
                                            
                                        if (!array_key_exists("_".$index, $years))
                                        {
                                            $index = ceil($index/5)*5;
                                            if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
                                                $out[$index] = $$key1;  
                                            else 
                                            $index = $index+5;
                                            if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
                                            $out[$index] = $$key1;                                              
                                        }
                                        if (array_key_exists("_".$index, $years)&& 0 == $years["_".$index])
                                        {
                                            $index = $index+5;
                                            if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
                                            $out[$index] = $$key1;                                                       
                                        }                                             
                                     }
                                    else
                                        $$key1 = 0;  
                                }
                                
                                if ($unit == "ktoe")
                                {
                                    if ((( $InstalledEnergy - $ElDemand)/$CF/365/24*11630*100 + abs($temp))<0)
                                    {
                                        $$key1 = ceil(abs(( $InstalledEnergy - $ElDemand)/$CF/365/24*11630*100 + abs($temp)));
                                        //$temp instalirani kapacitet koji se prenosi u iducu godinu
                                        $temp = ($InstalledEnergy- $ElDemand)/$CF/365/24*11630*100;                                                
                                        $index = (int)$tmp + (int)$lifetime;
                                        if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
                                            $out[$index] = $$key1;
                                            
                                        else if (!array_key_exists("_".$index, $years))
                                        {
                                            $index = ceil($index/5)*5;
                                            if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
                                            $out[$index] = $$key1;                                                
                                        }
                                        else if (array_key_exists("_".$index, $years)&& 0 == $years["_".$index])
                                        {
                                            $index = $index+5;
                                            if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
                                            $out[$index] = $$key1; 
                                                                                           
                                        }
                                    }
                                    else
                                    $$key1 = 0;
                                }
                              
                                if ($unit == "GWh")
                                {
                                    if ((( $InstalledEnergy - $ElDemand)/$CF*100/365/24*1000 + abs($temp))<0)
                                    {
                                        $$key1 = ceil(abs(( $InstalledEnergy - $ElDemand)/$CF*100/365/24*1000 + abs($temp)));
                                        //$temp instalirani kapacitet koji se prenosi u iducu godinu
                                        $temp = ($InstalledEnergy- $ElDemand)/$CF*100/365/24*1000;                                              
                                        $index = (int)$tmp + (int)$lifetime;
                                        
                                        if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
                                            $out[$index] = $$key1;
                                            
                                        else if (!array_key_exists("_".$index, $years))
                                        {
                                            $index = ceil($index/5)*5;
                                            if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
                                            $out[$index] = $$key1;                                                
                                        }
                                        else if (array_key_exists("_".$index, $years)&& 0 == $years["_".$index])
                                        {
                                            $index = $index+5;
                                            if (array_key_exists("_".$index, $years) && 1 == $years["_".$index])
                                            $out[$index] = $$key1;                                                  
                                        }
                                    }
                                    else
                                    $$key1 = 0;
                                }
                           }
                            else
                            $$key1=0;  
                        }
                    }          
                    return  $out;
             }   
        }                
    }

    public function getAdditionalPower() {
        if (empty($this->calculatedPower)){
		    $unit = $this->getUnit();
 		    foreach ($this->getElMixFuels() as $key=>$value){
            $temp = 0;
            if ($value =="1" && $key!='ImportExport')          //if ($value =="1" && $key=='Coal')
            {
                 $out = $this->getDecomEnergy($key);

                 foreach ($this->getYears() as $key1=>$value1){
                    //echo "year ".$key1." value ".$value1."<br>";
                    $tmp = substr($key1, 1);
                    if ($value1=="1"){                    
                        if (!isset($out[$tmp])){ 
                            $out[$tmp] = 0;
                        }
                        // echo "out by year " . $out[$tmp] . " tech " . $key .  "<br>";
                        $InstalledEnergy = $this->getInstalledEnergy($key, $tmp, $unit);     
                        $ElDemand        = $this->getElDemand($key, $tmp); 
                        $CF              = $this->getCapacityFactor($tmp, $key);  
                        if ($CF!=0){
                            if ($unit == "PJ"){
                                //
                                //echo "year ". $tmp . " tech " . $key . " MC " . ( $InstalledEnergy - $ElDemand )/$CF/365/24/3.6*100000000 ."<br>";


                                if (((( $InstalledEnergy - $ElDemand )/$CF/365/24/3.6*100000000 + abs($temp))- $out[$tmp])<0){
                                    $this->calculatedPower[$key][$tmp] = abs(((( $InstalledEnergy - $ElDemand )/$CF/365/24/3.6*100000000 + abs($temp))- $out[$tmp]));
                                    //$temp instalirani kapacitet koji se prenosi u iducu godinu
                                    $temp = ($InstalledEnergy - $ElDemand)/$CF/365/24/3.6*100000000;// - $out[$tmp];                                                                        
                                 }
                                else{
                                    $this->calculatedPower[$key][$tmp] = 0; 
                                } 
                            }




                            if ($unit == "ktoe")
                            {
                                if ((( $InstalledEnergy - $ElDemand)/$CF/365/24*11630*100 + abs($temp) - $out[$tmp])<0)
                                {
                                    $this->calculatedPower[$key][$tmp] = abs(((( $InstalledEnergy - $ElDemand)/$CF/365/24*11630*100 + abs($temp)) - $out[$tmp]));
                                    //$temp instalirani kapacitet koji se prenosi u iducu godinu
                                    $temp = ($InstalledEnergy- $ElDemand)/$CF/365/24*11630*100;// - $out[$tmp];                                            
                                 }
                                else{
                                    $this->calculatedPower[$key][$tmp] = 0;
                                }
                            }
                            if ($unit == "Mtoe")
                            {
                                if ((( $InstalledEnergy - $ElDemand)/$CF/365/24*11630*100*1000 + abs($temp) - $out[$tmp])<0)
                                {
                                    $this->calculatedPower[$key][$tmp] = abs(((( $InstalledEnergy - $ElDemand)/$CF/365/24*11630*100*1000 + abs($temp)) - $out[$tmp]));
                                    //$temp instalirani kapacitet koji se prenosi u iducu godinu
                                    $temp = ($InstalledEnergy - $ElDemand)/$CF/365/24*11630*100*1000;    // - $out[$tmp];                                            
                                 }
                                else{
                                    $this->calculatedPower[$key][$tmp] = 0;
                                }
                            }
                            if ($unit == "GWh")
                            {
                                if ((( $InstalledEnergy - $ElDemand)/$CF*100/365/24*1000 + abs($temp) - $out[$tmp])<0)
                                {
                                    $this->calculatedPower[$key][$tmp] = abs(((( $InstalledEnergy - $ElDemand)/$CF*100/365/24*1000 + abs($temp)) - $out[$tmp]));
                                    //$temp instalirani kapacitet koji se prenosi u iducu godinu
                                    $temp = ($InstalledEnergy - $ElDemand)/$CF*100/365/24*1000;// - $out[$tmp];                                            
                                 }
                                else{
                                    $this->calculatedPower[$key][$tmp] = 0;
                                }
                            }
                        }
                        else
                        {
                            $this->calculatedPower[$key][$tmp] = 0;
                        }       
                    }
                }
             }
        }                
		}
		return $this->calculatedPower;
	} 
    
    public function getTotalNeededPower(){
        if (empty($this->totalNeededPower)){
            $additionalPower = $this->getAdditionalPower();
            $existingPower = $this->getExistingPower();
            $elMixFuels = $this->getElMixFuels();
            $years = $this->getYears();
            foreach ($elMixFuels as $tech=>$value){

                $SUM[$tech] = 0;
                $SumDecomPower[$tech] = 0;
                $temp = 0;

                if ($value =="1" && $tech!='ImportExport') {
                    $decomPower = $this->getDecomEnergy($tech);
                    foreach ($years as $key1=>$value1){

                        $year = substr($key1, 1);
                        if (!isset($decomPower[$year])){ 
                            $decomPower[$year] = 0;
                        }
                        if ($value1=="1"){
                            $SUM[$tech] = $SUM[$tech] + $additionalPower[$tech][$year];
                            $SumDecomPower[$tech] = $SumDecomPower[$tech] + $decomPower[$year];
                            $this->totalNeededPower[$tech][$year] = $SUM[$tech] + $existingPower[$tech][$year] - $SumDecomPower[$tech];
                        }
                    }
                }
            }
        }
        return $this->totalNeededPower;
    }  
    
    public function getTotalNeededPowerAllFuels(){
        if (empty($this->totalNeededPowerAllFuels)){
            
            $totalNeededPower = $this->getTotalNeededPower();
            
            $elMixFuels = $this->getElMixFuels();
            $years = $this->getYears();
            
            foreach ($years as $key1=>$value1){
                $tmp = substr($key1, 1);
                 $this->totalNeededPowerAllFuels[$tmp] = 0;
                if ($value1=="1"){
                    foreach ($elMixFuels as $key=>$value){
                        if ($value =="1" && $key!='ImportExport') {
                                $this->totalNeededPowerAllFuels[$tmp] =  $this->totalNeededPowerAllFuels[$tmp] + $totalNeededPower[$key][$tmp];
                        }
                    }
                }
            }
        }
        return $this->totalNeededPowerAllFuels;
    }
    
    public function getReserveCapacityPower(){
        if (empty($this->reserveCapacityPower)){
            
            $reserveCapacity = $this->getReserveCapacity();
            $totalNeededPower = $this->getTotalNeededPower();
            $totalNeedePowerAllFuels = $this->getTotalNeededPowerAllFuels();
            
            $elMixFuels = $this->getElMixFuels();
            $years = $this->getYears();
            
// echo "reserveCapacity";
// echo "<pre>";
// print_r($reserveCapacity);
// echo "</pre>";
            
            foreach ($years as $key1=>$value1){
                $tmp = substr($key1, 1);
                if ($value1=="1"){
                    $this->reserveCapacityPower['total'][$tmp] = $totalNeedePowerAllFuels[$tmp] * $reserveCapacity[$tmp]['total']/100;
                    foreach ($elMixFuels as $key=>$value){
                        if ($value =="1" &&  in_array($key, $this->ReserveCapacityTechs)) {
                            //echo 'year ' . $tmp .  ' el mix ' . $key . ' RC power  ' . $this->reserveCapacityPower['total'][$tmp] . ' Res cap  ' . $reserveCapacity[$tmp][$key] . "<br>";
                            $this->reserveCapacityPower[$key][$tmp] = $this->reserveCapacityPower['total'][$tmp] * $reserveCapacity[$tmp][$key]/100;
                        }
                    }
                }
            }
        }
        return $this->reserveCapacityPower;
    }
    
    public function getTIC(){
        if (empty($this->totalInstalledPower)){

            $ReserveCapacityPower = $this->getReserveCapacityPower();
            $totalNeededPower = $this->getTotalNeededPower();
           // $EnsCapacity = $this->getEnsCapacity();
            $elMixFuels = $this->getElMixFuels();
            $years = $this->getYears();
            
//            echo "EnsCapacity";
// echo "<pre>";
// print_r($EnsCapacity);
// echo "</pre>"; 
            foreach ($years as $key1=>$value1){
                $tmp = substr($key1, 1);
                if ($value1=="1"){
                    foreach ($elMixFuels as $key=>$value){
                        if ($value =="1" && $key!='ImportExport' && in_array($key, $this->ReserveCapacityTechs)){
                            $this->totalInstalledPower[$key][$tmp] = $totalNeededPower[$key][$tmp] + $ReserveCapacityPower[$key][$tmp];// + $EnsCapacity[$tmp][$key];
                        }else if ($value =="1" && $key!='ImportExport' && !in_array($key, $this->ReserveCapacityTechs)){
                              //echo 'year ' . $tmp .  ' el mix ' . $key . ' total needed power  ' .$totalNeededPower[$key][$tmp] . ' ENS ' . $EnsCapacity[$tmp][$key] . "<br>";
                            $this->totalInstalledPower[$key][$tmp] = $totalNeededPower[$key][$tmp];// + $EnsCapacity[$tmp][$key];
                        }
                     }
                    //dodano za capacity ImportCExport
                    $this->totalInstalledPower['ImportExport'][$tmp] = $this->getCapacityForImportExport($tmp);
                }
            }
            //capacitet za Import export
            //$this->totalInstalledPower['ImportExport'] = $this->getElImport();
        }
        return $this->totalInstalledPower;
    }   

    public function getTICDy(){
        if (empty($this->totalInstalledPowerDispatchable)){
            
            $TIC = $this->getTIC();
            $elMixFuels = $this->getElMixFuels();
            $years = $this->getYears();
            
            foreach ($years as $key1=>$value1){
                $tmp = substr($key1, 1);
                 $this->totalInstalledPowerDispatchable[$tmp] = 0;

                if ($value1=="1"){
                    foreach ($elMixFuels as $key=>$value){
                        if ($value =="1" && $key!='ImportExport' && !in_array($key, $this->HourlyAnalysisTech)) {
                                $this->totalInstalledPowerDispatchable[$tmp] =  $this->totalInstalledPowerDispatchable[$tmp] + $TIC[$key][$tmp];
                        }
                    }
                }
            }
        }
        return $this->totalInstalledPowerDispatchable;
    } 



















   //NEW ESST CASE CLASS////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
   
   public function getTechs() {
		if (!isset($techs)) {
			$techs = array();
			foreach($this->xml->ElMix_fuels[0] as $aFuel) {
                // if ($aFuel == 1 && $aFuel->getName() != 'ImportExport' ){
                if ($aFuel == 1){
				    $techs[] = $aFuel->getName();
                }             
			}
		}
		return $techs;
	}

    public function getCommodities() {
		if (!isset($com)) {
			$techs = array();
			foreach($this->xml->Fuels[0] as $aFuel) {
                if ($aFuel == 1){
				    $com[] = $aFuel->getName();
                }             
			}
		}
		return $com;
	}

    public function getYrs() {
		if (!isset($yrs)) {
			$yrs = array();
			foreach($this->xml->Years[0] as $aYr) {
                if ($aYr == 1){
				    $yrs[] = substr($aYr->getName(), 1);
                }
			}
		}
		return $yrs;
	}

    public function getSect() {
		if (!isset($sct)) {
			$sct = array();
			foreach($this->xml->Sectors[0] as $aSec) {
                if ($aSec == 1){
				    $sct[] = $aSec->getName();
                }
			}
		}
		return $sct;
	}

   	// public function getCF() {
	// 	if (!isset($CF)) {
	// 		//$this->installedPower = array();
	// 		//$this->capacityFactor = array();
	// 		foreach($this->xml->Transformation->tra_technical as $fbs) {
	// 			$yr  = $fbs['year'];
	// 			$tec = $fbs['technology'];
	// 			$CF[(String)$yr][(String)$tec] = (float)$fbs->Capacity_factor;
	// 		}
	// 	}
	// 	return $CF;
	// }

    //final energy demadnd
	public function getFED() {
		if (!isset($FED)) {
			$FED = array();
			foreach($this->xml->Final_energy_demand->fed_bysectors as $fbs) {
				$yr  = $fbs['year'];
                $FED[(String)$yr] = array();
				foreach($fbs as $sec => $val) {
					$FED[(String)$yr][(String)$sec] = (float)$val;
				}
			}
		}
		return $FED; 
	}

    //fuel shares
	public function getFS() {
		if (!isset($FS)) {
			$FS = array();
			foreach($this->xml->xpath('//fed_fuelshares') as $fbs) {
				$yr  = $fbs['year'];
				$sec = $fbs['sector'];
				foreach($fbs as $fuel => $val) {
					$FS[(String)$yr][(String)$sec][(String)$fuel] = (float)$val;
				}
			}
		}
		return $FS; 
	}

    public function getEMix() {
		if (!isset($EMix)) {
			$EMix = array();
			foreach($this->xml->xpath('//ses_elmix') as $fbs) { 
				$yr = $fbs['year'];
				$EMix[(String)$yr] = array();
				foreach($fbs as $fuel => $val) {
					$EMix[(String)$yr][(String)$fuel] = (float)$val;
				}
			}
		}
		return $EMix;
	}

    //losses
	public function getLs() {
		if (!isset($LS)) {
			$LS = array();
			foreach($this->xml->xpath('//fed_losses') as $fbs) {
				$yr = $fbs['year'];
				$LS[(String)$yr] = array();
				foreach($fbs as $fuel => $val) {
					$LS[(String)$yr][(String)$fuel] = (float)$val;
				}
		    }
        }
		return $LS; 
	}

    //Domestic Production
    public function getDP() {
		if (!isset($DP)) {
			$DP = array();
			foreach($this->xml->xpath('//pes_domestic_production') as $fbs) {
				$yr = $fbs['year'];
				$DP[(String)$yr] = array();
				foreach($fbs as $fuel => $val) {
					$DP[(String)$yr][(String)$fuel] = (float)$val;
				}
			}
		}
		return $DP; 
	}  

    //efficiency 
    public function getEff() {
		if (!isset($ETA)) {
			$ETA = array();
			foreach($this->xml->xpath('//tra_efficiency') as $fbs) {
				$yr = $fbs['year'];
				$ETA[(String)$yr] = array();
				foreach($fbs as $fuel => $val) {
					$ETA[(String)$yr][(String)$fuel] = (float)$val;
				}
			}
		}
		return $ETA; 
	}

    //lifetime
	public function getLT() {
		if (!isset($LT)) {
			$LT = array();
			 foreach($this->xml->Transformation->tra_lifetime as $fbs) {
				$tec = $fbs['technology'];
				$LT[(String)$tec] =(float)$fbs->Lifetime;
			}
		}
		return $LT; 
	}

    //reserve capacity
    public function getRC() {
		if (!isset($RC)) {
			$RC = array();
			foreach($this->xml->xpath('//tra_reserve_capacity') as $fbs) { 
				$yr = $fbs['year'];
                $total = $fbs['total'];
                $RC[(string)$yr]["total"] = (string)$total[0];
				foreach($fbs as $fuel => $val) {
					$RC[(string)$yr][(string)$fuel] = (float)$val[0];
				}
			}
		}
		return $RC;
	}

    public function calcTFC(){
		if (!isset($TFC)) {
            $TFC = array();
            $fs = $this->getFS();
            $fed = $this->getFED();
            foreach($this->getYrs() as $year){
                foreach($this->getSect() as $sct){
                    foreach($this->getCommodities() as $com){
                        $TFC[$year][$sct][$com] = $fed[$year][$sct] * $fs[$year][$sct][$com] / 100;
                    }
                }
            }
        }
        return  $TFC;
    }

    public function calcSES(){
        if (!isset($SES)) {
            $tfc = $this->calcTFC();
            $losses = $this->getLS();
            $sectors = $this->getSect();
            foreach($this->getYrs() as $year){
                foreach($this->getCommodities() as $com){
                    $tfc_tmp = 0;
                    foreach($sectors as $sct){  
                        $tfc_tmp = $tfc_tmp + $tfc[$year][$sct][$com];                   
                    } 
                    $SES[$year][$com] =  $tfc_tmp / (1 - $losses[$year][$com] / 100);
                }
            }
        }
        return $SES;
    }

    //vrijednosti izlaza iz transformacije za proizvodnju el energije po gorivima
    //na Secondary Energy Supply (El Energija)*SES_El_MIX/100
    public function calcED(){    
        if (!isset($ED)) {
            $ED = array();
            $techs = $this->getTechs();
            $years = $this->getYrs();
            $emix = $this->getEmix();
            $ses = $this->calcSES();
            $unit = $this->getUnit();
            foreach($years as $year){
                foreach($techs as $tech){
                    $ED[$year][$tech] = $ses[$year]['Electricity'] * $emix[$year][$tech] / 100;
                }
            }
        }
        return $ED;  
    }

    //Installed Capacity
	public function getIC() {
		if (!isset($IC)) {
			$IC = array();
			foreach($this->xml->xpath('//tra_technical') as $fbs) {
				$yr  = $fbs['year'];
				$tec = $fbs['technology'];
				$IC[(String)$yr][(String)$tec] = (float)$fbs->Installed_power;
			}
		}
		return $IC; 
	}
        
    //needed capacity for eletricity demand inthe sistem NC = ElDemand/CF*87.6
    //porebno da se pretvori u MW
    public function calcNC(){
        if(!isset($NC)){
            try{
                $ed = $this->calcED();
                $cf = $this->getCF();
                $techs = $this->getTechs();
                $years = $this->getYrs();
                $unit = $this->getUnit();
                foreach($years as $year){
                    foreach($techs as $tech){
                        if($cf[$year][$tech] > 0){
                            $NC[$year][$tech] = $ed[$year][$tech] / ($cf[$year][$tech] * 87.6);
                            switch($unit){
                                default:
                                break;	
                                case 'PJ':
                                    $NC[$year][$tech] = $NC[$year][$tech] / 3.6 * pow(10, 6);
                                break;
                                case 'ktoe':
                                    $NC[$year][$tech] = $NC[$year][$tech] * 11630;
                                break;
                                case 'Mtoe':
                                    $NC[$year][$tech] = $NC[$year][$tech] * 11.630;
                                break;
                                case 'GWh':
                                    $NC[$year][$tech] = $NC[$year][$tech] / 1000;
                                break;
                            }
                        }
                        else{
                            throw new Exception("Capacity Factor is 0!");
                        }
                    }
                }
            }
            catch(Exception $e){
                    echo json_encode(array('msg'=>$e->getMessage(), "type"=>"ERROR"));
                    die();
            }
        }
        return $NC;
    }

    //missing capacity Demand - Installed capacity (no decom or already installed carryover)
    //negative if capacity is missing
    public function calcMC(){
        if(!isset($MC)){
            $techs = $this->getTechs();
            $years = $this->getYrs();
            $NC = $this->calcNC();
            $IC = $this->getIC();
            foreach($years as $year){
                foreach($techs as $tech){
                    $MC[$year][$tech] = $IC[$year][$tech] - $NC[$year][$tech];
                }
            }
        }
        return $MC;
    }

    private function getDCindex($years, $index){
        for($i=1; $i<count($years); $i++){
            if($index < $years[$i] && $index > $years[$i-1]){
                $index = $years[$i];
                break;
            }
            else{
                $index = null;
            }
        }     
        return $index;
    }

    //capacitet koja izlazi iz upotrebe po godinama i tehnologijama Decommissioning capacity
    public function calcDC(){
        if(!isset($DC)){
            $years = $this->getYrs();
            $techs = $this->getTechs();
            $unit = $this->getUnit();
            $lifetime = $this->getLT();
            $mc = $this->calcMC();
            foreach ($techs as $tech){
                $carryover = 0; 
                foreach ($years as $year){  

                    if($mc[$year][$tech] + $carryover < 0){

                        $yearIndex = $year + $lifetime[$tech];
                        if (in_array($yearIndex, $years)) {
                             $DC[$yearIndex][$tech] = abs($mc[$year][$tech] + $carryover);
                         }
                         else{
                            $index = $this->getDCindex($years, $yearIndex);
                            if($index != null){
                                $DC[$index][$tech] = abs($mc[$year][$tech] + $carryover);
                            }
                         }
                        $carryover = abs($mc[$year][$tech]);
                    } 

                }   
            }       
            return  $DC;             
        }                
    }

    //calculate addittional capacity needs to be build by years and technology
    public function calcAC() {
        if(!isset($AC)){
            $years = $this->getYrs();
            $techs = $this->getTechs();
            $lifetime = $this->getLT();
            $mc = $this->calcMC();
           // $dc = $this->calcDC();
            foreach ($techs as $tech){
                $carryover = 0; 
                 foreach ($years as $year){
                    if (!isset($dc[$year][$tech])){ 
                        $dc[$year][$tech] = 0;
                    }
                    if($mc[$year][$tech] + $carryover - $dc[$year][$tech] < 0){
                        $AC[$year][$tech] = abs($mc[$year][$tech] + $carryover - $dc[$year][$tech]);
                        //napraviti niz za decomisioning
                        $yearIndex = $year + $lifetime[$tech];
                        if (in_array($yearIndex, $years)) {
                             $dc[$yearIndex][$tech] = abs($mc[$year][$tech] + $carryover);
                        }
                        else{
                            $index = $this->getDCindex($years, $yearIndex);
                            if($index != null){
                                $dc[$index][$tech] = abs($mc[$year][$tech] + $carryover);
                            }
                        }
                        $carryover = abs($mc[$year][$tech]);
                    } 
                    else{
                        $AC[$year][$tech] = 0;
                    }                     
                }  
            }                
		}
		return $AC;
	} 

    //ENS capacity
    public function getENS() {
        if(!isset($ENS)){
			$ENS = array();
			foreach($this->xml->xpath('//ens_capacity') as $fbs) { 
				$yr = $fbs['year'];
				foreach($fbs as $fuel => $val) {
					$ENS[(string)$yr][(string)$fuel] = (float)$val[0];
				}
			}
		}
		return $ENS;
	}

    //Existing Reserve Capacity
    public function calcRCe() {
        if(!isset($RCe)){
            $years = $this->getYrs();
            $techs = $this->getTechs();
            $lifetime = $this->getLT();
            $mc = $this->calcMC();
            $dc = $this->calcDC();
            foreach ($techs as $tech){
                $carryover = 0; 
                 foreach ($years as $year){
                    if (!isset($dc[$year][$tech])){ 
                        $dc[$year][$tech] = 0;
                    }
                    if($mc[$year][$tech] + $carryover - $dc[$year][$tech] < 0){
                        //$AC[$year][$tech] = abs($mc[$year][$tech] + $carryover - $dc[$year][$tech]);
                        $carryover = abs($mc[$year][$tech]);
                        $RCe[$year][$tech] = 0;
                    } 
                    else{
                        $RCe[$year][$tech] = abs($mc[$year][$tech] + $carryover - $dc[$year][$tech]);
                    }                     
                }  
            }                
		}
		return $RCe;
	} 

    // Reserve Capacity
    public function calcRC() {
        if(!isset($RC)){
            $years = $this->getYrs();
            $techs = $this->getTechs();
            $lifetime = $this->getLT();
            $ac = $this->calcAC();
            $ic = $this->getIC();
            $rc = $this->getRC();
            $rce = $this->calcRCe();

            foreach ($years as $year){  
                $tc[$year] = array_sum($ic[$year]) + array_sum($ac[$year]); 
                $RC[$year]['Total'] = $tc[$year]  * $rc[$year]['total'] / 100 - array_sum($rce[$year]);
                $carryover = 0;  
                if(!isset($rcd[$year])) $rcd[$year][] = 0;

                if(($RC[$year]['Total']  - $carryover + array_sum($rcd[$year])) > 0){

                    foreach ($techs as $tech){
                        if(!isset($rcd[$year][$tech])) $rcd[$year][$tech] = 0;

                        $RC[$year][$tech] = $RC[$year]['Total']  * $rc[$year][$tech] / 100  - $carryover + $rcd[$year][$tech];
                        
                        $yearIndex = $year + $lifetime[$tech];
                        if (in_array($yearIndex, $years)) {
                            $rcd[$yearIndex][$tech] = abs($RC[$year]['Total']  * $rc[$year][$tech] / 100 - $rce[$year][$tech] - $carryover);
                        }
                        else{
                            $index = $this->getDCindex($years, $yearIndex);
                            if($index != null){
                                $rcd[$index][$tech] = abs($RC[$year]['Total']  * $rc[$year][$tech] / 100 - $rce[$year][$tech] - $carryover);
                            }
                        }
                       // echo "year " . $year . " tech " . $tech  . " rc decom " .  $rcd[$year][$tech] . "<br>";
                    }
                    $carryover = $RC[$year]['Total'];

                }   
                else{
                     $RC[$year]['Total'] = abs(array_sum($rce[$year])  + $carryover - array_sum($rcd[$year]));
                    foreach ($techs as $tech){
                        $RC[$year][$tech] = 0;
                    }
                }
            }    
		}
		return $RC;
	}

    // Total installed  Capacity = IC + AC + RC + ENS
    public function calcTIC() {
        if(!isset($TIC)){
            $years = $this->getYrs();
            $techs = $this->getTechs();
            $ac = $this->calcAC();
            $ic = $this->getIC();
            $rc = $this->calcRC();
            $ens = $this->getENS();
            foreach ($years as $year){  
                foreach ($techs as $tech){
                    $TIC[$year][$tech] = $ic[$year][$tech] + $ac[$year][$tech] + $rc[$year][$tech] + $ens[$year][$tech];
                }
            }    
		}
		return $TIC;
    }
    
}


// $emission = new EsstCase('esst ver 2-6-5 v3 small');
// //getInstalledEnergy

// echo "getSESP2(";
// echo "<pre>";
// print_r($emission->getSESP2('Electricity'));
// echo "</pre>";


// echo "getTPES";
// echo "<pre>";
// print_r($emission->getTPES('Coal', '2050'));
// echo "</pre>";


// echo "getReserveCapacityPower";
// echo "<pre>";
// print_r($emission->getSES('ImportExport', '2050'));
// echo "</pre>";

// echo "getAdditionalPower";
// echo "<pre>";
// print_r($emission->getAdditionalPower()['Hydro']['1990']);
// echo "</pre>";

// echo "getAdditionalPower";
// echo "<pre>";
// print_r($emission->getAdditionalPower()['Hydro']['1990']);
// echo "</pre>";

// echo " getCF";
// echo "<pre>";
// print_r($emission->getCF());
// echo "</pre>";

// echo " getElMixFuels()()";
// echo "<pre>";
// print_r($emission->getElMixFuels());
// echo "</pre>";



// echo " CF()";
// echo "<pre>";
// print_r($emission->getCF());
// echo "</pre>";
// echo "tpes 2";
// echo "<pre>";
// print_r($emission->getTPES('Oil', '2040'));
// echo "</pre>";

 //define('ROOT_DIR','C:\wamp64\www\ESST\esst.ver.2.6.0');
//  require_once '../config.php';
// require_once ROOT_DIR.'/classes/paths.php';
//  $esstCase = new EsstCase("test bez polja");
//         echo "esst case xml";
//  print_r($esstCase->xml);
//echo "UNIT " . $esstCase->getUnit()."<br>";




// echo 'RC';
// echo "<pre>";
// print_r($esstCase->getReserveCapacitypower()["Oil"]["2010"]);
// echo "</pre>";

// echo 'AP';
// echo "<pre>";
// print_r($esstCase->getAdditionalPower()["Oil"]["2010"]);
// echo "</pre>";

// echo 'EP';
// echo "<pre>";
// print_r($esstCase->getExistingPower()["Oil"]["2010"]);
// echo "</pre>";


// echo 'ENS';
// echo "<pre>";
// print_r($esstCase->getEnsCapacity()["2010"]["Oil"]);
// echo "</pre>";

// echo 'TIC';
// echo "<pre>";
// print_r($esstCase->getTIC()["Oil"]["2010"]);
// echo "</pre>";


// echo 'eff';
// echo "<pre>";
// print_r($esstCase->getEff());
// echo "</pre>";

// echo 'DP';
// echo "<pre>";
// print_r($esstCase->getDP());
// echo "</pre>";

// echo 'ENS';
// echo "<pre>";
// print_r($esstCase->getENS());
// echo "</pre>";

// echo 'RC';
// echo "<pre>";
// print_r($esstCase->calcRC());
// echo "</pre>";

// echo 'TIC';
// echo "<pre>";
// print_r($esstCase->calcTIC());
// echo "</pre>";

// echo 'LT';
// echo "<pre>";
// print_r($esstCase->getLT());
// echo "</pre>";

// echo 'additional cap';
// echo "<pre>";
// print_r($esstCase->getAdditionalPower());
// echo "</pre>";

// echo 'MC';
// echo "<pre>";
// print_r($esstCase->calcMC());
// echo "</pre>";

 //echo "CF Hydro 1990" . $esstCase->getCapacityFactor ('1990', 'Hydro')."<br>";

// echo 'AC';
// echo "<pre>";
// print_r($esstCase->calcAC());
// echo "</pre>";

// echo 'FED';
// echo "<pre>";
// print_r($esstCase->getFED());
// echo "</pre>";

// echo 'el demand old';
// echo "<pre>";
// print_r($esstCase->getElDemand('Coal', '1990'));
// echo "</pre>";

// echo 'ses';
// echo "<pre>";
// print_r($esstCase->calcED());
// echo "</pre>";

// echo 'NC';
// echo "<pre>";
// print_r($esstCase->calcNC());
// echo "</pre>";

// echo 'getTFCs old';
// echo "<pre>";
// print_r($esstCase->getTFC('Coal', '1990', 'Industry') );
// echo "</pre>";

// echo 'getTFCs';
// echo "<pre>";
// print_r($esstCase->getTFCs() );
// echo "</pre>";

// echo 'Losses';
// echo "<pre>";
// print_r($esstCase->getLS() );
// echo "</pre>";

// echo 'getTIC';
// echo "<pre>";
// print_r($esstCase->getTIC() );
// echo "</pre>";

// echo 'getTotalNeededPower';
// echo "<pre>";
// print_r($esstCase->getTotalNeededPower() );
// echo "</pre>";

// echo 'getReserveCapacityPower';
// echo "<pre>";
// print_r($esstCase->getReserveCapacityPower() );
// echo "</pre>";

// echo 'getTotalInstalledPower';
// echo "<pre>";
// print_r($esstCase->getTotalInstalledPower() );
// echo "</pre>";

// echo 'getTotalPower';
// echo "<pre>";
// print_r($esstCase->getTotalNeededPower() );
// echo "</pre>";

// echo 'getAdditionalPower';
// echo "<pre>";
// print_r($esstCase->getAdditionalPower());
// echo "</pre>";

// echo 'getExistingPower';
// echo "<pre>";
// print_r($esstCase->getExistingPower());
// echo "</pre>";
//
//
//
//
//
//
//foreach ($esstCase->getElMixFuels() as $key=>$value)
//{
//    if($key!="ImportExport" && $value==1)
//    {
//   // echo "tehnologija ".$key."<br>";
//   // echo "Decomm " . $esstCase->getDecomEnergy($key)."jaaa2<br>";
//echo "tehnologija ".$key."<br>";
//    echo "<pre>";
//    print_r($esstCase->getDecomEnergy($key));
//    echo "</pre>";
//    }
//}



////  foreach ($esstCase->getElMixFuels() as $key=>$value)
////            {
////                if($key!="ImportExport" && $value==1)
////                
////                echo "tehnologija ".$key."<br>";
////              //  echo "lifetime2 " . $esstCase->getLifetime($key)."jaaa2<br>";
//            echo "<pre>";
//            print_r($esstCase->getEnvironment());
//           echo "</pre>";
////           }
?>