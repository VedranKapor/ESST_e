<?php
require_once("../../config.php");
require_once(ROOT_DIR."/classes/paths.php");

if (isset($_POST['action'])){
	switch($_POST['action']){
		default:
		break;	
        case 'saveTech':
            try{
                $casename = $_POST['casename'];
                $data = json_decode($_POST['data'], true);

                 
                // echo $casename . "<br>";
                // echo "<pre>";
                // print_r( $data);
                // echo "</pre>";

                
                $filepath = USER_CASE_PATH.$casename."/".$casename.".xml";

                $xml = new DOMDocument("1.0");
                $xml->formatOutput = true;
                $xml->preserveWhiteSpace = false;
                $xml->load("$filepath");    
                $xpath = new DOMXPath($xml); 

                // print_r($xml);
                // print_r($xpath);


                foreach($data as $id=>$obj){
                    
                    $year = $obj['year'];
                    $technology = $obj['technology'];

                    $Capacity_factor = $obj['Capacity_factor'];
                    $Installed_power = $obj['Installed_power'];

                    $Lifetime = $obj['Lifetime'];
                    $Construction_time = $obj['Construction_time'];

                    $CO2 = $obj['CO2'];
                    $NOX = $obj['NOX'];
                    $SO2 = $obj['SO2'];
                    $PM = $obj['PM'];
                    //$Other = $obj['Other'];

               // echo "CF " . $Capacity_factor ."</br>";
                 //echo $xpath->query('//tra_technical[@year="{$year}" and @technology="{$technology}"]/Capacity_factor'). "</br>";

                // echo "xpath " . "//tra_technical[@year='{$year}' and @technology='{$technology}']/Capacity_factor" . "<br>";

                // echo "xpath " . sprintf('//tra_technical[@year="%s" and @technology="%s"]/Capacity_factor', $year,$technology) . "<br>";

                    
                    $Fuel_cost = $obj['Fuel_cost'];
                    $Investment_cost = $obj['Investment_cost'];
                    $Operating_cost_fixed = $obj['Operating_cost_fixed'];
                    $Operating_cost_variable = $obj['Operating_cost_variable'];

                    //$xpath->query("//tra_lifetime[@technology='{$technology}']/Lifetime")->item(0)->nodeValue = $Lifetime;
                    $xpath->query("//construction_time[@year='{$year}']/{$technology}")->item(0)->nodeValue = $Construction_time;
                    $xpath->query("//tra_lifetime[@year='{$year}']/{$technology}")->item(0)->nodeValue = $Lifetime;

                    $xpath->query("//tra_technical[@year='{$year}' and @technology='{$technology}']/Installed_power")->item(0)->nodeValue = $Installed_power;
                    $xpath->query("//tra_technical[@year='{$year}' and @technology='{$technology}']/Capacity_factor")->item(0)->nodeValue = $Capacity_factor;

                    $xpath->query("//tra_finance[@year='{$year}' and @technology='{$technology}']/Fuel_cost")->item(0)->nodeValue = $Fuel_cost;
                    $xpath->query("//tra_finance[@year='{$year}' and @technology='{$technology}']/Investment_cost")->item(0)->nodeValue = $Investment_cost;
                    $xpath->query("//tra_finance[@year='{$year}' and @technology='{$technology}']/Operating_cost_fixed")->item(0)->nodeValue = $Operating_cost_fixed;
                    $xpath->query("//tra_finance[@year='{$year}' and @technology='{$technology}']/Operating_cost_variable")->item(0)->nodeValue = $Operating_cost_variable;

                    $xpath->query("//tra_envirioment[@year='{$year}' and @technology='{$technology}']/CO2")->item(0)->nodeValue = $CO2;
                    $xpath->query("//tra_envirioment[@year='{$year}' and @technology='{$technology}']/NOX")->item(0)->nodeValue = $NOX;
                    $xpath->query("//tra_envirioment[@year='{$year}' and @technology='{$technology}']/SO2")->item(0)->nodeValue = $SO2;
                    $xpath->query("//tra_envirioment[@year='{$year}' and @technology='{$technology}']/Other")->item(0)->nodeValue = $PM;

                }
                file_put_contents($filepath,$xml->saveXML());
                echo json_encode(array('msg'=>"Update success!", "type"=>"SUCCESS"));
            }
            catch(runtimeException $e){
                echo json_encode(array('msg'=>$ex->getMessage(), "type"=>"ERROR"));
            }
		break;
	}
}

?>