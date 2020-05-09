<?php
    require_once '../functions/xml_functions.php';
   // require_once '../functions/calc_functions.php';
    require_once '../config.php';
    require_once '../classes/EsstCase.php';
    require_once '../includes/session.php';
	
    $filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";
    $orders = array();
    if (file_exists($filepath)) {

        $xml = simplexml_load_file($filepath)
          or die("Error: Cannot create object");
        $esstCase = new EsstCase($_SESSION['case'], $xml);  
          
        $sectors = $esstCase->getSectors();
        $years = $esstCase->getYears(); 
        $fuels = $esstCase->getFuels();

        $sector = $_GET['sector'];

        if (!isset($_GET['trans'])){
            $i=0;
            foreach ($years as $key=>$value) {
                $year = substr($key, 1);
                if($value == '1'){
                    $flag = false;
                    foreach ($fuels as $key3=>$value3) {
                        if ($value3=="1") {
                            if(!$flag) {
                                $orders[$i]['year'] = $year;
                                $flag = true;
                            }
                            $orders[$i][$key3] = $esstCase->getTFC($key3, $year, $sector);
                        }
                        else
                            $orders[$i][$key3] = '';
                    }
                }
                $i+=1;
            }
            $array1=array_filter($orders);
            //$array1 = array_filter(array_map('array_filter', $array1));
            echo '{"data":'.json_encode(array_values($array1)).'}';  
        }   
        
        //data za drugi graf i tabelu po godinama
        if (isset($_GET['trans'])&& $_GET['trans']==1) {
            $i=0;               
        	foreach($fuels as $key1=>$value1) {
                if ($value1=="1") {
                    $fuel = $key1;
                    $flag = false;
                    foreach ($years as $key=>$value) {    
                        $year = substr($key, 1);     
                        if ($value=="1"){
                            if(!$flag) {
                                $trans[$i]['name'] = $$fuel;
                                $flag = true;
                            }
                            $trans[$i][$year]  = $esstCase->getTFC($fuel, $year, $sector);
                        }   
                        else
                            $trans[$i][$year] = '';                
                    }
                } 
                $i++; 
            }  
            $array = array_filter($trans);
        	echo '{"data":'.json_encode(array_values($array)).'}';
        }
    }
?>
