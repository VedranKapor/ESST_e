<?php
   //  session_start();
    if (!isset($_SESSION['lang'])){
        $_SESSION['lang'] ='en';
        require ROOT_DIR.'/lang/en.php';
    }
    if (isset($_SESSION['lang']) && $_SESSION['lang']=='en')
        require ROOT_DIR.'/lang/en.php';
    if (isset($_SESSION['lang']) && $_SESSION['lang']=='fr')
        require ROOT_DIR.'/lang/fre.php';
    if (isset($_SESSION['lang']) && $_SESSION['lang']=='es')
        require ROOT_DIR.'/lang/spa.php';
    if (isset($_SESSION['lang']) && $_SESSION['lang']=='bcs')
        require ROOT_DIR.'/lang/bcs.php'; 
?>