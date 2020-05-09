<?php
require_once("../../config.php");
require_once("../login/constants.php");
require_once("../login/validate.php");
require_once("../login/pbkdf2.php");
require_once(ROOT_DIR."/functions/functions.php");
$url= '../us.json';

if (isset($_POST['action'])){
	switch($_POST['action']){
		default:
		break;	
        // case 'getUsers':
        //     try{
        //         $users=array();
        //         $url= '../us.json';
        //         $file = (file_get_contents($url));
        //         $users=json_decode($file, true);
		// 	}
		// 	catch(runtimeException $e){
		// 		echo json_encode(array('msg'=>$ex->getMessage(), "type"=>"ERROR"));
		// 	}
		// break;
		case 'getAccess': //update case desc
			try{
                //samo admin moze manage users
                if (isset($_SESSION['us']) && isset($_SESSION['gr']) && $_SESSION['gr'] == 'admin'){
                    //echo $_SESSION['us'].'|'.$_SESSION['gr'];
                    echo json_encode(array('msg'=>'Admin', "type"=>"ADMINACCESS"));
                }
                else if (isset($_SESSION['us']) && isset($_SESSION['gr'])){
                    //echo $_SESSION['us'].'|'.$_SESSION['gr'];
                    echo json_encode(array('msg'=>'User', "type"=>"USERACCESS"));
                }
                else{
                    //echo "-1";
                    echo json_encode(array('msg'=>'accesss denied!', "type"=>"ERROR"));
                    die();
                }
			}
			catch(runtimeException $e){
				echo json_encode(array('msg'=>$ex->getMessage(), "type"=>"ERROR"));
			}
		break;
		case 'changePassword': //update case desc
			try{
                $error='';
                $valid=true;

                if($_POST['userID'] == 'null'){
                    $username=$_SESSION['us'];
                }
                else{
                    $username=$_POST['userID'];
                }

                $currentpassword=$_POST['currentpassword'];
                $newpassword=$_POST['newpassword'];
                $users = getUsers(); 
            
                //checks
                if ($currentpassword==$newpassword) {
                    $valid=false;
                    //$error="New password is the same like current<br/>";
                    throw new Exception(json_encode(array('msg'=>'New password is the same as current!', "type"=>"ERRORNew")));
                }
            
                //find user
                $currentpass=false;
                for ($i=0; $i<count($users); $i++) {
                    if ($username==$users[$i]["username"]) {
                        $currentpass=validate_password($currentpassword, $users[$i]["password"]);
                    }
                }
            
                //current pass wrong
                if (!$currentpass) {
                    $valid=false;
                    throw new Exception(json_encode(array('msg'=>'Current password is wrong!', "type"=>"ERRORCurrent")));
                    //$error.="Current password is wrong<br/>";
                }
            
                //new pass is valid
                $newpassvalid=valid_pass($newpassword);
                if ($newpassvalid!==true) {
                    $valid=false;
                    //$error.=$newpassvalid;
                    throw new Exception(json_encode(array('msg'=>$newpassvalid, "type"=>"ERRORNew")));
                }
            
                if ($valid) {
                    for ($i=0; $i<count($users); $i++) {
                        if ($username==$users[$i]["username"]) {
                            $users[$i]["password"]=create_hash($newpassword);
                        }
                    }
            
                    $us= $jsondata = json_encode($users, true);
                    if (file_put_contents($url, $us)) {
                        //echo "success";
                        echo json_encode(array('msg'=>'Password has been changed!', "type"=>"SUCCESS"));
                    }
                } else {
                    //echo $error;
                    echo json_encode(array('msg'=>$error, "type"=>"ERROR"));
                }
			}
			catch(Exception $e){
                //echo json_encode(array('msg'=>$ex->getMessage(), "type"=>"ERROR"));
                echo $e->getMessage();
			}
		break;
		case 'addUser': //update case desc
            try{
                $valid=true;
                $error='';
                $username=$_POST['username'];
                $password=$_POST['password'];
                $group=$_POST['usergroup'];
                $users = getUsers(); 
            
                //check if user exist
                $userexist=false;
                for ($i=0; $i<count($users); $i++) {
                    if ($username==$users[$i]["username"]) {
                        $userexist=true;
                        //$error.='Username alredy exist';
                        throw new Exception(json_encode(array('msg'=>'Username alredy exist!', "type"=>"ERRORCurrent")));
                    }
                }
            
                if ($userexist) {
                    $valid=false;
                }
            
                //check password policy
                $passvalid=valid_pass($password);
                if ($passvalid!==true) {
                    $valid=false;
                    //$error.=$passvalid;
                    throw new Exception(json_encode(array('msg'=>$passvalid, "type"=>"ERRORNew")));
                } else {
                    $password=create_hash($password);
                }
                if ($valid) {
                    $users[]=array("username"=>$username, "password"=>$password, "usergroup"=>$group);
                    
                    $us= json_encode($users, true);
                    if (file_put_contents($url, $us)) {
                        // echo "success";
                        echo json_encode(array('msg'=>'User added!', "type"=>"SUCCESS"));
                    }
                } else {
                    //echo $error;
                    echo json_encode(array('msg'=>$error, "type"=>"ERROR"));
                }

            }
            catch(Exception $e){
                //echo json_encode(array('msg'=>$ex->getMessage(), "type"=>"ERROR"));
                echo $e->getMessage();
            }
        break;
        case 'deleteUser':
            try{
                $username=$_POST['username'];
                $users = getUsers(); 
                for ($i=0; $i<count($users); $i++) {
                    if ($username==$users[$i]["username"]) {
                        unset($users[$i]);
                    }
                }

                $us = json_encode(array_values($users), true);
                if(file_exists(XML_PATH.$username))
                    deleteDir(XML_PATH.$username);
                if (file_put_contents($url, $us)) {
                    echo json_encode(array('msg'=>"User deleted!", "type"=>"SUCCESS"));
                }
            }
            catch(runtimeException $e){
                echo json_encode(array('msg'=>$ex->getMessage(), "type"=>"ERROR"));
            }
        break;
	}
}



