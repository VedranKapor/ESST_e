<?php  
require_once("../config.php");
require_once(ROOT_DIR."/classes/paths.php");
require_once(ROOT_DIR."/classes/Case.Class.php");
require_once(ROOT_DIR.'/classes/EsstCase.php');

if (isset($_POST['action'])){
	//session_start();
	switch($_POST['action']){
		default:
		break;	
		case 'getGenData': //update case desc
			try{
				$filepath = USER_CASE_PATH.$_POST['case']."/".$_POST['case'].".xml";
				$xml = simplexml_load_file($filepath)
					or die("Error: Cannot create object");
				$esstCase = new EsstCase($_POST['case'], $xml);
				echo json_encode(array('msg'=>"Get data success!", "data"=> $xml,"type"=>"SUCCESS"));
			}
			catch(runtimeException $e){
				echo json_encode(array('msg'=>$ex->getMessage(), "type"=>"ERROR"));
			}
		break;
		case 'setSession': //update case desc
			try{
				if($_POST['session'] == 'case'){
					if(isset($_SESSION['case']))
						unset($_SESSION['case']);
					$_SESSION['case'] = $_POST['case'];
					echo json_encode(array('msg'=>"Case ".$_SESSION['case']." set success!", "type"=>"SUCCESS"));
					die();
				}
				if($_POST['session'] == 'tech'){
					if(isset($_SESSION['tech']))
						unset($_SESSION['tech']);
					$_SESSION['tech'] = $_POST['tech'];
					echo json_encode(array('msg'=>"Case ".$_SESSION['tech']." set success!", "type"=>"SUCCESS"));
					die();
				}
				if($_POST['session'] == 'sector'){
					if(isset($_SESSION['sector']))
						unset($_SESSION['sector']);
					$_SESSION['sector'] = $_POST['sector'];
					echo json_encode(array('msg'=>"Sector ".$_SESSION['sector']." is set!", "type"=>"SUCCESS"));
					die();
				}
				if($_POST['session'] == 'year'){
					if(isset($_SESSION['year']))
						unset($_SESSION['year']);
					$_SESSION['year'] = $_POST['year'];
					echo json_encode(array('msg'=>"year ".$_SESSION['year']." is set!", "type"=>"SUCCESS"));
					die();
				}
			}
			catch(runtimeException $e){
				echo json_encode(array('msg'=>$ex->getMessage(), "type"=>"ERROR"));
				die();
			}
		break;
		case 'getSession': //update case desc
			try{
    			echo json_encode($_SESSION);
			}
			catch(runtimeException $e){
				echo json_encode(array('msg'=>$ex->getMessage(), "type"=>"ERROR"));
			}
		break;
		case 'resetSession': //update case desc
		try{
			session_destroy();
			echo json_encode(array('msg'=>"Session destroyed!", "type"=>"SUCCESS"));
		}
		catch(runtimeException $e){
			echo json_encode(array('msg'=>$ex->getMessage(), "type"=>"ERROR"));
		}
		break;
		case 'getCases': //select cases
			$ps = CaseManagement::getAllCaseStudies();
			echo json_encode($ps);
		break;
	}
}
?>