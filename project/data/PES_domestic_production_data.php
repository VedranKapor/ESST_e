<?php
require_once '../config.php';
require_once '../functions/xml_functions.php';

require_once '../classes/EsstCase.php';
require_once '../includes/session.php';


$filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";

if (file_exists($filepath)){    
    $xml = simplexml_load_file($filepath)
        or die("Error: Cannot create object");
    $esstCase = new EsstCase($_SESSION['case'], $xml);    
        
    //function display(){
        $orders = array();
        //$years = get_years($filepath);
        //$fuels = get_fuels($filepath);
        $years = $GLOBALS['esstCase']->getYears(); 
        $fuels = $GLOBALS['esstCase']->getFuels();
        
         if (isset($_GET['trans'])&& $_GET['trans']==0){
         foreach($years as $key1=>$value1){
            if ($value1 == "1")
            {
             $tmp = substr($key1, 1);
             foreach($fuels as $key=>$value)
             {
                
               if ($value == "1" && $key!= 'Electricity' && $key!='Heat')
               {
                //$$key = $GLOBALS[xml]->xpath("//pes_domestic_production[@year='{$tmp}']/{$key}");
                $$key = $GLOBALS['esstCase']->getDomProduction($tmp,$key );
               }
               else
                $$key = '';
             }
               	    $orders[] = array(
        			//'year' => (int)$data->attributes()->year,
                    'year' => $tmp,
        			'Coal' => $Coal,
        			'Oil' => $Oil,
        			'Gas' => $Gas,
        			'Biofuels' => $Biofuels,
                    'Peat' => $Peat,
                    'Waste' => $Waste,
                    'OilShale' => $OilShale,
        			);
            }    
          }
             //$array = array_filter($orders);
    	     echo '{"data":'.json_encode($orders).'}';
             die();
          
         }
                               
         if (isset($_GET['trans'])&& $_GET['trans']==1){            
            $i=0;
        	 foreach($fuels as $key=>$value) {
               global $$key;
               if ($value=="1" && $key!= 'Electricity' && $key!='Heat'){
                foreach($years as $key1=>$value1){
                    if ($value1 =="1"){
                        $tmp = substr($key1, 1);
                        $$key1 = $GLOBALS['esstCase']->getDomProduction($tmp,$key);
                    }
                }
                //dinamickio kreiranje reda za json (bez obzira koje su godine odabrane)
                 $flag = false;
                 foreach ($years as $key2=>$value2) {
                    $tmp = substr($key2, 1);
                    if ($value2=="1"){
                        if(!$flag) {
                            $trans[$i]['name'] = $$key;
                            $flag = true;
                        }
                        $trans[$i][$tmp] = $$key2;
                    }
                 }
   //     	   $trans[] = array(
//               'name' => $$key,
//               '1990' => $_1990,
//               '2000' => $_2000,
//               '2005' => $_2005,
//               '2010' => $_2010,
//               '2015' => $_2015,
//               '2020' => $_2020,
//               '2025' => $_2025,
//               '2030' => $_2030,
//               '2035' => $_2035,
//               '2040' => $_2040,
//               '2045' => $_2045,
//               '2050' => $_2050,
//                );
                }   
                $i+=1;   
            }
            
            $array = array_filter($trans);
            
            // echo '{"data":'.json_encode($trans).'}';
            //print_r(array_values($array))."<br>";
            //$array = array_filter(array_map('array_filter', $array));
             //print_r($trans)."<br>";
            // print_r($array);
	    	echo '{"data":'.json_encode(array_values($array)).'}';
            die();
        }
    //}
 }
     

//dio z upis podataka u xml direktno iz handsontabele
if (isset($_POST['action'])){

    try{
        if ($_POST['action']=="saveData"){	
            $filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";
            if ($_POST){
                $fuel_array = get_dom_prod_fuel_array();
               //print_r($fuel_array);
                $year = $_POST['year'];
                $fuel = $fuel_array[$_POST['fuel']];
                $value = $_POST['value'];
                update_pes_domestic_production($filepath, $year, $fuel, $value);  
                echo json_encode(array('msg'=>"Domestic production updated!", "type"=>"SUCCESS"));
                die();			
            }       
        }
    }
    catch(runtimeException $e){
		echo json_encode(array('msg'=>$e->getMessage(), "type"=>"ERROR"));
        //echo 'greska!';
        die();
	}
}



// if (!isset($_POST['action'])){
    
//     display();
    
// }

?>
