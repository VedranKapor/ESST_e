<?php
require_once '../functions/xml_functions.php';
require_once '../config.php';
require_once '../classes/EsstCase.php';
require_once '../includes/session.php';

$filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";
    
if (file_exists($filepath)){
    //load xml file potreban da bi radile funkcije za update kroz grid
    $esstCase = new EsstCase($_SESSION['case'], $xml);

  //  function display(&$esstCase)    {     
        //global $esstCase;
        $sectors = $esstCase->getSectors();
        $years = $esstCase->getYears();            
        $orders = array();
        $trans = array();
        
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////example of array created dynamicaly  
        //////	     $orders[] = array(       //u ovom nizu ce biti ""prazna vrijednost za onaj sektor koji nije izabran, kasnije ce se direktno u jqx dataadapteru sakriti ta kolona
        //    			'year' => (string)$data->attributes()->year,
        //    			'Industry' => (string)$data->Industry,
        //    			'Transport' => (string)$data->Transport ,
        //    			'Residential' => (string)$data->Residential,
        //                ....
        //                );
        //json
        //{"data":[{"year":"1990","Industry":175,"Transport":203,"Residential":112,"Commercial":87,"Agriculture":59,"Fishing":17,"Non_energy_use":7,"Other":5},....
       ///////////////////////////////////////////////////////////////////////////////
        if (isset($_GET['trans'])&& $_GET['trans']==0){
            $i = 0;
            foreach($years as $key1=>$value1){
                if ($value1 =="1") {
                    $tmp = substr($key1, 1);
                    $flag = false;
                    //dinamickio kreiranje reda za json (bez obzira koje su sektori odabrane)
                    foreach($sectors as $key=>$value) {
                        if ($value=="1") {
                            $$key = $esstCase->getFedBySector($tmp,$key); 
                            if(!$flag) {
                                $orders[$i]['year'] = $tmp;
                                $flag = true;
                            }
                            $orders[$i][$key] = $$key;
                        }
                        else
                            $orders[$i][$key] = '';
                    }
                  }
                  $i+=1;
             }

            $array1=array_filter($orders);
            //$array1 = array_filter(array_map('array_filter', $array1));
	    	echo '{"data":'.json_encode(array_values($array1)).'}';         
             die(); 
        }   
        
           
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        ////////example of array created dynamicaly         	  
            //      $trans[] = array(
            //               'name' => $$key,
            //               '1990' => $_1990,
            //               '2000' => $_2000,
            //                ....
            //                );
            //json
            //{"data":[{"name":"Industry","1990":175,"2000":280,"2010":455,"2020":735,"2030":1190,"2040":1400,"2050":1610},....
       ///////////////////////////////////////////////////////////////////////////////
        if (isset($_GET['trans'])&& $_GET['trans']==1)
        {
             $i=0;
             //dinamickio kreiranje reda za json (bez obzira koje su godine odabrane)
        	 foreach($sectors as $key=>$value)
             {
               global $$key;
               if ($value=="1")
               {
                    $flag = false;
                    foreach($years as $key1=>$value1)
                    {
                        if ($value1 =="1")
                        {
                            $tmp = substr($key1, 1);
                            $$key1 = $esstCase->getFedBySector($tmp, $key);
                            if(!$flag) 
                            {
                                $trans[$i]['name'] = $$key;
                                $flag = true;
                            }
                            $trans[$i][$tmp] = $$key1;
                        }
                    }
                }
                $i+=1;      
            }
            $array = array_filter($trans);         
            echo '{"data":'.raw_json_encode(array_values($array)).'}';
             die();
        }

  //  }
}
//dio z upis podataka u xml direktno iz handsontabele
if (isset($_POST['action'])){
    if ($_POST['action']=="saveData"){
        try{
        $filepath = USER_CASE_PATH.$_SESSION['case']."/".$_SESSION['case'].".xml";
		if ($_POST){
            $xml = simplexml_load_file($filepath)
              or die("Error: Cannot create object");
// echo "post <br>";
// echo "<pre>";
// print_r($_POST);
// echo "<pre>";

    	    //echo $_POST['changes'][0]['year'];
            //echo $_POST['changes'][0]['sector'];
            //echo $_POST['changes'][0]['value'];
            $sector_array = get_sector_array();
            $year = $_POST['year'];
            $sector = $sector_array[$_POST['sector']];
            $value = $_POST['value'];
            update_fed_bysectors($filepath, $year, $sector, $value);
		}
        //display($esstCase);  
        //echo 1;
          echo json_encode(array('msg'=>"Final energy demand updated!", "type"=>"SUCCESS"));
            die();
        }
        catch(runtimeException $e){
				echo json_encode(array('msg'=>$e->getMessage(), "type"=>"ERROR"));
                //echo 'greska!';
                die();
		}
    }
}

//print_r($_POST);
// if (!isset($_POST['action'])){
//     display($esstCase);
// }
?>
