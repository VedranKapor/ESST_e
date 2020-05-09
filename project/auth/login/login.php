<?php
/*
 * Password hashing with PBKDF2.
 * Author: havoc AT defuse.ca
 * www: https://defuse.ca/php-pbkdf2.htm
 */
require_once("../../config.php");
require_once("constants.php");
require_once("validate.php");
require_once("pbkdf2.php");
// username and password sent from form 
$username=$_POST['username'];
$password=$_POST['password'];

$users=array();

//get users
$url='../us.json';
$file = (file_get_contents($url));
$users=json_decode($file, true);
// print_r($users);
// die();

//validate
$login=false;
for ($i=0; $i<count($users); $i++) {
    if ($username==$users[$i]["username"]) {
        $login=validate_password($password, $users[$i]["password"]);
        if ($login){
            $group=$users[$i]["usergroup"];
        }
    }
}

if ($login) {
    $_SESSION['us'] = $username; 
    $_SESSION['gr'] = $group; 
    echo json_encode(array('msg'=>"Login success", "type"=>"SUCCESS"));
    //header('Location:../../esst.html');
    exit;
}else{
    echo json_encode(array('msg'=>"Incorrect username or password", "type"=>"ERROR"));
   //header('Location:../../index.html?e=1');
    exit;
}

?>