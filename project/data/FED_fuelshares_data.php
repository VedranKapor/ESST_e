<?php
require_once '../functions/xml_functions.php';
require_once '../config.php';
require_once '../classes/EsstCase.php';
require_once '../includes/session.php';


$filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";
if (file_exists($filepath))
{
    $xml = simplexml_load_file($filepath)
        or die("Error: Cannot create object");
    $esstCase = new EsstCase($_SESSION['case'], $xml);

  //  function display(){   
        $orders = array();
        $trans = array();
        
        $sectors = $esstCase->getSectors();
        $years = $esstCase->getYears();            
        $fuels = $esstCase->getFuels(); 

        // $sectors = $GLOBALS['esstCase']->getSectors();
        // $years = $GLOBALS['esstCase']->getYears(); 
        // $fuels = $GLOBALS['esstCase']->getFuels(); 
        
        if (isset($_GET['trans'])&& $_GET['trans']==0)
          {
            $i = 0;
            //global $$key;
            foreach($years as $key1=>$value1)
            {
                if ($value1 =="1")
                {
                    $tmp = substr($key1, 1);
                    foreach($fuels as $key3=>$value3)
                    {
                        if ($value3=="1")
                        {
                            $$key3 = $GLOBALS['esstCase']->getFedFuelShare($tmp, $_GET['sector'], $key3);
                        }
                    }

                    //dinamickio kreiranje reda za json (bez obzira koja su goriva odabrane)
                    $flag = false;
                    foreach ($fuels as $key2=>$value2)
                    {
                        if ($value2=="1")
                        {
                            if(!$flag) 
                            {
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
           //$array1 = array_filter(array_map('array_filter', $array1));
	    	echo '{"data":'.json_encode(array_values($array1)).'}';
            die();
            
        }      
        if (isset($_GET['trans'])&& $_GET['trans']==1)
        {
            //$fuels = get_fuels($filepath);
            //$years = get_years($filepath);
            $sector = $_GET['sector'];
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
                       //$$key1 =$GLOBALS[xml]->xpath("//fed_fuelshares[@year='{$tmp}'and @sector='{$sector}']/{$key}");   
                       $$key1 = $GLOBALS['esstCase']->getFedFuelShare($tmp,$sector,$key);                         
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
                }
                $i+=1;                
            }
        $array = array_filter($trans);
       // $array = array_filter(array_map('array_filter', $array));
       	echo '{"data":'.json_encode(array_values($array)).'}';
           die();
        }

   // }
}
//dio z upis podataka u xml direktno iz handsontabele
if (isset($_POST['action'])){
if ($_POST['action']=="saveData"){
    try{
            $filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";
			if ($_POST) {
            //	 echo $_POST['changes'][0]['year'];
            //   echo $_POST['changes'][0]['sector'];
            //   echo $_POST['changes'][0]['value'];
            // print_r($_POST);
                $fuel_array = get_fuel_array();
                $sector = $_GET['sector'];
                $year = $_POST['year'];
                $fuel = $fuel_array[$_POST['fuel']];
                $value = $_POST['value'];
                update_fed_fuelshares($filepath, $year,$sector, $fuel, $value);
                echo json_encode(array('msg'=>"Final energy demand, fuel shares updated!", "type"=>"SUCCESS"));
                die();
		    }
        }
        catch(runtimeException $e){
				echo json_encode(array('msg'=>$e->getMessage(), "type"=>"ERROR"));
                //echo 'greska!';
                die();
		}
  //  display();
    }
}



// if (!isset($_POST['action'])){
//     display(); 
// }

?>