function getUsers(){
    $url= '../us.json';
    $users=array();
    $file = (file_get_contents($url));
    $users=json_decode($file, true);
    return $users;
}

//create hash
function create_hash($password)
{
    // format: algorithm:iterations:salt:hash
    $salt = base64_encode(mcrypt_create_iv(PBKDF2_SALT_BYTES, MCRYPT_DEV_URANDOM));
    return PBKDF2_HASH_ALGORITHM . ":" . PBKDF2_ITERATIONS . ":" .  $salt . ":" .
        base64_encode(pbkdf2(
            PBKDF2_HASH_ALGORITHM,
            $password,
            $salt,
            PBKDF2_ITERATIONS,
            PBKDF2_HASH_BYTES,
            true
        ));
}

function valid_pass($candidate)
{
    $r1='/[A-Z]/';  //Uppercase
    $r2='/[a-z]/';  //lowercase
    $r3='/[!@#$%^&*()\-_=+{};:,<.>]/';  // whatever you mean by 'special char'
    $r4='/[0-9]/';  //numbers
 
    if (preg_match_all($r1, $candidate, $o)<1) {
        return 'Minimum one uppercase';
    }
 
    if (preg_match_all($r2, $candidate, $o)<1) {
        return 'Minimum one lowercase';
    }
 
    if (preg_match_all($r3, $candidate, $o)<1) {
        return 'Minimum on special character !@#$%^&*()\-_=+{};:,<.>';
    }
 
    if (preg_match_all($r4, $candidate, $o)<1) {
        return 'Minimum one number';
    }
 
    if (strlen($candidate)<8) {
        return 'Minimum length 8';
    }
 
    return true;
}
