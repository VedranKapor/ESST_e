<?php
require_once '../functions/xml_functions.php';
require_once '../config.php';
require_once '../classes/EsstCase.php';
require_once '../includes/session.php';

$filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";
if (file_exists($filepath)){
    
    $xml = simplexml_load_file($filepath)
        or die("Error: Cannot create object");
    $esstCase = new EsstCase($_SESSION['case'], $xml);

    //function display()
    //{
    $orders = array();

     if (isset($_GET['trans'])&& $_GET['trans']==0)
          {
              //$years = get_years($filepath);
              $years = $GLOBALS['esstCase']->getYears();
              foreach ($years as $key=>$value)
              {
                $tmp = substr($key, 1);
                if ($value=="1")
                {
                    $result = $GLOBALS['xml']->xpath(sprintf("//ses_elmix[@year='%s']", $tmp));
                	foreach($result as $data)
                    
                    {
            			$orders[] = array(                        //   u ovom nizu ce biti ""prazna vrijednost za onaj sektor koji nije izabran, kasnije ce se direktno u jqx dataadapteru sakriti ta kolona
            			'year' => $tmp,
            			'Hydro' => (float)$data->Hydro,
            			'Coal' => (float)$data->Coal,
            			'Oil' => (float)$data->Oil,
            			'Gas' => (float)$data->Gas,
                        'Biofuels' => (float)$data->Biofuels,
                        'Peat' => (float)$data->Peat,
                        'Waste' => (float)$data->Waste,
                        'OilShale' => (float)$data->OilShale,
                        'Solar' => (float)$data->Solar,
                        'Wind' => (float)$data->Wind,
                        'Geothermal' => (float)$data->Geothermal,
                        'Nuclear' => (float)$data->Nuclear,
                        'ImportExport' => (float)$data->ImportExport
            			);
                       
                     }
                 }
               }
                //print_r($orders);
            $array1=array_filter($orders);
            //$array1 = array_filter(array_map('array_filter', $array1));
	    	echo '{"data":'.json_encode($array1).'}';
            die();
        }


        if (isset($_GET['trans'])&& $_GET['trans']==1){
            $years = $GLOBALS['esstCase']->getYears(); 
            $elmix_fuels = $GLOBALS['esstCase']->getElMixFuels();
            foreach ($elmix_fuels as $key=>$value) {
                //moramo definisati varijable goriva (iz fileove lang ukljucenih iznad) )kao globalne da bi ih mogli koristit u ovoj funkciji
                global $$key;
                if ($value=="1") {                 
                    foreach($years as $key1=>$value1){
                        if ($value1=="1"){   
                        $tmp = substr($key1, 1);
                        $$key1 = $GLOBALS['esstCase']->getSesElmix($tmp, $key); 
                        }
                        else{
                            $$key1 = '';
                        }
                    }  
                	$trans[] = array(
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
            $array = array_filter($trans);
       	    echo '{"data":'.json_encode(array_values($array)).'}';
            die();
        }



        if (isset($_GET['action'])&& $_GET['action']=='efficiency'){
            $years = $GLOBALS['esstCase']->getYears(); 
            $elmix_fuels = $GLOBALS['esstCase']->getElMixFuels();
            foreach ($elmix_fuels as $key=>$value) {
                if($key != 'ImportExport'){
                    //moramo definisati varijable goriva (iz fileove lang ukljucenih iznad) )kao globalne da bi ih mogli koristit u ovoj funkciji
                    global $$key;
                    if ($value=="1") {                 
                        foreach($years as $key1=>$value1){
                            if ($value1=="1"){   
                            $tmp = substr($key1, 1);
                            $$key1 = $GLOBALS['esstCase']->getEfficiencyByYearFuel($tmp, $key); 
                            }
                            else{
                                $$key1 = '';
                            }
                        }  
                        $trans[] = array(
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
            }
            $array = array_filter($trans);
       	    echo '{"data":'.json_encode(array_values($array)).'}';
            die();
        }


        if (isset($_GET['action'])&& $_GET['action']=='reserveCapacity'){
            $years = $GLOBALS['esstCase']->getYears(); 
            $elmix_fuels = $GLOBALS['esstCase']->getElMixFuels();
            foreach ($elmix_fuels as $key=>$value) {
                if($key!='ImportExport' && $key!='Hydro' && $key!='Solar' && $key!='Wind' && $key!='Nuclear' && $key!='Geothermal' && $key!='Biofuels' && $key!='Wind' && $key!='Peat' && $key!='Waste'){
                //if($key != 'ImportExport'){
                    //moramo definisati varijable goriva (iz fileove lang ukljucenih iznad) )kao globalne da bi ih mogli koristit u ovoj funkciji
                    global $$key;
                    if ($value=="1") {                 
                        foreach($years as $key1=>$value1){
                            if ($value1=="1"){   
                            $tmp = substr($key1, 1);
                            $$key1 = $GLOBALS['esstCase']->getReserveCapacityByYearFuel($tmp, $key); 
                            }
                            else{
                                $$key1 = '';
                            }
                        }  
                        $trans[] = array(
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
            }
            $array = array_filter($trans);
       	    echo '{"data":'.json_encode(array_values($array)).'}';
            die();
        }

        if (isset($_GET['action'])&& $_GET['action']=='reserveCapacityTotal'){
            $years = $GLOBALS['esstCase']->getYears(); 
            //$elmix_fuels = $GLOBALS['esstCase']->getElMixFuels();
           // foreach ($elmix_fuels as $key=>$value) {
               // if($key!='ImportExport' && $key!='Hydro' && $key!='Solar' && $key!='Wind' && $key!='Nuclear' && $key!='Geothermal' && $key!='Biofuels' && $key!='Wind' && $key!='Peat' && $key!='Waste'){
                //if($key != 'ImportExport'){
                    //moramo definisati varijable goriva (iz fileove lang ukljucenih iznad) )kao globalne da bi ih mogli koristit u ovoj funkciji
                    //global $$key;
                    //if ($value=="1") {                 
                        foreach($years as $key1=>$value1){
                            if ($value1=="1"){   
                            $tmp = substr($key1, 1);
                            $$key1 = $GLOBALS['esstCase']->getReserveCapacityTotalByYear($tmp); 
                            }
                            else{
                                $$key1 = '';
                            }
                        }  
                        $trans[] = array(
                            'name' => "Total Reserve Capacity",
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
                    //}
                    
                //}
            //}
            $array = array_filter($trans);
       	    echo '{"data":'.json_encode(array_values($array)).'}';
            die();
        }

////////////////////carbon cost
if (isset($_GET['action'])&& $_GET['action']=='carbonCost'){
    $carbonCosts = $GLOBALS['esstCase']->getCarbonContent();
 
    $carbonCosts['name'] = "Carbon cost";

    echo '{"data":'.json_encode($carbonCosts).'}';
    die();
}

////////////////////carbon cost
if (isset($_GET['action'])&& $_GET['action']=='discountRate'){
    $discountRate = $GLOBALS['esstCase']->getDiscountRate();
 
    $discountRate['name'] = "Discount rate";

    echo '{"data":'.json_encode($discountRate).'}';
    die();
}

}








//dio z upis podataka u xml direktno iz handsontabele
if (isset($_POST['action'])){
    try{
        if ($_POST['action']=="saveData"){ 
            $filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";
            if ($_POST){
                $fuel_array = get_elmix_fuel_array();
                //print_r($fuel_array);
                $year = $_POST['year'];
                //$fuel = $fuel_array[$_POST['fuel']];
                $fuel = $_POST['fuel'];
                $value = $_POST['value'];
                update_ses_elmix($filepath, $year, $fuel, $value);
                echo json_encode(array('msg'=>"Electricity supply structure updated!", "type"=>"SUCCESS"));
                die();
            }
        }
        if ($_POST['action']=="saveEff"){ 
            $filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";
            if ($_POST){
                $fuel_array = get_elmix_fuel_array();
                //print_r($fuel_array);
                $year = $_POST['year'];
                $fuel = $_POST['fuel'];
                //$fuel = $fuel_array[$_POST['fuel']];
                $value = $_POST['value'];
                update_efficiency($filepath, $year, $fuel, $value);
                echo json_encode(array('msg'=>"Efficiency structure updated!", "type"=>"SUCCESS"));
                die();
            }
        }

            if ($_POST['action']=="saveResCap"){ 
                $filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";
                if ($_POST){
                    $fuel_array = get_elmix_fuel_array();
                    //print_r($fuel_array);
                    $year = $_POST['year'];
                    //$fuel = $fuel_array[$_POST['fuel']];
                    $fuel = $_POST['fuel'];
                    $value = $_POST['value'];
                    update_reserve_capacity($filepath, $year, $fuel, $value);
                    echo json_encode(array('msg'=>"reserve capacity structure updated!", "type"=>"SUCCESS"));
                    die();
                }
            }
            if ($_POST['action']=="saveResCapTotal"){ 
                $filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";
                if ($_POST){
                    $fuel_array = get_elmix_fuel_array();
                    $year = $_POST['year'];
                    $value = $_POST['value'];
                    update_reserve_capacity_total($filepath, $year, $value);
                    echo json_encode(array('msg'=>"reserve capacity structure updated!", "type"=>"SUCCESS"));
                    die();
                }
            }

            if ($_POST['action']=="saveCarbonCosts"){ 
                $filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";
                if ($_POST){
                    $year = $_POST['year'];
                    $value = $_POST['value'];
                    appent_tra_carbon_content($filepath, $year, $value);
                    echo json_encode(array('msg'=>"Carbon content cost updated!", "type"=>"SUCCESS"));
                    die();
                }
            }

            if ($_POST['action']=="saveDiscountRate"){ 
                $filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";
                if ($_POST){
                    $year = $_POST['year'];
                    $value = $_POST['value'];
                    appent_tra_discount_rate($filepath, $year, $value);
                    echo json_encode(array('msg'=>"Carbon content cost updated!", "type"=>"SUCCESS"));
                    die();
                }
            }
        

    }
    catch(runtimeException $e){
		echo json_encode(array('msg'=>$e->getMessage(), "type"=>"ERROR"));
        die();
	}
}
?>
