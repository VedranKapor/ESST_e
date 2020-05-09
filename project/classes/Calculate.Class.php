<?php
/////////////// Calculate CLASS ///////////////////
//************************************************
//************************************************

//************** CREATED BY VK *******************
//************ esst ver 2.6.5 2019****************
///////////////////////////////////////////////////

//define('ROOT_DIR','C:\wamp64\www\ESST\esst.ver.2.7.0.);
//require_once ROOT_DIR.'/classes/HourlyAnalysis.Class.php';
require_once ROOT_DIR.'/classes/hLCOE.Class.php';
//require_once ROOT_DIR.'/classes/Maintenance.Class.php';
require_once ROOT_DIR.'/classes/paths.php';


class Calculate extends hLCOE {

    protected $OutputDetails = array(); 
 
    public function __construct($pCase){                
        parent::__construct($pCase);

    }

    public function getOutputDetails($pMaintenance, $pStorage){
        if (isset($this->OutputDetails) && empty($this->OutputDetails)) {
             
            //$LF = $this->getLF();
            $UD = $this->getUD($pStorage);

            $CHG = $this->getCHGStat($pStorage);
            //$PMAX = $this->getPMAX();
            //$CF2 = $this->getCF();
            $RCF = $this->getRCF($pStorage);
            
            $RHG = $this->getSUM_RHG($pStorage);

			if($pMaintenance){
				// $MCLASS = $this->getMCLASS();
				// $MSPACE_long = $this->getMSPACE_long();
                // $MSPACE_short = $this->getMSPACE_short();

                $MCLASS = $this->MCLASS;
				$MSPACE_long = $this->MSPACE_long;
                $MSPACE_short = $this->MSPACE_short;
            }
                                
            foreach($this->yrs as $year){   
				if($pMaintenance){			
                    //$MCLASS_order = $this->getMCLASS_order($MCLASS, $MSPACE_long, $MSPACE_short, $year);   
                    $MCLASS_order = $this->MCLASS_order[$year];
				}					
                foreach($this->tech[$year] as $fuel){

                    if($fuel != 'ImportExport' && $fuel != 'Storage'){
						if($pMaintenance){
							$technology[$year][] = array(
										'tech' => $fuel,
										'InstalledPower' => $this->TIC[$year][$fuel],
										'TotalGeneration' => $RHG[$year][$fuel]/1000,
										'CG' => $CHG[$year]['SumCHG'][$fuel]/1000,              //da dobijemo GWh
										'maxCHG' => $CHG[$year]['MaxCHG'][$fuel],
										'countCHG' => $CHG[$year]['CountCHG'][$fuel],
                                        //'CF' => $this->getCapacityFactor($year, $fuel),
                                        'CF' => $this->CF[$year][$fuel],
										'CF2' => $this->CF1[$year][$fuel],
										'RCF' => $RCF[$year][$fuel],
										'UnitSize' =>$MCLASS['UnitSize'][$year][$fuel],
										'UnitNumber' =>$MCLASS['UnitNumber'][$year][$fuel],
										'NotMaintenaned' => $MCLASS_order[$fuel][0],
										'PowerNotMaintenaned' =>  $MCLASS_order[$fuel][0] * $MCLASS['UnitSize'][$year][$fuel]
							);
						}
						else{
							$technology[$year][] = array(
										'tech' => $fuel,
										'InstalledPower' => $this->TIC[$year][$fuel],
										'TotalGeneration' => $RHG[$year][$fuel]/1000,
										'CG' => $CHG[$year]['SumCHG'][$fuel]/1000,              //da dobijemo GWh
										'maxCHG' => $CHG[$year]['MaxCHG'][$fuel],
										'countCHG' => $CHG[$year]['CountCHG'][$fuel],
                                        //'CF' => $this->getCapacityFactor($year, $fuel),
                                        'CF' => $this->CF[$year][$fuel],
										'CF2' => $this->CF1[$year][$fuel],
										'RCF' => $RCF[$year][$fuel],
										'UnitSize' =>0,
										'UnitNumber' =>0,
										'NotMaintenaned' => 0,
										'PowerNotMaintenaned' =>  0
							);

						}
                    }
                    //dio za ImportExport
                    else if($fuel == 'ImportExport'){
                        $technology[$year][] = array(
                            'tech' => $fuel,
                            'InstalledPower' => $this->TIC[$year][$fuel],
                            'TotalGeneration' => $RHG[$year][$fuel]/1000,
                            'CG' => $CHG[$year]['SumCHG'][$fuel]/1000,              //da dobijemo GWh
                            'maxCHG' => $CHG[$year]['MaxCHG'][$fuel],
                            'countCHG' => $CHG[$year]['CountCHG'][$fuel],
                            'CF' => 0,
                            'CF2' => 0,
                            'RCF' => $RCF[$year][$fuel],
                            'UnitSize' =>0,
                            'UnitNumber' =>0,
                            'NotMaintenaned' => 0,
                            'PowerNotMaintenaned' => 0
                        );
                    }
                    else if($fuel == 'Storage'){
                        $technology[$year][] = array(
                            'tech' => $fuel,
                            'InstalledPower' => $this->STGparam[$year]['VOL'] * 1000,
                            'TotalGeneration' => $RHG[$year][$fuel]/1000,
                            'CG' => $CHG[$year]['SumCHG'][$fuel]/1000,              //da dobijemo GWh
                            'maxCHG' => $CHG[$year]['MaxCHG'][$fuel],
                            'countCHG' => $CHG[$year]['CountCHG'][$fuel],
                            'CF' => 0,
                            'CF2' => 0,
                            'RCF' => $RCF[$year][$fuel],
                            'UnitSize' =>0,
                            'UnitNumber' =>0,
                            'NotMaintenaned' => 0,
                            'PowerNotMaintenaned' =>  0
                        );
                    }
                }
            }
                                
              //  print_r($this->technology[$year]);                       
            foreach($this->yrs as $year){    
                $this->OutputDetails[] = array(
                        'year' => $year,
                        'ED'   => $this->EDgwh[$year],
                        'LF'   => $this->LF[$year],
                        'UD'   => $UD[$year]['SumUD']/1000,     //da dobijemo GWH
                        'maxUD'   => $UD[$year]['MaxUD'],      
                        'countUD'   => $UD[$year]['CountUD'],   
                        'PMAX' => $this->PMAX[$year],
                        'technologies' => array(
                            'technology' => $technology[$year],
                        ),
               	);  
            }
        }
        return $this->OutputDetails;
    } 

