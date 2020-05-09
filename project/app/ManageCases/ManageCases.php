<?php
require_once("../../config.php");
require_once(ROOT_DIR."/classes/paths.php");
require_once(ROOT_DIR."/classes/Case.Class.php");
require_once(ROOT_DIR."/Classes/Upload.Class.php");
require_once(ROOT_DIR."/functions/functions.php");

if (isset($_POST['action'])){
	switch($_POST['action']){
		default:
		break;	
		case 'getDescription'://get case desc
			$esstCase = new CaseManagement($_POST['case']);
			echo $esstCase->getCaseDescription();
		break;
		case 'copyCase': //update case desc
			// try{
			// 	$srcFolder = realpath(USER_CASE_PATH.$_POST['case']);
			// 	$dstFolder = USER_CASE_PATH.$_POST['case']."_copy";
			// 	recurseCopy($srcFolder,$dstFolder);
			// 	CaseManagement::updateCaseName($srcFolder, $dstFolder, $_POST['case']);
			// 	echo json_encode(array('msg'=>"Copy of case done!", "type"=>"SUCCESS"));
			// }
			// catch(runtimeException $e){
			// 	echo json_encode(array('msg'=>$ex->getMessage(), "type"=>"ERROR"));
			// }
			function reqursion($pCase, $i){
				if($i ==0){
					
					$pCasePath = USER_CASE_PATH.$pCase;
					$pCopyPath = $pCasePath."_copy";
				}
				else {
					$pCasePath = USER_CASE_PATH.$pCase;
					if (strpos($pCase, '_copy') !== false) {
						$pCopyPath = $pCasePath."(".$i.")";
					}
					else{
						$pCopyPath = $pCasePath."_copy(".$i.")";
					}
				}
				if (!is_dir($pCopyPath)){
					try{
						$srcFolder = realpath($pCasePath);
						$dstFolder = $pCopyPath;
						recurseCopy($srcFolder,$dstFolder);
						CaseManagement::updateCaseName($srcFolder, $dstFolder, $pCase);
						//NestCase::updateCaseName(basename($pCopyPath));
						echo json_encode(array('msg'=>"Copy of case done!", "type"=>"SUCCESS"));
					}
					catch(runtimeException $e){
						echo json_encode(array('msg'=>$ex->getMessage(), "type"=>"ERROR"));
					}
				}
				else{
					$i++;
					reqursion($pCase, $i);
				}
			}
			$i = 0;
			reqursion($_POST['case'], $i);
		break;
		case 'deleteCase': //update case desc
			try{
				deleteDir(USER_CASE_PATH.$_POST['case']);
				if(isset($_SESSION['case']) && $_SESSION['case'] == $_POST['case'])
					unset($_SESSION['case']);
				echo json_encode(array('msg'=>"Delete success!", "type"=>"SUCCESS"));
			}
			catch(runtimeException $e){
				echo json_encode(array('msg'=>$ex->getMessage(), "type"=>"ERROR"));
			}
		break;

		case 'backupCase': //update case desc
			 CaseManagement::backupCase($_POST['case']);
			 echo json_encode(array('zip' => $_POST['case']));
		break;
		case 'cleanCases': //update case desc
			$files = scandir(USER_CASE_PATH);
			foreach ($files as $file) {
				if (preg_match('/.+\.zip$/', $file)) {
					unlink(USER_CASE_PATH.$file);
				}
			}
		break;
		case 'updateCaseDescription': //update case desc
			$desc=$_POST['desc'];
			CaseManagement::updateCaseDescription($desc,$_POST['case']);
			echo json_encode("Update of NEST case description done!") ;
		break;
		case 'updatePlanningStudy': //update case desc
		try{
			$desc=$_POST['desc'];
			$pCase = $_POST['case'];
			$pCaseNew = $_POST['caseNew'];
			CaseManagement::updatePlanningStudy($pCase, $pCaseNew, $desc);
			echo json_encode(array('msg'=>"Update of NEST case done!", "type"=>"SUCCESS"));
			}
			catch(runtimeException $e){
				echo json_encode(array('msg'=>$ex->getMessage(), "type"=>"ERROR"));
			}
		break;
		case 'downloadCase': //update case desc
			header("Location: ../../Storage/".$_GET['case'].".zip");
			echo json_encode($_GET['case']);
			exit;
		break;
		case 'createScenario': //create scenario
			$pCase = $_POST['case'];
			$pScenario = $_POST['scenario'];
			$pCopy = $_POST['pCopy'];
			$pScenarioCopy = $_POST['pScenarioCopy'];
			echo CaseManagement::createScenario($pCase, $pScenario, $pCopy, $pScenarioCopy);
		break;
	}
}
if(isset($_FILES) && !empty($_FILES)){
	try{
	    $upload = new Upload('fileToUpload', 'ZIP', USER_CASE_PATH,'esstRestore' );
	    $msg = $upload->uploaded();	
		echo json_encode(array('msg'=>"Upload success!", "type"=>"SUCCESS"));
	}  
	catch (RuntimeException $ex){
		echo json_encode(array('msg'=>$ex->getMessage(), "type"=>"ERROR"));
	}
}

//print_r($_FILES);
// if(isset($_FILES) && !empty($_FILES)){
// 	try{
// 	    $upload = new Upload('fileToUpload', 'ZIP', USER_CASE_PATH,'esstRestore' );
// 	    $msg = $upload->uploaded();	
// 		echo json_encode(array('msg'=>"Upload success!", "type"=>"SUCCESS"));
// 	}  
// 	catch (RuntimeException $ex){
// 		echo json_encode(array('msg'=>$ex->getMessage(), "type"=>"ERROR"));
// 	}
// }
?>