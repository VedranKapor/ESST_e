<?php
require_once '../functions/xml_functions.php';
//require_once '../functions/calc_functions.php';
require_once '../config.php';
require_once '../classes/EsstCase.php';
require_once '../includes/session.php';

$filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";

if (file_exists($filepath)){   
   $xml = simplexml_load_file($filepath)
      or die("Error: Cannot create object");
   $esstCase = new EsstCase($_SESSION['case'], $xml);

  //  function display(){
        $esstCase = new EsstCase($_SESSION['case'], $xml);  
        $orders = array();
        $years = $esstCase->getYears();            
        $fuels = $esstCase->getFuels(); 

        $fuel_array = get_fuel_array();

         if (isset($_GET['trans'])&& $_GET['trans']==0) {
            $i=0;
       	    foreach ($years as $key=>$value) {
                if($value =='1') {
                    $tmp = substr($key, 1);
                     foreach ($fuels as $key3=>$value3)
                     {    
                        if ($value3=="1")
                        {
                            //$$key3 = get_losses_value($key3, $tmp);  
                             $$key3 = $esstCase->getFedLosses($tmp, $key3);                         
                        }
                     }         
                     //dinamickio kreiranje reda za json (bez obzira koja su goriva odabrane)
                    $flag = false;
                    foreach ($fuels as $key2=>$value2) {
                        if ($value2=="1") {
                            if(!$flag) {
                                $orders[$i]['year'] = $tmp;
                                $flag = true;
                            }
                            $orders[$i][$key2] = $$key2;
                        }
                        else
                            $orders[$i][$key2] = '';
                    }                                                
                }
                $i+=1;
            }
            $array1=array_filter($orders);
            // $array1 = array_filter(array_map('array_filter', $array1));
            echo '{"data":'.json_encode(array_values($array1)).'}';
            die();
         }
        
         if (isset($_GET['trans'])&& $_GET['trans']==1)
        {
            // $years = $GLOBALS['esstCase']->getYears(); 
            // $fuels = $GLOBALS['esstCase']->getFuels();            
            //$sector = $_GET['sector'];
            $i=0;
            foreach ($fuels as $key=>$value)
            {
                global $$key;
               if ($value=="1")
               {                 
                foreach($years as $key1=>$value1)
                {
                   if ($value1=="1")
                   {   
                       $tmp = substr($key1, 1);
                       //$$key1 = $GLOBALS[xml]->xpath("//fed_losses[@year='{$tmp}']/{$key}");
                       $$key1 = $esstCase->getFedLosses($tmp, $key);
                          
                   }
                 } 
                          //dinamickio kreiranje reda za json (bez obzira koje su godine odabrane)
                 $flag = false;
                 foreach ($years as $key2=>$value2)
                 {
                    $tmp = substr($key2, 1);
                    if ($value2=="1")
                    {
                        if(!$flag) 
                        {
                            $trans[$i]['name'] = $$key;
                            $flag = true;
                        }
                        $trans[$i][$tmp] = $$key2;
                    }
                    else
                        $trans[$i][$tmp] = '';
                 } 
      //          	   $trans[] = array(
//                       'name' => $$key,
//                       '1990' => (string)$_1990[0],
//                       '2000' => (string)$_2000[0],
//                       '2005' => (string)$_2005[0],
//                       '2010' => (string)$_2010[0],
//                       '2015' => (string)$_2015[0],
//                       '2020' => (string)$_2020[0],
//                       '2025' => (string)$_2025[0],
//                       '2030' => (string)$_2030[0],
//                       '2035' => (string)$_2035[0],
//                       '2040' => (string)$_2040[0],
//                       '2045' => (string)$_2045[0],
//                       '2050' => (string)$_2050[0],
//                        );
                }
                $i+=1;
                
            }
        $array = array_filter($trans);
        //$array = array_filter(array_map('array_filter', $array));
       	echo '{"data":'.json_encode(array_values($array)).'}';
        }

   // }
}
//dio z upis podataka u xml direktno iz handsontabele
if (isset($_POST['action']))
{
    try{
    if ($_POST['action']=="saveData")
    {		  
          //$filepath = "../xml/".$_SESSION['case']."/".$_SESSION['case'].".xml";
          //$xml = simplexml_load_file($filepath)
          //or die("Error: Cannot create object");
        $fuel_array = get_fuel_array();
		if ($_POST) 
        {
            $year = $_POST['year'];
            $fuel = $fuel_array[$_POST['fuel']];
            $value = $_POST['value'];
            update_fed_losses($filepath, $year, $fuel, $value);
            echo json_encode(array('msg'=>"Losses updated!", "type"=>"SUCCESS"));
            die();
		}
        //display();   
    }
    }
    catch(runtimeException $e){
		echo json_encode(array('msg'=>$e->getMessage(), "type"=>"ERROR"));
        //echo 'greska!';
        die();
	}
}

// if (!isset($_POST['action']))
// {
//     display();
// }

?>
