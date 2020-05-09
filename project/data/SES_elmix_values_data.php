<?php
require_once '../config.php';
require_once '../functions/xml_functions.php';
//require_once '../functions/calc_functions.php';

require_once '../classes/EsstCase.php';
require_once '../includes/session.php';



	$filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";
    $orders = array();
    if (file_exists($filepath)){

        // $esstCase = new EsstCaseClass($_SESSION['case']);
       
        // $years = $esstCase->getYears();
        // $techs = $esstCase->getTechs();
        // $ses = $esstCase-> getSES_yc();

        // $SES = [];
        // $SES_t = [];

        // $SEStmp = array();
        // $SEStmp_t = array();

        // foreach($years as $yr){
        //     foreach($techs as $tech){
        //         $SEStmp[$tech] = $ses[$yr][$tech];
        //     }
        //     $SES[] = $SEStmp;
        // }
    
        // foreach($techs as $tech){
        //     $TPEStmp_t['name'] = $tech;
        //     foreach($years as $yr){
        //         $TPEStmp_t[$yr] = $ses[$yr][$tech];
        //     }
        //     $TPES_t[] = $TPEStmp_t;
        // }
    
    
        // //if (isset($_GET['action']) && $_GET['action']=="tpes" && isset($_GET['trans'])&& $_GET['trans']==0 ) { 
        // if (!isset($_GET['trans']))
        //     echo '{"data":'.json_encode(array_values($TPES)).'}';  
        //     die();
        // } 
    
        // //if (isset($_GET['action']) && $_GET['action']=="tpes" && isset($_GET['trans'])&& $_GET['trans']==1 ) { 
        // if (isset($_GET['trans'])&& $_GET['trans']==1)
        //     echo '{"data":'.json_encode(array_values($TPES_t)).'}';  
        //     die();
        // } 





        
        $xml = simplexml_load_file($filepath)
          or die("Error: Cannot create object");
        $esstCase = new EsstCase($_SESSION['case'], $xml);
  
        $years = $esstCase->getYears(); 
        $fuels = $esstCase->getFuels();
        $elmix_fuels = $esstCase->getElMixFuels();

          //$years = $GLOBALS['esstCase']->getYears(); 
          //$fuels = $GLOBALS['esstCase']->getFuels();
          //$elmix_fuels = $GLOBALS['esstCase']->getElMixFuels();
         
          if (!isset($_GET['trans']))
          {
            foreach ($years as $key1=>$value1)
            {
                if ($value1=="1")
                {
                $tmp = substr($key1, 1);
                foreach ($elmix_fuels as $key=>$value)
                {
                    if($value=="1")
                    {
                        //$$key = get_el_output($filepath, $key, $tmp);
                        //$$key = get_el_demand($key, $tmp);
                        $$key = $esstCase->getElDemand($key, $tmp);

                    }
                    else 
                    $$key = 0;
                }
                $SES[] = array(
        			'year' => $tmp,
        			'Hydro' => $Hydro,
        			'Coal' => $Coal,
        			'Oil' => $Oil,
        			'Gas' => $Gas,
                    'Biofuels' => $Biofuels,
                    'Peat' => $Peat,
                    'Waste' => $Waste,
                    'OilShale' => $OilShale,
                    'Solar' => $Solar,
                    'Wind' => $Wind,
                    'Geothermal' => $Geothermal,
                    'Nuclear' => $Nuclear,
                    'ImportExport' => $ImportExport
                    
        			);
                }
            }
            
            $array1=array_filter($SES);
        //    $array1 = array_filter(array_map('array_filter', $array1));
	    	echo '{"data":'.json_encode($array1).'}';
            
        } 

        if (isset($_GET['trans'])&& $_GET['trans']==1)
        {
        foreach ($elmix_fuels as $key=>$value)
        {        
            global $$key;
            if ($value=="1")
            {
                foreach ($years as $key3=>$value3)
                    {
                        if ($value3=="1")
                        {
                            $tmp = substr($key3, 1);
                            //$$key3 = get_el_output($filepath, $key, $tmp);
                            //$$key3 = get_el_demand($key, $tmp);
                            $$key3 = $esstCase->getElDemand($key, $tmp);

                            
                        }
                        else
                        $$key3 = '';
                    }
        
                $SES1[] = array(
        			'name' => $$key,
        			'1990' => $_1990,
        			'2000' => $_2000,
                    '2005' => $_2005,
        			'2010' => $_2010,
                    '2015' => $_2015,
        			'2020' => $_2020,
                    '2025' => $_2025,
                    '2030' => $_2030,
                    '2035' => $_2035,
                    '2040' => $_2040,
                    '2045' => $_2045,
                    '2050' => $_2050,                    
        			);
             }     
           }
            
            //print_r($SES1);
            $array2=array_filter($SES1);
            // $array2 = array_filter(array_map('array_filter', $array2));
	    	echo '{"data":'.json_encode($array2).'}';
      }
}
?>