    //run calculation with mainztnence save to files
    public function Calculate($pMaintenance, $pStorage){

        //$this->setMaintenance($pMaintenance);
       // echo "pMaintenance Calculation " . $this->hMaintenance . " pMaintenace flaf " . $pMaintenence . "<br>";

        $fp = fopen($this->hSimulation.'/HMcp.json', 'w');
        fwrite($fp, json_encode($this->getMCLASS_order_h_cp($pMaintenance)));
        fclose($fp);
        
        $fp = fopen($this->hSimulation.'/TAICcp.json', 'w');
        fwrite($fp, json_encode($this->getTAIC_cp($pMaintenance)));
        fclose($fp);
    
        //ovo se prvo racuna i proziva se getRHD())
        $fp = fopen($this->hSimulation.'/RHDcp.json', 'w');
        fwrite($fp, json_encode($this->getRHDcp()));
        fclose($fp);
        

        // if($pStorage){
        //     $this->storageFlag = true;
        // }

        //$RHGcp = $this->getRHGcp($pStorage);
        $CHGStat = $this->getCHGStat($pStorage);
        
        //ovo se prvo racuna i proziva se getRHG()
        //nakon ova dva poziva napunjene seu class variable $RHD, $RHG, $HG, $NHP,$NHDP, $MaxHP,MaxHDP, $CHG, TCHG_tl, RHD_tl 
        //RHG po tehnologijama predstavlja resulting H generation, HG -CHG
        // $fp = fopen($this->hSimulation.'/RHGcp.json', 'w');
        // fwrite($fp, json_encode($RHGcp));
        // fclose($fp);
        
        $fp = fopen($this->hSimulation.'/CHGcp.json', 'w');
        fwrite($fp, json_encode($CHGStat));
        fclose($fp);

        //year data
        $yearStat['HG'] = $this->getSUM_RHG($pStorage);
        $yearStat['CG'] = $this->getSUM_CHG_yt($pStorage);
        $yearStat['UD'] = $this->getSUM_RHD_yt($pStorage);

        $fp = fopen($this->hSimulation.'/RHG_yt.json', 'w');
        fwrite($fp, json_encode($yearStat, JSON_PRETTY_PRINT));
        fclose($fp);

        //month week day data
        $statistic['Y']['HG'] = $this->getSUM_RHG($pStorage);
        $statistic['Y']['CG'] = $this->getSUM_CHG_yt($pStorage);
        $statistic['Y']['UD'] = $this->getSUM_RHD_yt($pStorage);
        $statistic['M']['HG'] = $this->getSUM_RHG_c(true, 730, 12);
        $statistic['W']['HG'] = $this->getSUM_RHG_c(true, 168, 52);
        $statistic['D']['HG'] = $this->getSUM_RHG_c(true, 24, 365);
        $statistic['M']['CG'] = $this->getSUM_CHG_c(true, 730, 12);
        $statistic['W']['CG'] = $this->getSUM_CHG_c(true, 168, 52);
        $statistic['D']['CG'] = $this->getSUM_CHG_c(true, 24, 365);
        $statistic['M']['UD'] = $this->getSUM_RHD_c(true, 730, 12);
        $statistic['W']['UD'] = $this->getSUM_RHD_c(true, 168, 52);
        $statistic['D']['UD'] = $this->getSUM_RHD_c(true, 24, 365);

        $fp = fopen($this->hSimulation.'/STATS.json', 'w');
        fwrite($fp, json_encode($statistic));
        fclose($fp);
        

        // $fp = fopen($this->hSimulation.'/RHSGcp.json', 'w');
        // fwrite($fp, json_encode($RHGcp['Solar']));
        // fclose($fp);
        
        // $fp = fopen($this->hSimulation.'/RHWGcp.json', 'w');
        // fwrite($fp, json_encode($RHGcp['Wind']));
        // fclose($fp);
        
        // $fp = fopen($this->hSimulation.'/RHHGcp.json', 'w');
        // fwrite($fp, json_encode($RHGcp['Hydro']));
        // fclose($fp);
        
        // $fp = fopen($this->hSimulation.'/CHWGcp.json', 'w');
        // fwrite($fp, json_encode($CHGStat['Wind']));
        // fclose($fp);
        
        // $fp = fopen($this->hSimulation.'/CHSGcp.json', 'w');
        // fwrite($fp, json_encode($CHGStat['Solar']));
        // fclose($fp);
        
        // $fp = fopen($this->hSimulation.'/CHHGcp.json', 'w');
        // fwrite($fp, json_encode($CHGStat['Hydro']));
        // fclose($fp);
        
        //////////////GRID CHART////////////////////////////////////////////////
            
        $fp = fopen($this->hSimulation.'/MHGcp.json', 'w');
        fwrite($fp, json_encode($this->getMHG($pStorage)));
        fclose($fp);
        
        $fp = fopen($this->hSimulation.'/DHGcp.json', 'w');
        fwrite($fp, json_encode($this->getDHG($pStorage)));
        fclose($fp);
        $fp = fopen($this->hSimulation.'/UDcp.json', 'w');
        fwrite($fp, json_encode($this->getUD($pStorage)));
        fclose($fp);
        
        $fp = fopen($this->hSimulation.'/OutputDetails.json', 'w');
        fwrite($fp, json_encode($this->getOutputDetails($pMaintenance, $pStorage)));
        fclose($fp);
        
        $fp = fopen($this->hSimulation.'/HDValues.json', 'w');
        fwrite($fp, json_encode($this->getHourlyDataValues($pStorage)));
        fclose($fp);
        
        $fp = fopen($this->hSimulation.'/ElMixP2.json', 'w');
        fwrite($fp, json_encode($this->getElMixSharesP2($pStorage)));
        fclose($fp);

        $fp = fopen($this->hSimulation.'/LCOE.json', 'w');
        fwrite($fp, json_encode($this->getLCOE_yt_p2($pStorage)));
        fclose($fp);

        $fp = fopen($this->hSimulation.'/STGcp.json', 'w');
        fwrite($fp, json_encode($this->getSTGcp($pStorage)));
        fclose($fp);

        $fp = fopen($this->hSimulation.'/CO2.json', 'w');
        // fwrite($fp, json_encode($this->getElGenEmissions_p2( $pStorage)));
        fwrite($fp, json_encode($this->getEmissions_p2( $pStorage)));
        fclose($fp);

        $fp = fopen($this->hSimulation.'/AUC.json', 'w');
        fwrite($fp, json_encode($this->getAUC_y_p2( $pStorage)));
        fclose($fp);

        $fp = fopen($this->hSimulation.'/MCoScp.json', 'w');
        fwrite($fp, json_encode($this->getMCoScp( $pStorage)));
        fclose($fp);

        // $fp = fopen($this->hSimulation.'/MCoS.json', 'w');
        // fwrite($fp, json_encode($this->getMCoT_yt( $pStorage)));
        // fclose($fp);
        
        $fp = fopen($this->hSimulation.'/MCoSdcp.json', 'w');
        fwrite($fp, json_encode($this->getMCoSdcp( $pStorage)));
        fclose($fp);
    }
}

?>