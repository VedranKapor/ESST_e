<?php
//require_once('../log4php/Logger.php');
//define('ROOT_DIR','C:\wamp\www\ESST\esst.ver.2.3.5');
class CaseManagement {
    public $xml;    

	public function __construct($pCase){
    //$this->logger = Logger::getLogger(get_class($this));
	//	$this->xml = $pXml;
   	    if (!isset($this->xml)) {
            //$filepath = XML.$pCase."/".$pCase.".xml";
            $filepath = USER_CASE_PATH.$pCase."/".$pCase.".xml";
            if (file_exists($filepath)){
                $this->xml = simplexml_load_file($filepath)
                or die("Error: Cannot create object");
            }
        }
	}

    //return all case studies in array form
	public static function getAllCaseStudies(){
		if ($handle = opendir(USER_CASE_PATH)) {
			$caseStudies = array();
            $i=0;
			while (false !== ($file = readdir($handle))) {
				if($file != '.' && $file != '..' && is_dir(USER_CASE_PATH.$file)){
    				$caseStudies[$i]['title'] = $file;
                    $caseStudies[$i]['type'] = 'ps';  
                    $caseStudies[$i]['desc'] =  (string)simplexml_load_file( USER_CASE_PATH.$file."/".$file.".xml")->Case->description;  
                    $i++;                
				}  
			}
			return $caseStudies;
			closedir($handle);
		}
		return false;
    }

        //update planning study description
        // public static function updateCaseName($src, $dst, $pCase){
        //     $getDataurl = $dst."/" .$pCase.'.xml';
        //     $saveUrl = $dst."/" .$pCase.'_copy.xml';    
        //     if (file_exists($getDataurl)){
        //         $xml = simplexml_load_file($getDataurl);
        //         $xml->Case[0]->name = $_POST['case']."_copy";
        //         $xml->asXML($getDataurl);
        //         rename($getDataurl, $saveUrl);
        //     }
        //     return true; 
        // }
    

    //update planning study description
   	public static function updateCaseName($src, $dst, $pCase){
        
        

        $getDataurl = $dst."/" .$pCase.'.xml';

        $newCaseName = basename($dst);
        $newFileName = $newCaseName.".xml";

        $saveUrl = $dst."/" .$newFileName;

        if (file_exists($getDataurl)){
            $xml = simplexml_load_file($getDataurl);
            $xml->Case[0]->name = $newCaseName;
            $xml->asXML($getDataurl);
            rename($getDataurl, $saveUrl);
        }
		return true; 
    }

    //nest
    //        //update planning study description
    //        public static function updateCaseName($pCase){
    //         $getDataurl = STORAGE.$pCase."/genData.json";
    //         if (file_exists($getDataurl)){
    //             $getDataFile = file_get_contents( $getDataurl );
    //             $retval = json_decode($getDataFile, true);
    //             $retval['general']['casename'] = $pCase;
    //             $fp = fopen($getDataurl, 'w');
    //             fwrite($fp, json_encode($retval, JSON_PRETTY_PRINT));
    //             fclose($fp);
    //         }
    //         return true;
    //    }

    //get case description
    public function getCaseDescription() {
		$this->description = $this->xml->Case->description;           
		return $this->description;
	}

    //backup planning study 
   	public static function backupCase($pCase){
        if(isset($pCase)){
            // Get real path for our folder
            $rootPath = realpath(USER_CASE_PATH.$pCase);

            // Initialize archive object
            $zip = new ZipArchive();
            $zip->open($rootPath.'.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);
            //$zip->open($rootPath.'.zip', ZipArchive::CREATE);

            // Create recursive directory iterator
            /** @var SplFileInfo[] $files */
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($rootPath),
                RecursiveIteratorIterator::LEAVES_ONLY
            );
            foreach ($files as $name => $file){
                // Skip directories (they would be added automatically)
                if (!$file->isDir()){
                    // Get real and relative path for current file
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($rootPath) + 1);
                    // Add current file to archive
                    //$zip->addFile($filePath, $relativePath);
                    $zip->addFile($filePath, $pCase. '/'. $relativePath);
                }
            }
            // Zip archive will be created only after closing object
            $zip->close();
        }
    }   
}
?>