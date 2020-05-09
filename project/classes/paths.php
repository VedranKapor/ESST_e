<?php
error_reporting(E_ERROR);
//physical paths
define("DOC_ROOT_PATH",             $_SERVER['DOCUMENT_ROOT']."/".ROOT_DIR);
define("CLASSES",                   ROOT_DIR."/classes/");
define("XML",                       ROOT_DIR."/xml/");
// define("PHASE2",                    ROOT_DIR."/phase2/");
// define("PHASE2_DATA",               ROOT_DIR.PHASE2."/data/");

//logical paths
// define("HTTP_ROOT", "http://".$_SERVER['HTTP_HOST']."/".ROOT_DIR);
// define("CSS_PATH",  HTTP_ROOT."css/");
// define("LANG_PATH", HTTP_ROOT."languages/");
// define("DATA_PATH", HTTP_ROOT."data/projects/");
?>