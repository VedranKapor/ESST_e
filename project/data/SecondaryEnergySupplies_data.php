<?php
require_once '../functions/xml_functions.php';
require_once '../config.php';
require_once '../classes/EsstCase.php';
require_once '../includes/session.php';
//require_once '../functions/calc_functions.php';

$esstCase = new EsstCase($_SESSION['case'], $xml);
$years = $esstCase->getYears(); 
$fuels = $esstCase->getFuels();
//$sectors = $esstCase->getSectors();
           
if (isset($_GET['trans'])&& $_GET['trans']==0)
{
    foreach ($years as $key1=>$value1)
    {
        if ($value1=="1")
        {
            $tmp = substr($key1, 1);
            foreach ($fuels as $key3=>$value3)
            {                        
                if ($value3=="1")
                {
                  //$$key3 = get_ses($key3, $tmp);
                  $$key3 = $esstCase->getSES($key3, $tmp);
                }
                else 
                    $$key3 = 0;
            }
            $SES[] = array(
    			'year' => $tmp,
    			'Electricity' => $Electricity,
    			'Coal' => $Coal,
    			'Oil' => $Oil,
    			'Gas' => $Gas,
                'Biofuels' => $Biofuels,
                'Heat' => $Heat,
                'Peat' => $Peat,
                'Waste' => $Waste,
                'OilShale' => $OilShale
                
    			);
         }
    } 
    $array1=array_filter($SES);
    //$array1 = array_filter(array_map('array_filter', $array1));
	echo '{"data":'.json_encode($array1).'}';
}      


if (isset($_GET['trans'])&& $_GET['trans']==1)
{
    foreach ($fuels as $key1=>$value1)
    {
        global $$key1;
        if ($value1=="1")
        {
            foreach ($years as $key3=>$value3)
            {
                if ($value3=="1")
                {
                    $$key3=0;
                    $tmp = substr($key3, 1);
                    //$$key3 = get_ses($key1, $tmp);
                    $$key3 = $esstCase->getSES($key1, $tmp);
                }
                else
                    $$key3 = '';
            }

            $SES1[] = array(
    			'name' => $$key1,
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
    $array2=array_filter($SES1);
    // $array2 = array_filter(array_map('array_filter', $array2));
	echo '{"data":'.json_encode($array2).'}';
}

?>
