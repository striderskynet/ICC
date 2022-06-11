<?php
    define("_LOCAL", $_SERVER['DOCUMENT_ROOT'] . "/");

    define("_VERSION", "0.1.0-alpha");
    define("_COMMIT", "2022-06-08T04:51:59Z");
    define("_DEBUG", true);


    $config['title'] = "Endirecto";
    $_ADDRESS = "http://clients.technomobile.lan:85/";
    $_INSTALLED = true;


    $config['db']['host'] = "127.0.0.1";
    $config['db']['user'] = "root";
    $config['db']['pass'] = "";
    $config['db']['data'] = "icc_endirecto";

    $config['misc']['pagination'] = 10;

    
    // DONT TOUCH BELOW THIS LINE
    require_once ( _LOCAL . "core/misc.php" );
    require_once ( _LOCAL . "core/debug.php" );
    require_once ( _LOCAL . "core/class/mysql.php" );
    
?>