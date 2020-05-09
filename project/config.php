<?php 
session_start();
define('ROOT_DIR', dirname(__FILE__));
define("INCLUDE_PATH", ROOT_DIR."/includes/");
define("XML_PATH", ROOT_DIR."/xml/");
if(isset($_SESSION['us'])){
    define("USER_CASE_PATH", ROOT_DIR."/xml/". $_SESSION['us']."/" );
}
else{
    $_SESSION['us'] = 'admin';
    define("USER_CASE_PATH", ROOT_DIR."/xml/admin/" );
}
?>