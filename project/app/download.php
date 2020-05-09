<?php
    if(isset($_GET['case'])){
        if(isset($_SESSION['user'])){
            header("Location: ../xml/". $_SESSION['user']."/".$_GET['case'].".zip");
        }
        else{
            header("Location: ../xml/admin/".$_GET['case'].".zip");
        }
        //header("Location:". USER_CASE_PATH.$_GET['case'].".zip");
        echo json_encode($_GET['case']);
        exit;
    }
?>